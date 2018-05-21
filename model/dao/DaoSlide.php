<?php

class DaoSlide extends IDao{

	public function __construct(){

		parent::__construct();

	}

	//public function inserir( IObject $objeto ){}
	//public function editar( Iobject $objeto ){}
	//public function buscar( $id ){}
	public function listar(){}


	
public function buscar( $slide_id ){
	
		try{

			$sql = "SELECT s.*, st.nome as tipo from slide s
					INNER JOIN slide_tipo st ON ( st.id = s.slide_tipo)
					WHERE s.id = :slide_id";

			$query = $this->conex->prepare( $sql );
			
			$query->bindParam( ':slide_id', $slide_id );
			$query->execute();

			$slide = $query->fetch( PDO::FETCH_ASSOC );

			return $slide;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}
	}


	public function getTipoSlide(){

		try{

			$sql = "SELECT * FROM slide_tipo";

			$query = $this->conex->prepare( $sql );
			
			$query->execute();

			$tipos = array();
			while( $tipo = $query->fetch( PDO::FETCH_ASSOC ) ) {

				$tipos[] = $tipo;
			}

			return $tipos;

		}catch( Exception $e ){

			$this->conex->rollback();
			return false;

		}	
	}


	public function getSlidesByObjetivo( $objetivo_id, $disciplina_id ){
	

		try{

			if( !empty( $disciplina_id ) ){

				$sql = "SELECT s.*, st.nome as tipo FROM slide s
					INNER JOIN slide_tipo st ON ( st.id = s.slide_tipo)
					WHERE s.objetivo = :objetivo_id AND disciplina = :disciplina_id order by s.posicao";

				$query = $this->conex->prepare( $sql );
				
				$query->bindParam( ':objetivo_id', $objetivo_id );
				$query->bindParam( ':disciplina_id', $disciplina_id );

			}else{

				$sql = "SELECT s.*, st.nome as tipo FROM slide s
					INNER JOIN slide_tipo st ON ( st.id = s.slide_tipo)
					WHERE s.objetivo = :objetivo_id order by s.posicao";

				$query = $this->conex->prepare( $sql );
				
				$query->bindParam( ':objetivo_id', $objetivo_id );

			}
			
			$query->execute();

			$slides = array();
			while( $slide = $query->fetch( PDO::FETCH_ASSOC ) ) {

				$slides[] = $slide;
			}

			return $slides;

		}catch( Exception $e ){

			$this->conex->rollback();
			echo $e->getTraceAsString();

		}
	}




	public function inserir( IObject $slide ){

		/*echo '<pre>';
		print_r($slide);
		exit();*/
		$m_session = new Session();

		try{
			
			$sql = "INSERT INTO slide ( titulo, enunciado, enunciado_html, content_html, objetivo, disciplina, posicao, numero, status, correta, comentario, slide_tipo, parent, peso, arquivo, nivel, usuario, incluidoem ) 
					VALUES ( :titulo, :enunciado, :enunciado_html, :content_html, :objetivo, :disciplina, :posicao, :numero, :status, :correta, :comentario, :slide_tipo, :parent, :peso, :arquivo, :nivel, :usuario, NOW() )";


			$this->conex->beginTransaction();

			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':titulo', $slide->__get('titulo') );
			$query->bindParam( ':enunciado', $slide->__get('enunciado') );
			$query->bindParam( ':enunciado_html', $slide->__get('enunciado_html') );
			$query->bindParam( ':content_html', $slide->__get('content_html') );
			$query->bindParam( ':objetivo', $slide->__get('objetivo') );
			$query->bindParam( ':disciplina', $slide->__get('disciplina') );
			$query->bindParam( ':posicao', $slide->__get('posicao') );
			$query->bindParam( ':numero', $slide->__get('numero') );
			$query->bindParam( ':status', $slide->__get('status') );
			$query->bindParam( ':correta', $slide->__get('correta') );
			$query->bindParam( ':comentario', $slide->__get('comentario') );
			$query->bindParam( ':slide_tipo', $slide->__get('slide_tipo') );
			$query->bindParam( ':parent', $slide->__get('parent') );
			$query->bindParam( ':peso', $slide->__get('peso') );
			$query->bindParam( ':arquivo', $slide->__get('arquivo') );
			$query->bindParam( ':nivel', $slide->__get('nivel') );
			$query->bindParam( ':usuario', $m_session->getValue( 'usuarioid' ) );

			$query->execute();

            $lastId = $this->conex->lastInsertId('id');         
            $this->conex->commit();

            return $lastId;
			
		}catch( Exception $e ){

			//print_r( $query->errorInfo()); exit('2');
			$this->conex->rollback();			
			return false;
		}
	}

	public function editar( IObject $slide ){

		/*echo "<pre>";
        print_r($slide);
        echo "</pre>";
        exit();*/

		try{

			$sql = "UPDATE slide SET
					titulo = :titulo, enunciado = :enunciado, enunciado_html = :enunciado_html, content_html = :content_html, posicao = :posicao, numero = :numero, status = :status, 
					correta = :correta, comentario = :comentario, slide_tipo = :slide_tipo, parent = :parent, peso = :peso, arquivo = :arquivo, nivel = :nivel
					WHERE id = :id";


			$this->conex->beginTransaction();
			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':titulo', $slide->__get('titulo') );
			$query->bindParam( ':enunciado', $slide->__get('enunciado') );
			$query->bindParam( ':enunciado_html', $slide->__get('enunciado_html') );
			$query->bindParam( ':content_html', $slide->__get('content_html') );
			$query->bindParam( ':posicao', $slide->__get('posicao') );
			$query->bindParam( ':numero', $slide->__get('numero') );
			$query->bindParam( ':status', $slide->__get('status') );
			$query->bindParam( ':correta', $slide->__get('correta') );
			$query->bindParam( ':comentario', $slide->__get('comentario') );
			$query->bindParam( ':slide_tipo', $slide->__get('slide_tipo') );
			$query->bindParam( ':parent', $slide->__get('parent') );
			$query->bindParam( ':peso', $slide->__get('peso') );
			$query->bindParam( ':arquivo', $slide->__get('arquivo') );
			$query->bindParam( ':nivel', $slide->__get('nivel') );
			$query->bindParam( ':id', $slide->__get('id') );

			$query->execute();

            return $this->conex->commit();

			
		}catch( Exception $e ){

			$this->conex->rollback();
			//$e->getTraceAsString();
			//print_r( $query->errorInfo()); exit();
			return false;
		}
	}



	public function removerCompetencias( $slide_id ){

		try{

			$sql = "DELETE FROM competencia_slide WHERE slide = :slide_id";

			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':slide_id', $slide_id );

			return $query->execute();

		}catch( Exception $e ){

			$this->conex->rollback();			
			return false;
		}
	}



	public function addCompetencia( $slide_id, $competencia_id ){

		if( !empty( $competencia_id ) ){
			try{

				$sql = "INSERT INTO competencia_slide (competencia, slide ) VALUES ( :competencia_id, :slide_id)";

				$query = $this->conex->prepare( $sql );

				$query->bindParam( ':slide_id', $slide_id );
				$query->bindParam( ':competencia_id', $competencia_id );

				return $query->execute();

			}catch( Exception $e ){

				$this->conex->rollback();			
				return false;
			}
		}
	}



	/*public function getSlidesByAvaliacao( $objeto_id, $versao, $bloco = false ){
	
		$blc = ( $bloco )? " AND bloco = '" . $bloco['bloco'] . "' AND nivel = '" . $bloco['nivel'] . "'" : "";

		try{

			$sql = "SELECT * FROM slide 
					WHERE objeto = :objeto_id AND obj_versao  = :versao AND status = 'A' " . $blc . "
					UNION 
					SELECT * FROM slide 
					WHERE objeto = :objeto_id AND obj_versao  < :versao AND status <> 'S' " . $blc . "
					ORDER BY posicao";

			$query = $this->conex->prepare( $sql );			
			$query->bindParam( ':objeto_id', $objeto_id );
			$query->bindParam( ':versao', $versao );

			
			$query->execute();			

			$slides = array();
			while( $slide = $query->fetch( PDO::FETCH_ASSOC ) ) {

				$slides[] = $slide;
			}

			return $slides;

		}catch( Exception $e ){
			//print_r( $query->errorInfo()); exit();
			$this->conex->rollback();
			echo $e->getTraceAsString();

		}
	}*/


	/*public function getTotalSlides( $objeto_id){

		try{

			$sql = "SELECT count(*) AS total FROM slide
					WHERE slide_tipo not in ('SL') AND status IN ( 'A', 'N' ) AND objeto = :objeto_id";

			$query = $this->conex->prepare( $sql );
			
			$query->bindParam( ':objeto_id', $objeto_id );
			$query->execute();

			$total = $query->fetch( PDO::FETCH_ASSOC );
			
			return $total['total'];

		}catch( Exception $e ){

			$this->conex->rollback();
			return false();
		}

	}*/




	/*public function editarDadosArquivo( $id, $caminho, $nome_arquivo ){

		try{
			
			$sql = "UPDATE slide SET
					caminho = :caminho, nome_arquivo = :nome_arquivo 
					WHERE id = :id";


			$this->conex->beginTransaction();
			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':caminho', $caminho );
			$query->bindParam( ':nome_arquivo', $nome_arquivo );
			$query->bindParam( ':id', $id );

			$query->execute();
        
            return $this->conex->commit();
			
		}catch( Exception $e ){

			$this->conex->rollback();
			//$e->getTraceAsString();
			//print_r( $query->errorInfo()); exit();
			return false;
		}
	}*/



	/*public function editarStatus( $id, $status ){

		try{
			
			$sql = "UPDATE slide SET status = :status WHERE id = :id";

			$this->conex->beginTransaction();
			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':status', $status );
			$query->bindParam( ':id', $id );

			$query->execute();
        
            if( $this->conex->commit() ){

            	$this->setEdicaoSlide( array( 'slide' => $id, 'acao' => $status ) );
            }

            return true;
			
		}catch( Exception $e ){

			$this->conex->rollback();
			//$e->getTraceAsString();
			//print_r( $query->errorInfo()); exit();
			return false;
		}
	}*/


	/*public function ativarSlidesByObjeto( $objeto_id ){

		try{
			
			$sql = "UPDATE slide SET status = 'A' WHERE objeto = :objeto_id AND status = 'I' AND ( slide_tipo = 'SL' or ( correta is not null AND numero is not null ) )";

			$this->conex->beginTransaction();
			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':objeto_id', $objeto_id );

			$query->execute();
        
            $this->conex->commit();

            return $query->rowCount();
			
		}catch( Exception $e ){

			$this->conex->rollback();
			//$e->getTraceAsString();
			//print_r( $query->errorInfo()); exit();
			return false;
		}
	}*/



	/*public function remover( Slide $slide ){

		try{

			$sql = "DELETE FROM slide WHERE id = :slide_id";

			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':slide_id', $slide->__get('id') );

			return $query->execute();

		}catch( Exception $e ){

			$this->conex->rollback();			
			return false;
		}
	}*/


/*
	public function removerCompetencias( $slide_id, $competencia_id ){

		try{

			$sql = "DELETE FROM slide WHERE id = :slide_id";

			$query = $this->conex->prepare( $sql );

			$query->bindParam( ':slide_id', $slide->__get('id') );

			return $query->execute();

		}catch( Exception $e ){

			$this->conex->rollback();			
			return false;
		}
	}*/



	/*public function setEdicaoSlide( $dados ){

		try{

			$m_session = new Session();

			$sql = "INSERT INTO edicao_slide ( slide, acao, data, usuario ) 
					VALUES ( :slide, :acao, NOW(), :usuario )";

			$this->conex->beginTransaction();

			$query = $this->conex->prepare( $sql );
			$query->bindParam( ':slide', $dados['slide'] );
			$query->bindParam( ':acao', $dados['acao'] );
			$query->bindParam( ':usuario', $m_session->getValue( 'usuarioid' ) );

			$query->execute();

            $this->conex->commit();

		}catch( Exception $e ){

			$this->conex->rollback();
			return false;
		}
	}
*/



}
?>