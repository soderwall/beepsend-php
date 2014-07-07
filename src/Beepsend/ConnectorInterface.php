<?php

namespace Beepsend;

/**
 * Connector Interface
 * @package Beepsend
 */
interface ConnectorInterface 
{
    
    /**
     * Set required data for connector to make request to Beepsend API
     * @param int $version Beepsend API version
     * @param string $userAgent Beepsend PHP helper user agent
     * @param string $baseApiUrl Beepsend API Url
     * @param string $token User or connection that we will use for authorization on Beepsend API
     */
    public function __construct($version, $userAgent, $baseApiUrl, $token);
    
    /**
     * Method that will execute some call to Beepsend API
     * @param string $action Action that we are calling
     * @param string $method Request method
     * @param array $params Array of additional parameters
     * @return Beepsend\Response
     * @throws NotFound
     * @throws InvalidRequest
     */
    public function execute($action, $method, $params);
    
    /**
     * Method that will upload some file
     * @param string $action Action that we are calling
     * @param array $params Array of additional parameters
     * @param string $rawData String using this for posting file content
     * @return Beepsend\Response
     * @throws NotFound
     * @throws InvalidRequest
     * @throws InvalidToken
     */
    public function upload($action, $params, $rawData);
    
}