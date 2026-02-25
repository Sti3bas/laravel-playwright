<?php

return [
    'prefix' => env('PLAYWRIGHT_PREFIX', 'playwright'),
    'environments' => ['local', 'testing'],
    'secret' => env('PLAYWRIGHT_SECRET', null),
];
