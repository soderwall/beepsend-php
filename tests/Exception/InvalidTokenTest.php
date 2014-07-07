<?php

use Beepsend\Exception\InvalidToken;

class InvalidTokenTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Test throwing InvalidToken exception
     * @expectedException Beepsend\Exception\InvalidToken
     * @expectedExceptionMessage Please set valid token.
     * @expectedExceptionCode 20
     */
    public function testInvalidTokenException()
    {
        throw new InvalidToken('Please set valid token.', 20);
    }
    
}

