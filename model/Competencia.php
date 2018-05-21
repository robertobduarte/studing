<?php
include_once __DIR__ . "/../config.php";

class Competencia extends IObject {

	public static $instances = array();
	protected $controller = '../controller/controller.php?c=competencia';
	private $id;
	private $nome;
	private $disciplina;

	public function __construct( $dados = null ){

		parent::__construct( $dados );
	}



	public function listar( $disciplina = null ){


		/*$disciplina_id = ( $disciplina == null )? $this->__get('disciplina'): $disciplina;{
		
		$daoCompetencia = new DaoCompetencia);

		$competencias = $daoCompetencia->listarByDisciplina( $disciplina_id );
		
		if( $competencia ){

			foreach ( $competencia as $value ) {

				$disciplina = new Disciplina();
				$disciplina->__set( $disciplina, $value );
		
				Disciplina::$instances[] = $disciplina; 
			}
		}

		return Disciplina::$instances;*/

	}
	

	public function getCompetenciaByDisciplina( $disciplina_id ){
	
		$daoCompetencia = new DaoCompetencia();

		$dados = $daoCompetencia->getCompetenciaByDisciplina( $disciplina_id );
		
		if( $dados ){

			Competencia::$instances = '';

			foreach ( $dados as $value ) {

				$competencia = new Competencia();
				$competencia->__set( $competencia, $value );
		
				Competencia::$instances[] = $competencia; 
			}
		}

		return Competencia::$instances;

	}	


	public function getCompetenciaBySlide( $slide_id ){
	
		$daoCompetencia = new DaoCompetencia();

		$dados = $daoCompetencia->getCompetenciaBySlide( $slide_id );
		
		Competencia::$instances = '';

		if( $dados ){			

			foreach ( $dados as $value ) {

				$competencia = new Competencia( array( 'id' => $value['competencia'] ) );
		
				Competencia::$instances[] = $competencia; 
			}
		}
		
		return Competencia::$instances;

	}



	protected function defineTipos(){

		$this->tiposDeDados = array( 
									'id' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'nome' => array( 'type' => false, 'mandatory' => true, 'size' => 200 ),
									'disciplina' => array( 'type' => false, 'mandatory' => false, 'size' => false )
									);

	}


	public function getObjeto( $competencia_id ){

		$daoCompetencia = new DaoCompetencia();

		$competencia = $daoCompetencia->buscar( $competencia_id );

		$this->__set( $this, $competencia );

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
	


    public function novo(){

    	$daoCompetencia = new DaoCompetencia();

    	$id = $daoCompetencia->inserir( $this );

		return $id;

    }

       
    public function editar(){

    	$daoCompetencia = new DaoCompetencia();

    	$retorno = $daoCompetencia->editar( $this );

		return $retorno;

    }


    public function remover(){

    	$daoCompetencia = new DaoCompetencia();

    	$retorno = $daoCompetencia->remover( $this->__get('id') );

		return $retorno;

    }



}
?>