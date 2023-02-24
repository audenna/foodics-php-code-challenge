<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
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

# show default welcome response
Route::get('', [Controller::class, 'printWelcome']);

# make an Order
Route::post('orders', [OrderController::class, 'processCustomerOrder'])->middleware('validate.header');
