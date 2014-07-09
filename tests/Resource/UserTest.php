<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class UserTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test getting user data
     */
    public function testGettingData()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/users/me', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 4,
                            'name' => 'Beep',
                            'customer' => 'Beepsend AB',
                            'api_token' => 'abc123'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $user = $client->user->data();
        
        $this->assertInternalType('array', $user);
        $this->assertEquals(4, $user['id']);
        $this->assertEquals('Beep', $user['name']);
        $this->assertEquals('Beepsend AB', $user['customer']);
        $this->assertEquals('abc123', $user['api_token']);
    }
    
}