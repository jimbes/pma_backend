<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\DeviceToken;
use App\Models\Medication;
use App\Models\MedicationSchedule;
use App\Models\Notification;
use App\Policies\AppointmentPolicy;
use App\Policies\DeviceTokenPolicy;
use App\Policies\MedicationPolicy;
use App\Policies\MedicationSchedulePolicy;
use App\Policies\NotificationPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Appointment::class => AppointmentPolicy::class,
        Medication::class => MedicationPolicy::class,
        MedicationSchedule::class => MedicationSchedulePolicy::class,
        DeviceToken::class => DeviceTokenPolicy::class,
        Notification::class => NotificationPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }

    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            \Illuminate\Support\Facades\Gate::policy($model, $policy);
        }
    }
}
