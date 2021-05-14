<?php
/**
 * Class TalkSessionTest
 *
 * @package TalkJS
 */

use TalkJS\Facades\Inbox;
use TalkJS\Helpers\Settings;
use TalkJS\Traits\ImplementsTalkSession;

/**
 * Inbox Tests
 */
class TalkSessionTest extends BaseTest {


	/** Test */
	public function test_it_has_a_working_start_method()
	{
		$this->assertTrue( method_exists( 'TalkJS\Traits\ImplementsTalkSession', 'start' ) );
		$this->assertContains( 'Talk.ready.then(function()', $this->getHtml() );
	}

	/** Test */
	public function test_it_has_a_working_end_method()
	{
		$this->assertTrue( method_exists( 'TalkJS\Traits\ImplementsTalkSession', 'end' ) );
		$this->assertContains( '});</script>', $this->getHtml() );
	}


	/** test */
	public function test_it_can_include_the_script()
	{
		$GLOBALS['talkjs_session'] = false;
		$this->assertTrue( method_exists( 'TalkJS\Traits\ImplementsTalkSession', 'includeScript' ) );
		$this->assertContains( 'function(t,a,l,k,j,s)', $this->getHtml() );

		//test with the global session on, output should be gone:
		$GLOBALS['talkjs_session'] = true;
		$this->assertNotContains( 'function(t,a,l,k,j,s)', $this->getHtml() );
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
