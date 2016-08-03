<?php

namespace Beepsend\Resource;

use Beepsend\Request;

/**
 * Class Error
 * @package Beepsend\Resource
 */
class Error
{
    /**
     * Beepsend request handler
     * @var \Beepsend\Request
     */
    private $request;

    /**
     * Actions to call
     * @var array
     */
    private $actions = array(
        'errors' => '/errors/'
    );

    /**
     * Init error resource
     * @param \Beepsend\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param null $id
     * @return array
     */
    public function get($id = null)
    {
        $url = $this->actions['errors'];

        if ($id) {
            $url .= (int)$id;
        }

        return $this->request->execute($url, 'GET');
    }

}