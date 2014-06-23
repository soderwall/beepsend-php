<?php

namespace Beepsend;

class Response {
    
    /**
     * Response content type
     * @var string
     */
    private $contentType;
    
    /**
     * Response from Beepsend API
     * @var array
     */
    private $response;
    
    /**
     * Raw Response from Beepsend API
     * @var array
     */
    private $rawResponse;
    
    /**
     * Init Beepsend response
     * @param string $rawResponse Json object from Beepsend API
     * @param array $info Curl info
     */
    public function __construct($rawResponse, $info)
    {
        $this->contentType = $info['content_type'];
        $this->rawResponse = $rawResponse;
        
        if ($this->contentType == 'application/json') {
            $this->response = $this->parseResponse($rawResponse);
        }
    }
    
    /**
     * Return response data
     * @return Array
     */
    public function get()
    {
        return $this->response;
    }
    
    /**
     * Download CSV file from response
     */
    public function getCsv($fileName)
    {
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo $this->rawResponse;
    }
    
    /**
     * Parse raw response from Beepsend API
     * @param string $rawResponse Json object
     * @return array
     */
    private function parseResponse($rawResponse)
    {
        return json_decode($rawResponse, true);
    }
    
}
