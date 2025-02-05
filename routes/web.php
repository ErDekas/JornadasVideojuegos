<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    EventController,
    SpeakerController,
    RegistrationController,
    PaymentController,
    ProfileController
};
use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    ForgotPasswordController
};

// Rutas públicas
Route::get('/', [EventController::class, 'index'])->name('home');

// Eventos
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Ponentes
Route::get('/speakers', [SpeakerController::class, 'index'])->name('speakers.index');
Route::get('/speakers/{id}', [SpeakerController::class, 'show'])->name('speakers.show');

// Rutas protegidas
Route::middleware('api.token')->group(function () {
    // Registro a eventos
    Route::get('/register', [RegistrationController::class, 'create'])->name('registration.create');
    Route::post('/register', [RegistrationController::class, 'store'])->name('registration.store');
    
    // Pagos
    Route::get('/payment/{registration}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    
    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Rutas de Autenticación
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
});

Route::middleware('api.token')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('email/verify/{token}', [RegisterController::class, 'verify'])->name('verification.verify');
