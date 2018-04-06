<?php
include_once __DIR__ . "/head.php";

$m_objetivo = new Objetivo();
$m_objetivo->listar(true);

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