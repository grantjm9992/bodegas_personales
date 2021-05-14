<?php

	namespace TalkJS\Chat;

	use TalkJS\Traits\MakesConversation;

	class Manager extends MakesConversation{


		/**
		 * Render the specific html needed for this object
		 *
		 * @return void
		 */
		public function render()
		{
			$this->setConversation();

			echo 'var chatbox = talkSession.createChatbox(conversation);';
			echo 'chatbox.mount(document.getElementById("talkjs-chat-container"));';

		}


		/**
		 * Render the container this inbox is supposed to be in
		 *
		 * @return String
		 */
		public function renderContainer()
		{
			echo '<div id="talkjs-chat-container" style="' . $this->getContainerStyle() . '"></div>';
		}

	}
