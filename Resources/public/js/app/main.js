var captches = {}

$(document).ready(function(){

	$('input[data-form], button[data-form], form input[data-captcha], form button[data-captcha]').click(formSubmiter)

});


function formSubmiter(e)
{

  
	var $form = $(this).data('form') ? $('#' + $(this).data('form')) :  $(this).closest('form');
	var formId = $form.attr('id')
	$form.removeClass('was-validated')

	e.preventDefault();
	e.stopPropagation();

	if ($form[0].checkValidity() === false) {
        // console.log('form errors')
        $form.addClass('was-validated')
        if(typeof grecaptcha !== 'undefined' && typeof captches[formId] === 'undefined') grecaptcha.reset(captches[formId]) 
  	}
  	else
  	{

  			var submit = function()
  			{
          // console.log('submit?');
          if(typeof $(this).data('formAjax') === 'undefined' || $(this).data('formAjax') === 0)
	  			{
            // console.log('submit');
	  				$form.submit()
	  				if(typeof grecaptcha !== 'undefined' && typeof captches[formId] === 'undefined') grecaptcha.reset(captches[formId]) 
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
            if($('#' + $form.attr('id') + '-feedback').length)
            {
                $form.hide();
                $('#' + $form.attr('id') + '-feedback .feedback-body').html(data)
                $('#' + $form.attr('id') + '-feedback').show()
            }
						else
						{
							 var $parent = $form.parent()
							 $parent.html(data);
							 $parent.find('.btn-primary').click(formSubmiter)
						}

						if(typeof grecaptcha !== 'undefined' && typeof captches[formId] === 'undefined') grecaptcha.reset(captches[formId]) 
					})
				}
  			}


  			if(typeof $(this).data('captcha') !== 'undefined')
  			{
  				if(typeof grecaptcha !== 'undefined' && typeof captches[formId] === 'undefined')
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
  				else if(typeof grecaptcha !== 'undefined') {
  					grecaptcha.reset(captches[formId])
  				} 
  			}
  			else
  			{
  				submit.call(this)
  			}


  			
  	}

  	


}


