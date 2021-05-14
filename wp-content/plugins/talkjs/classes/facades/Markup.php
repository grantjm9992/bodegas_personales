<?php

namespace TalkJS\Facades;

class Markup extends Facade {

    /**
     * Return the igniter service key responsible for the Markup class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'markup';
    }

}
