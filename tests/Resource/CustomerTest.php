<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class CustomerTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test getting customer data
     */
    public function testGettingData()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/customer/', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 1,
                            'name' => 'Beepsend AB'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $customer = $client->customer->data();

        $this->assertInternalType('array', $customer);
        $this->assertEquals(1, $customer['id']);
        $this->assertEquals('Beepsend AB', $customer['name']);
    }
    
    public function tearDown()
    {
        \Mockery::close();
    }
    
}
