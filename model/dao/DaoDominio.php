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
	Lista todos os modelos de objetos de um determinado dominio
	*/
	/*public function getModelosByDominio( $dominio ){

		try {

            $sql = "SELECT * FROM modelo_objeto WHERE dominio = :dominio";
			
			$query = $this->conex->prepare( $sql ); 
			$query->bindParam( ':dominio', $dominio ); 

            $query->execute(); 
            
            $modelosObjeto = array();

            while( $modelo = $query->fetch( PDO::FETCH_ASSOC ) ){

            	$modelosObjeto[] = $modelo;
            }
			
			return $modelosObjeto;

        } catch (Exception $e) {

            $this->conex->rollback();
            echo $e->getTraceAsString();
        }

	}*/



	/*
	Retorna um modelo de objeto
	*/
	/*public function getModeloObjeto( $modeloObjeto_id ){

		try {

            $sql = "SELECT * FROM modelo_objeto as mo
            		WHERE mo.id = :modeloObjeto_id";
			
			$query = $this->conex->prepare( $sql ); 
			$query->bindParam( ':modeloObjeto_id', $modeloObjeto_id ); 

            $query->execute(); 
            
            $modeloObjeto = $query->fetch( PDO::FETCH_ASSOC );
			
			return $modeloObjeto;

        } catch (Exception $e) {

            $this->conex->rollback();
            echo $e->getTraceAsString();
        }

	}*/


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

			$sql = "INSERT INTO dominio ( nome, alias, descricao, imagem, diretorio, css, num_questoes, media, prazo, msg_inicial ) 
					VALUES ( :nome, :alias, :descricao, :imagem, :diretorio, :css, :num_questoes, :media, :prazo, :msg_inicial)";

			$this->conex->beginTransaction();

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':nome', $m_dominio->__get('nome') );
			$query->bindParam( ':alias', $m_dominio->__get('alias') );
			$query->bindParam( ':descricao', $m_dominio->__get('descricao') );
			$query->bindParam( ':imagem', $m_dominio->__get('imagem') );
			$query->bindParam( ':diretorio', $m_dominio->__get('diretorio') );
			$query->bindParam( ':css', $m_dominio->__get('css') );
			$query->bindParam( ':num_questoes', $m_dominio->__get('num_questoes') );
			$query->bindParam( ':media', $m_dominio->__get('media') );
			$query->bindParam( ':prazo', $m_dominio->__get('prazo') );
			$query->bindParam( ':msg_inicial', $m_dominio->__get('msg_inicial') );

			$query->execute();

            $lastId = $this->conex->lastInsertId('dominio_id_seq');         
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
					nome = :nome, alias = :alias, descricao = :descricao, imagem = :imagem, num_questoes = :num_questoes, media= :media, prazo = :prazo, msg_inicial = :msg_inicial
					WHERE id = :id";

			$this->conex->beginTransaction();
			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':nome', $m_dominio->__get('nome') );
			$query->bindParam( ':alias', $m_dominio->__get('alias') );
			$query->bindParam( ':descricao', $m_dominio->__get('descricao') );
			$query->bindParam( ':imagem', $m_dominio->__get('imagem') );
			$query->bindParam( ':num_questoes', $m_dominio->__get('num_questoes') );
			$query->bindParam( ':media', $m_dominio->__get('media') );
			$query->bindParam( ':prazo', $m_dominio->__get('prazo') );
			$query->bindParam( ':msg_inicial', $m_dominio->__get('msg_inicial') );
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