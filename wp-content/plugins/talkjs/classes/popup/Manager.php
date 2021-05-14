<?php

	namespace TalkJS\Popup;

	use TalkJS\Helpers\Settings;
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
			echo $this->getPopupMountSnippet();

		}

		/**
		 * The popup doesn't need a container, so return an empty string
		 *
		 * @return String
		 */
		public function renderContainer()
		{
			echo "";
		}

		/**
		 * Retuns popup mount code
		 *
		 * @return String
		 */
		private function getPopupMountSnippet() {
			// loadOpen is deprecated (it really meant keepOpen / show: true) - kept for backwards compatiblity
			$keepOpen = Settings::get( 'keepLivePopup', false ) === "true" || $this->getProp( 'loadOpen', false ) === "true" || $this->getProp( 'keepOpen', false ) === "true";

			if($keepOpen) {
				return 'var popup = talkSession.createPopup(conversation, { keepOpen: true, launcher: "always", showCloseInHeader: true }); popup.mount({show: false});';
			} else {
				return 'var popup = talkSession.createPopup(conversation, { keepOpen: false, launcher: "always", showCloseInHeader: true }); popup.mount({show: false});';
			}	
		}
	}
