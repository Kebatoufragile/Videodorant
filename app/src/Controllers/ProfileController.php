<?php

namespace App\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
Use App\Models\User;

class ProfileController extends AbstractController{

    protected $view;
    protected $logger;
    private $sentinel;

    public function __construct($view){
        parent::__construct($view);
        $this->sentinel = new Sentinel();
        $this->sentinel = $this->sentinel->getSentinel();
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
          if(isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email'])){
            $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);



            if(is_null(User::where('email', "like", $email)->where('first_name', '!=', $prenom)->where('last_name', '!=', $nom)->first())){
              $usersession = $_SESSION['user'];
              $user = User::where('email', "like", $usersession->email)->first();
              $user->first_name = $prenom;
              $user->last_name = $nom;
              $user->email = $email;
              if($user->save()){
                $_SESSION['user'] = $user;
                $this->view['view']->render($response, 'profile.html.twig', array(
                    'user' => $_SESSION['user'],
                    'success' => 'Votre profil a été modifié.'
                ));
              }else{
                $this->view['view']->render($response, 'profile.html.twig', array(
                  'user' => $_SESSION['user'],
                  'error' => 'Erreur interne. Votre profil n\'a pas été modifié. Contactez un administrateur.'
                ));
              }
            }else{
              $this->view['view']->render($response, 'profile.html.twig', array(
                'user' => $_SESSION['user'],
                'error' => 'Votre nouvelle adresse mail est déjà utilisée'
              ));
            }
          }else{
            $this->view['view']->render($response, 'profile.html.twig', array(
              'user' => $_SESSION['user'],
              'error' => 'Vous devez remplir les champs pour modifier vos informations'
            ));
          }
        }else{
            $this->view['view']->render($response, 'homepage.html.twig', array(
                'error' => 'Vous n\'êtes pas connecté.'
            ));
        }
    }


    public function modifyPassword(Request $request, Response $response, $args){
        if(isset($_SESSION['user'])){
          if(isset($_POST['newPassword']) && isset($_POST['actualPassword']) && isset($_POST['newPasswordConfirm'])){
            if(password_verify(filter_var($_POST['actualPassword'], FILTER_SANITIZE_STRING), $_SESSION['user']->password)){
              if($_POST['newPassword'] == $_POST['newPasswordConfirm']){
                $credentials = [
                  'password' => filter_var($_POST['newPassword'], FILTER_SANITIZE_STRING)
                ];

                $user = $this->sentinel->findById($_SESSION["user"]->id);
                if($user = $this->sentinel->update($user, $credentials)){
                  $this->view['view']->render($response, 'profile.html.twig', array(
                    'user' => $_SESSION['user'],
                    'success' => 'Votre mot de passe a été modifié.'
                  ));
                }else{
                  $this->view['view']->render($response, 'profile.html.twig', array(
                    'user' => $_SESSION['user'],
                    'error' => "Erreur interne. Votre mot de passe n\'a pas été modifié. Contactez un administrateur."
                  ));
                }
              }else{
                $this->view['view']->render($response, 'profile.html.twig', array(
                    'user' => $_SESSION['user'],
                    'error' => "Mot de passe de confirmation incorrect"
                ));
              }
            }else{
              $this->view['view']->render($response, 'profile.html.twig', array(
                'user' => $user,
                'error' => 'Mot de passe incorrect'
              ));
            }
          }else{
            $this->view['view']->render($response, 'profile.html.twig', array(
              'user' => $_SESSION['user'],
              'error' => "Vous devez remplir les champs pour pouvoir modifier votre mot de passe"
            ));
          }
        }else{
            $this->view['view']->render($response, 'homepage.html.twig', array(
                'error' => 'Vous n\'êtes pas connecté.'
            ));
        }
    }

}
