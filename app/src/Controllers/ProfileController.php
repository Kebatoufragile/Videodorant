<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ProfileController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view){
        parent::__construct($view);
    }

    public function displayProfile(Request $request, Response $response, $args){
        if(isset($_SESSION['user'])){
            $this->view['view']->render($response, 'profile.html.twig', array(
                'user' => $_SESSION['user']
            ));
        }else{
            $this->view['view']->render($response, 'homepage.html.twig', array(
                "error" => 'Utilisateur non connecté'
            ));
        }
    }


    public function modifyProfile(Request $request, Response $response, $args){

        if(isset($_SESSION['user'])){
        // TODO
            $this->view['view']->render($response, 'profile.html.twig', array(
                'user' => $_SESSION['user'],
                'success' => 'Votre profil a été modifié.'
            ));

        }else{
            $this->view['view']->render($response, 'homepage.html.twig', array(
                "error" => 'Vous n\'êtes pas connecté'
            ));
        }
    }


    public function modifyPassword(Request $request, Response $response, $args){
//TODO
        if(isset($_SESSION['user'])){
            $this->view['view']->render($response, 'profile.html.twig', array(
                'user' => $_SESSION['user'],
                'success' => 'Votre mot de passe a été modifié.'
            ));

        }else{
            $this->view['view']->render($response, 'homepage.html.twig', array(
                "error" => 'Vous n\'êtes pas connecté'
            ));
        }
    }

}
