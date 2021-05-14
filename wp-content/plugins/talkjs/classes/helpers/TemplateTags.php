<?php

	/**
	 *  Some template tag helpers that reside in the global namespace.
	 */

	if( !function_exists( 'talkjs_chat' ) ){

		/**
		 * Summon a TalkJS Chat window
		 *
		 * @param  Array  $props
		 * @param  bool $echo
		 *
		 * @return String (html)
		 */
		function talkjs_chat( $props = array(), $echo = true ){

			$html = \TalkJS\Facades\Chat::make( $props )->getHtml();

			if( $echo )
				echo $html;

			return $html;
		}
	}

	if( !function_exists( 'talkjs_popup' ) ){

		/**
		 * Summon a TalkJS Popup window
		 *
		 * @param  array   $props
		 * @param  boolean $echo
		 *
		 * @return String (html)
		 */
		function talkjs_popup( $props = array(), $echo = true )
		{
			$html = \TalkJS\Facades\Popup::make( $props )->getHtml();

			if( $echo )
				echo $html;

			return $html;
		}
	}


	if( !function_exists( 'talkjs_inbox' ) ){

		/**
		 * Summon a TalkJS Inbox
		 *
		 * @param  array   $props
		 * @param  boolean $echo
		 *
		 * @return String (html)
		 */
		function talkjs_inbox( $props = array(), $echo = true )
		{
			$html = \TalkJS\Facades\Inbox::make( $props )->getHtml();

			if( $echo )
				echo $html;

			return $html;
		}

	}
