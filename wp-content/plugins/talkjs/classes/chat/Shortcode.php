<?php

    namespace TalkJS\Chat;

    use TalkJS\Facades\Chat;
    use TalkJS\Contracts\ShortcodeInstance;

    class Shortcode extends ShortcodeInstance {

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
        	return Chat::make( $attributes )->getHtml();	
        }


        /**
         * Returns the shortcode handle
         * 
         * @return String
         */
        public static function getHandle()
        {
        	return 'talkjs_chat';
        }

    }

    \TalkJS\Chat\ShortCode::getInstance();