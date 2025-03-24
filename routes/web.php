<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LantaiController;
use App\Http\Controllers\TemaFormController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:web')->get('/', [DashboardController::class, 'index'])->name('dashboard');

//Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:web')->post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('verifying')->get('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email');
Route::middleware('verifying')->post('/verify-otp-aktivasi', [AuthController::class, 'verifyOtpAktivasi'])->name('verify-otp-aktivasi');
Route::middleware('verifying')->post('/resend-otp-aktivasi', [AuthController::class, 'resendOtpAktivasi'])->name('resend-otp-aktivasi');

//---------------------------------Admin-------------------------------------//
//-----Konfigurasi Objek Audit-----//
Route::middleware('auth:web')->get('/konfigurasi', [DashboardController::class, 'konfigurasiView'])->name('konfigurasi');
//Lantai
Route::middleware('auth:web')->group(function () {
    Route::get('/lantai', [LantaiController::class, 'index'])->name('lantai');
    Route::get('/add-lantai', [LantaiController::class, 'addLantai'])->name('add-lantai');
    Route::post('/add-lantai', [LantaiController::class, 'store'])->name('add-lantai');
    Route::delete('/delete-lantai/{id}', [LantaiController::class, 'destroy'])->name('delete-lantai');
});

//Area
Route::middleware('auth:web')->group(function () {
    Route::get('/area', [AreaController::class, 'index'])->name('area');
    Route::get('/add-area', [AreaController::class, 'addArea'])->name('add-area');
    Route::post('/add-area', [AreaController::class, 'store'])->name('add-area');
    Route::get('/edit-area/{id}', [AreaController::class, 'editArea'])->name('edit-area');
    Route::put('/edit-area/{id}', [AreaController::class, 'update'])->name('edit-area');
    Route::delete('/delete-area/{id}', [AreaController::class, 'destroy'])->name('delete-area');
});

//-----Form-----//
//Form
Route::middleware('auth:web')->group(function () {
    Route::get('/form', [FormController::class, 'index'])->name('form');
    Route::get('/add-form', [FormController::class, 'addForm'])->name('add-form');
    Route::post('/add-form', [FormController::class, 'store'])->name('add-form');
    Route::delete('/delete-form/{id}', [FormController::class, 'destroy'])->name('delete-form');
});

//Tema Form
Route::middleware('auth:web')->group(function () {
    Route::get('/tema-form/{id}', [TemaFormController::class, 'index'])->name('tema-form');
    Route::get('/add-tema-form/{id}', [TemaFormController::class, 'addTemaForm'])->name('add-tema-form');
    Route::post('/add-tema-form/{id}', [TemaFormController::class, 'store'])->name('add-tema-form');
    Route::delete('/delete-tema-form/{id}', [TemaFormController::class, 'destroy'])->name('delete-tema-form');
});
