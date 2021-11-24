<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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


Route::group(['middleware' => 'api'], function($router) {
    Route::get('/', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    })->name('api.hello');
});

Route::group(['middleware' =>  ['jwt.auth'],'prefix' => 'auth'], function ($router) {


//books
Route::get('/books', 'App\Http\Controllers\Api\Auth\BooksController@index')->name('api.auth.index.books');
Route::get('/books/{id}', 'App\Http\Controllers\Api\Auth\BooksController@show')->name('api.auth.show.books');
Route::post('/books', 'App\Http\Controllers\Api\Auth\BooksController@store')->name('api.auth.store.books');
Route::put('/books/{id}', 'App\Http\Controllers\Api\Auth\BooksController@update')->name('api.auth.update.books');
Route::delete('/books/{id}', 'App\Http\Controllers\Api\Auth\BooksController@destroy')->name('api.auth.delete.books');
Route::get('/books/search/{search}', 'App\Http\Controllers\Api\Auth\BooksController@search')->name('api.auth.search.books');
Route::get('/books/filter/{book_category_id}', 'App\Http\Controllers\Api\Auth\BooksController@filter_book')->name('api.auth.filter.books');

//book_categories
Route::get('/book_categories', 'App\Http\Controllers\Api\Auth\BookCategoriesController@index')->name('api.auth.index.book_categories');
Route::get('/book_categories/{id}', 'App\Http\Controllers\Api\Auth\BookCategoriesController@show')->name('api.auth.show.book_categories');
Route::post('/book_categories', 'App\Http\Controllers\Api\Auth\BookCategoriesController@store')->name('api.auth.store.book_categories');
Route::put('/book_categories/{id}', 'App\Http\Controllers\Api\Auth\BookCategoriesController@update')->name('api.auth.update.book_categories');
Route::delete('/book_categories/{id}', 'App\Http\Controllers\Api\Auth\BookCategoriesController@destroy')->name('api.auth.delete.book_categories');
Route::get('/book_categories/search/{search}', 'App\Http\Controllers\Api\Auth\BookCategoriesController@search')->name('api.auth.search.book_categories');

//book_user
Route::get('/book_users', 'App\Http\Controllers\Api\Auth\BookUsersController@index')->name('api.auth.index.book_users');
Route::get('/book_users/{id}', 'App\Http\Controllers\Api\Auth\BookUsersController@show')->name('api.auth.show.book_users');
Route::post('/book_users', 'App\Http\Controllers\Api\Auth\BookUsersController@store')->name('api.auth.store.book_users');
Route::put('/book_users/{id}', 'App\Http\Controllers\Api\Auth\BookUsersController@update')->name('api.auth.update.book_users');
Route::delete('/book_users/{id}', 'App\Http\Controllers\Api\Auth\BookUsersController@destroy')->name('api.auth.delete.book_users');
Route::get('/book_users/search/{search}', 'App\Http\Controllers\Api\Auth\BookUsersController@search')->name('api.auth.search.book_users');

//card
Route::get('/cards', 'App\Http\Controllers\Api\Auth\CardsController@index')->name('api.auth.index.cards');
Route::get('/cards/{id}', 'App\Http\Controllers\Api\Auth\CardsController@show')->name('api.auth.show.cards');
Route::post('/cards', 'App\Http\Controllers\Api\Auth\CardsController@store')->name('api.auth.store.cards');
Route::put('/cards/{id}', 'App\Http\Controllers\Api\Auth\CardsController@update')->name('api.auth.update.cards');
Route::delete('/cards/{id}', 'App\Http\Controllers\Api\Auth\CardsController@destroy')->name('api.auth.delete.cards');
Route::get('/cards/search/{search}', 'App\Http\Controllers\Api\Auth\CardsController@search')->name('api.auth.search.cards');

//failed_job
Route::get('/failed_jobs', 'App\Http\Controllers\Api\Auth\FailedJobsController@index')->name('api.auth.index.failed_jobs');
Route::get('/failed_jobs/{id}', 'App\Http\Controllers\Api\Auth\FailedJobsController@show')->name('api.auth.show.failed_jobs');
Route::post('/failed_jobs', 'App\Http\Controllers\Api\Auth\FailedJobsController@store')->name('api.auth.store.failed_jobs');
Route::put('/failed_jobs/{id}', 'App\Http\Controllers\Api\Auth\FailedJobsController@update')->name('api.auth.update.failed_jobs');
Route::delete('/failed_jobs/{id}', 'App\Http\Controllers\Api\Auth\FailedJobsController@destroy')->name('api.auth.delete.failed_jobs');
Route::get('/failed_jobs/search/{search}', 'App\Http\Controllers\Api\Auth\FailedJobsController@search')->name('api.auth.search.failed_jobs');

//food
Route::get('/food', 'App\Http\Controllers\Api\Auth\FoodController@index')->name('api.auth.index.food');
Route::get('/food/{id}', 'App\Http\Controllers\Api\Auth\FoodController@show')->name('api.auth.show.food');
Route::post('/food', 'App\Http\Controllers\Api\Auth\FoodController@store')->name('api.auth.store.food');
Route::post('/food/{id}', 'App\Http\Controllers\Api\Auth\FoodController@update')->name('api.auth.update.food');
Route::delete('/food/{id}', 'App\Http\Controllers\Api\Auth\FoodController@destroy')->name('api.auth.delete.food');
Route::get('/food/search/{search}', 'App\Http\Controllers\Api\Auth\FoodController@search')->name('api.auth.search.food');

//food_categories
Route::get('/food_categories', 'App\Http\Controllers\Api\Auth\FoodCategoriesController@index')->name('api.auth.index.food_categories');
Route::get('/food_categories/{id}', 'App\Http\Controllers\Api\Auth\FoodCategoriesController@show')->name('api.auth.show.food_categories');
Route::post('/food_categories', 'App\Http\Controllers\Api\Auth\FoodCategoriesController@store')->name('api.auth.store.food_categories');
Route::post('/food_categories/{id}', 'App\Http\Controllers\Api\Auth\FoodCategoriesController@update')->name('api.auth.update.food_categories');
Route::delete('/food_categories/{id}', 'App\Http\Controllers\Api\Auth\FoodCategoriesController@destroy')->name('api.auth.delete.food_categories');
Route::get('/food_categories/search/{search}', 'App\Http\Controllers\Api\Auth\FoodCategoriesController@search')->name('api.auth.search.food_categories');

//food with categories
Route::get('food_pos/{id}', 'App\Http\Controllers\Api\Auth\FoodCategoriesController@food_pos')->name('api.auth.food_pos');

//food_order
Route::get('/food_orders', 'App\Http\Controllers\Api\Auth\FoodOrdersController@index')->name('api.auth.index.food_orders');
Route::get('/food_orders/{id}', 'App\Http\Controllers\Api\Auth\FoodOrdersController@show')->name('api.auth.show.food_orders');
Route::post('/food_orders', 'App\Http\Controllers\Api\Auth\FoodOrdersController@store')->name('api.auth.store.food_orders');
Route::put('/food_orders/{id}', 'App\Http\Controllers\Api\Auth\FoodOrdersController@update')->name('api.auth.update.food_orders');
Route::delete('/food_orders/{id}', 'App\Http\Controllers\Api\Auth\FoodOrdersController@destroy')->name('api.auth.delete.food_orders');
Route::get('/food_orders/search/{search}', 'App\Http\Controllers\Api\Auth\FoodOrdersController@search')->name('api.auth.search.food_orders');

//funds
Route::get('/funds', 'App\Http\Controllers\Api\Auth\FundsController@index')->name('api.auth.index.funds');
Route::get('/funds/{id}', 'App\Http\Controllers\Api\Auth\FundsController@show')->name('api.auth.show.funds');
Route::post('/funds', 'App\Http\Controllers\Api\Auth\FundsController@store')->name('api.auth.store.funds');
Route::put('/funds/{id}', 'App\Http\Controllers\Api\Auth\FundsController@update')->name('api.auth.update.funds');
Route::delete('/funds/{id}', 'App\Http\Controllers\Api\Auth\FundsController@destroy')->name('api.auth.delete.funds');
Route::get('/funds/search/{search}', 'App\Http\Controllers\Api\Auth\FundsController@search')->name('api.auth.search.funds');

//migration
Route::get('/migrations', 'App\Http\Controllers\Api\Auth\MigrationsController@index')->name('api.auth.index.migrations');
Route::get('/migrations/{id}', 'App\Http\Controllers\Api\Auth\MigrationsController@show')->name('api.auth.show.migrations');
Route::post('/migrations', 'App\Http\Controllers\Api\Auth\MigrationsController@store')->name('api.auth.store.migrations');
Route::put('/migrations/{id}', 'App\Http\Controllers\Api\Auth\MigrationsController@update')->name('api.auth.update.migrations');
Route::delete('/migrations/{id}', 'App\Http\Controllers\Api\Auth\MigrationsController@destroy')->name('api.auth.delete.migrations');
Route::get('/migrations/search/{search}', 'App\Http\Controllers\Api\Auth\MigrationsController@search')->name('api.auth.search.migrations');

//permissions
Route::get('/permissions', 'App\Http\Controllers\Api\Auth\PermissionsController@index')->name('api.auth.index.permissions');
Route::get('/permissions/{id}', 'App\Http\Controllers\Api\Auth\PermissionsController@show')->name('api.auth.show.permissions');
Route::post('/permissions', 'App\Http\Controllers\Api\Auth\PermissionsController@store')->name('api.auth.store.permissions');
Route::put('/permissions/{id}', 'App\Http\Controllers\Api\Auth\PermissionsController@update')->name('api.auth.update.permissions');
Route::delete('/permissions/{id}', 'App\Http\Controllers\Api\Auth\PermissionsController@destroy')->name('api.auth.delete.permissions');
Route::get('/permissions/search/{search}', 'App\Http\Controllers\Api\Auth\PermissionsController@search')->name('api.auth.search.permissions');

//reviews
Route::get('/reviews', 'App\Http\Controllers\Api\Auth\ReviewsController@index')->name('api.auth.index.reviews');
Route::get('/reviews/{id}', 'App\Http\Controllers\Api\Auth\ReviewsController@show')->name('api.auth.show.reviews');
Route::post('/reviews', 'App\Http\Controllers\Api\Auth\ReviewsController@store')->name('api.auth.store.reviews');
Route::put('/reviews/{id}', 'App\Http\Controllers\Api\Auth\ReviewsController@update')->name('api.auth.update.reviews');
Route::delete('/reviews/{id}', 'App\Http\Controllers\Api\Auth\ReviewsController@destroy')->name('api.auth.delete.reviews');
Route::get('/reviews/search/{search}', 'App\Http\Controllers\Api\Auth\ReviewsController@search')->name('api.auth.search.reviews');

//roles
Route::get('/roles', 'App\Http\Controllers\Api\Auth\RolesController@index')->name('api.auth.index.roles');
Route::get('/roles/{id}', 'App\Http\Controllers\Api\Auth\RolesController@show')->name('api.auth.show.roles');
Route::post('/roles', 'App\Http\Controllers\Api\Auth\RolesController@store')->name('api.auth.store.roles');
Route::put('/roles/{id}', 'App\Http\Controllers\Api\Auth\RolesController@update')->name('api.auth.update.roles');
Route::delete('/roles/{id}', 'App\Http\Controllers\Api\Auth\RolesController@destroy')->name('api.auth.delete.roles');
Route::get('/roles/search/{search}', 'App\Http\Controllers\Api\Auth\RolesController@search')->name('api.auth.search.roles');

//role_permission
Route::get('/role_permissions', 'App\Http\Controllers\Api\Auth\RolePermissionsController@index')->name('api.auth.index.role_permissions');
Route::get('/role_permissions/{id}', 'App\Http\Controllers\Api\Auth\RolePermissionsController@show')->name('api.auth.show.role_permissions');
Route::post('/role_permissions', 'App\Http\Controllers\Api\Auth\RolePermissionsController@store')->name('api.auth.store.role_permissions');
Route::put('/role_permissions/{id}', 'App\Http\Controllers\Api\Auth\RolePermissionsController@update')->name('api.auth.update.role_permissions');
Route::delete('/role_permissions/{id}', 'App\Http\Controllers\Api\Auth\RolePermissionsController@destroy')->name('api.auth.delete.role_permissions');
Route::get('/role_permissions/search/{search}', 'App\Http\Controllers\Api\Auth\RolePermissionsController@search')->name('api.auth.search.role_permissions');

//users
Route::get('/users', 'App\Http\Controllers\Api\Auth\UsersController@index')->name('api.auth.index.users');
Route::get('/users/{id}', 'App\Http\Controllers\Api\Auth\UsersController@show')->name('api.auth.show.users');
Route::post('/users', 'App\Http\Controllers\Api\Auth\UsersController@store')->name('api.auth.store.users');
Route::put('/users/{id}', 'App\Http\Controllers\Api\Auth\UsersController@update')->name('api.auth.update.users');
Route::delete('/users/{id}', 'App\Http\Controllers\Api\Auth\UsersController@destroy')->name('api.auth.delete.users');
Route::get('/users/search/{search}', 'App\Http\Controllers\Api\Auth\UsersController@search')->name('api.auth.search.users');

//variations
Route::get('/variations', 'App\Http\Controllers\Api\Auth\VariationsController@index')->name('api.auth.index.variations');
Route::get('/variations/{id}', 'App\Http\Controllers\Api\Auth\VariationsController@show')->name('api.auth.show.variations');
Route::post('/variations', 'App\Http\Controllers\Api\Auth\VariationsController@store')->name('api.auth.store.variations');
Route::post('/variations/{id}', 'App\Http\Controllers\Api\Auth\VariationsController@update')->name('api.auth.update.variations');
Route::delete('/variations/{id}', 'App\Http\Controllers\Api\Auth\VariationsController@destroy')->name('api.auth.delete.variations');
Route::get('/variations/search/{search}', 'App\Http\Controllers\Api\Auth\VariationsController@search')->name('api.auth.search.variations');

//visitors
Route::get('/visitors', 'App\Http\Controllers\Api\Auth\VisitorsController@index')->name('api.auth.index.visitors');
Route::get('/visitors/{id}', 'App\Http\Controllers\Api\Auth\VisitorsController@show')->name('api.auth.show.visitors');
Route::post('/visitors', 'App\Http\Controllers\Api\Auth\VisitorsController@store')->name('api.auth.store.visitors');
Route::put('/visitors/{id}', 'App\Http\Controllers\Api\Auth\VisitorsController@update')->name('api.auth.update.visitors');
Route::delete('/visitors/{id}', 'App\Http\Controllers\Api\Auth\VisitorsController@destroy')->name('api.auth.delete.visitors');
Route::get('/visitors/search/{search}', 'App\Http\Controllers\Api\Auth\VisitorsController@search')->name('api.auth.search.visitors');

//visitor_food
Route::get('/visitor_food', 'App\Http\Controllers\Api\Auth\VisitorFoodController@index')->name('api.auth.index.visitor_food');
Route::get('/visitor_food/{id}', 'App\Http\Controllers\Api\Auth\VisitorFoodController@show')->name('api.auth.show.visitor_food');
Route::post('/visitor_food', 'App\Http\Controllers\Api\Auth\VisitorFoodController@store')->name('api.auth.store.visitor_food');
Route::put('/visitor_food/{id}', 'App\Http\Controllers\Api\Auth\VisitorFoodController@update')->name('api.auth.update.visitor_food');
Route::delete('/visitor_food/{id}', 'App\Http\Controllers\Api\Auth\VisitorFoodController@destroy')->name('api.auth.delete.visitor_food');
Route::get('/visitor_food/search/{search}', 'App\Http\Controllers\Api\Auth\VisitorFoodController@search')->name('api.auth.search.visitor_food');

//visitor_information
Route::get('/visitor_information', 'App\Http\Controllers\Api\Auth\VisitorInformationController@index')->name('api.auth.index.visitor_information');
Route::get('/visitor_information/{id}', 'App\Http\Controllers\Api\Auth\VisitorInformationController@show')->name('api.auth.show.visitor_information');
Route::post('/visitor_information', 'App\Http\Controllers\Api\Auth\VisitorInformationController@store')->name('api.auth.store.visitor_information');
Route::put('/visitor_information/{id}', 'App\Http\Controllers\Api\Auth\VisitorInformationController@update')->name('api.auth.update.visitor_information');
Route::delete('/visitor_information/{id}', 'App\Http\Controllers\Api\Auth\VisitorInformationController@destroy')->name('api.auth.delete.visitor_information');
Route::get('/visitor_information/search/{search}', 'App\Http\Controllers\Api\Auth\VisitorInformationController@search')->name('api.auth.search.visitor_info');
   
});




Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']); 
    Route::post('/library-login', [AuthController::class, 'libraryLogin']);

});













