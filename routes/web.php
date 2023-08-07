<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/',[GameController::class,'index'])->name('root');
Route::get('/all',[GameController::class,'index_all'])->name('all');
Route::resource('games',GameController::class);
Route::post('/games/{gameId}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('games/{gameId}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::get('/search', [GameController::class,'search'])->name('search');
Route::get('/role_control', [RoleController::class,'index'])->name('controll')->middleware('can:admin');

Route::put('/users/{user}/set-admin', [RoleController::class,'setAdmin'])->name('users.set_admin');

// 更新用户角色为ROLE_MANAGER
Route::put('/users/{user}/set-manager', [RoleController::class,'setManager'])->name('users.set_manager');

// 更新用户角色为ROLE_USER
Route::put('/users/{user}/set-user', [RoleController::class,'setUser'])->name('users.set_user');