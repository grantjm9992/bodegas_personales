<?php
/**
 * Class ChatTest
 *
 * @package TalkJS
 */

use TalkJS\Facades\Chat;
use TalkJS\Helpers\User;
use TalkJS\Helpers\Settings;

/**
 * Chat Tests
 */
class ChatTests extends BaseTest {


	/** Test */
	public function test_it_has_a_render_method()
	{
		$this->assertTrue( method_exists( 'TalkJS\Chat\Manager', 'render' ) );
	}


	/** Test */
	function test_it_checks_if_the_user_is_logged_in()
	{

		$this->getChatbox();
		Settings::set( 'mustBeLoggedIn', true );

		//it should be valid now:
		$this->assertTrue( Chat::isValid() );

		$this->logout();
		Settings::set( 'mustBeLoggedIn', false );

		//it should still be valid now:
		$this->assertTrue( Chat::isValid() );

	}


	/** Test */
	function test_it_creates_a_conversation()
	{
		$chat = $this->getChatbox();
		$this->assertContains( 'var user = new Talk.User', $chat->getHtml() );
	}


	/** Test */
	function test_it_has_a_defined_user_type()
	{
		$chat = $this->getChatbox();
		$this->assertNotNull( $chat->getProp( 'userType' ) );
	}


	/** Test */
	function test_it_has_a_default_user_type()
	{
		//default:
		$chat = $this->getChatbox();
		$this->assertEquals( $chat->getProp( 'userType' ), 'author' );
	}


	/** Test */
	function test_it_formats_user_data_properly(){

        $author = $this->makeAuthor();
        $roles = $author->roles;
        $role = array_shift( $roles );

		$authorData = [
            'id' => $author->data->ID,
            'name' => $author->data->display_name,
            'email' => $author->data->user_email,
            'photoUrl' => get_avatar_url( $author->data->ID ),
            'role' => $role
        ];

		$this->assertEquals( User::getTalkObject( 'author'), $authorData );
	}


	/** Test */
	function test_it_adds_the_author_in_a_conversation()
	{

		$author = $this->makeAuthor();
		$chat = $this->getChatbox();
		$json = json_encode( User::getTalkObject('author') );

		//check if the json is added to the html:
		$this->assertContains( $json, $chat->getHtml() );
	}


	/** Test */
	/*function test_it_defaults_to_the_chosen_user_if_there_is_no_author()
	{
		$chat = $this->getChatbox(); //without author
		$json = json_encode( User::getTalkObject('author') );
		$json2 = json_encode( User::getTalkObject('userId', Settings::get( 'defaultUser' ) ) );

		$this->assertEquals( $json, $json2 );
		$this->assertContains( $json, $chat->getHtml() );
	}*/



	/** Test */
	function test_it_can_take_a_user_as_a_conversation_user()
	{
		$this->setSettings();

		//create a user:
		$user = self::factory()->user->create( [
	        'role' => 'administrator',
    	]);

		//add it as a user:
		$chat = Chat::make([ 'userType' => 'userId', 'user' => $user ]);

		//check the properties:
		$this->assertEquals( $chat->getProp( 'userType' ), 'userId' );
		$this->assertNotNull( $chat->getProp( 'user' ) );

		//check the json
		$json = json_encode( User::getTalkObject( 'userId', $user ) );
		$userJson = substr( json_encode( ['id' => (string)$user ] ), 0, -1 );//remove the lingering }

		//check if the initial json is correct:
		$this->assertContains( $userJson, $json );

		//check if the json is added to the html:
		$this->assertContains( $json, $chat->getHtml() );
	}


	/** Test */
	function test_it_outputs_the_the_chatbox_javascript()
	{
		$chat = $this->getChatbox();
		$this->assertContains( 'talkSession.createChatbox(conversation)', $chat->getHtml() );
		$this->assertContains( 'chatbox.mount(document.getElementById("talkjs-chat-container"))', $chat->getHtml() );
		$this->assertContains( '<div id="talkjs-chat-container"', $chat->getHtml() );
	}


	/**
	 * Populate the settings with test data, and return a Chatbox instance
	 *
	 * @return TalkJS\Chat\Manager
	 */
	public function getChatbox()
	{
		$this->setSettings();

		$chat = Chat::make();
		return $chat;
	}


	/**
	 * Generate an author, place 'em in the global scope
	 *
	 * @return void
	 */
	public function makeAuthor()
	{
		$authorId = self::factory()->user->create( ['role' => 'administrator']);
    	$author = get_user_by( 'ID', $authorId );

    	//populate the authordata global
		$GLOBALS['authordata'] = $author;

		return $author;
	}


	/**
	 * Set the settings
	 *
	 * @return void
	 */
	public function setSettings()
	{
		Settings::set( 'appid', 'test' );
		Settings::set( 'secretkey', 'test' );
		Settings::set( 'mustBeLoggedIn', false );
	}

}
