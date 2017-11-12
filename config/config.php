<?php

Config::set('site_name', 'My news site');

Config::set('languages', array('en', 'ru'));

//Roures. Route name => method prefix
Config::set('routes', array(
    'default' => '',
    'admin' => 'admin_',
));

Config::set('default_route', 'default');
Config::set('default_language', 'en');
Config::set('default_controller', 'pages');
Config::set('default_action', 'index');

Config::set('db.host', 'localhost');
Config::set('db.login', 'root');
Config::set('db.password', '');
Config::set('db.db_name', 'MVC');

Config::set('salt', 'afew5agag89agrg9blbp943ppv8v');