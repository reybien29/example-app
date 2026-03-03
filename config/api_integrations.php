<?php

return [
    'internal' => [
        'token' => env('INTERNAL_API_TOKEN'),
    ],
    'external' => [
        'keys' => array_values(array_filter(array_map(
            static fn (string $key): string => trim($key),
            explode(',', (string) env('EXTERNAL_INTEGRATION_KEYS', ''))
        ))),
    ],
];
