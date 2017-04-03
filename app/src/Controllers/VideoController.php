<?php

namespace App\Controllers;

use App\Models\Video;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class VideoController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view) {
        parent::__construct($view);
    }

    public function showVideo(Request $request, Response $response, $args){

        if(isset($_GET['idVideo'])) {

            $video = Video::where('id', 'like', $_GET['idVideo'])->first();

            if(!is_null($video)){

                if(isset($_SESSION['user'])){

                    return $this->view['view']->render($response, 'video.html.twig', array(
                        'user' => $_SESSION['user'],
                        'video' => $video
                    ));

                }else{

                    return $this->view['view']->render($response, 'video.html.twig', array(
                        'video' => $video
                    ));

                }

            }else{

                if(isset($_SESSION['user'])){

                    return $this->view['view']->render($response, 'homepage.html.twig', array(
                        'user' => $_SESSION['user'],
                        'error' => 'La vidéo que vous cherchez n\'existe pas.'
                    ));

                }else{

                    return $this->view['view']->render($response, 'homepage.html.twig', array(
                        'error' => 'La vidéo que vous cherchez n\'existe pas.'
                    ));

                }
            }

        }else{

            if(isset($_SESSION['user'])){

                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'user' => $_SESSION['user'],
                    'error' => 'La vidéo que vous cherchez n\'existe pas.'
                ));

            }else{

                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'error' => 'La vidéo que vous cherchez n\'existe pas.'
                ));

            }

        }

    }

}

?>