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
