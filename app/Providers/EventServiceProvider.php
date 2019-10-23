<?php

namespace App\Providers;

use App\Events\ProductCreatedEvent;
use App\Events\TaskCreatedEvent;
use App\Events\TaskForwardedEvent;
use App\Listeners\HistoryListener;
use App\Observers\TaskObserver;
use App\Observers\ProductObserver;
use App\Product;
use App\Task;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TaskCreatedEvent::class => [
            HistoryListener::class
        ],
        TaskForwardedEvent::class => [
            HistoryListener::class
        ],
        ProductCreatedEvent::class => [
            HistoryListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Task::observe(TaskObserver::class);

        Product::observe(ProductObserver::class);
    }
}
