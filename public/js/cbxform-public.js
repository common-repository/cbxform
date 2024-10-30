function cbxform_size(ref, classname) {
	var element_width = ref.width();

	if (element_width >= 480) {
		//if(ref.hasClass(classname)){
			ref.addClass(classname);
		//}
	}
	else if (element_width < 480) {
		//if(ref.hasClass(classname)){
			ref.removeClass(classname);
		//}
	}
}


jQuery(document).ready(function($) {

	var ref = $('.cbxform');

	$('.cbxform_ajax_icon').hide();

	//validator translation of messages
	$.extend( $.validator.messages, {
		required: cbxform.required,
		remote: cbxform.remote,
		email: cbxform.email,
		url: cbxform.url,
		date: cbxform.date,
		dateISO: cbxform.dateISO,
		number: cbxform.number,
		digits: cbxform.digits,
		creditcard: cbxform.creditcard,
		equalTo: cbxform.equalTo,
		extension: cbxform.extension,
		maxlength: $.validator.format( cbxform.maxlength ),
		minlength: $.validator.format( cbxform.minlength ),
		rangelength: $.validator.format( cbxform.rangelength ),
		range: $.validator.format( cbxform.range ),
		max: $.validator.format( cbxform.max ),
		min: $.validator.format( cbxform.min ),
	});


	$(".cbxform-wrapper").each(function (index, elem) {

		var $formwrapper = $(elem);

		var $element = $formwrapper.find('.cbxform-single');

		var $form_structure = $formwrapper.data('structure');

		//special take care of inline mode if loaded in small content area
		if($form_structure == 'cbxform-inline'){
			cbxform_size($formwrapper, 'cbxform-inline');

			$( window ).resize(function() {
				cbxform_size($formwrapper, 'cbxform-inline');
			});
		}


		//var formvalidator = $element.validate({errorLabelContainer: ".cbxform-messages", wrapper: "p"});
		var formvalidator = $element.validate({
			errorPlacement: function(error, element) {
				error.appendTo( element.parents('.cbxform-item-wrap') );
			},
			errorElement: 'p'
		});

		// prevent double click form submission


		$element.submit(function (e) {
			var $form = $(this);


			var error_msg   = '';
			var success_msg = '';

			if(formvalidator.valid()){
				var googleResponse = $element.find('#g-recaptcha-response').val();
				var grecaptcha_used = ($element.find('#g-recaptcha-response').length)? true: false;

				if (grecaptcha_used && !googleResponse) {
					alert(cbxform.recaptcha);
					return false;
				}

				$element.find('.cbx-actionbutton').prop( "disabled", true );

				//process form by ajax
				if($element.data('ajax') === 'cbxform_ajax'){
					e.preventDefault();

					$element.find('.cbxform_ajax_icon').show();
					var data = $form.serialize();

					// process the form
					var request = $.ajax({
						type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
						url         : cbxform.ajaxurl, // the url where we want to POST
						data        : data+ '&ajax=true', // our data object
						security    : cbxform.nonce,
						dataType    : 'json', // what type of data do we expect back from the server
					});
					request.done(function(data) {
						if($.isEmptyObject(data.error)){
							$element.find('.cbxform_ajax_icon').hide();
							$element.find('.cbx-actionbutton').prop( "disabled", false);
							$.each(data.success, function(key,valueObj){
								if(valueObj.show == true){
									success_msg += '<p class="cbxform-success">'+valueObj.msg+'</p>';
								}
							});
							$formwrapper.find('.cbxform-messages').empty().html(success_msg);
							if(grecaptcha_used) grecaptcha.reset();
							formvalidator.resetForm();
							$element[0].reset();
						}else{
							$.each(data.error, function(key,valueObj){
								 error_msg += '<p class="cbxform-error"><a href="#'+key+'">'+valueObj+'</a></p>';
							});
							$formwrapper.find('.cbxform-messages').empty().html(error_msg);
						}
						$element.find('.cbxform_ajax_icon').hide();
						$element.find('.cbx-actionbutton').prop( "disabled", false);
					});

					request.fail(function(jqXHR, textStatus){
						$element.find('.cbxform_ajax_icon').hide();
					});
				}
			}
		});
	}); //end each form

});
