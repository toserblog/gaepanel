jQuery(document).ready(function($) {

	$('.tab-content, .shortcode-thumbnails').hide();
	$('#landing-shortcode').show();
	$('#shortcode-list a').click(function(e) {
		e.preventDefault();
  		var target = $(this).parent().attr('id');
		$('#shortcode-list li').removeClass('active');
		$(this).parent().addClass('active');
		$('.tab-content, .shortcode-thumbnails').hide();
		$('#'+target+'-shortcode, .'+target+'-thumbnails').fadeIn();
	});	
	
	$('.show-thumbnails a').click(function(e) {
		e.preventDefault();
  		var target = $(this).attr('href');
		$(this).toggleClass('active');
		$(target).slideToggle('fast');
	});	
	
	$('.thickbox_group').hide();
	$('.thickbox_group.image').show();
	$('.thickbox_group.image li').each( function() {
		var targetId = $(this).children().attr('id');
		$(this).children().attr('id','thickbox_'+targetId).attr('name',targetId);
	});
	$('.thickbox_group:not(.image) li').each( function() {
		$(this).children().removeClass('attribute');
	});
	$('#thickbox_type').change(function() {
		var target = $(this).val();
		$('.thickbox_group:not(.'+target+')').hide();
		$('.thickbox_group.'+target).fadeIn();
		$('.thickbox_group.'+target+' li').each( function() {
			var targetId = $(this).children().attr('id');
			if(targetId == 'undefined') return;
			$(this).children().attr('id','thickbox_'+targetId).attr('name',targetId);
			if(targetId != 'content') $(this).children().addClass('attribute');
		});
		$('.thickbox_group:not(.'+target+') li').each( function() {
			var targetId = $(this).children().attr('id').split('_')[1];
			$(this).children().attr('name','').removeClass('attribute').attr('id',targetId);
		});
	});	
	
	$('.code').click(function() { 
		document.getElementById($(this).attr('id')).focus();
    	document.getElementById($(this).attr('id')).select();
	});
	
	$('#quote-shortcode').each(function(){
		$('#quote_align').removeClass('attribute');
		$('#quote_style').change(function() { 
			var target = $(this).val();
			if(target=='blockquote') { $('#quote_align').removeClass('attribute'); }
			else { $('#quote_align').addClass('attribute'); }
		});
	});
	
	$('input[name=generate]').click(function() { 
		var target = $(this).attr('id'),
		shortcode = '';
		
  		shortcode+= '['+target;
		
		if(target!='tabs' && target!='accordions' && target!='columns') {
			
			if($('#'+target+'-shortcode .attribute').length > 0) {
				if (target=='ul') {
					shortcode+= ' style="'+$('#'+target+'-shortcode .attribute:checked').val()+'"';
				} else {
					$('#'+target+'-shortcode .attribute').each(function() {
						var name = $(this).attr('name');
						if ( $(this).hasClass('color-value') ) {
							var color = $(this).val().replace(/[^0-9a-fA-F]/g, '');
							if ( color.length == 6 || color.length == 3 ) {
								var nameVal = '#'+color;
							} else {
								var nameVal = '#';
							}
						} else {
							if(target == 'tweets' && name == 'timeformat') {
								if($(this).val() == 'equal_with_blog_setting') var nameVal = 'std';
								else var nameVal = 'ago';
							} else {
								var nameVal = $(this).val();
							}
						}
						if(nameVal != 'self' && nameVal != 'blank' && nameVal != 'none' && nameVal != '#') {
							if(name == 'author' && nameVal == '') return;
							if(target == 'totop' && nameVal == '') return;
							if(name == null) return;
							shortcode+= ' '+name+'="'+nameVal+'"';
						}
					});
				}
			}
		
		}
		
		if(target=='columns') {
			shortcode+= ' size="'+$('#'+target+'_offset').val()+'_'+$('#'+target+'_size').val()+'"';
			if(($('#'+target+'_position').val())=='yes') shortcode+= ' position="last"';
			if(($('#'+target+'_break').val())!='yes') shortcode+= ' break="no"';
		}
		
		shortcode+= ']';
		if(target!='dropcap' && target!='highlight') {
			shortcode+= '\n';
		}

		if(target=='tabs' || target=='accordions' || target=='ul') {
			if(target=='tabs') var trgt = 'tab';
			if(target=='accordions') var trgt = 'accordion';
			if(target=='ul') var trgt = 'li';
			var repeat = '2';
			for (count=1; count<=repeat; count=count+1) {
				shortcode+= '['+trgt;
				if($('#'+target+'-shortcode .attribute').length > 0 && target != 'ul') {
					$('#'+target+'-shortcode .attribute').each(function() {
						shortcode+= ' '+$(this).attr('name')+'="'+$(this).val()+'"';
					});
				}
				shortcode+= ']\n';
				shortcode+= $('#'+target+'_content').val()+'\n';
				shortcode+= '[/'+trgt+']\n';
			}
		} else {
			if($('#'+target+'_content').length > 0) {
				shortcode+= $('#'+target+'_content').val();
				if(target!='dropcap' && target!='highlight') {
					shortcode+= '\n';
				}
			}
		}
 		
		if($('#'+target+'_content').length > 0) {
			shortcode+= '[/'+target+']\n';
		}
		
 		$('#'+target+'_code').val(shortcode);
	});
	
	$(".number").change( function() {
		var number = $(this).val().replace(/[^0-9]/g, "");
		if (number != null) $(this).val(number);
	});
	
});