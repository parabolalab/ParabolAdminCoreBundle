$(document).ready(function(){


	$('form input[data-form], form button[data-form]').click(formSubmiter)

	

});

function formSubmiter(e)
	{
		e.preventDefault();
		var hasError = false;
		var $form = $('#'+$(this).data('form'));
		$form.find('.has-error').removeClass('has-error');
		$form.find('.list-unstyled').remove();
		$form.find('input, textarea').each(function(){

			if($(this).hasClass('required'))
			{
				if($(this).attr('type') == 'checkbox' && !$(this).is(':checked') || $(this).attr('type') == 'radio' && !$(this).is(':checked') || $(this).val() == '' || $(this).attr('type') == 'email' && !validateEmail($(this).val()))
				{
					$(this).parent().addClass('has-error')	
					hasError = true;
				}
				

			}
		});

		if(!hasError)
		{
			$form.addClass('sending');
			var $preloader = $form.find('.preloader')
			if($preloader.length) $preloader.show();
			$.post($form.attr('action'), $form.serialize(), function(data){
				$form.removeClass('sending');
				if($preloader.length) $preloader.hide();
				// if($(data).find('.msg-success').length)
				// {
				//  	//bootbox.alert("Dziekujemy za wysłąnie formularza"); 
				//  	$parent.html(data);	
				// }
				// else
				{
					 var $parent = $form.parent()
					 $parent.html(data);
					 $parent.find('.btn-primary').click(formSubmiter)

				}
			})
			
		} 
	}


function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}