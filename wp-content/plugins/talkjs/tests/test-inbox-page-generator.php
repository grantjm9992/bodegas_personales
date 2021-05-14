<?php
/**
 * Class InboxPageGeneratorTests
 *
 * @package TalkJS
 */

use TalkJS\Inbox\Shortcode;
use TalkJS\Inbox\PageGenerator;
use TalkJS\Exceptions\PageGeneratorException;

class InboxPageGeneratorTests extends BaseTest {


	/** Test */
	function test_it_can_check_if_a_page_has_been_generated()
	{

		$this->assertTrue(
			method_exists( 'TalkJS\Inbox\PageGenerator', 'check' ),
			'The page generator doesn\'t have a check method'
		);

		//remove the inbox_page option, to be certain
		delete_option( 'talkjs_inbox_page' );

		$generator = $this->getGenerator();

		$this->assertFalse( $generator->check() );
	}


	/** Test */
	function test_it_can_generate_pages_and_return_their_ids()
	{
		$generator = $this->getGenerator();

		try{

			$this->assertInternalType( 'int', $generator->generate() );

		} catch( PageGeneratorException $exception ) {

			$this->fail( $exception->getMessage() );

		}
	}


	/** Test */
	function test_it_can_check_if_the_generated_page_still_exists()
	{
		$this->assertTrue(
			method_exists( 'TalkJS\Inbox\PageGenerator', 'exists' ),
			'The page generator doesn\'t have an exists method'
		);


		$generator = $this->getGenerator();
		$generator->generate();

		$this->assertTrue( $generator->exists() );

	}


	/** Test */
	function test_it_adds_the_inbox_shortcode_to_the_page()
	{
		$page = $this->getGeneratedPost();
		$this->assertContains( Shortcode::get(), $page->post_content );
	}


	/** Test */
	function test_it_doesnt_autopublish_the_page()
	{
		$page = $this->getGeneratedPost();
		$this->assertEquals( $page->post_status, 'draft' );
	}


	/** Test */
	function test_it_doesnt_generate_if_a_page_already_exists()
	{
		$generator = $this->getGenerator();

		update_option( $generator::OPTION, 3 );

		$this->assertFalse( $generator->maybeGenerate() );
	}


	/**
	 * Creates a generator for our tests
	 *
	 * @return TalkJS\Inbox\PageGenerator
	 */
	public function getGenerator()
	{
		return new PageGenerator();
	}


	/**
	 * Returns the generated post
	 *
	 * @return WP_Post
	 */
	public function getGeneratedPost()
	{
		$generator = $this->getGenerator();
		$id = $generator->generate();

		$page = get_post( $id );
		return $page;

	}

}
