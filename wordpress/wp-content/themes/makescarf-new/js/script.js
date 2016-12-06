jQuery(document).ready(function() {

        var placeholders = {
            "name"    : "your name",
            "email"   : "your email address",
            "message" : "your message"
        };

		/***** popup ****/

		jQuery(".lifted_3 a").click(function(){
	    		jQuery(".popup_answ_site,.greyout").fadeIn(300);
    			return false;
		});
		jQuery(".lifted_4 a").click(function(){
	    		jQuery(".popup_answ_site,.greyout").fadeIn(300);
    			return false;
		});
		jQuery(".close_button,.greyout").click(function(){
			jQuery(".popup_answ_site,.greyout").fadeOut(300);
		});

		/***** owl slider ****/  



	    jQuery(".ever_link_ideas").mouseover(function(){
	        jQuery(this).find(".name_any_ideas").stop().slideDown(0);
	    }).mouseout(function(){
	        jQuery(this).find(".name_any_ideas").stop().slideUp(0);
		});

	    /**** call tinymce ***/

	    /*tinymce.init({
		   selector: "#mytextarea"
	    });*/

		/**** scrollbar *****/	

		/**** choose text ****/

		jQuery("input[type='checkbox']").on("click", function(e) {
            if(jQuery(this).is(":checked")) {
                jQuery(this).parent().css({"background" :"#fdfcfb","border":"2px solid #ece6db"});
            }
            else {
                jQuery(this).parent().css({"background" :"#fff","border":"2px solid transparent"});
            }
        });

		/****** snipet number goods ******/

		jQuery(".cart_count_plus", ".snip_num_good").on("click", function(e) {
			jQuery("input[name='field_snip']").val(+jQuery("input[name='field_snip']").val() + 1);
		});

		jQuery(".cart_count_minus", ".snip_num_good").on("click", function(e) {
			if(jQuery("input[name='field_snip']").val() <= 1) {
				return false;
			}
			jQuery("input[name='field_snip']").val(+jQuery("input[name='field_snip']").val() - 1);
		});

		/**** call color picker ***/

		var jQuerybox = jQuery('.much-color');

          //jQuerybox.tinycolorpicker();

        if(jQuerybox.length) {
            jQuery(jQuerybox).colorPicker({
                color: "#fff",
                renderCallback: function(elem, target) {
                    var color = elem.text;

                    if(color) {
                       jQuery(".under_t", ".descr_par_of_scr").css({color: color});
                       //document.querySelector(".under_t").style.color = color + "!important;";

                    }
                }
            });
        }

        jQuery(".eee").on("click", function() {
            jQuery(".under_t", ".descr_par_of_scr").css({color: "#000"});
        });


		/**** back_white back_white ****/

		jQuery(".list_other_ch_bl > li input[type='radio']").on("click", function(e) {
			jQuery(".list_other_ch_bl").find(".back_white").removeClass("back_white");
			if(jQuery(this).is(":checked")) {
			    jQuery(this).parent().addClass("back_white");


			}
	    });

		/**** back_white back_white YES/NO ****/

		jQuery(".list_other_yes_no > li input[type='radio']").on("click", function(e) {
			jQuery(".list_other_yes_no").find(".back_white").removeClass("back_white");
			if(jQuery(this).is(":checked")) {
			    jQuery(this).parent().addClass("back_white");


			}
	    });

		/*** switch fonts *****/

		jQuery(".list_check_fonts > li input[type='radio']").on("click", function(e) {
			jQuery(".descr_par_of_scr p").removeClass("mod_t");
			jQuery(".descr_par_of_scr p").removeClass("under_t");
			if(jQuery("#p").is(":checked")) {
			    jQuery(".descr_par_of_scr p").addClass("hand_t");
			}
			else if(jQuery("#p2").is(":checked")) {
			    jQuery(".descr_par_of_scr p").addClass("mod_t");
			}else if(jQuery("#p3").is(":checked")) {
				jQuery(".descr_par_of_scr p").addClass("under_t");
			}
	    });

	    jQuery(".list_picker li", "#colorPicker").on("click", function() {
	        jQuery(".list_picker li", "#colorPicker").removeClass("circle");
            jQuery(this).addClass("circle");
	    });


        jQuery(".send_form_cont input").on("focus", function() {
            jQuery(this).css({"input[placeholder]": "#fff"});
        });

		jQuery(".much-color").on("click",function(){
			
			jQuery(this).css({"background":"#000"});
		});

        // toggle mobile main menu on the top bar
        jQuery(document).on('click', '#toggle-mobile-menu', function(e){
            e.preventDefault();
            jQuery('.mobile-menu-wrapper').toggle();
        });

    });
