<?php

use Beepsend\Exception\InternalError;

class InternalErrorTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Test throwing InternalError exception
     * @expectedException Beepsend\Exception\InternalError
     * @expectedExceptionMessage There was some unexpected error.
     * @expectedExceptionCode 20
     */
    public function testInternalErrorException()
    {
        throw new InternalError('There was some unexpected error.', 20);
    }
    
}

