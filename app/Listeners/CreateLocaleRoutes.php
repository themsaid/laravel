<?php

namespace App\Listeners;

use Illuminate\Routing\Events\RouteCreated;

class CreateLocaleRoutes
{
    /**
     * Handle the event.
     *
     * @param  RouteCreated  $event
     * @return void
     */
    public function handle(RouteCreated $event)
    {
        if (! $this->routeShouldBeLocalized($event->route)) {
            return;
        }

        foreach (config('app.locales') as $locale) {
            $this->addRouteForLocale($event->route, $locale, $event->router);
        }
    }

    /**
     * Add a route for the locale in the route collection.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  string  $locale
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    private function addRouteForLocale($route, $locale, $router)
    {
        $route = clone $route;

        $routeUri = $route->getUri();

        $route->setUri(
            $routeUri == '/' ? $locale : $locale.'/'.$routeUri
        );

        $router->getRoutes()->add($route);
    }

    /**
     * Determine if the route should be localized.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @return bool
     */
    private function routeShouldBeLocalized($route)
    {
        return in_array('localize', $route->gatherMiddleware());
    }
}
