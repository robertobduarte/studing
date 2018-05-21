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


	//exibir modal vincular disciplina - atualiza lista de links para o banco de questões
	$('#addDisciplina').click( function(){
		showModal();
	});

	//fechar modal vincular disciplina
	$('#fecharModal').click( function(){
		$('#modalDisciplinasObjetivo').modal('hide');
		atualizaLinksBQ();
	});


	//vincular uma disciplina ao objetivo
	$( document ).on( "click", 'td[id*="addDisc_"]', function(){
		var disciplina = getId( $(this) );
		addDisciplinaObjetivo( disciplina );
	});

	//desvincular uma disciplina ao objetivo
	$( document ).on( "click", 'td[id*="removeDisc_"]', function(){
		var disciplina = getId( $(this) );
		rmDisciplinaObjetivo( disciplina );
	});

	//vincular/desvincular disciplina - addClass ou removeClass - mouseOver
	$( 'td[id*="addDisc_"]' ).on( "mouseenter mouseleave", classAddDisciplina );
	$( 'td[id*="removeDisc_"]' ).on( "mouseenter mouseleave", classRmDisciplina );

});


//exibir modal vincular disciplina
function showModal(){	

	$("#modalDisciplinasObjetivo").modal({
		backdrop: 'static',
		keyboard: false
	});
}


function classAddDisciplina(){

	$( 'td[id*="addDisc_"]' ).hover(
	  function() {
	    $(this).addClass('addDiscActive');
	  }, function() {
	    $(this).removeClass('addDiscActive');
	  }
	);

}

function classRmDisciplina(){

	$( 'td[id*="removeDisc_"]' ).hover(
	  function() {
	    $(this).addClass('rmDiscActive');
	  }, function() {
	    $(this).removeClass('rmDiscActive');
	  }
	);

}

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


function addDisciplinaObjetivo( disciplina ){

	$.ajax({
		url:"../controller/controller.php?c=disciplina",
		data:{ 
				action: 'addDisciplinaObjetivo',
				method: 'ajaxRequest',
				id: disciplina,
				objetivo: $('input[name="objetivo"]').val()
			},
			Type: 'POST',
			success: function( data ){

				var obj = jQuery.parseJSON(data);

				if( obj.cod ){

					var nome = $('#addDisc_'+disciplina).text();
					$('#addDisc_'+disciplina).remove();
					
					$('#listDisciplinasVinculadas').find('tbody').append('<tr><td class="addDisc" id="removeDisc_' + disciplina + '">' +
																	'<span class="glyphicon glyphicon-chevron-left"></span> ' +
																	nome + '</td></tr>');
				}else{

					$('#mensagem_disciplina').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
				}

				setTimeout(function() { 
					$('#mensagem_disciplina').find('div').remove();
				}, 6000);

			}
	});
}


function rmDisciplinaObjetivo( disciplina ){

	$.ajax({
		url:"../controller/controller.php?c=disciplina",
		data:{ 
				action: 'rmDisciplinaObjetivo',
				method: 'ajaxRequest',
				id: disciplina,
				objetivo: $('input[name="objetivo"]').val()
			},
			Type: 'POST',
			success: function( data ){

				var obj = jQuery.parseJSON(data);

				if( obj.cod ){

					var nome = $('#removeDisc_'+disciplina).text();
					$('#removeDisc_'+disciplina).remove();
					
					$('#listDisciplinasDisponiveis').find('tbody').append('<tr><td class="addDisc" id="addDisc_' + disciplina + '">' +
																	nome + '<span class="glyphicon glyphicon-chevron-right"></span> ' +
																	'</td></tr>');
				}else{

					$('#mensagem_disciplina').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
				}

				setTimeout(function() { 
					$('#mensagem_disciplina').find('div').remove();
				}, 6000);

			}
	});
}


function atualizaLinksBQ(){

	$.ajax({
		url:"../controller/controller.php?c=objetivo",
		data:{
				action: 'getDisciplinasObjetivo',
				method: 'ajaxRequest',
				dominio: $('input[name="dominio"]').val(),
				id: $('input[name="objetivo"]').val()
		},
		Type: 'POST',
		success: function( data ){

			var obj = jQuery.parseJSON(data);

			if( obj.cod ){

				atualizaLinksBancoQuestoes( obj.disciplinas );
			}

		}

	});
}

function atualizaLinksBancoQuestoes( disciplinas ){

	$('#linksDisciplinas').find('div').remove();
	
	if( disciplinas.length >= 1 ){

		var objetivo = $('input[name="objetivo"]').val();
		var links = '';

		for( var i = 0; i < disciplinas.length; i++ ){

			var id = disciplinas[i].id;
			var nome = disciplinas[i].nome;

			links += '<div class="col-md-2 col-sm-4 col-xs-6 linkDisciplina" id="linkDisciplina_' + id + '">';
				links += '<a href="bancodequestoes.php?obj=' + objetivo + '&disc=' + id + '">';
					links += '<img src="img/estudar.png" alt="" class="img-circle" style="max-width:100px;"><br>';
					links += '<span>' + nome + '</span>';
				links += '</a>';
			links += '</div>';
		}

		$('#linksDisciplinas').append(links);
	}

}
