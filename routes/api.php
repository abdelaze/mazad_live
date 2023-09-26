<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VerifyEmailController;


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
Route::group(['middleware' =>'changelanguage'  , 'namespace' =>'Api'],function (){
    Route::group(['namespace' =>'Auth'],function (){
        Route::group(['prefix'=>'users'],function (){
            Route::post('register',             'UserAuthController@register');
            Route::post('sendverficationcode',  'UserAuthController@SendVerificationCode');
            Route::post('verify',               'UserAuthController@verify');
            Route::post('login',                'UserAuthController@login');
            Route::post('delete',               'UserAuthController@delete');
            Route::post('resend-verify-code',   'UserAuthController@resendCode');

            Route::group(['middleware' =>'checkuserverify'],function (){
                Route::group(['middleware' => 'auth:api'], function () {
                    Route::post('change_password',  'UserAuthController@change_password');
                    Route::post('user_info',        'UserAuthController@user_info');
                    Route::post('update_profile',   'UserAuthController@update_profile');
                    Route::post('verify_number_after_update',   'UserAuthController@verfiyNumberAfterUpdate');
                    Route::post('update/fcm_token', 'UserAuthController@updatefcmToken');
                    Route::get('all/notifications', 'UserAuthController@allNotifications');
                    Route::post('update/notifications', 'UserAuthController@updateNotifications');
                    Route::post('unread/notifications/count', 'UserAuthController@unreadNotifications');
                    Route::post('add/user/rate',           'UserAuthController@addToRates');
                });
            });

            Route::post('forgot-password',      'PasswordResetController@reset_password_request');
            Route::post('verify-token',         'PasswordResetController@verify_token');
            Route::post('reset-password',       'PasswordResetController@reset_password_submit');
        });
    });

    // verfiy email
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

    // Resend link to verify email
    Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['msg' => 'Verification link sent!', 'status' => true]);
    })->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

    Route::get('/email/verify/sucess', function () {
    return view('verified-account');
    })->name('verify.success');

    Route::get('/email/verify/already-sucess', function () {
    return view('verified-account');
    })->name('verify.already-success');


    Route::post('users/contact_us', 'ContactController@store');
    Route::get('usage/policies', 'UsagePolicyController@getUsagePolicy');
    Route::get('privacy/policies', 'UsagePolicyController@getPrivacyPolicy');
    Route::get('abouts', 'UsagePolicyController@getAbout');
    // country routes ///////////
    Route::get('get/countries', 'CountryController@index');
    Route::post('country/states', 'CountryController@getStates');
    Route::post('state/cities', 'CountryController@getStateCities');
      
    Route::name('api.ads.')->group(function () {
        Route::apiResource('ads', 'AdsController');
    });

    Route::name('api.onboardings.')->group(function () {
        Route::apiResource('onboardings', 'OnboardingController');
    });
    Route::name('api.categories.')->group(function () {
        Route::apiResource('categories', 'CategoryController');
        Route::group(['prefix'=>'categories'],function (){
           Route::get('/{id}/options', 'CategoryController@all_options');
        });  
    });
  
    Route::group(['middleware' =>'checkuserverify'],function (){
        Route::group(['middleware' => 'auth:api'], function () {
            Route::name('api.mazdats.')->group(function () {
                Route::apiResource('mazdats', 'MazdatController');
                Route::post('mazad/update/image/{mazdat_image}','MazdatController@updateMazadImage');
                Route::post('mazad/update/video/{mazdat_video}','MazdatController@updateMazadVideo');
                Route::post('mazad/open/{mazdat}'  , 'MazdatController@OpenMazad');
                Route::post('mazad/close/{mazdat}' , 'MazdatController@CloseMazad');
                Route::get('mazad/selected/user/{mazdat}'         , 'MazdatController@MazadSelectedUser');
                Route::post('/add/mazad/favorites' , 'MazdatController@addToFavorites');
                Route::get('user/favorites'        , 'MazdatController@UserFavorites');
                Route::post('mazad/select/user'    , 'MazdatController@SelectMazadUser');
                Route::get('mazads/selected/users'  , 'MazdatController@UserMazadsSelectedUsers');
                Route::get('user/mazdats/selected'  , 'MazdatController@MySelectedMazdats');
              //  Route::post('mazad/filter'         , 'MazdatController@filter');
                Route::post('mazad/add/rate',           'MazdatController@addToRates');
                Route::post('mazad/all/rates',                  'MazdatController@mazadRates')->name('all_rates');
                Route::post('mazad/search',                     'MazdatController@search');
                Route::get('featured_mazdats'                      ,'MazdatController@featuredMazdats');
                Route::get('most_sold_mazdats'                      ,'MazdatController@soldMazdats');
                Route::get('open_mazdats'                      ,'MazdatController@openMazdats');
                Route::get('/mazad/callback', 'MazdatController@paymentCallback');
                Route::post('/mazad/add/payment/reference', 'MazdatController@UpdatePaymentHistory');
            });
        });
    });
    Route::post('mazadat/filter'         , 'MazdatController@filter');

    // product api 
    Route::group(['middleware' =>'checkuserverify'],function (){
        Route::group(['middleware' => 'auth:api'], function () {
            Route::name('api.products.')->group(function () {
                Route::apiResource('products', 'ProductController');
                Route::post('product/update/image/{product_image}'   ,'ProductController@updateProductImage');
                Route::post('product/sold/{product}'                 , 'ProductController@SoldProduct');
                Route::post('/add/product/favorites'                 , 'ProductController@addToFavorites');
                Route::post('/add/user/favorites'                    , 'ProductController@addUserToFavorites');
                Route::get('user/products/favorites'                 , 'ProductController@UserFavoriteProducts');
                Route::get('user/favorite/users'                     , 'ProductController@UserFavoriteUsers');
                Route::post('product/select/user'                    , 'ProductController@SelectProductUser');
                Route::post('user/add/mazad/product'                 , 'ProductController@AddMazadProduct');
                Route::get('users/select/product/{product}'         , 'ProductController@UserSelectProduct');
                Route::get('product/selected/user/{product}'         , 'ProductController@ProductSelectedUser');
                Route::post('product/search',                         'ProductController@search');
                Route::get('featured_products'                      ,'ProductController@featuredProducts');
           
                Route::get('products/selected/users'  , 'ProductController@UserProductsSelectedUsers');
                Route::get('user/products/selected'  , 'ProductController@MySelectedProducts');
                Route::post('/product/add/payment/reference', 'ProductController@UpdatePaymentHistory');
           
            }); 
        });
    });
    Route::post('product/filter'         , 'ProductController@filter');

    // end product routes 
    Route::group(['middleware' =>'checkuserverify'],function (){
        Route::group(['middleware' => 'auth:api'], function () {
            Route::apiResource('messages', 'MessageController');
            Route::post('user/chats', 'MessageController@getUserChats');
            Route::post('user/chat/ref', 'MessageController@getChatId');
            Route::post('test/notify', 'MessageController@testNotitcation');
        });

    });
   

    // end 

});
