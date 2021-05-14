<?php

namespace TalkJS\Facades;

class Notification extends Facade {

    /**
     * Return the igniter service key responsible for the Notification class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'notification';
    }

}
