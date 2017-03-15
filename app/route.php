<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:dispatch')->setName('homepage');

$app->get('/users', 'App\Controllers\UserController:dispatch')->setName('userpage');

$app->post('/login', 'App\Controllers\LoginController:login')->setName('login');

$app->get('/signin', 'App\Controllers\RegisterController:dispatch')->setName('signin');
