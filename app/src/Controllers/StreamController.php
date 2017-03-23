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

        if(isset($_SESSION['user'])){

            /**
            $stream = new Stream();
            $stream->streamName = $_GET['streamname'];
            if(isset($_GET['categories']))
            $stream->categories = $_GET['categories'];
            if(isset($_GET['tags']
            $stream->tags = $GET['tags'];
            // TODO split les tags et ajouter bdd
             */
            $idStream = 1;

            return $response->withHeader('Location' , 'stream?idStream='.$idStream);

        }else{
            return $this->view['view']->render($response, 'homepage.html.twig', array(
                'error' => 'Vous n\'êtes pas connecté.'
            ));
        }

    }

    public function showStream(Request $request, Response $response, $args){

        if(isset($_GET['idStream'])){

            $idStream = $_GET['idStream'];

            //$stream = Stream::get($args['idStream'])->first();
            $stream = true;

            // stream existe
            if(!is_null($stream)){

                if(isset($_SESSION['user'])){
                    return $this->view['view']->render($response, 'stream.html.twig', array(
                        'user' => $_SESSION['user'],
                        'stream' => $stream
                    ));
                }else{
                    return $this->view['view']->render($response, 'stream.html.twig', array(
                        'stream' => $stream
                    ));
                }

            }else{ //stream existe pas

                if(isset($_SESSION['user'])){
                    return $this->view['view']->render($response, 'homepage.html.twig', array(
                        'user' => $_SESSION['user'],
                        'error' => 'Le stream que vous cherchez n\'existe pas.'
                    ));
                }else{
                    return $this->view['view']->render($response, 'homepage.html.twig', array(
                        'error' => 'Le stream que vous cherchez n\'existe pas.'
                    ));
                }

            }

        }else{

            if(isset($_SESSION['user'])){
                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'user' => $_SESSION['user'],
                    'error' => 'Le stream que vous cherchez n\'existe pas.'
                ));
            }else{
                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'error' => 'Le stream que vous cherchez n\'existe pas.'
                ));
            }

        }

    }

}