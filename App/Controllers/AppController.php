<?php

namespace App\Controllers;

//Recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    //Simplificando o método de validar a autenticação do usuário
    public function validaAutenticacao() {
        
        //Verifica se a sessão está iniciada para publicar tweets
        session_start();

        if (!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') { //Verificando se os campos foram preenchidos (diferentes de vazio)
            //Te leva pra tela de erro caso algo estiver errado
            header('Location: /?login=erro'); 

        }
    }

    public function timeline() {

        $this->validaAutenticacao();
            
        //Recuperação dos tweets
        $tweet = Container::getModel('Tweet');

        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweets = $tweet->getAll();

        $this->view->tweets = $tweets;

        //Executando os métodos de recuperação das informações do usuário
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        //Renderiza a timeline caso tudo estiver certinho
        $this->render('timeline');

        
    }

    public function tweet() {

        $this->validaAutenticacao();
            
        $tweet = Container::getModel('Tweet');

        $tweet->__set('tweet', $_POST['tweet']);

        $tweet->__set('id_usuario', $_SESSION['id']); //Recuperando o tweet do usuário pela sessão de usuário

        $tweet->salvar();

        header('Location: /timeline'); //Redirecionando o usuário para a timeline após tweetar

    }

    //Sugestões de pessoas para seguir
    public function quemSeguir() {

        $this->validaAutenticacao();

        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

        $usuarios = array();

        //Se for diferente de vazio, procede com a pesquisa
        if ($pesquisarPor != '') {

            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pesquisarPor);
            $usuario->__set('id', $_SESSION['id']);
            $usuarios = $usuario->getAll();

        }

        $this->view->usuarios = $usuarios;

        $this->render('quemSeguir');

    }

    //Ação de seguir/deixar de seguir usuários
    public function acao() {

        $this->validaAutenticacao();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        //Estrutra de seguir/deixar de seguir usuário
        if ($acao == 'seguir') {

            $usuario->seguirUsuario($id_usuario_seguindo);

        } else if ($acao == 'deixar_de_seguir') {

            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        
        }

        //Redirecionando de volta pra página após seguir/deixar de seguir usuário
        header('Location: /quem_seguir');

    }

    public function removerTweet(){  

        $this->validaAutenticacao();    

        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $tweet = Container::getModel('Tweet');  

        $tweet->__set('id',$id);    

        $tweet->remover();    
        
        header('location: /timeline');

    }

}

?>