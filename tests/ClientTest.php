<?php

use Beepsend\Client;

class ClientTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Location of resources that we will load
     * @var string
     */
    private $resources = '/../src/Beepsend/Resource/';
    
    /**
     * Array of files in resource that we can't load
     * @var array
     */
    private $ignoredResourceFiles = array('.', '..');
    
    /**
     * We are using auto loading of resources and we need to be sure that all resources are loading.
     */
    public function testLoadingResources()
    {
        $resources = scandir(__DIR__ . $this->resources);
        $client = new Client('TokenThatDoesntExists'); // We don't need valid token for loading of resources
        
        /* Try loading all resources */
        foreach ($resources as $resource) {
            if (!in_array($resource, $this->ignoredResourceFiles)) {
                $resourceName = strtolower(pathinfo($resource, PATHINFO_FILENAME));
                $loadedResource = $client->$resourceName;
                $this->assertInstanceOf("Beepsend\Resource\\{$resourceName}", $loadedResource);
            }
        }
    }
    
    /**
     * Test initialization of beepsend client. 
     * We should set some non existing token and client should return invalid token exception.
     * @expectedException Beepsend\Exception\InvalidToken
     */
    public function testConstructor()
    {
        $client = new Client('TokenThatDoesntExists');
        $client->customer->data(); // Try to get customer data
    }
    
}

