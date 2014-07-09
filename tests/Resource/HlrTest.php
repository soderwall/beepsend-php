<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class HlrTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test calling intermediate HLR
     */
    public function testIntermediate()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/hlr/123456789', 'GET', array(
                        'connection' => 'me'
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 08087780013866630151559112345,
                            'imsi' => 423900000000000000000,
                            'roaming' => true,
                            'dlr' => array(
                                'status' => 'DELIVRD',
                                'error' => 0
                            ),
                            'mccmnc' => array(
                                'mcc' => 402,
                                'mnc' => 02
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $hlr = $client->hlr->intermediate(123456789);
        
        $this->assertInternalType('array', $hlr);
        $this->assertInternalType('array', $hlr['dlr']);
        $this->assertInternalType('array', $hlr['mccmnc']);
        $this->assertEquals(08087780013866630151559112345, $hlr['id']);
        $this->assertEquals(423900000000000000000, $hlr['imsi']);
        $this->assertEquals(true, $hlr['roaming']);
        $this->assertEquals('DELIVRD', $hlr['dlr']['status']);
        $this->assertEquals(0, $hlr['dlr']['error']);
        $this->assertEquals(402, $hlr['mccmnc']['mcc']);
        $this->assertEquals(02, $hlr['mccmnc']['mnc']);
    }
    
    /**
     * Test calling bulk HLR
     */
    public function testBulk()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/hlr/', 'POST', array(
                        'msisdn' => array(46736007518, 46736007505)
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            array(
                                'id' => 08087780013866630151559112345,
                                'msisdn' => 46736007518,
                                'errors' => null
                            ),
                            array(
                                'id' => 08087780013866301515591123456,
                                'msisdn' => 46736007505,
                                'errors' => null
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $hlr = $client->hlr->bulk(array(46736007518, 46736007505));
        
        $this->assertInternalType('array', $hlr);
        $this->assertEquals(08087780013866630151559112345, $hlr[0]['id']);
        $this->assertEquals(46736007518, $hlr[0]['msisdn']);
        $this->assertEquals(null, $hlr[0]['errors']);
        $this->assertEquals(08087780013866301515591123456, $hlr[1]['id']);
        $this->assertEquals(46736007505, $hlr[1]['msisdn']);
        $this->assertEquals(null, $hlr[1]['errors']);
    }
    
    /**
     * Test validating HLR request
     */
    public function testValidate()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/hlr/validate', 'POST', array(
                        'msisdn' => 46736007518,
                        'connection' => 'me'
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 08087780013866630151559112345,
                            'msisdn' => 46736007518,
                            'errors' => null
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $hlr = $client->hlr->validate(46736007518);
        
        $this->assertInternalType('array', $hlr);
        $this->assertEquals(08087780013866630151559112345, $hlr['id']);
        $this->assertEquals(46736007518, $hlr['msisdn']);
        $this->assertEquals(null, $hlr['errors']);
    }
    
}