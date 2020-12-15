<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model {

    private $id;
    private $nome; //Variáveis que representam as colunas do banco
    private $email;
    private $senha;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    //Método de salvar
    public function salvar() {

        $query = "INSERT INTO usuarios(nome, email, senha) VALUES 
            (:nome, :email, :senha)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();

        return $this;

    }

    //Validar o cadastro
    public function validarCadastro() {

        $valido = true;

        if (strlen($this->__get('nome')) < 3) { //Verifica se o campo contém até pelo menos 3 caracteres
            $valido = false; //Caso contrário, dá false
        }

        if (strlen($this->__get('email')) < 3) {
            $valido = false;
        }

        if (strlen($this->__get('senha')) < 3) {
            $valido = false;
        }

        return $valido;

    }

    //Recuperar usuário por e-mail
    public function getUsuarioPorEmail() {

        $query = "SELECT nome, email FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC); //Array associativo

    }

    //Autenticando usuário cadastrado
    public function autenticar() {

        $query = "SELECT id, nome, email FROM usuarios WHERE
            email = :email AND senha = :senha";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario['id'] != '' && $usuario['nome'] != '') { //Caso existir um índice id e um índice nome, podemos prosseguir
            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);
        }

        return $usuario;

    }

    //Pesquisando usuários
    public function getAll() {

        //Pesquisando usuários pelo nome
        $query = "SELECT u.id, u.nome, u.email, (SELECT COUNT(*) FROM usuarios_seguidores 
            AS US WHERE us.id_usuario = :id_usuario AND us.id_usuario_seguindo = u.id) AS seguindo_sn 
            FROM usuarios AS u WHERE u.nome like :nome AND u.id != :id_usuario";
        //A query também inclui a pesquisa de usuários que está seguindo ou não
        $stmt = $this->db->prepare($query);
        //Utilizando % (like) para facilitar a busca
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        //Selecionar todos os registros de usuários onde o id seja diferente do usuário autenticado
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    //Seguir usuário
    public function seguirUsuario($id_usuario_seguindo) {

        $query = "INSERT INTO usuarios_seguidores (id_usuario, id_usuario_seguindo) 
            VALUES (:id_usuario, :id_usuario_seguindo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $stmt->execute();

        return true;
        
    }

    //Deixar de seguir usuário
    public function deixarSeguirUsuario($id_usuario_seguindo) {

        $query = "DELETE FROM usuarios_seguidores WHERE id_usuario 
            = :id_usuario AND id_usuario_seguindo = :id_usuario_seguindo";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $stmt->execute();

        return true;

    }

    //Recuperar informações do usuário

    public function getInfoUsuario() {

        $query = "SELECT nome FROM usuarios WHERE id = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

    //Recuperar total de tweets

    public function getTotalTweets() {

        $query = "SELECT COUNT(*) AS total_tweet FROM tweets WHERE 
            id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }


    //Recuperar total de usuários que estamos seguindo

    public function getTotalSeguindo() {

        $query = "SELECT COUNT(*) AS total_seguindo FROM usuarios_seguidores
            WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

    //Recuperar total de seguidores

    public function getTotalSeguidores() {

        $query = "SELECT COUNT(*) AS total_seguidores FROM usuarios_seguidores
            WHERE id_usuario_seguindo = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

}

?>