<?php 
include_once __DIR__ . "/../config.php";

class ControllerDominio extends Icontroller {

	protected $name_session = 'form_dominio';
	
	function __construct(){

		parent::__construct();

	}


	protected function definePropriedades(){

		$this->m_object = new Dominio();
		$this->destinoDefault = '../admin/listDominio.php';
		$this->mensagemDefault = 'Erro! Não foi possível completar a ação.';
	}


	protected function startAction(){

		//verifica se usuário possui acesso a área de admn, fora de domínio
		$this->m_autenticacao->checkAcessAdm();

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

			default:

				$this->redirect( array( 'msg' => 'Usuário sem permissão para esta ação.' ) );
		}

	}


	protected function salvar(){

		$this->m_object->__set( 'Dominio', $this->dados );

		if( empty( $this->m_object->__get('id') ) ){ //novo domínio - cria o diretório

			$this->object_id = $this->m_object->novo();
				
			if( !$this->object_id ){

				$this->redirect( array( 'msg' => 'Erro ao gravar domínio.' ) );
			}

			//se sucesso, cria o diretório
			if( !mkdir( CAMINHO_ABSOLUTO . 'dominio/' . $this->m_object->__get('diretorio'), 0744 )){
				
				$this->redirect( array( 'msg' => 'Erro ao tentar criar o diretório em: ' . CAMINHO_ABSOLUTO . 'dominio/' . $this->m_object->__get('diretorio'), 'dst' => '../admin/dominio.php?dmn=' . $this->object_id ) );
			}

		}else{ //edição de um domínio

			$this->object_id = $this->m_object->__get('id');

			$retorno = $this->m_object->editar();

			if( !$retorno ){

				$this->redirect( array( 'msg' => 'Erro ao gravar domínio.', 'dst' => '../admin/dominio.php?dmn=' . $this->object_id ) );		
			}
		}

		if( !empty( $_FILES['file']['name'] ) ){ //arquivo enviado

			$dadosUpload = array( 
							'diretorio' => $this->m_object->__get('diretorio'),
							'caminho_relativo' => 'dominio'
							);

			$UploadBanner = new UploadLogoDominio( $dadosUpload );
			$retorno = $UploadBanner->getInformacoesArquivo();

			if( !$retorno['retorno']['cod'] ){

				$this->redirect( array( 'msg' => 'Erro ao gravar arquivo: <br>' . $retorno['retorno']['msg'] , 'dst' => '../admin/dominio.php?dmn=' . $this->object_id ) );	
			}
					
		}

		$this->m_object->__set('Dominio', array( 'id' => $this->object_id ) );

		$this->redirect( array( 'msg' => 'Domínio gravado com sucesso.', 'dst' => '../admin/dominio.php?dmn=' . $this->object_id ) );
	}



	protected function remover(){
		
		$this->m_object->__set( 'Dominio', $this->dados );

		if( !empty( $this->m_object->__get('id') ) ){

			$retorno = $this->m_object->remover();
				
			if( !$retorno ){

				$this->redirect( array( 'msg' => 'Erro ao remover domínio.', 'dst' => '../admin/dominio.php?dmn=' . $this->m_object->__get('id') ) );
				
			}

		}else{

			$this->redirect( array( 'msg' => 'Parâmetros incorretos. O objeto não foi excluído.' ) );
		}

		$this->redirect( array( 'msg' => 'Domínio excluído com sucesso.' ) );
	}




}
?>