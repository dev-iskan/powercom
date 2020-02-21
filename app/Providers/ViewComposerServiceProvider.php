<?php

namespace App\Providers;

use App\Models\Products\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layout.header'], function ($view) {
            $categories = Category::parents()->with('children')->orderBy('name')->get();
            $view->with(compact('categories'));
        });
    }
}
