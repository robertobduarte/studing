<?php

class Usuario extends IObject{

	protected static $instances = array();
	protected $id;
	protected $nome;
	protected $m_pessoa; //Object Pessoa
	protected $usuario;
	protected $perfil;
	protected $perfil_nome;
	protected $permissao;


	public function __construct( $dados = null ){

		parent::__construct( $dados );
	}


	protected function defineTipos(){

	}

	public function listar( $id = null ){

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


	/*
	retorna um array com as permissões que o perfil possui. Pode ser o perfil geral (do sistema) ou o perfil de uma determinado domínio.
	*/
	public function getPermissoes( $perfil ){

		$daoUsuario = new DaoUsuario();

		$permissoes = $daoUsuario->getPermissoesPerfil( $perfil );

		$this->permissao = $permissoes;

	}


}
?>