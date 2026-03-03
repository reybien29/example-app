<?php

use App\Http\Controllers\Api\V1\External\UserIntegrationController;
use App\Http\Controllers\Api\V1\Internal\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('internal')
        ->middleware(['auth.internal-api', 'throttle:internal-api'])
        ->name('api.v1.internal.')
        ->group(function (): void {
            Route::apiResource('users', UserController::class);
        });

    Route::prefix('external')
        ->middleware(['auth.external-integration', 'throttle:external-api'])
        ->name('api.v1.external.')
        ->group(function (): void {
            Route::get('users', [UserIntegrationController::class, 'index'])->name('users.index');
            Route::get('users/{user}', [UserIntegrationController::class, 'show'])->name('users.show');
            Route::post('users/upsert', [UserIntegrationController::class, 'upsert'])->name('users.upsert');
        });
});
