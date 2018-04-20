<?php
include_once __DIR__ . "/headAdm.php";

$m_autenticacao->checkAcessAdm();

/*$m_usuario = new Usuario();*/

$m_dominio = new Dominio();

/*
echo "<pre>";
print_r( $_SESSION );
echo "</pre>";
*/
?>
<div class="row panel_main_page">

	<div class="col-md-12 panel_conteudo">
		
		<?php $m_session->showRetorno(); ?>

		<div class="col-md-12 tituloPage">
			<h3>USUÁRIOS</h3>
		</div>

		<div class="col-md-12 corpoPage">
			
			<div class="col-md-12 divesp"></div>

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">

				<li role="presentation" class="active"> <a href="#aba_admin" aria-controls="aba_adm" role="tab" data-toggle="tab">Adm. Dominio</a></li>

				<li role="presentation" > <a href="#aba_estudantes" aria-controls="aba_std" role="tab" data-toggle="tab">Estudantes</a></li>

			</ul>
	  
			<!-- Tab panes -->
			<div class="tab-content">

				<div role="tabpanel" class="tab-pane active tabs" id="aba_admin">

					<div class="col-md-12 divesp"></div>

					<section class="col-md-12" id="admin">

						<?php $m_dominio->listUsuariosAdmd(); ?>

						<div class="col-md-12" id="msg_admin"></div>

					</section> <!-- #admin -->

				</div> <!-- #aba_dominio -->


				<div role="tabpanel" class="tab-pane tabs" id="aba_estudantes">

					<div class="col-md-12 divesp"></div>
					
					<section class="col-md-12" id="estudantes">

						<?php /*$m_dominio->showFormulario( $m_session );*/ ?>

						<div class="col-md-12" id="msg_estudantes"></div>

					</section> <!-- #estudantes -->

				</div> <!-- #aba_estudantes -->

				<div class="col-md-12 divesp"></div>

			</div> <!-- Tab panes -->

		</div> <!-- .corpoPage -->

	</div> <!-- .panel_conteudo -->

</div> <!-- .panel_main_page -->

<?php include_once __DIR__ . "/footer.php"; ?>