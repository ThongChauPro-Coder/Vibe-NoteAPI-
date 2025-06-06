<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteShareController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\LabelController;

Route::prefix('auth')->group(function () {
    Route::get('/', [AuthController::class, 'index']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('/notes/{note}/share', [NoteShareController::class, 'share']);
    Route::get('/notes/{note}/recipients', [NoteShareController::class, 'recipients']);
    Route::put('/recipients/{recipientId}', [NoteShareController::class, 'updateRecipient']);
    Route::delete('/recipients/{recipientId}', [NoteShareController::class, 'revokeRecipient']);
});

Route::apiResource('notes', NoteController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('labels', LabelController::class);
Route::post('notes/{id}/pin', [NoteController::class, 'pin']);
Route::post('notes/{id}/unpin', [NoteController::class, 'unpin']);

Route::post('/password/request', [PasswordResetController::class, 'sendResetOtp']);
Route::post('/password/verify', [PasswordResetController::class, 'verifyOtp']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);
Route::post('/send-otp', [OTPController::class, 'sendOTP']);
Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);

Route::post('notes/{id}/set-password', [NoteController::class, 'setPassword']);
Route::post('notes/{id}/change-password', [NoteController::class, 'changePassword']);
Route::post('notes/{id}/unlock', [NoteController::class, 'unlock']);
Route::post('notes/{id}/disable-password', [NoteController::class, 'disablePassword']);
