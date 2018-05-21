$( document ).ready( function(){

	
	//Carrega o editor html no textarea com a classe "mceEditor"
  tinyMCE.init({
        mode : "specific_textareas",
       
        force_br_newlines : false,
        force_p_newlines : false,
        forced_root_block : '', 
        file_browser_callback_types: 'file image media',
       
        editor_selector : "mceEditor",
        auto_focus: '',
        height: 300,
        plugins: [
            'advlist autolink lists link charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime table contextmenu paste code image'
        ],
        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent link image',
        content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
        ],

        // enable title field in the Image dialog
  image_title: true, 
  // enable automatic uploads of images represented by blob or data URIs
  automatic_uploads: true,
  // URL of our upload handler (for more details check: https://www.tinymce.com/docs/configure/file-image-upload/#images_upload_url)
  
  
  images_upload_url: '../controller/upload.php',
  
  // here we add custom filepicker only to Image dialog
  file_picker_types: 'image', 


  // and here's our custom image picker
  file_picker_callback: function(cb, value, meta) {
      var input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', 'image/*');
      
      // Note: In modern browsers input[type="file"] is functional without 
      // even adding it to the DOM, but that might not be the case in some older
      // or quirky browsers like IE, so you might want to add it to the DOM
      // just in case, and visually hide it. And do not forget do remove it
      // once you do not need it anymore.

      input.onchange = function() {
        var file = this.files[0];
        
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          // Note: Now we need to register the blob in TinyMCEs image blob
          // registry. In the next release this part hopefully won't be
          // necessary, as we are looking to handle it internally.
          var id = 'blobid' + (new Date()).getTime();
          var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
          var base64 = reader.result.split(',')[1];
          var blobInfo = blobCache.create(id, file, base64);
          blobCache.add(blobInfo);

          // call the callback and populate the Title field with the file name
          cb(blobInfo.blobUri(), { title: file.name });
        };
      };
      
      input.click();
      
    }
      
  });


  $('#listCompetencia').change( function(){

    
      var id = $( "#listCompetencia option:selected" ).val();
      var nome = $( "#listCompetencia option:selected" ).text();

      if( id != '' ){

          var competencias = ( $('input[name="competencias"]').val() != '' )? $('input[name="competencias"]').val() + ',' : $('input[name="competencias"]').val();

          $('input[name="competencias"]').val( competencias + id );

          
          $( "#listCompetencia option:selected" ).attr('disabled', true);           

          $('#slideCompetencias').append('<li id="comp_'+id+'">'+
            '<span style="cursor:pointer; margin-right:5px;" id="rmcompetencia_'+id+'" class="glyphicon glyphicon-remove"></span>'+
            nome+'</li>');

      }

      $("#listCompetencia option[value='']").prop('selected',true);

  });


  $( document ).on( "click", "span[id*='rmcompetencia_']", function() { 
        var id = $(this).attr('id');
        var competencia = id.substr( id.indexOf('_')+1 );

        if( competencia != 0 ){

          $('#comp_'+competencia).remove();

          var competencias = $('input[name="competencias"]').val().replace( competencia, '' );
          $('input[name="competencias"]').val( competencias );

          $("#listCompetencia option[value='"+competencia+"']").attr('disabled', false); 

        }

        $("#listCompetencia option[value='']").prop('selected',true);       
      });



    $('#tipo_slide').change( function(){

      var tipo = $('#tipo_slide').val();

      var div = $('div[id*="slide_"]');

      $.each( div, function(){
        $(this).addClass('oculta');
      });

      $('#slide_'+tipo).removeClass('oculta');

      if( tipo == 'SL' ){
        $('#comentario').addClass('oculta');      
      }else{
        $('#comentario').removeClass('oculta');
      }

    });



    $('button[id*="novaAlternativa_"]').click( function(){

      resetDadosMoldal();
      showModal();
    });


    $( document ).on( 'click', 'a[id*="alternativa_"]', function() {
    
      var id = $(this).attr('id').substr($(this).attr('id').indexOf('_')+1);

      carregaAlternativa( id );
    });


});
	

function resetDadosMoldal(){

  var slide = $('input[name="id"]').val();

  $('#fileAlt_'+slide).val('');
  $('#listArqAlt_'+slide).find('li').remove();

  $('#tipo-'+slide+'_AH' ).addClass('oculta');  
  $('#tipo_AH').prop( "checked", false );

  $('#tipo-'+slide+'_AT' ).addClass('oculta');
  $('#tipo_AT').prop( "checked", false );

  $('#mensagemAlt_'+slide).find('div').remove();
  $('#idAlt_'+slide).val('');
  $('#slideAlt_'+slide).val( slide );
  $('#arquivoAlt_'+slide).val('');
  $('#caminhoAlt_'+slide).val('');
  $('#alternativa_tipo_'+slide).val('');
  $('#valorAlt_'+slide).val('');
  $('#textoAlt_'+slide).val('');
  tinymce.get('textoAltHtml_'+slide).setContent('');

}

function showModal(){ 

  var slide = $('input[name="id"]').val();

  $("#modalAlt_"+slide).modal({
            backdrop: 'static',
            keyboard: false
          });
}


function carregaAlternativas(){

  $('#mensagem').find('div').remove();

  var slide = $('input[name="id"]').val();

  $.ajax({
    url:"../controller/controller.php?c=slide",
    data:   {
          action: 'listAlterantivas',
          method: 'ajaxRequest',
          id: slide
        },
    Type: 'POST',
    success: function(data){

      var obj = jQuery.parseJSON(data);

      if( obj.cod == '1' ){

        atualizaAlternartivas( obj.alternativas );

      }else{

        $('#mensagem').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
      }
    }


  });
}

function atualizaAlternartivas( alternativas ){

  if( alternativas.length > 0){

    var slide = $('input[name="id"]').val();
    var tr = $('#listAlt_' + slide + ' > tbody > tr' );

    $.each( tr, function(){

      //alternativa = $(this).attr('id').substr( $(this).attr('id').indexOf('_')+1 );
      $(this).remove();
    });
  
    for( i=0; i<alternativas.length; i++ ){

      var arquivo = ( alternativas[i]['arquivo'] == null )? '' : '<i class="fa fa-file-image-o" aria-hidden="true"></i>';
      var html = ( alternativas[i]['texto_html'] == null )? '' : alternativas[i]['texto_html'];
      var text = ( alternativas[i]['texto'] == null )? '' : alternativas[i]['texto'];

      $('#listAlt_' + slide + ' tbody' ).append('<tr id="tr_' + alternativas[i]['id'] + '">'+
        '<td><a href="#" id="alternativa_' + alternativas[i]['id'] + '" > <i class="fa fa-pencil" aria-hidden="true"></i></a></td>'+
        '<td>' + arquivo + '</td>'+
        '<td>' + alternativas[i]['tipo'] + '</td>'+
        '<td>' + alternativas[i]['valor'] + '</td>'+
        '<td>' + text + '</td>'+
        '<td>' + html + '</td>'+        
        '</tr>'
      );

      $('#mensagemAlt_' + alternativas[i]['id']).find('div').remove();
    }
  }
}



function carregaAlternativa( id ){

  $('#mensagem').find('div').remove();

  $.ajax({
    url:"../controller/controller.php?c=alternativa",
    data:   {
          action: 'getAlterantiva',
          method: 'ajaxRequest',
          id: id
        },
    Type: 'POST',
    success: function(data){

      var obj = jQuery.parseJSON(data);

      if( obj.cod == '1' ){

        populaModal( obj.alternativa );

      }else{

        $('#mensagem').append('<div class="alert alert-danger col-md-12">' + obj['msg'] + '</div>');
      }
    }


  });
}

function populaModal( alternativa ){

  resetDadosMoldal();

  var slide = $('input[name="id"]').val();

  $('#fileAlt_'+slide).val('');

  $('#mensagemAlt_'+slide).find('div').remove();
  $('#idAlt_'+slide).val( alternativa['id'] );
  $('#slideAlt_'+slide).val( alternativa['slide'] );
  $('#arquivoAlt_'+slide).val( alternativa['arquivo'] );
  $('#caminhoAlt_'+slide).val( alternativa['caminho'] );
  $('#alternativa_tipo_'+slide).val( alternativa['tipo'] );
  $('#valorAlt_'+slide).val( alternativa['valor'] );
  $('#tipo_'+alternativa['tipo']).prop( "checked", true );
  $('#textoAlt_'+slide).val( alternativa['texto'] );

  if( alternativa['texto_html'] != null ){

    tinymce.get('textoAltHtml_'+slide).setContent( alternativa['texto_html'] );

  } 
  
  if( alternativa['tipo'] == 'AT' ){

    $('#tipo-'+slide+'_AH' ).addClass('oculta');
    $('#tipo-'+slide+'_AT' ).removeClass('oculta');

  }else{

    $('#tipo-'+slide+'_AT' ).addClass('oculta');
    $('#tipo-'+slide+'_AH' ).removeClass('oculta');
  }

  $('#listArqAlt_'+slide).find('li').remove();

  if( alternativa['nome_arquivo'] != null ){

    $('#listArqAlt_'+slide).append( '<li class="iconeLink">'+alternativa['nome_arquivo']+
        '<a id="removerArqAlternativa_' + alternativa['id'] + '"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>'+
        '<a id="baixarArqAlternativa_' + alternativa['id'] + '"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>'+
        '<li>'
      );
  }

  showModal();

}


function resetDadosMoldal(){

  var slide = $('input[name="id"]').val();

  $('#fileAlt_'+slide).val('');
  $('#listArqAlt_'+slide).find('li').remove();

  $('#tipo-'+slide+'_AH' ).addClass('oculta');  
  $('#tipo_AH').prop( "checked", false );

  $('#tipo-'+slide+'_AT' ).addClass('oculta');
  $('#tipo_AT').prop( "checked", false );

  $('#mensagemAlt_'+slide).find('div').remove();
  $('#idAlt_'+slide).val('');
  $('#slideAlt_'+slide).val( slide );
  $('#arquivoAlt_'+slide).val('');
  $('#caminhoAlt_'+slide).val('');
  $('#tipo_'+slide).val('');
  $('#valorAlt_'+slide).val('');
  $('#textoAlt_'+slide).val('');
  tinymce.get('textoAltHtml_'+slide).setContent('');

}