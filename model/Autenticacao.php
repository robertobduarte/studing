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
			$this->m_session->setValue( 'permissao', $this->m_usuario->__get('permissao') );
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
	Verifica se esusuário logado possui acesso a área administrativa (fora de dominio)
	*/
	public function checkAcessAdm(){

		if( $this->m_session->getValue( 'perfil' ) != 'ADM' ) {

			$this->m_session->setValue( 'mensagem', 'Acesso não permitido para esta página.' );
			header("location: acessonegado.php");
			exit();
		}

	}	

	/*
	Verifica se possui a permissão adequada, passada por argumento do método. Retorna true ou false
	*/
	public function hasPermission( $permissoes = '', $condicao = null ){

		if( empty( $permissoes ) ) return true;

		if( is_array( $permissoes ) ){

			$has = true;
			foreach ( $permissoes as $permissao ) {
				
				if( strpos( $this->m_session->getValue( 'permissao' ), $permissao ) !== false ){

					if( $condicao == '&&' ){
						continue;
					}else{
						return true;
					}
				}else{
					$has = false;
				}
			}
			return $has;
			
		}else{

			return ( strpos( $this->m_session->getValue( 'permissao' ), $permissoes ) !== false )? true : false;

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

			$destino = ( !empty( $param['destino'] ) )? $param['destino'] : CAMINHO_ABSOLUTO;

			header( 'location: ' . $destino );
			exit();

		}else{

			$this->redirectLogin();
		}

	}


	/*
	retorna uma listagem de menus que o usuário tem permissão
	*/
	public function listMenu( $dominio = false ){
		
		$daoMenu = new DaoMenu();
		$menus = $daoMenu->listMenu( $this->m_session->getValue('perfil'), $dominio );				
		
		return $menus;

	}


		/*
	Verifia se o usuário logado em permissão para acessar um dominio
	*/
	public function checkAcessDominio( $dominio_id ){

		$m_usuario = new Usuario();

		$perfil = $this->m_session->getValue('perfil');
		$perfil_nome = $this->m_session->getValue('perfil_nome');
		$usuarioid = $this->m_session->getValue('usuarioid');

		if( in_array( $perfil, array( 'ADM' ) ) ){

			$m_usuario->getPermissoes( $perfil );
			$this->m_session->setValue( 'perfil_dominio', $perfil );
			$this->m_session->setValue( 'perfil_nome_dominio', $perfil_nome );
			$this->m_session->setValue( 'permissoes', $m_usuario->__get('permissoes') );
			$this->m_session->setValue( 'dominio', $dominio_id );
			return true;

		}else{
			/*
			retorna o perfil do usuário para o domínio. Se retornar vazio o usuário não tem acesso 
			*/
			$daoUsuario = new DaoUsuario();

			$perfilDominio = $daoUsuario->checkAcessDominio( $dominio_id,  $usuarioid );

			if( empty( $perfilDominio ) ){

				//return false;
				$this->m_session->setValue( 'mensagem', 'Acesso não permitido para este domíno.' );
				header("location: acessonegado.php");
				exit();

			}

			$m_usuario->getPermissoes( $perfilDominio['perfil'] );
			$this->m_session->setValue( 'perfil_dominio', $perfilDominio['perfil'] );
			$this->m_session->setValue( 'perfil_nome_dominio', $perfilDominio['nome'] );
			$this->m_session->setValue( 'permissao_dominio', $m_usuario->__get('permissao') );
			$this->m_session->setValue( 'dominio', $dominio_id );
			return true;

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