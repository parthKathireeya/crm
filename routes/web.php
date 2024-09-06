<?php

use Illuminate\Support\Facades\Route;

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




// login
use App\Http\Controllers\system\AuthorizationController;
    Route::get('/login', [AuthorizationController::class, 'login'])->name('login');
    Route::post('/do_login', [AuthorizationController::class, 'do_login'])->name('do_login');
    Route::get('/logout', [AuthorizationController::class, 'logout'])->name('logout');

    Route::get('/unauthorized', function () {
        return view('system/unauthorized');
    })->name('unauthorized');

    Route::get('/404', function () {
        return view('system/404');
    })->name('404');

use App\Http\Controllers\system\ProjectModulesController;
use App\Http\Controllers\system\UsersController;
use App\Http\Controllers\system\UserTypeRightsController;
use App\Http\Controllers\system\UserRightsController;
use App\Http\Middleware\CheckLogin;


Route::middleware(CheckLogin::class)->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // modules
    Route::get('/modules', [ProjectModulesController::class, 'index'])->name('modules');
    Route::post('/modules/manageRights', [ProjectModulesController::class, 'lodeTable'])->name('modules.loadTable');
    Route::get('/modules/add', [ProjectModulesController::class, 'add'])->name('modules.add');
    Route::post('/modules/insert', [ProjectModulesController::class, 'insert'])->name('modules.insert');
    Route::get('/modules/edit/{id}', [ProjectModulesController::class, 'edit'])->name('modules.edit');
    Route::post('/modules/update/{id}', [ProjectModulesController::class, 'update'])->name('modules.update');
    Route::post('/modules/change_showNo', [ProjectModulesController::class, 'change_showNo'])->name('modules.change_showNo');
    Route::post('/modules/{id}', [ProjectModulesController::class, 'delete'])->name('modules.delete');

    // users
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::post('/users/lodeTable', [UsersController::class, 'lodeTable'])->name('users.loadTable');
    Route::get('/users/add', [UsersController::class, 'add'])->name('users.add');
    Route::post('/users/checkuserName', [UsersController::class, 'checkuserName'])->name('users.checkuserName');
    Route::post('/users/insert', [UsersController::class, 'insert'])->name('users.insert');
    Route::get('/users/edit/{id}', [UsersController::class, 'edit'])->name('users.edit');
    Route::post('/users/update/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::post('/users/activeDeactivate', [UsersController::class, 'activeDeactivate'])->name('users.activeDeactivate');
    Route::post('/users/{id}', [UsersController::class, 'delete'])->name('users.delete');
    Route::post('/users/edit-profile/{id}', [UsersController::class, 'editProfile'])->name('edit-profile');
    Route::post('/users/update-profile/{id}', [UsersController::class, 'updateProfile'])->name('update-profile');


    // user type rights
    Route::get('/userType', [UserTypeRightsController::class, 'index'])->name('userTypeRights');
    Route::post('/userTypeRights/lodeTable', [UserTypeRightsController::class, 'lodeTable'])->name('userTypeRights.loadTable');
    Route::get('/userTypeRights/manageRights/{id}', [UserTypeRightsController::class, 'manageRights'])->name('userTypeRights.manageRights');
    Route::post('/userTypeRights/changeRights/{id}', [UserTypeRightsController::class, 'changeRights'])->name('userTypeRights.changeRights');
    Route::post('/userTypeRights/activeDeactivate', [UserTypeRightsController::class, 'activeDeactivate'])->name('userTypeRights.activeDeactivate');
    Route::post('/userTypeRights/delete/{id}', [UserTypeRightsController::class, 'delete'])->name('userTypeRights.delete');

    // user rights
    Route::get('/userRights/manageRights/{id}', [UserRightsController::class, 'manageRights'])->name('userRights.manageRights');
    Route::post('/userRights/changeRights/{id}', [UserRightsController::class, 'changeRights'])->name('userRights.changeRights');
    Route::post('/userRights/activeDeactivate', [UserRightsController::class, 'activeDeactivate'])->name('userRights.activeDeactivate');
    Route::get('/userRights/delete/{id}', [UserRightsController::class, 'delete'])->name('userRights.delete');

});
