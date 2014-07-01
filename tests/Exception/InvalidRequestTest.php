<?php

use Beepsend\Exception\InvalidRequest;

class InvalidRequestTest extends PHPUnit_Framework_TestCase 
{
    private $response = array('errors' => array('Request you are trying is invalid.'));
    
    /**
     * Test throwing InvalidRequest exception
     * @expectedException Beepsend\Exception\InvalidRequest
     * @expectedExceptionMessage Request you are trying is invalid.
     * @expectedExceptionCode 20
     */
    public function testInvalidRequestException()
    {
        throw new InvalidRequest(json_encode($this->response), 20); // InternalErrors expects raw response from API
    }
    
}

