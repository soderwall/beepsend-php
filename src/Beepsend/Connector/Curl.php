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
     * Array of request headers
     * @var array
     */
    private $headers;
    
    /**
     * Curl holder
     * @var Object 
     */
    private $curl;
    
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
        } else {
            $this->addHeader('Content-Type', 'application/json');
            $this->addHeader('Content-Length', strlen(json_encode($params)));
        }
        
        $this->makeConnection($action, $method, json_encode($params));
        
        $response = $this->makeRequest();
        $info = $this->getCurlInfo();
        
        $this->closeConnection();
        
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
        $this->addHeader('Content-Type', 'application/x-www-form-urlencoded');
        $this->makeConnection($action, 'POST', count($params) > 0 ? $params : $rawData);
        
        $response = $this->makeRequest();
        $info = $this->getCurlInfo();
        
        $this->closeConnection();
        
        return array('info' => $info, 'response' => $response);
    }
    
    /**
     * Make connection to Beepsend API
     * @param string $action Action that we are calling
     * @param string $method Request method
     * @param array $params Array of additional parameters
     */
    private function makeConnection($action, $method, $params)
    {
        $options = array(
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true
        );
        
        if ($method !== 'GET') {
            $options[CURLOPT_POSTFIELDS] = $params;
        }
        
        if ($method == 'PUT' || $method == 'DELETE') {
            $options[CURLOPT_CUSTOMREQUEST] = $method;
        }
        
        $this->addHeader('Authorization', 'Token ' . $this->token);
        $options[CURLOPT_HTTPHEADER] = $this->prepareHeaders();
        
        $this->curl = curl_init($this->baseApiUrl . '/' . $this->version . $action);
        curl_setopt_array($this->curl, $options);
    }
    
    /**
     * Make request to server
     * @return string
     */
    private function makeRequest()
    {
        return curl_exec($this->curl);
    }
    
    /**
     * Get curl info
     * @return array
     */
    public function getCurlInfo()
    {
        return curl_getinfo($this->curl);
    }
    
    /**
     * Close curl conenction
     */
    private function closeConnection()
    {
        curl_close($this->curl);
    }
    
    /**
     * Add request header
     * @param string $name Name of header
     * @param string $value Valud of header
     */
    private function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }
    
    /**
     * Return headers for request
     * @return string
     */
    private function prepareHeaders()
    {
        $header = array();
        foreach($this->headers as $n => $v) {
          $header[] = $n . ': ' . $v;
        }

        return $header;
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