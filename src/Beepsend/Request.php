<?php

namespace Beepsend;

use Beepsend\Exception\InvalidToken;

class Request {
    
    /**
     * Beepsend API version
     * @var int
     */
    private $version = 2;
    
    /**
     * Beepsend PHP library user agent
     * @var string
     */
    private $userAgent = 'beepsend-php-sdk-v0.1';
    
    /**
     * Beepsend API url
     * @var string
     */
    private $baseApiUrl = 'https://api.beepsend.com';
    
    /**
     * Connector that we will use to communicate with API
     * @var Object
     */
    private $connector;
    
    /**
     * Set requred values for Beepsend request
     * @param string $token Token to work with
     * @throws InvalidToken
     */
    public function __construct($token)
    {
        if (empty($token)) {
            /* User didn't set token */
            throw new InvalidToken('Please set valid token!');
        }
        
        /* Detect connector that we will use */
        if (extension_loaded('curl')) {
            $connector = new Connector\Curl(
                    $this->version, 
                    $this->userAgent, 
                    $this->baseApiUrl, 
                    $token);
        } else {
            $connector = new Connector\Stream();
        }
        
        $this->connector = $connector;
    }
    
    /**
     * Make some request over Beepsend API, supporting GET, POST, PUT and DELETE methods
     * @param string $action Action that we are calling
     * @param string $method Request method
     * @param array $params Array of additional parameters
     * @return Beepsend\Response
     */
    public function call($action, $method = 'GET', $params = array())
    {
        return $this->connector->execute($action, $method, $params);
    }
    
    /**
     * Upload file to Beepsend API, currently supporting only POST method
     * @param string $action Action that we are calling
     * @param array $params Array of additional parameters
     * @param string $rawData String using this for posting file content
     * @return Beepsend\Response
     */
    public function upload($action, $params = array(), $rawData = '')
    {
        return $this->connector->upload($action, $params, $rawData);
    }
    
}