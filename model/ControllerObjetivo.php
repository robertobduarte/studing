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

		switch ( $this->action ) {

			case 'salvar':

				/*if ( !array_intersect( array( 'C', 'U' ), $this->m_session->getValue( 'permissoes' ) )){

					$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );					
				}*/

				$this->salvar();
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

				$this->redirect( array( 'msg' => 'Erro ao gravar objetivo.', 'dst' => '../admin/objetivo.php?obj=' . $this->m_object->__get('id') ) );		
			}
		}

		$this->redirect( array( 'msg' => 'Objetivo gravado com sucesso.', 'dst' => '../admin/objetivo.php?obj=' . $this->object_id ) );
	}


	protected function remover(){}


	
}
?>