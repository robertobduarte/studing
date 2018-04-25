
$( document ).ready( function(){

	setHeightPanel_main_page();


	//ao passar o mouse sobre o link do domínio adiciona uma classe
	$( ".link_dominio" ).hover(
		function() {
			$( this ).addClass('hover');
		}, function() {
	    	$( this ).removeClass('hover');
	  	}
	);

	//ao passar o mouse sobre os links no menu lateral
	$( ".listMenu" ).hover(
		function() {
			$( this ).addClass('menuOver');
			$( this ).find('a').addClass('aOver');
		}, function() {
	    	$( this ).removeClass('menuOver');
	    	$( this ).find('a').removeClass('aOver');
	  	}
	);
	/*
	//clique sobre o link
	$(".link_dominio").click( function(){

		var dominio = $(this).attr('id');
		
		window.location = 'listObjetivos.php?dmn='+dominio;
	});
	*/

	$( 'div[id*="linkDisciplina_"]' ).hover(
	  function() {
	    $(this).addClass('linkActive');
	  }, function() {
	    $(this).removeClass('linkActive');
	  }
	);



	//sanfona exibindo os objetivos e seus filhos
	$( document ).on( 'click', 'i[id*="folderobj_"]', function(){


		if( $(this).parent().next('ul').hasClass('oculta') ){

			$(this).removeClass('fa-folder');
			$(this).addClass('fa-folder-open');
			$(this).parent().next('ul').removeClass('oculta');


		}else{

			$(this).removeClass('fa-folder-open');
			$(this).addClass('fa-folder');
			$(this).parent().next('ul').addClass('oculta');
		}


	});


});


function getId(object){

	var id = object.attr('id');
	id = id.substr(id.indexOf('_')+1 );

	return id;

}

function setHeightPanel_main_page(){
	var dimensoes = getSizeView();
	$('.panel_main_page').css('height', dimensoes['alturaBrowser']);
	
	if( $('.panel_menu') ) {
		$('.panel_menu').css('height', dimensoes['alturaBrowser']);
	}
}
	


function getSizeView(){

	var alturaBrowser = $(window).height();   // returns height of browser viewport
	var alturaDocumento = $(document).height(); // returns height of HTML document (same as pageHeight in screenshot)
	var larguraBrowser = $(window).width();   // returns width of browser viewport
	var larguraDocumento = $(document).width(); // returns width of HTML document (same as pageWidth in screenshot)

	var dimensoes = new Array();
	dimensoes['alturaBrowser'] = alturaBrowser;
	dimensoes['alturaDocumento'] = alturaDocumento;
	dimensoes['larguraBrowser'] = larguraBrowser;
	dimensoes['larguraDocumento'] = larguraDocumento;

	return dimensoes;
}


function enableInputs( formId ){

	$("form#"+formId+" :input").each(function(){	
		$(this).attr('disabled', false );		
	});
}
