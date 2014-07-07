<?php

namespace Beepsend;

use Beepsend\Response;
use Beepsend\Exception\InvalidToken;
use Beepsend\Exception\InvalidRequest;
use Beepsend\Exception\NotFound;
use Beepsend\Exception\InternalError;

/**
 * Beepsend request
 * @package Beepsend
 */
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
            $connector = new Connector\Stream(
                    $this->version, 
                    $this->userAgent, 
                    $this->baseApiUrl, 
                    $token);
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
    public function execute($action, $method = 'GET', $params = array())
    {
        $rawResponse = $this->connector->call($action, $method, $params);
        return $this->response($rawResponse['info'], $rawResponse['response']);
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
        $rawResponse = $this->connector->upload($action, $params, $rawData);
        return $this->response($rawResponse['info'], $rawResponse['response']);
    }
    
    /**
     * Return valid response based on respond from Beepsend API url
     * @param array $info Curl info
     * @param string $response Raw response from Beepsend API
     * @return Beepsend\Response
     * @throws InvalidToken
     * @throws InvalidRequest
     * @throws NotFound
     * @throws InternalError
     */
    private function response($info, $response)
    {
        switch ($info['http_code']) {
            case 200:
            case 201:
            case 204:
                return new Response($response, $info);
            case 401:
                throw new InvalidToken('A valid user API-token is required.');
            case 403:
                throw new InvalidRequest($this->parseError($response));
            case 404:
                throw new NotFound('Call you are looking for not existing, this means that something is wrong with API or this SDK.');
            case 500:
                throw new InternalError('Something is wrong with API, please try again later.');
        }
    }
    
    /**
     * Parse raw response
     * @param string $rawResponse 
     * @return string
     */
    private function parseError($rawResponse)
    {
        $errors = array();
        $response = json_decode($rawResponse, true);
        
        if (isset($response['errors'])) {
            $errors = $response['errors'];
        } else if ($response[0]['errors']) {
            $errors = $response[0]['errors'];
        }
        
        return $this->joinErrros($errors);
    }
    
    /**
     * Create one string from array of errors
     * @param array $errors
     * @return string
     */
    private function joinErrros($errors)
    {
        $response = null;
        
        foreach ($errors as $error) {
            $code = isset($error['code']) ? 'Code: ' . $error['code'] . ' ' : null;
            $description = isset($error['description']) ? $error['description'] : $error;
            $response .= $code . $description;
        }
        
        return $response;
    }
}