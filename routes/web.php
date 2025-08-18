<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostBodyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\CommentsController;
// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function(){
    // Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/suggested-users', [HomeController::class, 'suggested'])->name('suggested');
    Route::get('/all-suggested-users', [HomeController::class, 'allSuggested'])->name('all.suggested');

    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post/store',[PostController::class, 'store'])->name('post.store');
    Route::get('/post/{id}/show', [PostController::class, 'show'])->name('post.show');
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::patch('/post/{id}/update', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id}/delete', [PostController::class, 'delete'])->name('post.delete');
    Route::delete('/image/{id}/delete/', [PostController::class, 'deleteImage'])->name('image.delete');
    Route::delete('/post/{id}/deactivate', [PostController::class, 'deactivate'])->name('posts.deactivate');
    Route::patch('/post/{id}/activate', [PostController::class, 'activate'])->name('posts.activate');
    Route::post('/post/{id}/toggle-like', [PostController::class, 'toggleLike'])->name('post.toggleLike');

    Route::post('/comment/{post_id}/store', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('comment/{id}/delete', [CommentController::class, 'delete'])->name('comment.delete');

    //PROFILES
    Route::get('/profile/{id}/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{id}/followers', [ProfileController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{id}/following', [ProfileController::class, 'following'])->name('profile.following');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::patch('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
    
    //Categories
    Route::get('/show/{id}/category', [CategoryController::class, 'show'])->name('category.show');

    //photos
    Route::post('photos/{post_id}/store', [PostBodyController::class, 'store'])->name('post.body.store');
    Route::get('photos/{post_id}/edit/', [PostBodyController::class, 'edit'])->name('post.body.edit');
    Route::patch('photos/{post_id}/update', [PostBodyController::class, 'update'])->name('post.body.update');
    Route::delete('/photo/{id}', [PostBodyController::class, 'destroy'])->name('post.body.destroy');

    //Likes
    Route::post('/like/{post_id}/store', [LikeController::class, 'store'])->name('like.store');
    Route::delete('/like/{post_id}/delete', [LikeController::class, 'delete'])->name('like.delete');

    //Follow
    Route::post('/follow/{user_id}/store', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/follow/{user_id}/delete', [FollowController::class, 'delete'])->name('follow.delete');
    Route::post('/users/{user}/toggle-follow', [FollowController::class, 'toggleFollow'])
    ->name('follow.toggle');

    //ADMIN
    route::group(['prefix'=> 'admin', 'as' => 'admin.' , 'middleware' => 'admin'],function(){
        // Route::get('/users', [UsersController::class, 'index'])->name('users');
        Route::delete('/users/{id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');
        Route::patch('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');
        Route::get('/users', [UsersController::class, 'index'])->name('users');
        Route::patch('/users/{user_id}/roleid', [UsersController::class, 'updateRoleID'])->name('user.roleid');
        Route::get('/posts', [PostsController::class, 'index'])->name('posts');
        Route::delete('/posts/{id}/deactivate', [PostsController::class, 'deactivate'])->name('posts.deactivate');
        Route::patch('/posts/{id}/activate', [PostsController::class, 'activate'])->name('posts.activate');
        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
        Route::post('/categories/store', [CategoriesController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{id}/delete', [CategoriesController::class, 'delete'])->name('categories.delete');
        Route::patch('/categories/{id}/update', [CategoriesController::class, 'update'])->name('categories.update');
        Route::get('comments', [CommentsController::class, 'index'])->name('comments');
        Route::delete('/comments/{id}/deactivate', [CommentsController::class, 'deactivate'])->name('comments.deactivate');
        Route::patch('/comments/{id}/activate', [CommentsController::class, 'activate'])->name('comments.activate');
    });
});
