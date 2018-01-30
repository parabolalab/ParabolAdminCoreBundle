$(document).ready(function(){

	$('form input[data-form], form button[data-form]').click(formSubmiter)

});

function formSubmiter(e)
{
	var $form = $('#'+$(this).data('form'));
	$form.removeClass('was-validated')

	e.preventDefault();
	e.stopPropagation();

	if ($form[0].checkValidity() === false) {
        $form.addClass('was-validated')
  	}
  	else
  	{

  			if(typeof $(this).data('formAjax') != 'undefined' && $(this).data('formAjax') === 0)
  			{
  				$form.submit()
  			}
  			else
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

  	


}


