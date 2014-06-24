<?php

namespace Beepsend\Resource;

use Beepsend\Request;
use Beepsend\Resource\ResourceInterface;

class Analytic implements ResourceInterface {
    
    /**
     * Beepsend request handler
     * @var Beepsend\Request;
     */
    private $request;
    
    /**
     * Action to call
     * @var array
     */
    private $actions = array(
        'summary' => '/analytics/summary/',
        'network' => '/analytics/network/',
        'batch' => '/analytics/batches/'
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
     * Get accumulated Statistics, set connection for getting accumulated statictic for connection
     * @param string $connection Connection id
     * @param int $fromDate Unix time
     * @param int $toDate Unix time
     * @return array
     */
    public function summary($connection = null, $fromDate = null, $toDate = null)
    {
        $data = array();
        
        if (!is_null($fromDate)) {
            $data['from_date'] = $fromDate;
        }
        
        if (!is_null($toDate)) {
            $data['to_date'] = $toDate;
        }
        
        $response = $this->request->call($this->actions['summary'] . $connection, 'GET', $data);
        return $response->get();
    }
    
    /**
     * This call will give you granular delivery statistics for all messages sorted by each recipient network between two specified dates.
     * @param string $connection Connection id
     * @param int $fromDate Unix time
     * @param int $toDate Unix time
     * @param string $MCC Mobile Country Code
     * @param string $MNC Mobile Network Code
     * @return array
     */
    public function network($connection = null, $fromDate = null, $toDate = null, $MCC = null, $MNC = null)
    {
        $data = array();
        
        if (!is_null($fromDate)) {
            $data['from_date'] = $fromDate;
        }
        
        if (!is_null($toDate)) {
            $data['to_date'] = $toDate;
        }
        
        if (!is_null($MCC)) {
            $data['MCC'] = $MCC;
        }
        
        if (!is_null($MNC)) {
            $data['MNC'] = $MNC;
        }
        
        $response = $this->request->call($this->actions['network'] . $connection, 'GET', $data);
        return $response->get();
    }
    
    /**
     * This call will give you delivery statistics for a whole batch.
     * @param int $batchId Batch id
     */
    public function batch($batchId = null)
    {
        $response = $this->request->call($this->actions['batch'] . $batchId, 'GET');
        return $response->get();
    }
    
}