<?php

namespace Beepsend\Resource;

use Beepsend\Request;
use Beepsend\Resource\ResourceInterface;

class Contact implements ResourceInterface {
    
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
        'contacts' => '/contacts/',
        'groups' => '/contacts/groups/'
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
     * Get all contacts belonging to your user.
     * @param string $group Group id or name
     * @param string $sort Sorting of the dataset. Available keys: name, id. Can be prepended with + or - to change the sorting direction (+ ASC, - DESC).
     * @return array
     */
    public function all($group = null, $sort = null)
    {
        $data = array();
        
        if (!is_null($group)) {
            $data['group'] = $group;
        }
        
        if (!is_null($sort)) {
            $data['sort'] = $sort;
        }
        
        $response = $this->request->call($this->actions['contacts'], 'GET', $data);
        return $response->get();
    }
    
    /**
     * Add new contact
     * @param string $msisdn Contact number
     * @param string $firstName Contact first name
     * @param string $lastName Contact last name
     * @param int $groupId Contact group id
     * @return array
     */
    public function add($msisdn, $firstName = null, $lastName = null, $groupId = null)
    {
        $data = array(
            'msisdn' => $msisdn
        );
        
        if (!is_null($firstName)) {
            $data['firstname'] = $firstName;
        }
        
        if (!is_null($lastName)) {
            $data['lastname'] = $lastName;
        }
        
        if (!is_null($groupId)) {
            $data['group_id'] = $groupId;
        }
        
        $response = $this->request->call($this->actions['contacts'], 'POST', $data);
        return $response->get();
    }
    
    /**
     * Update existing contact
     * @param int $contactId Contact id
     * @param array $options Array of options to update. Available keys: msisdn, firstname, lastname, group_id.
     */
    public function update($contactId, $options)
    {
        $response = $this->request->call($this->actions['contacts'] . $contactId, 'PUT', $options);
        return $response->get();
    }
    
    /**
     * Delete contact
     * @param int $contactId Contact id
     * @return array
     */
    public function delete($contactId)
    {
        $response = $this->request->call($this->actions['contacts'] . $contactId, 'DELETE');
        return $response->get();
    }
    
    /**
     * Get all contact groups belonging to your user.
     * @return array
     */
    public function groups()
    {
        $response = $this->request->call($this->actions['groups'], 'GET');
        return $response->get();
    }
    
    /**
     * Get content of a contact group
     * @param int $groupId Group id
     * @return array
     */
    public function group($groupId)
    {
        $response = $this->request->call($this->actions['groups'] . $groupId, 'GET');
        return $response->get();
    }
    
    /**
     * Add new contact group
     * @param string $groupName Group id
     * @return array
     */
    public function addGroup($groupName)
    {
        $data = array(
            'name' => $groupName
        );
        
        $response = $this->request->call($this->actions['groups'], 'POST', $data);
        return $response->get();
    }
    
    /**
     * Update existing contact group.
     * @param int $groupId Group id
     * @param string $groupName Group name
     * @return array
     */
    public function updateGroup($groupId, $groupName)
    {
        $data = array(
            'name' => $groupName
        );
        
        $response = $this->request->call($this->actions['groups'] . $groupId, 'PUT', $data);
        return $response->get();
    }
    
    /**
     * Delete contact group
     * @param int $groupId
     * @return array
     */
    public function deleteGroup($groupId)
    {
        $response = $this->request->call($this->actions['groups'] . $groupId, 'DELETE');
        return $response->get();
    }
    
}