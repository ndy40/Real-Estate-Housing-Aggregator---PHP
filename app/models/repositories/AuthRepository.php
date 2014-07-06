<?php
namespace models\repositories;

use models\interfaces\RepositoryInterface;
use Cartalyst\Sentry\Facades\Laravel\Sentry;

/**
 * Description of AuthRepository
 *
 * @author ndy40
 */
class AuthRepository implements RepositoryInterface
{
    public function delete($id)
    {
        $user = Sentry::findUserById($id);
        $user->delete();
    }

    public function fetch($id)
    {
        return Sentry::findUserById($id);
    }

    public function searchUser($options)
    {
        return Sentry::findUserByCredentials($options);
    }

    public function fetchAllUsers()
    {
        return Sentry::findAllUsers();
    }

    public function fetchUsersInGroup($grounName)
    {
        return Sentry::findAllUsersInGroup($grounName);
    }

    public function save($entity)
    {
        return $entity->save();
    }

    public function updateUser($id, $properties)
    {
        $user = Sentry::findUserById($id);
        $user->setAttributes($properties);
        if (array_key_exists('groups', $properties)) {
            foreach ($properties['groups'] as $group) {
                $group = Sentry::findGroupByName($properties['group']);
                if (!$user->inGroup($group)) {
                    $user->addGroup($group);
                }
            }
        }
        $user->save();

        return $user;
    }

    public function authenticateUser($credentials, $remember = false)
    {
        return  Sentry::authenticate($credentials, $remember);
    }

    public function getLoggedInUser()
    {
        return Sentry::getUser();
    }

    public function isLoggedIn()
    {
        return Sentry::check();
    }

    /**
     * Convenient method for logging out a current user.
     * @return boolean Whether the logout was successful or not.
     */
    public function logOut()
    {
        return Sentry::logout();
    }

    public function saveGroup($name, $permissions = array())
    {
        return Sentry::createGroup(array(
            'name' => $name,
            'permissions' => $permissions,
        ));
    }

    public function updateGroup($id, $permissions)
    {
        $group = Sentry::findGroupById($id);
        $group->permissions = $permissions;
        $group->save();
    }

    public function update($entity)
    {
    }

    public function findGroupById($id)
    {
        return Sentry::findGroupById($id);
    }

    public function findGroupByName($name)
    {
        return Sentry::findGroupByName($name);
    }

    /**
     * Register a user and set activate flag.
     *
     * @param mixed[] $credentials
     * @param boolean $activate
     */
    public function registerUser($credentials, $activate)
    {
        return Sentry::register($credentials, $activate);
    }


    public function loginUser($user)
    {
        return Sentry::login($user, false);
    }

    public function findUserByLogin($email)
    {
        return Sentry::findUserByLogin($email);
    }

    public function findUserByResetCode($code)
    {
        return Sentry::findUserByResetPasswordCode($code);
    }
}
