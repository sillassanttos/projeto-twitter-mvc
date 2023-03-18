<?php

  namespace App\Controllers;

  use MF\Controller\Action;

  use MF\Model\Container;

  class AppController extends Action {

    public function timeline() {

      $this->validaAutenticacao();

      $tweet = Container::getModel('Tweet');
      $tweet->__set('id_usuario', $_SESSION['id']);

      $this->view->tweets = $tweet->getAll();

      $this->render('timeline');

    }


    public function tweet() {

      $this->validaAutenticacao();

      $tweet = Container::getModel('Tweet');

      $tweet->__set('tweet', $_POST['tweet']);
      $tweet->__set('id_usuario', $_SESSION['id']);

      echo '<pre>';
      print_r($tweet);
      echo '</pre>';

      $tweet->salvar();

      header('Location: /timeline');

    }


    public function quemSeguir() {

      $this->validaAutenticacao();

      $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

      $usuarios = array();

      if (!empty($pesquisarPor)) {

        $usuario = Container::getModel('Usuario');

        $usuario->__set('nome', $pesquisarPor);

        $usuarios = $usuario->getAll();

      }

      $this->view->usuarios = $usuarios;

      $this->render('quemSeguir');

    }


    public function validaAutenticacao() {

      session_start();

      if (!isset($_SESSION['id']) || empty($_SESSION['id']) ||
          !isset($_SESSION['nome']) || empty($_SESSION['nome']) ) {

            header('Location: /?login=erro');

      }

    }

  }

?>