<?php
namespace controllers\auth;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use controllers\auth\AuthenticationController;
use models\entities\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
/**
 * This is the resful controller for authentication.
 * 
 * @author Ndifreke Ekott <ndy40.ekott@gmail.com>
 */
class ClientAuthController extends AuthenticationController
{
    /**
     * 
     * @param mixed[] $data The json data to be returned.
     * @param int $code HTTP response code.
     * @param mixed[] $header Http headers to set.
     * @return JSON
     */
    protected function sendResponse (
        $data, 
        $code, 
        $header = array()
    ){
        return Response::json($data,$code, $header);
    }
    
    /**
     * Method for authentication
     * @return JSON
     */
    public function postIndex()
    {

        if (Request::isMethod("post")) {
            $credential = Input::get();
            $route = "login";
            
            $response = array();
            $responseCode = null;
                
            $user = $this->authLogic->authenticateUser(
                $credential['email'], 
                $credential['password'], 
                (isset($credential['remember'])? $credential['remember']: false)
            );
            
            if ($user instanceof User) {
                $response = $user->toArray();
                $responseCode = 200;
                $route = "home";
            }    

            return Redirect::route($route)->with(array("status" => $responseCode, "user" => $response));
        } else {

            return View::make("clientlogin");
        }    
    }
    
    /**
     * Method to logout the current user. 
     * @return boolean True/false if the user as been logged out.
     */
    public function getLogout() 
    {
        $this->authLogic->logout();
        $isLoggedOut = !$this->authLogic->isLoggedIn();
        if ($isLoggedOut) {
            $responseCode = 200;
        }
        
        return Redirect::route("login");
    }
    
    public function getStatus()
    {
        $user = $this->authLogic->getCurrentUser();
        if ($user) {
            $response = array ("data" => $user->toArray());
            $responseCode = 200;
        } else {
            throw new \Cartalyst\Sentry\Users\UserNotFoundException (
                "User not logged in."
            );
        }

        return $this->sendResponse($response, $responseCode);
    }
    
    
    public function postRegister()
    {
        try {
            $credential = Input::get();
            if (!array_key_exists("group",$credential)) {
                if (!Request::is("admin/*")) {
                    $credential['group'] = 'user';
                }
            }
            $location = $credential["location"];
            unset($credential['location']);
            
            $user = $this->authLogic->registerUser($credential);
            $this->authLogic->loginUser($user);
            
        } catch (Cartalyst\Sentry\Users\UserExistsException $ex) {
            $response = $ex->getMessage();
            $responseCode = 400;
        } catch (models\exceptions\UserCreationException $ex) {
            $response = $ex->getMessage();
            $responseCode = 400;
        } catch (Exception $ex) {
            $response = $ex->getMessage();
            $responseCode = 500;
        }
        
        return Redirect::to("/");
    }
    
    public function forgotPassword()
    {
        
    }
}