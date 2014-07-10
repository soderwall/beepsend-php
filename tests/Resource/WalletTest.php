<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class WalletTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test getting all wallets
     */
    public function testGettingAll()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/wallets/', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            array(
                                'id' => 1,
                                'balance' => 47.60858,
                                'name' => 'Beepsend wallet'
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $wallet = $client->wallet->all();
        
        $this->assertInternalType('array', $wallet);
        $this->assertEquals(1, $wallet[0]['id']);
        $this->assertEquals(47.60858, $wallet[0]['balance']);
        $this->assertEquals('Beepsend wallet', $wallet[0]['name']);
    }
    
    /**
     * Test getting details of some wallet
     */
    public function testGettingData()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/wallets/1', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 1,
                            'balance' => 47.60858,
                            'name' => 'Beepsend wallet'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $wallet = $client->wallet->data(1);
        
        $this->assertInternalType('array', $wallet);
        $this->assertEquals(1, $wallet['id']);
        $this->assertEquals(47.60858, $wallet['balance']);
        $this->assertEquals('Beepsend wallet', $wallet['name']);
    }
    
    /**
     * Test updating wallet info
     */
    public function testUpdating()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/wallets/1', 'PUT', array(
                        'name' => 'Beepsend new wallet'
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 1,
                            'balance' => 47.60858,
                            'name' => 'Beepsend new wallet'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $wallet = $client->wallet->update(1, 'Beepsend new wallet');
        
        $this->assertInternalType('array', $wallet);
        $this->assertEquals(1, $wallet['id']);
        $this->assertEquals(47.60858, $wallet['balance']);
        $this->assertEquals('Beepsend new wallet', $wallet['name']);
    }
    
    /**
     * Test getting wallet transactions
     */
    public function testGettingTransactions()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/wallets/1/transactions/', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            array(
                                'id' => 20,
                                'timestamp' => 1388669449,
                                'new_balance' => 8085.56838,
                                'change' => 200
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $wallet = $client->wallet->transactions(1);
        
        $this->assertInternalType('array', $wallet);
        $this->assertEquals(20, $wallet[0]['id']);
        $this->assertEquals(1388669449, $wallet[0]['timestamp']);
        $this->assertEquals(8085.56838, $wallet[0]['new_balance']);
        $this->assertEquals(200, $wallet[0]['change']);
    }
    
}