<?php

namespace App\Providers;

use App\Events\AssignedToTaskProductEvent;
use App\Events\ProductCreatedEvent;
use App\Events\TaskCreatedEvent;
use App\Events\TaskForwardedEvent;
use App\Observers\TaskObserver;
use App\Observers\HistoryObserver;
use App\History;
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
    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Task::observe(TaskObserver::class);
        History::observe(HistoryObserver::class);
        Product::observe(ProductObserver::class);
    }
}
