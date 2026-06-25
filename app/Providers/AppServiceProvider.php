<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\DeviceToken;
use App\Models\JourneyStage;
use App\Models\Medication;
use App\Models\MedicationSchedule;
use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\Practitioner;
use App\Policies\AppointmentPolicy;
use App\Policies\DeviceTokenPolicy;
use App\Policies\JourneyStagePolicy;
use App\Policies\MedicationPolicy;
use App\Policies\MedicationSchedulePolicy;
use App\Policies\NotificationPolicy;
use App\Policies\NotificationPreferencePolicy;
use App\Policies\PractitionerPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Appointment::class => AppointmentPolicy::class,
        Medication::class => MedicationPolicy::class,
        MedicationSchedule::class => MedicationSchedulePolicy::class,
        DeviceToken::class => DeviceTokenPolicy::class,
        Notification::class => NotificationPolicy::class,
        JourneyStage::class => JourneyStagePolicy::class,
        Practitioner::class => PractitionerPolicy::class,
        NotificationPreference::class => NotificationPreferencePolicy::class,
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
        Schema::defaultStringLength(191);
        $this->registerPolicies();
    }

    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            \Illuminate\Support\Facades\Gate::policy($model, $policy);
        }
    }
}
