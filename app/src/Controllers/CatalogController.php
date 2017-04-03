<?php


namespace App\Controllers;

use App\Models\Abonnements;
use App\Models\User;
use App\Models\Video;
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
        $abonnements = Abonnements::where('id', 'like', $_SESSION['id'])->get();

        $videos = array();

        foreach($abonnements as $k=>$v){
            $video = Video::where('idUser', 'like', $v->idUser)->last();
            array_push($videos, $video);
        }

        return $videos;
    }

    private function getVideosWhenNotLogged(){
        return Video::orderBy('dateAjout', 'desc')->take(30)->get();
    }

}
