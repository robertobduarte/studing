<?php
include_once __DIR__ . "/../config.php";

include $_SERVER['DOCUMENT_ROOT'] . '/' . APP . "view/head.php";

?>


<div class="row panel_main_page">

	<div class="col-md-12 panel_conteudo">

		<?php echo 'HOME BASE'; ?>
		<?php debug($_SESSION); ?>

	</div><!-- .panel_conteudo -->

</div><!-- .panel_main_page -->

<?php include $_SERVER['DOCUMENT_ROOT'] . '/' . APP . "view/footer.php"; ?>