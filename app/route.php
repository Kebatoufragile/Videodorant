<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:dispatch')->setName('homepage');

$app->get('/users', 'App\Controllers\UserController:dispatch')->setName('userpage');

$app->post('/login', 'App\Controllers\LoginController:login')->setName('login');

$app->get('/signin', 'App\Controllers\RegisterController:dispatch')->setName('signin');

$app->post('/register', 'App\Controllers\RegisterController:register')->setName('register');

$app->get('/logout', 'App\Controllers\LogoutController:logout')->setName('logout');

$app->get('/account', 'App\Controllers\ProfileController:displayProfile')->setName('profile');

$app->post('/account', 'App\Controllers\ProfileController:modifyProfile')->setName('profile');

$app->post('/modifypassword', 'App\Controllers\ProfileController:modifyPassword')->setName('profile');

$app->get('/catalog', 'App\Controllers\CatalogController:displayCatalog')->setName('catalog');

$app->get('/abonnements', 'App\Controllers\SubscribeController:dispatch')->setName('abonnements');

$app->get('/streamcreation', 'App\Controllers\StreamController:formStream')->setName('streamcreation');

$app->post('/streamcreation', 'App\Controllers\StreamController:createStream')->setName('streamcreation');

$app->get('/stream', 'App\Controllers\StreamController:showStream')->setName('stream');
