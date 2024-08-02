<?php

use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiFeeCancelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// users
Route::prefix('users')->group(function () {
    Route::get('datauser', [ApiUserController::class, 'index'])->name('users.index');
    Route::post('login', [ApiUserController::class, 'login']);
});
// Fee 
Route::prefix('feecancel')->group(function () {
    Route::post('store', [ApiFeeCancelController::class, 'store'])->name('fee-cancels.store');
    Route::get('index', [ApiFeeCancelController::class, 'index'])->name('fee-cancels.index');
    Route::get('show/{id_fee_cancels}', [ApiFeeCancelController::class, 'show'])->name('fee-cancels.show');
    Route::post('update/{id_fee_cancels}', [ApiFeeCancelController::class, 'update'])->name('fee-cancels.update');
    Route::get('destroy/{id_fee_cancels}', [ApiFeeCancelController::class, 'destroy'])->name('fee-cancels.destroy');
});
