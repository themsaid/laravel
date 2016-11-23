<?php

namespace App;

class Localizer
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function localizeRoutes($router)
    {
        foreach ($router->getRoutes() as $route) {
            if ($this->routeShouldBeLocalized($route)) {
                foreach (config('app.locales') as $locale) {
                    $this->addRouteForLocale($route, $locale, $router);
                }
            }
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

        if ($routeName = $route->getName()) {
            $route->name('.'.$locale);
        }

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
