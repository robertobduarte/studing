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

		if( empty( $this->m_object->__get('id') ) ){

			$this->object_id = $this->m_object->novo();
				
			if( !$this->object_id ){

				$this->redirect( array( 'msg' => 'Erro ao gravar domínio.' ) );
			}

			//se sucesso, cria o diretório
			if( !mkdir( CAMINHO_ABSOLUTO . '/dominio/' . $this->m_object->__get('diretorio'), 0744 )){
				$this->redirect( array( 'msg' => 'Erro ao tentar criar o diretório em: ' . CAMINHO_ABSOLUTO . '/dominio/' . $this->m_object->__get('diretorio'), 'dst' => '../admin/dominio.php?dmn=' . $this->object_id ) );
			}

		}else{

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

				$this->redirect( array( 'msg' => 'Erro ao remover domínio.', 'dst' => '../view/dominio.php?dmn=' . $this->m_object->__get('id') ) );
				
			}

		}else{

			$this->redirect( array( 'msg' => 'Parâmetros incorretos. O objeto não foi excluído.' ) );
		}

		$this->redirect( array( 'msg' => 'Domínio excluído com sucesso.' ) );
	}



	/*
	protected function getModelo(){
		
		$m_modeloObjeto = new ModeloObjeto( array( 'id' => $this->dados['id'] ) );

		$modelo = array();
		$modelo['id'] = $m_modeloObjeto->__get('id');
		$modelo['nome'] = $m_modeloObjeto->__get('nome');
		$modelo['num_questoes'] = $m_modeloObjeto->__get('num_questoes');
		$modelo['pes_satisfacao'] = $m_modeloObjeto->__get('pes_satisfacao');
		$modelo['peso'] = $m_modeloObjeto->__get('peso');
		$modelo['media'] = $m_modeloObjeto->__get('media');
		$modelo['prazo'] = $m_modeloObjeto->__get('prazo');
		$modelo['banner'] = $m_modeloObjeto->__get('banner');
		$modelo['msg_inicial'] = $m_modeloObjeto->__get('msg_inicial');

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Ok', 'modelo' => $modelo ) );

	}
	*/

	protected function listModelos(){

		$this->m_object->__set( 'Dominio', $this->dados );

		$this->m_object->getModelosObjeto();

		$model = array();

		foreach ( $this->m_object->modelosObjeto as $modelo ) {
						
			$mdl = array();
			$mdl['id'] = $modelo->__get('id');
			$mdl['nome'] = $modelo->__get('nome');
			$mdl['num_questoes'] = $modelo->__get('num_questoes');
			$mdl['peso'] = $modelo->__get('peso');
			$mdl['media'] = $modelo->__get('media');
			$mdl['tentativas'] = $modelo->__get('tentativas');
			$mdl['pes_satisfacao'] = $modelo->__get('pes_satisfacao');

			$model[] = $mdl;			
		}

		$this->retornoAjax( array( 'cod' => 1, 'msg' => 'Ok', 'modelos' => $model ) );

	}



	protected function salvarModelo(){

		$this->m_object->__set( 'Dominio', $this->dados );	

		if( empty( $this->dados['arquivo'] ) ){

			$dadosUpload = array( 
							'diretorio' => $this->m_object->__get('diretorio'),
							'caminho_relativo' => 'dominio'
							);

			$UploadBanner = new UploadBanner( $dadosUpload );
			$retorno = $UploadBanner->getInformacoesArquivo();

			if( $retorno['retorno']['cod'] ){

				$this->m_object->__set( 'Dominio', array( 'imagem' => $retorno['nome_arquivo'] ) );

			}else{

				$this->redirect( array( 'msg' => 'Erro ao gravar arquivo: <br>' . $retorno['retorno']['msg'] ) );
			}
					
		}

		if( empty( $this->m_object->__get('id') ) ){

			$this->object_id = $this->m_object->novo();
				
			if( !$this->object_id ){

				$this->redirect( array( 'msg' => 'Erro ao gravar domínio.' ) );
			}

		}else{

			$this->object_id = $this->m_object->__get('id');

			$retorno = $this->m_object->editar();

			if( !$retorno ){

				$this->redirect( array( 'msg' => 'Erro ao gravar domínio.', 'dst' => '../view/dominio.php?dmn=' . $this->m_object->__get('id') ) );		
			}
		}

		$this->m_object->__set('Dominio', array( 'id' => $this->object_id ) );

		$this->redirect( array( 'msg' => 'Domínio gravado com sucesso.', 'dst' => '../view/dominio.php?dmn=' . $this->object_id ) );
	}

}
?>