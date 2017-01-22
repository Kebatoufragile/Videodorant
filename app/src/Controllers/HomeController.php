<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController
{
    private $view;
    private $logger;
	private $user;

    public function __construct($view, LoggerInterface $logger, $user)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->model = $user;
    }

    public function dispatch(Request $request, Response $response, $args)
    {
        if(isset($_SESSION['user'])){
          return $this->view->render($response, 'homepage.html.twig', array(
            'user' => $_SESSION['user']
          ));
        }else{
          return $this->view->render($response, 'homepage.html.twig');
        }
    }
}
