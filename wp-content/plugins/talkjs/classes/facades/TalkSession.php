<?php

namespace TalkJS\Facades;

class TalkSession extends Facade {

    /**
     * Return the igniter service key responsible for the TalkSession class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'talk-session';
    }

}
