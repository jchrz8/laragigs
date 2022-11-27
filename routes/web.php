<?php

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;
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
//all
Route::get('/', [ListingController::class,'index']);
//show create form
Route::get('/listings/create', [ListingController::class,'create'])
->middleware('auth');
//Store Listing Data
Route::post('/listings', [ListingController::class, 'store'])
->middleware('auth');
//show edit form
Route::get('/listings/{listing}/edit',[ListingController::class, 'edit'])
->middleware('auth');
//Update Submit
Route::put('/listings/{listing}',[ListingController::class, 'update'])
->middleware('auth');
//Delete listing
Route::delete('/listings/{listing}',[ListingController::class, 'destroy'])
->middleware('auth');

//manage Listings
Route::get('/listings/manage',[ListingController::class,'manage'])
->middleware('auth');

//single
Route::get('/listings/{listing}', [ListingController::class, 'show']);

//register/create
Route::get('/register',[UserController::class, 'create'])
->middleware('guest');



//register/Create new users
Route::post('/users',[UserController::class,'store']);
//logout
Route::post('/logout',[UserController::class, 'logout'])
->middleware('auth');
//login form
Route::get('/login', [UserController::class, 'login'])
->name('login')
->middleware('guest');
//login user
Route::post('/users/authenticate',[UserController::class,'authenticate']);

// Route::get('/hello', function () {
//     return response('<h1>Hello World</h1>', 200)
//     ->header('Content-Type','text/plain')
//     ->header('foo','bar');
// });
// Route::get('/posts/{id}', function ($id) {
//     dd($id);
//     return response('Post '.$id);
// })->where('id','[0-9]+');
// Route::get('/search', function(Request $request) {
//     return $request->name . ' ' . $request->city;
    
// });