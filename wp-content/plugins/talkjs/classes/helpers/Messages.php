<?php

	namespace TalkJS\Helpers;

	class Messages{


		/**
		 * Magic method to link function calls to their message-return values
		 *
		 * @return String
		 */
		public static function __callStatic( $message, $atts = array() )
		{
			$messages = static::all();

			if( in_array( $message, array_keys( $messages ) ) )
				return $messages[ $message ];

			return '';

		}


		/**
		 * Render the welcome message (searches and replaces some tags)
		 *
		 * @param String $message
		 *
		 * @return String
		 */
		public static function welcome( $message ){

			$message = str_replace( '{{first_name}}', User::getAttribute( 'first_name', '' ), $message );
			$message = str_replace( '{{last_name}}', User::getAttribute( 'last_name', '' ), $message );
			$message = str_replace( '{{name}}', User::getAttribute( 'name', '' ), $message );

			return $message;
        }

        /**
         * Returns all the errors:
         *
         * @return Array
         */
        public static function allErrors(){

            $messages = static::all();
            $keys = array( 'noValidKey', 'configurationsNotSet', 'configurationNotAvailable' );
            $errors = array();

            foreach( $messages as $key => $msg ){
                if( in_array( $key, $keys ) )
                    $errors[ $key ] = $msg;
            }

            return $errors;
        }

		/**
		 * All messsages in this plugin
		 *
		 * @return Array
		 */
		public static function all()
		{
			return array(
				'noApiKey' => sprintf(
					__( 'You haven\'t yet provided your TalkJS app id. <a href="%s" alt="Add a TalkJS app id">Please do so here</a>', 'talkjs' ),
					Url::settingsPage()
                ),
                'generalError' => __( 'There are errors on the page. Please fix these first.', 'talkjs' ),
                'noValidKey' => sprintf(
                    __( 'This doesn\'t seem to be a valid app id and secret key combination, please check <a target="_blank" href="%s">the TalkJS dashboard.</a>', 'talkjs' ),
                    Url::dashboard()
                ),
                'configurationsNotSet' => sprintf(
                    __( 'Some roles haven\'t been created in TalkJS yet. <a href="%s" target="_blank">Go to the TalkJS dashboard</a> and create these roles, so e-mail notifications will work properly.', 'talkjs' ),
                    Url::dashboard()
                ),
                'configurationNotAvailable' => sprintf(
                    __( 'This WordPress role doesn\'t yet have a TalkJS role', 'talkjs' )
                )
			);

		}

	}
