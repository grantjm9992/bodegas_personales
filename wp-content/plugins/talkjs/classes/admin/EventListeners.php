<?php

	namespace TalkJS\Admin;

	use TalkJS\Helpers\Url;
	use TalkJS\Helpers\Settings;
	use TalkJS\Helpers\Messages;
	use TalkJS\Facades\Notification;
	use TalkJS\Contracts\EventsInstance;

	class EventListeners extends EventsInstance{


		/**
		 * Listen to events
		 *
		 * @return void
		 */
		public function listen()
		{
			//on init
			add_action( 'admin_init', function(){

				//check for an api key, and add a warning if needed,
				//only not if we're already on the settings-page
                if(
                    !Settings::get( 'appid', false ) &&
                    Url::current() !== Url::settingsPage() &&
                    Url::current() !== Url::onboardingPage()
                )
					Notification::add( Messages::noApiKey(), 'warning' );

                $activated = get_option( 'talkjs_activated', false );
                if( $activated ) {
					$pluginHandler = new PluginHandler();
                    $pluginHandler->activate();
				}

			});

			//add plugin link:
			add_filter( 'plugin_action_links_talkjs/talkjs.php', function( $links ){

				$url = 'options-general.php?page='.Settings::page();
			    $link = '<a href="'.esc_url( $url ).'">'. esc_html( __( 'Settings', 'talkjs' ) ) .'</a>';
			    return array_merge( array($link), $links );

            });

            //settings page:
            add_action( 'talkjs_settingspage_update', function( $settingsPage ){

                if( Settings::isOnboarding() ){

                    Settings::setOnboarding( false );

                    wp_redirect( Url::settingsPage() );
                    exit();
                }

            });


		}

	}

	\TalkJS\Admin\EventListeners::getInstance();
