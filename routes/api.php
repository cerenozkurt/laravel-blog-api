<?php

use App\Http\Controllers\Admin\AdminAuth;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscribeController as AdminSubscribeController;
use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\Frontend\GetPostController;
use App\Http\Controllers\Frontend\Contact;
use App\Http\Controllers\Frontend\SubscribeController;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::controller(AdminAuth::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});


//protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('/admin')->group(function () {

        //admin get token
        Route::controller(AdminAuth::class)->group(function () {
            Route::get('/getTokens', 'getTokens'); //get tokens
            Route::post('/logout', 'logout');
        });

        //categorys routes
        Route::prefix('categorys')->group(function () {
            Route::controller(CategoryController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/',  'store');
                Route::put('/{categorys}', 'edit');
                Route::post('/{categorys}', 'update');
                Route::delete('/{categorys}', 'delete');
                Route::get('/{categorys}', 'search');
                Route::get('/total', 'getTotalCategory');
            });
        });

        //posts routes
        Route::prefix('posts')->group(function () {
            Route::controller(PostController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/create/{posts}', 'store');
                Route::put('/{posts}', 'edit');
                Route::post('/{posts}', 'update');
                Route::delete('/{posts}', 'delete');
                Route::get('/{posts}', 'search');
            });
        });


        //setting routes
        Route::prefix('settings')->group(function () {
            Route::controller(SettingController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/{settings}', 'update');
            });
        });

        //contact routes
        Route::prefix('contacts')->group(function () {
            Route::controller(ContactController::class)->group(function () {
                Route::get('/', 'getContact');
                Route::delete('/{contacts}', 'delete');
            });
        });

        //subscribe routes
        Route::prefix('subscribe')->group(function () {
            Route::controller(AdminSubscribeController::class)->group(function () {
                Route::get('/', 'index');
                Route::delete('/{subscribe}', 'delete');
            });
        });

        //comment routes
        Route::prefix('comments')->group(function () {
            Route::controller(AdminCommentController::class)->group(function () {
                Route::get('/', 'getComments');
                Route::delete('/{comments}', 'delete');
                Route::get('/total', 'gettotalComments');
            });
        });
    });
});



//public routes
Route::prefix('/front')->group(function () {


    //post routes
    Route::prefix('/posts')->group(function () {
        Route::controller(GetPostController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/view', 'viewPosts');
            Route::get('/{posts}/id', 'getPostById');
            Route::get('/{posts}', 'searchPost');
            Route::get('/category/{posts}', 'getPostByCategory');
        });
    });

    //contact routes
    Route::prefix('contact')->group(function () {
        Route::controller(Contact::class)->group(function () {
            Route::post('/', 'store');
        });
    });

    //subscribe routes
    Route::prefix('subscribe')->group(function () {
        Route::controller(SubscribeController::class)->group(function () {
            Route::post('/', 'subscribe');
        });
    });


    //comment routes
    Route::prefix('comments')->group(function () {
        Route::controller(CommentController::class)->group(function () {
            Route::get('/', 'getComments');
            Route::post('/{comments}', 'store');
        });
    });
});
