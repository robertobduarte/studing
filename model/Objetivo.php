<?php
include_once __DIR__ . "/../config.php";

class Objetivo extends IObject{

	protected static $instances = array();
	protected $tiposDeDados = array();
	protected $controller = '../controller/controllerObjetivo.php';
	private $id;
	private $nome;
	private $descricao;
	private $objetivo_tipo;
	private $tipo;
	private $leaf;	
	private $parent;
	private $lastParent;
	private $parents = array();
	private $children = array();	
	private $tree;


	public function __construct( $dados = null ){

		parent::__construct( $dados );		
		
	}


	protected function defineTipos(){

		$this->tiposDeDados = array( 
									'id' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'nome' => array( 'type' => false, 'mandatory' => true, 'size' => 200 ),
									'peso' => array( 'type' => 'float', 'mandatory' => false, 'size' => false ),
									'objetivo_tipo' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'parent' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'leaf' => array( 'type' => false, 'mandatory' => true, 'size' => 1 ),
									'prova' => array( 'type' => false, 'mandatory' => true, 'size' => 1),
									'media' => array( 'type' => false, 'mandatory' => false, 'size' => false)
									);
	}

	public function getObjeto( $objetivo_id  ){

		$daoObjetivo = new DaoObjetivo();

		$dados = $daoObjetivo->getObjetivo( $objetivo_id );

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
	Busca todos os objetivos de um determinado domínio, iniciando pelo pai até o último filho - Recursão
	*/
	//public function getObjetivos( $dominio_id = null ) {
	public function listar( $id = null ) {

		$daoObjetivo = new DaoObjetivo();

		$dados = $daoObjetivo->startGetObjetivos();

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

    /*
    Lista a árvore de objetivos completa - Param: dominio_id
    */
    public function listObjetivos(){


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

						echo '<li class="list-unstyled objetivo_main ' . $classe . '">' . $icone .  '<a href="objetivo.php?obj=' . $objetivo->id . '" id="objetivo_' . $objetivo->id . '" class="linkObjetivo">' . $objetivo->tipo . ': ' . $objetivo->nome . '</a></li>';

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

					echo '<li class="list-unstyled objetivo_children ' . $classe . '">' . $icone . ' <a href="objetivo.php?obj=' . $children[$i]['id'] . '" id="objetivo_' . $children[$i]['id'] . '" class="linkObjetivo">' . $children[$i]['tipo'] . ': ' . $children[$i]['nome'] . '</a></li>'; 

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

		$obj_id = $daoObjetivo->insertObjetivo( $this );

		return $obj_id;

    }

    public function editar(){

    	$daoObjetivo = new DaoObjetivo();

    	$retorno = $daoObjetivo->updateObjetivo( $this );

		return $retorno;

    }


    public function buttonNovoObjetivo(){

    	echo '<div class="col-md-2 col-sm-3 col-xs-12 header">';

    	echo '<a href="objetivo.php?dmn="><button type="button" class="btn btn-primary btn-cor-primary btn-100"><i class="fa fa-plus-circle" aria-hidden="true"></i> Novo Objetivo</button></a>';
			
		echo '</div>';

    }



}
?>