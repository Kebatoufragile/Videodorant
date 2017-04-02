<?php

namespace App\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class LogoutController extends AbstractController{

    protected $view;
    protected $logger;
    private $sentinel;

    public function __construct($view){

        parent::__construct($view);
        $this->sentinel = new Sentinel();
        $this->sentinel = $this->sentinel->getSentinel();

    }

    public function logout(Request $request, Response $response, $args){

        if(isset($_SESSION['user'])){
            $this->sentinel->logout($this->sentinel->getUser());
            session_destroy();

            $this->view['view']->render($response, 'homepage.html.twig', array(
                'success' => "You have successfully been logged out."
            ));
        }else
            $this->view['view']->render($response, 'homepage.html.twig', array());

        return $response;

    }
}

?>
