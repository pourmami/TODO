<?php

use \App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\PostController;
use \App\Http\Controllers\CommentController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{id}', [PostController::class, 'show']);
    Route::get('posts/{id}/comments', [PostController::class, 'comments']);
    Route::post('posts/create', [PostController::class, 'store']);
    Route::put('update/{post}', [PostController::class, 'update']);
    Route::delete('delete/{post}', [PostController::class, 'destroy']);
    Route::get('post/{id}/tags', [PostController::class, 'get_tags']);

    Route::get('comments', [CommentController::class, 'index']);
    Route::get('comments/{id}', [CommentController::class, 'show']);
    Route::post('comments/create', [CommentController::class, 'store']);
    Route::put('update/{comment}', [CommentController::class, 'update']);
    Route::delete('delete/{comment}', [CommentController::class, 'destroy']);
    Route::get('comment/{id}/tags', [CommentController::class, 'get_tags']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::post('categories/create', [CategoryController::class, 'store']);
    Route::put('update/{category}', [CategoryController::class, 'update']);
    Route::delete('delete/{category}', [CategoryController::class, 'destroy']);
    Route::get('categories/{id}/tags', [CategoryController::class, 'get_tags']);

    Route::post('tags/posts',[TagController::class,'index']);
});
