<?php

namespace App\Controllers;

use App\Models\Video;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


define('TARGET', "../public/assets/media/");
define('MIN_TARGET', "../public/assets/img/miniatures/");
define('MAX_SIZE', 2000000000); //Taille max en octets du fichier

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

    public function uploadVideo(Request $request, Response $response){
        $tabExt = array('mp4', 'webm'); //Extensions autorisées
        $tabExtMin = array('jpg','png','jpeg', 'gif');

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
                    //On récupére le poid de la video
                    $sizeVid = $_FILES['video']['size'];

                    //On vérifie le type de l'image
                    //if($sizeVid >= MAX_SIZE){
                    //On vérifie les dimensions et taille de l'image
                    //Parcours du tableau d'erreur
                    if(isset($_FILES['video']['error']) && UPLOAD_ERR_OK === $_FILES['video']['error']){
                        //On renomme le fichier
                        $nomVideo = md5(uniqid()).'.'.$extension;

                        //Si c'est OK, on test l'upload
                        if(move_uploaded_file($_FILES['video']['tmp_name'], TARGET.$nomVideo)){
                            //insert database
                            //test data

                            $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
                            $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
                            $userId = $_SESSION['user']->id;

                            $vid = new Video();

                            $vid->link = $nomVideo;
                            $vid->title = $titre;
                            $vid->description = $desc;
                            $vid->userId = $userId;
                            $vid->state = $_POST['statut'];

                            if(!empty($_FILES['miniature']['name'])){
                                $minExt = pathinfo($_FILES['miniature']['name'], PATHINFO_EXTENSION);

                                if(in_array(strtolower($minExt), $tabExtMin)){
                                    if(isset($_FILES['miniature']["error"]) && UPLOAD_ERR_OK === $_FILES['miniature']["error"]){
                                        $nomMin = md5(uniqid()).'.'.$minExt;
                                        if(move_uploaded_file($_FILES['miniature']['tmp_name'], MIN_TARGET.$nomMin)){
                                            $vid->minLink = $nomMin;
                                        }
                                    }
                                }
                            }

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
                    // }else{
                    //     //Sinon erreur sur les dimensions et taille de l'image
                    //     return $this->view['view']->render($response, "videoUpload.html.twig", array(
                    //       "error" => "La video est trop lourde"
                    //     ));
                    //     //$message = 'L\'image est trop grande. (Max : 900x1200)';
                    // }
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
