<?php

return [
    'successfully' => ['rc' => '0200', 'message' => 'Successfully', 'data' => null],
    'create_successfully' => ['rc' => '0201', 'message' => 'Create successfully', 'data' => null],
    'update_successfully' => ['rc' => '0200', 'message' => 'Update successfully', 'data' => null],
    'delete_successfully' => ['rc' => '0204', 'message' => 'Delete successfully', 'data' => null],
    'invalid_data' => ['rc' => '0400', 'message' => 'The given data was invalid', 'data' => null],
    'exceed_the_limit' => ['rc' => '0140', 'message' => 'Exceed the limit', 'data' => null],
    'unauthenticated' => ['rc' => '0401', 'message' => 'Unauthenticated', 'data' => null],
    'incorrect_credential' => ['rc' => '0141', 'message' => 'The provided credentials are incorrect', 'data' => null],
    'forbidden' => ['rc' => '0403', 'message' => 'You are not authorized to access this resource', 'data' => null],
    'record_not_found' => ['rc' => '0404', 'message' => 'Record not found', 'data' => null],
    'url_not_reachable' => ['rc' => '0144', 'message' => 'URL not reachable', 'data' => null],
    'method_not_allowed' => ['rc' => '0405', 'message' => 'Method not allowed', 'data' => null],
    'too_many_request' => ['rc' => '0429', 'message' => 'Too many request', 'data' => null],
    'internal_server_error' => ['rc' => '0500', 'message' => 'Internal server error', 'data' => null],
];
