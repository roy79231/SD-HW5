<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\RoleController;
use App\Models\Game;
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

//登入
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//HOT和全部列表
Route::get('/',[GameController::class,'index'])->name('root');
Route::get('/all',[GameController::class,'index_all'])->name('all');
Route::resource('games',GameController::class);//編輯 刪除..等等

//留言
Route::post('/games/{gameId}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('games/{gameId}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

//搜尋欄
Route::get('/search', [GameController::class,'search'])->name('search');

//權限控制頁面
Route::get('/role_control', [RoleController::class,'index'])->name('controll')->middleware('can:admin');

// 更新成 ROLE_ADMIN
Route::put('/users/{user}/set-admin', [RoleController::class,'setAdmin'])->name('users.set_admin');

// 更新用户角色为ROLE_MANAGER
Route::put('/users/{user}/set-manager', [RoleController::class,'setManager'])->name('users.set_manager');

// 更新用户角色为ROLE_USER
Route::put('/users/{user}/set-user', [RoleController::class,'setUser'])->name('users.set_user');

//購物車
Route::get('/shoplist',[GameController::class,'shoplist'])->name('shoplist');
Route::post('/games/{gameId}/add-to-cart', [GameController::class,'addToCart'])->name('games.addToCart');//加入購物車
Route::post('/shoplist/clean', [GameController::class,'cleanShoplist'])->name('shoplist.clean');//清除購物車
Route::delete('/shoplist/{id}', [GameController::class,'destroyShoplist'])->name('shoplist.destroy');
Route::post('/shoplist/buy', [GameController::class,'buyFromShoplist'])->name('shoplist.buy');
