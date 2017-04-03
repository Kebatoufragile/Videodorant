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

$app->get('/uploadVid', 'App\Controllers\uploadVidController:dispatch')->setName('uploadVid');

$app->post('/uploadVid', 'App\Controllers\uploadVidController:uploadVideo')->setName('uploadVid');

$app->get('/streamcreation', 'App\Controllers\StreamController:formStream')->setName('streamcreation');

$app->post('/streamcreation', 'App\Controllers\StreamController:createStream')->setName('streamcreation');

$app->get('/stream', 'App\Controllers\StreamController:showStream')->setName('stream');

$app->get('/channel', 'App\Controllers\ChannelController:showChannel')->setName('channel');

$app->post('/subscribe', 'App\Controllers\ChannelController:subscribe')->setName('subscribe');

$app->post('/unsubscribe', 'App\Controllers\ChannelController:unsubscribe')->setName('unsubscribe');

$app->get('/gestionVideos', 'App\Controllers\gestionVidController:dispatch')->setName('gestionVideos');

$app->post('/delVideo', 'App\Controllers\gestionVidController:supprimerVideo')->setName('delVideo');

$app->post('/changeStateVideo', 'App\Controllers\gestionVidController:changeStateVid')->setName('changeStateVideo');

$app->get('/video', 'App\Controllers\VideoController:showVideo')->setName('video');
