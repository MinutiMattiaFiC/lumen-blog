<?php
namespace App\Providers;

use App\User;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {

    public function register() {
    }

    public function boot() {
        $this->app['auth']->viaRequest('api', function ($request) {
            $header = $request->header('Api-Token');
            if ($header) {
                return new User();
            }
            return null;
        });
    }

}
