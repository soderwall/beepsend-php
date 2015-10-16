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
                    ->with(BASE_API_URL . '/' . API_VERSION . '/send/', 'POST', [
                        'from' => 'Beepsend',
                        'to' => '46736007518',
                        'message' => 'Hello World! 你好世界!',
                        'encoding' => 'UTF-8'
                    ])
                    ->once()
                    ->andReturn([
                        'info' => [
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ],
                        'response' => json_encode([
                            'id' => ['07595980013893439611559146736007518'],
                            'to' => '46736007518',
                            'from' => 'Beepsend'
                        ])
                    ]);

        $client = new Client('abc123', $connector);
        $msgHelper = $client->getHelper('message');
        $msgHelper->message('46736007518', 'Beepsend', 'Hello World! 你好世界!');
        $message = $client->message->multiple($msgHelper);

        $this->assertInternalType('array', $message);
        $this->assertEquals(['07595980013893439611559146736007518'], $message['id']);
        $this->assertEquals('46736007518', $message['to']);
        $this->assertEquals('Beepsend', $message['from']);
    }

    /**
     * Test getting messages from helper
     */
    public function testGettingMessagesFromHelper()
    {
        $client = new Client('abc123');
        $msgHelper = $client->getHelper('message');

        $msgHelper->message('46736007518', 'Beepsend', 'Hello World! 你好世界!');
        $messages = $msgHelper->get();

        $this->assertInternalType('array', $messages);
        $this->assertEquals('Beepsend', $messages['from']);
        $this->assertEquals('46736007518', $messages['to']);
        $this->assertEquals('Hello World! 你好世界!', $messages['message']);

    }

    public function tearDown()
    {
        \Mockery::close();
    }

}