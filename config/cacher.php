<?php

return [

    'routes' => env('CACHER_ROUTES', true),

    'prefix' => env('CACHER_RREFIX', 'admin'),

    'middlewares' => explode(',', env('CACHER_MIDDLEWARES', 'web,auth:web')),

];
