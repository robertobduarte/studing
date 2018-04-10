<?php
include_once __DIR__ . "/../config.php";

class Objetivo extends IObject{

	protected static $instances = array();
	protected $tiposDeDados = array();
	protected $controller = '../controller/controllerObjetivo.php';
	private $id;
	private $nome;
	private $dominio;
	private $descricao;
	private $ordem;
	private $objetivo_tipo;
	private $tipo;
	private $leaf;	
	private $parent;
	private $lastParent;
	private $parents = array();
	private $children = array();	
	private $tree;
	private $tiposObjetivos;


	public function __construct( $dados = null ){

		if( isset( $dados['id'] ) && !empty( $dados['id'] ) ){

			$this->getObjeto( $dados['id'] );
		}

		$this->defineTipos();
		
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

	protected function defineTipos(){

		$this->tiposDeDados = array( 
									'id' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'nome' => array( 'type' => false, 'mandatory' => true, 'size' => 200 ),
									'dominio' => array( 'type' => 'int', 'mandatory' => true, 'size' => false ),
									'objetivo_tipo' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'parent' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'leaf' => array( 'type' => false, 'mandatory' => true, 'size' => 1 )
									);
	}


	public function getObjeto( $objetivo_id  ){

		$daoObjetivo = new DaoObjetivo();		
		$dados = $daoObjetivo->buscar( $objetivo_id );

		$this->__set( $this, $dados );
	}


	/*
	Busca o último pai do objetivo. O primeiro nó. Ex. Curso
	*/
	public function getLastParent( $id = null ){

		$id = ( $null != null )? $id : $this->__get('id');
		$daoObjetivo = new DaoObjetivo();

		$parent = array();
		$lastParent = $daoObjetivo->getLastParent( $id, $parent );
		$this->lastParent = $lastParent;

	}
	

	/*
	retorna uma árvore dos os pais de um objetivo específico (inclusive o prórpio). Params: id - Recursão
	*/
	public function getParents(){

		$daoObjetivo = new DaoObjetivo();

		$parent = array();
		$parentTree = $daoObjetivo->getParent( $this->id, $parent );

		foreach ( $parentTree as $parent ) {

			$this->parents[] = array( 'id' => $parent['id'], 'nome' => $parent['nome'], 'tipo' => $parent['tipo'] );
		}

	}

	/*
	retorna uma árvore dos filhos de um objetivo específico (inclusive o prórpio). Recursão
	*/
	public function getChildren(){

		$daoObjetivo = new DaoObjetivo();

		$childrenTree = $daoObjetivo->getChildren( $this->id );
		
		if( !empty( $childrenTree[0]['children'] ) ){

			$this->children[] = $childrenTree[0]['children'];
		}
	}

	/*
	Busca todos os objetivos pai. Se recursivo, busca inclusive os filhos
	*/
	public function listar( $dominio_id = null, $recursivo = false ) {

		$dominio = ( $dominio_id == null )? $this->dominio : $dominio_id;

		$daoObjetivo = new DaoObjetivo();

		$dados = $daoObjetivo->startGetObjetivos( $dominio, $recursivo );

		foreach ($dados as $value) {

			$objetivo = new Objetivo();
			$objetivo->__set( $objetivo, $value );
		
			Objetivo::$instances[] = $objetivo; 
		}

		return Objetivo::$instances;		
	}


    /*
    Lista a árvore de objetivos de um objetivo específico. Param objetivo_id
    */
    public function listObjetivosFilhos(){

    	$children = $this->children;

    	/*
    	Param false para que a primeira tag <ul> não seja oculta
    	*/
    	if( !empty( $this->children) ){

    		$this->printChildren( $children[0], false );

    	}else{

    		echo '<div class="alert alert-warning"><p>Não existem objetivos pertencentes a este grupo.</p></div>';
    	}
    }


    public function getTiposObjetivos(){

    	$daoObjetivo = new DaoObjetivo();

		$tiposObjetivos = $daoObjetivo->getTiposObjetivos();

		$this->tiposObjetivos = $tiposObjetivos;

		/*echo '<pre>';
		print_r($this->tiposObjetivos);
		echo '</pre>';*/
    }


    /*
    Lista a árvore de objetivos completa
    */
    public function listObjetivos(){

    	//return Objetivo::$instances;
    	if( !empty( Objetivo::$instances ) ){

			echo '<ul class="listObjetivos">';

			foreach ( Objetivo::$instances as $objetivo ) {
					
					echo '<div class="boxListObjetivos">';
						
						if( $objetivo->leaf == 'N' ) {

							$classe = 'with_children';

							if ( !empty( $objetivo->children ) ){

								$icone = ' <i class="fa fa-folder linkObj" aria-hidden="true" id="folderobj_' . $objetivo->id . '"></i> ';

							}else{

								$icone = ' <i class="fa fa-folder-o" aria-hidden="true"></i> ';

							}

						}else{

							$classe = 'without_children';
							$icone = '';
						}

						echo '<li class="list-unstyled objetivo_main ' . $classe . '">' . $icone .  '<a href="objetivo.php?obj=' . $objetivo->id . '&dmn=' . $objetivo->dominio. '" id="objetivo_' . $objetivo->id . '" class="linkObjetivo">' . $objetivo->tipo . ': ' . $objetivo->nome . '</a></li>';

						if( $objetivo->leaf == 'N' ){

							$this->printChildren( $objetivo->children );
						
						}

					echo '</div>'; // .boxListObjetivos

			}

			echo '</ul>';

		}else{

			echo '<div class="col-md-12 alert alert-warning">';
				echo '<p>Não existem objetos cadastrados pertencentes a este domínio.</p>';
			echo '</div>';

		}

    }

    private function printChildren( $children, $full = true ){

    	if( !empty( $children ) ){


    		$classOculta = ( !$full )? '' : 'oculta';
    		
    		echo '<ul class="listObjetivosFilhos ' . $classOculta . '">';

				for( $i = 0; $i < count( $children ); $i++ ){
					
					if( $children[$i]['leaf'] == 'N' ) {

						$classe = 'with_children';

						if ( !empty( $children[$i]['children'] ) ){

							$icone = ' <i class="fa fa-folder linkObj" aria-hidden="true" id="folderobj_' . $children[$i]['id'] . '"></i> ';

						}else{

							$icone = ' <i class="fa fa-folder-o" aria-hidden="true"></i> ';

						}

					}else{

						$classe = 'without_children';
						$icone = ' <i class="fa fa-book" aria-hidden="true"></i> ';
					}

					echo '<li class="list-unstyled objetivo_children ' . $classe . '">' . $icone . ' <a href="objetivo.php?obj=' . $children[$i]['id'] . '&dmn=' . $children[$i]['dominio']. '" id="objetivo_' . $children[$i]['id'] . '" class="linkObjetivo">' . $children[$i]['tipo'] . ': ' . $children[$i]['nome'] . '</a></li>'; 

					if( $children[$i]['leaf'] == 'N' ){

						$this->printChildren( $children[$i]['children'] );

					}

				}

			echo '</ul>';

    	}

    }


    /*
    define a árvore de objetivos, gerando um lista com links para os objetivos que fazem parte da lista
    */
    public function setTree( $first = false ){
    	
    	/*
    	Default = false: não imprime o próprio objetivo na árvore de pais.
		Quando está se criando um objetivo novo, é setado como true para que o pai do objetivo seja exibido na árvore.
		*/
		
		if( empty( $this->parents ) ){
    		
    		$this->getParents();
    	}
		
    	$indice = ( !$first )? 1 : 0;

    	$tree = '';
		if( !empty( $this->parents ) ){

			$qtdFilhos = count( $this->parents )-1;

			for ( $i = $qtdFilhos; $i >= $indice; $i-- ) {

				$icone = ( $i < $qtdFilhos )? ' <i class="fa fa-angle-right" aria-hidden="true"></i> ' : ''; 

				$tree .= $icone . '<a href="objetivo.php?obj=' . $this->parents[$i]['id'] . '">' . $this->parents[$i]['tipo'] . ': ' . $this->parents[$i]['nome'] . '</a>';

			}
		}

		$this->tree = $tree;
    }

    /*
    define a árvore de objetivos.
    */
    public function showTreeAvaliacao(){
    		
		if( empty( $this->parents ) ){
    		
    		$this->getParents();
    	}
		
    	$tree = '<div class="col-md-12 titulo_avaliacao">';
		if( !empty( $this->parents ) ){

			$qtdFilhos = count( $this->parents )-1;

			for ( $i = $qtdFilhos; $i >= 0; $i-- ) {

				$icone = ( $i < $qtdFilhos )? ' <i class="fa fa-angle-right" aria-hidden="true"></i> ' : ''; 

				$tree .= $icone . '<label>' . $this->parents[$i]['tipo'] . ': ' . $this->parents[$i]['nome'] . '</label>';

			}
		}
		$tree .= '</div>';

		echo $tree;
    }


    public function novo(){

    	$this->parent = ( empty( $this->parent ) ) ? null : $this->parent;

    	$daoObjetivo = new DaoObjetivo();

		$obj_id = $daoObjetivo->inserir( $this );

		return $obj_id;

    }

    public function editar(){

    	$daoObjetivo = new DaoObjetivo();

    	$retorno = $daoObjetivo->editar( $this );

		return $retorno;

    }


    public function buttonNovoObjetivo(){

    	echo '<div class="col-md-2 col-sm-3 col-xs-12 header">';

    	echo '<a href="objetivo.php?dmn=' . $this->dominio . '"><button type="button" class="btn btn-primary btn-cor-primary btn-100"><i class="fa fa-plus-circle" aria-hidden="true"></i> Novo Objetivo</button></a>';
			
		echo '</div>';

    }


    public function showFormulario( Session $m_session ){

    	$form = '';

    	$form .= '<form id="objetivo_' . $this->__get('id') . '" method="post" action="' . $this->__get('controller') . '">';

			$form .= '<input type="hidden" name="id" value="' . $this->__get('id') . '">';
			$form .= '<input type="hidden" name="dominio" value="' . $this->__get('dominio') . '">';
			$form .= '<input type="hidden" name="parent" value="' . $this->__get('parent') . '">';
			$form .= '<input type="hidden" name="method" value="post">';
			$form .= '<input type="hidden" name="action" value="salvar">';


			$form .= '<div class="col-md-12">';
				$form .= '<div class="form-group">';
					$form .= '<label for="nome">Nome</label>';
					$form .= '<input type="text" name="nome" class="form-control req" id="nome_' . $this->__get('id') . '" placeholder="Nome do objetivo" value="' . $this->__get('nome') . '">';
				$form .= '</div>';
			$form .= '</div>';

			$form .= '<div class="col-md-2 col-sm-3 col-xs-12">';
				$form .= '<div class="form-group">';
					$form .= '<label for="ordem">Ordem</label>';
					$form .= '<input type="text" name="ordem" class="form-control req" id="ordem_' . $this->__get('id') . '" placeholder="Ordem" value="' . $this->__get('ordem') . '">';
				$form .= '</div>';
			$form .= '</div>';

			$form .= '<div class="col-md-3 col-sm-4 col-xs-12">';
				$form .= '<div class="form-group">';
					$form .= '<label for="alias">Tipo</label>';
					$form .= '<select class="form-control req" id="objetivoTipo" name="objetivo_tipo">';
						$form .= '<option value="">Selecione</option>';

							foreach ( $this->tiposObjetivos as $objTipo ) {
								$select = ( $objTipo['id'] == $this->__get('objetivo_tipo') ) ? ' selected ' : '';
								$form .= '<option ' . $select . ' value="' . $objTipo['id'] . '">' . $objTipo['nome'] . '</option>';
							}

					$form .= '</select>';
				$form .= '</div>';
			$form .= '</div>';			

			$form .= '<div class="col-md-12">';
				$form .= '<div class="form-group">';
					$form .= '<label for="alias">descricao</label>';
					$form .= '<textarea class="form-control" name="descricao" rows="3" id="descricao_' . $this->__get('id') . '">' . $this->__get('descricao') . '</textarea>';
				$form .= '</div>';
			$form .= '</div>';

			$form .= '<div class="col-md-6 col-xs-12">';
				$form .= '<fieldset class="form-group" id="objApr">';

					$form .= '<legend>Tipo</legend>';

					$form .= '<div class="form-check">';
						$form .= '<label class="form-check-label">';
						$checked = ( $this->__get('leaf') == 'N' )? 'checked' : '';
						$form .= '<input type="radio" class="form-check-input" name="leaf" value="N" ' . $checked . ' >';
						$form .= 'Grupo de objetivos';
						$form .= '</label>';
					$form .= '</div>';

					$form .= '<div class="form-check">';
						$form .= '<label class="form-check-label">';
						$checked = ( $this->__get('leaf') == 'S' )? 'checked' : '';
						$form .= '<input type="radio" class="form-check-input" name="leaf" value="S" ' . $checked . ' >';
						$form .= 'Objetivo de aprendizagem';
						$form .= '</label>';
					$form .= '</div>';

				$form .= '</fieldset>';
			$form .= '</div>';


			$form .= '<div class="col-md-12">';
				$form .= '<div class="col-md-3 col-md-offset-9 col-sm-6 col-sm-offset-6 col-xs-12">';
					$form .= '<button type="submit" class="btn btn-primary btn-cor-primary btn-100" id="salvarObjetivo_' . $this->__get('id') . '">Salvar</button>';
	 			$form .= '</div>';
	 		$form .= '</div>';

		$form .= '</form>';


		echo $form;

	}

}
?>