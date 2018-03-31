<?php

class Pessoa extends IObject{

	protected static $instances = array();
	protected $id;
	protected $nome;
	protected $email;
	protected $cpf;
	protected $telefone;
	protected $celular;
	protected $endereco;
	protected $bairro;
	protected $cep;
	protected $estado;


	public function __construct( $dados = null ){

		parent::__construct();
	}


	protected function defineTipos(){

	}


	public function getObjeto( $pessoa_id ){

		$daoPessoa = new DaoPessoa();

		$pessoa = $daoPessoa->buscar( $pessoa_id );
		$this->__set( $this, $usuario );

	}


	
	public function listar() {

		$daoPessoa = new DaoPessoa();

		$dados = $daoPessoa->listar();

		foreach ($dados as $value) {
			
			$pessoa = new Pessoa();
			$pessoa->__set( $pessoa, $value );
			$this::$instances[] = $pessoa;   

		}
	}





}
?>