<?php

defined('C5_EXECUTE') or die("Access Denied.");
/**
 * @var $router \Concrete\Core\Routing\Router
 */
$router->get('/info', 'Info::view');
$router->post('/info/clear-cache', 'Info::clearCache');
