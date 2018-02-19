var captches = {}

$(document).ready(function(){

	$('form input[data-form], form button[data-form], form input[data-captcha], form button[data-captcha]').click(formSubmiter)

});


function formSubmiter(e)
{
	var $form = $(this).closest('form');
	var formId = $form.attr('id')
	$form.removeClass('was-validated')

	e.preventDefault();
	e.stopPropagation();

	if ($form[0].checkValidity() === false) {
        $form.addClass('was-validated')
        if(typeof captches[formId] === 'undefined') grecaptcha.reset(captches[formId]) 
  	}
  	else
  	{

  			var submit = function()
  			{
  				if(typeof $(this).data('formAjax') != 'undefined' && $(this).data('formAjax') === 0)
	  			{
	  				$form.submit()
	  				if(typeof captches[formId] === 'undefined') grecaptcha.reset(captches[formId]) 
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

						if(typeof captches[formId] === 'undefined') grecaptcha.reset(captches[formId]) 
					})
				}
  			}


  			if($(this).data('captcha') != 'undefined')
  			{
  				if(typeof captches[formId] === 'undefined')
				{
					var widgetId = grecaptcha.render($(this)[0], {
			          sitekey : $(this).data('captcha'),
			          theme : 'light',
			          size : 'invisible',
			          callback : submit.bind(this)
			    	});
				
					captches[formId] = widgetId
					grecaptcha.execute(widgetId)
				}
				else {
					grecaptcha.reset(captches[formId])
				} 
  			}
  			else
  			{
  				submit.call(this)
  			}


  			
  	}

  	


}


