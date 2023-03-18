<?php

  namespace App\Models;

  use MF\Model\Model;

  class Usuario extends Model {

    private $id;
    private $nome;
    private $email;
    private $senha;


    public function __get($atributo) {

      return $this->$atributo;

    }


    public function __set($atributo, $valor) {

      $this->$atributo = $valor;

    }


    public function salvar() {

      $sql = "insert into usuarios(nome, email, senha) values(:nome, :email, :senha)";

      $stmt = $this->db->prepare($sql);

      $stmt->bindValue(':nome', $this->__get('nome'));
      $stmt->bindValue(':email', $this->__get('email'));
      $stmt->bindValue(':senha', $this->__get('senha'));

      $stmt->execute();

      return $this;

    }


    public function validarCadastro() {

      $valido = true;

      if ((strlen($this->__get('nome')) < 3) ||
           strlen($this->__get('email')) < 3 ||
           strlen($this->__get('senha')) < 3) {

        return false;

      }

      return $valido;

    }


    public function getUsuarioPorEmail() {

      $sql = "select nome, email from usuarios where email = :email";

      $stmt = $this->db->prepare($sql);

      $stmt->bindValue(':email', $this->__get('email'));

      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function autenticar() {

      $sql = "select id, nome, email from usuarios where email = :email and senha = :senha";

      $stmt = $this->db->prepare($sql);

      $stmt->bindValue(':email', $this->__get('email'));
      $stmt->bindValue(':senha', $this->__get('senha'));

      $stmt->execute();

      $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

      if (!empty($usuario['id']) && !empty($usuario['nome'])) {

        $this->__set('id', $usuario['id']);
        $this->__set('nome', $usuario['nome']);

      }

      return $this;

    }


    public function getAll() {

      $sql = "
        select id
              , nome
              , email
          from usuarios
          where nome like :nome
      ";

      $stmt = $this->db->prepare($sql);

      $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');

      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

  }


?>