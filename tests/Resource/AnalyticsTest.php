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
}