jQuery(document).ready(function($) {

	$('.tab-content .element:nth-child(2)').addClass('first');
	
	// Img Upload 
	var uploadID = '';
	$('#gaeframework .file-button').click(function() {
		uploadID = $(this).prev('input');
		formfield = $('.upload').attr('name');
		tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html) {
		imgurl = $('img',html).attr('src');
		uploadID.val(imgurl);
		uploadID.change();
		tb_remove();
	};

	// Skin
	$('#gaeframework .skin input').change(function() {
		var select = $(this).attr('id');
		$('#gaeframework .skin li').removeClass('selected');
		$(this,'#+select+').parent().addClass('selected');
	});
	
});



(function( $ ){

	$.fn.gaeReset = function( options ) {  
		var setting = $.extend( {
			redirect	: '',
			confirm		: ''
		}, options);

		if ( setting.confirm != '' && setting.redirect != '' )
		return this.click(function() {
			if (confirm(setting.confirm)) {
				window.location = setting.redirect;
			};
		});
		
	};

	$.fn.gaeTabs = function() {  
		return this.each(function() {
			var myID = $(this).attr('id');
			$(this).find('.tab-content').hide();
			$(this).find('.tab-panel li').click(function(e) {
				e.preventDefault();
				$(this).parents().find('.tab-panel li').removeClass('active');
				$(this).addClass('active');
				$(this).parents().find('.tab-content').hide();

				var activeTab = $(this).find('a').attr('href');
				var activePanel = $(this).find('a').attr('class');
				$(activeTab).fadeIn();
				
				$.cookie(myID+'ContentOn', activeTab);
				$.cookie(myID+'PanelOn', '.'+activePanel);
				return false;
			});
				
			var openTab = $.cookie(myID+'ContentOn');
			var openPanel = $.cookie(myID+'PanelOn');
			if (openTab != null) {
				$(openPanel).parent().addClass('active');	
				$(openTab).show();
			} else {
				$(this).find('.tab-panel li:first').addClass('active');
				$(this).find('.tab-content:first').show();
			}
		});
	};

	$.fn.gaePreviewFile = function( options ) {  
		var setting = $.extend( {
			valClass	: 'file-input',
			prevClass	: 'file-preview',
			clearClass	: 'file-remove'
		}, options);

		return this.each(function() {
			if ( $(this).find('.'+setting.valClass).val() == '' ) {
				$(this).find('.'+setting.clearClass).hide();
				$(this).find('.'+setting.prevClass).empty();
			}

			$(this).find('.'+setting.valClass).change(function () {
				var src = $(this).val();
				$(this).parent().find('.'+setting.prevClass).fadeOut(100);
				$(this).parent().find('.'+setting.prevClass).empty();
				if ( src != '' ) {
					$(this).parent().find('.'+setting.prevClass).append('<img src="'+src+'" alt="" />');
				}
				$(this).addClass('previewed');
				$(this).parent().find('.'+setting.clearClass).show();
				$(this).parent().find('.'+setting.prevClass).fadeIn(900);
			});
			
			$(this).find('.'+setting.clearClass).click(function() {
				$(this).fadeOut();		
				$(this).parent().find('.'+setting.prevClass).slideUp();		
				$(this).parent().find('.'+setting.valClass).attr('value', '').removeClass('previewed');		
			});
			
		});
		
	};

	$.fn.gaeColor = function( options ) {  
		var setting = $.extend( {
			valClass	: 'color-value',
			butClass	: 'color-select',
			viewClass	: 'color-preview',
			pickClass	: 'color-picker',
			clearClass	: 'color-remove'
		}, options);

		return this.each(function() {

			var mainClass = $(this).attr('class');
			$(this).children().addClass(mainClass);

			var farbtastic;
			function pickColor(color) {
				farbtastic.setColor(color);
				$('.'+setting.valClass+'.'+mainClass).val(color);
				$('.'+setting.viewClass+'.'+mainClass).css('background-color', color);
				if ( color && color !== '#' )
					$('.'+setting.clearClass+'.'+mainClass).fadeIn();
				else
					$('.'+setting.clearClass+'.'+mainClass).fadeOut();
			}
			
			$('.'+setting.pickClass+'.'+mainClass).hide();
			$('.'+setting.butClass+'.'+mainClass).click(function() {
				$('.'+setting.pickClass+'.'+mainClass).fadeIn(200);
				return false;
			});
			$('.'+setting.clearClass+'.'+mainClass).click( function(e) {
				pickColor('#');
				$(this).parent().find('.'+setting.viewClass).attr('style','');
				e.preventDefault();
			});

			$('.'+setting.valClass+'.'+mainClass).keyup(function() {
				var _hex = $(this).val(), hex = _hex;
				if ( hex.charAt(0) != '#' )
					hex = '#' + hex;
				hex = hex.replace(/[^#a-fA-F0-9]+/, '');
				if ( hex != _hex )
					$(this).val(hex);
				if ( hex.length == 4 || hex.length == 7 )
					pickColor( hex );
			});
			
			farbtastic = $.farbtastic('.'+setting.pickClass+'.'+mainClass, function(color) {
				pickColor(color);
			});
			pickColor($('.'+setting.valClass+'.'+mainClass).val());
			
			$(document).mousedown(function(){
				$('.'+setting.pickClass+'.'+mainClass).each(function(){
					var display = $(this).css('display');
					if ( display == 'block' )
						$(this).fadeOut(100);
				});
			});

		});
		
	};
	
	$.fn.gaeNewAccount = function( options ) {  
		var setting = $.extend( {
			master	: 'prototype',
			theId	: ''
		}, options);
		
		return this.click(function() {
			var new_account_id = 10+Math.floor(Math.random()*100000),
				get_account_forms = $(this).parents().find('.'+setting.master).html(),
				new_account_forms = get_account_forms.replace(/the__id__/g, new_account_id).replace('__name__name__', setting.theId+'['+new_account_id+'][name]').replace('__url__name__', setting.theId+'['+new_account_id+'][url]').replace('__icon__name__', setting.theId+'['+new_account_id+'][icon]');
			$(this).parents().find('.social-accounts').append(new_account_forms);
			$('.account-'+new_account_id).slideDown();
		});
	};

})( jQuery );

function gaeDelAccount(ID) { 
	jQuery('.account-'+ID).css('background','#ecc').fadeOut('slow', function(){ jQuery(this).remove(); });
}
function gaeEditAccount(ID) { 
	jQuery('.account-title.account-'+ID).toggleClass('show');
	jQuery('.account-data.account-'+ID).slideToggle();
}