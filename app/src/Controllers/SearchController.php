<?php

namespace App\Controllers;

use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


final class SearchController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view) {
        parent::__construct($view);
    }

    public function search(Request $request, Response $response, $args){

        if(isset($_GET['search'])){

            $search = '%'.filter_var($_GET['search'], FILTER_SANITIZE_STRING).'%';

            $users = User::where('username', 'like', $search)->get();

            if(isset($_SESSION['user'])){

                return $this->view['view']->render($response, 'search.html.twig', array(
                    'user' => $_SESSION['user'],
                    'users' => $users
                ));

            }else{

                return $this->view['view']->render($response, 'search.html.twig', array(
                    'users' => $users
                ));

            }

        }else{

            if(isset($_SESSION['user'])){

                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'user' => $_SESSION['user']
                ));

            }else{

                return $this->view['view']->render($response, 'homepage.html.twig', array());

            }

        }

    }

}