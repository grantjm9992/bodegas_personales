<?php

    namespace TalkJS\Popup;

    use TalkJS\Facades\Popup;
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
        	return Popup::make( $attributes )->getHtml();
        }

        /**
         * Returns the shortcode handle
         *
         * @return String
         */
        public static function getHandle()
        {
        	return 'talkjs_popup';
        }

    }

    \TalkJS\Popup\ShortCode::getInstance();
