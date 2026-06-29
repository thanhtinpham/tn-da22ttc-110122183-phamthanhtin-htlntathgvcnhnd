<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            static $levels = null;
            static $topics = null;
            
            if ($levels === null) {
                if (app()->runningInConsole()) {
                    $levels = collect();
                    $topics = collect();
                } else {
                    $levels = \App\Models\Capdonghe::all();
                    $topics = \App\Models\Chude::all();
                }
            }
            
            $view->with('all_levels', $levels);
            $view->with('all_topics', $topics);
        });
    }

}