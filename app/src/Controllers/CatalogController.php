<?php


namespace App\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


final class CatalogController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view) {
        parent::__construct($view);
    }

    public function displayCatalog(Request $request, Response $response, $args){
        if(isset($_SESSION['user'])) {

            return $this->view['view']->render($response, 'catalog.html.twig', array(
                'user' => $_SESSION['user'],
                'catalog' => $this->getVideosWhenLogged()
            ));

        }else {

            return $this->view['view']->render($response, 'catalog.html.twig', array(
                'catalog' => $this->getVideosWhenNotLogged()
            ));

        }

    }

    private function getVideosWhenLogged(){
        // TODO videos récentes des abonnements
        return array();
    }

    private function getVideosWhenNotLogged(){
        // TODO videos populaires
        $videos = [1,2,3,4,5,6,7,8,9,10];
        return $videos;
    }

}