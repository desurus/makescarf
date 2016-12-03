/**
 * This is a first pure versions of this plugin.
 * At this moment it works very bad. We just sends all our request to a server backend which is a very slodowns the editor...
 * We need to find a normal PHP tokenizer written in javascript which can be used here.
 * @version 0.1
 * @author Shell
 * */
var SimplePHPBlocks = {
	editor: null,
	workerContainer: null,
	isDebug: false,
	log: function(param) {
		//FIXME: Need to check console is available
		//return console.log(param);
	},
	init: function(editor, url) {
		var $this = this;
		$this.editor = editor;
		editor.contentCSS.push(url+"/css/blocks.css");	
		//Seems we really need this hidden div to manipulate with some elements...
		if(!$this.workerContainer) {
			$this.workerContainer = jQuery('<div></div>');
			$this.workerContainer.addClass('pm-worker-container')
				.css({ display: 'none' })
				.attr("id", 'pm_worker_container');
			jQuery('body').append($this.workerContainer);
		}
		editor.on('BeforeSetContent', function(e) {
			//$this.log("Set content triggered");
			//$this.log(e.content);
			if(e.selection && e.selection == true) {	
				return e;
			}
			
			jQuery.ajax(ajax_url, {
				data: { action: 'pm_convert_code', to: 'visual', code: e.content },
				dataType: 'json',
				method: 'post',
				success: function(response) {
					if(parseInt(response.status) == 0)	{
						var code = response.result;
						//Post process the code with `bbcodes`		
						
						code = $this.internalToVisualPost(code);				
						
						$this.editor.setContent(code, { selection: true, initial: true });
					} else {
						//Some error occured...
					}
				}
			});
			e.content = "<p>Loading a code, please wait.</p>";
			return e.content;	
		});

		editor.on("GetContent", function(e){
			e.content = $this.toRawCode(e.content);	
			return e;
		});
	},
	getEditorRawCode: function() {
		var content = SimplePHPBlocks.editor.getContent();	
		return content;
	},
	internalToVisualPost: function(content) {
		//FIXME: This this method can drop some markup or text
		var $this = this;
		var code = "";	
		$this.workerContainer.html(content);
		
		$this.workerContainer.contents().each(function(i, el){	
			//Check element attributes containing code
			if(jQuery(el).length > 0) {
				if(undefined === jQuery(el)[0].outerHTML) {	
					var element_code = jQuery(el).text();
				} else {
					var element_code = jQuery(el)[0].outerHTML;
				}
			}	
			el = $this.checkAttributes(el);	
			var element_text = jQuery(el).text();
			var original_text = element_text;

			
			if($this.hasCode(element_text)) {
				element_text = $this.textToVisualBlocks(element_text);	
				element_code = element_code.replace(original_text, element_text);
			}
			
			code += element_code;
		});	
		//Ugly hacks goes here... :(
		/*$this.workerContainer.html(content);
		var text = $this.workerContainer.clone()
			.children()
			.remove()
			.end()
			.text();
		$this.workerContainer.html("");
		console.log(text);*/
		//We got an unexpected error: Uncaught TypeError: m.getBoundingClientRect is not a function	
		$this.workerContainer.html("");
		return code;
	},
	getOuterHtml: function(el) {
		return jQuery('<div>').append(jQuery(el).clone()).html();
	},
	toRawCodeNew: function(content) {
		$this = this;
		$this.workerContainer.html(content);
		var resultContent = $this.workerContainer.html();
		$this.workerContainer.find('div.pm_visual_block').each(function(i, el){
			var clean_code = $this.getOuterHtml(el);
			var code = $this.decodeCode(jQuery(el).data('code'));	
			resultContent = resultContent.replace(clean_code, code);
		});
		var match = resultContent.match(/\[pmcode lang=(.*?)\](.*?)\[\/pmcode\]/g);		
		jQuery(match).each(function(i, code){
			var parts = code.match(/\[pmcode lang=(.*?)\](.*?)\[\/pmcode\]/);
			var code = parts[2];
			resultContent = resultContent.replace(parts[0], $this.decodeCode(code));
		});
		$this.workerContainer.html("");
		return resultContent;
	},
	/*
	 * This method helps to get a fully _RAW_ code of a source from editor instance */
	toRawCode: function(content) {
		return this.toRawCodeNew(content);
		var $this = this;
		//Convert [pmcode] elements...
		var match = content.match(/\[pmcode lang=(.*?)\](.*?)\[\/pmcode\]/g);		
		jQuery(match).each(function(i, code){
			var parts = code.match(/\[pmcode lang=(.*?)\](.*?)\[\/pmcode\]/);
			var code = parts[2];
			content = content.replace(parts[0], $this.decodeCode(code));
		});	
		//Convert visual blocks elements...
		var html = "";
		jQuery(content).find('.pm_visual_block').each(function(i, el){
			var clean_code = jQuery('<div>').append(jQuery(el).clone()).html();
			//console.log(clean_code);
			var real_code = $this.decodeCode(jQuery(el).data('code'));
			content = content.replace(clean_code, real_code);
		});
		content = content.replace('<div id="pm_bugfix_node"><!-- This is a bugfix div --></div>', '');
		/*jQuery(content).find('div#pm_bugfix_node').each(function(i, el){
			var clean_code = $this.getOuterHtml(el);
			console.log(clean_code);
			content = content.replace(clean_code, '');
		});*/
		//Return latest paragraph, if is empty...
		return content;
	},
		/*
		 * This function checks the element(s) attributes and proerties for code and then replace it's with 'safer' code snippets 
		 * */
		checkAttributes: function(el) {
			return el;	
		},
		textToVisualBlocks: function(text) {
			text = text.replace(/\[pmcode lang=(.*?)\](.*?)\[\/pmcode\]/, '<div title="Code block, language: $1." class="pm_visual_block pm_visual_block_$1" data-code="$2">$1</div>');
			return text;
		},
		hasCode: function(text) {
			var result = text.search(/\[pmcode lang=(.*?)\](.*?)[/pmcode]/);
			if(-1 != result) return true;
			return false;
		},
		createControl: function(n, cm) {
			//console.log(n, cm);
		},
		decodeCode: function(data) {
			return this.base64Decode(data);
		},
		base64Decode: function(data) {
			// Decodes data encoded with MIME base64
			// 
			// +   original by: Tyler Akins (http://rumkin.com)


			var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
			var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';

			do {  // unpack four hexets into three octets using index points in b64
				h1 = b64.indexOf(data.charAt(i++));
				h2 = b64.indexOf(data.charAt(i++));
				h3 = b64.indexOf(data.charAt(i++));
				h4 = b64.indexOf(data.charAt(i++));

				bits = h1<<18 | h2<<12 | h3<<6 | h4;

				o1 = bits>>16 & 0xff;
				o2 = bits>>8 & 0xff;
				o3 = bits & 0xff;

				if (h3 == 64)	  enc += String.fromCharCode(o1);
				else if (h4 == 64) enc += String.fromCharCode(o1, o2);
				else			   enc += String.fromCharCode(o1, o2, o3);
			} while (i < data.length);

			return enc;
		}
};
