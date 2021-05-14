<?php

    namespace TalkJS\Contracts;
    
    abstract class AssetLoaderInstance extends StaticInstance {
    
    
        /**
         * Private constructor. Avoid building instances using the
         * 'new' keyword.
         */
        protected function __construct(){
            $this->load();
        }
    
    
        /**
         * Listen to events
         *
         * @return void
         */
        abstract public function load();
    
    
    } 