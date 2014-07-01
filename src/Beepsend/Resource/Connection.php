<?php

namespace Beepsend\Resource;

use Beepsend\Request;
use Beepsend\ResourceInterface;

class Connection implements ResourceInterface {
    
    /**
     * Beepsend request handler
     * @var Beepsend\Request
     */
    private $request;
    
    /**
     * Action to call
     * @var array
     */
    private $actions = array(
        'connections' => '/connections/',
        'tokenreset' => '/tokenreset',
        'passwordreset' => '/passwordreset'
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
     * Get all connections
     * @return array
     */
    public function all()
    {
        $response = $this->request->execute($this->actions['connections'], 'GET');
        return $response->get();
    }
    
    /**
     * Get data for single connection
     * @param string $connection Connection id
     * @return array
     */
    public function data($connection = 'me')
    {
        $response = $this->request->execute($this->actions['connections'] . $connection, 'GET');
        return $response->get();
    }
    
    /**
     * Update connection data
     * @param string $connection Connection id
     * @param array $options Option that we want to update
     * @return array
     */
    public function update($connection = 'me', $options = array())
    {
        $response = $this->request->execute($this->actions['connections'] . $connection, 'PUT', $options);
        return $response->get();
    }
    
    /**
     * Reset connection token, need to use user token to perform this action
     * @param string $connection Connection id
     * @return array
     */
    public function resetToken($connection = 'me')
    {
        $response = $this->request->execute($this->actions['connections'] . $connection . $this->actions['tokenreset'], 'GET');
        return $response->get();
    }
    
    /**
     * Reset connection password, need to use user token to perform this action
     * @param string $connection Connection id
     * @return array
     */
    public function resetPassword($connection = 'me')
    {
        $response = $this->request->execute($this->actions['connections'] . $connection . $this->actions['passwordreset'], 'GET');
        return $response->get();
    }
    
}