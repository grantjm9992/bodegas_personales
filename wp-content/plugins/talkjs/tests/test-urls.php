<?php
/**
 * Class ApiTests
 *
 * @package TalkJS
 */

use TalkJS\Helpers\Url;
use TalkJS\Helpers\Settings;

/**
 * Sample test case.
 */
class UrlTests extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_settings_page_url() {

		$slug = Settings::page();
		$url = Url::settingsPage();

		$this->assertEquals( $slug, 'talkjs-settings' );
		$this->assertEquals( $url, admin_url( 'options-general.php?page='. $slug ) );

	}


}
