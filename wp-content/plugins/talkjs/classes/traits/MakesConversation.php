<?php

	namespace TalkJS\Traits;

	use TalkJS\Helpers\User;
	use TalkJS\Helpers\Settings;

	class MakesConversation extends ImplementsTalkSession{


		/**
		 * Get the conversation user
		 *
		 * @return String
		 */
		public function setConversation()
		{
			$type = $this->getProp( 'userType' );
			$_user = $this->getProp( 'user', null );
			$user = json_encode( User::getTalkObject( $type, $_user ) );
			echo "var user = new Talk.User({$user});";

			$topicId = $this->getProp( 'topicid' );
			$subject = $this->getProp( 'subject' );

			$options = array();
			if ($topicId) {
				$options['topicId'] = $topicId;
			}

			if ($subject) {
				$options['subject'] = $subject;
			}

			if (count($options) > 0) {
				$options = json_encode($options);
				echo 'var conversation = talkSession.getOrStartConversation(user, ' . $options . ');';
			} else {
				echo 'var conversation = talkSession.getOrStartConversation(user);';
			}
		}


		/**
		 * Attribute sanitizer
		 *
		 * @return Array
		 */
		public function sanitizeProps( $props )
		{
			$props = parent::sanitizeProps( $props );

			if( !isset( $props['userType' ] ) )
				$props[ 'userType' ] = 'author';

			if( !isset( $props['user'] ) )
				$props[ 'user' ] = null;

			return $props;
		}
	}
