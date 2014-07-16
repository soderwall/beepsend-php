<?php

namespace Beepsend\Helper;

use Beepsend\Client;
use Beepsend\Connector\Curl;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test sending multiple messages
     */
    public function testMultipleMessagesSending()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/sms/', 'POST', array(
                        array(
                            'from' => 'Beepsend',
                            'to' => 46736007518,
                            'message' => 'Hello World! 你好世界!',
                            'encoding' => 'UTF-8',
                            'receive_dlr' => 0
                        ), 
                        array(
                            'from' => 'Beep',
                            'to' => array(46736007518, 46736007518),
                            'message' => 'Hello World! 你好世界!',
                            'encoding' => 'UTF-8',
                            'receive_dlr' => 0
                        )
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            array(
                                'id' => 07595980013893439611559146736007518,
                                'to' => 46736007518,
                                'from' => 'Beepsend',
                                'error' => null
                            ),
                            array(
                                'id' => 07595980013893439611559146736007519,
                                'to' => 46736007518,
                                'from' => 'Beep',
                                'error' => null
                            ),
                            array(
                                'id' => 07595980013893439611559146736007520,
                                'to' => 46736007518,
                                'from' => 'Beep',
                                'error' => null
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $msgHelper = $client->getHelper('message');
        $msgHelper->message(46736007518, 'Beepsend', 'Hello World! 你好世界!');
        $msgHelper->message(array(46736007518, 46736007518), 'Beep', 'Hello World! 你好世界!');
        $message = $client->message->multiple($msgHelper);
        
        $this->assertInternalType('array', $message);
        $this->assertEquals(07595980013893439611559146736007518, $message[0]['id']);
        $this->assertEquals(46736007518, $message[0]['to']);
        $this->assertEquals('Beepsend', $message[0]['from']);
        $this->assertEquals(null, $message[0]['error']);
        $this->assertEquals(07595980013893439611559146736007518, $message[1]['id']);
        $this->assertEquals(46736007518, $message[1]['to']);
        $this->assertEquals('Beep', $message[1]['from']);
        $this->assertEquals(null, $message[1]['error']);
        $this->assertEquals(07595980013893439611559146736007518, $message[2]['id']);
        $this->assertEquals(46736007518, $message[2]['to']);
        $this->assertEquals('Beep', $message[2]['from']);
        $this->assertEquals(null, $message[2]['error']);
    }
    
    /**
     * Test getting messages from helper
     */
    public function testGettingMessagesFromHelper()
    {
        $client = new Client('abc123');
        $msgHelper = $client->getHelper('message');
        
        $msgHelper->message(46736007518, 'Beepsend', 'Hello World! 你好世界!');
        $msgHelper->message(46736007518, 'Beep', 'Hello World! 你好世界!');
        $messages = $msgHelper->get();
        
        $this->assertInternalType('array', $messages);
        $this->assertEquals('Beepsend', $messages[0]['from']);
        $this->assertEquals(46736007518, $messages[0]['to']);
        $this->assertEquals('Hello World! 你好世界!', $messages[0]['message']);
        $this->assertEquals('Beep', $messages[1]['from']);
        $this->assertEquals(46736007518, $messages[1]['to']);
        $this->assertEquals('Hello World! 你好世界!', $messages[1]['message']);
    }
    
}