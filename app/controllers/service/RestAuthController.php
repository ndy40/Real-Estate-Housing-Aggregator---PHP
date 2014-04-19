<?php
namespace controllers\service;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use controllers\auth\AuthenticationController;
use models\entities\User;
use Illuminate\Support\Facades\Request;
/**
 * This is the resful controller for authentication.
 * 
 * @author Ndifreke Ekott <ndy40.ekott@gmail.com>
 */
class RestAuthController extends AuthenticationController
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
        $data = Input::get('data');
        
        $response = array();
        $responseCode = null;
            
        $user = $this->authLogic->authenicateUser(
            $data['email'], 
            $data['password'], 
            (isset($data['remember'])? $data['remember']: false)
        );

        if ($user instanceof User) {
            $response['data'] = $user->toArray();
            $responseCode = 200;
        }
        
        return $this->sendResponse($response, $responseCode);
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
            $response = true;
            $responseCode = 200;
        }
        
        return $this->sendResponse($response, $responseCode);
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
            $credential = Input::get("data");
            if (!array_key_exists("group",$credential)) {
                if (!Request::is("admin/*")) {
                    $credential['group'] = 'user';
                }
            }
            $user = $this->authLogic->registerUser($credential);
            $response = array ("data" => $user->toArray());
            $responseCode = 200;
            
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
        
        return $this->sendResponse($response, $responseCode);
    }
    

}