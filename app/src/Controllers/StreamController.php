<?php

namespace App\Controllers;

use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class StreamController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view) {
        parent::__construct($view);
    }

    public function formStream(Request $request, Response $response, $args){

        if(isset($_SESSION['user'])){

            $categories = array();
            // TODO recuperer catégories

            return $this->view['view']->render($response, 'streamcreation.html.twig', array(
                'user' => $_SESSION['user'],
                'categories' => $categories
            ));
        }else{
            return $this->view['view']->render($response, 'homepage.html.twig', array(
                'error' => 'Vous n\'êtes pas connecté.'
            ));
        }

    }

    public function createStream(Request $request, Response $response, $args){

        // TODO
        
    }

}