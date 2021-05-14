<?php

    namespace TalkJS\Contracts;
    
    abstract class EventsInstance extends StaticInstance {
    
    
        /**
         * Private constructor. Avoid building instances using the
         * 'new' keyword.
         */
        protected function __construct(){
            $this->listen();
        }
    
    
        /**
         * Listen to events
         *
         * @return void
         */
        abstract public function listen();
    
    
    } 