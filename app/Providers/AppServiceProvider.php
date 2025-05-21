<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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
        // $link = public_path('storage');
        // $target = storage_path('app/public');


        // if (file_exists($link) && !is_link($link)) {
        //     File::deleteDirectory($link);
        // }

        // if (!file_exists($link)) {
        //     Artisan::call('storage:link');
        // }
    }
}
