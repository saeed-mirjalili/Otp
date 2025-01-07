<?php

namespace Saeed\Otp;

use Illuminate\Support\Facades\Facade;

class OtpFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'otp';
    }
}
