<?php
/**
 * Created by PhpStorm.
 * User: ndy40
 * Date: 06/06/2014
 * Time: 04:50
 */
namespace controllers\service;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use BaseController;

class AuthService extends BaseController
{
    protected $authLogic;

    public function __construct()
    {
        $this->authLogic = App::make("AuthLogic");
    }

    /**
     * Get the currently Logged In User.
     * @return JSON
     */
    public function getCurrentUser()
    {
        if ($this->authLogic->isLoggedIn()) {
            $user = $this->authLogic->getCurrentUser();
            return Response::json(array("data" => $user->toArray()));
        }
        return Response::json(false);
    }
}
