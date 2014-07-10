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
    
}