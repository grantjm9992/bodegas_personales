<?php

	namespace TalkJS\Traits;

    use TalkJS\Helpers\User;
    use TalkJS\Helpers\Roles;
	use TalkJS\Helpers\Messages;
	use TalkJS\Helpers\Settings;

	class ImplementsTalkSession extends HasProperties{


		/**
		 * The html string being produced
		 *
		 * @var String
		 */
		protected $html;

		/**
		 * The App's app ID
		 *
		 * @var String
		 */
		protected $appId;

		/**
		 * App's secret key
		 *
		 * @var String
		 */
		protected $secretKey;


		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->appId = Settings::appid( null );
			$this->secretKey = Settings::secretkey( null );
		}


		/**
		 * Make the JS output
		 *
		 * @return void
		 */
		public function make( $properties = array() )
		{
			global $talkjs_session;

			$this->properties = $this->sanitizeProps( $properties );

			if( $this->isValid() ){

				ob_start();

				$this->start();

				$this->render();

				$this->end();

				$this->renderContainer();

				$this->html = ob_get_clean();
			}

			return $this;
		}


		/**
		 * Display the Html
		 *
		 * @return String (html, echoed)
		 */
		public function display()
		{
			echo $this->getHtml();
		}


		/**
		 * Returns the html
		 *
		 * @return String
		 */
		public function getHtml()
		{
			$GLOBALS['talkjs_session'] = true;
			return $this->html;
		}


		/**
		 * Setup the basic talk session
		 *
		 * @return void
		 */
		protected function start()
		{
			global $talkjs_session;

			$user = User::getTalkObject( 'current' );

			if ( $this->getWelcomeMessage() ) {
				$user['welcomeMessage'] = $this->getWelcomeMessage();
			}

			$encoded_user = json_encode( $user );

			//only render the include script if it's not yet outputted on the page:
			if( !$talkjs_session )
				echo $this->includeScript();
			?>
			<script>
			Talk.ready.then(function() {

				var me = new Talk.User(<?php echo $encoded_user;?>);

				window.talkSession = new Talk.Session({
					appId: '<?php echo esc_html( $this->appId );?>',
					me: me,
					<?php
						if ( isset($user['id']) && isset($this->secretKey) ) {
							$user_signature = hash_hmac('sha256', strval($user['id']), $this->secretKey);
							echo "signature: '" . $user_signature . "'";
						}
					?>
				});

				var badge = document.getElementsByClassName("talkjs-inbox");
				var badgeSpan = document.getElementById( 'notification-badge' );

				if( badge.length > 0 && badgeSpan == null ){

					badge = badge[0].firstChild;
					var badgeHtml = document.createElement("div");
					badgeHtml.setAttribute( "id", "notification-badge" );
					badge.insertBefore( badgeHtml, badge.firstChild );

					var notificationBadge = document.getElementById("notification-badge");
					notificationBadge.classList.add('talkjs-hidden');

					window.talkSession.unreads.on( "change", function( conversationIds) {

						var amountOfUnreads = conversationIds.length;

						if( amountOfUnreads > 0 ){
							notificationBadge.innerHTML = '<span>'+amountOfUnreads+'</span>';
							notificationBadge.classList.remove('talkjs-hidden');
						}else{
							notificationBadge.classList.add('talkjs-hidden');
						}

					});

				}
			<?php

		}


		/**
		 * End the html output.
		 *
		 * @return void
		 */
		protected function end()
		{
			//end Talk.ready, then close the script
			echo '});</script>';
		}


		/**
		 * Render the unique bit
		 *
		 * @return void
		 */
		protected function render()
		{
			//nothing here
		}


		/**
		 * Returns the inline style string for the UI container
		 *
		 * @return void
		 */
		public function getContainerStyle()
		{
			$style = 'margin: 30px; min-height: 150px;';

			$width = $this->getProp( 'width', 'calc(100% - 60px)' );
			$style = $style . ' width: ' . $width . ';';

			$height = $this->getProp( 'height', false );
			if ($height) $style = $style . ' height: ' . $height . ';';

			$style = $this->getProp( 'style', $style );

			return $style;
		}

		/**
		 * Render the container
		 *
		 * @return void
		 */
		protected function renderContainer()
		{
			//nothing here
		}


		/**
		 * Can this script be outputted?
		 *
		 * @return bool
		 */
		public function isValid()
		{

			//don't allow output without an appId or secret key
			if( is_null( $this->appId ) || is_null( $this->secretKey ) ) {
				return false;
			}

 			//if the script only works for logged in users, check that:
 			if( !User::loggedIn() ) {
				return false;
			 }

			//no users are allowed to chat:
            if ( ! Settings::get('userTypes') ) {
                return false;
			}

            //if some roles are excluded:
            if( sizeof( Settings::get( 'userTypes' ) ) != sizeof( Roles::values() ) ){

                $notAllowed = array_diff( Roles::values(), Settings::get('userTypes') );
                foreach( $notAllowed as $role ){
                    if( User::hasRole( $role ) )
                        return false;
                }
            }


			return true;
		}


		/**
		 * Return the include script
		 *
		 * @return String
		 */
		protected function includeScript()
		{
			return '<script>(function(t,a,l,k,j,s){
				s=a.createElement(\'script\');s.async=1;s.src="https://cdn.talkjs.com/talk.js";a.getElementsByTagName(\'head\')[0].appendChild(s);k=t.Promise
				t.Talk={ready:{then:function(f){if(k)return new k(function(r,e){l.push([f,r,e])});l.push([f])},catch:function(){return k&&new k()},c:l}}
				})(window,document,[]);</script>';
		}


		/**
		 * Returns the welcome message for this conversation
		 *
		 * @return void
		 */
		public function getWelcomeMessage(){

			$message = $this->getProp( 'welcomeMessage' );
			$message = Messages::welcome( $message );
			return $message;

		}


		/**
		 * Attribute sanitizer
		 *
		 * @return Array
		 */
		public function sanitizeProps( $props )
		{
			$props = parent::sanitizeProps( $props );

			if (!is_array($props)) {
				$props = array();
			}

			if( !isset( $props['welcomeMessage'] ) ) {
				$props['welcomeMessage'] = Settings::get( 'welcomeMessage' );
			}

			return $props;
		}
	}

