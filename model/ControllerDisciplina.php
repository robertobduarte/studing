<?php 
include_once __DIR__ . "/../config.php";

class ControllerDisciplina extends Icontroller {

	protected $name_session = 'form_disciplina';
	
	function __construct(){

		parent::__construct();

	}


	protected function definePropriedades(){

		$this->m_object = new Disciplina();
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
			

			case 'remover':

				if( !$this->m_autenticacao->hasPermission('D') ){

					$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );	

				}

				$this->remover();
				break;


			case 'getDisciplina':

				if( !$this->m_autenticacao->hasPermission( array( 'R' ) ) ){

					$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );						
				}

				$this->getDisciplina();
				break;


			case 'listDisciplinas':

				if( !$this->m_autenticacao->hasPermission( array( 'R' ) ) ){

					$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );						
				}

				$this->listDisciplinas();
				break;


			case 'addDisciplinaObjetivo':

				if( !$this->m_autenticacao->hasPermission( array( 'C', 'U' ) ) ){

					$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );						
				}

				$this->addDisciplinaObjetivo();
				break;


			case 'rmDisciplinaObjetivo':

				if( !$this->m_autenticacao->hasPermission( array( 'C', 'U' ) ) ){

					$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );						
				}

				$this->rmDisciplinaObjetivo();
				break;


			default:

				$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Sem permissão.' ) );	
		}

	}


	protected function salvar(){

		$this->m_object->__set( 'Disciplina', $this->dados );

		if( empty( $this->m_object->__get('id') ) ){ //nova Disciplina

			$this->object_id = $this->m_object->novo();
				
			if( !$this->object_id ){

				$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao gravar disciplina.' ) );
			}

		}else{ //edição de Disciplina

			$this->object_id = $this->m_object->__get('id');

			$retorno = $this->m_object->editar();

			if( !$retorno ){

				$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao gravar disciplina.' ) );		
			}
		}

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Disciplina gravada com sucesso', 'id' => $this->object_id ) );
	}



	protected function remover(){
		
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
	}



	protected function addDisciplinaObjetivo(){
		
		$this->m_object->__set( 'Disciplina', $this->dados );

		if( !$this->m_object->addDisciplinaObjetivo( $this->dados['objetivo'] ) ){

			$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao vincular disciplina ao objetivo.' ) );
		}

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Disciplina vinculada com sucesso' ) );
	}



	protected function rmDisciplinaObjetivo(){
		
		$this->m_object->__set( 'Disciplina', $this->dados );

		if( !$this->m_object->rmDisciplinaObjetivo( $this->dados['objetivo'] ) ){

			$this->retornoAjax( array( 'cod' => 0, 'msg' => 'Erro ao desvincular disciplina do objetivo.' ) );
		}

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Disciplina desvinculada com sucesso' ) );
	}



	protected function getDisciplina(){
		
		$m_disciplina = new Disciplina( array( 'id' => $this->dados['id'] ) );

		$disciplina = array();
		$disciplina['id'] = $m_disciplina->__get('id');
		$disciplina['nome'] = $m_disciplina->__get('nome');
		$disciplina['descricao'] = $m_disciplina->__get('descricao');
		$disciplina['competencias'] = $m_disciplina->getArrayCompetencias();

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Ok', 'disciplina' => $disciplina ) );

	}


	protected function listDisciplinas(){
		
		$m_disciplina = new Disciplina();
		$m_disciplina->listar( $this->dados['dominio'] );

		$disciplinas = array();
		foreach ( $m_disciplina::$instances as $m_disc ) {

			$disciplinas[] = array( 'id' => $m_disc->__get('id'), 'nome' => $m_disc->__get('nome'), 'descricao' => $m_disc->__get('descricao') );

		}
		
		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Ok', 'disciplinas' => $disciplinas ) );

	}



}
?>