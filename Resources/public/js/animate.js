(function() {
	var elem = [];
	this.Animate = function()
    {
      // console.log('Animate created');
    	var self = this
    	self.animatedElements;
    	$('[data-animated]').each(function(){
    			  self.add($(this));
        })
        $(document).scroll(function(){
              self.doAnimation($(this))
        })
    },
    prefix = function() {
		var styles = window.getComputedStyle(document.documentElement, ''),
	    pre = (Array.prototype.slice
	      .call(styles)
	      .join('') 
	      .match(/-(moz|webkit|ms)-/) || (styles.OLink === '' && ['', 'o'])
	    )[1],
	    dom = ('WebKit|Moz|MS|O').match(new RegExp('(' + pre + ')', 'i'))[1];
	  return {
	    dom: dom,
	    lowercase: pre,
	    css: '-' + pre + '-',
	    js: pre[0].toUpperCase() + pre.substr(1)
	  };
	},
    Animate.prototype.init = function() {
      // console.log('Animate initialized');
    	this.doAnimation($('html, body'))
  	},
  	Animate.prototype.add = function($elem) {
    	elem.push({ element: $elem, pos: $elem.offset().top })
      // console.log(elem);
    	$elem.hide();
  	},
  	Animate.prototype.doAnimation = function($container)
  	{
  		animatedToSlice = [];

  		for(var i in elem)
        {
         	    // console.log('animate', elem[i].pos , $container.scrollTop(), $(window).height())
             if(elem[i].pos > $container.scrollTop() && elem[i].pos < $container.scrollTop() + $(window).height())
             {
              // console.log('!!!!anim');
             	// console.log(elem[i].element.text(), elem[i], $('html, body').scrollTop() + $(window).height());
                elem[i].element.css(prefix().css + 'animation-delay', typeof(elem[i].element.data('animated-delay')) == 'undefined' ? '0.5s' : elem[i].element.data('animated-delay') ).show().addClass('animated ' + elem[i].element.data('animated'))
                animatedToSlice.push(i)
             }
        }
        for(var i in animatedToSlice)
        {
         	elem.splice(animatedToSlice[i] - i,1)
        } 
  	}
  	
}());