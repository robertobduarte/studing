<?php


abstract class IDao{

	protected $conex;
	
	function __construct(){
		
		$this->conex = Conex::doConnect();
	}

	abstract protected function buscar( $id );
	abstract protected function listar();
	abstract protected function inserir( IObject $objeto );
	abstract protected function editar( Iobject $objeto );
	

	
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
