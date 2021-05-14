<?php
/**
 * Class NotificationTests
 *
 * @package TalkJS
 */

use TalkJS\Facades\Inbox;
use TalkJS\Helpers\Settings;
use TalkJS\Inbox\PageGenerator;
use TalkJS\Traits\ImplementsTalkSession;

/**
 * Inbox Tests
 */
class NotificationTests extends BaseTest {

	/** Test */
	public function test_it_adds_a_classname_to_the_inbox_page_menu_item()
	{
		$menuItem = new stdClass();
		$menuItem->object_id = '4';

		//set the inbox page ID
		update_option( PageGenerator::OPTION, '4' );

		$classes = apply_filters( 'nav_menu_css_class', [], $menuItem );

		$this->assertTrue( is_array( $classes ) );
		$this->assertContains( 'talkjs-inbox', $classes );
	}


	/** Test */
	public function test_it_checks_for_the_inbox_menu_class()
	{
		$this->assertContains( 'document.getElementsByClassName("talkjs-inbox")', $this->getHtml() );
	}


	/** Test */
	public function test_it_inserts_the_badge()
	{
		$this->assertContains( 'var badgeHtml = document.createElement("div");', $this->getHtml() );
		$this->assertContains( 'badgeHtml.setAttribute( "id", "notification-badge" )', $this->getHtml() );
	}


	/** Test */
	public function test_it_checks_for_changes()
	{
		$this->assertContains( 'window.talkSession.unreads.on( "change"', $this->getHtml() );
	}


	/** Populate the settings with test data */
	public function getSession()
	{
		Settings::set( 'appid', 'test' );
		Settings::set( 'secretkey', 'test' );
		Settings::set( 'mustBeLoggedIn', false );

		$session = ( new ImplementsTalkSession() )->make([]);
		return $session;
	}


	/**
	 * Returns the html of the ImplementsTalkSession class
	 *
	 * @return String
	 */
	public function getHtml()
	{
		$session = $this->getSession()->make();
		return $session->getHtml();
	}
}
