<?php
namespace TalkJS\Helpers;

class Session{

	/**
	 * Action identifier for a nonce field
	*/
	const nonceAction = 'talkjs-nonce-action';

	/**
	 * Name attribute for a nonce field
	*/
	const nonceName = '_talkjsnonce';
	

	/**
	 * Private constructor. Avoid building instances using the
	 * 'new' keyword.
	 */
	private function __construct(){}


	/**
	 * Get the current POST ID, no matter where you at.
	 * 
	 * @return mixed
	 */
	public static function postId(){

		global $post;

		if( isset( $_GET['post'] ) )
			return absint( $_GET['post'] );

		if( isset( $_POST['post_ID'] ) )
			return absint( $_POST['post_ID'] );

		if( isset( $post ) && isset( $post->ID ) )
			return absint( $post->ID );

		return false;

	}


	/**
	 * Get the post ID based on the WP root query
	 * Else, default back to self::postId()
	 * 
	 * @return mixed
	 */
	public static function rootPostId(){

		global $wp_the_query;

		if( isset( $wp_the_query->post->ID ) )
			return $wp_the_query->post->ID;

		return self::postId();
		
	}

}