<?php


	namespace TalkJS\Inbox;


	use TalkJS\Exceptions\PageGeneratorException;


	class PageGenerator{


		/**
		 * Page ID of the inbox page
		 * 
		 * @var int
		 */
		protected $pageId;


		/**
		 * Option name as a constant string
		 *
		 * @var String
		 */
		const OPTION = 'talkjs_inbox_page';


		/**
		 * Construct
		 */
		public function __construct()
		{
			$this->pageId = get_option( static::OPTION, null );

		}


		/**
		 * Generate the actual page, and save it's data
		 * 
		 * @return Int $postId
		 */
		public function generate()
		{
			 //create the post:
	        $args = array(
	            'post_title'    => __( 'Inbox', 'talkjs' ),
	            'post_content'	=> Shortcode::get(),
	            'post_type'     => 'page'
	        );

	        $response = wp_insert_post( $args, true );

	        if( !is_wp_error( $response ) ){

	        	update_option( static::OPTION, $response );
	        	$this->pageId = $response;
	        	return $response;

	        }else{

	        	throw new PageGeneratorException( $response->get_error_message() );

	        }
		}


		/**
		 * Maybe generate the page, if it doesn't exist yet
		 * 
		 * @return void
		 */
		public function maybeGenerate()
		{
			if( !$this->check() ){

				try{
				
					$this->generate();
				
				} catch( PageGeneratorException $exception ){


				}
			}

			return false;
		}


		/************************************************/
		/**            Checks                           */
		/************************************************/

		/**
		 * Check if we need to generate an inbox page
		 * 
		 * @return bool
		 */
		public function check()
		{
			if( is_null( $this->pageId ) )
				return false;

			return true;
		}


		/**
		 * Check if a page actually exists in the database
		 * 
		 * @return bool
		 */
		public function exists()
		{
			global $wpdb;
	        
	        if ( $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE ID = %d LIMIT 1;", $this->pageId ) ) > 0 ) 
	            return true;
		
	        return false;
		}

	}