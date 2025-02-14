<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    EventController,
    SpeakerController,
    RegistrationController,
    PaymentController,
    ProfileController,
    EmailVerificationController
};
use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    PasswordResetLinkController,
    AuthenticatedSessionController,
    NewPasswordController
};

use App\Http\Controllers\Admin\{
    AdminController,
    AdminEventController,
    AdminSpeakerController,
    AdminAttendeeController,
    AdminPaymentController
};

// Rutas públicas
Route::get('/', [EventController::class, 'index'])->name('home');

// Eventos
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{id}/register', [EventController::class, 'showRegistrationForm'])->name('events.register');
Route::post('/events/{id}/register', [EventController::class, 'register'])->name('events.register.submit');
Route::get('/registration/{id}/success', [EventController::class, 'registrationSuccess'])->name('events.registration.success');
Route::delete('/registration/{id}', [EventController::class, 'cancelRegistration'])->name('events.registration.cancel');

// Ponentes
Route::resource('speakers', SpeakerController::class);

Route::middleware(['web'])->group(function () {
    Route::get('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

// Rutas protegidas
Route::middleware(\App\Http\Middleware\CheckApiToken::class)->group(function () {
    // Registro a eventos
    Route::get('/register', [RegistrationController::class, 'create'])->name('registration.create');
    Route::post('/register', [RegistrationController::class, 'store'])->name('registration.store');
    
    // Pagos
    Route::get('/payment/{registration}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/paypal/pay/{price}', [PaymentController::class, 'createPayment'])->name('paypal.pay');
    Route::get('/paypal/success', [PaymentController::class, 'capturePayment'])->name('paypal.success');
    Route::get('/paypal/cancel', function () {
        //Cambiar por una vista
        return "Pago cancelado.";
    })->name('paypal.cancel');
    
    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Rutas del Panel de Administración
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard principal
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Gestión de Ponentes
    Route::resource('speakers', AdminSpeakerController::class)->except(['show']);
    
    // Gestión de Eventos
    Route::resource('events', AdminEventController::class)->except(['show']);
    
    // Gestión de Asistentes
    Route::get('attendees', [AdminAttendeeController::class, 'index'])->name('attendees.index');
    Route::delete('attendees/{id}', [AdminAttendeeController::class, 'destroy'])->name('attendees.destroy');
    
    // Gestión de Ingresos/Pagos
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/export', [AdminPaymentController::class, 'export'])->name('payments.export');
});

// Rutas de Autenticación
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store'])->name('register');
    
    // Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    //     ->name('password.request');
    // Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    //     ->name('password.email');
});

Route::get('email/verify/{token}', [EmailVerificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware('guest');

// Ruta de la vista para el formulario de "Olvidé mi contraseña"
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

// Ruta para manejar la solicitud de enlace de restablecimiento
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// Ruta para el formulario de restablecimiento de la contraseña
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

// Ruta para procesar la actualización de la contraseña
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');



require __DIR__.'/auth.php';