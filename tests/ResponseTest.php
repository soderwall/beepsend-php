<?php

use Beepsend\Response;

class ResponseTest extends PHPUnit_Framework_TestCase 
{
    
    /**
     * Test setting file name for downloading
     */
    public function testSettingFileName()
    {
        $response = new Response(
                json_encode(array(
                    'msg' => 'Testing Beepsend API'
                )), 
                array(
                    'http_code' => 200,
                    'Content-Type' => 'application/json'
                )
            );
        
        $this->assertInstanceOf('Beepsend\Response', $response->setFileName('FileName.csv'));
    }
    
    /**
     * Test getting array of response from raw response
     */
    public function testGettingResponse()
    {
        $response = new Response(
                json_encode(array(
                    'msg' => 'Testing Beepsend API'
                )), 
                array(
                    'http_code' => 200,
                    'Content-Type' => 'application/json'
                )
            );
        
        $value = $response->get();
        $this->assertEquals('Testing Beepsend API', $value['msg']);
    }
    
}