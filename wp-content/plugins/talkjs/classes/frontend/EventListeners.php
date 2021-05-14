<?php

	namespace TalkJS\Front;

	use TalkJS\Facades\Inbox;
	use TalkJS\Helpers\Settings;
	use TalkJS\Contracts\EventsInstance;
	use TalkJS\Facades\NotificationBadge;

	class EventListeners extends EventsInstance{


		public function listen()
		{
			$GLOBALS['talkjs_session'] = false;

			//register widgets:
			add_action( 'widgets_init', array( $this, 'registerWidget'));

			//add a talk session to every page, if it's not set:
			add_action( 'wp_footer', function(){
				$implementationManager = new ImplementationManager();
				$implementationManager->maybeAddSession();
			});

			//add a nav menu class
			add_filter( 'nav_menu_css_class', array( $this, 'addNavClasses' ), 100, 2 );


        }

        /**************************************************************************/
        /*  Not putting these in a closures, because deregistration should be easy:
        /**************************************************************************/

        /**
         * Register the widget
         *
         * @return void
         */
        public function registerWidget(){
            register_widget( '\TalkJS\Chat\Widget' );
        }

        /**
         * Set the nav menu classes
         *
         * @param Array $classes
         * @param WP_Nav_Menu $item
         * @return Array
         */
        public function addNavClasses( $classes, $item ){

            $inboxPage = Settings::inboxPage();

            if( $item->object_id == $inboxPage ) {
                $classes[] = 'talkjs-inbox';
				if( !is_user_logged_in() ) $classes[] = 'talkjs-hidden';
			}

            return $classes;

        }

    }

	\TalkJS\Front\EventListeners::getInstance();
