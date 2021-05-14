<?php
namespace TalkJS\Admin\Ui;

use TalkJS\Helpers\User;
use TalkJS\Helpers\Session;
use TalkJS\Helpers\Settings;
use TalkJS\Helpers\Messages;
use TalkJS\Exceptions\SettingsPageException;

class SettingsPage {


	/**
	 * SettingPAge instance data.
	 *
	 * @var Array
	 */
	protected $data;

	/**
	 * The settings fields and paragraphs
	 *
	 * @var Array
	 */
	protected $objects;


	/**
	 * Whether or not check for user capability.
	 *
	 * @var bool
	 */
	protected $check = true;

	/**
	 * The capability to check.
	 *
	 * @var string
	 */
	protected $capability;


	/**
	 * Build a settings page instance.
	 *
	 */
	public function __construct()
	{
		$this->data = array();
	}


	/**
	 * Set a new settings page.
	 *
	 * @param string $title The settings page title.
	 * @param string $slug The settings page slug name.
	 * @param array $options SettingPage extra options.
	 *
	 * @return \TalkJS\Admin\SettingPage
	 * @return object
	 */
	public function make( $title, $slug, array $options = array() )
	{

	  	$this->data['title'] = $title;
	  	$this->data['form-title'] = Settings::optionName();
	    $this->data['slug'] = $slug;
	    $this->data['options'] = $this->parseOptions($options);
	    $this->data['icon'] = $this->data['options']['icon'];
	    $this->data['position'] = $this->data['options']['position'];

	    $this->capability = $this->data['options']['capability'];

	    return $this;
	}


	/**
	 * Build the set settings page.
	 *
	 * @param Array $objects
	 *
	 * @return \TalkJS\Admin\SettingPage
	 */
	public function set( $objects = null )
	{

		if( is_null( $objects ) )
			throw new SettingsPageException('A settings page needs fields');

		$this->objects = $objects;

		if( isset( $_POST[ $this->data['form-title']  ] ) ){

			//save all data:
			$this->save();

			//refresh the page:
			wp_redirect( $this->getUrl() );
			exit();
		}

	   	add_action( 'admin_menu', array( &$this, 'display' ) );

	    return $this;
	}


	/**
	 * Restrict access to a specific user capability.
	 *
	 * @param string $capability
	 *
	 * @return void
	 */
	public function can( $capability )
	{
	    $this->capability = $capability;
	    $this->check = true;
	}


	/**
	 * The wrapper display method.
	 *
	 * @return void
	 */
	public function display(){

		//ignore the settingspage if the user doesn't have the capability
	    if( $this->check && !User::can( $this->capability ) ) return;

	    //ignore the settingspage if no view has been set
	    if( is_null( $this->objects ) ) return;

	    if( $this->data['options']['parent'] == false ){

	    	add_menu_page(
	    		$this->data['title'],
	    		$this->data['options']['menu_title'],
	    		$this->capability,
	    		$this->data['slug'],
	    		array( $this, 'render' ),
	    		$this->data['icon'],
	    		$this->data['position']
	    	);

	    }else if( $this->data['options']['parent'] === 'options' ){

	    	add_options_page(
	    		$this->data['title'],
	    		$this->data['options']['menu_title'],
	    		$this->capability,
	    		$this->data['slug'],
	    		array( $this, 'render' )
	    	);


	    }else{

	    	$parentSlug = $this->data['options']['parent'];

	    	if( substr( $parentSlug, -4 ) !== '.php' )
	    		$parentSlug = 'edit.php?post_type='.esc_url( $parentSlug );

	    	add_submenu_page(
	    		$parentSlug,
	    		$this->data['title'],
	    		$this->data['options']['menu_title'],
	    		$this->data['options']['capability'],
	    		$this->data['slug'],
	    		array( $this, 'render' )
	    	);
	    }

	    return true;

	}


	/**
	 * Call by "add_menu_page / add_submenu_page", build the HTML code.
	 *
	 * @return void
	 */
	public function render() {

		echo '<div class="wrap">';

            echo '<div class="settings-page-wrapper '.esc_attr( $this->data['slug'] ).'">';
                echo '<h2>'.esc_html( $this->data['title'] ).'</h2>';

                echo '<form method="post">';

                echo '<input type="hidden" name="'.esc_attr( $this->data['form-title'] ).'" value="true"/>';

                // Add nonce fields
                wp_nonce_field( Session::nonceAction, Session::nonceName );


                echo '<div class="settings-wrapper">';

                    foreach( $this->objects as $object ){

                        $object->display();

                    }

                echo '</div>';


                echo '<div class="button-wrapper">';

                    echo '<p class="general-error-msg">'.esc_html( Messages::generalError() ).'</p>';
                    echo '<input type="submit" class="button button-primary button-large" value="'.esc_attr( __( 'Save settings', 'talkjs' ) ).'">';

                echo '</div>';
                echo '</form>';
            echo '</div>';
	    echo '</div>';
	}



	/**
	 * The save method, to save setting fields
	 *
	 * @return void
	 */
	public function save(){


	    $nonceName = (isset($_POST[Session::nonceName])) ? $_POST[Session::nonceName] : Session::nonceName;
	    if( !wp_verify_nonce( $nonceName, Session::nonceAction ) ) return;


	    // Check user capability.
	    if ( $this->check )
	        if ( !User::can( $this->capability ) ) return;


        $fields = array();;
        foreach( $this->objects as $object ){
            if( method_exists( $object, 'getName' ) )
                $fields[] = $object;

        }

	    $fields = apply_filters( 'talkjs_before_settings_field_save', $fields, $this );

	    $this->saveFields( $fields );

	}


	/**
	 * Register the settings page and its fields into the DB.
	 *
	 * @param array $fields
	 *
	 * @return void
	 */
	public function saveFields( $fields ) {

	    $save = array();

	    foreach( $fields as $field ){

			$key = $field->getName();
	       	$value = isset( $_POST[ $key ] ) ? $_POST[ $key ] : '';
			$save[ $key ] = $this->validate( $field, $value );
			
			// WP auto adds backslashes for 's, we want to remove them
			if($field->getName() === "welcomeMessage") {
				$save[ $key ] = stripslashes($value);
			}
        }

        $optionName = Settings::optionName();
        if( isset( $this->data['options']['dataset'] ) )
            $optionName = $this->data['options']['dataset'];


        update_option( $optionName, $save );

        do_action( 'talkjs_settingspage_update', $this );
	}


    /**
     * Validate the field on the backend again
     *
     * @param Field $field
     * @param Mixed $value
     * @return bool
     */
    public function validate( $field, $value ){

        if( $field->hasValidation() ){

            $validator = $field->getValidation();
            switch( $validator ){

                case 'valid-connection':
                    return (string) $value;
                    break;

                case 'valid-string':
                    return (string) $value;
                    break;

                case 'valid-bool':
                    return (bool) $value;
                    break;

                case 'valid-number':
                    return (float) $value;
                    break;

                case 'valid-int':
                    return absint( $value );
                    break;

                case 'valid-array':
                    if( is_array( $value ) )
                        return $value;

                    return array($value);
                    break;

            }
        }

        return $value;
    }

	/**
	 * Check settings page options: context, priority.
	 *
	 * @param array $options The settings page options.
	 * @return array
	 */
	protected function parseOptions(array $options) {

	    return wp_parse_args( $options, array(

	        'menu_title'   	=> $this->data['title'],
	        'parent'		=> false,
	        'capability'	=> 'manage_options',
	        'icon'			=> false,
	        'position'		=> null,

	    ));

	}

	/**
	 * Returns the Fields array
	 *
	 * @return Array
	 */
	public function getFields(){
		return $this->objects;
	}

	/**
	 * Get this settingspage url
	 *
	 * @return String
	 */
	public function getUrl()
	{
		switch( $this->data['options']['parent'] ){

			case false:
				return admin_url( $this->data['slug'].'.php' );
				break;
			case 'options':
				return admin_url( 'options-general.php?page='. $this->data['slug'] );
				break;

			default:

				$parentSlug = $this->data['options']['parent'];

	    		if( substr( $parentSlug, -4 ) !== '.php' )
	    			$parentSlug = 'edit.php?post_type='.$parentSlug;

	    		$url = add_query_arg( 'page',  $this->data['slug'], $parentSlug );
				return admin_url( $url );
				break;

		}
	}

}
