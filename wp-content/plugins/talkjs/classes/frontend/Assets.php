<?php 

	namespace TalkJS\Front;

	use TalkJS\Helpers\Url;
	use TalkJS\Contracts\AssetLoaderInstance;

	class Assets extends AssetLoaderInstance{


		/**
		 * Load our assets
		 * 
		 * @return void
		 */
		public function load()
		{
			
			add_action( 'wp_enqueue_scripts', function(){

				$url = Url::plugin( 'assets/dist/css/talkjs.css' );
				wp_enqueue_style( 'talkjs', $url );

			});

		}

	}

	\TalkJS\Front\Assets::getInstance();