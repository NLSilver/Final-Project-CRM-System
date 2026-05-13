<?php

use App\Http\Controllers\{
    UserController, DashboardController, CustomerController, 
    LeadController, ActivityController, FollowUpController, ReportController, TrashController
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware('can:admin')->group(function () {
        Route::resource('users', UserController::class);
        
        // Trash/System Archive
        Route::prefix('admin')->group(function () {
            Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
            Route::post('/trash/{id}/restore', [TrashController::class, 'restore'])->name('trash.restore');
            Route::delete('/trash/{id}/force', [TrashController::class, 'forceDelete'])->name('trash.forceDelete');
        });
    });
    

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show')->withTrashed();
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [LeadController::class, 'index'])->name('index');
        Route::get('/create', [LeadController::class, 'create'])->name('create');
        Route::post('/', [LeadController::class, 'store'])->name('store');
        Route::get('/{lead}/edit', [LeadController::class, 'edit'])->name('edit');
        Route::get('/{lead}', [LeadController::class, 'show'])->name('show')->withTrashed();
        Route::put('/{lead}', [LeadController::class, 'update'])->name('update');
        Route::post('/{lead}/convert', [LeadController::class, 'convert'])->name('convert');
        Route::post('/{lead}/update-status', [LeadController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{lead}', [LeadController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/', [ActivityController::class, 'index'])->name('index');
        Route::get('/create', [ActivityController::class, 'create'])->name('create');
        Route::post('/', [ActivityController::class, 'store'])->name('store');
        Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('follow-ups')->name('follow-ups.')->group(function () {
        Route::get('/', [FollowUpController::class, 'index'])->name('index');
        Route::get('/create', [FollowUpController::class, 'create'])->name('create');
        Route::post('/', [FollowUpController::class, 'store'])->name('store');
        Route::get('/{followUp}/edit', [FollowUpController::class, 'edit'])->name('edit');
        Route::put('/{followUp}', [FollowUpController::class, 'update'])->name('update');
        Route::post('/{followUp}/complete', [FollowUpController::class, 'markComplete'])->name('complete');
        Route::delete('/{followUp}', [FollowUpController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
    });
});

require __DIR__.'/auth.php';