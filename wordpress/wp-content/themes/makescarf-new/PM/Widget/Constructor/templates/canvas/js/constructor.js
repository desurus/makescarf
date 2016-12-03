jQuery(document).ready( function($) {
	$j = $;
	//No constructors on this page
	if($('.constructor').length == 0) return false; 
	
	var 
		editor_ = {}, 
		activeEditor_= {}, 
		select = {}, 
		deg = 0, 
		defaultBackground = "#e5e0d6",
		lvertival = "vertical layout";
		lhorizontal = "horizontal layout";
		scarf_text = "Biege color";
		active = false, 
		step = 0,
		scale = 1.0;

	var scarfEditor = tinymce.init({
		width: "19,5cm",
		height: "7.4cm",
		selector: "#scarf-editor",
		toolbar: [
			"layout-v layout-h layout-text",
			"scarf-color scarf-color-list",
			"forecolor fontcolor-name",
			"font-name fontselect bold italic alignleft aligncenter alignright alignjustify",
			/*"fontsize-name fontsizeselect"*/
    		],
    		plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
    			"searchreplace wordcount visualblocks visualchars code fullscreen",
    			"insertdatetime media nonbreaking save table contextmenu directionality",
    			"emoticons template paste textcolor colorpicker textpattern imagetools"//There are was a placeholder plugin!
		],
    		menu: {},
    		statusbar: false,
	    	
    		setup: function(editor) {
	    		editor_ = editor;
	    		editor.on('init', function(e) {
		    	editor.getBody().style.backgroundColor = defaultBackground;
		    	$(editor.getBody()).attr("data-full", "0");
		    	$(".mce-color-block").css({backgroundColor: defaultBackground });
		    	activeEditor_ = tinymce.activeEditor;


		    	editor.on("click", function() {
			    if(l && !l_a) {
				    $(".blackout").show();
				    $(".save-scarf-error").fadeIn(300); 
				    return false;   
			    }    
		    	});

	    		});

	    editor.addButton('layout-v', {
		    title: 'Vertical',
		    classes: "layout layout-vertical",
		    onclick: function() {

			    if(!active) {
				    lvertival = "Vertical layout";
				    self = this; 
				    deg = 90;

				    $j(".mce-edit-area > iframe").removeClass("h-vertival-1 h-vertival-2 h-horizontal");
				    setTimeout( function() {
					    $j(".mce-edit-area > iframe").removeClass("h-vertical-1");
					    $j(".mce-edit-area > iframe").addClass("h-vertical-2");
					    $j(".mce-edit-area").css({position: "relative"});
				    }, 400);
				    $j("input[name='scarf-layout-type']").val("vertical");
				    $j("#label-placeholder").hide();
				    setTimeout( function() {$j("#label-placeholder").css({
					    fontSize: "20px",
					    width: "250px",
					    height: "20px",
					    margin: "100px 0 0 -130px",
				    }).show(); }, 1000);
			    }
		    }
	    });

	    editor.addButton('layout-h', {
		    title: 'Horizontal',
		    classes: "layout layout-horizontal",
		    onclick: function() {
			    if(!active) {
				    lvertival = "Horisontal layout";
				    self = this;
				    deg = 0;

				    $j(".mce-edit-area > iframe").removeClass("h-vertival-1 h-vertical-2 h-horizontal");
				    $j(".mce-edit-area > iframe").addClass("h-horizontal");
				    $j(".mce-edit-area").css({position: "static"});
				    $j(".mce-layout-name").text(lvertival);
				    $j("input[name='scarf-layout-type']").val("horizontal");
				    $j("#label-placeholder").css({
					    fontSize: "32px",
					    width:'400px',
					    height: "30px",
					    margin: "-30px 0 0 -200px",
				    });
			    }
		    }
	    });
	    editor.addButton('layout-text', {
		    type: "label",
		    title: 'Current layout',
		    text: lhorizontal,
		    classes: "layout layout-name",
	    });

	    editor.addButton('scarf-color', {
		    title: 'scarf color',
		    text: "",
		    classes: "color-block",
		    disabled: true,
	    });

	    editor.addButton('scarf-color-list', {
		    type: 'menubutton',
		    tooltip:"Scarf color",
		    classes: "color-list",
		    text: scarf_text,
		    menu: [
	    		{	
		    		text: 'Biege color', 
		    		classes: "e5e0d6", 
		    		onclick: function(e) {	
					editor.getBody().style.backgroundColor = "#e5e0d6"; 
					$(".mce-color-block").css({backgroundColor:"#e5e0d6" });
					$("input[name='scarf-background']").val("#e5e0d6");
					$('.constructor-scarf').css({'background-color' : '#e5e0d6'});
				}
	    		},
		    	{
				text: 'White color', 
		    		classes:"ffffff", 
		    		onclick: function(e) {	
					editor.getBody().style.backgroundColor = "#ffffff";
					$(".mce-color-block").css({backgroundColor:"#ffffff" });
					$("input[name='scarf-background']").val("#ffffff");
					$('.constructor-scarf').css({'background-color': '#ffffff'});
				}
		    	}
	    		]
	    });

	    

	    editor.addButton('fontcolor-name', {
		    type: "label",
		    title: '',
		    classes: 'fcolor-name',
		    text: "Font color"
	    });

	    editor.addButton('font-name', {
		    type: "label",
		    title: '',
		    classes: 'font-name',
		    text: "Choose font"
	    });

	    editor.addButton('fontsize-name', {
		    type: "label",
		    title: '',
		    classes: 'f-name',
		    text: "Font size"
	    });


    }
});

/*$j(".c-rotate").on("click", function() {

  if(deg != 360) {
  deg = deg + 45;
  }
  else {
  deg = 0;
  }

  $j(".mce-edit-area > iframe").removeClass("h-rotate-0 h-rotate-45 h-rotate-90 h-rotate-135 h-rotate-180 h-rotate-225 h-rotate-270 h-rotate-315 h-rotate-360 h-vertival h-horizontal");
  $j(".mce-edit-area > iframe").addClass("h-rotate-" + deg);


  });*/

$j(".c-zoom-plus").on("click", function() {
	active = true;
	if(active) {

		$j(".blackout-white").show();
		var type = $j("input[name='scarf-layout-type']").val();
		width  = screen.availWidth  || screen.width || $j(window).width(), 
	height = screen.availHeight || screen.height || $j(window).height(),
	scarf_width = $j(".constructor-scarf").width(),
	scarf_height = $j(".constructor-scarf").height(),
	toolbar_width = $j(".mce-toolbar-grp").width();

if(type == "vertical") {
	width = width - height; 
	height = width + height;
	width = height - width;  
}

console.log(width + " -- " + height);

step += 1;
scale += 0.1;

var low = 1;
var res = step % 2;

if(step <= 3) {
	low = step;
}
else {
	low += 1;
}

if(step == 1) {
	$j(".constructor-scarf").hide();
	setTimeout( function() {

		$j(".constructor-scarf").removeClass("constructor-scarf-fixed-scale-1 constructor-scarf-fixed-scale-2 constructor-scarf-fixed-scale-3 constructor-scarf-fixed-scale-4 constructor-scarf-fixed-scale-5 constructor-scarf-fixed-scale-6 constructor-scarf-fixed-scale-7 constructor-scarf-fixed-scale-8 constructor-scarf-fixed-scale-9");
		$j(".mce-toolbar-grp").removeClass("mce-toolbar-grp-scale-1 mce-toolbar-grp-scale-2 mce-toolbar-grp-scale-3 mce-toolbar-grp-scale-4 mce-toolbar-grp-scale-5 mce-toolbar-grp-scale-6 mce-toolbar-grp-scale-7 mce-toolbar-grp-scale-8 mce-toolbar-grp-scale-9");
		$j(".mce-toolbar-grp").addClass("mce-toolbar-grp-fixed mce-toolbar-grp-scale-" + low).css({marginLeft: -Math.round(toolbar_width/2)});
		$j(".constructor-scarf").addClass("constructor-scarf-fixed constructor-scarf-fixed-scale-" + step );

		if(type == "horizontal") {
			$j(".constructor-scarf").css({width: width - 20}); 
		}
		else {
			$j(".constructor-scarf").css({
				width: scarf_height + 3, 
				height: scarf_width,
				position: "absolute",
				left: "50%"
			}); 
			$j(".h-vertical-2").removeClass("h-vertical-2").addClass("h-vertical-2-big");

			$j(".mce-toolbar-grp").addClass("h-toolbar-vertival");
		}

	$j(".constructor-scarf").show();
	}, 100);
}
/*else {

  $j(".constructor-scarf").removeClass("constructor-scarf-fixed-scale-1 constructor-scarf-fixed-scale-2 constructor-scarf-fixed-scale-3 constructor-scarf-fixed-scale-4 constructor-scarf-fixed-scale-5 constructor-scarf-fixed-scale-6 constructor-scarf-fixed-scale-7 constructor-scarf-fixed-scale-8 constructor-scarf-fixed-scale-9");
  $j(".mce-toolbar-grp").removeClass("mce-toolbar-grp-scale-1 mce-toolbar-grp-scale-2 mce-toolbar-grp-scale-3 mce-toolbar-grp-scale-4 mce-toolbar-grp-scale-5 mce-toolbar-grp-scale-6 mce-toolbar-grp-scale-7 mce-toolbar-grp-scale-8 mce-toolbar-grp-scale-9");
  $j(".mce-toolbar-grp").addClass("mce-toolbar-grp-fixed mce-toolbar-grp-scale-" + low).css({marginLeft: -Math.round(toolbar_width/2)});
  $j(".constructor-scarf").addClass("constructor-scarf-fixed constructor-scarf-fixed-scale-" + step );     
  }*/


var cwidth = Math.round(scarf_width * scale), cheight = Math.round(scarf_height * scale);


/*console.log("width " + width + " " + cwidth);
  console.log("height " + height + " " + cheight);

  if((width - cwidth) < 300) {
  active = false;    
  }
  if((height - cheight) < 250) {
  active = false;
  }*/

}

//$j(".mce-edit-area").addClass("c-zoom-full").css({top: topHeight, marginTop: _marginTop});

// $j(".constructor-scarf-menu").addClass("constructor-scarf-menu-fixed").css({top: topHeight - 230});

/*$j(activeEditor_.getBody()).attr("data-full", "1");
  activeEditor_.getBody().style.width = "149.9cm";
  activeEditor_.getBody().style.height = "58.4cm";
  activeEditor_.getBody().style.overflow = "auto";
  window.frames[0].document.body.style.fontSize = "1000%";*/

/*if(full == 0) {

  $j(activeEditor_.getBody()).attr("data-full", "1");
  activeEditor_.getBody().style.width = "149.9cm";
  activeEditor_.getBody().style.height = "58.4cm";
  activeEditor_.getBody().style.overflow = "auto";
  window.frames[0].document.body.style.fontSize = "1000%";
  }
  else {
  $j(activeEditor_.getBody()).attr("data-full", "0");
  activeEditor_.getBody().style.width = "19.5cm";
  activeEditor_.getBody().style.height = "7.4cm";
  activeEditor_.getBody().style.overflow = "hidden";
  window.frames[0].document.body.style.fontSize = "70%";    
  }*/

return false;

});



$j(".blackout").on("click", function() {
	$j(".blackout").hide();
	$j(".save-scarf-error").fadeOut(300); 
	return false;  
});
/*$j(".c-zoom-minus, .blackout-white").on("click", function() {

  $j(".mce-toolbar-grp").removeClass("mce-toolbar-grp-fixed mce-toolbar-grp-scale-1 mce-toolbar-grp-scale-2 mce-toolbar-grp-scale-3 mce-toolbar-grp-scale-4 mce-toolbar-grp-scale-5 mce-toolbar-grp-scale-6 mce-toolbar-grp-scale-7 mce-toolbar-grp-scale-8 mce-toolbar-grp-scale-9")
  .attr("style", "").removeClass("h-toolbar-vertival");

  $j(".constructor-scarf").removeClass("constructor-scarf-fixed constructor-scarf-fixed-scale-1 constructor-scarf-fixed-scale-2 constructor-scarf-fixed-scale-3 constructor-scarf-fixed-scale-4 constructor-scarf-fixed-scale-5 constructor-scarf-fixed-scale-6 constructor-scarf-fixed-scale-7 constructor-scarf-fixed-scale-8 constructor-scarf-fixed-scale-9")
  .attr("style", "").css({width: 770});
  $j(".blackout-white").hide();

  if($j(".h-vertical-2-big").length) {
  $j(".h-vertical-2-big").removeClass("h-vertical-2-big").addClass("h-vertical-2");
  }

  step = 0;
  scale = 1.0;
  active = false;
/*var full = +$j(activeEditor_.getBody()).attr("data-full");

$j(".mce-edit-area").removeClass("c-zoom-full").attr("style", "");
$j(".constructor-scarf").removeClass("constructor-scarf-fixed");
$j(".constructor-scarf-menu").removeClass("constructor-scarf-menu-fixed").attr("style", "");

$j(activeEditor_.getBody()).attr("data-full", "0");
activeEditor_.getBody().style.width = "19.5cm";
activeEditor_.getBody().style.height = "7.4cm";
activeEditor_.getBody().style.overflow = "hidden";
window.frames[0].document.body.style.fontSize = "70%";  */

/*return false;
  });*/

$j(".layout-vertical, .layout-horizontal").on("click", function(e) {
	var type = $j(this).attr("data-type"),
name = $j(this).attr("data-name"),
self = this;

console.log(type);

var obj = {};
if(type == 0) {
	obj = {
		width: "7.4cm",
height: "19.5cm",
left: "50%",
marginLeft: "-3.2cm",
marginTop: "-9.75cm"

	};

}
else {
	obj = {
		width: "19,5cm",
		height: "7.4cm",
		left: "50%",
		marginLeft: "-9.75cm",
		marginTop: "-3.2cm"

	}; 

}  
$j(".constructor-scarf").animate( obj, 300, function(e) {
	$j(".layout").removeClass("layout-active");
	$j(self).addClass("layout-active");
	$j(".l-name").text(name);
});   
}); 



$j(".other_ch_bl li").on("click", function() {
	var type = $j(this).attr("data-type-s").trim();

	$j("input[name='scarf-type']").val(type);
});

$j("form#product_addtocart_form").on("submit", function() {
	var text = tinyMCE.activeEditor.getContent()
	if(text.length) {
		$j("input[name='scarf-data']").val(text)
	}
	else {
		return false;  
	}

if(!$j("input[name='scarf-data']").val().length > 0) {
	return false;
}

});


/************* designer's tooltip *****************/

/*$j(document).ready( function() {
  $("")    
  });&*/

});
