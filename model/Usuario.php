<?php

class Usuario extends IObject{

	protected static $instances = array();
	protected $id;
	protected $nome;
	protected $m_pessoa; //Object Pessoa
	protected $usuario;
	protected $perfil;
	protected $perfil_nome;
	protected $perfil_dominio = array();
	protected $permissoes = array();


	public function __construct( $dados = null ){

		parent::__construct( $dados );
	}


	protected function defineTipos(){

	}

	protected function listar( $id = null ){

	}

	public function getObjeto( $id ){

		$daoUsuario = new DaoUsuario();

		$usuario = $daoUsuario->buscar( $id );
		
		$this->__set( $this, $usuario );

	}


	public function getUsuarioByUser( $userName ){

		$daoUsuario = new DaoUsuario();

		$usuario = $daoUsuario->getUsuarioByUser( $userName );
	

		$this->__set( $this, $usuario );

	}


	
	public function getUsuarios() {

		$daoUsuario = new DaoUsuario();

		$dados = $daoUsuario->listar();

		foreach ($dados as $value) {
			
			$usuario = new Usuario();
			$usuario->__set( $usuario, $value );
			$this::$instances[] = $usuario;   

		}
	}





}
?>