<?php 
include_once __DIR__ . "/../config.php";


class ControllerAlternativa extends Icontroller {


	function __construct(){

		parent::__construct();

	}


	protected function definePropriedades(){

		$this->m_object = new Alternativa();
		$this->destinoDefault = '../view/listObjetos.php?dmn=' . $this->m_session->getValue( 'dominio' );
		$this->mensagemDefault = 'Erro! Não foi possível completar a ação.';
	}


	protected function startAction(){

		switch ( $this->action ) {

			case 'salvar':

				if ( !array_intersect( array( 'C', 'U' ), $this->m_session->getValue( 'permissoes' ) )){

					echo 'sem permissao';
					$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );					
				}

				$this->salvar();
				break;


			case 'getAlterantiva':

				if ( !array_intersect( array( 'C', 'U', 'R' ), $this->m_session->getValue( 'permissoes' ) )){

					echo 'sem permissao';
					$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );					
				}

				$this->getAlterantiva();
				break;


			case 'baixarArquivo':

				if ( !array_intersect( array( 'R' ), $this->m_session->getValue( 'permissoes' ) )){

					$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );					
				}

				$this->baixar();
				break;


			case 'removerArquivo':

				if ( !array_intersect( array( 'D' ), $this->m_session->getValue( 'permissoes' ) )){

					$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );					
				}

				$this->removerArquivo();
				break;
			

			case 'remover':

				if ( !array_intersect( array( 'D' ), $this->m_session->getValue( 'permissoes' ) )){

					$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );
				}

				$this->remover();
				break;
			
			default:

				$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );
		}

	}


	protected function salvar(){

		$this->m_object->__set( 'Alternativa', $this->dados );


		if( empty( $this->dados['valor'] ) || ( empty( $this->dados['texto'] ) && empty( $this->dados['texto_html'] ) ) ){

			$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao gravar alternativa. Informações necessárias não foram encontradas.' ) );

		}

		$result = array();

		if( empty( $this->m_object->__get('id') ) ){

			$this->object_id = $this->m_object->novo();
				
			if( !$this->object_id ){

				$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao gravar alternativa.' ) );
			}

		}

		$this->object_id = $this->m_object->__get('id');
		
		/*
		Upload arquivo
		*/
		if( empty( $this->dados['file'] ) ){

			$m_slide = new Slide( array( 'id' => $this->m_object->__get('slide') ) );
			$m_dominio = new Dominio( array( 'id' => $m_slide->getDominio() ) );

			$dadosUpload = array( 
							'diretorio' => $m_dominio->__get('diretorio').'/objetos',
							'caminho_relativo' => 'dominio',
							'prefixo' => $this->object_id . '_A_'
							);

			$uploadObjeto = new UploadObjeto( $dadosUpload );
			$retorno = $uploadObjeto->getInformacoesArquivo();

			if( $retorno['retorno']['cod'] ){

				$this->m_object->__set( 'Alternativa', array( 'caminho' => $retorno['caminho_relativo_final'] . $retorno['nome_arquivo'] ) );
				$this->m_object->__set( 'Alternativa', array( 'nome_arquivo' => $retorno['nome_arquivo'] ) );

			}else{

				$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao gravar arquivo: <br>' . $retorno['retorno']['msg'] ) );
			}			
		}

		$this->object_id = $this->m_object->__get('id');

		$retorno = $this->m_object->editar();

		if( !$retorno ){

			$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao gravar alternativa.' ) );		
		}

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Alternativa gravada com sucesso', 'id' => $this->object_id,'caminho' => $this->m_object->__get('caminho'), 'nome_arquivo' => $this->m_object->__get('nome_arquivo') ) );

	}


	protected function getAlterantiva(){
		
		$m_alternativa = new Alternativa( array( 'id' => $this->dados['id'] ) );

		$alt = array();
		$alt['id'] = $m_alternativa->__get('id');
		$alt['slide'] = $m_alternativa->__get('slide');
		$alt['nome_arquivo'] = $m_alternativa->__get('nome_arquivo');
		$alt['caminho'] = $m_alternativa->__get('caminho');
		$alt['alternativa_tipo'] = $m_alternativa->__get('alternativa_tipo');
		$alt['tipo'] = $m_alternativa->__get('tipo');
		$alt['valor'] = $m_alternativa->__get('valor');
		$alt['texto'] = $m_alternativa->__get('texto');
		$alt['texto_html'] = $m_alternativa->__get('texto_html');

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Ok', 'alternativa' => $alt ) );

	}



	protected function remover(){
		
		$m_alternativa = new Alternativa( array( 'id' => $this->dados['id'] ) );
	
		$retorno = $m_alternativa->remover();

		if( $retorno ){

			$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Alternativa excluída.' ) );

		}else{

			$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao excluir alternativa' ) );
		}
	}


	private function baixar(){
		
		$m_alternativa = new Alternativa( array( 'id' => $this->dados['id'] ) );

		if( !empty( $m_alternativa->__get('caminho') ) ){

			$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Ok', 'caminho' => $m_alternativa->__get('caminho'), 'dominio' => $this->m_session->getValue( 'dominio' ) ) );

		}else{

			$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao localizar o diretório do arquivo' ) );
		}
	}


	private function removerArquivo(){
		
		$m_alternativa = new Alternativa( array( 'id' => $this->dados['id'] ) );

		$caminho = $m_alternativa->__get('caminho');

		$m_alternativa->__set( 'Alternativa', array( 'caminho' => '', 'nome_arquivo' => '' ) );
		
		$retorno = $m_alternativa->editar();

		if( $retorno ){

			unlink( APP.$caminho );

			$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Arquivo excluído.' ) );

		}else{

			$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao excluir arquivo' ) );
		}
	}


	
}
?>