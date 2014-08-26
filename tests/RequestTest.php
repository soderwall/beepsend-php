<?php

use Beepsend\Request;
use Beepsend\Connector\Curl;

class RequestTest extends PHPUnit_Framework_TestCase 
{
    
    /**
     * Test getting Beepsend API version
     */
    public function testGettingVersion()
    {
        $request = new Request('SomeSecretToken', new Curl());
        $this->assertEquals(2, $request->getVersion());
    }
    
    /**
     * Test getting Beepsend PHP helper user agent
     */
    public function testGettingUserAgent()
    {
        $request = new Request('SomeSecretToken', new Curl());
        $this->assertEquals('beepsend-php-sdk-v1.0', $request->getUserAgent());
    }
    
    /**
     * Test getting Beepsend base API url
     */
    public function testGettingBaseApiUrl()
    {
        $request = new Request('SomeSecretToken', new Curl());
        $this->assertEquals(BASE_API_URL, $request->getBaseApiUrl());
    }
    
    /**
     * Test executing requests to Beepsend API
     */
    public function testExecute()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->withAnyArgs()
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array('msg' => 'Welcome to Beepsend!'))
                    ));
        
        $request = new Request('SomeSecretToken', $connector);
        $response = $request->execute('/');
        
        $this->assertEquals('Welcome to Beepsend!', $response['msg']);
    }
    
    public function tearDown()
    {
        \Mockery::close();
    }
    
}