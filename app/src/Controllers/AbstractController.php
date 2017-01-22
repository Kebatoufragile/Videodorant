<?php

namespace App\Controllers,

abstract class AbstractController{
  protected $view;

  function __construct($view){
    $this->view = $view;
  }
}
