<?php

namespace App\Providers;
use Auth;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view){
            if(Auth::check()){
                $authUser = Auth::user()->load(['employee.division', 'employee.responsibility']);
                $view->with('authUser',$authUser);
            }
        });
    }
}
