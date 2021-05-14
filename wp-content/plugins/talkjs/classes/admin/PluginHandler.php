<?php

    namespace TalkJS\Admin;

    use TalkJS\Helpers\Url;
    use TalkJS\Helpers\Settings;
	use TalkJS\Inbox\PageGenerator;

    class PluginHandler{

        /**
         * This runs on plugin activation
         *
         * @return void
         */
        public function activate(){

            //delete the activation trigger:
            delete_option( 'talkjs_activated' );

			//maybe generate an inbox-page, if we haven't already:
			$pageGenerator = new PageGenerator();
            $pageGenerator->maybeGenerate();

            Settings::setOnboarding();

            wp_redirect( Url::onboardingPage() );
            exit();

        }

        /**
         * This runs on plugin deactivation
         *
         * @return void
         */
        public function deactivate(){

        }

    }
