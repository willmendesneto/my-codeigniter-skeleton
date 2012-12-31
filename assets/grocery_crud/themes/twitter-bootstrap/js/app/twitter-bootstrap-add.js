$(function(){

		var save_and_close = false;

		//	Salva as informações e retorna a listagem inicial
		$('#save-and-go-back-button').click(function(){
			save_and_close = true;

			$('#crudForm').trigger('submit');
		});

		//	Submete o formulário para inserir os dados no BD
		$('#crudForm').submit(function(){
			$(this).ajaxSubmit({
				url: validation_url,
				dataType: 'json',
				cache: 'false',
				beforeSend: function(){
					$("#FormLoading").show();
				},
				success: function(data){
					$("#FormLoading").hide();
					if(data.success)
					{
						$('#crudForm').ajaxSubmit({
							dataType: 'text',
							cache: 'false',
							beforeSend: function(){
								$("#FormLoading").show();
							},
							success: function(result){
								$("#FormLoading").fadeOut("slow");
								data = $.parseJSON( result );
								if(data.success)
								{
									if(save_and_close)
									{
										window.location = data.success_list_url;
										return true;
									}

									$('.form-input-box').each(function(){
										$(this).removeClass('error');
									});
									clearForm();
									//success_message(data.success_message);
									//
									alert_message('success', data.success_message);
								}
								else
								{
									//alert( message_insert_error );
									alert_message('error', message_insert_error);
								}
							},
							error: function(){
								//alert( message_insert_error );
								//
								alert_message('error', message_insert_error);
								$("#FormLoading").hide();
							}
						});
					}
					else
					{
						$('.form-input-box').removeClass('error');
						//error_message(data.error_message);

						alert_message('error', data.error_message);

						$.each(data.error_fields, function(index,value){
							$('input[name='+index+']').addClass('error');
						});

					}
				},
				error: function(){
					//error_message (message_insert_error);
					alert_message('error', message_insert_error);

					$("#FormLoading").hide();
				}
			});
			return false;
		});
	});

	//	Mensagens para a aplicação
	var alert_message = function(type_message, text_message){
		$('.alert-'+type_message).remove();
		$('#crudForm').prepend('<div class="alert alert-'+type_message+'"><a class="close" data-dismiss="alert" href="#"> x </a>'+text_message+'</div>');
		$('html, body').animate({
			scrollTop:0
		}, 600);
		window.setTimeout( function(){
	        $('.alert-'+type_message).slideUp();
	    }, 7000);
		return false;
	};

	//	Retornar para a tabela de listagem de dados inicial
	function goToList()
	{
		if( confirm( message_alert_add_form ) )
			window.location = list_url;

		return false;
	}
	//	Simula o efeito RESET no formulário de inserção de conteudo
	function clearForm()
	{
		$('#crudForm').find(':input').each(function() {
	        switch(this.type) {
	            case 'password':
	            case 'select-multiple':
	            case 'select-one':
	            case 'text':
	            case 'textarea':
	                $(this).val('');
	                break;
	            case 'checkbox':
	            case 'radio':
	                this.checked = false;
	        }
	    });

		/* Clear upload inputs  */
		$('.open-file, .gc-file-upload, .hidden-upload-input').each(function(){
			$(this).val('');
		});

		$('.upload-success-url').hide();
		$('.fileinput-button').fadeIn("normal");
		/* -------------------- */

		$('.remove-all').each(function(){
			$(this).trigger('click');
		});

		$('.chosen-multiple-select, .chosen-select, .ajax-chosen-select').each(function(){
			$(this).trigger("liszt:updated");
		});
	}