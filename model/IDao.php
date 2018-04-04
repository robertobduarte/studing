<?php


abstract class IDao{

	protected $conex;
	
	function __construct(){
		
		$this->conex = Conex::doConnect();
	}

	abstract public function buscar( $id );
	abstract public function listar();
	abstract public function inserir( IObject $objeto );
	abstract public function editar( Iobject $objeto );
	

	
	protected function falha( $error ){

		$debug = true;

		if( $debug ){

			echo '<pre>';
			print_r( $error );
			echo '</pre>';
			exit();
		}

		return false;

	}


}
?>
