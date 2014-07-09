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
                    ->with(BASE_API_URL . '/' . API_VERSION . '/connections/', 'GET', array())
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
    
    /**
     * Test getting data for single connection
     */
    public function testGettingData()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/connections/me', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 1,
                            'system_id' => 'beepsend',
                            'label' => 'beepsend-connection',
                            'api_token' => 'abc123',
                            'customer' => 'Beepsend AB'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $connection = $client->connection->data('me');
        
        $this->assertInternalType('array', $connection);
        $this->assertEquals(1, $connection['id']);
        $this->assertEquals('beepsend', $connection['system_id']);
        $this->assertEquals('beepsend-connection', $connection['label']);
        $this->assertEquals('abc123', $connection['api_token']);
        $this->assertEquals('Beepsend AB', $connection['customer']);
    }
    
    /**
     * Test updating connection data
     */
    public function testUpdating()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/connections/me', 'PUT', array(
                        'callbacks' => array(
                            'dlr' => 'https://beepsend.com/securedlr'
                        ),
                        'system_id' => 'crossover',
                        'label' => 'Pawnee-connection',
                        'password' => 'cake',
                        'description' => 'Cool. Cool, cool, cool'
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 1,
                            'system_id' => 'crossover',
                            'label' => 'Pawnee-connection',
                            'api_token' => 'abc123',
                            'customer' => 'Beepsend AB',
                            'description' => 'Cool. Cool, cool, cool',
                            'callbacks' => array(
                                'dlr' => 'https://beepsend.com/securedlr',
                                'mo' => 'https://beepsend.com/mocallback',
                                'method' => 'PUT'
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $connection = $client->connection->update('me', array(
            'callbacks' => array(
                'dlr' => 'https://beepsend.com/securedlr'
            ),
            'system_id' => 'crossover',
            'label' => 'Pawnee-connection',
            'password' => 'cake',
            'description' => 'Cool. Cool, cool, cool'
        ));
        
        $this->assertInternalType('array', $connection);
        $this->assertInternalType('array', $connection['callbacks']);
        $this->assertEquals(1, $connection['id']);
        $this->assertEquals('crossover', $connection['system_id']);
        $this->assertEquals('Pawnee-connection', $connection['label']);
        $this->assertEquals('abc123', $connection['api_token']);
        $this->assertEquals('Beepsend AB', $connection['customer']);
        $this->assertEquals('Cool. Cool, cool, cool', $connection['description']);
        $this->assertEquals('https://beepsend.com/securedlr', $connection['callbacks']['dlr']);
        $this->assertEquals('https://beepsend.com/mocallback', $connection['callbacks']['mo']);
        $this->assertEquals('PUT', $connection['callbacks']['method']);
    }
    
    /**
     * Test reseting token for connection
     */
    public function testResetingToken()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/connections/me/tokenreset', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'api_token' => 'abc123',
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $connection = $client->connection->resetToken('me');
        
        $this->assertInternalType('array', $connection);
        $this->assertEquals('abc123', $connection['api_token']);
    }
    
    /**
     * Test reseting password for connection
     */
    public function testResetingPassword()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/connections/me/passwordreset', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'password' => 'abc12345',
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $connection = $client->connection->resetPassword('me');
        
        $this->assertInternalType('array', $connection);
        $this->assertEquals('abc12345', $connection['password']);
    }
}