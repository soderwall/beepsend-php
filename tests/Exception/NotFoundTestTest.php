<?php

use Beepsend\Exception\NotFound;

class NotFoundTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Test throwing NotFound exception
     * @expectedException Beepsend\Exception\NotFound
     * @expectedExceptionMessage Call you are trying is not found.
     * @expectedExceptionCode 20
     */
    public function testNotFoundException()
    {
        throw new NotFound('Call you are trying is not found.', 20);
    }
    
}

