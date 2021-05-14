<?php

	/**
	 * Base Tests
	 */
	class BaseTest extends WP_UnitTestCase {

		/**
		 * Authenticate a new user
		 * 
		 * @return void
		 */
		public function auth( $role = 'administrator' )
		{
			wp_set_current_user( self::factory()->user->create( [
	            'role' => $role,
    	    ]) );
		}


		/**
		 * Log a user out
		 * 
		 * @return void
		 */
		public function logout()
		{
			wp_logout();
		}


		/** Test */
		public function test_dd_is_an_existing_function()
		{
			$this->assertTrue( function_exists( 'dd' ) );
		}

	}