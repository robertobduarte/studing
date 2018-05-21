<?php 
include_once __DIR__ . "/../config.php";

class ControllerSlide extends Icontroller {

	protected $name_session = 'form_slide';
	
	function __construct(){

		parent::__construct();

	}


	protected function definePropriedades(){

		$this->m_object = new Slide();
		if( !empty( $this->dados['id'] ) ){

			$this->destinoDefault = '../admin/slide.php?dmn=' . $this->m_session->getValue( 'dominio' ) . '&sld=' . $this->dados['id'];

		}else{

			$this->destinoDefault = '../admin/bancodequestoes.php?dmn=' . $this->m_session->getValue( 'dominio' ) . '&obj=' . $this->dados['objetivo']. '&disc=' . $this->dados['disciplina'];
		}
		
		$this->mensagemDefault = 'Erro! Não foi possível completar a ação.';
	}


	protected function startAction(){

		//verifica se tem permissão para o domínio enviado por post
		$this->m_autenticacao->checkAcessDominio( $this->dados['dominio'] );

		switch ( $this->action ) {

			case 'salvar':

				if( !$this->m_autenticacao->hasPermission( array( 'C', 'U' ) ) ){

					$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );					
				}

				$this->salvar();
				break;


			case 'remover':

				if( !$this->m_autenticacao->hasPermission('D') ){

					$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );

				}

				$this->remover();
				break;

			case 'listAlterantivas':

				if( !$this->m_autenticacao->hasPermission('C', 'U') ){

					$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );

				}

				$this->listAlterantivas();
				break;
			
			default:

				$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação...' ) );
		}

	}



	protected function salvar(){

		/*echo '<pre>';
		print_r($this->dados);
		exit();*/

		$this->m_object->__set( 'Slide', $this->dados );

		$this->m_object->getObjeto();

		$this->validaDados();

		if( empty( $this->m_object->__get('id') ) ){

			$this->object_id = $this->m_object->novo();
				
			if( !$this->object_id ){

				$this->redirect( array( 'msg' => 'Erro ao gravar slide.' ) );
			}

		}else{

			$retorno = $this->m_object->editar();

			if( !$retorno ){

				$this->redirect( array( 'msg' => 'Erro ao gravar slide.' ) );			
			}

		}

		$this->object_id = $this->m_object->__get('id');

		$this->m_object->editarCompetencias( $this->dados['competencias'] );
		

		if( !empty( $_FILES['file']['name'] ) ){

			//implementar método
			$this->uploadArquivo();	
		}

		$this->redirect( array( 'msg' => 'Slide gravado com sucesso.', 'dst' => '../admin/slide.php?sld=' . $this->object_id . '&dmn=' . $this->m_session->getValue( 'dominio' ) ) );
	}



	/*protected function remover(){
		
		$this->m_object->__set( 'Dominio', $this->dados );

		if( !empty( $this->m_object->__get('id') ) ){

			$retorno = $this->m_object->remover();
				
			if( !$retorno ){

				$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao excluir disciplina' ) );
			}

		}else{

			$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Parâmetros incorretos. A disciplina não foi excluída.' ) );
		}

		$this->redirect( array( 'msg' => 'Disciplina excluída com sucesso.' ) );
	}*/



	protected function uploadArquivo(){

		/*$m_dominio = new Dominio( array( 'id' => $this->m_session->getValue( 'dominio' ) ) );

		$dadosUpload = array( 
						'diretorio' => $m_dominio->__get('diretorio').'/objetos',
						'caminho_relativo' => 'dominio',
						'prefixo' => $this->object_id . '_S_'
						);

		$uploadObjeto = new Upload( $dadosUpload );
		$retorno = $uploadObjeto->getInformacoesArquivo();

		if( $retorno['retorno']['cod'] ){

			$caminho = $retorno['caminho_relativo_final'] . $retorno['nome_arquivo'];
			$nome_arquivo = $retorno['nome_arquivo'];
			
			//$retorno = $this->m_object->editarDadosArquivo( $this->m_object->__get('id'), $caminho, $nome_arquivo );

			if( !$retorno ){

				$this->redirect( array( 'msg' => 'Erro ao gravar slide.' ) );			
			}

		}else{

			$this->redirect( array( 'msg' => 'Erro ao gravar arquivo: <br>' . $retorno['retorno']['msg'] ) );
		}*/
		
	}


	protected function listAlterantivas(){

		$this->m_object->__set( 'Slide', $this->dados );

		$this->m_object->getAlternativas();

		$altern = array();
		//$i = 0;
		foreach ( $this->m_object->alternativas as $alternativa ) {
						
			$alt = array();
			$alt['id'] = $alternativa->__get('id');
			$alt['slide'] = $alternativa->__get('slide');
			$alt['nome_arquivo'] = $alternativa->__get('arquivo');
			$alt['caminho'] = $alternativa->__get('caminho');
			$alt['alternativa_tipo'] = $alternativa->__get('tipo');
			$alt['tipo'] = $alternativa->__get('tipo');
			$alt['valor'] = $alternativa->__get('valor');
			$alt['texto'] = $alternativa->__get('texto');
			$alt['texto_html'] = $alternativa->__get('texto_html');

			$altern[] = $alt;			
		}

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Ok', 'alternativas' => $altern ) );

	}


	protected function validaDados(){
		

		$slideTipo = $this->dados['slide_tipo'];

		if( empty( $slideTipo ) ) $this->redirect( array( 'msg' => 'Erro ao salvar slide. Informações inconsistentes.' ) );

		switch ( trim( $slideTipo ) ) {

			case 'SL':
				
				if( empty( $this->dados['content_html'] ) ) $this->redirect( array( 'msg' => 'Erro. Campo obrigatório: Conteúdo HTML' ) );
				break;

			case 'QH':
				
				if( empty( $this->dados['enunciado_html'] ) ) $this->redirect( array( 'msg' => 'Erro. Campo obrigatório: Enunciado Html' ) );

				if( empty( $this->dados['numero'] ) ) $this->redirect( array( 'msg' => 'Erro. Campo obrigatório: Número' ) );

				if( ( $this->dados['status'] == 'A' ) && ( empty( $this->dados['correta'] ) ) ) $this->redirect( array( 'msg' => 'Erro. Campo obrigatório: Correta' ) );
				break;

			case 'QT':
				
				if( empty( $this->dados['enunciado'] ) ) $this->redirect( array( 'msg' => 'Erro. Campo obrigatório: Enunciado' ) );

				if( empty( $this->dados['numero'] ) ) $this->redirect( array( 'msg' => 'Erro. Campo obrigatório: Número' ) );

				if( ( $this->dados['status'] == 'A' ) && ( empty( $this->dados['correta'] ) ) ) $this->redirect( array( 'msg' => 'Erro. Campo obrigatório: Correta' ) );

				break;
		}

	}


}
?>