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

//route for restful authenticatin countroller
Route::controller('auth', '\controllers\service\RestAuthController');


////////////////////////
////// Admin routes ////
///////////////////////

Route::group(array('prefix' => 'admin', "before" => "admin"), function () {
    Route::get("/", array(
        "uses" => "controllers\property\DashboardController@index",
        'as'   => "dashboard",
     ));
    
    Route::post("recovery", array(
        "uses" => "controllers\auth\AuthenticationController@recovery",
        "as" => "recovery",
    ));
    
    Route::any("profile", array(
        "uses" => "controllers\auth\AuthenticationController@profile",
        "as" => "profile",
    ));
    
    Route::any("changepassword", array(
        "uses" => "controllers\auth\AuthenticationController@changePassword",
        "as" => "changepassword",
    ));
    
    Route::controller("service", "controllers\property\PropertyController");
    Route::controller("scrape", "controllers\scrape\ScrapeConfigController");
    
    Route::get("property/country", array(
        "as" => "country", 
        "uses" => "controllers\property\PropertyController@country"
    ));
    
    Route::get("property/", array(
        "as" => "property", 
        "uses" => "controllers\property\PropertyController@index"
    ));
    
    Route::get("catalogue", array(
        "as" => "catalogue",
        "uses" => "controllers\scrape\ScrapeConfigController@index",
    ));
    
});

//define login rounte
Route::any('login', array(
    'uses' => 'controllers\auth\AuthenticationController@login', 
    "as" => "adminLogin"
));

Route::get("logout", array(
    "uses" => "controllers\auth\AuthenticationController@logout",
    'as' => "logout",
));
