jQuery.extend(jQuery.validator.messages, {
	required: "Este campo &eacute; requerido.",
	remote: "Por favor, corrija este campo.",
	email: "Por favor, forne&ccedil;a um endere&ccedil;o eletr&ocirc;nico v&aacute;lido.",
	url: "Por favor, forne&ccedil;a uma URL v&aacute;lida.",
	date: "Por favor, forne&ccedil;a uma data v&aacute;lida.",
	dateISO: "Por favor, forne&ccedil;a uma data v&aacute;lida (ISO).",
	number: "Por favor, forne&ccedil;a um n&uacute;mero v&aacute;lido.",
	digits: "Por favor, forne&ccedil;a somente d&iacute;gitos.",
	creditcard: "Por favor, forne&ccedil;a um cart&atilde;o de cr&eacute;dito v&aacute;lido.",
	equalTo: "Por favor, forne&ccedil;a o mesmo valor novamente.",
	accept: "Por favor, forne&ccedil;a um valor com uma extens&atilde;o v&aacute;lida.",
	maxlength: jQuery.validator.format("Por favor, forne&ccedil;a n&atilde;o mais que {0} caracteres."),
	minlength: jQuery.validator.format("Por favor, forne&ccedil;a ao menos {0} caracteres."),
	rangelength: jQuery.validator.format("Por favor, forne&ccedil;a um valor entre {0} e {1} caracteres de comprimento."),
	range: jQuery.validator.format("Por favor, forne&ccedil;a um valor entre {0} e {1}."),
	max: jQuery.validator.format("Por favor, forne&ccedil;a um valor menor ou igual a {0}."),
	min: jQuery.validator.format("Por favor, forne&ccedil;a um valor maior ou igual a {0}.")
});

$(document).ready(function(){
	$("#btnLogin").click(function(){
	    //Obtenemos variables
		var usuario = $("#lg_nome").val();
		var senha = $("#lg_senha").val();
		var dataString = "usuario="+usuario+"&senha="+senha;
		if(usuario =='' || senha==''){
			return;
		}

		$.ajax({
			async: true,
			dataType: "html",
			type: "POST",
			contentType: "application/x-www-form-urlencoded",
			url: url+"/autentifica/login-data",
			data: dataString,
			beforeSend: function(data){
				$("#msjLogin").html("<label>Carregando...</label>");
			},
			success: function(requestData){
				if(requestData == 1)
					location.reload();
				else	
					$("#msjLogin").html("<label>"+requestData+"</label>");
			},
			error: function(requestData, strError, strTipoError){
				alert("Error "+strTipoError + ":" + strError);
			},
			complete: function (requestData, exito){}
		});
		return false;
	});
});

(function($) {
	"use strict";

	  var options = {
		  'btn-loading': '<i class="fa fa-spinner fa-pulse"></i>',
		  'btn-success': '<i class="fa fa-check"></i>',
		  'btn-error': '<i class="fa fa-remove"></i>',
		  'msg-success': 'Logando',
		  'msg-error': 'Senha incorreta!',
	  };
	  
	  $("#login-form").validate({
		rules: {
		  lg_nome: "required",
		  lg_senha: "required",
		},
		errorClass: "form-invalid"
	  });
	  
	  $("#login-form").submit(function() {
		remove_loading($(this));
	  });
	  
	  $("#se-form").validate({
		  rules: {
			  se_cnpj: "required",
		      se_cnpj: "cnpj"
		  },
		  errorClass: "form-invalid"
	  });
	  
	  $("#se-form").submit(function() {
		  remove_loading($(this));
	  });

	  $("#forgot-password-form").validate({
		rules: {
		  fp_nome: "required",
		},
		errorClass: "form-invalid"
	  });
	  
	  $("#register-form").validate({
		rules: {
		  reg_nome: "required",
		  reg_senha: {
				required: true
			},
			reg_senha_confirm: {
				required: true,
				equalTo: "#register-form [name=reg_senha]"
			}
		},
		  errorClass: "form-invalid",
		  errorPlacement: function( label, element ) {
			if( element.attr( "type" ) === "checkbox" || element.attr( "type" ) === "radio" ) {
				element.parent().append( label );
			} else {
				label.insertAfter( element );
		    }
		 }
	  });
	  
	  $("#login-form").submit(function() {
		remove_loading($(this));
	  });

	  
	  $("#forgot-password-form").submit(function() {
		remove_loading($(this));
	  });

	  function remove_loading($form){
		$form.find('[type=submit]').removeClass('error success');
		$form.find('.login-form-main-message').removeClass('show error success').html('');
	  }

	  function form_loading($form){
		$form.find('[type=submit]').addClass('clicked').html(options['btn-loading']);
	  }
	  
	  function form_success($form){
		  $form.find('[type=submit]').addClass('success').html(options['btn-success']);
		  $form.find('.login-form-main-message').addClass('show success').html(options['msg-success']);
	  }

	  function form_failed($form){
		$form.find('[type=submit]').addClass('error').html(options['btn-error']);
		$form.find('.login-form-main-message').addClass('show error').html(options['msg-error']);
	  }

})(jQuery);

jQuery.validator.addMethod("cnpj", function(str, element) {
	str = str.replace('.','');
	str = str.replace('.','');
	str = str.replace('.','');
	str = str.replace('-','');
	str = str.replace('/','');
	str = str.replace('-','');
	cnpj = str;


	var numeros, digitos, soma, resultado, pos, tamanho,
		digitos_iguais = true;

	if (cnpj.length < 14 && cnpj.length > 15)
		return false;

	for (var i = 0; i < cnpj.length - 1; i++)
		if (cnpj.charAt(i) != cnpj.charAt(i + 1)) {
			digitos_iguais = false;
			break;
		}

	if (!digitos_iguais) {
		tamanho = cnpj.length - 2
		numeros = cnpj.substring(0,tamanho);
		digitos = cnpj.substring(tamanho);
		soma = 0;
		pos = tamanho - 7;

		for (i = tamanho; i >= 1; i--) {
			soma += numeros.charAt(tamanho - i) * pos--;
			if (pos < 2)
				pos = 9;
		}

		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;

		if (resultado != digitos.charAt(0))
			return false;

		tamanho = tamanho + 1;
		numeros = cnpj.substring(0,tamanho);
		soma = 0;
		pos = tamanho - 7;

		for (i = tamanho; i >= 1; i--) {
			soma += numeros.charAt(tamanho - i) * pos--;
			if (pos < 2)
				pos = 9;
		}

		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;

		if (resultado != digitos.charAt(1))
			return false;

		return true;
	}

	return false;
}, "Informe um CNPJ v&aacute;lido.");
