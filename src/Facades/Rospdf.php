<?php

namespace Vieira\Rospdf\Facades;

use Illuminate\Support\Facades\Facade;

class Rospdf extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Rospdf';
    }
}
