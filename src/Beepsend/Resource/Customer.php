<?php

namespace Beepsend\Resource;

use Beepsend\Request;
use Beepsend\Resource\ResourceInterface;

class Customer implements ResourceInterface {
    
    /**
     * Beepsend request handler
     * @var Beepsend\Request
     */
    private $request;
    
    /**
     * Actions to call
     * @var array
     */
    private $actions = array(
        'data' => '/customer/'
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
     * Get customer data
     * @return array
     */
    public function data()
    {
        $response = $this->request->call($this->actions['data'], 'GET');
        return $response->get();
    }
    
}