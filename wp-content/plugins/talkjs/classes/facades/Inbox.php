<?php

namespace TalkJS\Facades;

class Inbox extends Facade {

    /**
     * Return the igniter service key responsible for the Inbox class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'inbox';
    }

}
