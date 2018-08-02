<?php

return [

    'database' => [
        'connection' => config('database.default'),
    ],

    'header' => [
        /**
         * The name of the header that must contain the API key.
         */
        'name' => 'X-Authorization',

        /**
         * The format of the header value. This is a REGEX that will be matched against the value.
         * The first matched group (excluding the full match) is the token.
         */
        'format' => null,
    ],

];