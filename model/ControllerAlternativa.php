<?php 
include_once __DIR__ . "/../config.php";


class ControllerAlternativa extends Icontroller {


	function __construct(){

		parent::__construct();

	}


	protected function definePropriedades(){

		$this->m_object = new Alternativa();
		
		if( !empty( $this->dados['slide'] ) ){

			$this->destinoDefault = '../admin/slide.php?dmn=' . $this->m_session->getValue( 'dominio' ) . '&sld=' . $this->dados['slide'];

		}else{

			$this->destinoDefault = '../admin/';
		}

		$this->mensagemDefault = 'Erro! Não foi possível completar a ação.';
	}


	protected function startAction(){

		switch ( $this->action ) {

			case 'salvar':

				if( !$this->m_autenticacao->hasPermission( array( 'C', 'U' ) ) ){

					echo 'sem permissao';
					$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );					
				}

				$this->salvar();
				break;


			case 'getAlterantiva':

				if( !$this->m_autenticacao->hasPermission( array( 'C', 'U', 'R' ) ) ){

					echo 'sem permissao';
					$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );					
				}

				$this->getAlterantiva();
				break;


			case 'remover':

				if( !$this->m_autenticacao->hasPermission( array( 'D' ) ) ){

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

		$retorno = $this->m_object->editar();

		if( !$retorno ){

			$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao gravar alternativa.' ) );		
		}

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Alternativa gravada com sucesso', 'nome_arquivo' => $this->m_object->__get('arquivo') ) );

	}


	protected function getAlterantiva(){
		
		$m_alternativa = new Alternativa( array( 'id' => $this->dados['id'] ) );

		$alt = array();
		$alt['id'] = $m_alternativa->__get('id');
		$alt['slide'] = $m_alternativa->__get('slide');
		$alt['arquivo'] = $m_alternativa->__get('arquivo');
		$alt['tipo'] = $m_alternativa->__get('tipo');
		$alt['tipo_nome'] = $m_alternativa->__get('tipo_nome');
		$alt['valor'] = $m_alternativa->__get('valor');
		$alt['texto'] = $m_alternativa->__get('texto');
		$alt['texto_html'] = $m_alternativa->__get('texto_html');

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Ok', 'alternativa' => $alt ) );

	}



	/*protected function remover(){
		
		$m_alternativa = new Alternativa( array( 'id' => $this->dados['id'] ) );
	
		$retorno = $m_alternativa->remover();

		if( $retorno ){

			$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Alternativa excluída.' ) );

		}else{

			$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao excluir alternativa' ) );
		}
	}*/

	
}
?>