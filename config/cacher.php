<?php

return [

    'routes' => (bool) env('CACHER_ROUTES', true),

    'prefix' => env('CACHER_PREFIX', 'admin'),

    'middlewares' => explode(',', env('CACHER_MIDDLEWARES', 'web,auth:web')),

];
