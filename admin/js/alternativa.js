
$( document ).ready( function(){


	$('input[id*="enviarArqAlt_"]').click(function(){
		
		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );

		$('#fileAlt_'+id).click();
	});


	$('input[id*="fileAlt_"]').change( function(){

		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );

		$('input[name="arquivoAlt_'+id+'"]').val('');

		$('#listArqAlt_'+id).find('li').remove();
		$('#arquivoAlternativa_'+id).find('span').remove();
		var arquivo = $('#fileAlt_'+id)[0].files[0].name;
		$('#listArqAlt_'+id).append('<li>Arquivo: ' + arquivo + '</li>');
	});


	$('button[id*="salvarAlternativa_"').click( function(){

		$(this).attr('disabled', true );

		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );
		
		var formId = 'formAlternativa_'+id;

		if( validarFormAlternativa( formId ) ){
			
			salvarAlternativa( id );
			$(this).attr('disabled', false );

		}else{
			
			$(this).attr('disabled', false );
			return false;

		}			
	});


	$( document ).on( "click", 'a[id*="baixarArqAlternativa_"]', function(){

		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );		
		baixarArquivoAlternativa( id );					
	});

	$( document ).on( "click", 'a[id*="removerArqAlternativa_"]', function(){
	//$('a[id*="removerArqAlternativa_"]').click( function(){		

		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );
		var slide = $('input[name="slide"]').val();

		$('#mensagemAlt_'+slide).append(	'<div class="row alert alert-danger">'+
									'<div class="col-md-12">Confirma a exclusão definitiva deste arquivo?</div>'+
									'<div class="col-md-2"><button class="btn btn-danger btn-100" id="confirmaExclusaoArqAlt_'+id+'">Excluir</button></div>'+
									'<div class="col-md-2"><button class="btn btn-success btn-100" id="cancelaExclusaoArqAlt_'+id+'">Cancelar</button></div>'+
								'</div>');					
	});


	$( document ).on( "click", "button[id*='cancelaExclusaoArqAlt_']", function() {

		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );
		var slide = $('input[name="slide"]').val();

		$('#mensagemAlt_'+slide).find('div').remove();

	});	

	$( document ).on( "click", "button[id*='confirmaExclusaoArqAlt_']", function() {
		
		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );
		
		removerArquivoAlternativa( id );
	});

	/*
	alterar tipo de alternativa (texto ou html)
	*/
	$('input[name*="altTipo_"]').change( function(){

		var id = $(this).attr('name').substr( $(this).attr('name').indexOf('_')+1 );
		var tipo = $(this).val();

		$('div[id*=tipo-]').addClass('oculta');
		$('#tipo-'+id+'_'+tipo).removeClass('oculta');

		$('input[name="alternativa_tipo"]').val(tipo);

	});


	/*
	Remover alternativa
	*/
	$('button[id*="ExcluirAlternativa_"]').click( function(){

		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_') + 1 );
		
		$('#mensagemAlt_'+id).find('div').remove();

		$('#mensagemAlt_'+id).append(	'<div class="row alert alert-danger">'+
									'<div class="col-md-12">Confirma a exclusão definitiva desta alternativa?</div>'+
									'<div class="col-md-2"><button class="btn btn-danger btn-100" id="confirmaExclusaoAlternativa_'+id+'">Excluir</button></div>'+
									'<div class="col-md-2"><button class="btn btn-success btn-100" id="cancelaExclusaoAlternativa_'+id+'">Cancelar</button></div>'+
								'</div>');
	});
	

	$( document ).on( "click", "button[id*='cancelaExclusaoAlternativa_']", function() {

		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );

		$('#mensagemAlt_'+id).find('div').remove();

	});	

	$( document ).on( "click", "button[id*='confirmaExclusaoAlternativa_']", function() {
		
		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );
		
		removerAlternativa( id );
	});

	
});


function validarFormAlternativa( idForm ){

	var error = 0;

	msgerro = $('#' + idForm + ' .error');
	$.each( msgerro, function() {		
		$(this).remove();		
	});


	obj = $('#' + idForm + ' .req');
	$.each( obj, function() {

		if( $(this).val() == '' ){
			$(this).parent().append('<span class="error">Campo obrigatório</span>');
			error++;
		}
		
	});

	return ( error > 0 )? false : true;
}


function baixarArquivoAlternativa( id ){

	$('#mensagemAlt_'+id).find('div').remove();

	$.ajax({
			url: '../controller/controller.php?c=alternativa',
			data:  { 	action: 'baixarArquivo', 
						method: 'ajaxRequest', 
						id: id
				   },
			type: 'POST',
			success: function(data){

				var obj = jQuery.parseJSON(data);

				if(obj.cod == '1'){

					wnd = window.open('downloadArquivo.php?arquivo='+obj.caminho+'&dmn='+obj.dominio,'Localizacao','width=600,height=300,scrollbars=1,resizable=1');
					wnd.focus();

				}else{

					$('#mensagemAlt_'+id).append('<div class="alert alert-success col-md-12">' + obj['msg'] + '</div>');
				}		
			 }
	});	
}


function removerArquivoAlternativa( id ){

	var slide = $('input[name="slide"]').val();

	$('#mensagemAlt_'+slide).find('div').remove();

	$('#fileAlt_'+slide).val('');

	$.ajax({
			url: '../controller/controller.php?c=alternativa',
			data:  { 	action: 'removerArquivo', 
						method: 'ajaxRequest', 
						id: id
				   },
			type: 'POST',
			success: function(data){

				var obj = jQuery.parseJSON(data);

				if(obj.cod == '1'){

					$('#listArqAlt_'+slide).find('li').remove();

					$('#caminhoAlt_'+slide).val(''); 
					$('#arquivoAlt_'+slide).val(''); 

					$('#mensagemAlt_'+slide).append('<div class="alert alert-success col-md-12">' + obj['msg'] + '</div>');

				}else{

					$('#mensagemAlt_'+slide).append('<div class="alert alert-success col-md-12">' + obj['msg'] + '</div>');

				}

				
			}
	});	
}


function salvarAlternativa( id ) {
		
	$('#mensagemAlt_'+id).find('div').remove();

	//var alternativa = $('#idAlt_'+id).val();

    var formData = new FormData();

  	formData.append( 'file', $('#fileAlt_'+id)[0].files[0] ); 
  	formData.append( 'id', $('#idAlt_'+id).val() ); 
  	formData.append( 'slide', $('#slideAlt_'+id).val() );
  	formData.append( 'valor', $('#valorAlt_'+id).val() ); 
  	formData.append( 'texto', $('#textoAlt_'+id).val() ); 
  	formData.append( 'nome_arquivo', $('#arquivoAlt_'+id).val() ); 
  	formData.append( 'tipo', $('input[name="alternativa_tipo"]').val() ); 
  	//formData.append( 'caminho', $('#caminhoAlt_'+id).val() ); 
  	formData.append( 'texto_html', tinyMCE.get('textoAltHtml_'+id).getContent() ); 
  	formData.append( 'method', 'ajaxRequest' ); 
  	formData.append( 'action', 'salvar');
  	  
  	$.ajax({
        	url: '../controller/controller.php?c=alternativa',
        	data: formData,
        	type: 'POST',
        	contentType: false, 
        	processData: false,
        	success: function(data){

        	   	console.log(data);
        	   	var obj = jQuery.parseJSON(data);
        	   	
        	   	if( obj['cod'] == 1 ) {

                   	$('#idAlt_'+id).val( obj['id'] );

                   	if( obj['nome_arquivo'] != null ){

                   		$('#arquivoAlt_'+id).val(obj['nome_arquivo']);
                   		//$('#caminhoAlt_'+id).val(obj['caminho']);

                   		$('#listArqAlt_'+id).find('li').remove();
                   		$('#listArqAlt_'+id).append('<li class="iconeLink">'+
                   			'<li class="iconeLink">' + obj['nome_arquivo'] + '<a id="removerArqAlternativa_' + obj['id'] + '">'+
                   			'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>'+
                   			'<a id="baixarArqAlternativa_' + obj['id'] +'"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>'+
                   			'</li>'
                   			);                  			

                   	}

                   	$('#mensagemAlt_'+id).append('<div class="alert alert-success col-md-12">' + obj['msg'] + '</div>');

                }else{

               		$('#mensagemAlt_'+id).append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');

                }
                  
            }
    });

 }


function removerAlternativa( id ){

	$('#mensagemAlt_'+id).find('div').remove();

	var alternativa = $('#idAlt_'+id).val();

	$.ajax({
			url: '../controller/controller.php?c=alternativa',
			data:  { 	action: 'remover', 
						method: 'ajaxRequest', 
						id: alternativa
				   },
			type: 'POST',
			success: function(data){

				var obj = jQuery.parseJSON(data);

				if(obj.cod == '1'){

					carregaAlternativas();
					$('#modalAlt_'+id).modal('hide');

				}

				$('#mensagemAlt_'+id).append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
			}
	});	
}


	