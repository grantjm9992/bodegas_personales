<?php

	namespace TalkJS\Admin\Ui;

	use TalkJS\Contracts\StaticInstance;

	class Notifications{

		/**
		 * Array of notifications to display
		 *
		 * @var Array
		 */
		protected $notifications;

		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->listener();
		}


		/***********************************************/
		/**          Notification Display              */
		/***********************************************/

		/**
		 * Add the admin listener
		 *
		 * @return void
		 */
		public function listener()
		{
			$that = $this;
			add_action( 'admin_notices', function() use ($that) {

				if( !empty( $that->notifications ) ){

					foreach( self::getTypes() as $type ){

						if( $that->getNotificationCount( $type ) > 0 )
							$that->displayNotifications( $type );

					}

				}
			});
		}


		/**
		 * Display Notifications of a certain type
		 *
		 * @param String $type
		 *
		 * @return String
		 */
		public function displayNotifications( $type )
		{
			$notifications = $this->getNotifications( $type );
			foreach( $notifications as $notification ){

				printf(
					'<div class="%s"><p>%s</p></div>',
					esc_attr( 'notice notice-'.$type ),
					$notification['message']
				);

			}
		}




		/***********************************************/
		/**          Notification Management           */
		/***********************************************/


		/**
		 * Add a notification
		 *
		 * @param String $message
		 * @param String $type
		 *
		 * @return void
		 */
		public function add( $message, $type = 'success' )
		{
			$this->notifications[] = array( 'message' => $message, 'type' => $type );
		}


		/**
		 * Add an error
		 *
		 * @param String $message
		 *
		 * @return void
		 */
		public function addError( $message )
		{
			$this->add( $message, 'error' );
		}


		/**
		 * Get all notifications
		 *
		 * @param  String $type
		 *
		 * @return Array
		 */
		public function getNotifications( $type = null )
		{
			if( is_null( $type ) )
				return $this->notifications;

			$response = array();

			if( !empty( $this->notifications ) ){

				foreach( $this->notifications as $notification ){
					if( $notification['type'] == $type )
						$response[] = $notification;
				}
			}

			return $response;
		}


		/**
		 * Get the notification count
		 *
		 * @param  String $type
		 *
		 * @return Int
		 */
		public function getNotificationCount( $type = null )
		{
			return count( $this->getNotifications( $type ) );
		}

		/**
		 * Types of notifications
		 *
		 * @var Array
		 */

		public static function getTypes() {
			return array(
				'success',
				'error',
				'warning'
			);
		}

	}
