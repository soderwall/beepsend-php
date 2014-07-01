<?php

namespace Beepsend\Resource;

use Beepsend\Request;
use Beepsend\Resource\ResourceInterface;

class Search implements ResourceInterface {
    
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
        'contacts' => '/search/contacts/',
        'groups' => '/search/contact_groups/'
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
     * Search for contacts
     * @param string $query Will search entries matching on id, msisdn, firstname and lastname
     * @param int $groupId Group id
     * @return array
     */
    public function contacts($query, $groupId = null)
    {
        $data = array(
            'query' => $query
        );
        
        if (!is_null($groupId)) {
            $data['group_id'] = $groupId;
        }
        
        $response = $this->request->call($this->actions['contacts'], 'GET', $data);
        return $response->get();
    }
    
    /**
     * Search for groups
     * @param string $query Will search entries with matching name
     * @return array
     */
    public function groups($query)
    {
        $data = array(
            'query' => $query
        );
        
        $response = $this->request->call($this->actions['groups'], 'GET', $data);
        return $response->get();
    }
    
}