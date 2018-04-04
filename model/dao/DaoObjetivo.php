<?php 
include_once __DIR__ . "/../config.php";


class DaoObjetivo extends IDao {


	public function __construct(){

		parent::__construct();

	}

	public function listar(){}


	public function startGetObjetivos() {

		try{

			$sql = "SELECT o.*, ot.nome as tipo FROM objetivo o
					INNER JOIN objetivo_tipo ot ON ( ot.id = o.objetivo_tipo)
					WHERE parent IS NULL";
			
			$query = $this->conex->prepare( $sql );
		
			$query->execute();

			$parents = array();			
			while( $parent = $query->fetch( PDO::FETCH_ASSOC ) ){

				$parents[] = $parent;
			}

			$tree = null;

			$tree = $this->getObjetivosFilhos( $parents );

			return $tree;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();
		}

	}


	public function getObjetivosFilhos( $parents ){
	
		try{

			$tree = array();

			foreach( $parents as $parent ) {

				$sql = "SELECT o.*, ot.nome as tipo FROM objetivo o
						INNER JOIN objetivo_tipo ot ON ( ot.id = o.objetivo_tipo)
						WHERE o.parent = :parent";


				$query = $this->conex->prepare( $sql );
			
				$query->bindParam( ':parent', $parent['id'] );
				$query->execute();

				$childrens = array();			
				while( $children = $query->fetch( PDO::FETCH_ASSOC ) ){

					$childrens[] = $children;
				}

				//apenas com as informações necessárias para listar a árvore de objetivos
				$tree[] = array('id' => $parent['id'], 'nome' => $parent['nome'], 'tipo' => $parent['tipo'], 'leaf' => $parent['leaf'], 'parent' => $parent['parent'], 'children' => $this->getObjetivosFilhos( $childrens ) );
				
			}
			
			return $tree;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}
	}



	public function getParent( $objetivo_id, &$pathToParent ) {

		try{

			$sql = "SELECT o.*, ot.nome as tipo FROM objetivo o
					INNER JOIN objetivo_tipo ot ON ( ot.id = o.objetivo_tipo )
					WHERE o.id = :objetivo_id ";

			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':objetivo_id', $objetivo_id );
			$query->execute();

			$objeto = $query->fetch( PDO::FETCH_ASSOC );
			
			if( !empty( $objeto ) ) {

				$pathToParent[] = array( 'id' => $objeto['id'], 'nome' => $objeto['nome'], 'tipo' => $objeto['tipo'] );

				$this->getParent( $objeto['parent'], $pathToParent ); 
			}
			
			return $pathToParent;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}

	}


	public function getLastParent( $objetivo_id, &$obj ) {

		try{

			$sql = "SELECT o.*, ot.nome as tipo FROM objetivo o
					INNER JOIN objetivo_tipo ot ON ( ot.id = o.objetivo_tipo )
					WHERE o.id = :objetivo_id ";

			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':objetivo_id', $objetivo_id );
			$query->execute();

			$objeto = $query->fetch( PDO::FETCH_ASSOC );
			
			if( !empty( $objeto ) ) {

				$obj = $objeto;

				if( $objeto['parent'] != '' ){

					$this->getLastParent( $objeto['parent'], $obj );
				}										
			}	

			return $obj;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}

	}


	public function getChildren( $objetivo_id ) {

		try{

			$sql = "SELECT o.*, ot.nome as tipo FROM objetivo o
					INNER JOIN objetivo_tipo ot ON ( ot.id = o.objetivo_tipo)
					WHERE o.id = :objetivo_id";

			$query = $this->conex->prepare( $sql );
			
			$query->bindParam( ':objetivo_id', $objetivo_id );
			$query->execute();

			$objetivo = $query->fetch( PDO::FETCH_ASSOC );

			$tree = null;
			$tree = $this->getObjetivosFilhos( array( $objetivo ) );			
			
			return $tree;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}

	}	


	public function buscar( $objetivo_id ){
	//public function getObjetivo( $objetivo_id ){
	
		try{

			$sql = "SELECT o.*, ot.nome as tipo FROM objetivo o
					INNER JOIN objetivo_tipo ot ON ( ot.id = o.objetivo_tipo)
					WHERE o.id = :objetivo_id";

			$query = $this->conex->prepare( $sql );
			
			$query->bindParam( ':objetivo_id', $objetivo_id );
			$query->execute();

			$objetivo = $query->fetch( PDO::FETCH_ASSOC );

			return $objetivo;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}
	}


	public function getObject( $objetivo_id ){
	
		try{

			$sql = "SELECT o.*, ot.nome as tipo FROM objetivo o
					INNER JOIN objetivo_tipo ot ON ( ot.id = o.objetivo_tipo)
					WHERE o.id = :objetivo_id";

			$query = $this->conex->prepare( $sql );
			
			$query->bindParam( ':objetivo_id', $objetivo_id );
			$query->execute();

			$objetivo = $query->fetch( PDO::FETCH_ASSOC );

			//return array( $objetivo );
			return $objetivo;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}
	}



	public function inserir( Iobject $objetivo ){
	//public function insertObjetivo( $objetivo ){

		try{

			$sql = "INSERT INTO objetivo ( nome, descricao, objetivo_tipo, leaf, parent ) 
					VALUES ( :nome, :descricao, :objetivo_tipo, :leaf, :parent )";

			$this->conex->beginTransaction();

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':nome', $objetivo->__get('nome') );
			$query->bindParam( ':descricao', $objetivo->__get('descricao') );
			$query->bindParam( ':objetivo_tipo', $objetivo->__get('objetivo_tipo') );
			$query->bindParam( ':leaf', $objetivo->__get('leaf') );
			$query->bindParam( ':parent', $objetivo->__get('parent') );

			$query->execute();

            $lastId = $this->conex->lastInsertId('objetivo_id_seq');         
            $this->conex->commit();

            return $lastId;

		}catch( Exception $e ){

			$this->conex->rollback();
			return false;
		}
	}

	public function editar( Iobject $objetivo ){
	//public function updateObjetivo( Objetivo $objetivo ){

		try{

			$sql = "UPDATE objetivo	SET 
					nome = :nome, descricao = :descricao, objetivo_tipo = :objetivo_tipo, leaf = :leaf, parent = :parent
					WHERE id = :id";

			$this->conex->beginTransaction();
			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':nome', $objetivo->__get('nome') );
			$query->bindParam( ':descricao', $objetivo->__get('descricao') );
			$query->bindParam( ':leaf', $objetivo->__get('leaf') );
			$query->bindParam( ':parent', $objetivo->__get('parent') );
			$query->bindParam( ':id', $objetivo->__get('id') );

			$query->execute();
        
            return $this->conex->commit();

		}catch( Exception $e ){

			$this->conex->rollback();
			return false;
		}
	}


	public function getObjetivosPai( $dominio_id ){

		try{

			$sql = "SELECT o.*, ot.nome as tipo FROM objetivo o
					INNER JOIN objetivo_tipo ot ON ( ot.id = o.objetivo_tipo)
					WHERE parent IS NULL";
			
			$query = $this->conex->prepare( $sql );
		
			$query->execute();
			
			$objetivos = array();			
			while( $objetivo = $query->fetch( PDO::FETCH_ASSOC ) ){
				$objetivos[] = $objetivo;
			}

			return $objetivos;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();
		}

	}


	

}
?>