<?php

namespace App\Providers;

use App\Events\PaymentCreated;
use App\Events\PaymentSubmittedForApproval;
use App\Events\PlanPurchaseCreated;
use App\Events\PropertySubmittedForApproval;
use App\Events\UserRegistered;
use App\Listeners\CreateNotificationForPaymentCreated;
use App\Listeners\CreateNotificationForPaymentSubmittedForApproval;
use App\Listeners\CreateNotificationForPlanPurchaseCreated;
use App\Listeners\CreateNotificationForPropertySubmittedForApproval;
use App\Listeners\CreateNotificationForUserRegistered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        PlanPurchaseCreated::class => [
            CreateNotificationForPlanPurchaseCreated::class,
        ],
        PaymentCreated::class => [
            CreateNotificationForPaymentCreated::class,
        ],
        UserRegistered::class => [
            CreateNotificationForUserRegistered::class,
        ],
        PropertySubmittedForApproval::class => [
            CreateNotificationForPropertySubmittedForApproval::class,
        ],
        PaymentSubmittedForApproval::class => [
            CreateNotificationForPaymentSubmittedForApproval::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}