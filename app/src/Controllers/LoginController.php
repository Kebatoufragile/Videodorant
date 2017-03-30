<?php

namespace App\Controllers;

use App\Models\User;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LoginController extends AbstractController{

    protected $view;
    protected $logger;
    private $sentinel;

    public function __construct($view){
        parent::__construct($view);
        $this->sentinel = new Sentinel();
        $this->sentinel = $this->sentinel->getSentinel();
    }



    public function login(Request $request, Response $response, $args){
        if(isset($_POST['email']) && isset($_POST['password'])){
            $credentials = [
                'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                'password' => filter_var($_POST['password'], FILTER_SANITIZE_STRING)
            ];

            $userInterface = $this->sentinel->authenticate($credentials);

            if($userInterface instanceof UserInterface){
                $this->sentinel->login($userInterface, true);

                $user = User::where('id', 'like', $userInterface->getUserId())->first();

                $_SESSION['userId'] = $userInterface->getUserId();
                $_SESSION['user'] = $user;

                return $this->view['view']->render($response, 'profile.html.twig', array(
                    'success' => 'Connexion réussie.',
                    'user' => $_SESSION['user']
                ));
            }else{
                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'error' => 'Connexion impossible, email ou mot de passe incorrects.'
                ));
            }
        }else{
            return $this->view['view']->render($response, 'homepage.html.twig', array(
                'error' => 'Erreur, remplissez les champs obligatoires.'
            ));
        }
    }
}
