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

$app->get('/profil', 'App\Controllers\ProfilController:displayProfile')->setName('profil');
