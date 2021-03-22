<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        // $request->server->set('HTTP_HOST', env('APP_URL'));
        $this->app['url']->forceRootUrl(env('APP_URL'));
        // echo $request->server->get('HTTP_HOST');
    }
}
