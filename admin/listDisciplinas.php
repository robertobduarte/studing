<?php
include_once __DIR__ . "/headDominio.php"; //página instancia um dominio - $m_dominio

$m_session->setValue( 'menu', '2' );

$m_disciplina = new Disciplina();
$m_disciplina->__set( 'Disciplina', array( 'dominio' => $m_dominio->__get('id') ) );
$m_disciplina->listar( $m_dominio->__get('id') );

/*$dadosForm = array( 'method' => 'ajaxRequest' );
$m_session->setValue('form_disciplina', $dadosForm );*/
?>

<script src="js/disciplina.js?v=<?= filemtime('js/disciplina.js'); ?>"></script>

<div class="row panel_main_page">

	<div class="hidden-xs col-md-2 col-sm-3 panel_menu" id="menu_lateral">
		<?php include __DIR__ . "/menuLateral.php"; ?>
	</div>


	<div class="col-md-10 col-sm-9 col-xs-12 panel_conteudo">

		<?php $m_session->showRetorno(); ?>

		<div class="col-md-12 tituloPage">
			<h3>DISCIPLINAS</h3>
		</div>

		<div class="col-md-12 corpoPage">
			
			<div class="row">

			<?php $m_disciplina->buttonNovaDisciplina(); ?>

			</div>

			<div class="row">
				<div class="divesp"></div>
			</div>
			
			<div class="row">
			
				<?php $m_disciplina->listDisciplinas(); ?>

			</div>
		</div>

	</div>

</div>

<?php include_once __DIR__ . "/footer.php"; ?>