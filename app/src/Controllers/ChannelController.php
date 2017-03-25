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
      //On regarde si l'utilisateur est connecté
      if(isset($_SESSION['user'])){
        //On vérifie que l'id soit bien passé
        if(isset($_POST['idUser'])){

          echo $_POST['idUser'];
          $user = User::where('id', 'like', $_POST['idUser'])->get();
          echo $user;

          $videos = Video::where('userId', 'like', $_POST['idUser'])->get();
          //On vérifie que l'utilisateur ne s'abonne pas à lui-même
          if($_POST['idUser'] != $_SESSION['user']->id){

            $abo = Abonnements::where('idUser', 'like', $_POST['idUser'])->where('idAbonne', 'like', $_SESSION['user'])->count();
            //On vérifie que l'utilisateur ne se soit pas déjà abonné
            if($abo === 0){
              $a = new Abonnements();
              $a->idUser = $_POST['idUser'];
              $a->idAbonne = $_SESSION['user']->id;
              $a->save();

              $this->view["view"]->render($response, "channel.html.twig", array(
                'success' => 'Vous êtes désormais abonné à cette chaîne.',
                'channel' => $user,
                'video' => $videos
              ));

            } else {

              $this->view["view"]->render($response, "channel.html.twig", array(
                'error' => 'Vous êtes déjà abonné à cette chaîne.',
                'channel' => $user,
                'video' => $videos
              ));

            }
          } else {

            $this->view["view"]->render($response, "channel.html.twig", array(
              'error' => 'Vous ne pouvez pas vous abonner à vous même.',
              'channel' => $user,
              'video' => $videos
            ));

          }
        }
      } else {

        $this->view["view"]->render($response, "channel.html.twig", array(
          'error' => 'Vous devez être connecté pour vous abonner.'
        ));
        
      }
    }

}
