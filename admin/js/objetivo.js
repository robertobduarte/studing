$( document ).ready( function(){


	/*Salvar objetivo*/
	$('button[id*="salvarObjetivo_"]').click( function(){

		$(this).attr('disabled', true );

		var objetivo = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );

		var formId = 'objetivo_'+objetivo;

		if( validarForm( formId ) ){
			$('#objetivo_'+objetivo).submit();

		}else{

			$(this).attr('disabled', false );
			return false;
		}
	});

	/*Adicionar um novo objetivo filho*/
	$('button[id*="novoObjetivo_"]').click( function(){

		$("#novoObj").attr('action', 'objetivo.php');
		$("#novoObj").submit();

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

	if( !$("input[name='leaf']").is(':checked') ){

		error++;
		$('#objApr').append('<span class="error">Campo obrigatório</span>')

	}

	return ( error > 0 )? false : true;
}