<?php

namespace Beepsend;

use Beepsend\Response;
use Beepsend\Exception\InvalidToken;
use Beepsend\Exception\InvalidRequest;
use Beepsend\Exception\NotFound;

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
     * User or Connection token to authorize on Beepsend API
     * @var string
     */
    private $token;
    
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
        
        $this->token = $token;
    }
    
    /**
     * Make some request over Beepsend API, supporting GET, POST, PUT and DELETE methods
     * @param string $action Action that we are calling
     * @param string $method Request method
     * @param array $params Array of additional parameters
     * @return Beepsend\Response
     * @throws NotFound
     * @throws InvalidRequest
     */
    public function call($action, $method = 'GET', $params = array())
    {
        if ($method == 'GET') {
            $url = $this->appendParamsToUrl($url, $params);
        }
        
        $ch = curl_init($this->baseApiUrl . '/' . $this->version . $action);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        
        /* Set authorization token */
        $headers = array('Authorization: Token ' . $this->token);
        
        if ($method !== 'GET') {

            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen(json_encode($params));
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method == 'PUT' || $method == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        
        switch ((integer)$info['http_code']) {
            case 200:
            case 201:
            case 204:
                return new Response($response, $info);
            case 401:
                throw new InvalidToken('A valid user API-token is required.');
            case 403:
                throw new InvalidRequest($response);
            case 404:
                throw new NotFound('Call you are looking for not existing, this means that something is wrong with API or this SDK.');
        }
        
    }
    
    /**
     * Upload file to Beepsend API, currently supporting only POST method
     * @param string $action Action that we are calling
     * @param array $params Array of additional parameters
     * @param string $rawData String using this for posting file content
     * @return Beepsend\Response
     * @throws NotFound
     * @throws InvalidRequest
     * @throws InvalidToken
     */
    public function upload($action, $params = array(), $rawData = '')
    {
        $ch = curl_init($this->baseApiUrl . '/' . $this->version . $action);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
            'Authorization: Token ' . $this->token,
            'Content-Type: application/x-www-form-urlencoded'
        ));
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, count($params) > 0 ? $params : $rawData);
        
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        
        switch ((integer)$info['http_code']) {
            case 200:
            case 201:
            case 204:
                return new Response($response, $info);
            case 401:
                throw new InvalidToken('A valid user API-token is required.');
            case 403:
                throw new InvalidRequest($response);
            case 404:
                throw new NotFound('Call you are looking for not existing, this means that something is wrong with API or this SDK.');
        }
    }
    
    /**
     * Append parameters to url, using for GET request.
     * @param string $url Url that we will call
     * @param array $parameters Array of parameters
     * @return string
     */
    private function appendParamsToUrl($url, $parameters = array())
    {
        if (empty($parameters)) {
            return $url;
        }
        
        return $url . '&' . http_build_query($parameters);
    }
    
}