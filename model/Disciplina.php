<?php
include_once __DIR__ . "/../config.php";

class Disciplina extends IObject {

	public static $instances = array();
	protected $controller = '../controller/controller.php?c=disciplina';
	private $id;
	private $nome;
	private $descricao;
	private $dominio;
	private $competencias = array(); //array Objetcs Competencia
	

	public function __construct( $dados = null ){

		parent::__construct( $dados );
	}



	public function listar( $dominio = null ){

		if( $dominio == null ){

			$m_session = new Session;
			$dominio = $m_session->getValue('dominio');
		}
		
		$daoDisciplina = new DaoDisciplina();

		$disciplinas = $daoDisciplina->listarByDominio( $dominio );
		
		if( $disciplinas ){

			foreach ( $disciplinas as $value ) {

				$disciplina = new Disciplina();
				$disciplina->__set( $disciplina, $value );
		
				Disciplina::$instances[] = $disciplina; 
			}
		}

		return Disciplina::$instances;

	}


	public function listarDisponiveisObjetivo( $dominio, $objetivo ){

		$daoDisciplina = new DaoDisciplina();

		$disciplinas = $daoDisciplina->listarDisponiveisObjetivo( $dominio, $objetivo );
		
		if( $disciplinas ){

			foreach ( $disciplinas as $value ) {

				$disciplina = new Disciplina();
				$disciplina->__set( $disciplina, $value );
		
				Disciplina::$instances[] = $disciplina; 
			}
		}

		return Disciplina::$instances;

	}


	public function listarUtilizadasObjetivo( $objetivo ){

		$daoDisciplina = new DaoDisciplina();

		$disciplinas = $daoDisciplina->listarUtilizadasObjetivo( $objetivo );
		
		$arrayDisciplinas = array();

		if( $disciplinas ){

			foreach ( $disciplinas as $value ) {

				$disciplina = new Disciplina();
				$disciplina->__set( $disciplina, $value );
		
				$arrayDisciplinas[] = $disciplina; 
			}
		}

		return $arrayDisciplinas;

	}


	public function addDisciplinaObjetivo( $objetivo ){

		$daoDisciplina = new DaoDisciplina();

		$retorno = $daoDisciplina->addDisciplinaObjetivo( $this->__get('id'), $objetivo );
		
		return $retorno;

	}

	
	public function rmDisciplinaObjetivo( $objetivo ){

		$daoDisciplina = new DaoDisciplina();

		$retorno = $daoDisciplina->rmDisciplinaObjetivo( $this->__get('id'), $objetivo );
		
		return $retorno;

	}



	protected function defineTipos(){

		$this->tiposDeDados = array( 
									'id' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'nome' => array( 'type' => false, 'mandatory' => true, 'size' => 200 ),
									'descricao' => array( 'type' => false, 'mandatory' => false, 'size' => false ),
									'dominio' => array( 'type' => 'int', 'mandatory' => true, 'size' => false )
									);

	}


	public function getObjeto( $disciplina_id ){

		$daoDisciplina = new DaoDisciplina();

		$disciplina = $daoDisciplina->buscar( $disciplina_id );

		$this->__set( $this, $disciplina );

		$this->getCompetencias();

	}


	public function getCompetencias( $disciplina_id = null ){

		$disciplina_id = ( $disciplina_id == null )? $this->__get('id') : $disciplina_id;

		$m_competencia = new Competencia();

		$competencias = $m_competencia->getCompetenciaByDisciplina( $disciplina_id );

		$this->competencias = array();
		$this->competencias = $competencias;

	}



	public function getArrayCompetencias(){

		$competencias = array();

		if( !empty( $this->competencias ) ){

			foreach ( $this->competencias as $competencia ) {
	    		
	    		$competencias[] = array( 'id' => $competencia->__get('id'), 'nome' => $competencia->__get('nome'), 'disciplina' => $competencia->__get('disciplina') );
	    	}
		}

		return $competencias;
		
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
	

    public function buttonNovaDisciplina(){

    	$bts = '';
    	$bts .= '<div class="row">';

			$bts .= '<div class="col-md-12">';

				$bts .= '<div class="col-md-2 col-sm-4 col-xs-12">';

					$bts .= '<button type="button" class="btn btn-primary btn-cor-primary btn-100" id="novaDisciplina">Nova Disciplina</button>';	
					
			 	$bts .= '</div>';

			 $bts .= '</div>';
						
		$bts .= '</div>';

		echo $bts;
    }
	

    public function listDisciplinas(){

    	if( !empty( $this::$instances ) ){

    		echo '<table class="table table-striped" id="listDisciplinas">';

	    		echo '<thead>';
	    			echo '<th>Id</th>';
	    			echo '<th>Nome</th>';
	    			echo '<th>Descricao</th>';
	    		echo '</thead>';

	    		echo '<tbody>';

	    			foreach ( $this::$instances as $disciplina ) {
	    			
		    			echo '<tr id="tr_' . $disciplina->__get('id'). '">';
		    				echo '<td>' . $disciplina->__get('id') . '</td>';
		    				echo '<td><a href="#" id="disciplina_' . $disciplina->__get('id') . '">' . $disciplina->__get('nome') . '</a></td>';
		    				echo '<td>' . $disciplina->__get('descricao') . '</td>';		    				
		    			echo '</tr>';

	    			}

	    		echo '</tbody>';

    		echo '</table>';

    	}else{

    		echo '<div class="col-md-12 alert alert-warning"><p>Não existem disciplina cadastradas.</p></div>';
    	}


    	$this->showModalFormulario();

    }




    public function showModalFormulario(){

    	$m_autenticacao = new Autenticacao();

    	$form = '';
    	$form .= '<div class="modal fade" id="modalDisciplina" tabindex="-1" role="dialog" aria-labelledby="modalDisciplina">';

				$form .= '<div class="modal-dialog" role="document">';

					$form .= '<div class="modal-content">';
						
						$form .= '<div class="modal-header">';
					        $form .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
					        $form .= '<h4 class="modal-title" id="exampleModalLabel">Disciplina</h4>';
					    $form .= '</div>';
					    
					    $form .= '<div class="modal-body">';

						$form .= '<form id="form_disciplina" action="' . $this->__get('controller') . '" enctype="multipart/form-data" method="POST">';

							$form .= '<input type="hidden" name="id" value="">';
							$form .= '<input type="hidden" class="noreset" name="dominio" value="' . $this->__get('dominio') . '">';
							$form .= '<input type="hidden" class="noreset" name="action" value="salvar">';

							$form .= '<div class="row">';
									
								$form .= '<div class="col-md-12">';
									$form .= '<div class="form-group">';
										$form .= '<label for="nome">Nome</label>';
										$form .= '<input type="text" name="nome" class="form-control req" value="' . $this->__get('nome') . '" >';
									$form .= '</div>';
								$form .= '</div>';

								$form .= '<div class="col-md-12">';
									$form .= '<label for="descricao">Descrição</label>';
									$form .= '<textarea class="form-control" rows="4" name="descricao">' . $this->__get('descricao') . '</textarea>';
								$form .= '</div>';
								
							$form .= '</div>';

							$form .= '<div class="col-md-12 divesp10"></div>';

							$form .= '<div class="row">';
								
								$form .= '<div class="col-md-12">';

									$form .= '<label>Competências</label>';

								$form .= '</div>';
									
								$form .= '<div class="col-md-4 col-sm-6 col-xs-8">';
										
									$form .= '<input type="text" name="competencia_nome" class="form-control" value="">';

								$form .= '</div>';

								$form .= '<div class="col-md-3 col-sm-3 col-xs-4">';
											
									$disabled = ( !$m_autenticacao->hasPermission( array( 'C', 'U' ) ) )? ' disabled ' : '';

							 		$form .= '<button type="button" class="btn btn-primary btn-cor-primary btn-100" ' . $disabled . ' id="addCompetencia">Nova competência</button>';

							 	$form .= '</div>';	

							 	$form .= '<div class="col-md-12" id-"mensagem_competencia"></div>';	

							$form .= '</div>';

							$form .= '<div class="row">';
								
								$form .= '<div class="col-md-12">';

									$form .= '<ul class="list-unstyled" id="listCompetencias"></ul>';

								$form .= '</div>';

								$form .= '<div id="mensagem_competencia"></div>';
								
							$form .= '</div>';


							$form .= '<div class="col-md-12 divesp10"></div>';				
							
					 		$form .= '<div class="modal-footer">';					 			

								$form .= '<div class="col-md-3 col-sm-6 col-xs-12">';
											
									$disabled = ( !$m_autenticacao->hasPermission( array( 'C', 'U' ) ) )? ' disabled ' : '';

						 			$form .= '<button type="button" class="btn btn-primary btn-cor-primary btn-100" ' . $disabled . ' id="salvar">Salvar</button>';
						 		$form .= '</div>';				 			

					 			if( !empty( $this->__get('id') ) ){ 

									$form .= '<div class="col-md-3 col-sm-6 col-xs-12">';

										$disabled = ( !$m_autenticacao->hasPermission( array( 'D' ) ) )? ' disabled ' : '';

										$form .= '<button type="button" class="btn btn-danger btn-100" ' . $disabled . ' id="excluir">Excluir</button>';
									$form .= '</div>';

						 		}

						 		$form .= '<div class="col-md-3 col-sm-6 col-xs-12">';
									$form .= '<button type="button" class="btn btn-default btn-100" data-dismiss="modal" id="fechar">Fechar</button>';
								$form .= '</div>';
        						
      						$form .= '</div>'; 		

						$form .= '</form>';

						$form .= '<div class="row">';
							$form .= '<div class="col-md-12" id="mensagem_disciplina"></div>';
						$form .= '</div>';

					$form .= '</div>';

				$form .= '</div>';

			$form .= '</div>';

		echo $form;

    }



    public function novo(){

    	$daoDisciplina = new DaoDisciplina();

    	$id = $daoDisciplina->inserir( $this );

		return $id;

    }

       
    public function editar(){

    	$daoDisciplina = new DaoDisciplina();

    	$retorno = $daoDisciplina->editar( $this );

		return $retorno;

    }


    public function remover(){

    	$daoDisciplina = new DaoDisciplina();

    	$retorno = $daoDisciplina->remover( $this->__get('id') );

		return $retorno;

    }


    
    /*
    Imprime o formulário para criar e editar um domínio
    Param: Object Session
    */
    /*public function showFormulario( Session $m_session ){

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
    }*/


}
?>