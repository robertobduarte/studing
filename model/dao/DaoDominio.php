<?php

class DaoDominio extends IDao{

	public function __construct(){

		parent::__construct();

	}


	public function listar(){}
	/*
	Lista todos os domínios existentes (utilizado quando o perfil é ADM)
	*/
	public function getDominios(){

		try {

            $sql = "SELECT * FROM dominio";
			
			$query = $this->conex->prepare( $sql ); 

            $query->execute(); 
            
            $dominios = array();

            while( $dominio = $query->fetch( PDO::FETCH_ASSOC ) ){

            	$dominios[] = $dominio;
            }
			
			return $dominios;

        } catch (Exception $e) {

            $this->conex->rollback();
            echo $e->getTraceAsString();
        }

	}	


		/*
	busca os dados do domínio, tendo como parâmetro o id do domínio
	*/
	public function getDominioById( $id ){

		try{

			$sql = "SELECT * FROM dominio WHERE id = :id";

			$query = $this->conex->prepare( $sql );
            $query->bindParam( ':id', $id );

            $query->execute();

            $dominio = $query->fetch( PDO::FETCH_ASSOC );

            return $dominio;

		}catch( Exception $e ){

			$this->conex->rollback();
            echo $e->getTraceAsString();
		}

	}


	/*
	Lista todos os tipos de objetivos criados para um determinado dominio
	*/
	public function getTiposObjetivos( $dominio ){

		try {

            $sql = "SELECT id, nome as objetivoTipos FROM objetivo_tipo WHERE dominio = :dominio";
			
			$query = $this->conex->prepare( $sql ); 
			$query->bindParam( ':dominio', $dominio ); 

            $query->execute(); 
            
            $tiposObjetivos = array();

            while( $tipoObj = $query->fetch( PDO::FETCH_ASSOC ) ){

            	$tiposObjetivos[] = $tipoObj;
            }
			
			return $tiposObjetivos;

        } catch (Exception $e) {

            $this->conex->rollback();
            echo $e->getTraceAsString();
        }

	}


	/*
	Lista todos os domínios que o usuário possui acesso
	*/
	public function getDominiosUsuario( $usuarioId ){

		try {

            $sql = "SELECT d.* FROM dominio d
            		INNER JOIN dominio_usuario du ON (du.dominio = d.id)
					where du.usuario = :usuarioId";
			
			$query = $this->conex->prepare( $sql );
            $query->bindParam( 'usuarioId', $usuarioId ); 

            $query->execute(); 
            
            $dominios = array();

            while( $dominio = $query->fetch( PDO::FETCH_ASSOC ) ){

            	$dominios[] = $dominio;
            }
			
			return $dominios;

        } catch (Exception $e) {

            $this->conex->rollback();
            return false;
            //echo $e->getTraceAsString();
        }

	}


	/*
	busca os dados do domínio, tendo como parâmetro o id do domínio
	*/
	public function buscar( $id ){

		try{

			$sql = "SELECT * FROM dominio WHERE id = :id";

			$query = $this->conex->prepare( $sql );
            $query->bindParam( ':id', $id );

            $query->execute();

            $dominio = $query->fetch( PDO::FETCH_ASSOC );

            return $dominio;

		}catch( Exception $e ){

			$this->conex->rollback();
            echo $e->getTraceAsString();
		}

	}



	public function inserir( IObject $m_dominio ){

		try{

			$sql = "INSERT INTO dominio ( nome, alias, descricao, diretorio, css, mensagem ) 
					VALUES ( :nome, :alias, :descricao, :diretorio, :css, :mensagem )";

			$this->conex->beginTransaction();

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':nome', $m_dominio->__get('nome') );
			$query->bindParam( ':alias', $m_dominio->__get('alias') );
			$query->bindParam( ':descricao', $m_dominio->__get('descricao') );
			$query->bindParam( ':diretorio', $m_dominio->__get('diretorio') );
			$query->bindParam( ':css', $m_dominio->__get('css') );
			$query->bindParam( ':mensagem', $m_dominio->__get('mensagem') );

			$query->execute();

            $lastId = $this->conex->lastInsertId();         
            $this->conex->commit();

            return $lastId;

		}catch( Exception $e ){

			$this->conex->rollback();
			return false;
		}
	}

	public function editar( IObject $m_dominio ){

		try{

			$sql = "UPDATE dominio SET 
					nome = :nome, alias = :alias, descricao = :descricao, mensagem = :mensagem
					WHERE id = :id";

			$this->conex->beginTransaction();
			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':nome', $m_dominio->__get('nome') );
			$query->bindParam( ':alias', $m_dominio->__get('alias') );
			$query->bindParam( ':descricao', $m_dominio->__get('descricao') );
			$query->bindParam( ':mensagem', $m_dominio->__get('mensagem') );
			$query->bindParam( ':id', $m_dominio->__get('id') );

			$query->execute();
        
            return $this->conex->commit();

		}catch( Exception $e ){

			$this->conex->rollback();
			return false;
		}
	}



	public function remover( $id ){

		try{

			$sql = "DELETE FROM dominio WHERE id = :id";

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':id', $id );

			return $query->execute();

		}catch( Exception $e ){
			$this->conex->rollback();
			//return $e->getTraceAsString();
			return false;
		}

	}



}
?>