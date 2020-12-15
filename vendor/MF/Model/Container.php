<?php

namespace MF\Model;

use App\Connection;

class Container {

	 //Retornar o modelo solicitado já instanciado, inclusive com a conexão estabelecida

	public static function getModel($model) {
		$class = "\\App\\Models\\".ucfirst($model);
		$conn = Connection::getDb();

		return new $class($conn);
	}
}


?>