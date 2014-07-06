<?php
namespace controllers\auth;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use models\entities\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Security\Acl\Exception\Exception;

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
    ) {
        return Response::json($data, $code, $header);
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
            throw new \Cartalyst\Sentry\Users\UserNotFoundException(
                "User not logged in."
            );
        }

        return $this->sendResponse($response, $responseCode);
    }


    public function postRegister()
    {
        try {
            $credential = Input::get();
            if (!array_key_exists("group", $credential)) {
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
        if (Request::isMethod("get")) {
            return View::make("clientforgot");
        } else {
            $email = Input::get("email");
            $user = $this->authLogic->findUserByLogin($email);
            if ($user) {
                $resetCode = $user->getResetPasswordCode();
                $data = array(
                    "code"      => $resetCode,
                    "user"      => $user,
                    "baseUrl"   => Config::get("app.url"),
                );
                Mail::send(
                    array("emails.passwordreset", "emails.passwordresettext"),
                    $data,
                    function ($message) use ($user) {
                        $message->to($user->email, $user->getFullName())
                            ->subject("Password reset");
                    }
                );
                return View::make("clientforgot")->with("message", "Password reset email sent.");

            } else {
                return View::make("clientforgot")->with("message", "Email not found.");
            }
        }

    }

    /**
     * method for reseting password
     */
    public function resetPassword($code = "")
    {
        if (Request::isMethod("get")) {
            return View::make("clientresetpassword")->with("code", $code);
        } else {
            $validator = Validator::make(Input::all(), array(
                "password" => "required|min:8|confirmed"));
            $authcode = Input::get("code");
            $password = Input::get("password");
            if ($validator->fails()) {
                return Redirect::route("resetpassword")->with("code", $authcode)->withErrors($validator);
            }

            $user = $this->authLogic->findUserByResetCode($authcode);
            if ($user->attemptResetPassword($authcode, $password)) {
                return View::make("clientresetpassword")->with("message", "Password has been updated.");
            } else {
                return Redirect::route("resetpassword")
                    ->with("code", $authcode)
                    ->with("message", "Failed to update password. Try again.");
            }

        }
    }
}
