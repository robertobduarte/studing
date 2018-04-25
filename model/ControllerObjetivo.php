<?php 
include_once __DIR__ . "/../config.php";

class ControllerObjetivo extends Icontroller {

	protected $name_session = 'form_Objetivo';

	function __construct(){

		parent::__construct();

	}

	protected function definePropriedades(){

		$this->m_object = new Objetivo();
		$this->destinoDefault = '../admin/listObjetivos.php?dmn=' . $this->m_session->getValue( 'dominio' );
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


			case 'getDisciplinasObjetivo':

				if( !$this->m_autenticacao->hasPermission( array( 'C', 'U' ) ) ){

					$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );					
				}

				$this->getDisciplinasObjetivo();
				break;
			
			default:

				$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação...' ) );
		}

	}


	protected function salvar(){

		$this->m_object->__set( 'Objetivo', $this->dados );

		if( empty( $this->m_object->__get('id') ) ){

			$this->object_id = $this->m_object->novo();
				
			if( !$this->object_id ){

				$this->redirect( array( 'msg' => 'Erro ao gravar objeto.' ) );
			}

		}else{

			$this->object_id = $this->m_object->__get('id');

			$retorno = $this->m_object->editar();

			if( !$retorno ){

				$this->redirect( array( 'msg' => 'Erro ao gravar objetivo.', 'dst' => '../admin/objetivo.php?obj=' . $this->m_object->__get('id') . '&dmn=' . $this->m_object->__get('dominio') ) );		
			}
		}

		$this->redirect( array( 'msg' => 'Objetivo gravado com sucesso.', 'dst' => '../admin/objetivo.php?obj=' . $this->object_id . '&dmn=' . $this->m_object->__get('dominio')) );
	}

	//busca todas as disciplinas que estão vinculadas ao objetivo
	protected function getDisciplinasObjetivo(){

		$this->m_object->__set( 'Objetivo', $this->dados );

		if( !empty( $this->m_object->__get('id') ) ){

			$this->m_object->getDisciplinas();

			$disciplinas = array();
			foreach ( $this->m_object->__get('disciplinas') as $disciplina ) {
					
				$disciplinas[] = array( 'id' => $disciplina->__get('id'), 'nome' => $disciplina->__get('nome') );
			}

			$this->retornoAjax( array( 'cod' => 1, 'msg' => 'ok.', 'disciplinas' => $disciplinas ) );

		}

		$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro. Dados inconsistentes.' ) );	

	}


	protected function remover(){}


	
}
?>