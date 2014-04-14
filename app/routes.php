<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function() 
{
	return View::make('index');
});

/*	Old Routes
Route::get('signup', array('uses' => 'UserController@get_new'));

Route::get('dashboard', array('uses' => 'DashController@get_index'));

Route::get('calculations', array('uses' => 'CalcController@get_new'));
Route::get('working', array('uses' => 'CalcController@get_index'));
*/

//route for restful authenticatin countroller
Route::controller('auth', '\controllers\service\RestAuthController');


////////////////////////
////// Admin routes ////
///////////////////////

Route::group(array('prefix' => 'admin'), function () {
    //define login rounte
    Route::any('/', array(
        'uses' => 'controllers\auth\AuthenticationController@index', 
        "as" => "admin"
    ));
    
    Route::post("/recovery", array(
        "uses" => "controllers\auth\AuthenticationController@recovery",
        "as" => "recovery",
    ));
    
    Route::get("/dashboard", array(
        "uses" => "controllers\property\DashboardController@index",
        'as'   => "dashboard",
    ));
});