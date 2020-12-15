<?php

namespace App; //Estabelecendo o namespace

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() { //Criando as rotas

		$routes['home'] = array( //Rota home
			'route' => '/', //Rota
			'controller' => 'indexController', //Controlador
			'action' => 'index' //Ação do controlador
		);

		$routes['inscreverse'] = array(
			'route' => '/inscreverse',
			'controller' => 'indexController',
			'action' => 'inscreverse'
		);

		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'indexController',
			'action' => 'registrar'
		);

		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthController', //Controlador de autenticação
			'action' => 'autenticar'
		);

		$routes['timeline'] = array(
			'route' => '/timeline',
			'controller' => 'AppController', //Controlador da aplicação
			'action' => 'timeline'
		);

		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'AuthController',
			'action' => 'sair'
		);

		$routes['tweet'] = array(
			'route' => '/tweet',
			'controller' => 'AppController',
			'action' => 'tweet'
		);

		$routes['remover_tweet'] = array(    
			'route' => '/remover_tweet',    
			'controller' => 'AppController',    
			'action' => 'removerTweet'
			);

		$routes['quem_seguir'] = array(
			'route' => '/quem_seguir',
			'controller' => 'AppController',
			'action' => 'quemSeguir'
		);

		$routes['acao'] = array(
			'route' => '/acao',
			'controller' => 'AppController',
			'action' => 'acao'
		);

		$this->setRoutes($routes);
	}

}

?>