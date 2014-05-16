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

Route::get("/", array("as" => "home", function () {
    $repo = App::make("AuthLogic");
    if ($repo->isLoggedIn()) {
        return View::make("index");
    } else {
        return Redirect::route("login");
    }
}));

Route::any("signin", array(
    "as"    => "login",
    "uses"  => "controllers\auth\ClientAuthController@postIndex"
));

Route::get("signout", array(
    "as"    => "logout",
    "uses"  => "controllers\auth\ClientAuthController@getLogout"
));

Route::any("forgot", array(
    "as"    => "forgotpassword",
    "uses"  => "controllers\auth\ClientAuthController@forgotPassword"
));

Route::get("signup", array("as" => "signup", function () {
    return View::make("clientnew");
}));

Route::post("register", array(
    "as"    => "register", 
    "uses"  => "controllers\auth\ClientAuthController@postRegister"
));

////////////////////////
////// Admin routes ////
///////////////////////

Route::group(array('prefix' => 'admin', "before" => "admin"), function () {
    Route::get("dashboard", array(
        "uses"  => "controllers\property\DashboardController@index",
        'as'    => "dashboard",
     ));
    
    Route::post("recovery", array(
        "uses"  => "controllers\auth\AuthenticationController@recovery",
        "as"    => "recovery",
    ));
    
    Route::any("profile", array(
        "uses"  => "controllers\auth\AuthenticationController@profile",
        "as"    => "profile",
    ));
    
    Route::any("changepassword", array(
        "uses"  => "controllers\auth\AuthenticationController@changePassword",
        "as"    => "changepassword",
    ));
    
    Route::controller("service", "controllers\property\PropertyController");
    Route::controller("scrape", "controllers\scrape\ScrapeConfigController");
    
    Route::get("property/country", array(
        "as"    => "country",
        "uses"  => "controllers\property\PropertyController@country"
    ));
    
    Route::get("property", array(
        "as"    => "property",
        "uses"  => "controllers\property\PropertyController@index"
    ));

    Route::get("property/postcode", array(
        "as"    => "postcode",
        "uses"  => "controllers\property\PropertyController@getPostcode"
    ));
    
    Route::get("catalogue", array(
        "as"    => "catalogue",
        "uses"  => "controllers\scrape\ScrapeConfigController@index",
    ));

    Route::get("failedscrapes", array(
        "as"    => "failedScrapes",
        "uses"  => "controllers\scrape\ScrapeConfigController@failedScrapes"

    ));
    
});
//define login rounte
Route::any('login', array(
    'uses' => 'controllers\auth\AuthenticationController@login', 
    "as" => "adminLogin"
));

Route::get("logout", array(
    "uses" => "controllers\auth\AuthenticationController@logout",
    'as' => "adminlogout",
));

///////////////////////////////
//////// Client Service //////
/////////////////////////////

Route::group(array("prefix" => "client"), function (){
    Route::controller("search", "controllers\search\SearchController");
});
 

