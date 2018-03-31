<?php


class DaoPessoa extends IDao{


	public function __construct(){

		parent::__construct();

	}


	public function inserir( IObject $objeto ){

	}


	public function editar( Iobject $objeto ){

	}


	public function buscar( $pessoa_id ){

		try {

            $sql = "SELECT * FROM pessoa WHERE id = :pessoa_id";
			
			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':pessoa_id', $pessoa_id );
            $query->execute(); 
            
			$pessoa = $query->fetch(PDO::FETCH_ASSOC);
			
			return $pessoa;

        }catch( Exception $e){

			$this->conex->rollback();
            $this->falha( $this->conex->errorInfo() );
		}
        
	}



	public function listar(){

		try {

			$sql = "SELECT * FROM pessoa";

			$query = $this->conex->prepare( $sql );
			
           	$query->execute(); 
            
            $pessoas = array();
			while( $pessoa = $query->fetch(PDO::FETCH_ASSOC) ){

				$pessoas[] = $pessoa;
			}
			
			return $pessoas;

        }catch( Exception $e){

			$this->conex->rollback();
            $this->falha( $this->conex->errorInfo() );
		}
        
	}






}
?>