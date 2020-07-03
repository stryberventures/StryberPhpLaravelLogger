<?php

return [
    'ignore_headers' => [
        'cookie',
        'authorization',
    ],
    'ignore_request_params' => [
        'password',
    ],
    'ignore_response_params' => [
        'token',
    ],
];
