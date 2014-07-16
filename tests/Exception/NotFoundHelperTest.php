<?php

use Beepsend\Exception\NotFoundHelper;

class NotFoundHelperTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Test throwing NotFoundHelper exception
     * @expectedException Beepsend\Exception\NotFoundHelper
     * @expectedExceptionMessage Helper is not found.
     * @expectedExceptionCode 20
     */
    public function testNotFoundHelperException()
    {
        throw new NotFoundHelper('Helper is not found.', 20);
    }
    
}

