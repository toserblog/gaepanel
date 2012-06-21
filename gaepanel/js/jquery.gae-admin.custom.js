/*
 * jQuery Gae custom back-end plugin
 *
 * (c) 2012 GaeTheme.com
 *
 */

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
				var src 	= $(this).val();
				$(this).parent().find('.'+setting.prevClass).fadeOut(100);
				$(this).parent().find('.'+setting.prevClass).empty();
				if ( src != '' ) {
					$(this).parent().find('.'+setting.prevClass).append('<img src="'+src+'" alt="" />');
				}
				$(this).parent().find('.'+setting.clearClass).show();
				$(this).parent().find('.'+setting.prevClass).fadeIn(900);
			});
			
			$(this).find('.'+setting.clearClass).click(function() {
				$(this).fadeOut();		
				$(this).parent().find('.'+setting.prevClass).slideUp();		
				$(this).parent().find('.'+setting.valClass).attr('value', '');		
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
	
})( jQuery );