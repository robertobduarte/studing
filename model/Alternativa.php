<?php
include_once __DIR__ . "/../config.php";

class Alternativa extends IObject{

	static $instances = array();
	//private $tiposDeDados = array();
	protected $controller = '../controller/controller.php?c=alternativa';
	private $id;
	private $slide;
	private $m_slide; //Object Slide
	private $valor;
	private $texto;
	private $texto_html;
	private $arquivo;
	private $tipo; //id do tipo de alternativa (AT, AH)
	private $tipo_nome;// nome do tipo da alternativa (Alternativa Texto , Alternativa Html)	
	private $tipos;	//tipos possíveis para a alternativa (para carregar no dropdown)


	/*public function __construct( $dados = null ){

		$this->defineTipos();

		if( isset( $dados['id'] ) && !empty( $dados['id'] ) ){

			$this->getAlternativa( $dados['id'] );
		}
	}*/
	public function __construct( $dados = null ){

		parent::__construct( $dados );
	}


	public function listar( $id = null ){}

	protected function defineTipos(){

		$this->tiposDeDados = array( 
									'id' => array( 'type' => 'int', 'mandatory' => false, 'size' => false ),
									'slide' => array( 'type' => 'int', 'mandatory' => true, 'size' => false ),
									'valor' => array( 'type' => false, 'mandatory' => true, 'size' => 1 ),						
									'alternativa_tipo' => array( 'type' => false, 'mandatory' => true, 'size' => 3 )						
									);
		
	}


	public function getObjeto( $slide_id  ){

		$daoSlide = new DaoSlide();

		$dados = $daoSlide->buscar( $slide_id );

		$this->__set( $this, $dados );
	}


	private function getAlternativa( $alternativa_id  ){

		$daoAlternativa = new DaoAlternativa();

		$dados = $daoAlternativa->getAlternativa( $alternativa_id );
		
		$this->__set( $this, $dados );
	}



	public function getTiposAlternativa(){

		$daoAlternativa = new daoAlternativa();

		$tipoAlternativas = $daoAlternativa->getTiposAlternativa();

		$this->tipos = $tipoAlternativas;

	}


	/*
	retorna um array de Alternativas pertencentes a um Slide
	*/
	public function getAlternativasBySlide( $slide_id  ){

		$daoAlternativa = new DaoAlternativa();

		$dados = $daoAlternativa->getAlternativasBySlide( $slide_id );

		if( !empty( $dados ) ){

			//Alternativa::$instances = '';

			foreach ( $dados as $value ) {
				
				$alternativa = new Alternativa();
				$alternativa->__set( $alternativa, $value );
				Alternativa::$instances[] = $alternativa; 

			}
			
		}

		/*echo '<pre>';
		print_r($dados);
		//print_r(Alternativa::$instances);
		echo '</pre>';*/
		return Alternativa::$instances;
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

	 		return $this->$name;
	 	}
    }


    public function novo(){

	    $daoAlternativa = new DaoAlternativa();

		$alternativa_id = $daoAlternativa->inserir( $this );

		$this->id = ( $alternativa_id )? $alternativa_id : '';

		return $alternativa_id;

    }


    public function editar(){

    	$daoAlternativa = new DaoAlternativa();

	    $retorno = $daoAlternativa->editar( $this );

		return $retorno;
    }


    public function remover(){

    	$daoAlternativa = new DaoAlternativa();

	    $retorno = $daoAlternativa->remover( $this );

		return $retorno;	
    }

    /*
    Param slide_id - remove todas as alternativas de um slide
    */
    public function removerAlternativaBySlideId( $slide_id ){

    	$daoAlternativa = new DaoAlternativa();

    	return $daoAlternativa->removerAlternativaBySlideId( $slide_id );

    }

    


 



}
?>