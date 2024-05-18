<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HospitalController;

// Route::get('/', function () {
//     return view('login');
// });

Route::get('/', [HospitalController::class, 'index']);
Route::post('/store-hospital', [HospitalController::class, 'store'])->name('store-hospital');
Route::delete('/delete-hospital/{id}', [HospitalController::class, 'destroy']);



