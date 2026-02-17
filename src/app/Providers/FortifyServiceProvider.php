<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::createUsersUsing(CreateNewUser::class);
        // ユーザー登録の処理をセット

        Fortify::registerView(function () {
            return view('auth.register');
        });
        //GETメソッドで/registerにアクセスしたときに表示するviewファイル

        Fortify::loginView(function () {
            return view('auth.login');
        });
        //GETメソッドで/loginにアクセスしたときに表示するviewファイル

        RateLimiter::for('login', function(Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);
        // Fortify はデフォルトで内部の LoginRequest を使っている。カスタムApp\Http\Requests\LoginRequest を使わせるには、FortifyServiceProvider にバインドを追加する必要がある。
    }
}