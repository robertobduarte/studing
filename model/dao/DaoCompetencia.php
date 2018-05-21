<?php

class DaoCompetencia extends IDao{

	public function __construct(){

		parent::__construct();

	}

	//public function inserir( IObject $objeto ){}
	//public function editar( Iobject $objeto ){}
	//public function buscar( $id ){}
	public function listar(){}


	

	public function buscar( $id ){

		try{

			$sql = "SELECT * FROM competencia WHERE id = :id";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':id', $id );

			$query->execute();
			
			$competencia = $query->fetch( PDO::FETCH_ASSOC );

			return $competencia;

		}catch( Exception $e ){
			$this->conex->rollback();
			$this->falha( $this->conex->errorInfo() );
		}

	}



	public function listarByDisciplina( $disciplina_id ){

		try{

			$sql = "SELECT * FROM competencia WHERE disciplina = :disciplina_id";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':disciplina_id', $disciplina_id );

			$query->execute();

			$competencias = array();
			while( $competencia = $query->fetch( PDO::FETCH_ASSOC ) ){

				$competencias[] = $competencia;
			}

			return $competencias;

		}catch( Exception $e ){
			$this->conex->rollback();
			$this->falha( $this->conex->errorInfo() );
		}

	}


	public function getCompetenciaByDisciplina( $disciplina_id ){

		try{

			$sql = "SELECT * FROM competencia WHERE disciplina = :disciplina_id";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':disciplina_id', $disciplina_id );

			$query->execute();

			$competencias = array();
			while( $competencia = $query->fetch( PDO::FETCH_ASSOC ) ){

				$competencias[] = $competencia;
			}

			return $competencias;

		}catch( Exception $e ){
			$this->conex->rollback();
			$this->falha( $this->conex->errorInfo() );
		}

	}


	public function getCompetenciaBySlide( $slide_id ){

		try{

			$sql = "SELECT * FROM competencia_slide WHERE slide = :slide_id";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':slide_id', $slide_id );

			$query->execute();

			$competencias = array();
			while( $competencia = $query->fetch( PDO::FETCH_ASSOC ) ){

				$competencias[] = $competencia;
			}

			return $competencias;

		}catch( Exception $e ){
			$this->conex->rollback();
			$this->falha( $this->conex->errorInfo() );
		}

	}




	public function inserir( Iobject $competencia ){

		try{

			$sql = "INSERT INTO competencia ( nome, disciplina ) 
					VALUES ( :nome, :disciplina )";

			$this->conex->beginTransaction();

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':nome', $competencia->__get('nome') );
			$query->bindParam( ':disciplina', $competencia->__get('disciplina') );

			$query->execute();

            $lastId = $this->conex->lastInsertId();         
            $this->conex->commit();

            return $lastId;

		}catch( Exception $e ){

			$this->conex->rollback();
			//$this->falha( $this->conex->errorInfo() );
			return false;
		}
	}


	public function editar( Iobject $competencia ){

		try{

			$sql = "UPDATE competencia	SET 
					nome = :nome, disciplina = :disciplina
					WHERE id = :id";

			$this->conex->beginTransaction();
			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':nome', $competencia->__get('nome') );
			$query->bindParam( ':disciplina', $competencia->__get('disciplina') );
			$query->bindParam( ':id', $competencia->__get('id') );

			$query->execute();
        
            return $this->conex->commit();

		}catch( Exception $e ){

			$this->conex->rollback();
			//$this->falha( $this->conex->errorInfo() );
			return false;
		}
	}



	public function remover( $id ){

		try{

			$sql = "DELETE FROM competencia WHERE id = :id";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':id', $id );

			return $query->execute();

		}catch( Exception $e ){
			$this->conex->rollback();
			//$this->falha( $this->conex->errorInfo() );
			return false;
		}

	}




}
?>