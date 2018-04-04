
$( document ).ready( function(){



$('#enviar').click(function(){

		$('input[name="file"]').click();
	});


	$('input[name="file"]').change( function(){

		$('input[name="arquivo"]').val('');

		$('#listCertificado').find('li').remove();
		$('#arquivoCertificado').find('span').remove();
		var arquivo = $('input[name="file"]')[0].files[0].name;
		$('#listCertificado').append('<li>Arquivo: ' + arquivo + '</li>');
	});



	/*
	clicou no ícone para remover um certificado (lisCertificado.php)
	*/
	$('i[id*="certificado_"]').click( function(){

		var id = $(this).attr('id').substr( $(this).attr('id').indexOf('_') + 1 );
		
		$('#tr_'+id).addClass('confirm');

		if( confirm('Excluir este certificado?') ){

			excluirCertificado( id );
		}

		$('#tr_'+id).removeClass('confirm');

	});


	/*
	carrega o modal com o dado variável
	*/
	$( document ).on( 'click', 'a[id*="dadovariavel_"]', function() {
		
		var id = $(this).attr('id').substr($(this).attr('id').indexOf('_')+1);

		carregaDado( id );
	});


	$('#novoDadoVariavel').click( function(){

		criarDadoVariavel();
	});


	$('#cancelar').click( function(){

		$('#modalDadosVariaveis').modal('hide');		
		carregaDadosVariaveis();

	});


	$('#salvar_dadovariavel').click( function(){

		$(this).attr('disabled', true );

		if( validarForm( 'formDadosVariaveis' ) ){
			
			salvarDadoVariavel();
			$(this).attr('disabled', false );

		}else{
			
			$(this).attr('disabled', false );
			return false;

		}			
	});	


	$('#excluir_dadovariavel').click( function(){

		$(this).attr('disabled', true );

		if( confirm('Excluir este campo?') ){

			excluirDadoVariavel();
		}

		$(this).attr('disabled', true );			
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

	return ( error > 0 )? false : true;
}


function salvarDadoVariavel(){

	$('#mensagemDadosVariaivel').find('div').remove();

	var formData = new FormData();
	
  	formData.append( 'nome', $('#dv_nome').val() ); 
  	formData.append( 'valor', $('#dv_valor').val() ); 
  	formData.append( 'posicao_v', $('#dv_posicao_v').val() ); 
  	formData.append( 'posicao_h', $('#dv_posicao_h').val() ); 
  	formData.append( 'posicao_h', $('#dv_posicao_h').val() ); 
  	formData.append( 'side', $('input[name="side"]:checked').val() );
  	formData.append( 'label', $('input[name="label"]:checked').val() );
	
  	$.ajax({
        	url: '../controller/controllerDadoVariavel.php',
        	data: formData,
        	type: 'POST',
        	contentType: false, 
        	processData: false,
        	success: function(data){

        	   	console.log(data);
        	   	var obj = jQuery.parseJSON(data);
        	   	
        	   	if( obj['cod'] == 1 ) {

                   	$('#mensagemDadosVariaivel').append('<div class="alert alert-success col-md-12">' + obj['msg'] + '</div>');

                }else{

               		$('#mensagemDadosVariaivel').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');

                }
                  
            }
    });
}



function excluirDadoVariavel(){

	$.ajax({
		url: "../controller/controllerDadoVariavel.php",
		data: {
			'action' : 'remover',
			'method' : 'ajaxRequest'
		},
		type: 'POST',

		success: function( data ){
			var obj = jQuery.parseJSON( data );

			if( obj.retorno ){

				$('#modalDadosVariaveis').modal('hide');		
				carregaDadosVariaveis();

			}else{
				alert( obj.mensagem );
			}
		}
	});
}



function excluirCertificado( id ){

	$.ajax({
		url: "../controller/controllerCertificado.php",
		data: {
			'action' : 'remover',
			'method' : 'ajaxRequest',
			'id' : id
		},
		type: 'POST',

		success: function( data ){
			var obj = jQuery.parseJSON( data );

			if( obj.retorno ){

				$('#tr_'+id).remove();
			}else{
				alert( obj.mensagem );
			}
		}
	});
}



function criarDadoVariavel(){

	$('#mensagemDadosVariaivel').find('div').remove();

	$.ajax({
		url:"../controller/controllerDadoVariavel.php",
		data: 	{
					action: 'novoDadoVariavel',
					method: 'ajaxRequest'
				},
		Type: 'POST',
		success: function(data){

			var obj = jQuery.parseJSON(data);

			if( obj.cod == '1' ){

				populaModal( obj.dado );

			}else{

				$('#mensagemDadosVariaivel').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
			}
		}

	});

}


function carregaDado( id ){

	$('#mensagemDadosVariaivel').find('div').remove();

	$.ajax({
		url:"../controller/controllerDadoVariavel.php",
		data: 	{
					action: 'getDado',
					method: 'ajaxRequest',
					id: id
				},
		Type: 'POST',
		success: function(data){

			var obj = jQuery.parseJSON(data);

			if( obj.cod == '1' ){

				populaModal( obj.dado );

			}else{

				$('#mensagemDadosVariaivel').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
			}
		}

	});

}


function showModal(){	

	$("#modalDadosVariaveis").modal({
						backdrop: 'static',
						keyboard: false
					});
}


function populaModal( dado ){

	resetDadosMoldal();

	$('#dv_nome').val( dado['nome'] );
	$('#dv_valor').val( dado['valor'] );
	$('#dv_posicao_v').val( dado['posicao_v'] );
	$('#dv_posicao_h').val( dado['posicao_h'] );
	
	

	if( dado['label'] != null ){

		var label = dado['label'];
		$('#label_'+label.toLowerCase()).prop('checked',true);

	}

	if( dado['side'] != null ){

		var side = dado['side'];
		$('#side_'+side.toLowerCase()).prop('checked',true);

	}

	
	showModal();
}


function resetDadosMoldal(){

	$('#mensagem').find('div').remove();
	$('#dv_nome').val('');
	$('#dv_valor').val('');
	$('#dv_posicao_v').val('');
	$('#dv_posicao_h').val('');
	$('input[name="side"]').prop('checked', false);
	$('input[name="label"]').prop('checked', false);

}

function carregaDadosVariaveis(){

	$('#mensagemDadosVariaivel').find('div').remove();

	$.ajax({
		url:"../controller/controllerDadoVariavel.php",
		data: 	{
					action: 'listDadosVariaveis',
					method: 'ajaxRequest'
				},
		Type: 'POST',
		success: function(data){

			var obj = jQuery.parseJSON(data);

			if( obj.cod == '1' ){

				atualizaDados( obj.dados );

			}else{

				$('#mensagemDadosVariaivel').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
			}
		}


	});
}


function atualizaDados( dados ){

	if( dados.length > 0){

		var tr = $('#listDados > tbody > tr' );

		$.each( tr, function(){
			$(this).remove();
		});
	

		for( i=0; i<dados.length; i++ ){

			if( dados[i]['valor'] != null ){
				var valor = dados[i]['valor'];
			}else{
				var valor = '';
			}

			$('#listDados tbody' ).append('<tr id="tr_' + dados[i]['id'] + '">'+
				'<td><a id="dadovariavel_' + dados[i]['id'] + '" href="#"> ' + dados[i]['nome'] + '</a></td>'+
				'<td>' + valor + '</td>'+
				'<td>' + dados[i]['posicao_h'] + '</td>'+
				'<td>' + dados[i]['posicao_v'] + '</td>'+			
				'</tr>'
			);
		}

		$('#mensagemDadosVariaivel').find('div').remove();
	}
}