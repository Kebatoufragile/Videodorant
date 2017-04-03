<?php

namespace App\Controllers;

use App\Models\Video;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

define('VIDEOPATH', "../public/assets/media/");
define('MINPATH', "../public/assets/img/miniatures/");

final class gestionVidController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view){
        parent::__construct($view);
    }

    public function dispatch(Request $request, Response $response, $args) {

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

                $video = Video::where('id', 'like', $_POST['idVideo'])->first();

                unlink(VIDEOPATH.$_POST['idVideo']);
                unlink(MINPATH.$video->minlink);

                Video::where('id', 'like', $_POST['idVideo'])->delete();



                $videos = Video::where('idUser', 'like', $_SESSION['user']->id)->get();

                return $this->view['view']->render($response, 'gestionVideo.html.twig', array(
                  'user' => $_SESSION['user'],
                  'videos' => $videos,
                  'success' => 'Votre vidéo a été supprimée avec succès'
                ));


            } else {

              $videos = Video::where('idUser', '=', $_SESSION['user']->id)->get();

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

    public function changeStateVid(Request $request, Response $response, $args){

        if(isset($_SESSION['user'])){

            if(isset($_POST['idVideo'])){

                $vid = Video::where('id', '=', $_POST['idVideo'])->first();

                if($vid->state === "publique"){
                  $vid->state = "privee";
                  $vid->save();
                } else {
                  $vid->state = "publique";
                  $vid->save();
                }

                $videos = Video::where('idUser', 'like', $_SESSION['user']->id)->get();

                return $this->view['view']->render($response, 'gestionVideo.html.twig', array(
                  'user' => $_SESSION['user'],
                  'videos' => $videos,
                  'success' => 'Votre vidéo a été passée en privée avec succès'
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
