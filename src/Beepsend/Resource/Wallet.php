<?php

namespace Beepsend\Resource;

use Beepsend\Request;
use Beepsend\ResourceInterface;

class Wallet implements ResourceInterface {
    
    /**
     * Beepsend request handler
     * @var Beepsend\Request
     */
    private $request;
    
    /**
     * Action to call
     * @var array
     */
    private $actions = array(
        'wallets' => '/wallets/',
        'transactions' => '/transactions/',
        'transfer' => '/transfer/',
        'notifications' => '/emails/'
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
     * Get all wallets that are connected to this user
     */
    public function all()
    {
        $response = $this->request->execute($this->actions['wallets'], 'GET');
        return $response;
    }
    
    /**
     * Get wallet details
     * @param int $walletId Wallet id
     * @return array
     */
    public function data($walletId)
    {
        $response = $this->request->execute($this->actions['wallets'] . $walletId, 'GET');
        return $response;
    }
    
    /**
     * Update wallet id
     * @param int $walletId Wallet id
     * @param string $name Name of wallet
     * @param int $notifyLimit Email notifiacation limit
     * @return array
     */
    public function update($walletId, $name = null, $notifyLimit = null)
    {
        $data = array();
        
        if (!is_null($name)) {
            $data['name'] = $name;
        }
        
        if (!is_null($notifyLimit)) {
            $data['notify_limit'] = $notifyLimit;
        }
        
        $response = $this->request->execute($this->actions['wallets'] . $walletId, 'PUT', $data);
        return $response;
    }
    
    /**
     * Returns all transactions for wallet
     * @param int $walletId Wallet id
     * @return array
     */
    public function transactions($walletId)
    {
        $response = $this->request->execute($this->actions['wallets'] . $walletId . $this->actions['transactions'], 'GET');
        return $response;
    }
    
    /**
     * Transfer credits between your connection wallets.
     * @param int $sourceId Source wallet
     * @param int $targetId Target wallet
     * @param float $amount Amount to transfer
     * @return array
     */
    public function transfer($sourceId, $targetId, $amount)
    {
        $data = array(
            'amount' => $amount
        );
        
        $response = $this->request->execute($this->actions['wallets'] . $sourceId . $this->actions['transfer'] . $targetId . '/', 'POST', $data);
        return $response;
    }
    
    /**
     * Get a list of your external emails. 
     * @param int $walletId Wallet id
     * @return array
     */
    public function notifications($walletId)
    {
        $response = $this->request->execute($this->actions['wallets'] . $walletId . $this->actions['notifications'], 'GET');
        return $response;
    }
    
    /**
     * Add external email for notifications to wallet.
     * @param int $walletId Wallet id
     * @param string $email Email address
     * @return array
     */
    public function addNotificationEmail($walletId, $email)
    {
        $data = array(
            'email' => $email
        );
        
        $response = $this->request->execute($this->actions['wallets'] . $walletId . $this->actions['notifications'], 'POST', $data);
        return $response;
    }
    
    /**
     * Delete external email for notifications to wallet.
     * @param int $walletId Wallet id
     * @param int $emailId Email id
     * @return array
     */
    public function deleteNotificationEmail($walletId, $emailId)
    {
        $response = $this->request->execute($this->actions['wallets'] . $walletId . $this->actions['notifications'] . $emailId, 'DELETE');
        return $response;
    }
}