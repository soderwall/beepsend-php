<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class MessageTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test sending messages
     */
    public function testSending()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/sms/', 'POST', array(
                        'from' => 'Beepsend',
                        'to' => 46736007518,
                        'message' => 'Hello World! 你好世界!',
                        'encoding' => 'UTF-8',
                        'receive_dlr' => 0
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 07595980013893439611559146736007518,
                            'to' => 46736007518,
                            'from' => 'Beepsend',
                            'error' => null
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $message = $client->message->send(46736007518, 'Beepsend', 'Hello World! 你好世界!');
        
        $this->assertInternalType('array', $message);
        $this->assertEquals(07595980013893439611559146736007518, $message['id']);
        $this->assertEquals(46736007518, $message['to']);
        $this->assertEquals('Beepsend', $message['from']);
        $this->assertEquals(null, $message['error']);
    }
    
}