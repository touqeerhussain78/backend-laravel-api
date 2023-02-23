<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //appservice bind
        $this->app->bind('App\Contracts\Services\Logic\IUserAppService', 'App\Services\Logic\UserAppService');

        //repository bind
        $this->app->bind('App\Contracts\Repositories\IUserRepository', 'App\Repositories\UserRepository');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
