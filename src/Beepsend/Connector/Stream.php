<?php

namespace Beepsend\Connector;

use Beepsend\Connector\ConnectorInterface;

/**
 * Beepsend stream connector
 * @package Beepsend
 */
class Stream implements ConnectorInterface 
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
     * Response headers from the stream wrapper
     * @var array
     */
    private $responseHeaders;
    
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
        $options = array(
            'http' => array(
                'method' => $method,
                'timeout' => 60,
                'ignore_errors' => true
            )
        );
        
        if ($method == 'GET') {
            $action = $this->appendParamsToUrl($action, $params);
        }
        
        $this->addHeader('Authorization', 'Token ' . $this->token);
        $this->addHeader('User-agent', $this->userAgent);
        
        if ($method !== 'GET') {
            $this->addHeader('Content-Type', 'application/json');
            $this->addHeader('Content-Length', strlen(json_encode($params)));
            $options['http']['content'] = json_encode($params);
        }
        
        $options['http']['header'] = $this->prepareHeaders();
        
        $context = $this->createStreamContext($options);
        $response = $this->getContent($action, $context);
        $info = $this->formatHeadersToArray();
        
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
        $options = array(
            'http' => array(
                'method' => 'POST',
                'timeout' => 60,
                'ignore_errors' => true
            )
        );
        
        $options['http']['content'] = count($params) > 0 ? $params : $rawData;
        
        $this->addHeader('Authorization', 'Token ' . $this->token);
        $this->addHeader('User-agent', $this->userAgent);
        $this->addHeader('Content-type', 'application/x-www-form-urlencoded');
        
        $options['http']['header'] = $this->prepareHeaders();
        
        $context = $this->createStreamContext($options);
        $response = $this->getContent($action, $context);
        $info = $this->formatHeadersToArray();
        
        return array('info' => $info, 'response' => $response);
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

        return implode("\r\n", $header);
    }
    
    /**
     * Convert headers from response to standardized array
     * @return array
     */
    private function formatHeadersToArray()
    {
        $headers = array();
        foreach ($this->responseHeaders as $line) {
            if (strpos($line, ':') === false) {
                $headers['http_code'] = $this->exportHttpCode($line);
            } else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }

        return $headers;
    }
    
    /**
     * Export http code from header
     * @param string $header
     * @return int
     */
    private function exportHttpCode($header)
    {
        preg_match('|HTTP/\d\.\d\s+(\d+)\s+.*|', $header, $match);
        return (int)$match[1];
    }

    /**
     * Create stream context
     * @param array $options Array of options
     * @return Object
     */
    private function createStreamContext(array $options)
    {
        return stream_context_create($options);
    }
    
    /**
     * Get content
     * @param string $action Action we are calling
     * @param Object $context stream_context_create
     * @return string
     */
    private function getContent($action, $context)
    {
        $rawResponse = file_get_contents($this->baseApiUrl . '/' . $this->version . $action, false, $context);
        $this->responseHeaders = $http_response_header;
        return $rawResponse;
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