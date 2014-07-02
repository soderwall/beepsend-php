<?php

namespace Beepsend\Connector;

use Beepsend\ConnectorInterface;
use Beepsend\Response;
use Beepsend\Exception\InvalidToken;
use Beepsend\Exception\InvalidRequest;
use Beepsend\Exception\NotFound;
use Beepsend\Exception\InternalError;

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
     * @return Beepsend\Response
     * @throws NotFound
     * @throws InvalidRequest
     */
    public function execute($action, $method, $params)
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
        
        return $this->response($info, $response);
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
        
        return $this->response($info, $response);
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
        switch ((integer)$info['http_code']) {
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