<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $raw_locale = $request->session()->get('locale');
        if (in_array($raw_locale, Config::get('app.locales'))) {
            $locale = $raw_locale;
        } else $locale = Config::get('app.locale');
        App::setLocale($locale);
        return $next($request);
    }
}
