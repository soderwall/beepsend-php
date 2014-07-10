<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class PricelistTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test getting pricelist info for some connection
     */
    public function testGet()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/connections/me/pricelists/current', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'networks' => array(
                                'mccmnc' => array(
                                    'mnc' => 03,
                                    'mcc' => 648
                                ),
                                'price' => 0.006,
                                'country' => array(
                                    'name' => 'Zimbabwe',
                                    'prefix' => 263,
                                    'code' => 'ZW'
                                ),
                                'operator' => 'Telecel Zimbabwe (PVT) Ltd (TELECEL)'
                            ),
                            'id' => 280290,
                            'timestamp' => 1386085755,
                            'active' => true
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $pricelist = $client->pricelist->get('me');
        
        $this->assertInternalType('array', $pricelist);
        $this->assertEquals(648, $pricelist['networks']['mccmnc']['mcc']);
        $this->assertEquals(0.006, $pricelist['networks']['price']);
        $this->assertEquals('Zimbabwe', $pricelist['networks']['country']['name']);
        $this->assertEquals('Telecel Zimbabwe (PVT) Ltd (TELECEL)', $pricelist['networks']['operator']);
        $this->assertEquals(280290, $pricelist['id']);
        $this->assertEquals(true, $pricelist['active']);
    }
    
    /**
     * Test downloading pricelists
     */
    public function testDownload()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/pricelists/me.csv', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => 'mcc;mnc;operator;price'
                                    . '240;;Default;0.08'
                                    . '240;01;"TeliaSonera Mobile Networks AB Sweden (TeliaSonera Mobile Networks)";0.068'
                    ));
        
        $client = new Client('abc123', $connector);
        $pricelist = $client->pricelist->download('me', 'me');
        
        $this->assertEquals(null, $pricelist);
    }
    
}