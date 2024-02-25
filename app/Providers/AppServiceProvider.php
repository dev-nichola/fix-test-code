<?php

namespace App\Providers;

use App\Services\Implementation\TransactionDetailServiceImpl;
use App\Services\TransactionDetailService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
require \base_path("app/Helpers/Helpers.php");

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public array $singletons = [
        TransactionDetailService::class => TransactionDetailServiceImpl::class
    ];

    public function provides()
    {
        return [TransactionDetailService::class];
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
