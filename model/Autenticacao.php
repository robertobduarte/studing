<?php

class Autenticacao{

	private $m_usuario;
	private $m_session;

	public function __construct(){

		$this->m_session = new Session();
	}

	/*
	Executa uma consulta no banco de dados e retorna os dados do usuário passado por parâmetro.
	Guarda as informações na session, casso retorne um usuário existente.
	*/
	public function setUser( $userName ){

		$this->m_usuario = new Usuario();
		$this->m_usuario->getUsuarioByUser( $userName );
		
		if( !empty( $this->m_usuario ) ){
			
			$this->m_session->setValue( 'usuarioid', $this->m_usuario->__get('id') );
			$this->m_session->setValue( 'usuario', $this->m_usuario->__get('usuario') );
			$this->m_session->setValue( 'perfil', $this->m_usuario->__get('perfil') );
			$this->m_session->setValue( 'nome', $this->m_usuario->__get('nome') );
			$this->m_session->setValue( 'perfil_nome', $this->m_usuario->__get('perfil_nome') );
		}		
	}


	/*
	Verifica se está logado, ou seja, com as variaveis de sessão caregadas.
	Caso esteja, verifica se possui acesso a pagina. Caos tenha, exibe cerrega a página.
	Caso contrário guarda a página na sessão e redireciona para a página de login do publicA
	*/
	public function checkAcess(){

		$usuario = $this->m_session->getValue( 'usuario' );
		$perfil = $this->m_session->getValue( 'perfil' );

		if( ( empty( $usuario ) || empty( $perfil ) ) ) {

			//echo 'erro login';
			$this->redirectLogin();
		}

	}	


	/*
	Método chamado em casos de falha na autenticação ou para sair do sistema
	Apaga as variáveis de sessão, caso existam, persistindo apenas a página atual que chamou o método.
	Ao fazer o login, o usuário é redirecionado para a mesma paǵina que chamou este método
	*/
	public function redirectLogin(){

		echo str_repeat( '<br>', 5 );
		exit('implementar method redirectLogin');
		/*
		$this->m_session->setValue( 'url', $_SERVER['REQUEST_URI'] );

		header( "location: http://publica.grupoa.com.br/?inpRedirectURL=http://publica.grupoa.com.br/" . APP ); 
		exit();
		*/
	}	


	public function logout(){

		$this->m_session->unsetSession();
		//header("location:http://publica.grupoa.com.br?logout=S");
		exit();
	}



	public function redirectAcesso( $param ){

		if( !empty( $param ) ){

			if( !empty( $param['mensagem'] ) ){

				$this->m_session->setValue( 'retorno', $param['mensagem'] );

			}

			$destino = ( !empty( $param['destino'] ) )? $param['destino'] : $_SERVER['DOCUMENT_ROOT'] . '/' . APP;

			header( 'location: ' . $destino );
			exit();

		}else{

			$this->redirectLogin();
		}

	}



	public function unscramble( $str ) {

		$SCR2 = 'ABCDEFGH-IJKLMNOPQRST_UVWXYZ.abcdefghijklmnopqrstuvwxyz';
		$SCR1 = 'DAMQCLWEXKJHGUI_SOFZ-.BNVRTYPdamqclwexkjhguisofzbnvrtyp';

		$ct = strpos( $SCR2,substr( $str,0,1 ) );
		$str2 = '';	

		for ( $i=1; $i < strlen( $str ); $i++ ) {

			if ( strpos( $SCR2, substr( $str, $i, 1) ) !== FALSE ) {
				
				$ct = 107+strpos( $SCR2, substr( $str, $i, 1 ) )-$ct;
				$ct = $ct % 55;
				$str2 .= substr( $SCR1, $ct, 1 );
				$ct = strpos( $SCR2, substr( $str, $i, 1 ) );

			} else{

				$str2 .= substr( $str, $i, 1 );
			}		
		}	

		return $str2;
	}


	public function scramble3( $str ) {
		
		$SCR1 = 'DAM1QC2LW3EX4KJ7HG86UI95_SOFZ-BN0VRTY.P';
		$str2 = '';

		for ($i=0;$i<strlen($str);$i++) {

			$str2 = $str2 . chr(65+(ord(substr($str,$i,1)) ^ ord(substr($SCR1,$i,1)))%20);
		}

		return $str2;
	}
	
	public function scramble( $str ) {

		$SCR2 = 'ABCDEFGH-IJKLMNOPQRST_UVWXYZ.abcdefghijklmnopqrstuvwxyz';
	   	$SCR1 = 'DAMQCLWEXKJHGUI_SOFZ-.BNVRTYPdamqclwexkjhguisofzbnvrtyp';

	   	$ct = rand(0,54);

	   	$str2 = substr($SCR2,$ct,1);

	   	for ($i=0;$i<=strlen($str);$i++) {

		  	if (strpos($SCR1, substr($str,$i,1)) !== FALSE) {
				$ct = strpos($SCR1, substr($str,$i,1))+$ct+3;
				$ct = $ct % 55;
			 	$str2 .= substr($SCR2,$ct,1);

		  	} else {

		  		$str2 .= substr($str,$i,1);
		  	}

	   	}

	   	return $str2;
	}



}
?>