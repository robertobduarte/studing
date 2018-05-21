<?php 

class DaoAlternativa extends IDao{

	public function __construct(){

		parent::__construct();

	}

	public function listar(){}
	//public function inserir( IObject $objeto ){}
	//public function editar( Iobject $objeto ){}
	public function buscar( $id ){}

	/*
	retorna a alterantiva 
	*/
	public function getAlternativa( $alternativa_id ){
	
		try{

			$sql = "SELECT a.*, at.nome as tipo_nome FROM alternativa a
					INNER JOIN alternativa_tipo at ON ( a.alternativa_tipo = at.id )
					WHERE a.id = :alternativa_id";

			$query = $this->conex->prepare( $sql );
			
			$query->bindParam( ':alternativa_id', $alternativa_id );
			$query->execute();

			$alternativa = $query->fetch( PDO::FETCH_ASSOC );

			return $alternativa;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}
	}


	public function getTiposAlternativa(){

		try{

			$sql = "SELECT * FROM alternativa_tipo";

			$query = $this->conex->prepare( $sql );
			
			$query->execute();

			$tipos = array();
			while( $tipo = $query->fetch( PDO::FETCH_ASSOC ) ) {

				$tipos[] = $tipo;
			}

			return $tipos;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}	
	}


	/*
	retorna um array de alterantivas pertencentes a um slide. Param slide_id 
	*/
	public function getAlternativasBySlide( $slide_id ){
	
		try{

			$sql = "SELECT a.*, at.nome as tipo_nome FROM alternativa a
					INNER JOIN alternativa_tipo at ON ( a.tipo = at.id )
					WHERE slide = :slide_id ORDER BY a.valor ASC";

			$query = $this->conex->prepare( $sql );
			
			$query->bindParam( ':slide_id', $slide_id );
			$query->execute();

			$alternativas = array();
			while( $alternativa = $query->fetch( PDO::FETCH_ASSOC ) ){

				$alternativas[] = $alternativa;
			}
			
			return $alternativas;


		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}
	}



	public function inserir( IObject $alternativa ){

		$m_session = new Session();

		try{
			
			$sql = "INSERT INTO alternativa ( slide, valor, texto, texto_html, nome_arquivo, caminho, alternativa_tipo, usuario, incluidoem ) 
					values( :slide, :valor, :texto,  :texto_html, :nome_arquivo, :caminho, :alternativa_tipo, :usuario, NOW() )";

			$this->conex->beginTransaction();

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':slide', $alternativa->__get('slide') );
			$query->bindParam( ':valor', $alternativa->__get('valor') );
			$query->bindParam( ':texto', $alternativa->__get('texto') );
			$query->bindParam( ':texto_html', $alternativa->__get('texto_html') );
			$query->bindParam( ':nome_arquivo', $alternativa->__get('nome_arquivo') );
			$query->bindParam( ':caminho', $alternativa->__get('caminho') );
			$query->bindParam( ':alternativa_tipo', $alternativa->__get('alternativa_tipo') );
			$query->bindParam( ':usuario', $m_session->getValue( 'usuarioid' ) );

			$query->execute();

            $lastId = $this->conex->lastInsertId('alternativa_id_seq');         
            $this->conex->commit();

            return $lastId;
			
		}catch( Exception $e ){

			$this->conex->rollback();
			return false;
		}
	}



	public function editar( IObject $alternativa ){

		try{
			
			$sql = "UPDATE alternativa	SET 
					slide = :slide, valor = :valor, texto = :texto, texto_html = :texto_html, nome_arquivo = :nome_arquivo, caminho = :caminho, alternativa_tipo = :alternativa_tipo
					WHERE id = :id";

			$this->conex->beginTransaction();
			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':slide', $alternativa->__get('slide') );
			$query->bindParam( ':valor', $alternativa->__get('valor') );
			$query->bindParam( ':texto', $alternativa->__get('texto') );
			$query->bindParam( ':texto_html', $alternativa->__get('texto_html') );
			$query->bindParam( ':nome_arquivo', $alternativa->__get('nome_arquivo') );
			$query->bindParam( ':caminho', $alternativa->__get('caminho') );
			$query->bindParam( ':alternativa_tipo', $alternativa->__get('alternativa_tipo') );
			$query->bindParam( ':id', $alternativa->__get('id') );

			$query->execute();
        
            return $this->conex->commit();
			
		}catch( Exception $e ){

			$this->conex->rollback();
			return false;
		}
	}



	public function remover( Alternativa $alternativa ){

		try{

			$sql = "DELETE FROM alternativa WHERE id = :alternativa_id";

			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':alternativa_id', $alternativa->__get('id') );

			return $query->execute();

		}catch( Exception $e ){

			$this->conex->rollback();
			return false;			
		}
	}

	/*
	public function removerAlternativaBySlideId( $slide_id ){

		try{

			$sql = "DELETE FROM alternativa WHERE slide = :slide_id";

			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':slide_id', $slide_id );

			return $query->execute();

		}catch( Exception $e ){

			$this->conex->rollback();
			return false;
		}
	}
	*/





}
?>