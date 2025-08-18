<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use App\Models\User;
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
        Paginator::useBootstrap();

        Gate::define('admin', function ($user) {
            return $user->role_id == User::ADMIN_ROLE_ID;
        });

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // ── Aiven MySQL: CA証明書を storage に書き出す ─────────────
        // Herokuはファイルをコミットできないため、環境変数に入れて起動時にファイル化します。
        $cert = env('AIVEN_CA_CERT');
        if (!empty($cert)) {
            $path = storage_path('mysql-ca.pem');
            // すでに同じ内容なら上書きしない（無駄なI/Oを避ける）
            if (!file_exists($path) || trim((string) @file_get_contents($path)) !== trim($cert)) {
                @file_put_contents($path, $cert);
            }
        }
    }
}
