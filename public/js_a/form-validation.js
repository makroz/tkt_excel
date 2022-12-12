var _flagMod = 0;
(function($) {
  'use strict';
  $.validator.setDefaults({
    submitHandler: function() {
      console.log('paso');
      submit();
      //alert("submitted!");
      
    }
  });
  $(function() {

    var emails = [];
    var $options = $('#email_dominio option');
    if($options.length){
      $options.each(function(e){
        var $this = $(this);
        var text = $this.text();
        emails.push(text);
      });
    }
    //console.log(emails);


    $("#btnSumImport").click( function(){
        $("#estudiantesImportSave").submit();
      }
    );

    $('#btnCerrar').click((e) => {
      location.reload();
    });

    /* DAR BAJA - MANUAL */
    $('.dar_baja').click( e => {
      e.preventDefault();
      let dni = e.currentTarget.getAttribute('data-dni');
      let evento = e.currentTarget.getAttribute('data-evento');

      //let url = estudiantes/{dni}/{evento}
      let url = `estudiantes/${dni}/${evento}`;

      //console.log("dni: "+dni+" evento: "+ evento+ " url:"+url);
        swal({
          title: "Estás seguro?",
          text: "Una vez eliminado, no podrá recuperar su registro de actividad",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((value) => {

          if(value == true){
            console.log('Aprobado');

            $.ajax({
              url: url,
              type:'GET',
              processData: false,
              contentType: false,
                beforeSend: function(){
                    //toastr.warning('Procesando su solicitud');
                },
              success: function(respuesta){

                if(respuesta == 1){
                  swal("Eliminado!", "Se dio de baja al participante.", "success")
                  .then((val) => {
                    if(val == true){location.reload();}
                  });
                  
                }else{
                  swal("Error!", "El participante no tiene registrado actividades.", "error");
                }
                 
              },
              error: function(xhr, status, error){
                
                var err = JSON.parse(xhr.responseText);
                alert(error);
                  
              }
          });


        } // end if


      });
      
      console.log('Dar baja');

      /*swal({ type:'warning',icon: "success",title:'Se dio de baja al participante',
        button: "Cerrar"
      });*/

    });

    /* REENVIO INVITACION Y CONF**/
    $('.solicitud').click((e) => {
      e.preventDefault();

      let dni = e.currentTarget.getAttribute('data-dni');
      let evento = e.currentTarget.getAttribute('data-evento');
      let tipo = e.currentTarget.getAttribute('data-tipo');

      //console.log("dni: "+dni+" evento: "+ evento+" tipo: "+ tipo);

      //var url = `estudiantes/${id}/s/${dni}/${evento}/${tipo}`;
      var url = `s/${dni}/${evento}/${tipo}`;
      console.log(url);
      //return false;

      $.ajax({
            url: url,
            type:'GET',
            //data: new FormData( this ),
            processData: false,
            contentType: false,
              beforeSend: function(){
                  //toastr.warning('Procesando su solicitud');
              },
            success: function(respuesta){

                showToastPosition('bottom-right', respuesta);
                console.log('Reenvio conf:');
               
            },
            error: function(xhr, status, error){
              
              var err = JSON.parse(xhr.responseText);
              alert(error);
                
            }
        });
    });

    showToastPosition = function(position, respuesta){
        'use strict';
        resetToastPosition();
        $.toast({
          heading: 'Mensaje',
          text: String(respuesta.msg),
          position: String(position),
          icon: respuesta.tipo,
          stack: false,
          loaderBg: respuesta.color,//'#f96868'
          hideAfter: 5000
        })
      }

    // cerrar modal aviso
    $('.close-jq-toast-single').click(e => {
      $('.jq-toast-single').css('display','none');
    });

    //$("#estudiantesForm :input").attr("disabled", true);
    //$("#estudiantesForm #cboTipDoc").attr("disabled", false);
    
    // VALIDADCION FORM CAII PG
    // DNI - CARNET EXTR.
 
    /*function seleccionaTipoDoc(){}
    seleccionaTipoDoc();*/

    $("#cboTipDoc").on('change', function(e){
      var $tipo = $("#cboTipDoc");
      var $nro = $("#dni_doc");
      var $ap_mat = $("#ap_materno");
      var tipo = $tipo.val();
      var nro = $nro.val();
      if(tipo == 1){
        $nro.val('');
        //$nro.attr("type", 'number').attr('maxlength',8);
        $nro.attr({type:'number', maxLength:8});
        $ap_mat.attr({required: true});
        $(".ap_materno span").addClass('d-inline-block');
      }else{
        $nro.attr("type", 'text').attr('maxlength',15);
        $ap_mat.attr({required: false});
        $(".ap_materno span").addClass('d-none');
      }
    });
    
    $("#dni_doc").keypress(function (e){
      var charCode = (e.which) ? e.which : e.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)&&$("#cboTipDoc").val()==1) {
        return false;
      }
    });

    // PAIS -> REGION
    $('#pais').change(function (event){
      console.log(event.target.value);
      
      if(event.target.value == 'PERU'){
        $('#cboDepartamento, #dpto, #provincia, #distrito').removeAttr('disabled','disabled');
        $('#cboDepartamento, #dpto').attr('required','required');
        $('.required_camp').attr('required','required').removeClass('d-none');

        let url = baseURL('')+"getUbigeo/"+event.target.value+""
        $.get(url,function(resp,depa){
        //$.get("ubigeo/"+event.target.value+"",function(resp,depa){
          console.log("ID: "+event.target.value);
          if(resp.length>0){
            //console.log("valor country ="+resp.length);

            $('#cboDepartamento, #dpto').empty();
            $("#cboDepartamento, #dpto").append("<option value=''>SELECCIONE</option>");
            for(var i=0;i<resp.length;i++){
              $("#cboDepartamento, #dpto").append("<option value='"+resp[i].nombre+"'>"+resp[i].nombre+"</option>");
            }
              //$("#cboDepartamento, #dpto").append("<option value='"+resp[i].ubigeo_id+"'>"+resp[i].nombre+"</option>");
          }else{
            console.log("0 Registros.");
          }
          
        });

      }else{
        $('#cboDepartamento, #dpto, #provincia, #distrito').empty();
        $("#cboDepartamento, #dpto, #provincia, #distrito").append("<option value='0'>SELECCIONE</option>");
        $('#cboDepartamento, #dpto, #provincia, #distrito').attr('disabled','disabled');
        $('#cboDepartamento, #dpto, #provincia, #distrito').removeAttr('required','required');
        $('.required_camp').removeAttr('required','required').addClass('d-none');
      }
    });

    // FORM EVENTOS
    $("#gafete").on('change', function(){
      var tipo = $("#gafete").val();

      if(tipo == "1"){
        $("#campo_gafete").removeClass('d-none');
      }else{
        $("#campo_gafete").addClass('d-none');
      }
    });

    $("#auto_conf").on('change', function(){
      var tipo = $("#auto_conf").val();
      console.log('click auto_conf');

      if(tipo == "1"){
        $("#auto_conf_div, .campos_opcles").removeClass('d-none');
        $('#opc_1, #opc_3, #email_id').attr({'required':true});
        $('#confirm_email').attr({"checked":true});
      }else{
        $("#auto_conf_div, .campos_opcles").addClass('d-none');
        $('#opc_1, #opc_3, #email_id').attr({'required':false});
        $('#confirm_email').attr({"checked":false});
        $('#email_asunto,#email_id,#fecha_texto').val('');
      }
    });

    // Crear Actividades
    $('.addActividades').click(function (event){
      $("#modalAct").modal('show');

      /*var formAction = $(this).attr("action");
      console.log(formAction);*/

      var id_est = $(this).data('id');
      var url = "eventos_list_dias/"+id_est+"";      

      $.get(url, function (resp,resul){
        console.log('Ajaxs '+ url);

        $(".modal-body").html(resp);
      });

    });

    // upload add actividad
    $('.file-upload-browse').on('click', function() {
      var file = $(this).parent().parent().parent().find('.file-upload-default');
      file.trigger('click');
    });
    $('.file-upload-default').on('change', function() {
      $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    });

    // end form CAII

    // PASAPORTE  : CONTINENTE PAIS CIUDAD
    $('#cboDepartamento').change(function (event){
      
      $.get("ubigeo/"+event.target.value+"",function(resp,depa){
        console.log("ID: "+event.target.value);
        if(resp.length>0){
          console.log("valor depar ="+resp.length);

          $('#cboProvincia').empty();
          $("#cboProvincia").append("<option value='0'>SELECCIONE</option>");
          for(var i=0;i<resp.length;i++){
            $("#cboProvincia").append("<option value='"+resp[i].ubigeo_id+"'>"+resp[i].nombre+"</option>");
          }
        }else{
          console.log("0 Registros.");
        }
        
      });
    });

    // ACTIVAR HIDDEN EMAIL Y CEL
    var email = $('#email');
    var email_dominio = $('#email_dominio');
    var celular = $('#celular');
    var editCel = $('#editCel');
    var editEmail = $('#editEmail');

    $('#editCel').click(function (e){
      e.preventDefault();
      editCel.css('display','none');
      celular.attr('disabled',false).attr('type','number').val('');
    });

    $('#editEmail').click(function (e){
      e.preventDefault();
      editEmail.css('display','none');
      email.attr('disabled',false).val('');
    });

    // Buscar por DNI
    var $dni_doc=$("#dni_doc");
    $dni_doc.bind("change",function(e){
      var evento = $('#eventos_id').val();
      var xdni = $('#dni_doc').val();
      var xcurso = $('#cod_curso').val();
      
      var pos=this.value;
      var url = baseURL('')+"getDNI/"+e.target.value+"/"+evento+"";
      console.log(url);
      
      $.get(url, function(resp,depa){
        
        if(resp.datos.length > 0){

            // VALIDAR QUE SEA OTRO EVENTO // 7 es Mod DDJJ / estudiantes_tipo_id=7
            if((resp.datos[0].eventos_id == evento && resp.datos[0].tipo_id == 2) || 
                (resp.datos[0].eventos_id == evento && resp.datos[0].tipo_id == 1) ||
                (resp.datos[0].eventos_id == evento) && (resp.datos[0].estudiantes_tipo_id != 7)){
                  
            }else{ 
              console.log('Valid dni');
              let g = resp.datos[0].grupo == null ? 0 : 
                      resp.datos[0].grupo == "" ? 0 : 1 ;

              let p = resp.datos[0].pais == null ? 0 : 
                      resp.datos[0].pais == "" ? 0 : 1 ;

              let r = resp.datos[0].region == null ? 0 : 
                      resp.datos[0].region == "" ? 0 : 1 ;

              if(g == 1){
                $('#grupo').append('<option selected value="'+resp.datos[0].grupo+'">'+resp.datos[0].grupo+'</option>');
              }
              if(p == 1){
                //$('#pais').append('<option selected value="'+resp.datos[0].pais+'">'+resp.datos[0].pais+'</option>');
                //$('#pais').val(resp.datos[0].pais);
                $('#pais').val('');
              }
              /*if(r == 1){
                $('#dpto').append('<option selected value="'+resp.datos[0].region+'">'+resp.datos[0].region+'</option>');
              }*/
              $('#nombres').val(resp.datos[0].nombres);
              $('#ap_paterno').val(resp.datos[0].ap_paterno);
              $('#ap_materno').val(resp.datos[0].ap_materno);
              $('#profesion').val(resp.datos[0].profesion);
              $('#organizacion').val(resp.datos[0].organizacion);
              $('#cargo').val(resp.datos[0].cargo);
              $('#xemail').val(resp.datos[0].email);
              $('#xcelular').val(resp.datos[0].celular);

              // proveedor de dominio
              let cadena_email = resp.datos[0].email;
              let email_usuario = "";
              let email_dominio = "";

              let cad_partes = cadena_email.split('@');
              if(cad_partes.length == 2){
                email_usuario = cad_partes[0];
                email_dominio = "@"+cad_partes[1];
              }

              var index = emails.indexOf(email_dominio);
              //console.log("index : "+index);
              if(index > -1){
                $('#email_dominio').prop('selectedIndex',index);
              }else{
                $('#email_dominio').prop('selectedIndex',0);
              }
              //$('#email_dominio').val(email_dominio);
              //console.log(email_usuario);console.log(email_dominio);

              editEmail.css('display','inline-block');
              editCel.css('display','inline-block');
              email.val(email_usuario).attr('disabled',true);//resp.xdatos.email
              //email_dominio.val(email_dominio).attr('disabled',true);//resp.xdatos.email
              celular.attr('type','text').attr('disabled',true).val(resp.xdatos.celular);

            }
          
          $.each(resp.datos, function(index, data){

            if(data.eventos_id == evento){
              //permitir dni duplicados a los grupos DJ
              if(data.estudiantes_tipo_id != 7){

                // si dni y cod_curso => alert registrado
                swal(
                    "Advertencia", 
                    "Usted se encuentra registrado. Para mayor información envia un correo electronico a: inscripciones@enc.edu.pe", 
                    "warning"
                    );
  
                  clearForm();
                  $('#dni_doc').val('');
                  email.val("");
                  celular.val("");

              }
            }

          });

        }else{
          //console.log("El DNI no esta registrado.");
          clearForm();

          editCel.css('display','none');
          celular.attr('disabled',false).attr('type','number').val('');

          editEmail.css('display','none');
          email.attr('disabled',false).val('');
        }
      });

    });

    function clearForm(){
      console.log('Clean...');
      $('#nombres').val("");
      $('#ap_paterno').val("");
      $('#ap_materno').val("");
      $('#profesion').val("");
      $('#organizacion').val("");
      $('#cargo').val("");
      $('#email').val("");
      $('#email_dominio').val("");
      $('#celular').val("");
      $('#cboDepartamento,#dpto,#distrito,#provincia').val("");
      $('#pais').val("");
      $('#grupo').val("");
      
    }

    // Form: ddjj-inscripcion 
    var $nom_curso=$("#nom_curso");
    $nom_curso.bind("change",function(e){
      var evento = $('#eventos_id').val();
      var val = e.target.value;

      if(!val){
        clearCursos();
        return false;
      }

      console.log('Cursos...');
      
      var url = baseURL('')+"getCurso/"+e.target.value+"/"+evento+"";
      
      $.get(url, function(resp,depa){
        
        if(resp.datos.length > 0){
          
          $('#nom_curso2, #nombre_curso').val(resp.datos[0].nom_curso);
          $('#cod_curso').val(resp.datos[0].cod_curso);
          //$('#mod_curso').val(resp.datos[0].mod_curso);
          $('#fech_ini').val(resp.datos[0].fech_ini);
          $('#fech_fin').val(resp.datos[0].fech_fin);

        }else{
          
          clearCursos();

          swal(
              "Advertencia", 
              "El curso no se encuentra disponibe, por favor elija otro curso", 
              "warning"
              );
        }
      });

    });
    

    function clearCursos(){
      console.log('Clean cursos');
      $('#nombre_curso').val("");
      $('#cod_curso').val("");
      $('#mod_curso').val("");
      $('#fech_ini').val("");
      $('#fech_fin').val("");
    }

    // END FORM CAII

    $('#cboProvincia').on(function (event){
      //console.log("PASO3");
      console.log("ID: "+event.target.value);
      $.get("ubigeo2/"+event.target.value+"",function(resp,depa){
        if(resp.length>0){
          //console.log("valor resp ="+resp.length);
          $('#cboDistrito').empty();
          $("#cboDistrito").append("<option value='0'>SELECCIONE</option>");
          for(var i=0;i<resp.length;i++){
            $("#cboDistrito").append("<option value='"+resp[i].ubigeo_id+"'>"+resp[i].nombre+"</option>");
          }
        }else{console.log("0 Registros.");}
      });
    });

    /* plantillaHTML */
    $('.openHTML').click(function(){
      $('#openHTML').modal('show');
      var id = $(this).data('id');
      var url = baseURL('')+"verHTML/"+id+"";
      console.log('url: '+url);

      $.get(url,function(resp, resul){
        $('#plantillaHTML').empty();
        //console.log(resp);
        $("#plantillaHTML").html(resp.plantillahtml); 
      });
    });

    $('.openHTML_plantilla').click(function(e){
      e.preventDefault();
      $('#openHTML_plantilla').modal('show');
      var id = $(this).data('id');
      var name = $(this).data('name');
      var url = "verHTML/"+name+"/"+id+"";

      $.get(url,function(resp, resul){
        $('#viewHTML').empty();
        console.log(resp.plantillahtml);
        $("#viewHTML").html(resp.plantillahtml); 
      });
    });
    /* show view html MOD GAFETES */
    $('.openHTML_2').click(function(e){
      e.preventDefault();
      $('#openHTML_2').modal('show');
      var id = $(this).data('id');
      var url = baseURL('show')+"/verHTML/"+id+"";
      var base = baseURL('');

      $.get(url,function(resp, resul){
        $('#plantillaHTML').empty();
        let img = '<img src="'+base+'images/g/'+resp.plantillahtml+'.jpg" class="img-fluid" alt="gafete modelo 1">'
        $("#plantillaHTML").html(img); 
        console.log(img);
      });
    });

    // enviar correos a una programación
    $('.pcorreos').click(function(){

      var id_evento = "";
      if ($('.btn-html').is(':checked')) {
        
        $('li input:radio:checked').each(function(){
          var id_plantilla = $(this).val();
          //alert('id plan ' +id_plantilla);
        });

      }else{
        //$('#delete_selec').attr('disabled',true);
        alert('Debe seleccionar una plantilla HTML');
        $('.bloque_plantilla').css('background','#d5ebf3');
      }

    });
    

    // ENVIO RECORDATORIO
    $('#Recordatorio').click(function(e) {
      e.preventDefault();
      var keys = $(this).data('id');
      var modulo = $(this).data('mod');

      console.log('Recordatorio=' +modulo);

      if(!confirm('¿Desea enviar el RECORDATORIO?')) return false;
      //alert(keys);
      var url = "estudiantes/recor/"+keys+"/"+modulo+"";;
      console.log('Recordatorio: ' +url);

      $.get(url,function(resp, resul){
        swal(resp.msg_tit, resp.msg, resp.tipo);
      });

    });

    // CONFIRMAR ELIMINAR
    $('#btnDeleteEvento').click(function(e){
      e.preventDefault();

      if(!confirm('¿Desea eliminar el evento?')) return false;

      $('#formEvento').submit();

    });

    // PROCESAR CORREOS CON BOTON
    $('.eCorreos').click(function() {
      var keys = $(this).data('id');
      console.log('eCorreos=' +keys);
      //alert(keys);
      var url = "plantillaemail/procesaremailsxlote/"+keys+"";

      $.get(url,function(resp, resul){

        if(resp.length > 0){

          //$('#plantillaHTML').empty();
          console.log(resp);
          //$("#plantillaHTML").html(resp.plantillahtml);
          swal("Correos Enviados", "Cantidad de Correos Procesados: "+resp.length+"", "success");

        }else{

          swal("Aviso", "...no existen estudiantes en esta programación.");
          console.log('Menor');

        }
        
      });

    });

    // check eliminar registros
    $('._check').click(function() {
  
        $('#enviarCorreos').attr('disabled',false);
        var id = $(this).data('id');
        //console.log('_check=' +id);
        //$(this).parents('tr').fadeOut(1000);

        if ($('._check').is(':checked')) {
          $("#chek_enviarTodos").prop('checked', false);
        }else{
          $('#enviarCorreos').attr('disabled',true);
          console.log('_check desact');
        }
         //row.fadeOut(1000);
    });

    var $inputdni=$("#inputdni");
    $inputdni.bind("change",function(){
      var pos=this.value;
      var evento = $('#eventos_id').val();
      console.log(pos + ' -------'+evento+'------');
      console.log("Evento: "+ evento);
      console.log(baseURL('estudiantes'));
      //$.get("vdni/"+event.target.value+"",function(resp,depa){
      $.get(baseURL('estudiantes')+"/vdni/"+pos+"/"+evento+"",function(resp,depa){

          $('#dni_doc').val("");
          $('#inputNombres, #nombres').val("");
          $('#ap_paterno, #inputApe_pat').val("");
          $('#ap_materno, #inputApe_mat').val("");
          $('#profesion').val("");
          $('#organizacion').val("");
          $('#cargo').val("");
          $('#email').val("");
          $('#celular').val("");
          $('#cboDepartamento,#dpto').val("");
          $('#pais').val("");
          $('#grupo').val("");

          // E = 0 => no existe / E = 1 => si existe E = 2 => existe y esta en el evento
          if(resp.existe == 0){
            
          }else if(resp.existe == 2){
            swal("Advertencia", "El DNI ya esta registrado en el evento.", "warning");
            $('#inputdni').val("");
          }else{

              $('#grupo').append('<option selected value="'+resp.data.grupo+'">'+resp.data.grupo+'</option>');
              $('#pais').append('<option selected value="'+resp.data.pais+'">'+resp.data.pais+'</option>');
              $('#cboDepartamento,#dpto').append('<option selected value="'+resp.data.region+'">'+resp.data.region+'</option>');
              $('#inputNombres, #nombres').val(resp.data.nombres);
              $('#inputApe_pat, #ap_paterno').val(resp.data.ap_paterno);
              $('#inputApe_mat, #ap_materno').val(resp.data.ap_materno);
              $('#inputProfesion').val(resp.data.profesion);
              $('#inputOrganizacion').val(resp.data.organizacion);
              $('#inputCargo').val(resp.data.cargo);
              $('#inputEmail,#email').val(resp.data.email);
              $('#celular').val(resp.data.celular);
              $('#codigo_cel').append('<option selected value="'+resp.data.codigo_cel+'">'+resp.data.codigo_cel+'</option>');
          }
          
          $('#existe').val(resp.existe);
          console.log('existe: ' +resp.existe);
      
      });

    });
    // validate the comment form when it is submitted
    $("#commentForm").validate({
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
    // validate signup form on keyup and submit
    $("#estudiantesForm").validate({ 
      rules: {
        inputdni: "required",
        //inputNombres: "required",
        inputdni: {
          required: true,
          minlength: 5,
          maxlength: 15
          //maxlength: 8
        },
        inputEmail: {
          //required: true,
          email: true
        }
      },
      messages: {
        inputdni: "Escribir DNI",
        //inputNombres: "Please enter your lastname",
        inputdni: {
          required: "Escribir DNI",
          minlength: "DNI debe tener 5 digitos",
          maxlength: "DNI debe máximo 15 digitos",
        },
        
        email: "Escribir email valido"
      },
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
    
    //------------------------ESTUDIANTES-------------------------//

    $('form#estudiantesImportSave').submit( function( e ) {
        $("#btnSumImport").attr("disabled","disabled");
        var _numC = $("#totCol").val();
        var x;
        var _flag=0;
        var _flagDni=0;
        var _flagCodProg=0;
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() > 0 ){
            _flag = 1;break;
          }
        }
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() == 1 ){
            _flagDni = 1;break;
          }
        }
        /*for(x = 0; x < _numC ; x++){
          // 14 // 7 ES LA POSC DEL ARCHIVO importresults.blade.php
          if($("#cmbOrganizar" + x).val() == 14 ){
            _flagCodProg = 1;break;
          }
        }*/

        if(_flag==0){
            //alert("Por favor asignar al menos una columna");
            swal({ type:'info',title:'Por favor asignar al menos una columna',showConfirmButton: false,timer: 1500});
            $("#btnSumImport").removeAttr("disabled");
            return false;
        }

        if(_flagDni==0){
            //alert("El DNI es un campo obligatorio");
            swal({ type:'info',title:'El DNI es un campo obligatorio',showConfirmButton: false,timer: 2000});
            $("#btnSumImport").removeAttr("disabled");
            return false;
        }
        /*if(_flagCodProg==0){
            swal({ type:'info',title:'El ID EVENTO es un campo obligatorio',showConfirmButton: false,timer: 2000});
            $("#btnSumImport").removeAttr("disabled");
            return false;
        }*/

        $("#cargador_excel2").attr("style","display: block; position: -webkit-sticky;position: sticky;left: 0;");
        e.preventDefault();
        var actionform = $(this).attr('action');
        console.log(actionform);
        // $("#btnGuardar").attr("disabled","disabled");
        $.ajax({
            url: actionform,
            type:'POST',
            data: new FormData( this ),
            processData: false,
            contentType: false,
              beforeSend: function(){
                  //toastr.warning('Procesando su solicitud');
              },
            success: function(respuesta){
                console.log(respuesta);
                $("#cargador_excel2").attr("style","display: none");

                if(respuesta == "error_no_evento"){
                  swal({ type:'info',icon: "error",title:'No existe el EVENTO.',showConfirmButton: false,timer: 1500});
                  $("#btnSumImport").removeAttr("disabled");
                  return false;
                }
                if(respuesta == "error_no_plantilla"){
                  swal({ type:'info',icon: "error",title:'No existe plantilla ni formulario para este evento.',showConfirmButton: false,timer: 1500});
                  $("#btnSumImport").removeAttr("disabled");
                  return false;
                }
                
                
                //swal({ type:'success',title:'Datos cargados',showConfirmButton: false,timer: 1500});
                swal({
                  title: "Registro importado",
                  text: "Los registros fueron importados correctamente.",
                  icon: "success",
                  button: "Cerrar",
                });

                $("#btnSumImport").removeAttr("disabled");
                $("#iframePrev").attr("style","display: block;");
                $("#estudiantesImportSave").attr("style","display: none");
                $("#btnSumImport").attr("style","display: none");
                $("#iframePrev").attr("style","display: none ");
                //sleep(500);
                document.getElementById("iframePrev").contentDocument.location.reload(true);
                console.log('Enviar Invitación');
                $('#btnEInvitacion').addClass('d-block');
                $("#iframePrev").attr("style","display: block;border: 1px solid #e6e6e6;");
                
                //$('#order-listing').DataTable().ajax.reload();

                e.preventDefault();
            },
            error: function(xhr, status, error){
              $("#cargador_excel2").attr("style","display: none");
              var err = JSON.parse(xhr.responseText);           
            }
        });
    });

      $("#btnCerrarIf").click(function(){
        //location.href="/estudiantes";
        eximForm();
      })

      $("#btnRegresar").click(function(){
        _flagMod = 1;
        eximForm();
      })

      $('#Modal_organizar').on('hidden.bs.modal', function () {
        if(_flagMod == 0){
          //location.href="/estudiantes"; 
          cerrar_form();
        }

      });

     $("#archivo").change(function (){
        _flagMod = 0;
        console.log('Archivo seleccionado...');
        $("#f_cargar_datos_estudiantes").submit();
     });

    var tipo_evento = 0;

    $('form#f_cargar_datos_estudiantes').submit( function( event ) {
        tipo_evento = $("#tipo_evento").val();
        if(tipo_evento==2){
          
        }else{
          tipo_evento = 1;
        }
        console.log('tipo:: '+tipo_evento);

        $("#btnImport1").attr("disabled","disabled");
        $("#cargador_excel").attr("style","display:block");
        event.preventDefault();        
    }).validate({
      // Rules for form validation
      errorClass: 'error', 
        rules : {
          archivo: {
            required: true,
            extension: "xls|csv"
          }
        },
        // Messages for form validation
        messages : {
          archivo : {
            required: "Solo se aceptan archivos XLS y CSV"
          }

        },
        submitHandler: function(form) {

          $("#iframePrev").attr("style","display: none ");
          $("#estudiantesImportSave").attr("style","display: block");
          $("#btnSumImport").attr("style","display: block");
                
         // $("#btnImport1").removeAttr("disabled");

          var actionform = $("#f_cargar_datos_estudiantes").attr('action');
          console.log('actionform');
          console.log(actionform);
          //$("#btnGuardar").attr("disabled","disabled");
          $.ajax({
              url: actionform,
              type:'POST',
              data: new FormData( form ),
              processData: false,
              contentType: false,
                beforeSend: function(){
                    //toastr.warning('Procesando su solicitud');
                },
              success: function(datos){
                //console.log(datos);
                $("#hdnTabla").val(datos);
                $("#btnImport1").removeAttr("disabled");    
                $("#cargador_excel").attr("style","display:none");       

                $('#Modal_estudiantes').modal('hide');
                $('#tbl_estudiantes_imp_ord').html("");
                var _combo = "<select class='form-control border-primary text-uppercase' required onchange='cmbOrganiza(this)' id='cambiar' name='cambiar' style='width:200px'>";
                _combo = _combo + "<option value=0></option>";
                _combo = _combo + "<option value=1>DNI / PASAPORTE</option>";
                _combo = _combo + "<option value=2>NOMBRES</option>";
                _combo = _combo + "<option value=3>APELLIDO PATERNO</option>";
                _combo = _combo + "<option value=4>APELLIDO MATERNO</option>";
                _combo = _combo + "<option value=5>GRUPO</option>";
                _combo = _combo + "<option value=17>PAÍS</option>";
                _combo = _combo + "<option value=18>DEPARTAMENTO</option>";
                _combo = _combo + "<option value=7>PROFESIÓN</option>";
                _combo = _combo + "<option value=6>CARGO</option>";
                _combo = _combo + "<option value=16>ENTIDAD / EMPRESA / ORGANIZACIÓN</option>";
                _combo = _combo + "<option value=11>CORREO ELECTRÓNICO PERSONAL</option>";
                _combo = _combo + "<option value=10>CELULAR</option>";
                _combo = _combo + "<option value=9>TELEFONO</option>";
                _combo = _combo + "<option value=15>EMAIL_LABOR</option>";
                if(tipo_evento==2){
                  _combo = _combo + "<option value=19>INSCRITO</option>";
                  _combo = _combo + "<option value=20>ASISTIDO</option>";
                }
                /*SE QUITO*/
                /*_combo = _combo + "<option value=5>fecha_nac</option>";
                _combo = _combo + "<option value=8>direccion</option>";
                _combo = _combo + "<option value=9>telefono</option>";
                _combo = _combo + "<option value=12>sexo</option>";
                //_combo = _combo + "<option value=13>Entidad</option>";
                */
                _combo = _combo + "</select>";

                $("#Modal_organizar").modal('show').addClass('modal-big');

                if(datos.length>0){
                  var _strTable = '<thead><tr role="row">';
                  var _numCol = datos[0].length ;
                  /*if(_numCol>12){
                    alert("El número máximo de columnas es 12, por favor revise su archivo de Excel");
                    return false;
                  }*/
                  $("#totCol").val(_numCol);
                  var x;
                  var xx = 1;
                  for(x = 0; x < _numCol ; x++){
                    var _nnombre = "cmbOrganizar"+ xx;
                    var _num = xx;
                    
                    _combo = _combo.replace("cambiar", _nnombre );
                    _combo = _combo.replace("cambiar", _nnombre );

                    _combo = _combo.replace("cmbOrganizar"+ (xx -1), _nnombre );
                    _combo = _combo.replace("cmbOrganizar"+ (xx -1), _nnombre );

                    _strTable = _strTable + '<th >' + _combo + '</th>';
                    xx++;
                  }
                  _strTable = _strTable + '</tr></thead>';
                  
                  var y;
                  _strTable = _strTable + '<tbody>';
                  for(y=0; y< datos.length; y++){
                    _strTable = _strTable + '<tr>';
                    var z;  
                    for(z=0; z< _numCol; z++){
                      var _clase = "col" + (z+1);
                      _strTable = _strTable + '<td style="background:#E5E5E5;" class="'+_clase+'">' + datos[y][z] + '</td>' ;
                    }
                    _strTable = _strTable + '</tr>';
                  }
                  _strTable = _strTable + '</tbody>';
                  $('#tbl_estudiantes_imp_ord').append(_strTable);

                }
                organizaFirstRow();
                  
              },
              error: function(xhr, status, error){ 
                    var err = JSON.parse(xhr.responseText);
                    //alert(err["error"]);
                    swal(err["error"]);
                    $("#btnImport1").removeAttr("disabled");    
                    $("#cargador_excel").attr("style","display:none");                        

              }
          });
        },
      errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
      }
    });

    // INVITACION
    


    //------------------------ END ESTUDIANTES-------------------------//


    $("#chkPrimeraFila").on('change', function(event){
      organizaFirstRow();
    });


    // propose username by combining first- and lastname
    $("#username").focus(function() {
      var firstname = $("#firstname").val();
      var lastname = $("#lastname").val();
      if (firstname && lastname && !this.value) {
        this.value = firstname + "." + lastname;
      }
    });


  });
})(jQuery);