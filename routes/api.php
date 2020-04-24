<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('email-list/add', 'Api\EmailListController@add')->name('email-list.add');

Route::middleware('auth:api')->name('api.')->group(function(){
    Route::name('teachers.')->prefix('teacher')->group(function(){
        Route::get('/generate_invite_code', "Api\TeacherController@generate_invite_code")->name('generate_invite_code');
    });

    Route::name('dictionary.')->prefix('dictionary')->group(function(){
        Route::post('/query', "Api\DictionaryController@query")->name('query');
    });

    Route::name('vocabulary.')->prefix('vocabulary')->group(function(){
        Route::post('/add', 'Api\DictionaryController@addToVocabulary')->name('add');
    });

    Route::name('chat.')->prefix('chat')->group(function(){
        Route::get('conversations', "Api\ChatController@getConversations")->name('get_conversations');
        Route::post('send', 'Api\ChatController@sendMessage')->name('send');
    });

    Route::name('dashboard.')->prefix('dashboard')->group(function(){
        Route::get('vocabSizePerDayThisMonth', 'Api\DashboardController@vocabSizePerDayThisMonth')->name('vocab_size');
    });

    Route::name('learningpath.')->prefix('learning_path')->group(function(){
        Route::prefix('items')->name('items.')->group(function(){
            Route::post('create', 'Api\LearningPathController@newItem')->middleware('isRole:admin')->name('store');
            Route::post('{item}/delete', 'Api\LearningPathController@deleteItem')->middleware('isRole:admin')->name('delete');
            Route::post('{item}/update', 'Api\LearningPathController@updateItem')->middleware('isRole:admin')->name('update');

            Route::name('review.')->prefix('review')->group(function(){
                Route::post('update_level', 'Api\LearningPathController@updateLevel')->name('update_level');
            });
        });
    });

    Route::name('video_lesson.')->prefix('video_lesson')->group(function(){
        Route::post('update_availability', 'Api\VideoLessonController@updateAvailability')->name('updateAvailability');
        Route::get('fetch_availability', 'Api\VideoLessonController@fetchAvailabilities')->name('fetchAvailability');
        Route::post('fetch_availability_for_date', 'Api\VideoLessonController@fetchAvailabilitiesForDate')->name('fetchAvailabilityDate');
    });

    Route::name('payment.')->prefix('payment')->middleware('isRole:student')->group(function(){
        Route::post('add-payment-method', 'Api\PaymentController@addPaymentMethod')->name('add-payment-method');
        Route::post('delete-payment-method', 'Api\PaymentController@deletePaymentMethod')->name('delete-payment-method');
    });
});
