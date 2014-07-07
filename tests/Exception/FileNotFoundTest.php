<?php

use Beepsend\Exception\FileNotFound;

class FileNotFoundTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Test throwing FleNotFound exception
     * @expectedException Beepsend\Exception\FileNotFound
     * @expectedExceptionMessage File you are looking for is not found.
     * @expectedExceptionCode 20
     */
    public function testFileNotFoundException()
    {
        throw new FileNotFound('File you are looking for is not found.', 20);
    }
    
}

