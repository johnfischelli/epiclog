<?php

namespace EpicLog;

use Illuminate\Support\Facades\Facade;

class EpicLogFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'epiclog';
    }
}
