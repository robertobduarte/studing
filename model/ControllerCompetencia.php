<?php 
include_once __DIR__ . "/../config.php";

class ControllerCompetencia extends Icontroller {

	protected $name_session = 'form_competencia';
	
	function __construct(){

		parent::__construct();

	}


	protected function definePropriedades(){

		$this->m_object = new Competencia();
		$this->destinoDefault = '../admin/listDisciplinas.php?dmn=' . $this->m_session->getValue( 'dominio' );
		$this->mensagemDefault = 'Erro! Não foi possível completar a ação.';
	}


	protected function startAction(){

		switch ( $this->action ) {

			case 'salvar':

				if( !$this->m_autenticacao->hasPermission( array( 'C', 'U' ) ) ){

					$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );						
				}

				$this->salvar();
				break;
			

			/*case 'remover':

				if( !$this->m_autenticacao->hasPermission('D') ){

					$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );	

				}

				$this->remover();
				break;*/

			default:

				$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );	
		}

	}


	protected function salvar(){

		$this->m_object->__set( 'Competencia', $this->dados );

		if( empty( $this->m_object->__get('id') ) ){ //nova Competencia

			$this->object_id = $this->m_object->novo();
				
			if( !$this->object_id ){

				$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao gravar competencia.' ) );
			}

		}else{ //edição de Disciplina

			$this->object_id = $this->m_object->__get('id');

			$retorno = $this->m_object->editar();

			if( !$retorno ){

				$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao gravar competencia.' ) );		
			}
		}

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Competencia gravada com sucesso', 'id' => $this->object_id ) );
	}



/*	protected function remover(){
		
		$this->m_object->__set( 'Competencia', $this->dados );

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


}
?>