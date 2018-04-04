<?php
include_once __DIR__ . "/head.php";

$m_objetivo = new Objetivo();
$m_objetivo->listar();

?>


<div class="row panel_main_page">

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

		</div>
	</div>
	
</div>



<?php include_once __DIR__ . "/footer.php"; ?>