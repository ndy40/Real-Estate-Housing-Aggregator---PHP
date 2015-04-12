<?php
use models\repositories\AuthRepository;
use Cartalyst\Sentry\Groups\Eloquent\Group;
use Cartalyst\Sentry\Users\Eloquent\User;

/**
 * Description of AuthRespositoryTest
 *
 * @author ndy40
 */
class AuthRespositoryTest extends TestCase
{
    
    public function userdata(){
        return array(
            array('test1', 'password', 'test', 'test', 'testgroup'),
            array('test2', 'password', 'test2', 'test2', 'testgroup')
        );
    }
    
    public function testSaveGroup()
    {
        $repo = new AuthRepository;
        $group = $repo->saveGroup("testgroup");
        $this->assertInstanceOf('Cartalyst\Sentry\Groups\Eloquent\Group', $group);
        $this->assertSame('testgroup', $group->name);
        $group->delete();
    }
    
    /**
     * @dataProvider userdata
     * @param type $email
     * @param type $password
     */
    public function testSaveUser($email, $password, $first_name, $last_name, $group)
    {
        $repo = new AuthRepository;
        $group = $repo->saveGroup($group);
        $user = array(
            'email' => $email , 
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name
        );
        $newUser = Sentry::createUser($user);
        $newUser->addGroup($group);
        $this->assertInstanceOf('\models\entities\User', $newUser);
        $newUser->delete();
        $group->delete();
    }
    
}
