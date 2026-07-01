<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AcceptInviteController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\InvitePartnerController;
use App\Http\Controllers\Auth\JoinCoupleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DeviceTokenController;
use App\Http\Controllers\JourneyStageController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\MedicationScheduleController;
use App\Http\Controllers\MedicationTakenLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationPreferenceController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PractitionerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('login', [LoginController::class, 'login']);
        Route::post('google', [GoogleAuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [MeController::class, 'show']);
            Route::put('me', [MeController::class, 'update']);
            Route::post('logout', [LogoutController::class, 'logout']);
            Route::get('invite-partner', [InvitePartnerController::class, 'current']);
            Route::post('invite-partner', [InvitePartnerController::class, 'invite']);
            Route::delete('invite-partner/{id}', [InvitePartnerController::class, 'cancel']);
            Route::get('my-invitations', [InvitePartnerController::class, 'received']);
            Route::delete('my-invitations/{id}', [InvitePartnerController::class, 'decline']);
            Route::post('join-couple', [JoinCoupleController::class, 'join']);
        });
        Route::post('accept-invite/{token}', [AcceptInviteController::class, 'accept']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('appointments', AppointmentController::class);
        Route::patch('appointments/{id}/complete', [AppointmentController::class, 'markComplete']);

        Route::apiResource('medications', MedicationController::class);
        Route::get('medications/{id}/schedules', [MedicationScheduleController::class, 'index']);
        Route::post('medications/{id}/schedules', [MedicationScheduleController::class, 'store']);

        Route::apiResource('schedules', MedicationScheduleController::class)->except(['create', 'edit']);
        Route::get('schedules/{id}/history', [MedicationTakenLogController::class, 'history']);
        Route::post('schedules/{id}/mark-taken', [MedicationTakenLogController::class, 'markTaken']);
        Route::put('schedules/{id}/mark-not-taken', [MedicationTakenLogController::class, 'markNotTaken']);

        Route::post('devices/register', [DeviceTokenController::class, 'register']);
        Route::delete('devices/{id}', [DeviceTokenController::class, 'revoke']);

        Route::get('partner', [PartnerController::class, 'show']);
        Route::delete('partner/{id}', [PartnerController::class, 'remove']);

        Route::apiResource('journey-stages', JourneyStageController::class);
        Route::post('journey-stages/{id}/close', [JourneyStageController::class, 'close']);
        Route::apiResource('practitioners', PractitionerController::class);
        Route::apiResource('notification-preferences', NotificationPreferenceController::class);

        Route::get('notifications', [NotificationController::class, 'index']);
        Route::get('notifications/{id}', [NotificationController::class, 'show']);
    });

    Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('users', [AdminController::class, 'usersApi']);
            Route::delete('users/{user}', [AdminController::class, 'deleteUserApi']);
        });
    });

    Route::prefix('admin')->group(function () {
        Route::get('stats', [AdminController::class, 'stats']);
    });
});
