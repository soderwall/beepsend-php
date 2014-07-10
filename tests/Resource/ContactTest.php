<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class ContactTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test getting all contacts
     */
    public function testGettingAll()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/contacts/', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            array(
                                'id' => 22594418,
                                'msisdn' => 46736007518,
                                'name' => 'Phone',
                                'group_id' => null,
                                'group_name' => null
                            ),
                            array(
                                'id' => 22594420,
                                'msisdn' => 46406007500,
                                'name' => 'Beepsend',
                                'group_id' => 213,
                                'group_name' => 'Beepnumbers'
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $contact = $client->contact->all();
        
        $this->assertInternalType('array', $contact);
        $this->assertInternalType('array', $contact[0]);
        $this->assertInternalType('array', $contact[1]);
        $this->assertEquals(22594418, $contact[0]['id']);
        $this->assertEquals(46736007518, $contact[0]['msisdn']);
        $this->assertEquals('Phone', $contact[0]['name']);
        $this->assertEquals(null, $contact[0]['group_id']);
        $this->assertEquals(null, $contact[0]['group_name']);
        $this->assertEquals(22594420, $contact[1]['id']);
        $this->assertEquals(46406007500, $contact[1]['msisdn']);
        $this->assertEquals('Beepsend', $contact[1]['name']);
        $this->assertEquals(213, $contact[1]['group_id']);
        $this->assertEquals('Beepnumbers', $contact[1]['group_name']);
    }
    
}