<?php

use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\AuditAnswerController;
use App\Http\Controllers\Api\AuditOfficeAdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DetailAuditAnswerController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\KaryawanController;
use App\Http\Controllers\Api\LantaiController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\TemaFormController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VariabelFormController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');
Route::middleware('auth:api')->post('/reset-device', [AuthController::class, 'resetDevice']);
Route::post('/password/forgot', [PasswordResetController::class, 'sendResetLink']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::middleware('auth:api')->get('/user/{id}', [AuthController::class, 'show']);
//Karyawan
Route::middleware('auth:api')->get('/karyawan/{nik}', [KaryawanController::class, 'getKaryawan']);
Route::middleware('auth:api')->get('/karyawan-pic', [KaryawanController::class, 'getKaryawanPic']);
//Lantai
Route::middleware('auth:api')->get('/lantai', [LantaiController::class, 'index']);
Route::middleware('auth:api')->get('/total-lantai', [LantaiController::class, 'getTotalLantai']);
Route::middleware('auth:api')->post('/lantai', [LantaiController::class, 'store']);
Route::middleware('auth:api')->get('/lantai/{id}', [LantaiController::class, 'show']);
Route::middleware('auth:api')->delete('/lantai/{id}', [LantaiController::class, 'destroy']);
//Area
Route::middleware('auth:api')->get('/area', [AreaController::class, 'index']);
Route::middleware('auth:api')->get('/total-area', [AreaController::class, 'getTotalArea']);
Route::middleware('auth:api')->post('/area', [AreaController::class, 'store']);
Route::middleware('auth:api')->get('/area/{id}', [AreaController::class, 'show']);
Route::middleware('auth:api')->put('/area/{id}', [AreaController::class, 'update']);
Route::middleware('auth:api')->delete('/area/{id}', [AreaController::class, 'destroy']);
//Form
Route::middleware('auth:api')->get('/form', [FormController::class, 'index']);
Route::middleware('auth:api')->post('/form', [FormController::class, 'store']);
Route::middleware('auth:api')->get('/form/{id}', [FormController::class, 'show']);
Route::middleware('auth:api')->put('/form/{id}', [FormController::class, 'update']);
Route::middleware('auth:api')->delete('/form/{id}', [FormController::class, 'destroy']);
//TemaForm
Route::middleware('auth:api')->get('/tema-form/{id}', [TemaFormController::class, 'index']);
Route::middleware('auth:api')->post('/tema-form', [TemaFormController::class, 'store']);
Route::middleware('auth:api')->get('/tema-form-single/{id}', [TemaFormController::class, 'show']);
Route::middleware('auth:api')->put('/tema-form/{id}', [TemaFormController::class, 'update']);
Route::middleware('auth:api')->delete('/tema-form/{id}', [TemaFormController::class, 'destroy']);
//VariabelForm
Route::middleware('auth:api')->get('/variabel-form/{id}', [VariabelFormController::class, 'index']);
Route::middleware('auth:api')->post('/variabel-form', [VariabelFormController::class, 'store']);
Route::middleware('auth:api')->get('/total-variabel', [VariabelFormController::class, 'getTotalVariabel']);
Route::middleware('auth:api')->get('/variabel-form-single/{id}', [VariabelFormController::class, 'show']);
Route::middleware('auth:api')->put('/variabel-form/{id}', [VariabelFormController::class, 'update']);
Route::middleware('auth:api')->delete('/variabel-form/{id}', [VariabelFormController::class, 'destroy']);
//Audit Answer
Route::middleware('auth:api')->post('/audit-answer-insert', [AuditAnswerController::class, 'store']);
Route::middleware('auth:api')->get('/audit-answer-auditor/{id}', [AuditAnswerController::class, 'getTotalAuditByAuditor']);
Route::middleware('auth:api')->get('/audit-answer-total', [AuditAnswerController::class, 'getTotalAudit']);
Route::middleware('auth:api')->get('/audit-answer-area/{areaId}', [AuditAnswerController::class, 'getAuditAnswerByArea']);
Route::middleware('auth:api')->get('/audit-answer/{id}', [AuditAnswerController::class, 'show']);
//Detail Audit Answer
Route::middleware('auth:api')->get('/detail-audit-answer/{id}', [DetailAuditAnswerController::class, 'getDetailAuditAnswer']);
Route::middleware('auth:api')->post('/detail-audit-answer/{auditAnswerId}/detail/{detailAuditAnswerId}', [DetailAuditAnswerController::class, 'submitAnswer']);
Route::middleware('auth:api')->post('/detail-audit-answer/upload-photo/', [DetailAuditAnswerController::class, 'uploadPhoto']);
Route::middleware('auth:api')->get('/detail-audit-answer-show/{auditAnswerId}', [DetailAuditAnswerController::class, 'getAuditAnswer']);
Route::middleware('auth-api')->post('/detail-audit-answer/upload-signature/', [DetailAuditAnswerController::class, 'uploadSignature']);
Route::middleware('auth:api')->get('/audit-office/detail/{id}', [AuditOfficeAdminController::class, 'getDetailAuditAnswerForExport']);
Route::middleware('auth:api')->get('/audit-office/download/{id}', [AuditOfficeAdminController::class, 'downloadAuditExcel']);
//User
Route::middleware('auth:api')->get('/users', [UserController::class, 'index']);
Route::middleware('auth:api')->get('/user/{id}', [UserController::class, 'show']);
Route::middleware('auth:api')->post('/user', [UserController::class, 'store']);
Route::middleware('auth:api')->put('/user/{id}', [UserController::class, 'update']);
Route::middleware('auth:api')->delete('user/{id}', [UserController::class, 'destroy']);


// Route::get('/aktivasi-berhasil', [AuthController::class, 'index'])->name('aktivasi-berhasil');


// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
// });