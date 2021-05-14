<?php

	namespace TalkJS\Admin\Ui;

	use TalkJS\Traits\HasProperties; //supporting 5.3, means we don't get to use actual traits here

	class FieldBuilder extends HasProperties{


		/**
		 * Field name
		 *
		 * @var String
		 */
		protected $name;


		/**
		 * Field type
		 *
		 * @var String
		 */
		protected $type;



		/**
		 * Html of the field
		 *
		 * @var String
		 */
		protected $html;


		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->html = '';
		}


		/**
		 * Magic method for field types:
		 *
		 * @param  String $type
		 * @param  Array $attributes
		 *
		 * @return String
		 */
		public function __call( $type, $attr = array() )
		{
			$this->name = $attr[0];
			$this->type = $type;

			$props = ( isset( $attr[1] ) ? $attr[1] : array() );
			$this->properties = $this->sanitizeProps( $props );

			return $this;
		}


		/**
		 * Render the field html
		 *
		 * @return String
		 */
		public function render()
		{

			ob_start();

			$this->{$this->type}();

			//set the field output as html, and wrap it:
			$this->html = $this->wrap( ob_get_clean() );

			return $this->html;
		}


		/**
		 * Display the field html
		 *
		 * @return String
		 */
		public function display()
		{
			echo $this->render();
		}



		/**************************************************/
		/**       Render functions                        */
		/**************************************************/

		/**
		 * Input field
		 *
		 * @return String
		 */
		protected function text()
		{

			$this->renderLabel();

			echo '<input type="text" name="'.esc_attr( $this->name ).'" ';

				if( !is_null( $this->getValue() ) )
					echo 'value="'.esc_attr( $this->getValue() ).'" ';

				if( !is_null( $this->getProp( 'placeholder' ) ) )
					echo 'placeholder="'.esc_attr( $this->getProp( 'placeholder' ) ).'" ';

				$this->renderId();
				$this->renderClasses();
				$this->renderValidation();

			echo '>';

		}


		/**
		 * Render a textarea
		 *
		 * @return String
		 */
		protected function textarea()
		{
			$this->renderLabel();

			echo '<textarea name="'.esc_attr( $this->name ).'" ';

				if( !is_null( $this->getProp( 'placeholder' ) ) )
					echo 'placeholder="'.esc_attr( $this->getProp( 'placeholder' ) ).'" ';

				$this->renderId();
				$this->renderClasses();
				$this->renderValidation();

			echo '>';

				if( !is_null( $this->getValue() ) )
					echo $this->getValue();

			echo '</textarea>';
		}


		/**
		 * Render a checkbox
		 *
		 * @return String
		 */
		protected function checkbox()
		{
			 //add a hidden field before the checkbox, if not checked:
       		echo '<input type="hidden" name="'.esc_attr( $this->name ).'" value="false"/>';

       		echo '<input type="checkbox" name="'.esc_attr( $this->name ).'" ';

            	echo 'value="true" ';

            	if( $this->getValue() )
                	echo 'checked ';

				$this->renderValidation();
				$this->renderId();
				$this->renderClasses();

        	echo '/>';

        	$this->renderLabel();

		}


		/**
		 * Render a select field
		 *
		 * @return String
		 */
		protected function select()
		{

			if( !is_null( $this->getProp( 'choices' ) ) ){

				$this->renderLabel();

				$name = $this->name;
				if( $this->getProp( 'multiple' ) )
					$name .= '[]';

				echo '<select name="'.esc_attr( $name ).'" ';

					if( $this->getProp( 'multiple' ) )
					    echo ' multiple';

				echo '>';


				foreach( $this->getProp( 'choices' ) as $choice ){

					echo '<option value="'.esc_attr( $choice['value'] ).'" ';

						if( $this->isSelected( $choice['value'] ) )
							echo 'selected ';

					echo '>';

						echo esc_html( $choice['label'] );

					echo '</option>';

				}

				echo '</select>';

			}else{
				echo '';
			}
		}


        /**
         * Returns the HTML for checkboxes
         *
         * @return String
         */
        protected function checkboxes(){

            if( !is_null( $this->getProp( 'choices' ) ) ){

                $this->renderLabel();

                $i = 0;
                foreach( $this->getProp( 'choices' ) as $choice ){


                    $name = $this->name."[{$i}]";

                    echo '<label>';
                        echo '<input type="checkbox" name="'.esc_attr( $name ).'" ';
                            echo 'value="'.esc_attr( $choice['value'] ).'" ';

                            if( $this->isSelected( $choice['value'] ) )
                                echo 'checked ';

                            $this->renderValidation();
                            $this->renderId();
                            $this->renderClasses();

                        echo '/>';

                        echo '<span>'.esc_html( $choice['label'] ).'</span>';

                    echo '</label>';
                    $i++;

                }

            }else{
                echo '';
            }
        }

		/**
		 * Check wether or not a select item has been selected
		 *
		 * @param String $choiceValue
		 *
		 * @return boolean
		 */
		public function isSelected( $choiceValue )
		{
			$value = $this->getValue();

			if( !is_array( $value ) && $value == $choiceValue )
				return true;

			if( is_array( $value ) && in_array( $choiceValue, $value ) )
				return true;

			return false;
		}


		/**
		 * Render the field ID
		 *
		 * @return String
		 */
		protected function renderId()
		{
			echo 'id="'.esc_attr( $this->getFieldId() ).'" ';
		}


		/**
		 * Render a label
		 *
		 * @return String
		 */
		protected function renderLabel()
		{
			if( !is_null( $this->getProp( 'label' ) ) )
				echo '<label for="'.esc_attr( $this->getFieldId() ).'">'.esc_html( $this->getProp( 'label' ) ).'</label>';
		}


		/**
		 * Render validation options
		 *
		 * @return String
		 */
		protected function renderValidation()
		{

			if( $this->getProp( 'validation' ) )
				echo 'data-validate="'.esc_attr( $this->getProp( 'validation-string' ) ).'" ';
		}


		/**
		 * Render field classes
		 *
		 * @return String
		 */
		protected function renderClasses( $prop = 'classes', $echo = true )
		{

			$class = 'field field-type-'.$this->type;

			if( $prop == 'wrapper-classes' )
				$class = 'field-wrapper field-'.$this->type;


			if( !is_null( $this->getProp( $prop ) ) )
				$class .= ' '.implode( ' ', $this->getProp( $prop ) );

			if( $echo )
				echo 'class="'.esc_attr( $class ).'" ';

			return $class;
		}


		/**
		 * Field wrapper
		 *
		 * @param String $fieldHtml
		 *
		 * @return String
		 */
		protected function wrap( $fieldHtml )
		{

            $class = esc_attr( $this->renderClasses( 'wrapper-classes', false ) );
			return "<div class='{$class}'>{$fieldHtml}</div>";

		}


		/**
		 * Get the field ID
		 *
		 * @return String
		 */
		public function getFieldId()
		{
			return 'field-'.$this->name.'-'.$this->type;
		}


		/**
		 * Get the field name
		 *
		 * @return String
		 */
		public function getName(){
			return $this->name;
		}


		/**
		 * Get the fields value
		 *
		 * @return String
		 */
		public function getValue(){

			if( !is_null( $this->getProp( 'value' ) ) )
				return $this->getProp( 'value' );


			return $this->getProp( 'defaultValue' );
		}


		/**************************************************/
		/**       Properties                              */
		/**************************************************/

		/**
		 * Attribute sanitizer
		 *
		 * @return Array
		 */
		public function sanitizeProps( $props )
		{

			if( isset( $props['validation'] ) ){
				if( !is_array( $props['validation'] ) ) $props['validation'] = array( $props['validation'] );
				$props['validation-string'] = implode( ',', $props['validation'] );
			}

			return $props;
        }

   		/**************************************************/
		/**       Validation                              */
		/**************************************************/

        /**
         * Check if this field has validation parameters
         *
         * @return boolean
         */
        public function hasValidation()
        {
            return ( $this->getProp( 'validation', false ) ? true : false );
        }

        /**
         * Returns the validation parameters
         *
         * @return Array
         */
        public function getValidation(){

            $validators = $this->getProp('validation', array());

            if( !empty( $validators ) )
                return $validators[0];

            return null;
        }




    }
