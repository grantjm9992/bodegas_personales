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

	class OnboardingPageCreator extends EventsInstance{

        /**
         * Listen to onboarding events
         *
         * @return void
         */
        public function listen()
        {
			$that = $this;
            add_action( 'wp_loaded', function() use ($that) {

                if( Settings::isOnboarding() ){

                    SettingsPage::make(

                        __( 'Welcome to TalkJS!', 'talkjs' ),
                        Settings::onboardingPage(),
                        array(
                            'parent' => 'options-general.php',
                            'buttonText' => __( 'Next', 'talkjs' ),
                            'dataset' => Settings::optionName()
						)

                    )->set( $that->getObjects() );

                }

			});
        }


        /**
         * Returns the right objects
         *
         * @return Array
         */
        public function getObjects(){

            return array(

                Markup::paragraph(
                    "This plugin connects the <a href=\"%s\" target=\"_blank\">TalkJS user-to-user chat service</a> to your WordPress site.\n\n
					<br />If you haven't yet, you need to <a href=\"%s\" target=\"_blank\">create a TalkJS account</a> to get your app ID and secret key.
					<br />You can find your app ID and secret key in the <a href=\"%s\" target=\"_blank\">TalkJS Dashboard</a> and fill it out below.",
                    Url::site(),
                    Url::signup(),
                    Url::dashboard()
				),

				Markup::paragraph(
                    __( "TalkJS can run in test mode and in live mode. Test mode is free forever
                    but you're not allowed to use it for anything other than testing.\n\n
					When you signed up, you immediately get access to the TalkJS test mode app id and secret key.", 'talkjs' )
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
                    'label' => __( 'Which Wordpress Roles are allowed to chat on your site?', 'talkjs' ),
                    'value' => Settings::get( 'userTypes', array() ),
                    'choices' => Roles::forCheckboxes(),
                    'classes' => array( 'check-configurations', 'help-below' ),
                    'validation' => 'valid-array'
                )),


			);
        }

        /**
         * Returns the right fields
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

    OnboardingPageCreator::getInstance();
