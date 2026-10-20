<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported locales; the first one is the fallback.
     */
    private const SUPPORTED = ['en', 'ar'];

    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale($request->getPreferredLanguage(self::SUPPORTED) ?? self::SUPPORTED[0]);

        return $next($request);
    }
}
