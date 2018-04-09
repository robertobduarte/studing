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

            $sql = "SELECT u.*, p.nome, p.email, pp.permissao, pe.nome as perfil_nome FROM usuario as u
					inner join pessoa as p on (p.id = u.pessoa)
					inner join perfil_permissao as pp on (pp.perfil = u.perfil)
					inner join perfil as pe on (pe.perfil = pp.perfil)
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


	public function getPermissoesPerfil( $perfil ){

		try {

            $sql = "SELECT permissao FROM perfil_permissao WHERE perfil = :perfil";
			
			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':perfil', $perfil );

            $query->execute(); 
            
			//$permissoes = array();

			$permissao = $query->fetch(PDO::FETCH_ASSOC);
			/*while( $permissao = $query->fetch(PDO::FETCH_ASSOC) ){

				$permissoes[] = $permissao;
			}

			return $permissoes;*/
			return $permissao['permissao'];

        } catch (Exception $e) {
            $this->conex->rollback();
            echo $e->getTraceAsString();
        }

	}


	public function checkAcessDominio( $dominio_id,  $usuario_id  ){

		try {

            //$sql = "SELECT perfil FROM dominio_usuario WHERE usuario = :usuario_id AND dominio = :dominio_id";
           
            $sql = "SELECT d.perfil, p.nome FROM dominio_usuario d
					INNER JOIN perfil p ON (p.perfil = d.perfil)
					WHERE usuario = :usuario_id AND dominio = :dominio_id";
			
			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':dominio_id', $dominio_id );
			$query->bindParam( ':usuario_id', $usuario_id );
            $query->execute(); 
            
			$dominio_perfil = $query->fetch(PDO::FETCH_ASSOC);

			return $dominio_perfil;

        } catch (Exception $e) {
            $this->conex->rollback();
            echo $e->getTraceAsString();
        }

	}

}
?>