<?php

return [
    'name' => 'Menu',
    'menu-class' => 'menu',
    'menu-item-class' => 'menu-item',
    'cache-keys' => [
        'menus' => 'menus',
        'items' => 'items',
        'built' => 'built'
    ],
    'use-cache' => !env('APP_DEBUG')
];
