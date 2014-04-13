<?php
namespace models\datalogic;

use models\interfaces\DataLogic;
use \Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use models\exceptions\UserCreationException;
use Illuminate\Support\Facades\Config;
use Cartalyst\Sentry\Hashing\BcryptHasher;
/**
 * Description of AuthenticationLogic
 *
 * @author ndy40
 */
class AuthenticationLogic implements DataLogic 
{
    /**
     * A respository instance.
     * @var AuthRespository 
     */
    protected $authRepository;
    
    protected $userRules = array(
        'group'     => 'required|alpha_num',
        'password'  => 'required|alpha_num',
        'email'     => 'required|email',
        'firstName' => 'required|alpha|min:3',
        'lastName'  => 'required|alpha|min:3',
        'activated' => 'in:1,0',
    );

    public function __construct() {
        $this->authRepository = App::make('AuthRepository');
    }
    
    protected function getErrorMessages ($messages) {
        $errorMessage = '';
        foreach($messages as $message) {
                $errorMessage  .= $message . PHP_EOL;
        }
        
        return $errorMessage;
    }
    
    public function authenicateUser($username, $password, $remember = false) 
    {
        $hasher = new BcryptHasher();
        
        $credentials = array(
            "email" => $username,
            "password" => $password,
        );
        $user = $this->authRepository->authenticateUser($credentials, $remember);
        
        return $user;
    }
    
    /**
     * Method for creating new users. 
     * 
     * @param mixed[] $credentials
     * @return \models\enitities\User
     * @throws UserCreationException
     */
    public function createUser($credentials) 
    {
        $validator = Validator::make($credentials, $this->userRules);
        $errorMessage = '';
        
        if($validator->fails()) {
            $errorMessage = $this->getErrorMessages($validator->messages()->all());
            throw new UserCreationException($errorMessage, 400);
        }

        $credentials = array('activated'  => Config::get('auth.auto_activite_user'));
        
        foreach ($credentials as $key => $value) {
            if (snake_case($key) == 'password') {
                $value = Crypt::encrypt($value);
            }
            unset($credentials[$key]);
            $credentials[snake_case($key)] = $value; 
        }
        $user = $this->authRepository->save($credentials);
        
        if (array_key_exists("group", $credentials)) {
            $this->addUserToGroup($user->id, $credentials['group']);
        }
        if (!($user instanceof User)) {
            $errorMessage = "An error occurred while creating user.";
            throw new UserCreationException($errorMessage, 400);
        }

        return $user;
    }
    
    public function findUser($option) 
    {
        //if value passed is integer then fetch by user Id.
        if (is_int($option)) {
            $user = $this->authRepository->fetch($option);
        } elseif (is_array($option)) {
            $user = $this->authRepository->searchUser($option);
        }
        
        return $user;
    }
    
    public function addUserToGroup($id, $group) {
        $user = $this->authRepository->fetch($id);
        $group = $this->authRepository->fetchGroupByName($group);
        $user->addGroup($group);
        return $user;
    }
    
    /**
     * Checks if a user is logged in. 
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->authRepository->isLoggedIn();
    }
    
    /**
     * Logout current user.
     */
    public function logOut(){
        $this->authRepository->logOut();
        if (!$this->isLoggedIn()) {
            return true;
        }
        return false;
    }
    
    /**
     * Get currently logged in user.
     * @return User
     */
    public function getCurrentUser()
    {
        return $this->authRepository->getLoggedInUser();
    }
    /**
     * 
     * @param mixed[] $credentials Registration parameters
     * @param boolean $activate
     * @throws UserCreationException
     */
    public function registerUser($credentials, $activate = false)
    {
        $validator = Validator::make($credentials, $this->userRules);
        if ($validator->fails()) {
            $errorMessages = $this->getErrorMessages($validator->messages()->all());
            throw new UserCreationException ($errorMessages);
        }
        $credential = array();
        foreach ($credentials as $key => $value) {
            $credential[snake_case($key)] = $value; 
        }
        
        if (array_key_exists("group", $credential)) {
            $group = $this->authRepository->findGroupByName($credential['group']);
            unset($credential['group']);
        }

        $autoActivate = Config::get('auth.auto_activate_user');
        $user = $this->authRepository->registerUser($credential, $autoActivate);
        if (!($user instanceof \models\entities\User)) {
            throw new UserCreationException ("Error registering user.");
        } else {
            $user = $this->authRepository->fetch($user->id);
            $user->addGroup($group);
        }
        
        return $user;
    }
    
}
