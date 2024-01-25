<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Authenticated;
use App\Http\Controllers\Tenant\Team\TeamController;
use App\Http\Controllers\Tenant\Files\FilesController;
use App\Http\Controllers\Tenant\Setup\ZonesController;
use App\Http\Controllers\Tenant\Tasks\TasksController;
use App\Http\Controllers\Tenant\Setup\BrandsController;
use App\Http\Controllers\Tenant\Setup\ConfigController;
use App\Http\Controllers\Tenant\Setup\ServicesController;
use App\Http\Controllers\Tenant\Profile\ProfileController;
use App\Http\Controllers\Tenant\Auth\NewPasswordController;
//use App\Http\Controllers\Tenant\User\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Tenant\Auth\VerifyEmailController;
use App\Http\Controllers\Tenant\Setup\CustomTypesController;
use App\Http\Controllers\Tenant\Tasks\TasksReportsController;
use App\Http\Controllers\Tenant\Auth\RegisteredUserController;
use App\Http\Controllers\Tenant\Customers\CustomersController;
use App\Http\Controllers\Tenant\Dashboard\DashboardController;
use App\Http\Controllers\Tenant\OpenTimes\OpenTimesController;
use App\Http\Controllers\Tenant\TeamMember\TeamMemberController;
use App\Http\Controllers\Tenant\Auth\PasswordResetLinkController;
use App\Http\Controllers\Tenant\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Tenant\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Tenant\FilesCustomer\FilesCustomerController;
use App\Http\Controllers\Tenant\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Tenant\Analysis\AnalysisTasksReportsController;
use App\Http\Controllers\Tenant\CustomerMember\CustomerMemberController;
use App\Http\Controllers\Tenant\Analysis\CompletedTasksReportsController;
use App\Http\Controllers\Tenant\AnalysisDashboard\AnalysisDashboardController;
use App\Http\Controllers\Tenant\CustomerContacts\CustomerContactsController;
use App\Http\Controllers\Tenant\CustomerServices\CustomerServicesController;
use App\Http\Controllers\Tenant\CustomerLocations\CustomerLocationsController;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('tenant.verify');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

Route::middleware(['auth', 'cmsSettings'])->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    //     ->middleware('throttle:6,1')
    //     ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('dashboard', [DashboardController::class, 'show'])
        ->name('tenant.dashboard');

    // Route::prefix('tasks')->group(function () {
    //     Route::get('list', [TeamController::class, 'index'])
    //         ->name('tenant.tasks.list');
    //     Route::prefix('reports')->group(function () {
    //         Route::get('list', [TeamController::class, 'index'])
    //             ->name('tenant.tasks.reports.list');
    //     });
    // });

    // Route::prefix('devices')->group(function () {
    //     Route::get('list', [TeamController::class, 'index'])
    //         ->name('tenant.devices.list');
    // });

    // Route::prefix('services')->group(function () {
    //     Route::get('list', [TeamController::class, 'index'])
    //         ->name('tenant.services.list');
    // });


    // Route::prefix('partners')->group(function () {
    //     Route::get('list', [TeamController::class, 'index'])
    //         ->name('tenant.partners.list');
    // });

    // Route::prefix('team')->group(function () {
    //     Route::get('list', [TeamController::class, 'index'])
    //         ->name('tenant.team.list');
    // });
    Route::resource('tasks', TasksController::class, [
        'as' => 'tenant'
    ]);

    Route::resource('tasks-reports', TasksReportsController::class, [
        'as' => 'tenant'
    ]);

    Route::resource('open-times', OpenTimesController::class, [
        'as' => 'tenant'
    ]);

    Route::resource('analysis-dashboard', AnalysisDashboardController::class, [
        'as' => 'tenant'
    ]);

    Route::delete('tasks-reports-times/delete/{time_id}',[TasksReportsController::class,'destroyTimeTask'])->name('tenant.tasks-reports.destroytimetask');


    Route::post('tasks-reports/{task}/preview', [TasksController::class, 'previewTask'])
        ->name('tenant.tasks-reports.preview');

    /**
     * Route to dispath the task:
     *      1 - change task status
     *      2 - send email to customer
     *      3 - send email to worker
     *      4 - add task to calendar
     */
    Route::post('tasks/{task}/dispatchtask', [TasksController::class, 'dispatchTask'])
        ->name('tenant.tasks.dispatchtask');


    Route::resource('services', CustomerServicesController::class, [
        'as' => 'tenant'
    ]);

    Route::resource('analysis', AnalysisTasksReportsController::class, [
        'as' => 'tenant'
    ]);

    Route::resource('completed', CompletedTasksReportsController::class,[
        'as' => 'tenant'
    ]);

    Route::get('loginCustomer/{customer}',[CustomersController::class,'createloginCustomer'])->name('tenant.loginCustomer.loginCustomer');

    Route::get('loginTeamMember/{teammember}',[TeamMemberController::class,'createlogin'])->name('tenant.loginTeamMember.loginTeamMember');

    Route::get('deleteTask/{taskID}',[TasksController::class,'destroyTask'])->name('tenant.deleteTask.deleteTask');

    
    Route::resource('customers', CustomersController::class, [
        'as' => 'tenant'
    ]);

    Route::get('login/{member}', [TeamMemberController::class,'createlogin'])->name('tenant.login.logadas');

    Route::resource('team-member', TeamMemberController::class, [
        'as' => 'tenant'
    ]);

    Route::post('user-info', [ProfileController::class,'UserInfo'])->name('tenant.user-info.userinfo');

    Route::resource('profile', ProfileController::class, [
        'as' => 'tenant'
    ]);

    Route::resource('customer-locations', CustomerLocationsController::class, [
        'as' => 'tenant'
    ]);

    Route::resource('customer-member', CustomerMemberController::class, [
        'as' => 'tenant'
    ]);

    Route::resource('files', FilesController::class, [
        'as' => 'tenant'
    ]);

    Route::resource('files-customer', FilesCustomerController::class, [
        'as' => 'tenant'
    ]);

    Route::resource('customer-contacts', CustomerContactsController::class);

    Route::prefix('setup')->group(function () {
        // Route::prefix('devices')->group(function () {
        //     Route::get('list', [TeamController::class, 'index'])
        //         ->name('tenant.setup.devices.list');
        // });

        Route::resource('brands', BrandsController::class, [
            'as' => 'tenant.setup'
        ]);

        Route::resource('services', ServicesController::class, [
            'as' => 'tenant.setup'
        ]);

        Route::resource('custom-types', CustomTypesController::class, [
            'as' => 'tenant.setup'
        ]);

        Route::resource('zones', ZonesController::class,[
            'as' => 'tenant.setup'
        ]);

        Route::get('config', [ConfigController::class, 'index'])
            ->name('tenant.setup.app');

        // Route::prefix('parts')->group(function () {
        //     Route::get('list', [TeamController::class, 'index'])
        //         ->name('tenant.setup.parts.list');
        // });
        // Route::prefix('attributes')->group(function () {
        //     Route::get('list', [TeamController::class, 'index'])
        //         ->name('tenant.setup.attributes.list');
        // });
        // Route::prefix('attributesvalues')->group(function () {
        //     Route::get('list', [TeamController::class, 'index'])
        //         ->name('tenant.setup.attributesvalues.list');
        // });
    });

});
