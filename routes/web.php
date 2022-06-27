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



Auth::routes(['register'=>false]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


$vuePages = ['home', 'profile', 'staff', 'create-task'];
foreach ($vuePages as $page) {
    Route::get("/$page", [App\Http\Controllers\HomeController::class, 'index'])->name($page);
}
Route::get('/task/{id}', [App\Http\Controllers\HomeController::class, 'index'])->name('viewTask');

Route::get("/vue/profile", [App\Http\Controllers\HomeController::class, 'vueProfile'])->name('vueProfile');
Route::post("/vue/save-profile", [App\Http\Controllers\HomeController::class, 'vueSaveProfile'])->name('vueSaveProfile');
Route::get("/vue/home", [App\Http\Controllers\HomeController::class, 'vueHome'])->name('vueHome');
Route::get("/vue/home-staff", [App\Http\Controllers\HomeController::class, 'vueHomeStaff'])->name('vueHomeStaff');
Route::get("/vue/create-task", [App\Http\Controllers\HomeController::class, 'vueCreateTask'])->name('vueCreateTask');
Route::post("/vue/create-task-submit", [App\Http\Controllers\HomeController::class, 'vueCreateTaskSubmit'])->name('vueCreateTaskSubmit');

Route::get("/vue/fetch-task", [App\Http\Controllers\HomeController::class, 'vueFetchTask'])->name('vueFetchTask');
Route::get("/vue/set-task-status", [App\Http\Controllers\HomeController::class, 'vueTaskStatus'])->name('vueTaskStatus');
Route::get("/vue/view-task", [App\Http\Controllers\HomeController::class, 'vueTaskView'])->name('vueTaskView');
Route::get("/vue/get-edit-data", [App\Http\Controllers\HomeController::class, 'vueTaskEditData'])->name('vueTaskEditData');




//
Route::middleware(['admin'])->group(function () {
    Route::get("/admin", [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
    Route::get("/vue/admin/save-user-role", [App\Http\Controllers\AdminController::class, 'vueAdminSaveUserRole'])->name('vueAdminSaveUserRole');
    Route::get("/vue/admin/save-user-head", [App\Http\Controllers\AdminController::class, 'vueAdminSaveUserHead'])->name('vueAdminSaveUserHead');
    Route::get("/vue/admin", [App\Http\Controllers\AdminController::class, 'vueAdmin'])->name('vueAdmin');
});
