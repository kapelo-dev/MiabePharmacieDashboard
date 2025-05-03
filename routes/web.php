<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\pharmaciesController;
use App\Http\Controllers\gardesController;
use App\Http\Controllers\usersController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PersonnelsController;

/* 
Route::get('/', [dashboardController::class, 'index']); */

Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');
/* Route::get('/pharmacies', [PharmaciesController::class, 'index'])->name('pharmacies.index');
Route::post('/pharmacies', [PharmaciesController::class, 'store'])->name('pharmacies.store'); */
Route::get('/gardes', [GardesController::class, 'index'])->name('gardes.index');
Route::delete('/gardes/destroy/{id}', [GardesController::class, 'destroy'])->name('gardes.destroy');
Route::get('/gardes/edit/{id}', [GardesController::class, 'edit'])->name('gardes.edit');
Route::put('/gardes/update/{id}', [GardesController::class, 'update'])->name('gardes.update');
Route::post('/gardes/store', [GardesController::class, 'store'])->name('gardes.store');
Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::post('/users', [UsersController::class, 'store'])->name('users.store');

Route::get('/comptes', [PharmaciesController::class, 'indexComptes'])->name('pharmacies.indexComptes');
Route::resource('pharmacies', PharmaciesController::class);
Route::post('/pharmacies/addPharmacien/{pharmacyId}', [PharmaciesController::class, 'addPharmacien'])->name('pharmacies.addPharmacien');

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/personnels', [PersonnelsController::class, 'index'])->name('personnels.index');
Route::post('/personnels', [PersonnelsController::class, 'store'])->name('personnels.store');
Route::delete('/personnels/destroy/{id}', [PersonnelsController::class, 'destroy'])->name('personnels.destroy');
Route::get('/personnels/edit/{id}', [PersonnelsController::class, 'edit'])->name('personnels.edit');
Route::put('/personnels/update/{id}', [PersonnelsController::class, 'update'])->name('personnels.update');


