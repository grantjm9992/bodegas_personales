<?php

	namespace TalkJS\Admin;

    use TalkJS\Helpers\Url;
    use TalkJS\Helpers\Messages;
	use TalkJs\Contracts\AssetLoaderInstance;

	class Assets extends AssetLoaderInstance{


		/**
		 * Load all assets for the admin
		 *
		 * @return void
		 */
		public function load()
		{
			add_action( 'admin_enqueue_scripts', function(){

				$url = Url::plugin( 'assets/dist/' );
				wp_enqueue_style( 'talkjs-admin', $url .'css/admin.css' );

				wp_enqueue_script( 'talkjs', $url.'js/talk.js' );
				wp_enqueue_script( 'talkjs-script', $url .'js/app.js', array( 'jquery', 'talkjs' ) );
				wp_enqueue_script( 'crypto-js', 'https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js' );

                $object = array( 'errors' => Messages::allErrors() );
                wp_localize_script( 'talkjs-script', 'TalkJS', $object );
			});
		}

	}


	if( is_admin() )
		\TalkJS\Admin\Assets::getInstance();
