<?php

namespace Beepsend\Connector;

use Beepsend\Connector\ConnectorInterface;

/**
 * Beepsend curl connector
 * @package Beepsend
 */
class Curl implements ConnectorInterface 
{
    
    /**
     * Beepsend API version
     * @var int
     */
    private $version;
    
    /**
     * Beepsend PHP library user agent
     * @var string
     */
    private $userAgent;
    
    /**
     * Beepsend API url
     * @var string
     */
    private $baseApiUrl;
    
    /**
     * User or Connection token to authorize on Beepsend API
     * @var string
     */
    private $token;
    
    /**
     * {@inheritdoc}
     */
    public function __construct($version, $userAgent, $baseApiUrl, $token)
    {
        $this->version = $version;
        $this->userAgent = $userAgent;
        $this->baseApiUrl = $baseApiUrl;
        $this->token = $token;
    }

    /**
     * Make some request over Beepsend API, supporting GET, POST, PUT and DELETE methods
     * @param string $action Action that we are calling
     * @param string $method Request method
     * @param array $params Array of additional parameters
     * @return array
     */
    public function call($action, $method, $params)
    {
        if ($method == 'GET') {
            $action = $this->appendParamsToUrl($action, $params);
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
        
        return array('info' => $info, 'response' => $response);
    }
    
    /**
     * Upload file to Beepsend API, currently supporting only POST method
     * @param string $action Action that we are calling
     * @param array $params Array of additional parameters
     * @param string $rawData String using this for posting file content
     * @return Beepsend\Response
     */
    public function upload($action, $params, $rawData)
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
        
        return array('info' => $info, 'response' => $response);
    }
    
    /**
     * Append parameters to url, using for GET request.
     * @param string $url Url that we will call
     * @param array $params Array of parameters
     * @return string
     */
    private function appendParamsToUrl($url, $params = array())
    {
        if (empty($params)) {
            return $url;
        }
        
        return $url . '?' . http_build_query($params);
    }
}