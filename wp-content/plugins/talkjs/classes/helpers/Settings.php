<?php

	namespace TalkJS\Helpers;

	use \TalkJS\Inbox\PageGenerator;

	class Settings{


		/**
		 * Get a setting
		 *
		 * @param  string $key
		 * @param  mixed $default
		 *
		 * @return mixed
		 */
		public static function get( $key, $default = false )
		{

			$settings = self::findSettings();
			if( isset( $settings[ $key ] ) && $settings[ $key ] != false ){
				$value = $settings[ $key ];
			}else{
				$value = $default;
			}

			if( $value === 'true' ){
				$value = true;
			}else if( $value === 'false' ){
				$value = false;
			}

			return $value;

		}


		/**
		 * Set a setting
		 *
		 * @param String $key
		 * @param Mixed $value
		 *
		 * @return void
		 */
		public static function set( $key, $value )
		{
			$settings = self::findSettings();
			$settings[ $key ] = $value;
			update_option( self::optionName(), $settings );
		}


		/**
		 * Get the settings
		 *
		 * @return array
		 */
		private static function findSettings()
		{
			return get_option( static::optionName(), static::getDefaults() );
		}


		/**
		 * Return default TalkJS Settings
		 *
		 * @return Array
		 */
		public static function getDefaults()
		{
			return array(
				'appid' => '',
				'secretkey' => '',
				'welcomeMessage' => static::welcomeMessage(),
                'postTypes' => array(),
                'authorTypes' => Roles::values(),
                'userTypes' => Roles::values(),
				'defaultUser' => 1,
				'mustBeLoggedIn' => true,
                'keepLivePopup' => false,
			);
		}


		/**
		 * Return the url of the settings page
		 *
		 * @return Url
		 */
		public static function page()
		{
			return 'talkjs-settings';
		}

        /**
         * Return the url of the onboarding page
         *
         * @return Url
         */
        public static function onboardingPage(){
            return 'talkjs-onboarding';
        }

        /**
         * Check if we're onboarding
         *
         * @return boolean
         */
        public static function isOnboarding(){
            $option = get_option( 'talkjs_onboarding' );
            if( isset( $option ) && $option == true ) return true;

            return false;
        }

        /**
         * Set the onboarding option
         *
         * @return void
         */
        public static function setOnboarding( $value = true ){
            update_option( 'talkjs_onboarding', $value );
        }

		/**
		 * Returns the default welcome message
		 *
		 * @return String
		 */
		public static function welcomeMessage()
		{
			$sitename = get_bloginfo( 'name' );
			$message = "Hi I'm {{fullname}}, welcome to {$sitename}";
			return $message;
		}


		/**
		 * The name of the setting option
		 *
		 * @return String
		 */
		public static function optionName()
		{
			return 'talkjs-settings';
		}

		/**
		 * Returns the app ID based on the application mode
		 *
		 * @param  mixed $default
		 *
		 * @return String
		 */
		public static function appId( $default = null )
		{
			return static::get( 'appid', $default );
		}


		/**
		 * Returns the secret key based on the application mode
		 *
		 * @param  mixed $default
		 *
		 * @return String
		 */
		public static function secretkey( $default = null )
		{
			return static::get( 'secretkey', $default );
		}

		/**
		 * Returns the inbox page ID
		 *
		 * @return Int
		 */
		public static function inboxPage()
		{
			$name = PageGenerator::OPTION;
			$id = get_option( $name, null );

			return $id;
		}

	}
