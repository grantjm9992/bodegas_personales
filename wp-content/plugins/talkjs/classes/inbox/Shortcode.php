<?php

    namespace TalkJS\Inbox;

    use TalkJS\Facades\Inbox;
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
        public function respond( $attributes = array(), $content = null )
        {
			return Inbox::make( $attributes )->getHtml();
        }

        /**
         * Returns the shortcode handle
         *
         * @return String
         */
        public static function getHandle()
        {
        	return 'talkjs_inbox';
        }

    }

    \TalkJS\Inbox\ShortCode::getInstance();
