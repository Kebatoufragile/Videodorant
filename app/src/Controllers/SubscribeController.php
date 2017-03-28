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
            'users' => $this->displaySubscribes()
          ));
        }else{
          return $this->view['view']->render($response, 'abo.html.twig', array(
            'error' => 'Vous devez être connecté pour accéder à cette page'
          ));
        }

    }

    //à compléter/corriger plus tard
    public function displaySubscribes(){

      if(isset($_SESSION['user'])){
        $abos = Abonnements::where('idAbonne', 'like', $_SESSION['user']->id)->get();
        $chan = [];

        foreach ($abos as $abo)
          $chan[] = User::where('id', 'like', $abo->idUser)->first();

        return $chan;
      }
    }


}
