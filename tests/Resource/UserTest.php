<?php

use Beepsend\Client;
use Beepsend\Connector\Curl;

class UserTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test getting user data
     */
    public function testGettingData()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/users/me', 'GET', array())
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 4,
                            'name' => 'Beep',
                            'customer' => 'Beepsend AB',
                            'api_token' => 'abc123'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $user = $client->user->data();
        
        $this->assertInternalType('array', $user);
        $this->assertEquals(4, $user['id']);
        $this->assertEquals('Beep', $user['name']);
        $this->assertEquals('Beepsend AB', $user['customer']);
        $this->assertEquals('abc123', $user['api_token']);
    }
    
    /**
     * Test updating some of user data
     */
    public function testUpdatingData()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/users/me', 'PUT', array('name' => 'New Beepsend'))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 4,
                            'name' => 'New Beepsend',
                            'customer' => 'Beepsend AB',
                            'api_token' => 'abc123'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $user = $client->user->update(array('name' => 'New Beepsend'));
        
        $this->assertInternalType('array', $user);
        $this->assertEquals(4, $user['id']);
        $this->assertEquals('New Beepsend', $user['name']);
        $this->assertEquals('Beepsend AB', $user['customer']);
        $this->assertEquals('abc123', $user['api_token']);
    }
    
    /**
     * Test updating user email address
     */
    public function testUpdatingUserEmail()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/users/me/email', 
                            'PUT', 
                            array('email' => 'new@beepsend.com', 'password' => 'supersecret'))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 204,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array())
                    ));
        
        $client = new Client('abc123', $connector);
        $user = $client->user->updateEmail('new@beepsend.com', 'supersecret');
        
        $this->assertInternalType('array', $user);
    }
    
    /**
     * Test updating user password
     */
    public function testUpdatingUserPassword()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/users/me/password', 
                            'PUT', 
                            array('password' => 'supersecret', 'new_password' => 'donotlookplease'))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 204,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array())
                    ));
        
        $client = new Client('abc123', $connector);
        $user = $client->user->updatePassword('donotlookplease', 'supersecret');
        
        $this->assertInternalType('array', $user);
    }
    
    /**
     * Test reseting user password
     */
    public function testResetingUserPassword()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/users/passwordreset', 
                            'GET', 
                            array('email' => 'myemail@beepsend.com'))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 204,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array())
                    ));
        
        $client = new Client('abc123', $connector);
        $user = $client->user->resetUserPassword('myemail@beepsend.com');
        
        $this->assertInternalType('array', $user);
    }
    
    /**
     * Test setting new password with reset hash
     */
    public function testSettingNewPassword()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/users/password/abchash', 
                            'PUT', 
                            array('password' => 'mynewpassword'))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'id' => 4,
                            'name' => 'Beep',
                            'customer' => 'Beepsend AB',
                            'api_token' => 'abc123'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $user = $client->user->setNewPassword('abchash', 'mynewpassword');
        
        $this->assertInternalType('array', $user);
        $this->assertEquals(4, $user['id']);
        $this->assertEquals('Beep', $user['name']);
        $this->assertEquals('Beepsend AB', $user['customer']);
        $this->assertEquals('abc123', $user['api_token']);
    }
    
    /**
     * Test reseting user token
     */
    public function testResetingUserToken()
    {
        $connector = \Mockery::mock(new Curl());
        $connector->shouldReceive('call')
                    ->with('https://api.beepsend.com/2/users/me/tokenreset', 
                            'GET', 
                            array('password' => 'supersecret'))
                    ->once()
                    ->andReturn(array(
                        'info' => array(
                            'http_code' => 200,
                            'Content-Type' => 'application/json'
                        ),
                        'response' => json_encode(array(
                            'api_token' => 'abc123'
                        ))
                    ));
        
        $client = new Client('abc123', $connector);
        $user = $client->user->resetUserToken('supersecret');
        
        $this->assertInternalType('array', $user);
        $this->assertEquals('abc123', $user['api_token']);
    }
    
}