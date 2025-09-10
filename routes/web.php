<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticationController::class, 'index'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login'])->name('loginPost');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::resource('items', ItemsController::class);
    Route::get('items-data', [ItemsController::class, 'getData'])->name('items.data');
    Route::resource('suppliers', SupplierController::class);
    Route::get('suppliers-data', [SupplierController::class, 'getData'])->name('suppliers.data');
    Route::resource('clients', ClientController::class);
    Route::get('clients-data', [ClientController::class, 'getData'])->name('clients.data');
    Route::resource('transactions', TransactionsController::class);
    Route::get('transactions-data', [TransactionsController::class, 'getData'])->name('transactions.data');
});