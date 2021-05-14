<?php

    namespace TalkJS\Contracts;
    
    abstract class AjaxInstance extends StaticInstance {
    

        /**
         * WordPress doesn't keep the post-global around on an ajax request, 
         * so we do it this way
         *
         * @return void
         */
        public function setPostGlobal(){
            
            global $post;
            if( !isset( $GLOBALS['post'] ) && isset( $_POST['post_id'] ) ){
                $GLOBALS['post'] = new stdClass();
                $GLOBALS['post']->ID = absint( $_POST['post_id'] );
            } 
        }
    
    
    } 