<?php

	namespace TalkJS\Contracts;

	class ShortcodeInstance extends StaticInstance {

        /**
         * Private constructor. Avoid building instances using the
         * 'new' keyword.
         */
        protected function __construct(){
        	$this->register();
        }


        /**
         * Register the shortcode
         *
         * @return void
         */
        public function register()
        {
			$that = $this;
            add_action( 'init', function() use ($that) {
        		add_shortcode( $that->getHandle(), array( $that, 'respond' ) );
        	});
        }


        /**
         * Returns the full shortcode as a String
         *
         * @return String
         */
        public static function get()
        {
            $handle = static::getHandle();
            return "[{$handle}]";
        }

        /**
         * Respond to the shortcode's call
         *
         * @param Array $attributes
         * @param String $content
         *
         * @return String
         */
        public function respond( $attributes, $content = null )
        {
        	return '';
        }

        /**
         * Returns the shortcode handle
         *
         * @return String
         */
        public static function getHandle()
        {
        	return '';
        }
	}
