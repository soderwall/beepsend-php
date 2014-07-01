<?php

use Beepsend\Exception\InvalidRequest;

class InvalidRequestTest extends PHPUnit_Framework_TestCase 
{
    
    /**
     * Test throwing InvalidRequest exception
     * @expectedException Beepsend\Exception\InvalidRequest
     * @expectedExceptionMessage Request you are trying is invalid.
     * @expectedExceptionCode 20
     */
    public function testInvalidRequestException()
    {
        throw new InvalidRequest('Request you are trying is invalid.', 20); // InternalErrors expects raw response from API
    }
    
}

