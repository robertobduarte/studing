<?php
include_once __DIR__ . "/../config.php";

class Dominio extends IObject {

	public static $instances = array();
	protected $tiposDeDados = array();
	protected $controller = '../controller/controllerDominio.php';
	private $id;
	private $nome;
	private $alias;
	private $descricao;
	private $diretorio;
	private $css;
	private $objetivoTipos = array();
	private $mensagem;
	private $objetivos = array(); //list de objetivos pai do domínio
	private $usuarios = array(); //list de usuários - usado para guardar os usuário vinculados a um dominio



	public function __construct( $dados = null ){

		$this->defineTipos();

		if( isset( $dados['id'] ) && !empty( $dados['id'] ) ){

			$this->getDominio( $dados['id'] );
		}
	}

	public function listar( $id = null ){}

	protected function defineTipos(){

		$this->tiposDeDados = array( 
									'id' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'nome' => array( 'type' => false, 'mandatory' => true, 'size' => 200 ),
									'alias' => array( 'type' => false, 'mandatory' => false, 'size' => 50 ),
									'diretorio' => array( 'type' => false, 'mandatory' => true, 'size' => 50 )
									);

	}

	public function getDominios(){ //retorna os domínios que o usuário logado é vinculado. Se ADM, retorna todos os domínios

		$daoDominio = new DaoDominio();

		if( !isset( $m_session ) ){

			$m_session = new Session();
		}

		$perfil = $m_session->getValue('perfil');
		$usuarioid = $m_session->getValue('usuarioid');

		if( in_array( $perfil, array( 'ADM' ) ) ){

			$dados = $daoDominio->getDominios();

		}else{

			$dados = $daoDominio->getDominiosUsuario( $usuarioid );

		}

		if( !empty( $dados ) ){

			foreach ( $dados as $value ) {
				
				$dominio = new Dominio();
				$dominio->__set( $dominio, $value );
				Dominio::$instances[] = $dominio; 
			}
		}

		return Dominio::$instances;
	}



	public function getDominio( $dominio_id ){

		$daoDominio = new DaoDominio();

		$dominio = $daoDominio->getDominioById( $dominio_id );
		$this->__set( $this, $dominio );

	}


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

	public function getObjeto( $dominio_id ){

		$daoDominio = new DaoDominio();

		$dominio = $daoDominio->buscar( $dominio_id );
		$this->__set( $this, $dominio );

	}
	

	/*
	Popula os tipos de objetivo cadastrados para o módulo ( curso, programa, volume, módulo, aula ...)
	*/
	public function getTiposObjetivos(){

		$daoDominio = new DaoDominio();
		$objetivoTipos = $daoDominio->getTiposObjetivos( $this->id );

		foreach ( $objetivoTipos as $objTipo ) {

			$this->objetivoTipos[] = array( 'id' => $objTipo['id'], 'nome' => $objTipo['objetivotipos'] );
		}
	}


	/*
	Retorna uma lista com objetivos PAI pertencentes ao domínio
	*/
	public function getObjetivosPai(){

		$daoObjetivo = new DaoObjetivo();
		$objetivos = $daoObjetivo->getObjetivosPai( $this->id );

		foreach ( $objetivos as $objetivo ) {

			$obj = new Objetivo();
			$obj->__set( $obj, $objetivo );

			$this->objetivos[] = $obj;
		}
		
	}
	


    public function buttonNovoDominio(){

    	$bts = '';
    	$bts .= '<div class="row">';

			$bts .= '<div class="col-md-12">';

				$bts .= '<div class="col-md-2 col-sm-4 col-xs-12">';

					$bts .= '<a href="dominio.php"><button type="button" class="btn btn-primary btn-cor-primary btn-100" id="novoDominio">Novo Dominio</button></a>';	
					
			 	$bts .= '</div>';

			 $bts .= '</div>';
						
		$bts .= '</div>';

		echo $bts;
    }

    public function listDominios(){

    	$this->getDominios();

    	if( !empty( $this::$instances ) ){

    		echo '<table class="table table-striped" id="">';

	    		echo '<thead>';
	    			echo '<th>Id</th>';
	    			echo '<th>Nome</th>';
	    			echo '<th>Descricao</th>';
	    		echo '</thead>';

	    		echo '<tbody>';

	    			foreach ( $this::$instances as $dominio ) {
	    			
		    			echo '<tr id="tr_' . $dominio->__get('id'). '">';
		    				echo '<td>' . $dominio->__get('id') . '</td>';
		    				echo '<td><a href="dominio.php?dmn=' . $dominio->__get('id') . '" class="" >' . $dominio->__get('nome') . '</a></td>';
		    				echo '<td>' . $dominio->__get('descricao') . '</td>';		    				
		    			echo '</tr>';

	    			}

	    		echo '</tbody>';

    		echo '</table>';

    	}else{

    		echo '<div class="col-md-12 alert alert-warning"><p>Não existem domínios cadastrados.</p></div>';
    	}

    }



    public function listUsuariosAdmd(){

    	$this->getDominios();

    	if( !empty( $this::$instances ) ){

    		//método para retornar os usuários pertencentes a um domínio //param perfil (ADMD ou STD)
    		//$this->getUsuariosDominio( 'ADMD' );

    		/*echo '<table class="table table-striped" id="">';

	    		echo '<thead>';
	    			echo '<th>Id</th>';
	    			echo '<th>Nome</th>';
	    			echo '<th>Descricao</th>';
	    		echo '</thead>';

	    		echo '<tbody>';

	    			foreach ( $this::$instances as $dominio ) {
	    			
		    			echo '<tr id="tr_' . $dominio->__get('id'). '">';
		    				echo '<td>' . $dominio->__get('id') . '</td>';
		    				echo '<td><a href="dominio.php?dmn=' . $dominio->__get('id') . '" class="" >' . $dominio->__get('nome') . '</a></td>';
		    				echo '<td>' . $dominio->__get('descricao') . '</td>';		    				
		    			echo '</tr>';

	    			}

	    		echo '</tbody>';

    		echo '</table>';*/

    	}else{

    		echo '<div class="col-md-12 alert alert-warning"><p>Não existem usuários ADMD vinculados a este domínio.</p></div>';
    	}

    }



    public function novo(){

    	$daoDominio = new DaoDominio();

    	$id = $daoDominio->inserir( $this );

		return $id;

    }

        public function editar(){

    	$daoDominio = new DaoDominio();

    	$retorno = $daoDominio->editar( $this );

		return $retorno;

    }


    public function remover(){

    	$daoDominio = new DaoDominio();

    	$retorno = $daoDominio->remover( $this->__get('id') );

		return $retorno;

    }


    /*
    Imprime o logo do domínio, caso exista
    */
    public function showLogo(){

    	if( file_exists( "../dominio/" . $this->__get('diretorio') . "/logo.png" ) ) {

			$logo .= '<div class="row">';
	    		$logo .= '<div class="col-md-3 col-sm-5 col-xs-10">';
					$logo .= '<img src="../dominio/' . $this->__get('diretorio') . '/logo.png" class="img-responsive banner">';
				$logo .= '</div>';
			$logo .= '</div>';
		}

		echo $logo;
    }


    /*
    Imprime o formulário para criar e editar um domínio
    Param: Object Session
    */
    public function showFormulario( Session $m_session ){

    	$form = '';

    	if( file_exists( "../dominio/" . $this->__get('diretorio') . "/logo.png" ) ) {

    		$form .= '<div class="row">';
	    		$form .= '<div class="col-md-3 col-sm-5 col-xs-10">';
					$form .= '<img src="../dominio/' . $this->__get('diretorio') . '/logo.png" class="img-responsive banner">';
				$form .= '</div>';
			$form .= '</div>';

			$form .= '<div class="col-md-12 divesp"></div>';
		}

    	$form .= '<form id="dominio_' . $this->__get('id') . '" action="' . $this->__get('controller') . '" enctype="multipart/form-data" method="POST">';

			$form .= '<input type="hidden" name="id" value="' . $this->__get('id') . '">';
			$form .= '<input type="hidden" name="action" value="salvar">';

			$form .= '<div class="row">';
					
				$form .= '<div class="col-md-12">';
					$form .= '<div class="form-group">';
						$form .= '<label for="nome">Nome</label>';
						$form .= '<input type="text" name="nome" class="form-control req" value="' . $this->__get('nome') . '" >';
					$form .= '</div>';
				$form .= '</div>';

				$form .= '<div class="col-md-4 col-sm-6 col-xs-12">';
					$form .= '<div class="form-group">';
						$form .= '<label for="alias">Alias</label>';
						$form .= '<input type="text" name="alias" class="form-control req" value="' . $this->__get('alias') . '" >';
					$form .= '</div>';
				$form .= '</div>';

				$disabled = ( !empty( $this->__get('id') ) )? ' disabled ' : '';

				$form .= '<div class="col-md-2 col-sm-4 col-xs-12">';
					$form .= '<div class="form-group">';
						$form .= '<label for="css">CSS</label>';
						$form .= '<input type="text" name="css" class="form-control" '. $disabled . ' value="' . $this->__get('css') . '" >';
					$form .= '</div>';
				$form .= '</div>';

				$form .= '<div class="col-md-2 col-sm-4 col-xs-12">';
					$form .= '<div class="form-group">';
						$form .= '<label for="diretorio">Diretório</label>';
						$form .= '<input type="text" name="diretorio" class="form-control req" '. $disabled . ' value="' . $this->__get('diretorio') . '" >';
					$form .= '</div>';
				$form .= '</div>';

				$form .= '<div class="col-md-12">';
					$form .= '<label for="descricao">Descrição</label>';
					$form .= '<textarea class="form-control" rows="4" name="descricao">' . $this->__get('descricao') . '</textarea>';
				$form .= '</div>';

			$form .= '<div class="col-md-12 divesp10"></div>';

				$form .= '<div class="col-md-12">';
							
					$form .= '<div class="col-md-2 col-sm-4 col-xs-12">';
						$form .= '<input type="button" class="btn btn-primary btn-100" id="enviar" value="Enviar Imagem">';
					    $form .= '<input type="file" name="file" class="oculta" value="">';
					$form .= '</div>';

					$form .= '<div class="col-md-12" id="arquivoDominio">';
						$form .= '<ul class="list-unstyled listFiles" id="listFile">';
									
							$form .= ( file_exists( "../dominio/" . $this->__get('diretorio') . "/logo.png" ) )? '<li class="iconeLink" data-toggle="modal" data-target="#modalDominio">Logo<i class="fa fa-file-image-o fa-lg" aria-hidden="true"></i></li>' : ''; 
								
						$form .= '</ul>';
					$form .= '</div>';

				$form .= '</div>';

			$m_autenticacao = new Autenticacao();

			$form .= '<div class="col-md-3 col-sm-6 col-xs-12">';
						
				$disabled = ( !$m_autenticacao->hasPermission( array( 'C', 'U' ) ) )? ' disabled ' : '';

	 			$form .= '<button type="button" class="btn btn-primary btn-cor-primary btn-100" ' . $disabled . ' id="salvarDominio_' . $this->__get('id') . '">Salvar</button>';
	 		$form .= '</div>';

	 		if( !empty( $this->__get('id') ) ){ 

				$form .= '<div class="col-md-3 col-sm-6 col-xs-12">';

					$disabled = ( !$m_autenticacao->hasPermission( array( 'D' ) ) )? ' disabled ' : '';

					$form .= '<button type="button" class="btn btn-danger btn-100" ' . $disabled . ' id="excluirDominio_' . $this->__get('id') . '">Excluir domínio</button>';
				$form .= '</div>';

	 		}

		$form .= '</form>';

		if( file_exists( "../dominio/" . $this->__get('diretorio') . "/logo.png" ) ) {

			$form .= '<div class="modal fade" id="modalDominio" tabindex="-1" role="dialog" aria-labelledby="modalDominio">';

				$form .= '<div class="modal-dialog modal-lg" role="document">';

					$form .= '<div class="modal-content">';

						$form .= '<div class="modal-header">';
							$form .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
							$form .= '<h4 class="modal-title">Imagem</h4>';
						$form .= '</div>';
						$form .= '<div class="modal-body">';
							$form .= '<img src="../dominio/' . $this->__get('diretorio') . '/logo.png" class="img-responsive banner">';
						$form .= '</div>';
													
						$form .= '<div class="modal-footer">';
							$form .= '<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>';
						$form .= '</div>';

					$form .= '</div>';

				$form .= '</div>';

			$form .= '</div>';
		}

		echo $form;
    }


}
?>