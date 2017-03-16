<?php


namespace App\Controllers;

use App\Models\User;
use Illuminate\Database\Capsule\Manager;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

define('TARGET', "../public/assets/img/profil/");
define('MAX_SIZE', 2000000); //Taille max en octets du fichier
define('WIDTH_MAX', 1200);   //Largeur max de l'image en pixels
define('HEIGHT_MAX', 900);  //Hauteur max de l'image en pixels



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

            $user = User::where('email', 'like', $email)->first();
            $user->username = $identifiant;


            /***************** Ajout de la photo de profil *****************/


                  //Tableaux de données
            $tabExt = array('jpg','png','jpeg', 'gif'); //Extensions autorisées
            $infosImg = array();

            //Variables
            $extension = '';
            $message = '';
            $nomImage = '';

            /*************************************************************************
             * Creation du repertoire cible si inexistant
             ***********************************************************************/
            if(!is_dir(TARGET)){
                if(!mkdir(TARGET, 777)) {
                    exit('Erreur : le repertoire cible ne peut-être créé ! Vérifiez que vous disposez des droits suffisants pour le faire ou créez le manuellement !');
                }
            }

            /*************************************************************************
             * Script d'upload
             ************************************************************************/
            if(!empty($_POST)){
                //On vérifie si le champ de l'extension est rempli
                if( !empty($_FILES['picture']['name'])){
                    //Récupération de l'extension du fichier
                    $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);

                    //On vérifie l'extension du fichier
                    if(in_array(strtolower($extension), $tabExt)){
                        //On récupére les dimensions du fichier
                        $infosImg = getimagesize($_FILES['picture']['tmp_name']);

                        //On vérifie le type de l'image
                        if($infosImg[2] >= 1 && $infosImg[2] <= 14){
                            //On vérifie les dimensions et taille de l'image
                            if(($infosImg[0]<=WIDTH_MAX) && ($infosImg[1]<=HEIGHT_MAX) && (filesize($_FILES['picture']['tmp_name'])<=MAX_SIZE)){
                                //Parcours du tableau d'erreur
                                if(isset($_FILES['picture']['error']) && UPLOAD_ERR_OK === $_FILES['picture']['error']){
                                    //On renomme le fichier
                                    $nomImage = md5(uniqid()).'.'.$extension;

                                    //Si c'est OK, on test l'upload
                                    if(move_uploaded_file($_FILES['picture']['tmp_name'], TARGET.$nomImage)){
                                        //insert database
                                        //test data

                                        $user->profilPicLink = $nomImage;
                                        $user->save();

                                    }else{
                                        //Sinon on affiche une erreur système
                                        $message = 'A problem has occured during the upload.';
                                    }
                                }else{
                                    $message = 'An internal error prevented the upload.';
                                }
                            }else{
                                //Sinon erreur sur les dimensions et taille de l'image
                                $message = 'The picture is too big. (Max : 900x1200)';
                            }
                        }else{
                            //Sinon erreur sur le type de l'image
                            $message = 'The file you want to upload is not a picture.';
                        }
                    }else{
                        //Sinon on affiche une erreur sur l'extension
                        $message = 'The file extension is uncorrect.';
                    }
                }else{
                    //Sinon on affiche une erreur pour le champ vide
                    $message = 'Please fill the form.';
                }
            }
        }



            /***************************************************************/

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
