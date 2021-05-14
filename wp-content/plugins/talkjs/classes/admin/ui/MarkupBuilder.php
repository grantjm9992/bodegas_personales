<?php

	namespace TalkJS\Admin\Ui;

    
	class MarkupBuilder{


        /**
         * String with text     
         *
         * @var text
         */
        protected $string;


        /**
         * Create a paragraph
         *
         * @param String $string
         * @return void
         */
        public function paragraph( $string )
        {
            $args = func_get_args();
            array_shift( $args );

            if( !empty( $args ) )
                $string = vsprintf( $string, $args );

                
            $string = apply_filters( 'the_content', $string );
            $this->string = $string;
            return $this;
        }

        /**
         * Create a heading
         *
         * @return void
         */
        public function heading( $string, $type, $elClass = '' ){
            
            $class = '';
            if( $elClass !== '' )
                $class = ' class="'.esc_attr( $elClass ).'"';

            $string = esc_html( $string );
            $this->string = "<{$type}{$class}>{$string}</{$type}>";
            return $this;
        }

        /**
         * Returns the string as html
         *
         * @return String (html)
         */
        public function render()
        {
            return $this->string;
        }

	    /**
		 * Display the paragraph html
		 * 
		 * @return String
		 */
		public function display()
		{
			echo $this->render();
		}

    }
