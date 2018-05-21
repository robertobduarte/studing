<?php
include_once __DIR__ . "/../config.php";

class Slide extends IObject {

	static $instances = array();
	//protected $tiposDeDados = array();
	protected $controller = '../controller/controller.php?c=slide';
	private $id;
	private $titulo;
	private $enunciado;
	private $enunciado_html;
	private $content_html;
	private $objetivo; //	int objetivo_id
	private $disciplina; // int disciplina_id
	private $posicao;
	private $numero;
	private $status = 'I';
	private $correta;
	private $comentario;
	private $nivel = 'M';
	private $slide_tipo;
	private $arquivo;
	private $tipo;// nome do tipo do slide (caso clinio, questão)	
	private $tipos;	//tipos possíveis para o slides (para carregar no dropdown)
	private $peso = 1;
	private $parent; //caso de ser um slide que seja filho de outro slide. Ex.: Pai: us slide com um texto. Filho: questões de interpretação do texto do slide pai
	private $alternativas = array(); //Object Alternativa
	private $m_disciplina; //Object Disciplina
	private $competencias = array(); //array de Object Conpetencia do slide


	public function __construct( $dados = null ){

		parent::__construct( $dados );
	}

	public function listar( $id = null ){}

	protected function defineTipos(){

		$this->tiposDeDados = array( 
									'id' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'disciplina' => array( 'type' => 'int', 'mandatory' => true, 'size' => false ),
									'posicao' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'numero' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'status' => array( 'type' => false, 'mandatory' => false, 'size' => 1 ),
									'correta' => array( 'type' => false, 'mandatory' => false, 'size' => 1 ),
									'slide_tipo' => array( 'type' => false, 'mandatory' => true, 'size' => 3 ),
									'parent' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'posicao' => array( 'type' => 'int', 'mandatory' => true, 'size' => 3),
									'nivel' => array( 'type' => 'false', 'mandatory' => false, 'size' => 1),
									'peso' => array( 'type' => 'float', 'mandatory' => false, 'size' => false)
								);
	}


	public function getObjeto( $slide_id  ){

		$daoSlide = new DaoSlide();

		$dados = $daoSlide->buscar( $slide_id );

		$this->__set( $this, $dados );
	}


	public function getDisciplina( $disciplina_id = ''  ){

		$disciplina_id = ( !empty( $disciplina_id ) )? $disciplina_id : $this->__get('disciplina');

		$m_disciplina = new Disciplina( array( 'id' => $disciplina_id ) );

		$this->__set( $this, array( 'm_disciplina' => $m_disciplina ) );
	}


	public function getTipoSlide(){

		$daoSlide = new DaoSlide();

		$tipoSlides = $daoSlide->getTipoSlide();

		for ( $i = 0; $i < count( $tipoSlides ); $i++ ) {
			
			$tipoSlides[$i]['id'] = trim( $tipoSlides[$i]['id'] );
		}

		$this->tipos = $tipoSlides;

	}


	
	//Retorna as competências que o slide tem.	
	public function getCompetencias(){

		$m_competencia = new Competencia();

		$competencias = $m_competencia->getCompetenciaBySlide( $this->__get('id') );

		$this->competencias = $competencias;

	}
	
	/*
	//Retorna o Objeto objeto (prova) que o slide pertence
	public function getObjeto( $id = null ){

		$id = ( $id == null )? $this->__get('objeto') : $id;

		$m_objeto = new Objeto();

		$m_objeto->getObjeto( $id );

		$this->prova = $m_objeto;
	}*/


	
	/*retorna um array de Slides com todos os slides(questões) pertencentes a um objetivo. 
	Se a disciplina for passada no segundo parâmetro, retorna apenas as questões do objetivo pertencentes a disciplina*/
	public function getSlidesByObjetivo( $objetivo_id, $disciplina_id = '' ){

		$daoSlide = new DaoSlide();

		$dados = $daoSlide->getSlidesByObjetivo( $objetivo_id, $disciplina_id );

		if( !empty( $dados ) ){

			foreach ( $dados as $value ) {
				
				$slide = new Slide();
				$slide->__set( $slide, $value );
				Slide::$instances[] = $slide; 
			}
			
		}
		return Slide::$instances;
	}

	/*
	Busca slides de um objeto específico filtrando por um bloco(aula)
	public function getSlidesByBloco( $objeto_id, $versao, $bloco, $nivel = 'M' ){

		$blc = array( 'bloco' => $bloco, 'nivel' => $nivel );

		$daoSlide = new DaoSlide();
		$dados = $daoSlide->getSlidesByAvaliacao( $objeto_id, $versao, $blc );

		Slide::$instances = array();

		if( !empty( $dados ) ){

			foreach ( $dados as $value ) {				
				
				if( !empty( $value ) ){
					$slide = new Slide();
					$slide->__set( $slide, $value );
					Slide::$instances[] = $slide; 
				}
				
			}
			
		}

		return Slide::$instances;

	}*/


    /*
    retorna uma lista de alternativas do slide.
    */
    public function getAlternativas( $id = null ){

    	$id = ( $id == null )? $this->__get('id') : $id;

    	$alternativa = new Alternativa();

    	$alternativas = $alternativa->getAlternativasBySlide( $id );
    	$this->alternativas = array();
		$this->alternativas = $alternativas;
    }



	public function __set( $classe, $objeto ) {

	 	foreach ( $objeto as $key => $value ) {

	 		if( property_exists( $classe, $key ) ){

	 			$value = ( empty( $value ) ) ? NULL : $value;
				$this->$key = $value;

	 		}
	 	}
    }


    public function __get( $name ) {

	 	if( property_exists( $this, $name ) ){

	 		$valor = ( !empty( $this->$name ) )? $this->$name : NULL;
	 		$valor = ( $name == 'slide_tipo' )? trim( $valor ) : $valor;
			
	 		return $valor;
	 	}
    }


    public function novo(){

    	$daoSlide = new DaoSlide();

    	$id = $daoSlide->inserir( $this );

    	$this->__set('Slide', array( 'id' => $id ) );

    	return $id;
    }


    public function editar(){

    	$daoSlide = new DaoSlide();

    	return $daoSlide->editar( $this );

    }


    public function editarCompetencias( $competencias ){

    	$daoSlide = new DaoSlide();

    	$daoSlide->removerCompetencias( $this->__get('id') );

    	$arrayCompetencias = explode( ',' , $competencias );

    	if( !empty( $arrayCompetencias ) ){

    		foreach ($arrayCompetencias as $comp) {

    			$daoSlide->addCompetencia( $this->__get('id'), $comp );
    		}
    	}

    }


    /*public function editarDadosArquivo( $id, $caminho, $nome_arquivo ){

    	$daoSlide = new DaoSlide();

    	$retorno = $daoSlide->editarDadosArquivo( $id, $caminho, $nome_arquivo );

    	return $retorno;
    }*/



    /*public function editarStatus( $status ){

    	$this->getObjeto( $this->__get('objeto') );

    	if( $this->prova->__get('status_versao') != 'I' ){ //status_versao do objeto deve estar inativo - caso não esteja com este status, retorna erro.
    		return false;
    	}

    	$daoSlide = new DaoSlide();

    	$retorno = $daoSlide->editarStatus( $this->__get('id'), $status );

		return $retorno;
    }*/

    /*public function ativarSlidesByObjeto(){

    	$this->getObjeto( $this->__get('objeto') );

    	if( $this->prova->__get('status') != 'I' ){ //prova inativa - caso não esteja com este status, retorna erro.

    		return false;

    	}

    	$daoSlide = new DaoSlide();

    	$retorno = $daoSlide->ativarSlidesByObjeto( $this->__get('objeto') );

		return $retorno;
    }*/


    /*public function remover( $all = false ){ //line 328

    	$this->getObjeto( $this->__get('objeto') );

    	//cria um novo objeto para buscar a informação atual do slide no banco de dados

    	$m_slide_actual = new Slide( array( 'id' => $this->__get('id') ) );

    	if( ( $this->prova->__get('status') == 'I' ) && ( $all || in_array( $m_slide_actual->__get('status'), array( 'A', 'I' ) ) ) ){

    		$this->getAlternativas();

	    	$daoSlide = new DaoSlide();

	    	$retorno = $daoSlide->remover( $this );

	    	if( $retorno ){

	    		foreach ( $this->alternativas as $alternativa ) {
	    			
	    			$alternativa->remover();
	    		}
	    	}

    	}else{

    		return false; 
    	}

		return $retorno;
    }*/


    /*public function removerSlideByObjetoId( $objeto_id = null ){

    	$objeto = ( $objeto_id == null )? $this->__get('objeto') : $objeto_id;

    	Slide::$instances = '';

    	$this->getSlidesByObjeto( $objeto );
    	
    	$daoSlide = new DaoSlide();

    	$result = array();

    	foreach ( Slide::$instances as $m_slide ) {

    		$retorno = $m_slide->remover( true );

    		if( !$retorno ){

    			$result[] = array( 'msg' => 'Erro ao excluir o slide ' . $m_slide->__get('id') );
    		}

    	}

    	return $result;
    }*/


    /*public function showLink(){

    	if( !empty( $this->__get('id') ) ){

    		echo '<a href="slide.php?sld=' . $this->__get('id') .'">' . $this->__get('titulo') . '</a>';
    	}
    }*/



    public function showLink( $dominio_id ){

    	echo '<a href="bancodequestoes.php?dmn=' . $dominio_id .'&obj=' . $this->__get('objetivo') . '&disc=' . $this->__get('disciplina') . '">Banco de questões</a>';   		
    }


    /*
    Imprimme o formulário para criar e editar um slide
    Param: Object Objeto, Object Session (obrigatórios)
    */
    public function showFormulario( Objetivo $m_objetivo, Session $m_session, Autenticacao $m_autenticacao ){

		if( empty( $this->__get('tipos') ) ){

    		$this->getTipoSlide();

    	}

		if( empty( $this->__get('m_disciplina') ) ){

    		$this->getDisciplina();

    	}

    	$this->getCompetencias();
    	

    	/*echo '<pre>';
    	print_r( $this->__get('competencias') );
    	echo '</pre>';*/

    	$competencias = '';
    	$arrayComp = array();
    	foreach ( $this->__get('competencias') as $m_competencia ) {

    		$competencias .= ( !empty( $competencias ) )? ',' . $m_competencia->__get('id') : $m_competencia->__get('id');
    		$arrayComp[] = $m_competencia->__get('id');
    	}
    	
    	$form = '';

    	$form .= '<form id="slide_' . $this->__get('id') . '" action="' . $this->__get('controller') . '" enctype="multipart/form-data" method="POST">';

				$form .= '<input type="hidden" name="id" value="' . $this->__get('id') . '">';
				$form .= '<input type="hidden" name="dominio" value="' . $m_objetivo->__get('dominio') . '">';
				$form .= '<input type="hidden" name="objetivo" value="' . $m_objetivo->__get('id') . '">';
				$form .= '<input type="hidden" name="disciplina" value="' . $this->__get('disciplina') . '">';
				$form .= '<input type="hidden" name="competencias" value="' . $competencias . '">';
				$form .= '<input type="hidden" name="action" value="salvar">';

				$form .= '<div class="row">';

					$form .= '<div class="col-md-2 col-sm-3 col-xs-6">';
						$form .= '<div class="form-group">';
							$form .= '<label for="posicao">Posição*</label>';
							$form .= '<input type="text" name="posicao" class="form-control int req" value="' . $this->__get('posicao') . '" >';
						$form .= '</div>';
					$form .= '</div>';

					$form .= '<div class="col-md-2 col-sm-3 col-xs-6">';
						$form .= '<div class="form-group">';
							$form .= '<label for="posicao">Número*</label>';
							$form .= '<input type="text" name="numero" class="form-control" value="' . $this->__get('numero') . '" >';
						$form .= '</div>';
					$form .= '</div>';

					$form .= '<div class="col-md-3 col-sm-4 col-xs-12">';

						$form .= '<div class="form-group">';
							$form .= '<label for="pesq">Tipo*</label>';
							$form .= '<select class="form-control req" id="tipo_slide" name="slide_tipo">';
								$form .= '<option value="">Selecione</option>';
								
								if( count( $this->__get('tipos') ) > 0 ) {

									foreach ( $this->__get('tipos') as $tipo ) {
										$select = ( $this->__get('slide_tipo') == $tipo['id'] ) ? ' selected ' : '';
										$form .= '<option ' . $select . ' value="' . $tipo['id'] . '">' . $tipo['nome'] . '</option>';
									}
								}								

						    $form .= '</select>';
						$form .= '</div>';
					$form .= '</div>';


					$form .= '<div class="col-md-2 col-sm-2 col-xs-6">';

						$form .= '<div class="form-group">';
							$peso = ( !empty( $this->__get('peso') ) )? $this->__get('peso') : 1;
							$form .= '<label for="peso">Peso*</label>';
							$form .= '<input type="text" name="peso" class="form-control int req" disabled="disabled" value="' . $peso . '" >';
						$form .= '</div>';

					$form .= '</div>';					

				$form .= '</div>'; //.row    


				$form .= '<div class="row">';

					$form .= '<div class="col-md-2 col-sm-3 col-xs-6">';
							$form .= '<div class="form-group">';
								$form .= '<label for="correta">Correta*</label>';
								$form .= '<input type="text" name="correta" class="form-control" maxlength="1" style="text-transform: uppercase" value="' . $this->__get('correta') . '" >';
							$form .= '</div>';
						$form .= '</div>';				

					$form .= '<div class="col-md-2 col-sm-3 col-xs-6">';
						$form .= '<div class="form-group">';
							$form .= '<label for="pai">Slide</label>';
							$form .= '<input type="text" name="parent" class="form-control" value="' . $this->__get('parent') . '" >';
						$form .= '</div>';
					$form .= '</div>';


					$form .= '<div class="col-md-3 col-sm-4 col-xs-6" id="objStatus">';

							$form .= '<label for="versao">Status*</label>';
							$form .= '<div class="form-group">';

								$form .= '<label class="checkbox-inline">';
								$checked = ( $this->__get('status') == 'A' )? ' checked="checked" ' : '';
								$form .= '<input type="radio" class="form-check-input" name="status" ' .  $checked . ' value="A"> Ativo';							 
								$form .= '</label>';

								$form .= '<label class="checkbox-inline">';								
								$checked = ( empty( $this->__get('status') ) || $this->__get('status') == 'I' )? ' checked="checked" ' : '';

								$form .= '<input type="radio" class="form-check-input" name="status"  ' .  $checked . ' value="I"> Inativo';
								$form .= '</label>';

							$form .= '</div>';

					$form .= '</div>';

					$form .= '<div class="col-md-4 col-sm-4 col-xs-6" id="objNivel">';

							$form .= '<label for="versao">Nível*</label>';
							$form .= '<div class="form-group">';

								$form .= '<label class="checkbox-inline">';
								$checked = ( $this->__get('nivel') == 'F' )? ' checked="checked" ' : '';
								$form .= '<input type="radio" class="form-check-input" name="nivel" ' .  $checked . ' value="F"> Fácil';							 
								$form .= '</label>';

								$form .= '<label class="checkbox-inline">';								
								$checked = ( $this->__get('nivel') == 'M' )? ' checked="checked" ' : '';

								$form .= '<input type="radio" class="form-check-input" name="nivel"  ' .  $checked . ' value="M"> Média';
								$form .= '</label>';

								$form .= '<label class="checkbox-inline">';								
								$checked = ( $this->__get('nivel') == 'D' )? ' checked="checked" ' : '';

								$form .= '<input type="radio" class="form-check-input" name="nivel"  ' .  $checked . ' value="D"> Difícil';
								$form .= '</label>';

							$form .= '</div>';

					$form .= '</div>';					
						
				$form .= '</div>'; //.row


				$form .= '<div class="row">';

					$form .= '<div class="col-md-3 col-sm-4 col-xs-12" id="competencias">';

							$form .= '<div class="form-group">';
								$form .= '<label for="pesq">Competência</label>';
								$form .= '<select class="form-control" id="listCompetencia">';
									$form .= '<option value="">Selecione</option>';
									
									if( count( $this->__get('m_disciplina')->__get('competencias') ) > 0 ) {

										foreach ( $this->__get('m_disciplina')->__get('competencias') as $m_competencia ) {

											$select = ( in_array( $m_competencia->__get('id'), $arrayComp ) )?  'disabled="disabled"' : '';

											$form .= '<option ' . $select . ' value="' . $m_competencia->__get('id') . '">' . $m_competencia->__get('nome') . '</option>';

										}

									}								

							    $form .= '</select>';   							    
							
							$form .= '</div>';
							
					$form .= '</div>';

					$form .= '<div class="col-md-4 col-sm-6 col-xs-12">';

						$form .= '<ul class="list-unstyled" id="slideCompetencias">';

							foreach ( $this->__get('competencias') as $m_competencia ) {

					    		$form .= '<li id="comp_' . $m_competencia->__get('id') . '"><span style="cursor:pointer; margin-right:5px;" id="rmcompetencia_' . $m_competencia->__get('id') . '" class="glyphicon glyphicon-remove"></span>' . $m_competencia->__get('nome') . '</li>';
					    	}

						$form .= '</ul>';						

					$form .= '</div>';

				$form .= '</div>'; //.row


				$form .= '<div class="col-md-12 divesp10"></div>';

				$form .= '<div class="row">';

					$form .= '<div class="col-md-12">';
						$form .= '<div class="form-group">';
							$form .= '<label for="correta">Título*</label>';
							$form .= '<input type="text" name="titulo" class="form-control req" value="' . $this->__get('titulo') . '" >';
						$form .= '</div>';
					$form .= '</div>';					

					$classe = ( trim( $this->__get('slide_tipo') ) == 'QT' )? '' : ' oculta ';

					$form .= '<div class="col-md-12 ' . $classe . '" id="slide_QT">';
						$form .= '<label for="enunciado">Enunciado*</label>';
						$form .= '<textarea class="form-control" rows="4" name="enunciado">' . $this->__get('enunciado') . '</textarea>';
					$form .= '</div>';

					$classe = ( trim( $this->__get('slide_tipo') ) == 'QH' )? '' : ' oculta ';

					$form .= '<div class="col-md-12 ' . $classe . '" id="slide_QH">';
						$form .= '<label for="enunciado_html">Enunciado Html*</label>';
						$form .= '<textarea class="form-control mceEditor" rows="4" name="enunciado_html">' . $this->__get('enunciado_html') . '</textarea>';
					$form .= '</div>';

					$classe = ( trim( $this->__get('slide_tipo') ) == 'SL' )? '' : ' oculta ';

					$form .= '<div class="col-md-12 ' . $classe . '" id="slide_SL">';
						$form .= '<h3>Caso Clínico</h3>';
						$form .= '<label for="enunciado">Conteúdo HTML*</label>';
						$form .= '<textarea class="form-control mceEditor" rows="4" name="content_html">' . $this->__get('content_html') . '</textarea>';
					$form .= '</div>';

					$form .= '<div class="col-md-12 divesp10"></div>';

					$classe = ( trim( $this->__get('slide_tipo') ) != 'SL' )? '' : ' oculta ';

					$form .= '<div class="col-md-12 ' . $classe . '" id="comentario">';
						$form .= '<label for="comentario">Comentário</label>';
						$form .= '<textarea class="form-control mceEditor" rows="4" name="comentario">' . $this->__get('comentario') . '</textarea>';
					$form .= '</div>';

					//$form .= '<div class="col-md-12 divesp10"></div>';

				$form .= '</div>'; //.row				

				$form .= '<div class="col-md-12 divesp10"></div>';

				$form .= '<div class="row">';
					
					$form .= '<div class="col-md-3 col-sm-6 col-xs-12">';
							
						$disabled = ( !$m_autenticacao->hasPermission( array( 'C', 'U' ) ) )? ' disabled ' : '';

						$form .= '<button type="submit" class="btn btn-primary btn-cor-primary btn-100" ' . $disabled . ' id="salvarSlide_' . $this->__get('id') . '">Salvar</button>';

					 $form .= '</div>';

					 if( !empty( $this->__get('id') ) ){

						$form .= '<div class="col-md-3 col-sm-6 col-xs-12">';

							$disabled = ( !$m_autenticacao->hasPermission( array( 'D' ) ) )? ' disabled ' : '';

							$form .= '<button type="button" class="btn btn-danger btn-100" ' . $disabled . ' id="excluirSlide_' . $this->__get('id') . '">Excluir Slide</button>';

				 		$form .= '</div>';
			 		}				

	 			$form .= '</div>'; //.row		 		

		$form .= '</form>';

		echo $form;

    }
 


    public function showAlternartivas( Session $m_session, Autenticacao $m_autenticacao ){


    	if ( ( trim( $this->__get('slide_tipo') ) ) != 'SL' ){ //Casos clínicos não possuem alternativas

	    	$this->getAlternativas();

	    	$table = '';	

	    	$table .= '<div class="col-md-12">';
	    		$table .= '<h3>Alternativas</h3>';
	    	$table .= '</div>';

	    	$table .= '<script src="js/alternativa.js?v=<?= filemtime("js/alternativa.js"); ?>"></script>';

	    	$disabled = ( !$m_autenticacao->hasPermission( array( 'C', 'U' ) ) )? ' disabled ' : '';

	    	$table .= '<div class="col-md-3 col-md-offset-9 col-sm-5 col-sm-offset-7 col-xs-12">';
	    	$table .= '<button type="button" class="btn btn-100 btn-primary btn-cor-primary" role="button" ' . $disabled . ' id="novaAlternativa_' . $this->__get('id') . '">Nova Alternativa</button>';

	    	
	    	$table .= '</div>';
	    		
	    	$table .= '<table class="table table-striped" id="listAlt_' . $this->__get('id') . '">';

		    	$table .= '<thead>';
		    		$table .= '<th style="width: 3%;"></th>';
		    		$table .= '<th style="width: 3%;"></th>';
		    		$table .= '<th style="width: 7%;">Tipo</th>';
		    		$table .= '<th style="width: 5%;">Opção</th>';
		    		$table .= '<th style="width: 40%;">Texto</th>';
		    		$table .= '<th style="width: 42%;">Texto Html</th>';	    		
		    	$table .= '</thead>';

		    	$table .= '<tbody>';

		    	/*echo '<pre>';
		    	print_r($this->alternativas);
		    	echo '</pre>';*/

		    	if( !empty( $this->alternativas ) ){

		    		foreach ( $this->alternativas as $m_alternativa ) {  

		    			$arquivo = ( !empty( $m_alternativa->__get('arquivo') ) )? '<i class="fa fa-file-image-o" aria-hidden="true"></i>' : '';
		    			$table .= '<tr id="tr_' . $m_alternativa->__get('id') . '">';
		    				$table .= '<td><a id="alternativa_' . $m_alternativa->__get('id') . '" href="#"> <i class="fa fa-pencil" aria-hidden="true"></i></a></td>';
		    				$table .= '<td>' . $arquivo . '</td>';
		    				$table .= '<td>' . $m_alternativa->__get('tipo') . '</td>';
		    				$table .= '<td>' . $m_alternativa->__get('valor') . '</td>';
				    		$table .= '<td>' . $m_alternativa->__get('texto') . '</td>';
				    		$table .= '<td>' . $m_alternativa->__get('texto_html') . '</td>';			    		
				    	$table .= '</tr>';

		    		}

	    		}else{

	    			$table .= '<tr>';
		    			$table .= '<td colspan="6"><div class="col-md-12 alert alert-warning"><p>Não existem alternativas cadastradas.</p></td></div>';
					$table .= '</tr>';
	    	}

	    		$table .= '</tbody>';
	    	$table .= '</table>';


	   		$table .= '<div id="modalAlt_' . $this->__get('id') . '" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modalAlt_' . $this->__get('id') . '" aria-hidden="true">';		
				$table .= '<div class="modal-dialog">';
								
					$table .= '<div class="modal-content">';

						$table .= '<div class="modal-header">';
										
							$table .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
									
							$table .= '<h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Alternativa </h4>';
									
						$table .= '</div>'; //modal-header
									
						$table .= '<div class="modal-body">';
										
							$table .= '<form id="formAlternativa_' . $this->__get('id') . '" action="../controller/controllerAlternativa.php" enctype="multipart/form-data" method="POST">';
											
								$table .= '<input type="hidden" name="id" id="idAlt_' . $this->__get('id') . '" value="" />';
								$table .= '<input type="hidden" name="slide" id="slideAlt_' . $this->__get('id') . '" value="" />';
								$table .= '<input type="hidden" id="arquivoAlt_' . $this->__get('id') . '" name="arquivo" value="">';
								$table .= '<input type="hidden" id="caminhoAlt_' . $this->__get('id') . '"  name="caminho" value="">';					
								$table .= '<input type="hidden" name="alternativa_tipo" id="alternativa_tipo_' . $this->__get('id') . '" value="">';

								$table .= '<div class="row">';
												
									$table .= '<div class="col-md-3 col-sm-4 col-xs-12">';
										$table .= '<div class="form-group" id="">';
											$table .= '<label>Opção*</label>';
											$table .= '<input type="text" class="form-control req" id="valorAlt_' . $this->__get('id') . '" name="valor" maxlength="1" style="text-transform: uppercase" value=""/>';
										$table .= '</div>';
									$table .= '</div>';	

									$table .= '<div class="col-md-offset-5 col-md-4 col-sm-offset-3 col-sm-5 col-xs-12">';
													
										$table .= '<div class="radio-inline">';
										  $table .= '<label>';
										    $table .= '<input type="radio" id="tipo_AT" name="altTipo_' . $this->__get('id') . '" style="margin-top: 0;" value="AT">';
										  $table .= 'Texto simples</label>';
										$table .= '</div>';

										$table .= '<div class="radio-inline">';
											$table .= '<label>';
										    $table .= '<input type="radio" id="tipo_AH" name="altTipo_' . $this->__get('id') . '" style="margin-top: 0;" value="AH">';
											$table .= 'HTML</label>';
										$table .= '</div>';

									$table .= '</div>';	
											
								$table .= '</div>';	//.row

								$table .= '<div class="row">';

									$table .= '<div class="col-md-12" id="tipo-' . $this->__get('id') . '_AT">';
										$table .= '<div class="form-group">';
											$table .= '<label for="texto">Texto</label>';
											$table .= '<textarea class="form-control" rows="4" name="texto" id="textoAlt_' . $this->__get('id') . '"></textarea>';
										$table .= '</div>';
									$table .= '</div>';

								$table .= '</div>'; //.row
											
								$table .= '<div class="row">'; 

								$table .= '<div class="col-md-12" id="tipo-' . $this->__get('id') . '_AH">';													
									$table .= '<div class="form-group">';
										$table .= '<label for="texto_html">Texto Html</label>';
										$table .= '<textarea class="form-control mceEditor" rows="4" name="texto_html" id="textoAltHtml_' . $this->__get('id') . '"></textarea>';
									$table .= '</div>';
												
								$table .= '</div>';
											
								$table .= '</div>'; //.row

								$table .= '<div class="row">';
												
									$table .= '<div class="col-md-offset-8 col-md-4 col-sm-offset-6 col-sm-6 col-xs-12">';
										$table .= '<input type="button" class="btn btn-primary btn-100" id="enviarArqAlt_' . $this->__get('id') . '" value="Enviar Arquivo">';
									    $table .= '<input type="file" name="file" id="fileAlt_' . $this->__get('id') . '" class="oculta" value="">';
									$table .= '</div>';

									$table .= '<div class="col-md-offset-8 col-md-4 col-sm-offset-6 col-sm-6 col-xs-12" id="arquivoAlternativa_' . $this->__get('id') . '">';
										$table .= '<ul class="list-unstyled listFiles" id="listArqAlt_' . $this->__get('id') . '"></ul>';
									$table .= '</div>';

								$table .= '</div>'; //.row
											
								$table .= '<div class="row">';						

									$table .= '<div class="col-md-12">';

									$disabled = ( !$m_autenticacao->hasPermission( array( 'C', 'U', 'R' ) ) )? ' disabled ' : '';

										$table .= '<div class="col-md-3 col-sm-4 col-xs-12" id="">';
											$table .= '<button type="button" class="btn btn-100 btn-primary btn-cor-primary" role="button" ' . $disabled . ' id="salvarAlternativa_' . $this->__get('id') . '"><i class="fa fa-check"></i> Salvar</button>';
										$table .= '</div>';

										$table .= '<div class="col-md-3 col-sm-4 col-xs-12" id="">';
											$table .= '<button type="button" ' . $disabled . ' class="btn btn-100 btn-danger" role="button" id="ExcluirAlternativa_' . $this->__get('id') . '"><i class="fa fa-check"></i> Excluir</button>';
										$table .= '</div>';
									
										$table .= '<div class="col-md-3 col-sm-4 col-xs-12">';	
											$table .= '<button type="button" class="btn btn-100 btn-warning" id="cancelarAlt_' . $this->__get('id') . '">Fechar</button>';
										$table .= '</div>';
									
									$table .= '</div>'; //.col-md-12

								$table .= '</div>'; //.row

								$table .= '<div class="divesp"></div>';

								$table .= '<div class="row">';
									$table .= '<div class="col-md-12" id="mensagemAlt_' . $this->__get('id') . '"></div>';
								$table .= '</div>'; //.row
																					
							$table .= '</form>';
									
						$table .= '</div>'; //modal-body

					$table .= '</div>'; //modal-content
				$table .= '</div>';
			$table .= '</div>'; //modal
	    	

	    	echo $table;    	

    	}

    }





}
?>