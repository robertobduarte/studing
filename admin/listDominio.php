<?php
include_once __DIR__ . "/headAdm.php";

$m_autenticacao->checkAcessAdm();

$m_dominio = new Dominio();

/*echo "<pre>";
print_r( $_SESSION );
echo "</pre>";*/
?>

<div class="container">

	<div class="row panel_main_page">

	<div class="col-md-12 panel_conteudo">
		
		<?php $m_session->showRetorno(); ?>

		<div class="col-md-12 tituloPage">
			<h3>DOMÍNIOS</h3>
		</div>

		<div class="col-md-12 corpoPage">
			
			<?php $m_dominio->buttonNovoDominio(); ?>

			<div class="col-md-12">
			
				<?php $m_dominio->listDominios(); ?>

			</div>
		</div>

	</div> <!-- .panel_main_page -->

</div> <!-- .container -->

<?php include_once __DIR__ . "/footer.php"; ?>