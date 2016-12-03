$(document).ready(function() {

        var placeholders = {
            "name"    : "your name",
            "email"   : "your email address",
            "message" : "your message"
        };

		/***** popup ****/

		$(".lifted_3 a").click(function(){
    		$(".popup_answ_site,.greyout").fadeIn(300);
    		return false;
		});
		$(".close_button,.greyout").click(function(){
			$(".popup_answ_site,.greyout").fadeOut(300);
		});

		/***** owl slider ****/

        $("#owl-demo").owlCarousel({

          autoPlay: 3000, //Set AutoPlay to 3 seconds

          items : 1,
          itemsDesktop : [1199,1],
          itemsDesktopSmall : [979,1],
		  itemsTablet : [768,1],
          navigation : true
        });

	    $("#owl-demo-2").owlCarousel({

          autoPlay: 3000, //Set AutoPlay to 3 seconds

          items : 5,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [979,3],
          navigation : true
        });



	    $(".ever_link_ideas").mouseover(function(){
	        $(this).find(".name_any_ideas").stop().slideDown(0);
	    }).mouseout(function(){
	        $(this).find(".name_any_ideas").stop().slideUp(0);
		});

	    /**** call tinymce ***/

	    tinymce.init({
		   selector: "#mytextarea"
	    });

		/**** scrollbar *****/

		if($(".scrollbar").length) {
			var news = document.querySelectorAll(".scrollbar");

			for(var i = 0; i < news.length; i++) {
				Ps.initialize(news[i], {
				  wheelSpeed: 2,
				  wheelPropagation: true,
				  minScrollbarLength: 20
				});
			}
		}

		/**** choose text ****/

		$("input[type='checkbox']").on("click", function(e) {
            if($(this).is(":checked")) {
                $(this).parent().css({"background" :"#fdfcfb","border":"2px solid #ece6db"});
            }
            else {
                $(this).parent().css({"background" :"#fff","border":"2px solid transparent"});
            }
        });

		/****** snipet number goods ******/

		$(".cart_count_plus", ".snip_num_good").on("click", function(e) {
			$("input[name='field_snip']").val(+$("input[name='field_snip']").val() + 1);
		});

		$(".cart_count_minus", ".snip_num_good").on("click", function(e) {
			if($("input[name='field_snip']").val() <= 1) {
				return false;
			}
			$("input[name='field_snip']").val(+$("input[name='field_snip']").val() - 1);
		});

		/**** call color picker ***/

		var $box = $('.much-color');

          //$box.tinycolorpicker();

        if($box.length) {
            $($box).colorPicker({
                color: "#fff",
                renderCallback: function(elem, target) {
                    var color = elem.text;

                    if(color) {
                       $(".under_t", ".descr_par_of_scr").css({color: color});
                       //document.querySelector(".under_t").style.color = color + "!important;";

                    }
                }
            });
        }

        $(".eee").on("click", function() {
            $(".under_t", ".descr_par_of_scr").css({color: "#000"});
        });


		/**** back_white back_white ****/

		$(".list_other_ch_bl > li input[type='radio']").on("click", function(e) {
			$(".back_white").removeClass("back_white");
			if($(this).is(":checked")) {
			    $(this).parent().addClass("back_white");


			}
	    });

		/**** back_white back_white YES/NO ****/

		$(".list_other_yes_no > li input[type='radio']").on("click", function(e) {
			$(".back_white_yes_no").removeClass("back_white_yes_no");
			if($(this).is(":checked")) {
			    $(this).parent().addClass("back_white_yes_no");


			}
	    });

		/*** switch fonts *****/

		$(".list_check_fonts > li input[type='radio']").on("click", function(e) {
			$(".descr_par_of_scr p").removeClass("mod_t");
			$(".descr_par_of_scr p").removeClass("under_t");
			if($("#p").is(":checked")) {
			    $(".descr_par_of_scr p").addClass("hand_t");
			}
			else if($("#p2").is(":checked")) {
			    $(".descr_par_of_scr p").addClass("mod_t");
			}else if($("#p3").is(":checked")) {
				$(".descr_par_of_scr p").addClass("under_t");
			}
	    });

	    $(".list_picker li", "#colorPicker").on("click", function() {
	        $(".list_picker li", "#colorPicker").removeClass("circle");
            $(this).addClass("circle");
	    });


        $(".send_form_cont input").on("focus", function() {
            $(this).css({"input[placeholder]": "#fff"});
        });

		$(".much-color").on("click",function(){
			
			$(this).css({"background":"#000"});
		});

    });