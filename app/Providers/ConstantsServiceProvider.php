<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConstantsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('profile_folder', function () {
            return public_path('images/profile_picture/');
        });

        $this->app->bind('profile_picture', function () {
            return asset('images/profile_picture');
        });

        $this->app->bind('default_image', function () {
            return asset('images/default-image.png');
        });
    }
}
