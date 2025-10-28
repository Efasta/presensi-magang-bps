<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Notif;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;
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
        Carbon::setLocale('id'); // untuk Carbon (tanggal lokal)
        App::setLocale('id');    // untuk Laravel translation bawaan (opsional)
        Model::preventLazyLoading();

        //Kirim data ke layout
        // View::composer('components.layout', function ($view) {
        //     $view->with('user', Auth::user());
        // });

        // Kirim data notifikasi ke semua view navbar
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // Ambil semua notifikasi milik user login (baik admin maupun bukan)
                $unreadCount = Notif::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                $recentNotifs = Notif::where('user_id', $user->id)
                    ->where('is_read', false) // hanya ambil notifikasi belum dibaca
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();


                $view->with(compact('unreadCount', 'recentNotifs'));
            }
        });
    }
}
