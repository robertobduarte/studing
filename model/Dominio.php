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
	private $imagem;
	private $diretorio;
	private $objetivoTipos = array();
	private $mensagem;
	private $objetivos = array(); //list de objetivos pai do domínio



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
									'imagem' => array( 'type' => false, 'mandatory' => false, 'size' => 50 ),
									'diretorio' => array( 'type' => false, 'mandatory' => true, 'size' => 50 ),
									'diretorio' => array( 'type' => false, 'mandatory' => false, 'size' => false )
									);

	}

	public function getDominiosUsuario(){

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
		//return $dados;
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
	


	/*public function __set( $classe , $dominio ) {

	 	foreach ( $dominio as $key => $value ) {

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
    }*/


    public function buttonNovoDominio( Session $m_session ){

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

    public function listDominios( Session $m_session ){

    	$this->getDominiosUsuario();

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
    Imprime o formulário para criar e editar um domínio
    Param: Object Session
    */
    public function showFormulario( Session $m_session ){

    	$form = '';

    	$form .= '<form id="dominio_' . $this->__get('id') . '" action="' . $this->__get('controller') . '" enctype="multipart/form-data" method="POST">';

			$form .= '<input type="hidden" name="id" value="' . $this->__get('id') . '">';
			$form .= '<input type="hidden" name="arquivo" value="' . $this->__get('imagem') . '">';
			$form .= '<input type="hidden" name="imagem" value="' . $this->__get('imagem') . '">';
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
									
							$form .= ( !empty( $this->__get('imagem') ) )? '<li class="iconeLink" data-toggle="modal" data-target="#modalDominio">' . $this->__get('imagem') . ' <i class="fa fa-file-image-o fa-lg" aria-hidden="true"></i></li>' : ''; 
								
						$form .= '</ul>';
					$form .= '</div>';

				$form .= '</div>';

			$form .= '<div class="col-md-3 col-sm-6 col-xs-12">';
						
				$disabled = ( !array_intersect( array( 'C', 'U' ), $m_session->getValue( 'permissoes' ) ) )? ' disabled ' : '';

	 			$form .= '<button type="button" class="btn btn-primary btn-cor-primary btn-100" ' . $disabled . ' id="salvarDominio_' . $this->__get('id') . '">Salvar</button>';
	 		$form .= '</div>';

	 		if( !empty( $this->__get('id') ) ){ 

				$form .= '<div class="col-md-3 col-sm-6 col-xs-12">';

					$disabled = ( !array_intersect( array( 'X' ), $m_session->getValue( 'permissoes' ) ))? ' disabled ' : '';

					$form .= '<button type="button" class="btn btn-danger btn-100" ' . $disabled . ' id="excluirDominio_' . $this->__get('id') . '">Excluir domínio</button>';
				$form .= '</div>';

	 		}

		$form .= '</form>';


		if( !empty( $this->__get('imagem') ) ){

			$form .= '<div class="modal fade" id="modalDominio" tabindex="-1" role="dialog" aria-labelledby="modalDominio">';

				$form .= '<div class="modal-dialog modal-lg" role="document">';

					$form .= '<div class="modal-content">';

						$form .= '<div class="modal-header">';
							$form .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
							$form .= '<h4 class="modal-title">Imagem</h4>';
						$form .= '</div>';
						$form .= '<div class="modal-body">';
							$form .= '<img src="../dominio/' . $this->__get('diretorio') . '/' . $this->__get('imagem') . '" class="img-responsive banner">';
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



    /*public function showModelosObjeto( Session $m_session ){


	    	$this->getModelosObjeto();
	    	$this->getListBanners();
	    	$this->getPesquisas();

	    	$table = '';	

	    	$table .= '<div class="col-md-12">';
	    		$table .= '<h3>Modelos de Objeto</h3>';
	    	$table .= '</div>';

	    	
	    	$disabled = ( !array_intersect( array( 'C', 'U', 'R' ), $m_session->getValue( 'permissoes' ) ) )? ' disabled ' : '';

	    	$table .= '<div class="col-md-3 col-md-offset-9 col-sm-5 col-sm-offset-7 col-xs-12">';
	    		$table .= '<a href="modelo.php?dmn='. $this->__get('id') . '">';
	    		$table .= '<button type="button" class="btn btn-100 btn-primary btn-cor-primary" role="button" ' . $disabled . ' id="novoModelo_' . $this->__get('id') . '">Novo Modelo</button>';
	    		$table .= '</a>';
	    	$table .= '</div>';

	    	$table .= '<div class="col-md-12 divesp"></div>';
	    		
	    	$table .= '<table class="table table-striped" id="listModelo_' . $this->__get('id') . '">';

		    	$table .= '<thead>';
		    		$table .= '<th style="width: 60%;">Modelo</th>';
		    		$table .= '<th style="width: 12%;">Questões</th>';
		    		$table .= '<th style="width: 10%;">Média</th>';
		    		$table .= '<th style="width: 6%;">Tentativas</th>';
		    		$table .= '<th style="width: 7%;">Peso</th>'; 		
		    	$table .= '</thead>';

		    	$table .= '<tbody>';

		    	if( !empty( $this->modelosObjeto ) ){

		    		foreach ( $this->modelosObjeto as $modelo ) {  

		    			$table .= '<tr id="tr_' . $modelo->__get('id') . '">';
		    				$table .= '<td><a href="modelo.php?mdl=' . $modelo->__get('id') . '" class="" >' . $modelo->__get('nome') . '</a></td>';
		    				$table .= '<td>' . $modelo->__get('num_questoes') . '</td>';
				    		$table .= '<td>' . $modelo->__get('media') . '</td>';
				    		$table .= '<td>' . $modelo->__get('tentativas') . '</td>';			    		
				    		$table .= '<td>' . $modelo->__get('peso') . '</td>';			    		
				    	$table .= '</tr>';

		    		}

	    		}else{

	    			$table .= '<tr>';
		    			$table .= '<td colspan="5"><div class="col-md-12 alert alert-warning"><p>Não existem modelos cadastrados.</p></td></div>';
					$table .= '</tr>';
	    	}

	    		$table .= '</tbody>';
	    	$table .= '</table>';	    	

	    	echo $table; 

    }*/



}
?>