(function($) {	
	var Constructor = {
		debug: true,
editorContainer: null,
containerIframe: null,
editor: null,

colors: null,
font_sizes: null,
fonts: null,
overlay: null,
layouts: {
	horizontal: {
		width: 65,
		height: 27
	},
	vertical: {
		width: 27,
		height: 65
	}
},
	settings: {
		zoomFullScreen: 1,
		zoomNormal: 1
	},
	/**
	 * Overlay methods which helps to hide a page content while "fullscreen" mode entered... 
	 * */
	initOverlay: function() {
		var overlay = jQuery('<div>').addClass('constructor_overlay');
		jQuery(overlay).appendTo('body');
		this.overlay = overlay;
	},
	showOverlay: function() {
		this.overlay.show();
	},
	hideOverlay: function() {
		this.overlay.hide();
	},
	getEditorBody: function() {
		var doc = this.editor.getDoc();
		return jQuery(doc).find('html').find('body');
	},
	currentLayout: null,
	/**
	 * This method just updates the zoom for our scarf 
	 * */
	updateZoom: function(zoom_value) {
		/**
		 * Default zoom value */
		if(!zoom_value)
			zoom_value = this.settings.zoomNormal;

		this.editorContainer.css({ 'zoom': zoom_value, '-moz-transform': 'scale('+zoom_value+')', '-moz-transform-origin': '0 0' });
		this.editorContainer.find('iframe').css({ height: '100%' });
		var doc = this.editor.getDoc();
		jQuery(doc).find('html').css({ 'zoom': zoom_value});
			
		return true;
	},
	/**
	 * We need to strip a text which is hided by overflow: hidden property... 
	 * */
	stripText: function() {
		//this.editorContainer.css({ 'overflow': 'hidden' });
		//this.containerIframe.css({ 'overflow': 'hidden' });
		var doc = this.editor.getDoc();
		jQuery(doc).find('html').css({ 'overflow': 'hidden' });
	},
	setLayout: function(layoutName) {
		if(this.debug) {
			console.log("Set layout "+layoutName);
		}


		$this = this;
		var classes = this.editorContainer.attr('class').split(/\s+/);	
		$.each(classes, function(i, className){
			if(-1 !== className.indexOf('layout_')) $this.editorContainer.removeClass(className);
		});
		this.editorContainer.addClass('layout_'+layoutName);	
		var sizes = this.layouts[layoutName];
		//var sizes = original_sizes;		
		this.editorContainer.css({ width: sizes.width +'in', height: sizes.height+'in' });

		this.containerIframe.css({ width: '100%', height: '100%', 'border-width': $this.settings.indent, 'box-sizing': 'border-box' });
		
		
		this.stripText();
		this.setConstructorValue('layout', layoutName)	;
		jQuery('#wp-scarf-editor-wrap').removeClass('layout_vertical');
		jQuery('#wp-scarf-editor-wrap').removeClass('layout_horizontal');
		jQuery('#wp-scarf-editor-wrap').addClass('layout_'+layoutName);
		//Advanced set a canvas sizes	
//		this.updateCanvas(sizes);	
		this.updateZoom();
		this.currentLayout = layoutName;
	},
	setBackgroundColor: function(colorName) {
		if(this.debug) {
			console.log("Set background color: "+ colorName);
		}
		$this = this;
		var classes = this.editorContainer.attr('class').split(/\s+/);
		$.each(classes, function(i, className){
			if(-1 !== className.indexOf('background_')) {
				$this.editorContainer.removeClass(className);
			}	
		});
		this.editorContainer.addClass('background_'+colorName);	
		var color = false;
		jQuery($this.colors).each(function(i, c){
			if(c.color == colorName) color = c;
		});

		$(this.editor.getBody()).css({ 'background-color': color.colorValue });

		this.setConstructorValue('color', colorName);	
	},
	setFontSize: function(fontSize) {	
		this.getEditorBody().css({ 'font-size': fontSize });	
		this.setConstructorValue('fontsize', fontSize);
		return true;
	},
	initValues: function() {
		$this = this;
		var layout = $this.getConstructorValue('layout');
		//as a tinymce architecture, we can not really trigger a select on a dropdowns, so, do it manually by call all needed code directly
		if ('' != layout) {
			this.setLayout(layout);	
		}
		var backgroundColor = $this.getConstructorValue('color');
		if('' != backgroundColor) {
			this.setBackgroundColor(backgroundColor);
		}
		var skip_default_styles = jQuery('input[name="constructor[skip_default_styles]"]').val();
		if(skip_default_styles == "yes") return;
		var defaultFontSize = this.getConstructorValue('fontsize');
		if('' != defaultFontSize) {
			this.editor.execCommand("FontSize", false, defaultFontSize);
		}
		var defaultFontFamily = this.getConstructorValue('font');
		if('' != defaultFontFamily) {
			this.editor.execCommand("FontName", false, defaultFontFamily);
		}
	},
	getConstructorValue(param) {
		var input = jQuery('input[name="constructor['+param+']"]');
		if(input.length == 0) {
			return false;
		}
		return jQuery(input).val();
	},
	setConstructorValue(param, value) {
		var input = jQuery('input[name="constructor['+param+']"]');
		jQuery(input).val(value);
	},
	initButtons: function(editor) {
		var _editor = editor;
		var colorsMenu = new Array();	
		jQuery(this.colors).each(function(i, color){
			colorsMenu.push({
				text: color.title,
				classes: 'select_'+color.title,
				value: color.color
			});
		});
		editor.addButton('constructor_colors_list_button', {
			type: 'menubutton',
			tooltip:"Select your scarf color",
			classes: "color_select_list listbox",
			text: 'Scarf color',
			onselect: function(e) {
				var value = e.control.value();
				//Seems here we can change a title of the button, or some other advanced stuff
				editor.plugins.Constructor.setBackgroundColor(value);
			},
			onshow: function(e) {
				e.control.items().each(function(item){
					item.active(false);	
					if(editor.plugins.Constructor.getConstructorValue('color') == item.value()) {
						item.active(true);
					}	
				});
			},
			menu: colorsMenu 
		});
		editor.addButton('constructor_layout_select', {
			type: 'menubutton',
			tooltip: 'Select a layout for your Scarf',
			classes: 'layout_select_list listbox',
			text: 'Layout',

			onselect: function(e) {
				var value = e.control.value();
				editor.plugins.Constructor.setLayout(value);	
			},
			onshow: function(e) {
				e.control.items().each(function(item){
					item.active(false);
					if(editor.plugins.Constructor.getConstructorValue('layout') == item.value()) {
						item.active(true);
					}	
				});
			},
			menu: [
		{
			text: 'Horizontal',
			classes: "select_layout_horizontal",
			value: "horizontal"
		},
			{
				text: 'Vertical',
				classes: "select_layout_vertical",
				value: "vertical"	
			}
		]
		});	
	},
	init: function(editor) {
		$this = this;
		$this.colors = editor.settings.constructor_background_colors;	
		$this.fonts = editor.settings.constructor_fonts;
		$this.font_sizes = editor.settings.constructor_font_sizes;
		$this.settings.zoomNormal = editor.settings.zoomNormal;
		$this.settings.zoomFullScreen = editor.settings.zoomFullScreen;
		$this.settings.indent = editor.settings.indent;
		
		editor.on("init", function(e){
			$this.editor = e.target;
			$this.editorContainer = $($this.editor.getContentAreaContainer());
			$this.containerIframe = $this.editorContainer.find('iframe');
			$this.editorContainer.addClass('constructor_layout');	
		});
		editor.on("FullscreenStateChanged", function(ev) {
			if(ev.state) {
				//Fullscreen
				this.plugins.Constructor.updateZoom(this.plugins.Constructor.settings.zoomFullScreen);
				
				//Temporary hack we need to hide other page elements...
				this.plugins.Constructor.showOverlay();
			} else {
				this.plugins.Constructor.updateZoom(this.plugins.Constructor.settings.zoomNormal);
				this.plugins.Constructor.hideOverlay();	
			}
		});
		//We have a bug in fullscreen mode...
		$(window).resize(function(){
			editor.plugins.Constructor.containerIframe.css({ 'height': '100%' });
		});
		editor.on("BeforeExecCommand", function(ev) {
			//console.log(ev);
			if(ev.command == 'FontSize') {
				if(editor.selection.getContent() == '') {
					//Set fontsize constructor value only if no selection
					editor.selection.select(editor.getBody(), true);
					editor.plugins.Constructor.setConstructorValue('fontsize', ev.value);
				}
			}
			if(ev.command == 'FontName') {
				if(editor.selection.getContent() == '') {
					editor.selection.select(editor.getBody(), true);	
				}
				//editor.plugins.Constructor.setConstructorValue('font', ev.value);
			}
		});
		editor.on("change", function(ed) {
			editor.plugins.Constructor.updateCanvas(ed);
		});
		this.initButtons(editor);
		this.initOverlay();
		//Init canvas element...
	},
	
	getCanvas: function() {
		if(!this.canvas) {
			this.canvas = document.getElementById('constructor_output');
		}
		return this.canvas;
	},
	inToPx: function(inches) {
		var dpi_x = document.getElementById('dpi').offsetWidth;
		var dpi_y = document.getElementById('dpi').offsetHeight;
		//?????
		return inches * dpi_x;
	},
	updateCanvas: function(ed) {
		var sizes = this.layouts[this.currentLayout];
		var canvas = this.getCanvas();
		$(canvas).css( { width: sizes.width + 'in', height: sizes.height + 'in' } );
		$(canvas).css( { zoom: 1 });
		//Clear it...
		var context = canvas.getContext("2d");
		context.clearRect(0, 0, canvas.width, canvas.height);
		var content = this.editor.getContent({ format: 'raw' });
		var elements = $(content);
		//TODO: Set offsets
		var offsets = this.inToPx(0.75);
		$(elements).each(function(i, el){
			var text = $(el).text();
			var font = $(el).css('font-family');
			var size = $(el).css('font-size');
			var font_string = size + ' ' + font;
			//console.log(font_string);
			context.font = font_string;
			var metrics = context.measureText(text);
			console.log(metrics);
			var textWidth = metrics.width;
			var scalex = (canvas.width / textWidth);
			var scaley = (canvas.height / 23);

			var ypos = (canvas.height / (scaley * 1.25));
			context.scale(scalex, scaley);
			context.fillText(text, 0, ypos);
			return;
		});
	}
};

tinymce.create('tinymce.plugins.Constructor', Constructor);
tinymce.PluginManager.add( 'Constructor', tinymce.plugins.Constructor );

}(jQuery));
