<?php

namespace Sendportal\Base\Facades;

use Illuminate\Support\Facades\Facade;

class Helper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'sendportal.helper';
    }
}
