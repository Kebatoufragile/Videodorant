<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Abonnements;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SubscribeController extends AbstractController {

    protected $view;
    protected $logger;

    public function __construct($view){
        parent::__construct($view);
    }

    public function dispatch(Request $request, Response $response, $args) {
        if(isset($_SESSION['user'])){
          return $this->view['view']->render($response, 'abo.html.twig', array(
            'user' => $_SESSION['user'],
            'channels' => $this->displaySubscribes()
          ));
        }else{
          return $this->view['view']->render($response, 'abo.html.twig', array(
            'error' => 'Vous devez être connecté pour accéder à cette page'
          ));
        }

    }

    //à compléter/corriger plus tard
    public function displaySubscribes(){

      /*if(isset($_SESSION['user'])){
        $abos = Abonnements::where('user_id', 'like', $_SESSION['userId'])->get();*/
        $chan = [];

        /*foreach ($abos as $channel)
          $chan[] = Channels::where('id', 'like', $channel->channel_id)->first();*/

        return $chan;
    }


}

 ?>
