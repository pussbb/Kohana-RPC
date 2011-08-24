<?php defined('SYSPATH') or die('No direct script access.');

Route::set('rpc', 'rpc')
    ->defaults(array(
        'controller' => 'rpc',
        'action'    => 'index'
    ));