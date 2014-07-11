<?php

use Beepsend\Exception\RateLimit;

class RateLimitTest extends PHPUnit_Framework_TestCase 
{
    
    /**
     * Test throwing RateLimit exception
     * @expectedException Beepsend\Exception\RateLimit
     * @expectedExceptionMessage To many requests.
     * @expectedExceptionCode 20
     */
    public function testRateLimitException()
    {
        throw new RateLimit('To many requests.', 20);
    }
    
}

