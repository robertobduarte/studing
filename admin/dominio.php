<?php
include_once __DIR__ . "/headAdm.php";

$m_autenticacao->checkAcessAdm();

$dominio_id = ( isset( $_GET['dmn'] ) )? $_GET['dmn'] : '';

$m_dominio = new Dominio( array('id' => $dominio_id ) );

//$dadosForm = array('id' => $dominio_id, 'action' => 'editar' , 'method' => 'post' );
//$m_session->setValue('form_dominio', $dadosForm );
?>
<script src="js/dominio.js?v=<?= filemtime('js/dominio.js'); ?>"></script>
<script src="js/tinymce/js/tinymce/tinymce.min.js"></script>

<div class="row panel_main_page">

	<div class="col-md-12 panel_conteudo">

		<?php $m_session->showRetorno(); ?>

		<div class="col-md-12 tituloPage">
			<h3><?php echo ( !empty( $m_dominio->__get( 'nome' ) ) ) ? $m_dominio->__get( 'nome' ) : 'Novo Domínio'; ?></h3>
		</div>

		<div class="col-md-12 divesp"></div>

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">

			<li role="presentation" class="active"> <a href="#aba_dominio" aria-controls="dominio" role="tab" data-toggle="tab">Dominio</a></li>

		</ul>
  
		<!-- Tab panes -->
		<div class="tab-content">

			<div role="tabpanel" class="tab-pane active tabs" id="aba_dominio">

				<div class="col-md-12 divesp"></div>

				<section class="col-md-12" id="dominio">

					<?php $m_dominio->showFormulario( $m_session ); ?>

					<div class="col-md-12" id="mensagem"></div>

				</section> <!-- #dominio -->

			</div> <!-- #aba_dominio -->


			<div class="col-md-12 divesp"></div>

		</div> <!-- Tab panes -->

	</div> <!-- .panel_conteudo -->

</div> <!-- .panel_main_page -->


<?php include_once __DIR__ . "/footer.php"; ?>
