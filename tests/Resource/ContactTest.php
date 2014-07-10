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
    
    /**
     * Test adding new contact
     */
    public function testAdd()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/contacts/', 'POST', array(
                        'msisdn' => 1234567,
                        'firstname' => 'Example contact'
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 22594443,
                            'msisdn' => 1234567,
                            'firstname' => 'Example contact'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $contact = $client->contact->add(1234567, 'Example contact');
        
        $this->assertInternalType('array', $contact);
        $this->assertEquals(22594443, $contact['id']);
        $this->assertEquals(1234567, $contact['msisdn']);
        $this->assertEquals('Example contact', $contact['firstname']);
    }
    
    /**
     * Test updating existing contacts
     */
    public function testUpdate()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/contacts/22594443', 'PUT', array(
                        'msisdn' => 3456789,
                        'firstname' => 'Still an example'
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 22594443,
                            'msisdn' => 3456789,
                            'firstname' => 'Still an example'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $contact = $client->contact->update(22594443, array('msisdn' => 3456789, 'firstname' => 'Still an example'));
        
        $this->assertInternalType('array', $contact);
        $this->assertEquals(22594443, $contact['id']);
        $this->assertEquals(3456789, $contact['msisdn']);
        $this->assertEquals('Still an example', $contact['firstname']);
    }
    
    /**
     * Test deleting contacts
     */
    public function testDelete()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/contacts/22594443', 'DELETE', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 204,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array())
                    ));
        
        $client = new Client('abc123', $connector);
        $contact = $client->contact->delete(22594443);
        
        $this->assertInternalType('array', $contact);
    }
    
    /**
     * Test getting all groups
     */
    public function testGroups()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/contacts/groups/', 'GET', array())
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
        $contact = $client->contact->groups();
        
        $this->assertInternalType('array', $contact);
        $this->assertInternalType('array', $contact[0]);
        $this->assertInternalType('array', $contact[1]);
        $this->assertEquals(1, $contact[0]['id']);
        $this->assertEquals('Customers', $contact[0]['name']);
        $this->assertEquals(27, $contact[0]['contacts_count']);
        $this->assertEquals(1, $contact[0]['processing']);
        $this->assertEquals(2, $contact[1]['id']);
        $this->assertEquals('Others', $contact[1]['name']);
        $this->assertEquals(2, $contact[1]['contacts_count']);
        $this->assertEquals(0, $contact[1]['processing']);
    }
    
    /**
     * Test getting contacts from some group
     */
    public function testGroup()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/contacts/groups/1', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            array(
                                'id' => 22594444,
                                'msisdn' => 12345,
                                'name' => 'Multi-example',
                                'group_id' => 1,
                                'group_name' => 'Customers'
                            ),
                            array(
                                'id' => 22594443,
                                'msisdn' => 3456789,
                                'name' => 'Still an example',
                                'group_id' => 1,
                                'group_name' => 'Customers'
                            )
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $contact = $client->contact->group(1);
        
        $this->assertInternalType('array', $contact);
        $this->assertInternalType('array', $contact[0]);
        $this->assertInternalType('array', $contact[1]);
        $this->assertEquals(22594444, $contact[0]['id']);
        $this->assertEquals(12345, $contact[0]['msisdn']);
        $this->assertEquals('Multi-example', $contact[0]['name']);
        $this->assertEquals(1, $contact[0]['group_id']);
        $this->assertEquals('Customers', $contact[0]['group_name']);
        $this->assertEquals(22594443, $contact[1]['id']);
        $this->assertEquals(3456789, $contact[1]['msisdn']);
        $this->assertEquals('Still an example', $contact[1]['name']);
        $this->assertEquals(1, $contact[1]['group_id']);
        $this->assertEquals('Customers', $contact[1]['group_name']);
    }
    
    /**
     * Test adding new group
     */
    public function testAddingGroup()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/contacts/groups/', 'POST', array(
                        'name' => 'Important people'
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 2,
                            'name' => 'Important people',
                            'contacts_count' => 0
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $contact = $client->contact->addGroup('Important people');
        
        $this->assertInternalType('array', $contact);
        $this->assertEquals(2, $contact['id']);
        $this->assertEquals('Important people', $contact['name']);
        $this->assertEquals(0, $contact['contacts_count']);
    }
    
    /**
     * Test updating existing groups
     */
    public function testUpdatingGroup()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/contacts/groups/2', 'PUT', array(
                        'name' => 'Still very important people'
                    ))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 2,
                            'name' => 'Still very important people',
                            'contacts_count' => 0
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $contact = $client->contact->updateGroup(2, 'Still very important people');
        
        $this->assertInternalType('array', $contact);
        $this->assertEquals(2, $contact['id']);
        $this->assertEquals('Still very important people', $contact['name']);
        $this->assertEquals(0, $contact['contacts_count']);
    }
    
    /**
     * Test deleting contact groups
     */
    public function testDeletingGroup()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with(BASE_API_URL . '/' . API_VERSION . '/contacts/groups/2', 'DELETE', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 204,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array())
                    ));
        
        $client = new Client('abc123', $connector);
        $contact = $client->contact->deleteGroup(2);
        
        $this->assertInternalType('array', $contact);
    }
}