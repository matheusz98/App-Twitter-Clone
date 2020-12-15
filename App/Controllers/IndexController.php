<?php

namespace App\Controllers;

//Recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		//Teste ternário caso o parâmetro for enviado
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';

		$this->render('index');
	}

	public function inscreverse() {
		
		//Valores default
		$this->view->usuario = array(
			'nome' => '', 
			'email' => '', //Passando os dados para a view trabalhar 
			'senha' => '',
		);

		$this->view->erroCadastro = false;
		$this->render('inscreverse');
	}

	public function registrar() {

		//Recebendo e setando os dados do formulário

		$usuario = Container::getModel('Usuario');

		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha'])); //Utilizando md5 para criptografar e proteger a senha do usuário

		//Sucesso

		if ($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0) {

			$usuario->salvar();

			$this->render('cadastro'); //Se estiver tudo certo, leva pra mensagem de sucesso
		
		//Erro
		} else {

			$this->view->usuario = array(
				'nome' => $_POST['nome'],
				'email' => $_POST['email'], //Retornando as informações dos campos para que o usuário não precise redigitar os dados
				'senha' => $_POST['senha'],
			);

			$this->view->erroCadastro = true;

			$this->render('inscreverse'); //Caso contrário, leva de volta pra tela de cadastro

		}

	}

}


?>