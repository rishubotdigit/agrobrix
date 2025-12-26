<?php

namespace App\Providers;

use App\Events\InquiryCreated;
use App\Events\PaymentApproved;
use App\Events\PaymentCreated;
use App\Events\PaymentSubmittedForApproval;
use App\Events\PlanPurchaseCreated;
use App\Events\PropertyApproved;
use App\Events\PropertyRejected;
use App\Events\PropertySubmittedForApproval;
use App\Events\UserRegistered;
use App\Listeners\CreateNotificationForPaymentApproved;
use App\Listeners\CreateNotificationForPaymentCreated;
use App\Listeners\CreateNotificationForPaymentSubmittedForApproval;
use App\Listeners\CreateNotificationForPlanPurchaseCreated;
use App\Listeners\CreateNotificationForPropertyApproved;
use App\Listeners\CreateNotificationForPropertyRejected;
use App\Listeners\CreateNotificationForPropertySubmittedForApproval;
use App\Listeners\CreateNotificationForUserRegistered;
use App\Listeners\NotifyAdminsOfPaymentApproved;
use App\Listeners\NotifyAdminsOfPlanPurchase;
use App\Listeners\NotifyAdminsOfPropertyApproved;
use App\Listeners\NotifyBuyerOfInquiryConfirmation;
use App\Listeners\NotifyOwnerOfNewInquiry;
use App\Listeners\SendInvoiceEmail;
use App\Listeners\SendPaymentApprovedEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        InquiryCreated::class => [
            NotifyBuyerOfInquiryConfirmation::class,
            NotifyOwnerOfNewInquiry::class,
        ],
        PlanPurchaseCreated::class => [
            CreateNotificationForPlanPurchaseCreated::class,
            NotifyAdminsOfPlanPurchase::class,
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
        PaymentApproved::class => [
            CreateNotificationForPaymentApproved::class,
            NotifyAdminsOfPaymentApproved::class,
            SendPaymentApprovedEmail::class,
            SendInvoiceEmail::class,
        ],
        PropertyApproved::class => [
            CreateNotificationForPropertyApproved::class,
            NotifyAdminsOfPropertyApproved::class,
        ],
        PropertyRejected::class => [
            CreateNotificationForPropertyRejected::class,
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