<?php
/**
 * Class ApiTests
 *
 * @package TalkJS
 */

use TalkJS\Helpers\User;
use TalkJS\Facades\Field;
use TalkJS\Helpers\Session;
use TalkJS\Helpers\Settings;
use TalkJS\Facades\SettingsPage;
use TalkJS\Exceptions\SettingsPageException;

use TalkJS\Admin\Ui\Notifications;

/**
 * Api Tests
 */
class SettingsTest extends BaseTest {

	/** Test */
	function test_it_can_create_a_settings_page()
	{
		$this->assertTrue( class_exists( '\TalkJS\Admin\Ui\SettingsPage' ) );
		$this->assertTrue( method_exists( '\TalkJS\Admin\Ui\SettingsPage', 'make' ) );
	}

	/** Test */
	function test_it_can_check_user_capabilities()
	{

		$role = get_role( 'administrator' );
		$role->remove_cap( 'edit-talkjs-settings' );

		$page = SettingsPage::make(
			'Test Settings',
			Settings::page(),
			[
				'parent' => 'options-general.php',
				'capabilities' => 'edit-talkjs-settings'
			]
		);

		$page->set([ Field::text( 'testInput' )]);

		//display returns a null if the current user doesn't
		//have the capability 'edit-talkjs-settings'
		$this->assertNull( $page->display() );


		$role->add_cap( 'edit-talkjs-settings' );

		//re login:
		$this->auth();

		//and check again:
		$this->assertTrue( $page->display() );

	}


	/** Test */
	function test_it_cant_add_a_page_without_fields()
	{

		$page = $this->create();
		$this->expectException( SettingsPageException::class );
		$page->set();

	}


	/** Test */
	function test_it_has_a_nonce()
	{
		$page = $this->createWithFields();
		$nonce = wp_nonce_field( Session::nonceAction, Session::nonceName, true, false );

		ob_start();
		$page->render();
		$html = ob_get_clean();

		$this->assertContains( $nonce, $html );
	}


	/** Test */
	function test_it_can_display_fields()
	{

		$page = $this->create();
		$field = Field::text( 'testField', [ 'label' => 'TestField' ] );

		$page->set([ $field, Field::textarea( 'testArea' ) ]);

		//check if the field count checks out:
		$this->assertCount( 2, $page->getFields() );

		ob_start();
		$page->render();
		$html = ob_get_clean();

		$this->assertContains( $field->render(), $html );
	}


	/** Test */
	function test_it_needs_a_nonce_to_be_saved()
	{

		$page = $this->createWithFields();

		//saving without a nonce is impossible
		$this->assertNull( $page->save() );

	}


	/** Test */
	function test_it_saves_its_fields()
	{

		$page = $this->createWithFields();

		//save fields, without checking the nonce:
		$page->saveFields( $page->getFields() );

		//and check the saved options:
		$options = get_option( Settings::optionName() );

		$this->assertNotFalse( $options, 'Settings didn\'t get saved.' );
		$this->assertCount( 1, $options );
	}

	/** Test */
	function test_it_returns_a_boolean_even_if_its_a_string()
	{
		Settings::set( 'mustBeLoggedIn', 'false' );
		$this->assertFalse( Settings::get( 'mustBeLoggedIn' ) );

		Settings::set( 'mustBeLoggedIn', 'true' );
		$this->assertTrue( Settings::get( 'mustBeLoggedIn' ) );
	}

	/** Test */
	function test_it_has_the_proper_defaults()
	{
		$defaults = array_keys( Settings::getDefaults() );
		$settingsPage = \TalkJS\Admin\SettingsPageCreator::getInstance();
        $fields = $settingsPage->getFields();

		$names = [];
		foreach( $fields as $field ){

			$name = $field->getName();
			if( !in_array( $name, $names ) )
				$names[] = $name;
		}

		$names[] = 'defaultUser';
		$names[] = 'mustBeLoggedIn';
		sort( $names );
		sort( $defaults );

		$this->assertEquals( $defaults, $names );
    }

    /** Test */
    function test_it_can_save_its_data()
    {
        $page = $this->createWithFields();
        $_POST['testField'] = 'my value';
        $page->saveFields( $page->getFields() );

        $option = get_option( Settings::optionName() );
        $this->assertEquals( $option, $_POST );

        $_POST['_nonce'] = 'crap-data';
        $this->assertNotEquals( $option, $_POST );
    }

    /** Test */
    function test_it_can_change_its_dataset()
    {

        $page = SettingsPage::make(
                'Test Settings',
                Settings::page(),
                [
                    'parent' => 'options-general.php',
                    'dataset' => 'test-data'
                ]
        );

        $fields = [ Field::text( 'testField', [ 'label' => 'Test Field' ]) ];
        $page->set( $fields );

        $_POST['testField'] = 'test-value';
        $page->saveFields( $fields );

        $testData = get_option( 'test-data' );
        $this->assertEquals( $testData, $_POST );
    }


	/************************************************/
	/**      Helper functions                       */
	/************************************************/

	/**
	 * Create a page with fields
	 *
	 * @return SettingsPage
	 */
	public function create()
	{
		return SettingsPage::make( 'Test Settings', Settings::page(), [ 'parent' => 'options-general.php' ] );
	}

	/**
	 * Create a page with fields
	 *
	 * @return SettingsPage
	 */
	public function createWithFields()
	{
		$page = $this->create();
		$field = Field::text( 'testField', [ 'label' => 'Test Field' ]);
		return $page->set([ $field ]);
	}
}
