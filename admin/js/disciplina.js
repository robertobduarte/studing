$( document ).ready( function(){


	//mostra o modal com a disciplina
	$( document ).on( "click", "a[id*='disciplina_']", function() {

		var id = $(this).attr('id');
		var disciplina = id.substr( id.indexOf(''+1) );
		
		getDisciplina(disciplina);

	});

	//mostra o modal para criar uma nova disciplina
	$('button[id="novaDisciplina"]').click( function(){
		limpaDadosModal();
		showModal();
	});


	//salvar uma disciplina (nova ou edição)
	$('button[id*="salvar"').click( function(){

		$(this).attr('disabled', true );

		if( validarForm( 'form_disciplina' ) ){
			
			salvar();
			$(this).attr('disabled', false );

		}else{
			
			$(this).attr('disabled', false );
			return false;

		}			
	});


	//Adiciona uma nova competencia a disciplina
	$('button[id*="addCompetencia"').click( function(){

		$('#errorComp').remove();

		$(this).attr('disabled', true );

		if( $('input[name="competencia_nome"]').val() == '' ) {
						
			$('input[name="competencia_nome"]').parent().append('<span class="error" id="errorComp">Campo obrigatório</span>');
			$(this).attr('disabled', false );
			return false;

		}			
		
		addCompetencia();
		$(this).attr('disabled', false ); 
			
	});


	//fecha o modal e atualiza a tabela com as disciplinas
	$('button[id*="fechar"]').click( function(){

		$('#modalDisciplina').modal('hide');
		carregaDisciplinas();
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


function limpaDadosModal(){

	var inputs = $('#form_disciplina input');

	$.each( inputs, function(){

		if( !$(this).hasClass('noreset') ){
			$(this).val('');
		}
	});

	var textareas = $('#form_disciplina input[type=text], textarea');

	$.each( textareas, function(){

		if( !$(this).hasClass('noreset') ){
			$(this).val('');
		}
	});

	$('#listCompetencias').find('li').remove();
}


function populaModal( disciplina ){

	limpaDadosModal();

	$('input[name="id"').val( disciplina.id );
	$('input[name="nome"').val( disciplina.nome );
	$('textarea[name="descricao"').val( disciplina.descricao );

	
	if( disciplina['competencias'] != null ){

		var competencias = '';
		
		for( var i = 0; i < disciplina['competencias'].length; i++ ){
			competencias += '<li>' + disciplina['competencias'][i]['nome'] + '</li>';
		}

		$('#listCompetencias').append(competencias);
	}
	

	showModal();
}	


function showModal(){	

	$("#modalDisciplina").modal({
		backdrop: 'static',
		keyboard: false
	});
}


function salvar(){

	var formData = new FormData();
  	formData.append( 'id', $('input[name="id"]').val() ); 
  	formData.append( 'dominio', $('input[name="dominio"]').val() );
  	formData.append( 'nome', $('input[name="nome"]').val() ); 
  	formData.append( 'descricao', $('textarea[name="descricao"]').val() ); 
  	formData.append( 'method', 'ajaxRequest' ); 
  	formData.append( 'action', 'salvar');

	$('#mensagem_disciplina').find('div').remove();

	$.ajax({

		url:"../controller/controllerDisciplina.php",
		data: formData,
		type: 'POST',
		processData: false,
    	contentType: false,
		
		success: function(data){
			console.log(data);
			var obj = jQuery.parseJSON(data);

			if( obj['cod'] == 1 ){

				$('input[name="id"]').val( obj['id'] );
                $('#mensagem_disciplina').append('<div class="alert alert-success col-md-12">' + obj['msg'] + '</div>');


			}else{

				$('#mensagem_disciplina').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
			}
		}
	});

}


function getDisciplina( disciplina ){

	$('#mensagem_disciplina').find('div').remove();

	$.ajax({
		url:"../controller/controllerDisciplina.php",
		data: 	{
					action: 'getDisciplina',
					method: 'ajaxRequest',
					id: disciplina
				},
		Type: 'POST',
		success: function(data){

			var obj = jQuery.parseJSON(data);

			if( obj.cod ){

				populaModal( obj.disciplina );

			}else{

				$('#mensagem_disciplina').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
			}
		}
	});

}


function carregaDisciplinas(){

	$.ajax({
		url:"../controller/controllerDisciplina.php",
		data: 	{
					action: 'listDisciplinas',
					method: 'ajaxRequest',
					dominio: $('input[name="dominio"]').val()
				},
		Type: 'POST',
		success: function(data){

			var obj = jQuery.parseJSON(data);

			if( obj.cod ){

				atualizaDisciplinas( obj.disciplinas );

			}
		}
	});

}


function atualizaDisciplinas( disciplinas ){

	$('#listDisciplinas tbody').find('tr').remove();

	for( var i = 0; i < disciplinas.length; i++ ){

		$('#listDisciplinas').append('<tr id="tr_' + disciplinas[i]['id'] + '">' +
									 '<td>' + disciplinas[i]['id'] + '</td>' +
									 '<td><a href="#" id="disciplina_' + disciplinas[i]['id'] + '">' + disciplinas[i]['nome'] + '</a></td>' +
									 '<td>' + disciplinas[i]['descricao'] + '</td>' +
									 '</tr>' );
	}

}


function addCompetencia(){

	var nome = $('input[name="competencia_nome"]').val();

	var formData = new FormData();
  	formData.append( 'nome', nome ); 
  	formData.append( 'disciplina', $('input[name="id"]').val() );
  	formData.append( 'action', 'salvar' );
  	formData.append( 'method', 'ajaxRequest' );

	$('#mensagem_competencia').find('div').remove();

	$.ajax({

		url:"../controller/controllerCompetencia.php",
		data: formData,
		type: 'POST',
		processData: false,
    	contentType: false,
		
		success: function(data){
			console.log(data);
			var obj = jQuery.parseJSON(data);

			if( obj['cod'] == 1 ){

				$('input[name="competencia_nome"]').val('');
				$('#listCompetencias').append('<li>' + nome + '</li>');

			}else{

				$('#mensagem_competencia').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
			}
		}
	});

}