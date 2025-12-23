<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\MergeCartListener;

class EventServiceProviders extends ServiceProvider
{
    protected $listen = [
    Login::class => [
        MergeCartListener::class,
    ],
];

    public function boot(): void
    {
        
    }
}