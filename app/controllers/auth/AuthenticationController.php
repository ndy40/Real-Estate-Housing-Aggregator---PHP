<?php
namespace controllers\auth;

use BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
/**
 * This simply overrides the rest controller.
 *
 * @author ndy40
 */
class AuthenticationController extends BaseController
{
    protected $authLogic;
    
    protected $changePasswordRules = array(
        "id" => "required|integer",
        "password" => "required|alpha_num",
        "new_password" => "required|alpha_num|min:8|max:32|confirmed",
        "new_password_confirmation" => "required"
    );


    public function __construct() {
        $this->authLogic = App::make('AuthLogic');
    }
    
    public function index()
    {
        return View::make('dashboard');
    }
    
    public function login() 
    {
        if (Request::isMethod("get")) {
            return View::make("login");
        }
        
        $loginCred = Input::except("remember");
        $remember = Input::get("remember");
        $this->authLogic->authenticateUser(
            $loginCred['email'],
            $loginCred['password'], 
            $remember
        );
        
        if ($this->authLogic->isLoggedIn()) {
            return Redirect::route("dashboard");
        }
    }
    
    public function recovery ()
    {
        return "Yes recover";
    }
    
    /**
     * Logout a user from the application. 
     * 
     * @return Route
     */
    public function logOut(){
        $this->authLogic->logOut();
        
        return Redirect::route("adminLogin");
    }
    
    public function profile () 
    {
        $data = array();
        $data["user"] = $this->authLogic->getCurrentUser();
        
        return View::make("profile", $data);
    }
    
    /**
     * Method for changing password
     */
    public function changePassword ()
    {
        $validator = Validator::make(Input::get(), $this->changePasswordRules);
        $data = array();
        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $message) {
                $data["messages"][] = $message;
            }
            $data["status"]  = "error";
        } else {
            $id = (int)Input::get("id");
            $user = $this->authLogic->findUser($id);
            if (!$user->checkPassword(Input::get("password"))) {
                $data["messages"][] = "Old password does not match";
                $data["status"] = "error";
            } else {
                $saved = $this->authLogic->updateUser(
                    $id, 
                    array("password" => Input::get("new_password"))
                );
                if ($saved) {
                    $data["messages"][] = "Password updated.";
                    $data["status"] = "success";
                } else {
                    $data["messages"][] = "An error occured while updating password.";
                    $data["status"] = "error";
                }
            }
        }
        
        return Redirect::to("admin/profile")->with("status", $data);
    }
}
