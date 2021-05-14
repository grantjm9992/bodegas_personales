<?php

	namespace TalkJS\Chat;

	use WP_Widget;
	use TalkJS\Helpers\User;
	use TalkJS\Facades\Chat;
	use TalkJS\Facades\Field;
	use TalkJS\Helpers\Settings;

	class Widget extends WP_Widget {

		/**
		 * Constructor
		 */
		function __construct()
		{
			parent::__construct(
				'talkjs',
		 		__('TalkJS Chat', 'talkjs'),
				array(
					'classname' => 'talkjs',
					'description' => __(
						'TalkJS Chat application as a widget',
						'talkjs'
					)
				)
			);
		}


	 	/**
	 	 * Frontend function
	 	 *
	 	 * @param  Array $args
	 	 * @param  Array $instance
	 	 *
	 	 * @return String (html, echoed )
	 	 */
		public function widget( $args, $instance )
		{
			$title = apply_filters( 'widget_title', $instance['title'] );

			// before and after widget arguments are defined by themes
			echo $args['before_widget'];
			if ( ! empty( $title ) )
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];

			Chat::make( $instance )->display();
		}


		/**
		 * Backend UI
		 *
		 * @param  Array $instance
		 *
		 * @return void
		 */
		public function form( $instance )
		{

			$fields = $this->getFields( $instance );

			foreach( $fields as $field ){
				$field->display();
			}
		}


		/**
		 * Saving a widget instance
		 *
		 * @param  Array $newInstance
		 * @param  Array $oldInstance
		 *
		 * @return Array
		 */
		public function update( $newInstance, $oldInstance ) {

			$instance = array();
			$instance['title'] = ( ! empty( $newInstance['title'] ) ) ? strip_tags( $newInstance['title'] ) : '';
			$instance['welcome'] = ( !empty( $newInstance['welcome' ] ) ) ? strip_tags( $newInstance['welcome'] ) : '';

			$instance['chatWith'] = ( !empty( $newInstance['chatWith'] ) ) ? $newInstance['chatWith'] : array( 'author' );

			return $instance;

		}


		/**
		 * Returns the fields for this widget in an array
		 *
		 * @param  Array $instance
		 *
		 * @return Array
		 */
		public function getFields( $instance )
		{
			$welcome = ( isset( $instance['welcome'] ) ) ? $instance['welcome'] : '';
			$choice = array( array( 'value' => 'author', 'label' => __( 'Author of the page the widget is on', 'talkjs') ) );
			$choices = $choice + User::getAll();

			if( $welcome == '' )
				$welcome = Settings::get( 'welcomeMessage' );


			$fields = array(
				Field::text( 'title', array(
					'value' => $instance['title' ],
					'label' => __( 'Widget title', 'talkjs' )
				) ),
				Field::textarea( 'welcome', array(
					'value' => $welcome,
					'label' => __( 'Welcome message', 'talkjs' )
				) ),
				Field::select( 'chatWith', array(
					'label' => __( 'Chat with...', 'talkjs' ),
					'choices' => $choices
				) )
			);

			$fields = apply_filters( 'talkjs_widget_fields', $fields );
			return $fields;
		}

	}
