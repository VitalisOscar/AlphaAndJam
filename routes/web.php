<?php

use App\Http\Controllers\Payments\PesapalIpnController;
use App\Http\Controllers\User\Account\InvoicesController;
use App\Http\Controllers\User\Account\UserAccountController;
use App\Http\Controllers\User\Account\VerifyEmailController;
use App\Http\Controllers\User\Account\VerifyPhoneController;
use App\Http\Controllers\User\Adverts\FetchAdvertsController;
use App\Http\Controllers\User\Adverts\CreateAdvertController;
use App\Http\Controllers\User\Adverts\SingleAdvertController;
use App\Http\Controllers\User\Adverts\SlotsController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\Auth\PasswordResetController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\Payments\MpesaPaymentController;
use App\Http\Controllers\User\Payments\PesapalController;
use App\Http\Controllers\User\Payments\PesapalPaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('react-app/{path?}', 'react_app')->where('path', '.+');

Route::view('/', 'web.home')->name('home');

Route::get('logout', function () {
    Auth::logout();
    return redirect()->route('home');
})->name('logout');

// Payments
// mpesa
Route::post('checkout/{invoice_number}', [MpesaPaymentController::class, 'initiate'])->name('web.mpesa.checkout');
Route::any('mpesa/hook', [MpesaPaymentController::class, 'hook'])->name('web.mpesa.hook');

// Route::any('checkout/{invoice_number}', function($invoice_number){
//     return redirect()->route('web.pesapal.make', $invoice_number);
// })->name('web.mpesa.checkout');

Route::prefix('pesapal')->group(function(){
    Route::get('checkout/{invoice_number}', PesapalPaymentController::class)->middleware('auth:web')->name('web.pesapal.make');
    Route::get('received', [PesapalPaymentController::class, 'callback'])->name('web.pesapal.received');
    Route::get('ipn', [PesapalIpnController::class, 'index'])->name('web.pesapal.ipn');
});

Route::get('terms', function(){
    return response()->file(public_path('storage/docs/terms.pdf'));
})->name('terms');

Route::view('presence', 'web.presence')->middleware('auth:web')->name('web.presence');

Route::prefix('account')->group(function(){
    Route::middleware('guest:web')->group(function(){
        Route::get('login/{context?}', function($context = null){
            if($context == 'agent') return view('web.agents.login');
            return view('web.auth.login');
        })->name('web.auth.login');
        Route::post('login/{context?}', LoginController::class)->name('web.auth.login');

        Route::view('signup', 'web.auth.signup_start')->name('web.auth.signup');
        Route::post('signup/initial', [RegisterController::class, 'initialSignup'])->name('web.auth.signup.initial');
        Route::post('signup', [RegisterController::class, 'signup'])->name('web.auth.signup');

        Route::view('password/forgot', 'web.auth.forgot_password')->name('web.auth.forgot_password');
        Route::post('password/forgot', [PasswordResetController::class, 'forgot'])->name('web.auth.forgot_password');
    });

    Route::get('password/reset/{token}', [PasswordResetController::class, 'reset'])->name('web.auth.reset_password');
    Route::post('password/reset/{token}', [PasswordResetController::class, 'reset'])->name('web.auth.reset_password');

    Auth::routes([
        'login'    => false,
        'logout'   => false,
        'register' => false,
        'reset'    => false,   // for resetting passwords
        'confirm'  => true,  // for additional password confirmations
        'verify'   => false
    ]);

    Route::middleware('auth:web', 'complete_profile')->group(function(){
        Route::view('profile', 'web.user.account')->name('web.user.account');
        Route::post('profile', [UserAccountController::class, 'updateInfo'])->name('web.user.account');
        Route::post('profile/complete', [UserAccountController::class, 'completeProfile'])
            ->withoutMiddleware('complete_profile')
            ->name('web.user.account.complete');
        Route::post('profile/password', [UserAccountController::class, 'updatePassword'])->name('web.user.account.password');
        Route::post('profile/logo', [UserAccountController::class, 'updateLogo'])->name('web.user.account.logo');

        Route::get('invoices', [InvoicesController::class, 'getAll'])->name('web.user.invoices');
        Route::get('invoices/{invoice_number}', [InvoicesController::class, 'getSingle'])->name('web.user.invoices.single');

        // Invoice payment
        Route::get('invoices/{invoice_number}/payment', [InvoicesController::class, 'payment'])->name('web.user.invoices.single.payment');
        Route::post('invoices/{invoice_number}/payment/mpesa', [MpesaPaymentController::class, 'initiate'])->name('web.user.invoices.single.payment.mpesa');

        // Route::get('invoices/{invoice_number}/payment', function($invoice_number){
        //     return redirect()->route('web.pesapal.make', $invoice_number);
        // })->name('web.user.invoices.single.payment');

        Route::get('invoices/{invoice_number}/download', [InvoicesController::class, 'download'])->name('web.user.invoices.single.download');
    });

    // Email verification
    Route::post('email/verify', [VerifyEmailController::class, 'sendVerificationEmail'])->name('web.user.account.verify_email.send');
    Route::get('email/verify/{token}', [VerifyEmailController::class, 'verifyEmail'])->name('web.user.account.verify_email');

    // Phone verification
    Route::post('phone/verify', [VerifyPhoneController::class, 'sendCode'])
        ->middleware('complete_profile')
        ->name('web.user.account.verify_phone.send');
    Route::post('phone/verify/submit', [VerifyPhoneController::class, 'verifyCode'])
        ->middleware('complete_profile')
        ->name('web.user.account.verify_phone');
});

Route::prefix('platform')->middleware('auth:web', 'complete_profile')->group(function(){
    Route::view('', 'web.user.dashboard')->name('web.user.dashboard');

    Route::prefix('adverts')->group(function(){
        // Slot availability
        Route::post('slots/availability', [SlotsController::class, 'checkAvailability'])->name('web.adverts.slots.availability');

        // History
        Route::get('drafts', [FetchAdvertsController::class, 'drafts'])->name('web.adverts.drafts');
        Route::get('pending', [FetchAdvertsController::class, 'pending'])->name('web.adverts.pending');
        Route::get('approved', [FetchAdvertsController::class, 'approved'])->name('web.adverts.approved');
        Route::get('declined', [FetchAdvertsController::class, 'declined'])->name('web.adverts.declined');

        // Single
        Route::get('view/{id}', [FetchAdvertsController::class, 'single'])->name('web.adverts.single');

        Route::middleware(['verified'])->group(function(){
            // New
            Route::view('create', 'web.ads.create')->name('web.adverts.create');
            Route::post('create', [CreateAdvertController::class, 'create'])->name('web.adverts.create');

            Route::view('create/saved', 'web.ads.ad_submitted')->name('web.adverts.submitted');

            Route::get('recreate/{id}', [CreateAdvertController::class, 'getAdForRecreate'])->name('web.adverts.recreate');
            Route::post('recreate/{id}', [CreateAdvertController::class, 'recreate'])->name('web.adverts.recreate');

            // Edit
            Route::get('edit/{id}', [SingleAdvertController::class, 'getAd'])->name('web.adverts.edit');
            Route::post('edit/{id}', [SingleAdvertController::class, 'editAd'])->name('web.adverts.edit');

            // Delete
            Route::post('delete/{id}', [SingleAdvertController::class, 'deleteAd'])->middleware('password.confirm')->name('web.adverts.delete');
        });

    });
});

include __DIR__ . '/admin.php';
