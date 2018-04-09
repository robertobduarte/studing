<?php
include_once __DIR__ . "/head.php";

$objetivo_id = ( isset( $_REQUEST['obj'] ) )? $_REQUEST['obj'] : '';
$dominio_id = ( isset( $_REQUEST['dmn'] ) )? $_REQUEST['dmn'] : '';
$parent_id = ( isset( $_REQUEST['prt'] ) )? $_REQUEST['prt'] : '';


if( empty( $objetivo_id ) && empty( $dominio_id ) ){

	header("location: acessonegado.php");
	exit();	
}


if( !empty( $objetivo_id ) ){

	$m_objetivo = new Objetivo( array( 'id' => $objetivo_id ) );

	$m_objetivo->getParents();
	$m_objetivo->setTree();
	$m_objetivo->getChildren();

	$m_dominio = new Dominio( array('id' => $m_objetivo->__get( 'dominio' ) ) );

}else{

	$m_objetivo = new Objetivo();
	$m_objetivo->__set( 'Objetivo', array( 'dominio' => $dominio_id ) );
	$m_dominio = new Dominio( array('id' => $dominio_id ) );

	if( !empty( $parent_id ) ){

		$m_objetivo->__set( 'Objetivo', array( 'parent' => $parent_id ) );
	}

	$m_objetivoParent = new Objetivo( array( 'id' => $parent_id ) );
	$m_objetivoParent->getParents();
	$m_objetivoParent->setTree( true );

}

$m_objetivo->getTiposObjetivos();

if( !$m_autenticacao->checkAcessDominio( $m_dominio->__get( 'id' ) ) ){

	$m_session->setValue( 'mensagem', 'Acesso não permitido para este domíno.' );
	header("location: acessonegado.php");
	exit();
}


?>

<script src="js/objetivo.js?v=<?= filemtime('js/objetivo.js'); ?>"></script>

<div class="row panel_main_page">

	<div class="hidden-xs col-md-2 col-sm-3 panel_menu" id="menu_lateral">
		<?php include __DIR__ . "/menuLateral.php"; ?>
	</div>

	<div class="col-md-10 col-sm-9 col-xs-12 panel_conteudo">

		<?php 

			$retorno = $m_session->getValue( 'retorno', true );

			if( !empty( $retorno ) ){

				echo '<div class="alert alert-warning">' . $retorno . '</div>';
			}
		
		?>

		<span class="linkTree"><?php echo ( isset( $m_objetivoParent ) ) ? $m_objetivoParent->tree :  $m_objetivo->tree ?></span>
		<div class="col-md-12 tituloPage">
			<h4><?php echo ( !empty( $m_objetivo->__get('nome') ) ) ? $m_objetivo->__get('tipo') . ': ' . $m_objetivo->__get('nome') : 'Novo Objetivo'; ?></h4>
		</div>

		<div class="col-md-12 corpoPage">

			<section class="col-md-12" id="objetivo">

				
				<?php $m_objetivo->showFormulario( $m_session ); ?>
				
			</section> <!-- #objetivo -->

			<div class="col-md-12 divesp"></div>			
						
			<div class="col-md-12 divesp"></div>

			<section class="col-md-12" id="objetivosfilhos">

				<?php if( $m_objetivo->leaf == 'N' ) { ?>

					<div class="col-md-2 col-sm-4 col-xs-12">

	 					<button type="button" class="btn btn-primary btn-cor-primary btn-100" id="novoObjetivo_<?=$m_objetivo->id?>"><i class="fa fa-plus-circle" aria-hidden="true"></i> Objetivo</button>

	 				</div>

					<div class="col-md-10 col-sm-8 col-xs-12">

						<legend>Objetivos pertencentes a este grupo:</legend>

						<?php $m_objetivo->listObjetivosFilhos(); ?>

					</div>

				<?php } ?>
			
			</section> <!-- #objetivosfilhos -->


			<form id="novoObj" method="POST">
				<input type="hidden" name="prt" value="<?= $m_objetivo->id ?>">
				<input type="hidden" name="dmn" value="<?= $m_dominio->id ?>">
				<input type="hidden" name="action" value="">
			</form>

		</div> <!-- .corpoPage -->

	</div> <!-- .panel_main_page -->

<?php include_once __DIR__ . "/footer.php"; ?>
