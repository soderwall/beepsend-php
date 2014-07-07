<?php

namespace Beepsend;

use Beepsend\Request;

/**
 * Resource interface
 * @package Beepsend
 */
interface ResourceInterface {
    
    public function __construct(Request $request);
    
}