<?php

namespace App\Controllers;

use App\Models\Video;
use Illuminate\Database\Capsule\Manager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


define('TARGET', "../public/assets/video/");
define('MAX_SIZE', 2000000); //Taille max en octets du fichier
define('WIDTH_MAX', 1200);   //Largeur max de l'image en pixels
define('HEIGHT_MAX', 900);  //Hauteur max de l'image en pixels


final class uploadVidController extends AbstractController{

    protected $view;
    protected $logger;



    public function __construct($view){
      $this->view = $view;
    }

    public function dispatch(Request $request, Response $response, $args){
        if(isset($_SESSION['user'])){
          return $this->view['view']->render($response, 'videoUpload.html.twig', array(
            "user" => $_SESSION['user']
          ));
        }else{
          return $this->view['view']->render($response, 'homepage.html.twig', array(
            "error" => "Vous devez être connecté pour accèder à cette fonctionnalité"
          ));
        }
    }

    public function uploadVideo(){
      $tabExt = array('MP4', 'WebM'); //Extensions autorisées
      $infosVid = array();

      //Variables
      $extension = '';
      $message = 'L\'image a été ajoutée avec succès';
      $nomVideo = '';

      /*************************************************************************
       * Script d'upload
       ************************************************************************/

      if(!empty($_FILES)){
          //On vérifie si le champ de l'extension est rempli
          if( !empty($_FILES['video']['name'])){
              //Récupération de l'extension du fichier
              $extension = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);

              //On vérifie l'extension du fichier
              if(in_array(strtolower($extension), $tabExt)){
                  //On récupére les dimensions du fichier
                  $infosVid = getimagesize($_FILES['video']['tmp_name']);

                  //On vérifie le type de l'image
                  if($infosVid[2] >= 1 && $infosVid[2] <= 14){
                      //On vérifie les dimensions et taille de l'image
                      if(($infosVid[0]<=WIDTH_MAX) && ($infosVid[1]<=HEIGHT_MAX) && (filesize($_FILES['video']['tmp_name'])<=MAX_SIZE)){
                          //Parcours du tableau d'erreur
                          if(isset($_FILES['video']['error']) && UPLOAD_ERR_OK === $_FILES['video']['error']){
                              //On renomme le fichier
                              $nomVideo = md5(uniqid()).'.'.$extension;

                              //Si c'est OK, on test l'upload
                              if(move_uploaded_file($_FILES['video']['tmp_name'], TARGET.$nomVideo)){
                                  //insert database
                                  //test data

                                  $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
                                  $desc = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
                                  $userId = $_SESSION['user']->id;

                                  $vid = new Video();

                                  $vid->link = $nomVideo;
                                  $vid->title = $titre;
                                  $vid->description = $desc;
                                  $vid->userId = $userId;

                                  if($vid->save()){
                                    return $this->view['view']->render($response, 'videoUpload.html.twig', array(
                                      "success" => "Votre video a été enregistrée",
                                      "user" => $_SESSION['user']
                                    ));
                                  }else{
                                    return $this->view['view']->render($response, 'videoUpload.html.twig', array(
                                      "error" => "Erreur interne - Contactez un administrateur"
                                    ));
                                  }

                              }else{
                                  //Sinon on affiche une erreur système
                                  return $this->view['view']->render($response, "videoUpload.html.twig", array(
                                      "error" => "Problème d\'upload"
                                  ));
                                  //$message = 'Problème d\'upload.';
                              }
                          }else{
                            return $this->view['view']->render($response, "videoUpload.html.twig", array(
                              "error" => "Erreur interne - Contactez un administrateur"
                            ));
                              //$message = 'Erreur interne.';
                          }
                      }else{
                          //Sinon erreur sur les dimensions et taille de l'image
                          return $this->view['view']->render($response, "videoUpload.html.twig", array(
                            "error" => "La video est trop lourde"
                          ));
                          //$message = 'L\'image est trop grande. (Max : 900x1200)';
                      }
                  }else{
                      //Sinon erreur sur le type de l'image
                      return $this->view['view']->render($response, "videoUpload.html.twig", array(
                        "error" => "Le fichier n\'est pas une video"
                      ));
                      //$message = 'Le fichier n\'est pas une image .';
                  }
              }else{
                  //Sinon on affiche une erreur sur l'extension
                  return $this->view['view']->render($response, "videoUpload.html.twig", array(
                    "error" => "L\'extension du fichier n\'est pas correct"
                  ));
                  //$message = 'L\'extension du fichier n\'est pas correct.';
              }
          }else{
              //Sinon on affiche une erreur pour le champ vide
              return $this->view['view']->render($response, "videoUpload.html.twig", array(
                "error" => "Remplissez le champ"
              ));
              //$message = 'Remplissez le champ.';
          }
      }else{
        return $this->view['view']->render($response, 'videoUpload.html.twig', array(
          "error" => "Aucun fichier n\'a été envoyé"
        ));
        //$message = "Aucun fichier n'\a été envoyé";
    }
  }
}
