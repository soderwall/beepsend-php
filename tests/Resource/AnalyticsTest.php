<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class AnalyticsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getting accumulated Statistics
     */
    public function testSummary()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/analytics/summary/me', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'connection' => 'customer-1',
                            'count' => 100,
                            'price' => 4.38
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $analytics = $client->analytic->summary('me');
        
        $this->assertInternalType('array', $analytics);
        $this->assertEquals('customer-1', $analytics['connection']);
        $this->assertEquals(100, $analytics['count']);
        $this->assertEquals(4.38, $analytics['price']);
    }
    
    /**
     * Test getting granular delivery statistics for all messages sorted by each recipient network between two specified dates
     */
    public function testNetwork()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/analytics/network/me', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'mccmnc' => array(
                                'mcc' => 683,
                                'mnc' => 03
                            ),
                            'statistics' => array(
                                'delivered' => 100,
                                'expired' => 12
                            ),
                            'total' => 142
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $analytics = $client->analytic->network('me');
        
        $this->assertInternalType('array', $analytics);
        $this->assertInternalType('array', $analytics['mccmnc']);
        $this->assertInternalType('array', $analytics['statistics']);
        $this->assertEquals(683, $analytics['mccmnc']['mcc']);
        $this->assertEquals(03, $analytics['mccmnc']['mnc']);
        $this->assertEquals(100, $analytics['statistics']['delivered']);
        $this->assertEquals(12, $analytics['statistics']['expired']);
        $this->assertEquals(142, $analytics['total']);
    }
    
    /**
     * Test getting delivery statistics for a whole batch
     */
    public function testBatch()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/analytics/batches/789456', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 23,
                            'label' => 'My batch',
                            'total' => 142,
                            'statistics' => array(
                                'delivered' => 100,
                                'expired' => 12
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $analytics = $client->analytic->batch(789456);
        
        $this->assertInternalType('array', $analytics);
        $this->assertInternalType('array', $analytics['statistics']);
        $this->assertEquals(23, $analytics['id']);
        $this->assertEquals('My batch', $analytics['label']);
        $this->assertEquals(142, $analytics['total']);
        $this->assertEquals(100, $analytics['statistics']['delivered']);
        $this->assertEquals(12, $analytics['statistics']['expired']);
    }
}