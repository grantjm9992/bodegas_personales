<?php
/**
 * Class InboxTests
 *
 * @package TalkJS
 */

use TalkJS\Helpers\Settings;
use TalkJS\Facades\Inbox;

/**
 * Inbox Tests
 */
class InboxTests extends BaseTest {


	/** Test */
	public function test_it_has_a_render_method()
	{
		$this->assertTrue( method_exists( 'TalkJS\Inbox\Manager', 'render' ) );
	}


	/** Test */
	function test_it_checks_if_the_user_is_logged_in()
	{

		$this->getInbox();
		Settings::set( 'mustBeLoggedIn', true );

		//it should be valid now:
		$this->assertTrue( Inbox::isValid() );

		$this->logout();
		Settings::set( 'mustBeLoggedIn', false );

		//it should still be valid now:
		$this->assertTrue( Inbox::isValid() );

	}


	/** Test */
	function test_it_outputs_the_right_javascript()
	{
		$inbox = $this->getInbox();
		$this->assertContains( 'talkSession.createInbox()', $inbox->getHtml() );
		$this->assertContains( 'inbox.mount(document.getElementById("talkjs-inbox-container"))', $inbox->getHtml() );
		$this->assertContains( '<div id="talkjs-inbox-container"', $inbox->getHtml() );
	}


	/** Populate the settings with test data */
	public function getInbox()
	{
		Settings::set( 'appid', 'test' );
		Settings::set( 'secretkey', 'test' );
		Settings::set( 'mustBeLoggedIn', false );

		$inbox = Inbox::make();
		return $inbox;
	}
}
