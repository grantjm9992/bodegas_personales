<?php

	namespace TalkJS\Inbox;

	use TalkJS\Traits\ImplementsTalkSession;

	class Manager extends ImplementsTalkSession{


		/**
		 * Render the specific html needed for this object
		 *
		 * @return void
		 */
		public function render()
		{
			echo 'var inbox = talkSession.createInbox();';
			echo 'inbox.mount(document.getElementById("talkjs-inbox-container"));';
		}


		/**
		 * Render the container this inbox is supposed to be in
		 *
		 * @return String
		 */
		public function renderContainer()
		{
			echo '<div id="talkjs-inbox-container" style="' . $this->getContainerStyle() . '"></div>';
		}

	}
