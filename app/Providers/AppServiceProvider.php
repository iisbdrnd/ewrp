<?php

namespace App\Providers;

use App\Model\EwCandidatesCV;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       // Gate::define('accesToMobiization',function(){
       //  $userIds = EwCandidatesCV::
       // }); 
    }
}
