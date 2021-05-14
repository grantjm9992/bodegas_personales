<?php

	namespace TalkJS\Front;

    use TalkJS\Facades\Popup;
    use TalkJS\Helpers\Roles;
	use TalkJS\Helpers\Settings;
	use TalkJS\Facades\TalkSession;

	class ImplementationManager{

		/**
		 * Maybe add a TalkJS Session
		 *
		 * @return void
		 */
		public function maybeAddSession()
		{
			global $talkjs_session;

			if( $this->isValidPostType() || $this->isValidAuthor() )
				Popup::make()->display();

			//add the talkJs session, anyway if no application is on the page:
			if( !$talkjs_session )
				TalkSession::make()->display();
		}


		/**
		 * Check if there's a current valid post-type active
		 *
		 * @return boolean
		 */
		public function isValidPostType()
		{
			if( Settings::get( 'postTypes', false ) && is_singular() ){

				$postTypes = Settings::get( 'postTypes', array() );
				$postTypes = ( is_array( $postTypes ) ? $postTypes : array( $postTypes ) );

				if( in_array( get_post_type(), $postTypes ) )
					return true;

			}

			return false;
		}

		/**
		 * Checks if this is an author page
		 *
		 * @return boolean
		 */
		public function isValidAuthor()
		{
			if( is_author() ){
                global $authordata;

                if( sizeof( Settings::get( 'authorTypes' ) ) !== sizeof( Roles::values() ) ){

                    $notAllowed = array_diff( Roles::values(), Settings::get('authorTypes') );
                    foreach( $notAllowed as $role ){
                        if( in_array( $role, $authordata->roles ) )
                            return false;
                    }

                }

                return true;
            }

			return false;
		}

	}
