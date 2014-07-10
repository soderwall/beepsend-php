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
    
    /**
     * Test sending messages to groups
     */
    public function testGroup()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/batches/', 'POST', array(
                        'from' => 'Beepsend',
                        'groups' => array(1,2),
                        'message' => 'You rock!',
                        'encoding' => 'UTF-8',
                        'receive_dlr' => 0
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 201,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'groups' => array(1,2),
                            'from' => 'Beepsend',
                            'error' => null
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $message = $client->message->group(array(1,2), 'Beepsend', 'You rock!');
        
        $this->assertInternalType('array', $message);
        $this->assertInternalType('array', $message['groups']);
        $this->assertEquals('Beepsend', $message['from']);
        $this->assertEquals(null, $message['error']);
    }
    
    /**
     * Test sending messages in batches
     */
    public function testBatch()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/sms/', 'POST', array(
                        array(
                            'batch_label' => 'Lottery winner',
                            'from' => 'Beepsend',
                            'to' => 12345,
                            'message' => 'You won!',
                            'encoding' => 'UTF-8',
                            'message_type' => 'flash'
                        ),
                        array(
                            'batch_label' => 'Lottery contestants',
                            'from' => 'Beepboop',
                            'to' => array(123456, 1234567),
                            'message' => 'You won!',
                            'encoding' => 'UTF-8'
                        )
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 201,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            array(
                                'id' => 03003620013893441731559112345,
                                'batch' => array(
                                    'id' => 10,
                                    'name' => 'Lottery winner'
                                ),
                                'to' => 12345,
                                'from' => 'Beepsend',
                                'errors' => null
                            ),
                            array(
                                'id' => 030889300138934417315591123456,
                                'batch' => array(
                                    'id' => 11,
                                    'name' => 'Lottery contestants'
                                ),
                                'to' => 123456,
                                'from' => 'Beepboop',
                                'errors' => null
                            ),
                            array(
                                'id' => 0310373001389344173155911234567,
                                'batch' => array(
                                    'id' => 11,
                                    'name' => 'Lottery contestants'
                                ),
                                'to' => 1234567,
                                'from' => 'Beepboop',
                                'errors' => null
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $message = $client->message->batch(array(
                        array(
                            'batch_label' => 'Lottery winner',
                            'from' => 'Beepsend',
                            'to' => 12345,
                            'message' => 'You won!',
                            'encoding' => 'UTF-8',
                            'message_type' => 'flash'
                        ),
                        array(
                            'batch_label' => 'Lottery contestants',
                            'from' => 'Beepboop',
                            'to' => array(123456, 1234567),
                            'message' => 'You won!',
                            'encoding' => 'UTF-8'
                        )
                    ));
        
        $this->assertInternalType('array', $message);
        $this->assertInternalType('array', $message[0]);
        $this->assertInternalType('array', $message[1]);
        $this->assertInternalType('array', $message[2]);
        $this->assertEquals(03003620013893441731559112345, $message[0]['id']);
        $this->assertEquals(10, $message[0]['batch']['id']);
        $this->assertEquals('Lottery winner', $message[0]['batch']['name']);
        $this->assertEquals(12345, $message[0]['to']);
        $this->assertEquals('Beepsend', $message[0]['from']);
        $this->assertEquals(null, $message[0]['errors']);
    }
    
    /**
     * Test sending binary messages
     */
    public function testBinary()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/sms/', 'POST', array(
                        'from' => 'Beepsend',
                        'to' => 46736007518,
                        'message' => 'Binary world',
                        'receive_dlr' => 0,
                        'message_type' => 'binary'
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
        $message = $client->message->binary(46736007518, 'Beepsend', 'Binary world');
        
        $this->assertInternalType('array', $message);
        $this->assertEquals(07595980013893439611559146736007518, $message['id']);
        $this->assertEquals(46736007518, $message['to']);
        $this->assertEquals('Beepsend', $message['from']);
        $this->assertEquals(null, $message['error']);
    }
    
    /**
     * Test getting details of sent messages
     */
    public function testLookup()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/sms/12345', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 12345,
                            'to' => array(
                                'address' => 46736007518,
                                'ton' => 1,
                                'npi' => 1
                            ),
                            'from' => array(
                                'address' => 'Beepsend',
                                'ton' => 1,
                                'npi' => 1
                            ),
                            'dlr' => array(
                                'status' => 'DELIVRD',
                                'error' => 0
                            ),
                            'price' => 0.068
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $message = $client->message->lookup(12345);
        
        $this->assertInternalType('array', $message);
        $this->assertEquals(12345, $message['id']);
        $this->assertEquals(46736007518, $message['to']['address']);
        $this->assertEquals('Beepsend', $message['from']['address']);
        $this->assertEquals('DELIVRD', $message['dlr']['status']);
        $this->assertEquals(0.068, $message['price']);
    }
    
    /**
     * Test getting details for multiple messages
     */
    public function testMultipleLookup()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/sms/', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            array(
                                'id' => 12345,
                                'to' => array(
                                    'address' => 46736007518,
                                    'ton' => 1,
                                    'npi' => 1
                                ),
                                'from' => array(
                                    'address' => 'Beepsend',
                                    'ton' => 1,
                                    'npi' => 1
                                ),
                                'dlr' => array(
                                    'status' => 'DELIVRD',
                                    'error' => 0
                                ),
                                'price' => 0.068
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $message = $client->message->multipleLookup();
        
        $this->assertInternalType('array', $message);
        $this->assertEquals(12345, $message[0]['id']);
        $this->assertEquals(46736007518, $message[0]['to']['address']);
        $this->assertEquals('Beepsend', $message[0]['from']['address']);
        $this->assertEquals('DELIVRD', $message[0]['dlr']['status']);
        $this->assertEquals(0.068, $message[0]['price']);
    }
    
    /**
     * Test validating messages
     */
    public function testValidate()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/sms/validate/', 'POST', array(
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
                            'id' => null,
                            'to' => 46736007518,
                            'from' => 'Beepsend',
                            'error' => null
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $message = $client->message->validate(46736007518, 'Beepsend', 'Hello World! 你好世界!');
        
        $this->assertInternalType('array', $message);
        $this->assertEquals(null, $message['id']);
        $this->assertEquals(46736007518, $message['to']);
        $this->assertEquals('Beepsend', $message['from']);
        $this->assertEquals(null, $message['error']);
    }
    
}