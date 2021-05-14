<?php

	namespace TalkJS\Helpers;

	class Url{

		/**
		 * Method to return link without using the magic button
		 *
		 * @return String
		 */
		public static function get( $url, $atts = array() )
		{
			$urls = static::all();

			if( in_array( $url, array_keys( $urls ) ) )
				return $urls[ $url ];

			return '';

		}

		/**
		 * Magic method to link function calls to their message-return values
		 *
		 * @return String
		 */
		public static function __callStatic( $url, $atts = array() )
		{
			return self::get($url, $atts);
		}


		/**
		 * Get the plugin url
		 *
		 * @return String
		 */
		public static function plugin( $folder = '' )
		{
			$url = trailingslashit( WP_PLUGIN_URL );
			$url .= trailingslashit( 'talkjs' );

			if( $folder !== '' )
				$url .= $folder;

			return $url;
		}


		/**
		 * Get the plugin path
		 *
		 * @return String
		 */
		public static function pluginPath( $folder = '' )
		{
			$path = WP_PLUGIN_DIR . DS . 'talkjs' . DS;

			if( $folder !== '' )
				$path .= str_replace( '/', DS, $folder );

			return $path;
		}


		/**
		 * Return the current URL.
		 *
		 * @return string
		 */
		public static function current()
		{
			$url = '';

			// Check to see if it's over https
			$is_https = self::isHttps();
			if ($is_https) {
			    $url .= 'https://';
			} else {
			    $url .= 'http://';
			}

			// Was a username or password passed?
			if (isset($_SERVER['PHP_AUTH_USER'])) {
			    $url .= $_SERVER['PHP_AUTH_USER'];

				if (isset($_SERVER['PHP_AUTH_PW'])) {
					$url .= ':' . $_SERVER['PHP_AUTH_PW'];
				}

				$url .= '@';
			}


			// We want the user to stay on the same host they are currently on,
			// but beware of security issues
			// see http://shiflett.org/blog/2006/mar/server-name-versus-http-host
			$url .= $_SERVER['HTTP_HOST'];

			// Get the rest of the URL
			if (!isset($_SERVER['REQUEST_URI'])) {
			    // Microsoft IIS doesn't set REQUEST_URI by default
			    $url .= $_SERVER['PHP_SELF'];

			    if (isset($_SERVER['QUERY_STRING'])) {
			        $url .= '?' . $_SERVER['QUERY_STRING'];
			    }
			} else {
				$url .= $_SERVER['REQUEST_URI'];
			}

			return $url;
		}


		/**
		 * All messsages in this plugin
		 *
		 * @return Array
		 */
		public static function all()
		{
			return array(
                'settingsPage' => admin_url( 'options-general.php?page='.Settings::page() ),
                'onboardingPage' => admin_url( 'options-general.php?page='.Settings::onboardingPage() ),
                'inboxPage' => admin_url('post.php?post='.Settings::inboxPage().'&action=edit' ),
                'site' => 'https://talkjs.com/?ref=wp_plugin',
                'account' => 'https://talkjs.com/pricing/?ref=wp_plugin',
                'dashboard' => 'https://talkjs.com/dashboard/?ref=wp_plugin',
				'docs' => 'https://talkjs.com/docs/?ref=wp_plugin',
				'signup' => 'https://talkjs.com/dashboard/signup/standard/?ref=wp_plugin',
				'docs.notifications.getting_started' => 'https://talkjs.com/docs/Notifications/Getting_Started.html?ref=wp_plugin',
			);

		}

	}
