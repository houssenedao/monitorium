<?php

namespace App;

use App\Providers\AppServiceProvider;

class Kernel
{
    public static function boot()
    {
        return (object) [
            'app' => AppServiceProvider::setup(),
            'container' => AppServiceProvider::container()
        ];
    }
}