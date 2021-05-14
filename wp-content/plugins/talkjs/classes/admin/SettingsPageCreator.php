<?php

	namespace TalkJS\Admin;

    use TalkJS\Helpers\Url;
    use TalkJS\Helpers\User;
    use TalkJS\Facades\Field;
    use TalkJS\Helpers\Roles;
    use TalkJS\Facades\Markup;
    use TalkJS\Helpers\Settings;
	use TalkJS\Helpers\PostTypes;
	use TalkJS\Facades\SettingsPage;
	use TalkJS\Contracts\EventsInstance;

	class SettingsPageCreator extends EventsInstance{


		/**
		 * Listener
		 *
		 * @return void
		 */
		public function listen()
		{
			$that = $this;
			add_action( 'wp_loaded', function() use ($that) {

				SettingsPage::make(

					__( 'TalkJS Settings', 'talkjs' ),
					Settings::page(),
					array( 'parent' => 'options-general.php' )

				)->set( $that->getObjects() );

			});
		}


		/**
		 * Return the fields for our settings page
		 *
		 * @return Array
		 */
		public function getObjects()
		{
			$fields = array(

                Markup::paragraph(
                    __( "This plugin connects the <a href=\"%s\">TalkJS user-to-user chat service</a> to your WordPress site.\n\n
                    TalkJS includes an \"Inbox page\", with the current user's full conversation history.
                    We already created such a page for you, <a href=\"%s\">set it up here</a>.", 'talkjs' ),
                    Url::site(),
                    Url::inboxPage()
                ),

                Markup::heading(
                    __( "TalkJS Credentials", 'talkjs'),
                    'h3',
                    'border-top'
                ),

				Markup::paragraph(
                    __( "TalkJS can run in test mode and in live mode. Test mode is free forever
                    but you're not allowed to use it for anything other than testing.\n\n
					When you signed up, you immediately get access to the TalkJS test mode app id and secret key.", 'talkjs' )
				),

				Markup::paragraph(
                    __( "If you want to go live with real users, go to the <a href=\"%s\">TalkJS Dashboard</a>,
                provide payment information, and then replace the credentials below with your new set of live-mode credentials.", 'talkjs' ),
                    Url::dashboard()
				),

				Field::text( 'appid', array(
					'label' => __( 'App ID', 'talkjs' ),
					'placeholder' => __( 'Your TalkJS App ID', 'talkjs' ),
					'value' => Settings::get('appid', '' ),
					'classes' => array('check-connection', 'check-appid'),
					'validation' => array('valid-connection')
				)),

				Field::text( 'secretkey', array(
					'label' => __( 'Secret Key', 'talkjs' ),
					'placeholder' => __( 'Your TalkJS Secret Key','talkjs' ),
					'value' => Settings::get( 'secretkey', '' ),
					'classes' => array('check-connection', 'check-secretkey'),
					'validation' => array('valid-connection')
				)),

                Markup::heading(
                    __( "Customization", 'talkjs' ),
                    'h3',
                    'border-top'
                ),

				Field::textarea( 'welcomeMessage', array(
					'label' => __( 'Default welcome message - this is an automatically generated message that is shown to users when they start a chat.', 'talkjs' ),
                    'value' => Settings::get( 'welcomeMessage' ),
                    'help' => __( 'this is an automatically generated message that is shown to users when they start a chat.', 'talkjs' ),
                    'validation' => 'valid-string'
                )),

				Field::checkboxes( 'postTypes', array(
					'label' => __( 'Auto-show a chat popup button on single views of the following post-types', 'talkjs'),
					'value' => Settings::get( 'postTypes', array() ),
                    'choices' => PostTypes::all(),
                    'validation' => 'valid-array'
                )),

                Field::checkboxes( 'authorTypes', array(
                    'label' => __( 'Always show the chat popup button on these profile pages', 'talkjs' ),
                    'value' => Settings::get( 'authorTypes', array() ),
                    'choices' => Roles::forCheckboxes(),
                    'validation' => 'valid-array'
                )),

				Field::checkbox( 'keepLivePopup', array(
					'label' => __( 'Keep the popup open when the user navigates between pages', 'talkjs' ),
                    'value' => Settings::get( 'keepLivePopup', false ),
                    'validation' => 'valid-boolean'
                )),

                Markup::paragraph(
                    __( "Interested in further customization? <a href=\"%s\" target=\"_blank\">Check our developer documentation</a>", 'talkjs' ),
                    Url::docs()
                ),

                Markup::heading(
                    'Roles',
                    'h3'
                ),

                Markup::paragraph(
                    __( "TalkJS allows you to change most if its behavior in the <a href=\"%s\" target=\"_blank\">TalkJS Dashboard</a> by assigning TalkJS roles to each of your. For instance, you can fine-tune the look & feel of the chat, set up email and sms notifications, suppress contact information in user messages, and so on. \n\n
                    ", 'talkjs' ),
                    Url::dashboard()
                ),

                Markup::paragraph(
                    __( "You must make a \"role\" in the <a href=\"%s\" target=\"_blank\">TalkJS Dashboard</a> for every WordPress role you select below. The role in TalkJS should be the lowercase name of the roles in wordpress. For example, if you had a WordPress role 'Buyer' you need to create a role in the TalkJS dashboard named 'buyer'. <strong>Please note that some features like notifications won't start working until roles are created in the TalkJS Dashboard.</strong>", 'talkjs' ),
                    Url::dashboard()
                ),
                
                Markup::paragraph(
                    __( "If you want to learn more about roles, <a href=\"%s\" target=\"_blank\">check out our docs</a>.", 'talkjs' ),
                    Url::get('docs.notifications.getting_started')
				),

                Field::checkboxes( 'userTypes', array(
                    'label' => __( 'Which WordPress Roles are allowed to chat on your site?', 'talkjs' ),
                    'value' => Settings::get( 'userTypes', array() ),
                    'choices' => Roles::forCheckboxes(),
                    'classes' => array('check-configurations'),
                    'validation' => 'valid-array'
                ))
			);

			return $fields;

        }

        /**
         * Returns only the fields:
         *
         * @return Array
         */
        public function getFields(){
            $fields = array();
            $objects = $this->getObjects();

            foreach( $objects as $obj ){
                if( method_exists( $obj, 'getName' ) )
                    $fields[] = $obj;
            }

            return $fields;
        }
	}



	if( is_admin() )
		\TalkJS\Admin\SettingsPageCreator::getInstance();
