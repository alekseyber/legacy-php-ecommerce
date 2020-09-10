<?php

use ishop\Router;

Router::add('^news/(?P<alias>[a-z0-9-]+)/?$', ['controller' => 'News', 'action' => 'view']);

Router::add('^utm_.+$', ['controller' => 'Main', 'action' => 'index']);

Router::add('^page/(?P<alias>[a-z0-9-]+)/?$', ['controller' => 'Page', 'action' => 'view']);
Router::add('^product/(?P<alias>[a-z0-9-]+)/?$', ['controller' => 'Product', 'action' => 'view']);
Router::add('^category/(?P<alias>[a-z0-9-]+)/?$', ['controller' => 'Category', 'action' => 'view']);
Router::add('^citypage/(?P<alias>[a-z0-9-]+)/?$', ['controller' => 'Citypage', 'action' => 'view']);

// default routes
Router::add('^' . ADMIN_PREFIX . '$', ['controller' => 'Main', 'action' => 'index', 'prefix' => 'admin']);
Router::add('^' . ADMIN_PREFIX . '/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$', ['prefix' => 'admin']);

Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
