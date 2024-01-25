<?php

namespace App\Providers;

use App\Listeners\EmailEvent;
use App\Events\Alerts\AlertEvent;
use App\Events\Alerts\EmailConclusionEvent;
use App\Events\Tasks\TaskCreated;

use App\Events\Tasks\TaskCustomer;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Events\Alerts\EmailNotifyEvent;
use App\Events\ChatEmail\ChatEmailEvent;
use App\Events\Tasks\DispatchTaskReport;
use App\Events\Tasks\DispatchTasksToUser;
use App\Listeners\Tasks\TaskNotification;
use App\Events\TeamMember\TeamMemberEvent;
use App\Listeners\AlertEmail\EmailConclusionNotification;
use App\Listeners\Tasks\TaskCustomerNotification;
use App\Listeners\ChatEmail\ChatEmailNotification;
use App\Listeners\Tasks\SendTaskReportNotification;
use App\Listeners\AlertEmail\EmailNotifyNotification;
use App\Listeners\Tasks\SendDispatchTasksNotification;
use App\Listeners\AlertEmail\SendAlertEmailNotification;
use App\Listeners\TeamMember\SendTeamMemberNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        DispatchTasksToUser::class => [
            SendDispatchTasksNotification::class
        ],
        MessageSending::class =>[
            EmailEvent::class,
        ],
        DispatchTaskReport::class => [
            SendTaskReportNotification::class,
        ],
        TeamMemberEvent::class => [
            SendTeamMemberNotification::class,
        ],
        AlertEvent::class => [
            SendAlertEmailNotification::class,
        ],
        ChatEmailEvent::class => [
            ChatEmailNotification::class,
        ],
        TaskCreated::class => [
            TaskNotification::class,
        ],
        TaskCustomer::class => [
            TaskCustomerNotification::class
        ],
        EmailNotifyEvent::class => [
            EmailNotifyNotification::class
        ],
        EmailConclusionEvent::class => [
            EmailConclusionNotification::class
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
