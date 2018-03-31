<?php 

class DaoUsuario extends IDao{

	public function __construct(){

		parent::__construct();

	}

	public function inserir( IObject $objeto ){

	}

	public function editar( Iobject $objeto ){

	}

	public function getUsuarioByUser( $userName ){

		$pos = strpos( $userName, '@' );
		$userName = ( $pos )? substr( $userName, 0, $pos ): $userName;

		try {

            $sql = "SELECT * FROM usuario as u
					inner join pessoa as p on (p.id = u.pessoa)
					where u.usuario LIKE '". $userName . "@%'";
			
			$query = $this->conex->prepare( $sql );
            $query->execute(); 
            
			$usuario = $query->fetch(PDO::FETCH_ASSOC);
			
			return $usuario;

        }catch( Exception $e){

			$this->conex->rollback();
            $this->falha( $this->conex->errorInfo() );
		}
        
	}


	public function buscar( $usuarioId ){


		try {

            $sql = "SELECT * FROM usuario WHERE u.id = :usuarioId";
			
			$query = $this->conex->prepare( $sql );
			$query->bindParam( 'usuarioId', $usuarioId );
			
            $query->execute(); 
            
			$usuario = $query->fetch(PDO::FETCH_ASSOC);
			
			return $usuario;

        }catch( Exception $e){

			$this->conex->rollback();
            $this->falha( $this->conex->errorInfo() );
		}
        
	}


	public function listar(){

		try {

			$sql = "select * from usuario ORDER BY usuario";

			$query = $this->conex->prepare( $sql );
			
	        $query->execute(); 
	            
	        $usuarios = array();
				
			while( $usuario = $query->fetch(PDO::FETCH_ASSOC) ){

				$usuarios[] = $usuario;
			}
				
			return $usuarios;

        }catch( Exception $e){

			$this->conex->rollback();
            $this->falha( $this->conex->errorInfo() );
		}
        
	}





}
?>