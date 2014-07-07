<?php

namespace Beepsend;

use Beepsend\Request;

interface ResourceInterface {
    
    public function __construct(Request $request);
    
}