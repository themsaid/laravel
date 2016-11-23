<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class Localize
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
        $defaultLocale = config('app.locale');

        if (! $this->requestPathHasLocale($request)) {
            return redirect(trim($defaultLocale.'/'.$request->path(), '/'));
        }

        $locale = $this->getLocaleFromRequest($request);

        App::setLocale($locale);

        URL::formatPathUsing(function ($path) use ($locale) {
            return rtrim('/'.$locale.$path, '/');
        });

        return $next($request);
    }

    /**
     * Determine if the request path contains locale information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function requestPathHasLocale($request)
    {
        return in_array($request->segment(1), config('app.locales'));
    }

    /**
     * Extract the locale from the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function getLocaleFromRequest($request)
    {
        return $this->requestPathHasLocale($request) ?
                        $request->segment(1) : config('app.locale');
    }
}
