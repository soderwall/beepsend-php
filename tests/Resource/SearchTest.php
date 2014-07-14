<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class SearchTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test searching contacts
     */
    public function testSearchingContacts()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/search/contacts/', 'GET', array(
                        'query' => 'Phone'
                    ))
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
                                'name' => 'Phone',
                                'group_id' => 213,
                                'group_name' => 'Beepnumbers'
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $results = $client->search->contacts('Phone');
        
        $this->assertInternalType('array', $results);
        $this->assertEquals(22594418, $results[0]['id']);
        $this->assertEquals(46736007518, $results[0]['msisdn']);
        $this->assertEquals('Phone', $results[0]['name']);
        $this->assertEquals(22594420, $results[1]['id']);
        $this->assertEquals(46406007500, $results[1]['msisdn']);
        $this->assertEquals('Phone', $results[1]['name']);
    }
    
    /**
     * Test searching contacts groups
     */
    public function testSearchingGroups()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/search/contact_groups/', 'GET', array(
                        'query' => 'Something'
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            array(
                                'id' => 1,
                                'name' => 'Customers',
                                'contacts_count' => 27,
                                'processing' => 1
                            ),
                            array(
                                'id' => 2,
                                'name' => 'Others',
                                'contacts_count' => 2,
                                'processing' => 0
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $results = $client->search->groups('Something');
        
        $this->assertInternalType('array', $results);
        $this->assertEquals(1, $results[0]['id']);
        $this->assertEquals('Customers', $results[0]['name']);
        $this->assertEquals(27, $results[0]['contacts_count']);
        $this->assertEquals(1, $results[0]['processing']);
        $this->assertEquals(2, $results[1]['id']);
        $this->assertEquals('Others', $results[1]['name']);
        $this->assertEquals(2, $results[1]['contacts_count']);
        $this->assertEquals(0, $results[1]['processing']);
    }
    
    public function tearDown()
    {
        \Mockery::close();
    }
}