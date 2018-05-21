$( document ).ready( function(){


	//mostra o modal com a disciplina
	$( document ).on( "click", "a[id*='disciplina_']", function() {

		var id = $(this).attr('id');
		var disciplina = id.substr( id.indexOf(''+1) );
		
		getDisciplina(disciplina);
	});

	//remover uma competencia
	$( document ).on( "click", "span[id*='rmcompetencia_']", function(){
			
			var competencia = getId( $(this) );

			//var competencia = id.substr( id.indexOf('_')+1 );
			$('li[id*="comp_'+competencia+'"]').addClass('remover');

			confirmarRemoverCompetencia(competencia);
	});
	//cancelou remoção competencia
	$( document ).on("click", "input[id*='ccomp_']", function(){

		var competencia = getId( $(this) );
		//var competencia = id.substr(id.indexOf('_')+1);

		$('#comp_'+competencia).removeClass('remover');
		$('#comp_'+competencia).find('input').remove();
	});

	//confirmou remoção competencia
	$( document ).on("click", "input[id*='rmcomp_']", function(){

		var competencia = getId( $(this) );
		//var competencia = id.substr(id.indexOf('_')+1);

		removerCompetencia( competencia );

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


function confirmarRemoverCompetencia(id){

	$('#comp_'+id).append('<input id="rmcomp_'+id+'" type="button" style="margin:0 5px;" class="btn btn-danger" value="Excluir"><input type="button" id="ccomp_'+id+'" value="Cancelar" class="btn btn-success">');
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

			listCompetencia( disciplina['competencias'][i]['id'], disciplina['competencias'][i]['nome'] );
		}
	}
	
	showModal();
}	


function listCompetencia( id, nome ){

	var icnRemover = '<span style="cursor:pointer; margin-right:5px;" id="rmcompetencia_' + id + '" class="glyphicon glyphicon-remove"></span>';
	competencia = '<li id="comp_'+id+'">' + icnRemover + nome + '</li>';
	$('#listCompetencias').append(competencia);
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

		url:"../controller/controller.php?c=disciplina",
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

			setTimeout(function() { 
				$('#mensagem_disciplina').find('div').remove();
			}, 6000);
		}
	});

}


function getDisciplina( disciplina ){

	$('#mensagem_disciplina').find('div').remove();

	$.ajax({
		url:"../controller/controller.php?c=disciplina",
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

			setTimeout(function() { 
				$('#mensagem_disciplina').find('div').remove();
			}, 6000);
		}
	});

}


function carregaDisciplinas(){

	$.ajax({
		url:"../controller/controller.php?c=disciplina",
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

	$('#mensagem_competencia').find('div').remove();

	var nome = $('input[name="competencia_nome"]').val();

	var formData = new FormData();
  	formData.append( 'nome', nome ); 
  	formData.append( 'disciplina', $('input[name="id"]').val() );
  	formData.append( 'action', 'salvar' );
  	formData.append( 'method', 'ajaxRequest' );

	$('#mensagem_competencia').find('div').remove();

	$.ajax({

		url:"../controller/controller.php?c=competencia",
		data: formData,
		type: 'POST',
		processData: false,
    	contentType: false,
		
		success: function(data){
			console.log(data);
			var obj = jQuery.parseJSON(data);

			if( obj['cod'] == 1 ){

				$('input[name="competencia_nome"]').val('');
				listCompetencia( obj['id'], nome );

			}else{

				$('#mensagem_competencia').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
			}

			$('#addCompetencia').attr('disabled', false );

			setTimeout(function() { 
				$('#mensagem_competencia').find('div').remove();
			}, 6000);
		}
	});

}


function removerCompetencia(id){

	$('#comp_'+id).find('input').remove();
	$('#mensagem_competencia').find('div').remove();

	$.ajax({
		url:"../controller/controller.php?c=competencia",
		data: { id: id,
			method: 'ajaxRequest',
				action: 'remover'
			},
		type: 'POST',

		success: function( data ){

			var obj = jQuery.parseJSON(data);

			if( obj['cod'] ){

				$('#comp_'+id).remove();

			}else{

				$('#comp_'+id).removeClass('remover');
				$('#mensagem_competencia').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');

			}

			setTimeout(function() { 
				$('#mensagem_competencia').find('div').remove();
			}, 6000);
		}
	});
}