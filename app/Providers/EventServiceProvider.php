<?php

namespace App\Providers;

use App\Events\CustomerCreated;
use App\Events\OrderCreated;
use Illuminate\Auth\Events\Registered;
use App\Listeners\HandleCustomerCreated;
use App\Listeners\HandleOrderCreated;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class
        ],

        CustomerCreated::class => [
            HandleCustomerCreated::class
        ],

        OrderCreated::class => [
            HandleOrderCreated::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
