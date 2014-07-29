<?php

namespace Beepsend\Resource;

use Beepsend\Request;
use Beepsend\Helper\Message as MessageHelper;

/**
 * Beepsend message resource
 * @package Beepsend
 */
class Message 
{
    
    /**
     * Beepsend request handler
     * @var Beepsend\Request
     */
    private $request;
    
    /**
     * Actions to call
     * @var array
     */
    private $actions = array(
        'sms' => '/sms/',
        'validate' => '/sms/validate/',
        'batches' => '/batches/',
        'estimation' => 'costestimate/'
    );
    
    /**
     * Init customer resource
     * @param Beepsend\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Send new SMS
     * @param int|string $to Number where we are sending message, for multiple recepiants use array (number1, number2)
     * @param int|array $from Number we are sending from or text
     * @param string $message Message we are sending
     * @param string $connection Connection id to use for sending sms
     * @param string $encoding Encoding of message UTF-8, ISO-8859-15 or Unicode
     * @param array $options Array of additional options. More info on: http://api.beepsend.com/docs.html#send-sms
     * @return array
     */
    public function send($to, $from, $message, $connection = null, $encoding = 'UTF-8', $options = array())
    {
        $data = array(
            'from' => $from,
            'to' => $to,
            'message' => mb_convert_encoding($message, $encoding, 'UTF-8'),
            'encoding' => $encoding,
            'receive_dlr' => 0
        );
                
        /* Merge additional options if we have */
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        $response = $this->request->execute($this->actions['sms'] . $connection, 'POST', $data);
        return $response;
    }
    
    /**
     * Send SMS to your groups of contacts
     * @param int|array $groups Group where we are sending message, for multiple groups use array (number1, number2)
     * @param int|string $from Number we are sending from or text
     * @param string $message Message we are sending
     * @param string $connection Connection id to use for sending sms
     * @param string $encoding Encoding of message UTF-8, ISO-8859-15 or Unicode
     * @param array $options Array of additional options. More info on: http://api.beepsend.com/docs.html#send-sms
     * @return array
     */
    public function group($groups, $from, $message, $connection = null, $encoding = 'UTF-8', $options = array())
    {
        $data = array(
            'from' => $from,
            'groups' => $groups,
            'message' => mb_convert_encoding($message, $encoding, 'UTF-8'),
            'encoding' => $encoding,
            'receive_dlr' => 0
        );
                
        /* Merge additional options if we have */
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        $response = $this->request->execute($this->actions['batches'] . $connection, 'POST', $data);
        return $response;
    }
    
    /**
     * Send multiple messages to one or more receivers
     * @param \Beepsend\Helper\Message $messages
     * @param string $connection Connection id to use for sending sms
     * @return array
     */
    public function multiple(MessageHelper $messages, $connection = null)
    {
        $response = $this->request->execute($this->actions['sms'] . $connection, 'POST', $messages->get());
        return $response;
    }
    
    /**
     * Send new binary SMS
     * @param int|string $to Number where we are sending message, for multiple recepiants use array (number1, number2)
     * @param int|array $from Number we are sending from or text
     * @param string $message Message we are sending
     * @param string $connection Connection id to use for sending sms
     * @param string $encoding Encoding of message UTF-8, ISO-8859-15 or Unicode
     * @param array $options Array of additional options. More info on: http://api.beepsend.com/docs.html#send-sms-binary
     * @return array
     */
    public function binary($to, $from, $message, $connection = null, $options = array())
    {
        $data = array(
            'from' => $from,
            'to' => $to,
            'message' => $message,
            'receive_dlr' => 0,
            'message_type' => 'binary'
        );
                
        /* Merge additional options if we have */
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        $response = $this->request->execute($this->actions['sms'] . $connection, 'POST', $data);
        return $response;
    }
    
    /**
     * Get message details of sent messages through Beepsend
     * @param int $smsId Id of message
     */
    public function lookup($smsId)
    {
        $response = $this->request->execute($this->actions['sms'] . $smsId, 'GET');
        return $response;
    }
    
    /**
     * Get messages details of sent messages through Beepsend
     * @param array $options Array of options to fetch messages. More info on: http://api.beepsend.com/docs.html#sms-lookup-multiple
     */
    public function multipleLookup($options = array())
    {
        $response = $this->request->execute($this->actions['sms'], 'GET', $options);
        return $response;
    }
    
    /**
     * Validate sms
     * @param int|array $to Number where we are sending message, for multiple recepiants use array (number1, number2)
     * @param int|string $from Number we are sending from or text
     * @param string $message Message we are sending
     * @param string $connection Connection id to use for sending sms
     * @param string $encoding Encoding of message UTF-8, ISO-8859-15 or Unicode
     * @param array $options Array of additional options. More info on: http://api.beepsend.com/docs.html#send-sms
     * @return array
     */
    public function validate($to, $from, $message, $connection = null, $encoding = 'UTF-8', $options = array())
    {
        $data = array(
            'from' => $from,
            'to' => $to,
            'message' => mb_convert_encoding($message, $encoding, 'UTF-8'),
            'encoding' => $encoding,
            'receive_dlr' => 0
        );
                
        /* Merge additional options if we have */
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        $response = $this->request->execute($this->actions['validate'] . $connection, 'POST', $data);
        return $response;
    }
    
    /**
     * Get previous batches
     * @return array
     */
    public function batches()
    {
        $response = $this->request->execute($this->actions['batches'], 'GET');
        return $response;
    }
    
    /**
     * Estimate cost
     * @param int|array $to Msisdn or array of msisdns
     * @param string $message Message
     * @param string $connection Connection id
     * @param string $encoding Encoding of message UTF-8, ISO-8859-15 or Unicode
     * @return array
     */
    public function estimateCost($to, $message, $connection = null, $encoding = 'UTF-8')
    {
        $data = array(
            'to' => $to,
            'message' => $message,
            'encoding' => $encoding
        );
        
        $response = $this->request->execute($this->actions['sms'] . $this->actions['estimation'] . $connection, 'POST', $data);
        return $response;
    }
    
    /**
     * Estimate cost for sendting to group
     * @param id|array $groups Single or multple groups
     * @param string $message Message
     * @param string $connection Connection id
     * @param string $encoding Encoding of message UTF-8, ISO-8859-15 or Unicode
     */
    public function estimateCostGroup($groups, $message, $connection = null, $encoding = 'UTF-8')
    {
        $data = array(
            'groups' => $groups,
            'message' => $message,
            'encoding' => $encoding
        );
        
        $response = $this->request->execute($this->actions['sms'] . $this->actions['estimation'] . $connection, 'POST', $data);
        return $response;
    }
    
}