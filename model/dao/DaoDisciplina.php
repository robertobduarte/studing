<?php

class DaoDisciplina extends IDao{

	public function __construct(){

		parent::__construct();

	}

	//public function inserir( IObject $objeto ){}
	//public function editar( Iobject $objeto ){}
	//public function buscar( $id ){}
	public function listar(){}


	

	public function buscar( $disciplina_id ){

		try{

			$sql = "SELECT * FROM disciplina WHERE id = :disciplina_id";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':disciplina_id', $disciplina_id );

			$query->execute();

			$disciplina = $query->fetch( PDO::FETCH_ASSOC );

			return $disciplina;


		}catch( Exception $e ){

			$this->conex->rollback();
			$this->falha( $this->conex->errorInfo() );
		}

	}	


	public function listarByDominio( $dominio_id ){

		try{

			$sql = "SELECT * FROM disciplina WHERE dominio = :dominio_id";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':dominio_id', $dominio_id );

			$query->execute();

			$disciplinas = array();
			while( $disciplina = $query->fetch( PDO::FETCH_ASSOC ) ){

				$disciplinas[] = $disciplina;
			}

			return $disciplinas;

		}catch( Exception $e ){
			$this->conex->rollback();
			$this->falha( $this->conex->errorInfo() );
		}

	}


	public function listarUtilizadasObjetivo( $objetivo_id ){

		try{

			$sql = "SELECT d.* from disciplina_objetivo diso
					INNER JOIN disciplina d on (diso.disciplina = d.id)
					WHERE  objetivo = :objetivo_id";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':objetivo_id', $objetivo_id );

			$query->execute();

			$disciplinas = array();
			while( $disciplina = $query->fetch( PDO::FETCH_ASSOC ) ){

				$disciplinas[] = $disciplina;
			}

			return $disciplinas;

		}catch( Exception $e ){
			$this->conex->rollback();
			$this->falha( $this->conex->errorInfo() );
		}

	}


	public function listarDisponiveisObjetivo( $dominio_id, $objetivo_id ){

		try{

			$sql = "SELECT * FROM disciplina
					WHERE dominio = :dominio_id 
					and id not in ( SELECT disciplina from disciplina_objetivo WHERE objetivo = :objetivo_id )";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':dominio_id', $dominio_id );
			$query->bindParam( ':objetivo_id', $objetivo_id );

			$query->execute();

			$disciplinas = array();
			while( $disciplina = $query->fetch( PDO::FETCH_ASSOC ) ){

				$disciplinas[] = $disciplina;
			}

			return $disciplinas;

		}catch( Exception $e ){
			$this->conex->rollback();
			$this->falha( $this->conex->errorInfo() );
		}

	}


	public function getCompetencias( $disciplina_id ){

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


	public function addDisciplinaObjetivo( $disciplina_id, $objetivo_id ){

		try{

			$sql = "INSERT INTO disciplina_objetivo (disciplina, objetivo) 
					VALUES ( :disciplina_id, :objetivo_id )";
			
			$this->conex->beginTransaction();

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':disciplina_id', $disciplina_id );
			$query->bindParam( ':objetivo_id', $objetivo_id );

			$query->execute();

			return $this->conex->commit();

		}catch( Exception $e ){
			$this->conex->rollback();
			$this->falha( $this->conex->errorInfo() );
		}

	}


	public function rmDisciplinaObjetivo( $disciplina_id, $objetivo_id ){

		try{

			$sql = "DELETE FROM disciplina_objetivo 
					WHERE disciplina = :disciplina_id AND objetivo = :objetivo_id";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':disciplina_id', $disciplina_id );
			$query->bindParam( ':objetivo_id', $objetivo_id );

			return $query->execute();

		}catch( Exception $e ){
			$this->conex->rollback();
			$this->falha( $this->conex->errorInfo() );
		}

	}




	public function inserir( Iobject $disciplina ){

		try{

			$sql = "INSERT INTO disciplina ( nome, descricao, dominio ) 
					VALUES ( :nome, :descricao, :dominio )";

			$this->conex->beginTransaction();

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':nome', $disciplina->__get('nome') );
			$query->bindParam( ':descricao', $disciplina->__get('descricao') );
			$query->bindParam( ':dominio', $disciplina->__get('dominio') );

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


	public function editar( Iobject $disciplina ){

		try{

			$sql = "UPDATE disciplina	SET 
					nome = :nome, descricao = :descricao
					WHERE id = :id";

			$this->conex->beginTransaction();
			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':nome', $disciplina->__get('nome') );
			$query->bindParam( ':descricao', $disciplina->__get('descricao') );
			$query->bindParam( ':id', $disciplina->__get('id') );

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

			$sql = "DELETE FROM disciplina WHERE id = :id";

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