<?php

namespace App\Providers;

use App\Models\Karyawan;
use App\Models\Role;
use App\Observers\KaryawanObserver;
use App\Observers\RoleObserver;
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
        Role::observe(RoleObserver::class);
        Karyawan::observe(KaryawanObserver::class);
    }
}
