<?php

return [

    'database' => [
        'connection' => env('API_KEY_DB_CONNECTION', config('database.default')),
    ],

    'header' => [
        /**
         * The name of the header that must contain the API key.
         */
        'name' => env('API_KEY_HEADER_NAME', 'X-Authorization'),

        /**
         * The format of the header value. This is a REGEX that will be matched against the value.
         * The first matched group (excluding the full match) is the token.
         */
        'format' => env('API_KEY_HEADER_FORMAT', null),
    ],

];