<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SpafTemplateController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SpafController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Schedule\CountryController;
use App\Http\Controllers\Schedule\ScheduleStatusController;
use App\Http\Controllers\Schedule\AuditModelController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ProficiencyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['auth']], function()
{
    Route::get('/', [StaterkitController::class, 'home'])->name('home');
    Route::get('/getBadges', [StaterkitController::class, 'getBadges'])->name('getBadges');

    Route::post('/user/sendReset/{user}', [UserController::class, 'sendReset'])->name('user.sendReset');
    Route::get('user/delete/{user}', [UserController::class, 'delete'])->name('user.delete');
    Route::resource('user', UserController::class)->except('show')->middleware('permission:user.manage');
    Route::resource('proficiency', ProficiencyController::class)->middleware('permission:user.manage');
    Route::get('role/delete/{role}', [RoleController::class, 'delete'])->name('role.delete');

    // Route::get('spaf/edit/{spaf}', [SpafController::class, 'edit'])->name('spaf.edit')->middleware('role:Supplier');
    // Route::put('spaf/{spaf}', [SpafController::class, 'update'])->name('spaf.edit')->middleware('role:Supplier');
    Route::post('spaf/approve/{spaf}/', [SpafController::class, 'approve'])->name('spaf.approve')->middleware('permission:spaf.approve');
    Route::get('spaf', [SpafController::class, 'index'])->name('spaf.index')->middleware('permission:spaf.manage,spaf.approve');
    Route::get('spaf/create', [SpafController::class, 'create'])->name('spaf.create')->middleware('permission:spaf.manage');
    Route::post('spaf/loadSuppliers/{company}', [SpafController::class, 'loadSuppliers'])->name('spaf.loadSuppliers');
    Route::post('spaf/loadClientContactPersons/{company}', [SpafController::class, 'loadClientContactPersons'])->name('spaf.loadClientContactPersons')->middleware('permission:spaf.manage');
    Route::post('spaf/loadSupplierContactPersons/{company}', [SpafController::class, 'loadSupplierContactPersons'])->name('spaf.loadSupplierContactPersons')->middleware('permission:spaf.manage');




    Route::post('spaf/sendReminder/{spaf}', [SpafController::class, 'sendReminder'])->name('spaf.sendReminder')->middleware('permission:spaf.manage');
    Route::post('spaf', [SpafController::class, 'store'])->name('spaf.store')->middleware('permission:spaf.manage');
    Route::get('spaf/supplierIndex', [SpafController::class, 'supplierIndex'])->name('spaf.supplierIndex')->middleware('role:Supplier');
    Route::get('spaf/clientIndex', [SpafController::class, 'clientIndex'])->name('spaf.clientIndex')->middleware('role:Client');
    Route::get('spaf/{spaf}', [SpafController::class, 'show'])->name('spaf.show');
    Route::resource('spaf', SpafController::class)->only(['update', 'edit'])->middleware('role:Supplier,Client');

    Route::resource('schedule', ScheduleController::class)->except(['show'])->parameters(['schedule' => 'event']);
    Route::get('schedule/getEvents', [ScheduleController::class, 'getEvents'])->name('schedule.getEvents');
    Route::get('schedule/ganttChart', [ScheduleController::class, 'ganttChart'])->name('schedule.ganttChart');

    Route::post('loadAvailableUsers', [ScheduleController::class, 'loadAvailableUsers'])->name('schedule.loadAvailableUsers')->middleware('permission:schedule.manage');
    Route::post('loadAvailableSuppliers/{company}', [ScheduleController::class, 'loadAvailableSuppliers'])->name('schedule.loadAvailableSuppliers')->middleware('permission:schedule.manage');
    Route::post('loadSpaf/{company}', [ScheduleController::class, 'loadSpaf'])->name('schedule.loadSpaf')->middleware('permission:schedule.manage');

    Route::group(['prefix' => 'settings'], function()
    {
        Route::get('email', [SettingController::class, 'email'])->name('settings.email')->middleware('permission:settings.email.manage');
        Route::put('emailUpdate', [SettingController::class, 'emailUpdate'])->name('settings.emailUpdate')->middleware('permission:settings.email.manage');
        Route::get('schedule', [SettingController::class, 'schedule'])->name('settings.schedule')->middleware('permission:settings.schedule.manage');
        Route::put('scheduleUpdate', [SettingController::class, 'scheduleUpdate'])->name('settings.scheduleUpdate')->middleware('permission:settings.schedule.manage');

        Route::resource('country', CountryController::class, ['names' => 'settings.country'])->middleware('permission:settings.country.manage');
        Route::resource('scheduleStatus', ScheduleStatusController::class, ['names' => 'settings.scheduleStatus'])->middleware('permission:settings.scheduleStatus.manage');
        Route::resource('auditModel', AuditModelController::class, ['names' => 'settings.auditModel'])->middleware('permission:settings.auditModel.manage');
    });


    Route::resource('role', RoleController::class)->middleware('permission:role.manage');
    Route::get('supplier/{company}/addContact', [SupplierController::class, 'addContact'])->name('supplier.addContact')->middleware('permission:supplier.manage,client.manage');
    Route::post('supplier/{company}/addContact', [SupplierController::class, 'storeContact'])->name('supplier.storeContact')->middleware('permission:supplier.manage,client.manage');
    Route::resource('supplier', SupplierController::class)->middleware('permission:supplier.manage,client.manage')->parameters(['supplier' => 'company']);
    Route::resource('client', ClientController::class)->middleware('permission:client.manage')->parameters(['client' => 'user']);
    Route::group(['prefix' => 'template', 'as' => 'template.'], function()
    {
        Route::group([], function()
        {
            // template
             Route::get('spaf/preview/{template}', [SpafTemplateController::class, 'preview'])->name('spaf.preview')->middleware('permission:template.manage');
             Route::get('spaf/delete/{template}', [SpafTemplateController::class, 'delete'])->name('spaf.delete')->middleware('permission:template.manage');
             Route::post('spaf/changeStatus/{template}', [SpafTemplateController::class, 'changeStatus'])->name('spaf.changeStatus')->middleware('permission:template.manage');
             Route::post('spaf/approve/{template}', [SpafTemplateController::class, 'approve'])->name('spaf.approve')->middleware('permission:template.approve');
             Route::post('spaf/clone/{template}', [SpafTemplateController::class, 'clone'])->name('spaf.clone')->middleware('permission:template.manage');
             Route::get('spaf/{type}', [SpafTemplateController::class, 'index'])->name('spaf.index')->middleware('permission:template.manage,template.approve');
             Route::get('spaf/show/{template}', [SpafTemplateController::class, 'show'])->name('spaf.show')->middleware('permission:template.manage,template.approve');
             Route::resource('spaf', SpafTemplateController::class)->parameters(['spaf' => 'template'])->except(['index', 'show'])->middleware('permission:template.manage');
             // group
             Route::post('group/updateSort/{template}', [GroupController::class, 'updateSort'])->name('group.updateSort')->middleware('permission:template.manage');
             Route::post('group/clone/{group}', [GroupController::class, 'clone'])->name('group.clone')->middleware('permission:template.manage');
             Route::get('group/create/{template}', [GroupController::class, 'create'])->name('group.create')->middleware('permission:template.manage');
             Route::post('group/{template}', [GroupController::class, 'store'])->name('group.store')->middleware('permission:template.manage');
             Route::get('group/delete/{group}', [GroupController::class, 'delete'])->name('group.delete')->middleware('permission:template.manage');
             Route::get('group/preview/{template}', [GroupController::class, 'preview'])->name('group.preview');
             Route::resource('group', GroupController::class)->except(['create', 'store'])->middleware('permission:template.manage');
        });


    });
});

// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
