<?php

use Beepsend\Exception\CurlExtension;

class CurlExtensionTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Test throwing CurlExtension
     * @expectedException Beepsend\Exception\CurlExtension
     * @expectedExceptionMessage Curl extension is not found.
     * @expectedExceptionCode 20
     */
    public function testCurlExtensionException()
    {
        throw new CurlExtension('Curl extension is not found.', 20);
    }
    
}

