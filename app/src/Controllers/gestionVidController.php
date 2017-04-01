<?php

namespace App\Controllers;

use App\Models\Video;
use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class gestionVidController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view){
        parent::__construct($view);
    }

    public function dispatch(Request $request, Response $response, $args){

        if(isset($_SESSION['user'])){

          $videos = Video::where('idUser', 'like', $_SESSION['user']->id);

          return $this->view['view']->render($response, 'gestionVideo.html.twig', array(
            'user' => $_SESSION['user'],
            'videos' => $videos
          ));

        }else{

          return $this->view['view']->render($response, 'gestionVideo.html.twig', array(
            'error' => 'Vous devez être connecté pour accéder à cette page'
          ));

        }

    }

    public function supprimerVideo(Request $request, Response $response, $args){

        if(isset($_SESSION['user'])){

            if(isset($_POST['idVideo'])){

                $supp = Video::where('id', 'like', $_POST['idVideo'])->delete();

                $videos = Video::where('idUser', 'like', $_SESSION['user']->id);

                return $this->view['view']->render($response, 'gestionVideo.html.twig', array(
                  'user' => $_SESSION['user'],
                  'videos' => $videos,
                  'success' => 'Votre vidéo a été supprimée avec succès'
                ));


            } else {

              $videos = Video::where('idUser', 'like', $_SESSION['user']->id);

              return $this->view['view']->render($response, 'gestionVideo.html.twig', array(
                'user' => $_SESSION['user'],
                'videos' => $videos,
                'error' => 'Impossible de trouver la vidéo'
              ));

            }


        } else {

          return $this->view['view']->render($response, 'gestionVideo.html.twig', array(
            'error' => 'Vous devez être connecté pour effectuer cette action'
          ));

        }

    }

}
