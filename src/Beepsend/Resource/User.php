<?php

namespace Beepsend\Resource;

use Beepsend\Request;
use Beepsend\Resource\ResourceInterface;

class User implements ResourceInterface {
    
    /**
     * Beepsend request handler
     * @var Beepsend\Request;
     */
    private $request;
    
    /**
     * User that we are using for working with.
     * Currently API only support working with logged in user.
     * @var string
     */
    private $user = 'me';
    
    /**
     * Action to call
     * @var array
     */
    private $actions = array(
        'users' => '/users/',
        'email' => '/email',
        'password' => '/password'
    );
    
    /**
     * Init customer resource
     * @param Beepsend\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Get user details
     * @return array
     */
    public function data()
    {
        $response = $this->request->call($this->actions['users'] . $this->user, 'GET');
        return $response->get();
    }
    
    /**
     * Update current user.
     * @param array $options Array of options that we wan't to update. 
     * @link http://api.beepsend.com/docs.html#user-update List if all options we can update
     * @return array
     */
    public function update($options)
    {
        $response = $this->request->call($this->actions['users'] . $this->user, 'PUT', $options);
        return $response->get();
    }
    
    /**
     * Update user email
     * Password is needed for extra security
     * @param string $email Your new email address
     * @param string $password Current password
     * @return array
     */
    public function updateEmail($email, $password)
    {
        $data = array(
            'email' => $email,
            'password' => $password
        );
        
        $response = $this->request->call($this->actions['users'] . $this->user . $this->actions['email'], 'PUT', $data);
        return $response->get();
    }
    
    /**
     * Update user password
     * @param string $newPassword New password that you want to set
     * @param string $oldPassword User current password, needed for extra security.
     * @return array
     */
    public function updatePassword($newPassword, $oldPassword)
    {
        $data = array(
            'password' => $oldPassword,
            'new_password' => $newPassword
        );
        
        $response = $this->request->call($this->actions['users'] . $this->user . $this->actions['password'], 'PUT', $data);
        return $response->get();
    }
}