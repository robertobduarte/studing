<?php
include_once __DIR__ . "/headDominio.php"; //página instancia um dominio - $m_dominio

/*$dominio_id = (isset($_REQUEST['dmn']))? $_REQUEST['dmn'] : '';
$m_dominio = new Dominio( array('id' => $dominio_id ) );
$m_session->setValue('dominio', $dominio_id );

if( empty( $m_dominio->__get( 'id' ) ) ){
	$m_session->setValue( 'mensagem', 'Parâmetros incorretos.' );
	header("location: acessonegado.php");
	exit();
}

$m_autenticacao->checkAcessDominio( $m_dominio->__get( 'id' ) );*/

$m_session->setValue( 'menu', '1' );

$m_objetivo = new Objetivo();
$m_objetivo->__set( 'Objetivo', array( 'dominio' => $m_dominio->__get('id') ) );
$m_objetivo->listar($m_dominio->__get('id'), true);

?>


<div class="row panel_main_page">

	<div class="hidden-xs col-md-2 col-sm-3 panel_menu" id="menu_lateral">
		<?php include __DIR__ . "/menuLateral.php"; ?>
	</div>


	<div class="col-md-10 col-sm-9 col-xs-12 panel_conteudo">

		<?php $m_session->showRetorno(); ?>

		<div class="col-md-12 tituloPage">
			<h3>OBJETIVOS DE APRENDIZAGEM</h3>
		</div>

		<div class="col-md-12 corpoPage">
			
			<div class="row">

			<?php $m_objetivo->buttonNovoObjetivo(); ?>

			</div>

			<div class="row">
				<div class="divesp"></div>
			</div>
			
			<div class="row">
			
				<?php $m_objetivo->listObjetivos(); ?>
				<?php //echo '<pre>'; print_r( $m_objetivo->listObjetivos()); echo '</pre>'; ?>

			</div>
		</div>

	</div>

</div>

<?php include_once __DIR__ . "/footer.php"; ?>