<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class ConnectionTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test getting all conections
     */
    public function testGettingAll()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/connections/', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            array(
                                'id' => 1,
                                'system_id' => 'beepsend',
                                'label' => 'beepsend-connection',
                                'api_token' => 'abc123',
                                'customer' => 'Beepsend AB'
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $connections = $client->connection->all();
        
        $this->assertInternalType('array', $connections);
        $this->assertEquals(1, $connections[0]['id']);
        $this->assertEquals('beepsend', $connections[0]['system_id']);
        $this->assertEquals('beepsend-connection', $connections[0]['label']);
        $this->assertEquals('abc123', $connections[0]['api_token']);
        $this->assertEquals('Beepsend AB', $connections[0]['customer']);
    }
    
}