<?php


namespace App\Controllers;

use App\Models\User;
use Illuminate\Database\Capsule\Manager;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


final class RegisterController extends AbstractController{

  protected $view;
  protected $logger;
  private $sentinel;

  public function __construct($view){
    parent::__construct($view);
    $this->sentinel = new Sentinel();
    $this->sentinel = $this->sentinel->getSentinel();
  }

  public function dispatch(Request $request, Response $response, $args){
        $this->view['view']->render($response, 'register.html.twig');
        return $response;
  }

  public function register(Request $request, Response $response){
    if(isset($_POST['identifiant']) && isset($_POST['password']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email'])){
      $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
      $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
      $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
      $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
      $identifiant = filter_var($_POST['identifiant'], FILTER_SANITIZE_STRING);

      if(is_null(User::where('email', 'like', $email)->first())){
          if($identifiant && $password && $prenom && $nom && $email){
            $credentials = [
              'password' => $password,
              'last_name' => $nom,
              'first_name' => $prenom,
              'email' => $email,
              'username' => $identifiant
            ];

            $this->sentinel->registerAndActivate($credentials);

            $this->view['view']->render($response, 'homepage.html.twig', array(
                'success' => "You have been successfully registered. You can now try log in."
            ));
          }
        }else{
          $this->view['view']->render($response, 'register.html.twig', array(
              'error' => 'Mail address already used.'
          ));
          }
        }else{
          $this->view['view']->render($response, 'register.html.twig', array(
              'error' => 'Unable to register you, informations are missing, please try again.',
              'pass' => $_POST['password'],
              'id' => $_POST['identifiant'],
              'nom' => $_POST['nom'],
              'prenom' => $_POST['prenom'],
              'email' => $_POST['email']
          ));
        }
      }

  public function dispatchSubmit(Request $request, Response $response, $args){
      $res = $this->register();
      switch($res) {
          case 2:
              $this->view['view']->render($response, 'register.html.twig', array(
                  'error' => 'Unable to register you, informations are missing, please try again.'
              ));
              break;
          case 3:
              $this->view['view']->render($response, 'homepage.html.twig', array(
                  'success' => "You have been successfully registered. You can now try log in."
              ));
              break;
          case 4:
              $this->view['view']->render($response, 'register.html.twig', array(
                  'error' => 'Mail address already used.'
              ));
              break;
          case 5:
              $this->view['view']->render($response, 'register.html.twig', array(
                  'error' => 'Username already used.'
              ));
              break;
          case 6:
              $this->view['view']->render($response, 'register.html.twig', array(
                  'error' => 'Passwords are differents.'
              ));
              break;
          default:
              $this->view['view']->render($response, 'register.html.twig', array(
                  'error' => 'Unable to register you, informations are wrong, please try again.'
              ));
              break;
      }
      return $response;
  }

}
