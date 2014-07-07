<?php

use Beepsend\Exception\NotFoundResource;

class NotFoundResourceTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Test throwing NotFoundResource exception
     * @expectedException Beepsend\Exception\NotFoundResource
     * @expectedExceptionMessage Resource is not found.
     * @expectedExceptionCode 20
     */
    public function testNotFoundResourceException()
    {
        throw new NotFoundResource('Resource is not found.', 20);
    }
    
}

