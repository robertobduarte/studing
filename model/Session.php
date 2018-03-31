<?php

class Session{

	private $session_id;

	public function __construct() {

		$this->initSession();
		
	}


	private function initSession(){

		if ( !isset( $_SESSION ) ){
			session_start();			
		}

		$this->session_id = session_id();

	}


	public function getValue( $chave, $unset = FALSE ){

		if( is_array( $chave ) ){

			$valor = isset( $_SESSION[$this->session_id][ $chave[0] ][ $chave[1] ] )? $_SESSION[$this->session_id][ $chave[0] ][ $chave[1] ] : '';

		}else{

			$valor = isset( $_SESSION[$this->session_id][$chave] )? $_SESSION[$this->session_id][$chave] : '';

		}

		if( $unset ){

			 	$this->unsetValue( $chave );
			}			
			
		return $valor;
	
	}


	public function setValue( $chave, $value ){

		$_SESSION[$this->session_id][$chave] = $value;
	
	}


	public function unsetValue( $chave ){

		if( is_array( $chave ) ){

			foreach ($chave as $key => $value) {
				
				unset( $_SESSION[$this->session_id][$key]);
			}
		}else{

			unset( $_SESSION[$this->session_id][$chave]);
		}
		
	
	}


	/*
	Limpa todas as variáveis de sessão, exceto o id da sessao (session_id)
	*/
	public function unsetSession(){

		foreach ( $_SESSION[$this->session_id] as $key => $value) {
			
			unset( $_SESSION[$this->session_id][$key] );
		}

		unset( $_SESSION[$this->session_id] );

	}


	/*
	Exibe mensagem de retorno após alguma ação.
	*/
	public function showRetorno(){

		$retorno = $this->getValue( 'retorno', true );


		if( !empty( $retorno ) ){

			echo '<div class="alert alert-warning">';

				echo $retorno;

			echo '</div>';
		}

	}

	

}
?>