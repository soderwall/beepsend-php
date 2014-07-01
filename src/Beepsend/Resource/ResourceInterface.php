<?php

namespace Beepsend\Resource;

use Beepsend\Request;

interface ResourceInterface {
    
    public function __construct(Request $request);
    
}