<?php

namespace Beepsend;

use Beepsend\Request;
use Beepsend\Exception\NotFoundResource;

/**
 * Beepsend client
 * @package Beepsend
 */
class Client 
{
    
    /**
     * Version of Beepsend PHP helper
     */
    public $version = '0.1';
    
    /**
     * Beepsend request handler
     * @var Beepsend\Request;
     */
    private $request;
    
    /**
     * Init beepsend client
     * @param string $token User or Connection token to work with
     * @param object $connector Specify manually connector that you want to use
     */
    public function __construct($token, $connector = null)
    {
        if (empty($token)) {
            /* User didn't set token */
            throw new InvalidToken('Please set valid token!');
        }
        
        if (!$connector) {
            /* Detect connector that we will use */
            if (extension_loaded('curl')) {
                $connector = new Connector\Curl();
            } else {
                $connector = new Connector\Stream();
            }
        }
        
        $this->request = new Request($token, $connector);
    }
    
    /**
     * Load resource that user will work with
     * @param string $resource Name of resource that we wan't to load
     * @return Object
     */
    public function __get($resource)
    {
        return $this->loadResource($resource);
    }
    
    /**
     * Try to load resource
     * @param string $resource Resource name
     * @return Object Resource
     * @throws NotFoundResource
     */
    private function loadResource($resource)
    {
        $resourceName = ucfirst($resource);
        if (file_exists(__DIR__ . '/Resource/' . $resourceName . '.php')) {
            $loadResource = "Beepsend\Resource\\{$resourceName}";
            return new $loadResource($this->request);
        }
        
        throw new NotFoundResource('Resource ' . $resourceName . ' can not be found!');
    }
}