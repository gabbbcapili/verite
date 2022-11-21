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


    Route::get('user/delete/{user}', [UserController::class, 'delete'])->name('user.delete');
    Route::resource('user', UserController::class)->except('show', 'create')->middleware('permission:user.manage');
    Route::get('role/delete/{role}', [RoleController::class, 'delete'])->name('role.delete');

    // Route::get('spaf/edit/{spaf}', [SpafController::class, 'edit'])->name('spaf.edit')->middleware('role:Supplier');
    // Route::put('spaf/{spaf}', [SpafController::class, 'update'])->name('spaf.edit')->middleware('role:Supplier');
    Route::post('spaf/approve/{spaf}/', [SpafController::class, 'approve'])->name('spaf.approve')->middleware('permission:spaf.approve');
    Route::get('spaf', [SpafController::class, 'index'])->name('spaf.index')->middleware('permission:spaf.manage');
    Route::get('spaf/create', [SpafController::class, 'create'])->name('spaf.create')->middleware('permission:spaf.manage');
    Route::post('spaf/loadSuppliers/{user}', [SpafController::class, 'loadSuppliers'])->name('spaf.loadSuppliers')->middleware('permission:spaf.manage');
    Route::post('spaf', [SpafController::class, 'store'])->name('spaf.store')->middleware('permission:spaf.manage');
    Route::get('spaf/supplierIndex', [SpafController::class, 'supplierIndex'])->name('spaf.supplierIndex')->middleware('role:Supplier');
    Route::get('spaf/clientIndex', [SpafController::class, 'clientIndex'])->name('spaf.clientIndex')->middleware('role:Client');
    Route::get('spaf/{spaf}', [SpafController::class, 'show'])->name('spaf.show');
    Route::resource('spaf', SpafController::class)->only(['update', 'edit'])->middleware('role:Supplier');

    Route::resource('role', RoleController::class)->except('create')->middleware('permission:role.manage');
    Route::resource('supplier', SupplierController::class)->middleware('permission:supplier.manage')->parameters(['supplier' => 'user']);
    Route::resource('client', ClientController::class)->middleware('permission:client.manage')->parameters(['client' => 'user']);
    Route::group(['prefix' => 'template', 'as' => 'template.'], function()
    {
        Route::group(['middleware' => 'permission:template.manage'], function()
        {
            // template
             Route::get('spaf/preview/{template}', [SpafTemplateController::class, 'preview'])->name('spaf.preview');
             Route::get('spaf/delete/{template}', [SpafTemplateController::class, 'delete'])->name('spaf.delete');
             Route::post('spaf/approve/{template}', [SpafTemplateController::class, 'approve'])->name('spaf.approve');
             Route::post('spaf/clone/{template}', [SpafTemplateController::class, 'clone'])->name('spaf.clone');
             Route::get('spaf/{type}', [SpafTemplateController::class, 'index'])->name('spaf.index');
             Route::resource('spaf', SpafTemplateController::class)->parameters(['spaf' => 'template'])->except('index');
             // group
             Route::post('group/updateSort/{template}', [GroupController::class, 'updateSort'])->name('group.updateSort');
             Route::post('group/clone/{group}', [GroupController::class, 'clone'])->name('group.clone');
             Route::get('group/create/{template}', [GroupController::class, 'create'])->name('group.create');
             Route::post('group/{template}', [GroupController::class, 'store'])->name('group.store');
             Route::get('group/delete/{group}', [GroupController::class, 'delete'])->name('group.delete');
             Route::get('group/preview/{template}', [GroupController::class, 'preview'])->name('group.preview');
             Route::resource('group', GroupController::class)->except(['create', 'store']);
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
