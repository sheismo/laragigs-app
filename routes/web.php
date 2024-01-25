<?php

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// All Listings
Route::get('/', [ ListingController::class, 'index' ]);

// Show Create Listing Form
Route::get('listings/create', [ ListingController::class, 'create'])->middleware('auth');

// Store Listing Data
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

// Show Edit Form
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

// Update Listing 
Route::put('/listings/{listing}', [ ListingController::class, 'update' ])->middleware('auth');

// Manage Listings
Route::get('/listings/manage', [ ListingController::class, 'manage' ])->middleware('auth');

// Delete Listing 
Route::delete('/listings/{listing}', [ ListingController::class, 'destroy' ])->middleware('auth');

// Single Listing
Route::get('listings/{listing}', [ ListingController::class, 'show' ] );

// Show Register Form
Route::get('/register', [ UserController::class, 'create' ])->middleware('guest');

// Create New User
Route::post('/users', [ UserController::class, 'store']);

// Show Login Form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// Log User in
Route::post('/user/auth', [UserController::class, 'auth']);

// Log User out
Route::post('/logout', [ UserController::class, 'logout'])->middleware('auth');



