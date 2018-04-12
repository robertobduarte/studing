<?php

class UploadLogoDominio extends Iupload {
	
	function __construct( $dados ){

		$this->mime = array('image/png'); //aceitas apenas arquivo png
		$this->caminho_relativo = $dados['caminho_relativo'];
		$dados['nome_arquivo'] = 'logo.png';

		parent::__construct( $dados );
	}

}
?>