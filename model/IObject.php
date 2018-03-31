<?php

abstract class IObject{
	
	protected $tiposDeDados = array();
	protected $controller;


	function __construct( $dados = null ){
		
		if( isset( $dados['id'] ) && !empty( $dados['id'] ) ){

			$this->getObjeto( $dados['id'] );
		}

		$this->defineTipos();

	}


	abstract protected function getObjeto( $id );
	abstract protected function defineTipos();
	abstract protected function listar( $id = null );


	public function __set( $classe , $objeto ) {

	 	foreach ( $objeto as $key => $value ) {
	 		
	 		if( property_exists( $classe, $key ) ){

				$value = ( empty( $value ) ) ? NULL : $value;
				$this->$key = $value;				
	 		}
	 	}
    }


    public function __get( $name ) {

	 	if( property_exists( $this, $name ) ){

	 		return $this->$name;
	 	}
    }

}
?>