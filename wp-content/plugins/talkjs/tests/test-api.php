<?php
/**
 * Class ApiTests
 *
 * @package TalkJS
 */

use TalkJS\Helpers\Settings;
use TalkJS\Helpers\Messages;
use TalkJS\Admin\Ui\Notifications;

/**
 * Api Tests
 */
class ApiTests extends WP_UnitTestCase {

	/**
	 * A notification gets displayed if there's no api-key setting
	 */
	function test_it_can_give_a_notification_warning() {

		if( Settings::get( 'apikey' ) == false ){

			$notifications = new Notifications();

			$notifications->add( Messages::noApiKey(), 'warning' );
			$count = $notifications->getNotificationCount( 'warning' );

			//check if the notification is set:
			$this->assertEquals( $count, 1 );


			ob_start();

				$notifications->displayNotifications( 'warning' );

			$string = ob_get_clean();

			//check if the notification get's displayed
			$this->assertContains( Messages::noApiKey(), $string );
		}
	}




}
