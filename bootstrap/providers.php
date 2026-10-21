<?php

use App\Providers\AppServiceProvider;
use App\Providers\RateLimitServiceProvider;
use App\Providers\ResponseMacroServiceProvider;

return [
    AppServiceProvider::class,
    RateLimitServiceProvider::class,
    ResponseMacroServiceProvider::class,
];
