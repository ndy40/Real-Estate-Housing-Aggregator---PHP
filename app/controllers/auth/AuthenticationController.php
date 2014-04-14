<?php
namespace controllers\auth;

use BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
/**
 * This simply overrides the rest controller.
 *
 * @author ndy40
 */
class AuthenticationController extends BaseController
{
    protected $authLogic;
        
    public function __construct() {
        $this->authLogic = App::make('AuthLogic');
    }
    
    protected function doLogin() {
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
    
    public function index()
    {
        $view = "admin.login";
        
        if (Request::isMethod('post')) {
            $view = $this->doLogin();
        } elseif ($this->authLogic->isLoggedIn()) {
            return Redirect::intended('dashboard');
        }
        
        return View::make('admin.login');
    }
    
    public function recovery ()
    {
        return "Yes recover";
    }
    
    
}
