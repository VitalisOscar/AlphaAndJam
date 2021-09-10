<?php

use App\Http\Controllers\Admin\Adverts\AdvertsController;
use App\Http\Controllers\Admin\Adverts\SingleAdvertController;
use App\Http\Controllers\Admin\Agents\AgentsController;
use App\Http\Controllers\Admin\Agents\SingleAgentController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Billing\InvoicesController;
use App\Http\Controllers\Admin\Billing\BillingController;
use App\Http\Controllers\Admin\Categories\CategoriesController;
use App\Http\Controllers\Admin\Clients\ClientsController;
use App\Http\Controllers\Admin\Clients\SingleClientController;
use App\Http\Controllers\Admin\Packages\PackagesController;
use App\Http\Controllers\Admin\Packages\SinglePackageController;
use App\Http\Controllers\Admin\Schedule\ScheduleController;
use App\Http\Controllers\Admin\Schedule\DownloadScheduleController;
use App\Http\Controllers\Admin\Schedule\PlaybackCommentsController;
use App\Http\Controllers\Admin\Screens\ScreensController;
use App\Http\Controllers\Admin\Screens\SingleScreenController;
use App\Http\Controllers\Admin\Staff\SingleStaffController;
use App\Http\Controllers\Admin\Staff\StaffActivityController;
use App\Http\Controllers\Admin\Staff\StaffController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->middleware('auth:staff')->group(function(){
    // Dashboard
    Route::view('', 'admin.dashboard')->name('admin.dashboard');

    // Auth
    Route::view('login', 'admin.auth.login')->withoutMiddleware('auth:staff')->name('admin.auth.login');
    Route::post('login', LoginController::class)->withoutMiddleware('auth:staff');

    Route::get('logout', function(){
        auth('staff')->logout();
        return redirect(route('admin.auth.login'));
    })->name('admin.logout');

    // Categories
    Route::prefix('categories')->group(function(){
        // All
        Route::get('', [CategoriesController::class, 'getAll'])->name('admin.categories');

        Route::middleware(['admin', 'password.confirm'])->group(function(){
            // New
            Route::post('add', [CategoriesController::class, 'add'])->name('admin.categories.add');

            // Delete
            Route::post('{slug}/delete', [CategoriesController::class, 'delete'])->name('admin.categories.delete');
        });

        Route::get('export', [CategoriesController::class, 'export'])->name('admin.categories.export');

        // Ads in category
        Route::get('{slug}/ads', [])->name('admin.categories.ads');
    });

    // Packages
    Route::prefix('packages')->group(function(){
        // All
        Route::get('', [PackagesController::class, 'getAll'])->name('admin.packages');

        // Single
        Route::get('{id}/manage', [SinglePackageController::class, 'view'])->name('admin.packages.manage');

        Route::middleware(['admin', 'password.confirm'])->group(function(){
            // New
            Route::post('add', [PackagesController::class, 'add'])->name('admin.packages.add');
            Route::post('{id}/edit', [SinglePackageController::class, 'edit'])->name('admin.packages.edit');
            Route::post('{id}/pricing', [SinglePackageController::class, 'pricing'])->name('admin.packages.pricing');
        });
    });

    // Screens
    Route::prefix('screens')->group(function(){
        // All
        Route::get('', [ScreensController::class, 'getAll'])->name('admin.screens');

        // New
        Route::post('add', [ScreensController::class, 'add'])->middleware(['admin', 'password.confirm'])->name('admin.screens.add');

        // Single screen
        Route::get('{slug}', [SingleScreenController::class, 'getScreen'])->name('admin.screens.single');
        Route::middleware(['admin', 'password.confirm'])->group(function(){
            Route::post('{slug}/delete', [SingleScreenController::class, 'delete'])->name('admin.screens.delete');
            Route::post('{slug}/edit', [SingleScreenController::class, 'edit'])->name('admin.screens.edit');
            Route::post('{slug}/pricing', [SingleScreenController::class, 'pricing'])->name('admin.screens.pricing');
        });
    });

    // Clients
    Route::prefix('clients')->group(function(){
        // View
        Route::get('', [ClientsController::class, 'getAll'])->name('admin.clients');
        Route::get('{email}', [SingleClientController::class, 'get'])->name('admin.clients.single');
        Route::get('{email}/certificate', [SingleClientController::class, 'viewCertificate'])->name('admin.clients.single.certificate');
        Route::get('{email}/kra_pin', [SingleClientController::class, 'viewKraPin'])->name('admin.clients.single.kra_pin');

        // Approve or reject
        Route::middleware(['admin', 'password.confirm'])->group(function(){
            Route::post('{email}/approve', [SingleClientController::class, 'verify'])->name('admin.clients.approve');
            Route::post('{email}/reject', [SingleClientController::class, 'reject'])->name('admin.clients.reject');
            Route::post('{email}/add-post-pay', [SingleClientController::class, 'addPostPay'])->name('admin.clients.add_post_pay');
            Route::post('{email}/remove-post-pay', [SingleClientController::class, 'removePostPay'])->name('admin.clients.remove_post_pay');
        });

        // Invoicing
        Route::get('invoices/all', [InvoicesController::class, 'getAll'])->name('admin.clients.invoices');
        Route::get('invoices/export', [InvoicesController::class, 'export'])->name('admin.clients.invoices.export');
        Route::get('invoices/{number}', [InvoicesController::class, 'getSingle'])->name('admin.clients.invoices.single');
        Route::get('invoices/{number}/download', [InvoicesController::class, 'download'])->name('admin.clients.invoices.single.download');
        Route::post('invoices/{number}/confirm-payment', [InvoicesController::class, 'confirmPayment'])->name('admin.clients.invoices.confirm_payment');
    });

    // Agents
    Route::prefix('agents')->group(function(){
        // View
        Route::get('', [AgentsController::class, 'getAll'])->name('admin.agents');
        Route::view('add', 'admin.agents.add')->name('admin.agents.add');
        Route::post('add', [AgentsController::class, 'add'])->name('admin.agents.add');
        Route::get('{agent_id}', [SingleAgentController::class, 'get'])->name('admin.agents.single');
        Route::post('{agent_id}/approve', [SingleAgentController::class, 'approve'])->name('admin.agents.single.approve');
        Route::post('{agent_id}/deactivate', [SingleAgentController::class, 'reject'])->name('admin.agents.single.deactivate');
    });

    // Income Stats
    Route::get('billing/stats', [BillingController::class, 'stats'])->name('admin.billing.stats');

    // Staff
    Route::prefix('staff')->middleware(['admin'])->group(function(){
        Route::get('', [StaffController::class, 'getAll'])->name('admin.staff');

        Route::view('add', 'admin.staff.add')->name('admin.staff.add');
        Route::post('add', [StaffController::class, 'add'])->middleware('password.confirm')->name('admin.staff.add');

        // Activity
        Route::get('logs', StaffActivityController::class)->name('admin.staff.activity');
        Route::get('logs/redirect/{item}/{id}', [StaffActivityController::class, 'redirect'])->name('admin.staff.activity.redirect');

        // Self
        Route::view('change-password', 'admin.staff.password')->name('admin.staff.password');
        Route::post('change-password', [PasswordController::class, 'change'])->middleware('password.confirm')->name('admin.staff.password');

        Route::get('{username}', [SingleStaffController::class, 'get'])->name('admin.staff.single');
        Route::middleware('password.confirm')->group(function(){
            Route::post('{username}/edit', [SingleStaffController::class, 'edit'])->name('admin.staff.edit');
            Route::post('{username}/delete', [SingleStaffController::class, 'delete'])->name('admin.staff.delete');
            Route::post('{username}/password', [SingleStaffController::class, 'resetPassword'])->name('admin.staff.password.reset');
        });
    });

    // Adverts
    Route::prefix('adverts')->group(function(){
        Route::get('', [AdvertsController::class, 'getAll'])->name('admin.adverts');

        Route::get('export', [AdvertsController::class, 'export'])->name('admin.adverts.export');

        Route::get('{id}', [AdvertsController::class, 'getSingle'])->name('admin.adverts.single');

        Route::post('{id}/approve', [SingleAdvertController::class, 'approve'])->middleware('password.confirm')->name('admin.adverts.approve');
        Route::post('{id}/reject', [SingleAdvertController::class, 'reject'])->middleware('password.confirm')->name('admin.adverts.reject');
    });

    // Schedule
    Route::get('schedule', ScheduleController::class)->name('admin.schedule');
    Route::get('schedule/download', DownloadScheduleController::class)->name('admin.schedule.download');
    Route::get('schedule/download/single', [DownloadScheduleController::class, 'single'])->name('admin.schedule.download.single');
    Route::post('schedule/comments/add', [PlaybackCommentsController::class, 'save'])->name('admin.schedule.comments.add');

});
