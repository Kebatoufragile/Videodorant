<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Models\Abonnements;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


final class ChannelController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view) {
        parent::__construct($view);
    }

    public function showChannel(Request $request, Response $response, $args){

        if(isset($_GET['idUser'])){

            $user = User::where('id', 'like', $_GET['idUser'])->first();

            if(!is_null($user)){

                $videos = Video::where('idUser', 'like', $_GET['idUser']);

                if(isset($_SESSION['user'])){

                    return $this->view['view']->render($response, 'channel.html.twig', array(
                        'user' => $_SESSION['user'],
                        'channel' => $user,
                        'videos' => $videos
                    ));

                }else{

                    return $this->view['view']->render($response, 'channel.html.twig', array(
                        'channel' => $user,
                        'videos' => $videos
                    ));

                }

            }else{

                if(isset($_SESSION['user'])){
                    return $this->view['view']->render($response, 'homepage.html.twig', array(
                        'user' => $_SESSION['user'],
                        'error' => 'La chaîne que vous cherchez n\'existe pas.'
                    ));
                }else{
                    return $this->view['view']->render($response, 'homepage.html.twig', array(
                        'error' => 'La chaîne que vous cherchez n\'existe pas.'
                    ));
                }

            }

        }else{

            if(isset($_SESSION['user'])){
                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'user' => $_SESSION['user'],
                    'error' => 'La chaîne que vous cherchez n\'existe pas.'
                ));
            }else{
                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'error' => 'La chaîne que vous cherchez n\'existe pas.'
                ));
            }

        }

    }

    public function subscribe(Request $request, Response $response, $args){
      if(isset($_SESSION['user'])){
        if(isset($_POST['idUser'])){
          $abo = Abonnements::where('idUser', 'like', $_POST['idUser'])->where('idAbonne', 'like', $_SESSION['user'])->count();
          if($abo != 1){
            $a = new Abonnements();
            $a->idUser = $_POST['idUser'];
            $a->idAbonne = $_SESSION['user'];
            $a->save();

            $this->view["view"]->render($response, "channel.html.twig", array(
              "success" => "Vous êtes désormais abonné à cette chaîne."
            ));
          } else {
            $this->view["view"]->render($response, "channel.html.twig", array(
              "error" => "Vous êtes déjà abonné à cette chaîne."
            ));
          }
        }
      } else {
        $this->view["view"]->render($response, "channel.html.twig", array(
          "error" => "Vous devez être connecté pour vous abonner."
        ));
      }
    }

}
