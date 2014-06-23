<?php

namespace Beepsend\Exception;

/**
 * Throw when we can't find resource to load
 */
final class InvalidRequest extends \Exception
{
    /**
     * @internal
     * @param string $rawResponse Raw response from 
     * @param integer $code
     * @param Object $previous
     */
    public function __construct($rawResponse, $code = 0, $previous = null)
    {
        $message = $this->parseResponse($rawResponse);
        
        parent::__construct($message, $code, $previous);
    }
    
    /**
     * Parse raw response
     * @param string $rawResponse 
     * @return string
     */
    private function parseResponse($rawResponse)
    {
        $response = json_decode($rawResponse, true);
        $errors = $response[0]['errors'];
        
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
            $response .= ' Code: ' . $error['code'] . ' ' . $error['description'];
        }
        
        return $response;
    }
    
}
