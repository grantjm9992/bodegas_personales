<?php

namespace TalkJS\Facades;

class SettingsPage extends Facade {

    /**
     * Return the igniter service key responsible for the SettingsPage class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'settings-page';
    }

}
