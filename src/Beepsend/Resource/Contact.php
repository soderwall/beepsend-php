<?php

namespace Beepsend\Resource;

use Beepsend\Request;
use Beepsend\ResourceInterface;
use Beepsend\Exception\FileNotFound;

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
        'groups' => '/contacts/groups/',
        'upload' => '/upload/'
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
        
        $response = $this->request->execute($this->actions['contacts'], 'GET', $data);
        return $response;
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
        
        $response = $this->request->execute($this->actions['contacts'], 'POST', $data);
        return $response;
    }
    
    /**
     * Update existing contact
     * @param int $contactId Contact id
     * @param array $options Array of options to update. Available keys: msisdn, firstname, lastname, group_id.
     */
    public function update($contactId, $options)
    {
        $response = $this->request->execute($this->actions['contacts'] . $contactId, 'PUT', $options);
        return $response;
    }
    
    /**
     * Delete contact
     * @param int $contactId Contact id
     * @return array
     */
    public function delete($contactId)
    {
        $response = $this->request->execute($this->actions['contacts'] . $contactId, 'DELETE');
        return $response;
    }
    
    /**
     * Get all contact groups belonging to your user.
     * @return array
     */
    public function groups()
    {
        $response = $this->request->execute($this->actions['groups'], 'GET');
        return $response;
    }
    
    /**
     * Get content of a contact group
     * @param int $groupId Group id
     * @return array
     */
    public function group($groupId)
    {
        $response = $this->request->execute($this->actions['groups'] . $groupId, 'GET');
        return $response;
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
        
        $response = $this->request->execute($this->actions['groups'], 'POST', $data);
        return $response;
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
        
        $response = $this->request->execute($this->actions['groups'] . $groupId, 'PUT', $data);
        return $response;
    }
    
    /**
     * Delete contact group
     * @param int $groupId
     * @return array
     */
    public function deleteGroup($groupId)
    {
        $response = $this->request->execute($this->actions['groups'] . $groupId, 'DELETE');
        return $response;
    }
    
    /**
     * Upload contacts stored in a csv file to some group
     * @param string $file Path to csv file
     * @param int $groupId Group id
     * @link http://api.beepsend.com/docs.html#contacts-upload More informations about file formating
     * @return array
     */
    public function upload($file, $groupId)
    {
        if (!file_exists($file)) {
            throw new FileNotFound('File you are trying to upload doesn\'t exists!');
        }
        
        /* Read file data */
        $data = file_get_contents($file);
        
        $response = $this->request->upload($this->actions['groups'] . $groupId . $this->actions['upload'], $data);
        return $response;
    }
    
}