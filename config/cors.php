<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */

    'supportsCredentials' => false,
    'allowedOrigins' => [],
    'allowedOriginsPatterns' => ['/eashcm.dev/' , '/eas-hcm.com/', '/eas-hcm.tk/'],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['POST', 'GET', 'PUT', 'DELETE'],
    'exposedHeaders' => [],
    'maxAge' => 0,

];
