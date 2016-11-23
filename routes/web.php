<?php

use Illuminate\Support\Facades\URL;

$test = function () {
    dump(__('Translated message.'));

    // use same locale...
    dump(url('some/url'));

    // use custom locale...
    dump(url_with_locale('ar', 'some/url'));

    // use no locale...
    dump(url_without_locale('some/url'));
};


Route::group(['middleware' => 'localize'], function () use ($test) {
    Route::get('/', $test);

    Route::get('/test', $test)->name('test');
});

Route::get('/test2', $test)->middleware('localize');

Route::get('/nolocale', $test);


function url_without_locale($path)
{
    $formatter = URL::pathFormatter();

    URL::formatPathUsing(function ($path) {
        return $path;
    });

    return tap(url($path), function ($path) use ($formatter) {
        URL::formatPathUsing($formatter);
    });
}

function url_with_locale($locale, $path)
{
    $formatter = URL::pathFormatter();

    URL::formatPathUsing(function ($path) use ($locale) {
        return rtrim('/'.$locale.$path, '/');
    });

    return tap(url($path), function ($path) use ($formatter) {
        URL::formatPathUsing($formatter);
    });
}