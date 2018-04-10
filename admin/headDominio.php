<?php 
include_once __DIR__ . "/../config.php";

$m_session = new Session();
$m_autenticacao = new Autenticacao();

//verifica se está logado
$m_autenticacao->checkAcess();

$m_dominio = new Dominio( array('id' => @$_REQUEST['dmn'] ) );
if( empty( $m_dominio->__get( 'id' ) ) ){
	$m_session->setValue( 'mensagem', 'Parâmetros incorretos.' );
	header("location: acessonegado.php");
	exit();
}

//verifica se tem acesso ao domínio
$m_autenticacao->checkAcessDominio( $m_dominio->__get( 'id' ) );

$m_session->setValue('dominio', $m_dominio->__get( 'id' ) );
?>

<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta charset="UTF-8">
		<title><?=APP?></title>

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSS -->
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/jquery-ui.css" rel="stylesheet">
		<link href="css/jquery-ui.theme.css" rel="stylesheet">
		<link href="css/jquery-ui.structure.css" rel="stylesheet">
		<link href="css/font-awesome.css" rel="stylesheet">
		<link href="css/admin.css?v=<?= filemtime('css/admin.css'); ?>" rel="stylesheet">

		<!-- JS -->		
		<script src="js/jquery-3-2-1.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/admin.js?v=<?= filemtime('js/admin.js'); ?>"></script>
	
	</head>

	<nav class="navbar navbar-default navbar-fixed-top">	
		<nav class="faixa"></nav>
		<div class="container">

			<div class="navbar-header">	
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			    </button>			
				<a class="navbar-brand" href="<?php echo '/'. APP ?>"><img height='30px' src="img/logo.png" alt="" class=""></a>
			</div>
						
		</div>
	</nav>

	<body>
 
	<div class="container-fluid" id="panel_main">
		
		<div class="corposistema">

		
