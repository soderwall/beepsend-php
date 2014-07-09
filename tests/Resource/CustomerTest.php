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
                    ->with('https://api.beepsend.com/2/customer/', 'GET', array())
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
        
        $client = new Client('BeepsendToken', $connector);
        $customer = $client->customer->data();
        
        $this->assertEquals(1, $customer['id']);
        $this->assertEquals('Beepsend AB', $customer['name']);
    }
    
}
