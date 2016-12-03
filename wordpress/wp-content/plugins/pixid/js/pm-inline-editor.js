/**
 * This is a temporary version of PixiD inline wrappers.
 * We need to rewrite this code. Need to use a dedicated object Wrapper directly to any wrapper. +Some global initialization methods.
 * At this moment code seems unsupported.
 * */
var PM = {
	debug_enabled: function() {
		return false;
	},
	fade_timeout: 400,

	get_highlight_element : function(element) {
		var wrapper_uid = jQuery(element).data('wrapper-uid');
		var highlighter = jQuery('#pm-highlight-'+wrapper_uid)[0];
		return highlighter;
	},

	maybe_hide_controls : function(element) {
		var highlighter = PM.get_highlight_element(element);
		if(!jQuery(highlighter).is(':visible')) return;
		setTimeout(function(){
			var hovered = false;

			if(jQuery(highlighter).is(':hover')) {
				return false;
			}	
			jQuery(highlighter).find('*').each(function(i, el){
				if(jQuery(el).is(':hover')) hovered = true;
			});	
			if(false == hovered) jQuery(highlighter).hide();
		}, 400);	
	},	

	maybe_show_controls : function(element) {
		var highlighter = this.get_highlight_element(element);	
		jQuery(highlighter).fadeIn(300);
	},

	bind_action_handlers : function() {
		var triggers = jQuery('.pm-action-trigger');
		jQuery(triggers).each(function(i, trigger) {
			jQuery(trigger).click(function(){
				var url = jQuery(this).attr("href");
				var width = jQuery(this).data('dialog_width');
				var height = jQuery(this).data('dialog_height');
				if(!width) width = 800;
				if(!height) height = 600;
				jQuery.featherlight({
					iframe: url,
					afterContent: function() {
					
					},
					iframeMaxWidth: '100%',
					iframeWidth: width,	
					iframeHeight: height
				});
				return false;
			});
		});
	},
	resize_dialog: function(width, height) {
		var flc = jQuery('.featherlight-content');
		jQuery(flc).width(width);
		jQuery(flc).height(height);
	},
	init_elements: function() {
		$j = jQuery;
		$this = this;
		$j('.pm-wrapper').each(function(i, pm){
			var highlight = $this.get_highlight_element(pm);
			$j(highlight).detach();
			$j('body').append(highlight);
		});	
	},

	is_block_element: function(element) {
		return (jQuery(element).css('display') == 'block');
	},

	recalc_positions: function() {
		$this = this;
		$j = jQuery;
		$j('.pm-wrapper').each(function(i, pm){
			var highlight = $this.get_highlight_element(pm);
			var w, h = 0;
			if($this.is_block_element(pm)) {
				var f = pm;
				$j(highlight).width($j(pm).outerWidth());
				$j(highlight).height($j(pm).outerHeight());
			}
			else {
				var f =	$j(pm).find(":first");	
				$j(highlight).width($j(f).outerWidth());
				$j(highlight).height($j(f).outerHeight());
			}
		var o = $j(f).offset();	
		if('undefined' != typeof o)	
			$j(highlight).offset({ top: o.top, left: o.left });	
		});
	},

	find_buttons: function(wrapper) {
		var highlighter = this.get_highlight_element(wrapper);
		var buttons = jQuery(highlighter).find('.pm-button');
		return buttons;
	},
	closeModal: function(reload) {
		jQuery.featherlight.close();	
		if(reload)
			window.location.reload();
	},
	closePMEditor: function(reload) {
		this.closeModal();
		if(reload)
			window.location.reload();
	}

}
jQuery(window).load(function() {
	//Init must be really onload, we do not need to wait until page full-load!
	PM.init_elements();
	//FIXME: !Important This is a small fixes which can fix some issues when wrapper wraps a floated elements.
	jQuery('.pm-wrapper').each(function(i, wrapper){
		var fe = jQuery(wrapper).find(':first');
		var fl = jQuery(fe).css('float');
		if(fl == 'left') {
			jQuery(wrapper).css({ float: fl });
		}
		var childs = jQuery(wrapper).children();	
		var d = false;
		jQuery(childs).each(function(i, c){
			var display = jQuery(c).css('display');
			if(jQuery(c).prop("tagName") != "SCRIPT" && jQuery(c).prop("tagName") != "STYLE" && !d && display != 'none') {
				d = true;
				var display = jQuery(c).css('display');
				jQuery(wrapper).css({display: display});
			}
		});
		
	});
	//We need to bind our actions and do a positioning little later, not just on all DOM ready or content loaded.
	//Some websites have a some visual effects before show a webpage, such as a fadingout of all of the content.
	//So the positions of the elements, and or other stuff can be not a valid...
	setTimeout(function(){
		var $ = jQuery.noConflict();
		$('body').append("<div class='pm-storage' id='pm-storage'></div>");	
		PM.recalc_positions();
		//FIXME: Here we have a large bug, if we call recalc_position bug - the position will be wrong
		//PM.recalc_positions();
		$('.pm-wrapper').hover(function(){
			PM.maybe_show_controls(this);	
		}, function(){
			PM.maybe_hide_controls(this);	
		});
		$('.pm-highlight').hover(function(){}, function(){
			var wrapper = jQuery('#pm-wrapper-'+jQuery(this).data('wrapper-uid'));
			PM.maybe_hide_controls(wrapper);
		});
		$('.pm-wrapper').dblclick(function(){
			var buttons = PM.find_buttons(this);

			if(buttons.length > 0) {
				//FIXME: this code not work :(
				jQuery(buttons[0]).trigger('click');
			}	
			return true;
		});
			PM.bind_action_handlers();
			window.PM = PM;
			jQuery(window).resize(function(){
				//PM.recalc_positions();
			})
			}, 2000);
});
