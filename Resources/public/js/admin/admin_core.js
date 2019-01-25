if(typeof(CKEDITOR) != 'undefined')
{
	CKEDITOR.dtd.$removeEmpty.span = 0
	CKEDITOR.dtd.$removeEmpty.i = 0
}

function trans(value)
{
	if(typeof translations[value] != 'undefined')
	{
		return translations[value]
	}
	else value
}

$(document).ready(function () {


	// var hideSeoMetatagsFieldIfRedirectionSet = 

	$('input[id$=admincorebundle_seo_redirectTo]').keyup(function(){
		if($(this).val() === '')
		{
			$('.tab-pane-model-Tags, .tab-pane-model-OpenGraph, .tab-pane-model-Robots').show()
		}
		else {
			$('.tab-pane-model-Tags, .tab-pane-model-OpenGraph, .tab-pane-model-Robots').hide()
		}
	})

	$('input[id$=admincorebundle_seo_redirectTo]').trigger('keyup')


	$('.btn.import-csv').show()

	function admincorebundle_page_type_set()
	{
		switch($(this).val())
		{
			// case 'homepage':
			// 	$('label[for$=_headline]').parent().hide()
			// 	$('.form-model-field-isEnabled').parent().hide()
			// 	$('.form-model-field-post').parent().hide()
			// 	$('.form-model-field-type').parent().hide()
			// 	$('label[for$=_subheadline]').parent().hide()
			// 	$('label[for$=_content]').parent().hide()
			// 	$('label[for$=_buttonLabel]').parent().hide()
			// 	$('label[for$=_parent]').parent().hide()
			// 	$('div[id$=_containerStyles]').parent().hide()
			// 	$('div[id$=_animations]').parent().hide()
			// 	$('label[for$=_color]').parent().hide()
			// break;

			// case 'portfolio':
			// 	$('.form-model-field-isEnabled').parent().show()
			// 	$('.form-model-field-post').parent().show()
			// 	$('.form-model-field-type').parent().show()
			// 	$('label[for$=_headline]').parent().hide()
			// 	$('label[for$=_subheadline]').parent().hide()
			// 	$('label[for$=_content]').parent().hide()
			// 	$('div[id$=_containerStyles]').parent().hide()
			// 	$('div[id$=_animations]').parent().hide()
			// 	$('label[for$=_color]').parent().hide()
			// 	$(this).find('option[value=homepage]').remove();
			// break;

			// default: 
			// 	$(this).find('option[value=homepage]').remove();
			// 	$('.form-model-field-isEnabled').parent().show()
			// 	$('.form-model-field-post').parent().show()
			// 	$('.form-model-field-type').parent().show()
			// 	$('label[for$=_headline]').parent().show()
			// 	$('label[for$=_subheadline]').parent().show()
			// 	$('label[for$=_content]').parent().show()
			// 	$('label[for$=_buttonLabel]').parent().show()
			// 	$('label[for$=_parent]').parent().show()
			// 	$('div[id$=_containerStyles]').parent().show()
			// 	$('div[id$=_animations]').parent().show()
			// 	$('label[for$=_color]').parent().show()
		}
	}

	$('#new_admincorebundle_page_type, #edit_admincorebundle_page_type').change(admincorebundle_page_type_set)
	$('#new_admincorebundle_page_type, #edit_admincorebundle_page_type').trigger('change')


	$('form').attr('novalidate', true);

    $('input[data-type]').keydown(function(e){
        switch($(this).data('type'))
        {
            case 'int':
                if(!(e.keyCode >=48 && e.keyCode <= 57) && e.keyCode != 8 && e.keyCode != 9)
                {
                    return false;
                }
            break;

            case 'decimal':

                if(($(this).val() == '' || $(this).val().indexOf(',') != -1 || $(this).val().indexOf('.') != -1) && (e.keyCode == 190 || e.keyCode == 188))
                {
                    return false;
                }

                if(!(e.keyCode >=48 && e.keyCode <= 57) && e.keyCode != 8 && e.keyCode != 9 && e.keyCode != 190 && e.keyCode != 188)
                {
                    return false;
                }
            break;

            case 'time':

                if(!(e.keyCode >=48 && e.keyCode <= 57) && e.keyCode != 8)
                {
                    return false;
                }

                if($(this).val().length == 1 && e.keyCode != 8)
                {
                    if($(this).val() == 2 && !(e.keyCode >=48 && e.keyCode <= 51))
                    {
                        return false; 
                    }

                    $(this).val($(this).val() + String.fromCharCode(e.keyCode) + ':'); 
                    return false;  
                }

                
                if($(this).val() == '' && !(e.keyCode >=48 && e.keyCode <= 50))
                {
                    return false; 
                }

                if($(this).val().length == 3 && e.keyCode != 8)
                {
                    if(!(e.keyCode >=48 && e.keyCode <= 53))
                    {
                        return false; 
                    }
                }

                if($(this).val().length >= 4 && e.keyCode != 8)
                {
                    var time = $(this).val() + String.fromCharCode(e.keyCode);
                    if (!time.match(/^\s*([01]?\d|2[0-3]):?([0-5]\d)\s*$/)) {
                        return false;  
                    }
                }

            break;

        }
   })


	var $sortColumn = $('th.list-results-fields-header-column-sort')
	if($sortColumn.length && $sortColumn.hasClass('sorting_asc'))
	{

	 	$("table[id^=table-list-]").sortable({
	            	    containerSelector: 'table',
					    itemPath: '> tbody',
					    itemSelector: 'tr',
					    onMousedown: function ($item, _super, event) {
					      if (!event.target.nodeName.match(/^(input|select)$/i)) {
					      	$item.data('sort-start-index',$item.index());	
					        event.preventDefault()
					        return true
					      }

					    },
					 //    onDrag: function ($item, position, _super, event) {
					 //       console.log($item, $item.index());	
					 //       $item.css(position)	
						//    // $item.data('sort',parseInt($item.find('.list-results-fields-row-column-sort').html()))
						//    // $item.data('sort',parseInt($item.find('.list-results-fields-row-column-sort').html()));
						// },
		            	onDrop: function ($item, container, _super, event) {

		            		var interval = $item.index() - $item.data('sort-start-index')
		            		var startIndex = $item.data('sort-start-index');
		            		var currIndex = $item.index();
		            		var newPos = null;
		            		var mod = 1
		            		if($item.index() < $item.data('sort-start-index'))
		            		{
		            			startIndex = $item.index();
		            			currIndex = $item.data('sort-start-index');
		            			mod = -1	
		            		} 

						 	$item.removeClass("dragged").removeAttr("style")
      						$("body").removeClass("dragging")

      						$('table > tbody > tr').slice(startIndex, currIndex + 1).each(function(i){
		            			
		            			$resortItem = $(this).find('.list-results-fields-row-column-sort');
		            			if($(this).index() == $item.index())
		            			{	
		            				newPos = parseInt($resortItem.html()) + interval;
		            				$resortItem.html(newPos)
		            			} 
		            			else $resortItem.html(parseInt($resortItem.html()) - mod)
		            		})

		            		if(newPos)
		            		{
		            			$.get($item.find('.list-results-fields-row-objectactions .fa-edit').parent().attr('href').replace(/\/[\w]+$/, '/reorder?sort='+newPos), function(jdata){
		            				if(jdata.result != 'success')
		            				{	
		            					bootbox.dialog({
		            						message: 'Wystąpił błąd i nastąpi przełądowanie strony.',
											onEscape: false,
											locale: 'pl',
											buttons: {
											  	OK: {
													label: 'OK',
													callback: function() {
														location.reload()
													}
												}
											}
		            					})
		            					
		            				}
		            			}, 'json');
		            		}
						},
						placeholder: '<tr class="row placeholder"/></tr>'
		            })
	}
	else if($sortColumn.length)
	{
		$('.flash-inner').append('<div class="alert alert-dismissable alert-warning fade in"><i class="fa fa-info-circle"></i> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Aby uaktywnić opcję sortowania tabela musi być posortowana wg kolumny pozycja w kolejności rosnącej.</div>')
	}


	$('#CustomFilter select[data-option]').change(function(){
		if($(this).data('nointeract') != 'yes')
		{
			$.get(sf_env+'/admin/menu-item/options?option='+$(this).data('option'), $('#CustomFilter').serialize(), function(jdata){
					for (var option in jdata)
					{
						var $select = $('#CustomFilter select[data-option='+option+']');
						$select.html('')
						for (var i in jdata[option])
						{
							$select.append('<option value="'+( jdata[option][i].value ? jdata[option][i].value : '' )+'">'+( jdata[option][i].key ? jdata[option][i].key : '' )+'</option>')
						}
					}
			}, 'json')
		}
	})


	// $('select').select2();

	// if($('.form-model-page').length)
	// {
	// 	if($('select[id$=_admincorebundle_page_type]').val() == 'homepage')
	// 	{
	// 		$('textarea[id*=_admincorebundle_page_translations_]').parent().hide()		
	// 		$('select[id$=_admincorebundle_page_parent]').parent().hide()
	// 	}
	// 	else if($('select[id$=_admincorebundle_page_type]').val() == 'team')
	// 	{
	// 		$('textarea[id*=_admincorebundle_page_translations_]').parent().hide()		
	// 	}
	// 	else if($('select[id$=_admincorebundle_page_type]').val() != 'contact')
	// 	{
	// 		$('select[id$=_admincorebundle_page_parent] option:first-child').remove();
	// 		$('select[id$=_admincorebundle_page_type]').val('rightsidebar')
	// 	}
	// }

	$('.a2lix_translations.tabbable > .nav-tabs').each(function(){
		if($(this).find('li').length < 2)
		{
			$(this).css('border-bottom', 'none');
			$(this).parent().parent().children('label').hide();
		}
	})


	
    $('div[data-prototype]').each(function(){
    	$(this).admin_core_initCollection();
    })


	   $('input.detetimepicker').admin_core_addCalendarBtn();
       $('input.detetimepicker').admin_core_addRemoveBtn(); 	

       $('.colorpicker').admin_core_colorpicker();
    
		if(typeof(window['sortable']) == 'function')
		{     
		$(".sort").sortable({
            	onDragStart: function (item, container, _super) {
    // Duplicate items of the no drop area
    if(!container.options.drop)
      item.clone().insertAfter(item)
    _super(item)
  }
            })

	}

	$('input[id*=Url], input[id*=url], td.list-results-fields-row-column-url').admin_core_checkUrl();		


	 
      var transaltionErrors = $('.a2lix_translations .has-error, .a2lix_translations .help-block .list-unstyled')
      
   
      if(transaltionErrors.length)
      {

      	$('.a2lix_translations .nav-tabs li:nth-child('+(transaltionErrors.first().closest('.tab-pane').index() + 1)+') > a').trigger('click')
      }
	   
});





(function ( $ ) {

	var CollectionHolders = {};

	$.fn.admin_core_initCollection = function () {
			
			// CollectionHolders[$(this).parent().attr('id')] = $(this).parent();
			
			var $children = $(this).find('> div');

			

			$(this).data('index', $children.length);
      $children.each(function(){
				
				// if($(this).find('div.form-group').length == 0) $container = $(this)
				// else $container = 

				$container = $(this).find(' > div')

        $container.find('input[type=hidden][id$=_id]').each(function(){
          console.log($(this).val())
          $container.find('input[data-ref]').data('ref',$(this).val())
        })

				$container.append($('<div class="text-right"><a href="#" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span> ' + trans('action.object.delete.label') + ' </a><div>').on('click', function(e){
					e.preventDefault();
					var container = $(this).parent().parent().parent();
					$(this).parent().parent().remove()
					var sort = container.find('input[id$=_sort]')
					if(sort.length)
					{
						sort.each(function(index){
				  	  		$(this).val(index+1)
				  	 	})
					}
				}))
			})

			if($(this).find('input[id$=_sort]').length)
			{
				$(this).children('div').addClass('sortable');

				$(this).sortable({
		          containerSelector: '#' + $(this).attr('id'),
				  itemSelector: 'div.sortable',
				  onDrop: function ($item, container, _super, event) {
				  	  $item.parent().find('input[id$=_sort]').each(function(index){
				  	  	$(this).val(index+1)
				  	  })
					  $item.removeClass(container.group.options.draggedClass).removeAttr("style")
					  $("body").removeClass(container.group.options.bodyClass)
					}
		       })


				 $(this).addClass('sortable-initialized');

			}

			

		      

		   



			$(this).parent().append(
				$('<a href="#" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span> ' + trans('action.object.add.label') + '</a>').on('click', function(e) {
		        	e.preventDefault();
		        	$(this).admin_core_addCollection();
    			})
    		);


	}
	
	$.fn.admin_core_addCollection = function () {
			

			var $container = $(this).parent().find('> div[data-prototype]')

			var index = $container.data('index')
			var $prototype = $($container.data('prototype').replace(/__name__/g, index));
			
			var $rmContainer = ($prototype.find('> div').length ? $prototype.find('> div') : $prototype)
			$rmContainer.append($('<div class="text-right"><a href="#" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> ' + trans('action.object.delete.label') + '</a><div>').on('click', function(e){
					e.preventDefault();
					var container = $(this).parent().parent().parent();
					$(this).parent().parent().remove()
					var sort = container.find('input[id$=_sort]')
					if(sort.length)
					{
						sort.each(function(index){
				  	  		$(this).val(index+1)
				  	 	})
					}
					
				}))

			var sort = $prototype.find('input[id$=_sort]');
			if(sort.length)
			{
				sort.val($container.find('input[id$=_sort]').length + 1)
				$prototype.addClass('sortable')

			}

      
      $prototype.find('div[data-prototype]').each(function(){
        $(this).admin_core_initCollection();
      })

			$container.append($prototype);
			$container.data('index', index + 1)

      var eventAddCollection = new Event('collection_add');
      $container.children().last()[0].dispatchEvent(eventAddCollection)
			

			if(!$container.hasClass('sortable-initialized') && sort.length)
			{
				$container.sortable({
		          containerSelector: '#' + $container.attr('id'),
				  itemSelector: 'div.sortable',
				  onDrop: function ($item, container, _super, event) {
				  	  $item.parent().find('input[id$=_sort]').each(function(index){
				  	  	$(this).val(index+1)
				  	  })
					  $item.removeClass(container.group.options.draggedClass).removeAttr("style")
					  $("body").removeClass(container.group.options.bodyClass)
					}
		       })


			    $container.addClass('sortable-initialized');
			}

			$container.find('.colorpicker').admin_core_colorpicker()
	}

	$.fn.admin_core_addRemoveBtn = function() {

		var rmBtn = $('<span class="input-group-addon add-on"><span class="glyphicon glyphicon-remove"></span></span>');
		rmBtn.click(function(e){
			e.stopPropagation();
			$(this).parent().find('input').val('');
		})
		this.parent().prepend(rmBtn);

	};

	$.fn.admin_core_addCalendarBtn = function() {


		// if(typeof(window['datetimepicker']) == 'function')
		// {

			var calBtn = $('<span class="input-group-addon add-on calendar"><span class="glyphicon-calendar glyphicon"></span></span>').click(function(){
				$(this).parent().find('input').focus()
			});
			this.data('dateFormat', $(this).data('date-format'));
			this.parent().append(calBtn);
			this.parent().addClass('input-group');
			this.parent().css('max-width', '180px');

			this.parent().find('span.error').each(function(){
				var parent = $(this).parent().parent();
				$(this).detach().prependTo(parent);
			});

			var label = this.parent().find('label').each(function(){
				var parent = $(this).parent().parent();
				$(this).detach().prependTo(parent);
			});



			this.click(function(){
				$(this).parent().find('.calendar').trigger('click')
			})

			this.parent().datetimepicker({
	        	useCurrent: false,
	        	format: 'dd.MM.YYYY'
	        });
	    // }

	};

	$.fn.admin_core_showDialogForm = function() {

		bootbox.dialog({
			  message: '<center style="padding: 120px 0;"><img src="/bundles/paraboladmincore/images/preloaders/loading-spin-dark.svg" ></center>',
			  onEscape: true,
			  locale: 'pl',
			  buttons: {
			  	cancel: {
					label: 'Cancel',
				},
				save: {
				    label: 'Save',
				    className: 'btn-success',
				    callback: function() {
				    	$.post($form.attr('action'), $form.serialize(), function(data){
				    		$('.bootbox-body').html(data);
				    		$('.bootbox-body .colorpicker').admin_core_colorpicker();
				    		if($('.bootbox-body .flash-container .alert-success').length)
				    		{
				    			window.setTimeout(function(){
							    	bootbox.hideAll();
								}, 1000);	
				    		}
				    		
				    	})
				    	return false;
				    }
				}
			  }
		})
		$.get($(this).data('dialog-form'), $(this).data(), function(data){
			$('.bootbox-body').html(data)
			$('.bootbox-body .colorpicker').admin_core_colorpicker();
			// console.log($('.bootbox-body input[id*=url]'));
			$('.bootbox-body input[id*=url]').admin_core_checkUrl();
		})
		
		return false;
	}
	
	$.fn.admin_core_colorpicker = function() {

		if(typeof(window['ColorPickerSliders']) != 'undefined')
		{

			var $input = $(this);
			 $input.ColorPickerSliders({
				color: 'rgba(0,0,0,0)',
		       	placement: 'auto right',
		       	previewformat: 'hex',
		       	grouping: false,
		        flat: false,
		        sliders: true,
		        swatches: colorspicker.swatches,
		        hsvpanel: false,
		        order: {
		            cie: 1, opacity: 2
		        },
		        labels: {
		        	cielightness: 'Brightness',
		        	ciechroma: 'Saturation',
		        	ciehue: 'Hue',
		        	opacity: 'Opacity'
	        	},
	        	onchange: function(container, color) 
	        	{
	        		if(color.rgba.r == 0 && color.rgba.g == 0 && color.rgba.b == 0 && color.rgba.a == 0)
	        		{
	        			$input.val('')
	        		}        		
	        	}

	    	});

	    	if($input.val() == 'rgba(0, 0, 0, 0)')
	    	{
	    		$input.val('')
	    	}
	    }
	}

	$.fn.admin_core_checkUrl = function() {

		return false;

		// var ts_inputUrl;
		// var req_Url = {};

		// var deleyedCheckUrlInput = function(e)
		// {
		// 	if(ts_inputUrl) clearTimeout(ts_inputUrl)

		// 	var $this = $(this);
		// 	ts_inputUrl = setTimeout(function(){
		// 		$this.trigger('filled');
		// 	}, 700)
			
		// }

		// var checkUrlInput = function(e)
		// {
		// 	if($(this).val() != '' && $(this).data('value') != $(this).val())
		// 	{
		// 		var $input;
		// 		if($(this).parent().find('button[data-type=url]').length == 0)
		// 		{
		// 			$input = $(this).clone();	
		// 			$input.blur(checkUrlInput);
		// 			$input.keyup(deleyedCheckUrlInput);
		// 			$input.on('filled', checkUrlInput);
		// 			$(this).replaceWith($('<div class="input-group"><span class="input-group-btn"><button data-type="url" class="btn btn-flat btn-default" type="button"><i class="fa fa-refresh fa-spin"></i></button></span></div>').prepend($input))

		// 			//<span class="input-group-addon"><i class="fa fa-refresh fa-spin"></i></span>
		// 		}
		// 		else
		// 		{
		// 			$input = $(this);
		// 			$input.parent().find('button').removeClass().addClass('btn btn-flat btn-default');
		// 			$input.parent().find('i.fa').removeClass().addClass('fa fa-refresh fa-spin');
		// 		}

		// 		$input.data('value', $input.val());
		// 		if(req_Url[$input.attr('id')] != undefined)
		// 		{
		// 			req_Url[$input.attr('id')].abort()	
		// 		} 
		// 		req_Url[$input.attr('id')] = $.ajax({
		// 			type: 'POST',
		// 			url: sf_env+'/admin/check-url', 
		// 			data: {url: $input.val(), id: $input.attr('id')}, 
		// 			dataType: 'json',
		// 			async: false,
		// 			success: function(jdata){					
		// 				$input = $('#'+jdata.id);


		// 				if(jdata.result)
		// 				{
		// 					$input.parent().find('button').removeClass().addClass('btn btn-flat btn-success');
		// 					$input.parent().find('i.fa').removeClass().addClass('fa fa-check');
		// 				}
		// 				else
		// 				{
		// 					$input.parent().find('button').removeClass().addClass('btn btn-flat btn-danger');
		// 					$input.parent().find('i.fa').removeClass().addClass('fa fa-close');
		// 				}
		// 			}
		// 		})
		// 	}
		// 	else
		// 	{
		// 		$(this).keyup(deleyedCheckUrlInput);
		// 		$(this).blur(checkUrlInput);
		// 	}
		// }

		// var checkUrlTd = function(e)
		// {
		// 	var $this = $(this)
		// 	$this.find('small').remove()
		// 	var url = $this.html().replace(/^[\s]+/g, '');

		// 	$this.append('<small style="margin-left: 5px;"> <i class="fa fa-refresh fa-spin"></i></small>')


					
		// 	 $.ajax({
		// 			  type: 'POST',
		// 			  url: sf_env+'/admin/check-url',
		// 			  data: {url: url, id: null},
		// 			  success: function(jdata){
		// 			  	if(jdata.result)
		// 				{
		// 					$this.find('small').addClass('label btn-success').html('ok')
		// 					$this.find('i').remove()
		// 				}
		// 				else
		// 				{
		// 					$this.find('small').addClass('label btn-danger').html('zły')
		// 					$this.find('i').remove()
		// 				}
		// 			  },
		// 			  dataType: 'json',
		// 			  async:false
		// 			});	

		// }

		// if(typeof $(this).first() != 'undefined')
		// {
		// 	if($(this)[0].localName == 'td')
		// 	{
		// 		$(this).on('filled', checkUrlTd)
		// 		var $btn = $('<a class="generic-action btn btn-default" href="/app_dev.php/admin/menu-item/new"><i class="fa fa-fw fa-binoculars"></i> Sprawdź adresy</a>');
		// 		$btn.click(function(e){
		// 			e.preventDefault()
		// 			var $td = $('td.list-results-fields-row-column-url');
					
		// 			$td.trigger('filled');
		// 		})
		// 		$('.list-results-actions-generic').prepend($btn)
				
				
		// 	}	
		// 	else
		// 	{
		// 		$(this).on('filled', checkUrlInput);
		// 		$(this).trigger('filled');
		// 	}
		// }
		
	}



}( jQuery ));	


