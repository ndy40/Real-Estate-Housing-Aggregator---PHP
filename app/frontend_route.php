<?php
/**
 * Created by PhpStorm.
 * User: ndy40
 * Date: 06/06/2014
 * Time: 04:51
 */
/////////////////////////////
////// Front end routes ////
///////////////////////////

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
    "uses"  => "controllers\\auth\\ClientAuthController@postIndex"
));

Route::get("signout", array(
    "as"    => "logout",
    "uses"  => "controllers\auth\ClientAuthController@getLogout"
));

Route::any("forgot", array(
    "as"    => "forgotpassword",
    "uses"  => "controllers\auth\ClientAuthController@forgotPassword"
));

Route::any("resetpassword/{code?}", array(
    "as"     => "resetpassword",
    "uses"  => "controllers\auth\ClientAuthController@resetPassword",
));

Route::get("signup", array("as" => "signup", function () {
    return View::make("clientnew");
}));

Route::post("register", array(
    "as"    => "register",
    "uses"  => "controllers\auth\ClientAuthController@postRegister"
));


Route::get("images", "controllers\ImageTestController@index");

///////////////////////////////
//////// Client Service //////
/////////////////////////////

Route::group(array("prefix" => "client"), function (){
    Route::controller("search", "controllers\search\SearchController");
    Route::controller("auth", "controllers\service\AuthService");
});
