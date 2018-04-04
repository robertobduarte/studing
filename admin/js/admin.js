
$( document ).ready( function(){

	setHeightPanel_main_page();


});



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
