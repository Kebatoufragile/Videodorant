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

  public fuction register(){
    if(isset($_POST['username']) && isset($_POST['password'] && isset($_POST['nom'] && isset($_POST['prenom']) && isset($_POST['email']))){
      $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
      $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
      $pass_confirm = filter_var($_POST['password_confirm'], FILTER_SANITIZE_STRING);
      $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
      $prenom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
      $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

      if(is_null(User::where('email', 'like', $email)->first())){
        if(!strcmp($password, $pass_confirm)){
          if($username && $password && $prenom && $nom && $email){
            $credentials = [
              'password' => $password,
              'last_name' => $nom,
              'fullname' => $prenom,
              'email' => $email
            ];

            $this->sentinel->registerAndActivate($credentials);

            return 3;
          }else{
            return 1;
          }
        }else{
          return 6;
        }else{
          return 5;
        }else{
          return 4,
        }else{
          return 2;
        }
      }
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
