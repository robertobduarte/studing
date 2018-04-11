
$( document ).ready( function(){

	//Carrega o editor html no textarea com a classe "mceEditor"
    tinyMCE.init({
        mode : "specific_textareas",
       
        force_br_newlines : true,
        force_p_newlines : true,
        forced_root_block : '', 
       
        editor_selector : "mceEditor",
        auto_focus: '',
        height: 300,
        plugins: [
            'advlist autolink lists link charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime table contextmenu paste code'
        ],
        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
        content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });

	$('#enviar').click(function(){

		$('input[name="file"]').click();
	});


	$('input[name="file"]').change( function(){

		//$('input[name="arquivo"]').val('');

		$('#listFile').find('li').remove();
		$('#arquivoDominio').find('span').remove();
		var arquivo = $('input[name="file"]')[0].files[0].name;
		$('#listFile').append('<li>Arquivo: ' + arquivo + '</li>');
	});


	$('button[id*="salvarDominio_"').click( function(){

		$(this).attr('disabled', true );

		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );
		
		var formId = 'dominio_'+id;

		if( validarForm( formId ) ){

			$("form#"+formId+" :input").each(function(){	
				$(this).attr('disabled', false );		
			});

			$('#dominio_'+id).submit();

		}else{
			
			$(this).attr('disabled', false );
			return false;

		}		
		
	});

	$('button[id*="excluirDominio_"]').click( function(){
		
		var dominio = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );

		$(this).attr('disabled', true );

		$('#mensagem').append(	'<div class="row alert alert-danger">'+
									'<div class="col-md-12">Confirmar exclusão deste domínio?</div>'+
									'<div class="col-md-2"><button class="btn btn-danger btn-100" id="confirmarExclusaoDominio_'+dominio+'">Excluir</button></div>'+
									'<div class="col-md-2"><button class="btn btn-success btn-100" id="cancelarExclusaoDominio_'+dominio+'">Cancelar</button></div>'+
								'</div>');
	});

	$( document ).on( "click", "button[id*='cancelarExclusaoDominio_']", function() {

		var dominio = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );

		$('#excluirDominio_'+dominio).attr('disabled', false );
		$('#mensagem').find('div').remove();
		return false;

	});	

	$( document ).on( "click", "button[id*='confirmarExclusaoDominio_']", function() {
		
		var dominio = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );
		
		enableInputs( 'dominio_'+dominio );

		$('input[name="action"').val('remover');
		$('#dominio_'+dominio).submit();
	});


});


function validarForm( idObjForm ){

	var error = 0;

	msgerro = $('#' + idObjForm + ' .error');
	$.each( msgerro, function() {		
		$(this).remove();		
	});


	obj = $('#' + idObjForm + ' .req');
	$.each( obj, function() {

		if( $(this).val() == '' ){
			$(this).parent().append('<span class="error">Campo obrigatório</span>');
			error++;
		}
		
	});

	if( idObjForm.indexOf('Modelo') >= 0 ) {
		if( !checkArquivo() ){
			error++;
		}
	}

	return ( error > 0 )? false : true;
}


function checkArquivo(){

	var tot = $('#listFile li').length;

	if( tot == 0 ){
		$('#arquivoDominio').append('<span class="col-md-12 error">É necessário selecionar o arquivo.</span>');
		return false;
	}

	return true;	
} 


/*function enableInputs( formId ){

	$("form#"+formId+" :input").each(function(){	
				$(this).attr('disabled', false );		
	});
}*/
