<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/calendar', [AdminController::class, 'calendar'])->name('calendar');
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account', [AccountController::class, 'update'])->name('account.update');

    Route::middleware('role:manager')->group(function () {
        Route::get('/locations', [AdminController::class, 'locations'])->name('locations.index');
        Route::post('/locations', [AdminController::class, 'storeLocation'])->name('locations.store');
        Route::post('/locations/{location}', [AdminController::class, 'updateLocation'])->name('locations.update');

        Route::post('/rooms', [AdminController::class, 'storeRoom'])->name('rooms.store');
        Route::post('/rooms/{room}', [AdminController::class, 'updateRoom'])->name('rooms.update');

        Route::get('/customers', [AdminController::class, 'customers'])->name('customers.index');
        Route::post('/customers', [AdminController::class, 'storeCustomer'])->name('customers.store');
        Route::post('/customers/{customer}', [AdminController::class, 'updateCustomer'])->name('customers.update');
    });

    Route::get('/rooms', [AdminController::class, 'rooms'])->name('rooms.index');
    Route::get('/stays', [AdminController::class, 'stays'])->name('stays.index');
    Route::post('/stays', [AdminController::class, 'storeStay'])->name('stays.store');
    Route::post('/stays/{stay}/checkout', [AdminController::class, 'checkoutStay'])->name('stays.checkout');

    Route::middleware('role:manager,accounting')->group(function () {
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/exports/profit-loss.csv', [AdminController::class, 'exportProfitLoss'])->name('exports.profit-loss');

        Route::get('/incomes', [AdminController::class, 'incomes'])->name('incomes.index');
        Route::post('/incomes', [AdminController::class, 'storeIncome'])->name('incomes.store');
        Route::post('/incomes/{income}', [AdminController::class, 'updateIncome'])->name('incomes.update');

        Route::get('/expenses', [AdminController::class, 'expenses'])->name('expenses.index');
        Route::post('/expenses', [AdminController::class, 'storeExpense'])->name('expenses.store');
        Route::post('/expenses/{expense}', [AdminController::class, 'updateExpense'])->name('expenses.update');

        Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions.index');
        Route::post('/subscriptions', [AdminController::class, 'storeSubscription'])->name('subscriptions.store');
    });

    Route::get('/maintenance', [AdminController::class, 'maintenance'])->name('maintenance.index');
    Route::post('/maintenance', [AdminController::class, 'storeMaintenance'])->name('maintenance.store');
});
