<?php
namespace controllers\auth;

use BaseController;
use Illuminate\Support\Facades\App;
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
    
}
