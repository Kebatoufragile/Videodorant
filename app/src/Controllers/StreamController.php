<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Stream;
use App\Models\Tags;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StreamController extends AbstractController{

    protected $view;
    protected $logger;


    public function __construct($view) {
        parent::__construct($view);
    }

    public function formStream(Request $request, Response $response, $args){

        if(isset($_SESSION['user'])){

            $categories = Categorie::all();

            return $this->view['view']->render($response, 'streamcreation.html.twig', array(
                'user' => $_SESSION['user'],
                'categories' => $categories
            ));

        }else{
            return $this->view['view']->render($response, 'homepage.html.twig', array(
                'error' => 'Vous n\'êtes pas connecté.'
            ));
        }

    }

    public function createStream(Request $request, Response $response, $args){

        if(isset($_SESSION['user'])){

            $stream = new Stream();
            $stream->idUser = $_SESSION['user']->id;
            $stream->name = filter_var($_POST['streamName'], FILTER_SANITIZE_STRING);

            if(isset($_POST['categories'])){
                // TODO foreach
            }

            $stream->save();

            if(isset($_POST['tags'])){
                $tags = explode(",", $_POST['tags']);

                foreach($tags as $k => $v){
                    if(!strcmp($v, "")){
                        $tag = new Tags();
                        $tag->idStream = $stream->id;
                        $tag->tag = $v;
                        $tag->save();
                    }
                }

            }

            return $response->withHeader('Location' , 'stream?idStream='.$stream->id);

        }else{
            return $this->view['view']->render($response, 'homepage.html.twig', array(
                'error' => 'Vous n\'êtes pas connecté.'
            ));
        }

    }

    public function showStream(Request $request, Response $response, $args){

        if(isset($_GET['idStream'])){

            $idStream = $_GET['idStream'];

            $stream = Stream::where('idStream', 'like', $idStream)->first();

            try{
              exec("nohup cvlc v4l2:///dev/video0 --sout \'#transcode{vcodec=mp4v, acodec=mp4a, mux=mp4, vb=1024}:rtp{sdp=rtsp://127.0.0.1:8554/$idStream}\' 1>/dev/null 2>/dev/null &", $output);
              var_dump($output);
            }catch(Exception $e){
              var_dump($e);
            }
            // stream existe
            if(!is_null($stream)){
                if(isset($_SESSION['user'])){
                    return $this->view['view']->render($response, 'stream.html.twig', array(
                        'user' => $_SESSION['user'],
                        'stream' => $stream,
                        'idStream' => $idStream
                    ));
                }else{
                    return $this->view['view']->render($response, 'stream.html.twig', array(
                        'stream' => $stream
                      ));
                }

            }else{ //stream existe pas

                if(isset($_SESSION['user'])){
                    return $this->view['view']->render($response, 'homepage.html.twig', array(
                        'user' => $_SESSION['user'],
                        'error' => 'Le stream que vous cherchez n\'existe pas.'
                    ));
                }else{
                    return $this->view['view']->render($response, 'homepage.html.twig', array(
                        'error' => 'Le stream que vous cherchez n\'existe pas.'
                    ));
                }

            }

        }else{

            if(isset($_SESSION['user'])){
                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'user' => $_SESSION['user'],
                    'error' => 'Le stream que vous cherchez n\'existe pas.'
                ));
            }else{
                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'error' => 'Le stream que vous cherchez n\'existe pas.'
                ));
            }

        }

    }

}
