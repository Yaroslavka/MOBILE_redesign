var SubwayList = [];
var myMap = [];
var myPlacemark = [];
var myCollection = [];
var eel;
function initSingle() {
    var el = eel
    var lat = $(el).attr('data-lat');
    var lng = $(el).attr('data-lng');
    var div = $(el).attr('id');
    myMap = new ymaps.Map(div, {center: [lat, lng], zoom: 15});
    options = {
    };
    myPlacemark = new ymaps.Placemark([lat, lng], {}, options);
    myCollection = new ymaps.GeoObjectCollection({});
    myCollection.add(myPlacemark);
    myMap.geoObjects.add(myCollection);
    myMap.behaviors.disable('drag');  
}
var mb = (function(){
var flag_fixed = true
// определение моб браузера
    var mobile_test = function(){
    var isMobile = {
            Android: function() {
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function() {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function() {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function() {
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function() {
                return navigator.userAgent.match(/IEMobile/i);
            },
            any: function() {
                return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
            }
        };
        if(isMobile.any()){
            //alert("wer")
        }
        if(isMobile.iOS()){
            //alert("IOS");
        }
        if(isMobile.Android()){
            //alert("Android");
        }
    }
    var  iphone_mobile = function(){
//        var isiPhone = navigator.userAgent.toLowerCase().indexOf("iphone");
//        if (isiPhone > -1) {
//          var list = ["doctor", "search_doctor", "search_clinic", "clinic", "blog", "services", "illness"],
//              pathName = window.location.pathname.split("/")[1],
//              i = 0;
//            for(; i < list.length; i++){
//                if(list[i] === pathName || window.location.href === "http://medbooking.com/"){
//                    window.location.host = "m." + window.location.host
//                }
//            }
//        }
    }
// Все спецциализации <-
    var all_specialist = function(){
        // Добавление название ссылки
        $(".body_main .all_specialist>span").text("Специализации");
        $("body").on("touchstart", ".all_specialist", function() {
            if($("body").hasClass("body_main")){
                $(".menu_specialist_wrapper").show().animate({"right":"0"},400, function(){
                     $(".page").hide();
                });
                $(this).find("span").text("Назад");
                $(this).removeClass("all_specialist").addClass("back_all_specialist");
                letter_search();
            }
            else{
               console.log("click")
                $(".menu_specialist_wrapper").show().animate({"left":"0"},400, function(){
                    $(".page").hide();
                });
                
                $(this).find("span").text("Назад");
                $(this).removeClass("all_specialist").addClass("back_all_specialist");
                letter_search();
            }    
            return false;
        });
        //
       // Назад
      // 
        $("body").on("touchstart", ".back_all_specialist", function(){
            $(".page").show();
          if($("body").hasClass("body_main")){
              if($(".menu_subway_wrapper").css("display") === "block"){
                $(".menu_subway_wrapper").animate({"right":"-100%"}, 400, function(){
                    $(this).hide();
                });
              }
              else{
                  $(".menu_specialist_wrapper").animate({"right":"-100%"}, 400, function(){
                    $(this).hide();
                });
              }

          }
          else{
            $(".menu_specialist_wrapper").animate({"left":"-100%"}, 400, function(){
                $(this).hide();
            });
          }
           $(this).removeClass("back_all_specialist").addClass("all_specialist").find("span").text("Специальности");
        });
        // Назад на всплывахе метро 
        $("body").on("touchstart", ".back_all_subway", function(){
            $(".page").show();
            $(".menu_subway_wrapper").animate({"left":"-100%"}, 400, function(){
                $(this).hide();
          });
           $(this).removeClass("back_all_subway").addClass("all_specialist").find("span").text("Специальности");
        });
        //
       // На Главной
      // 
        $("body").on("click", ".select_category", function(){
            $(".menu_specialist_wrapper").show().animate({"right":"0"},400);
            $(".all_specialist").find("span").text("Назад");
            $(".all_specialist").removeClass("all_specialist").addClass("back_all_specialist");
            $(".menu_specialist_wrapper .items-modal-list").focus();
            letter_search();
            $(this).find(":text").blur();
        });
        $("body").on("click", ".select_subway", function(){
            $(".menu_subway_wrapper").show().animate({"right":"0"},400);
            $(".all_specialist").find("span").text("Назад");
            $(".all_specialist").removeClass("all_specialist").addClass("back_all_specialist");
            $(".menu_subway_wrapper .items-modal-list").focus();
            letter_search();
            $(this).find(":text").blur();
        });
    }
// Меню ->
    var menu = function(){
        $('body').on("click",".menu_footer a", function() {
            var that = $(".menu_footer");
            $(".menu_nav_wrapper").height($(document).height());
            if(that.hasClass("active")){
                $(".overlay_menu").remove();
                that.removeClass("active");
                $("html, body").removeClass("ovh");
                // Главная
                if(that.hasClass("main_menu_footer")){
                    $(".menu_nav_wrapper").animate({"left":"-80%"},200, function(){
                        $(".menu_nav_wrapper").hide();
                    });
                    $(".page, .footer").animate({"left":"0"},250, function(){
                        $("html, body").removeClass("ovh");
                    });
                }
                else{
                    $(".header_mini, .search_content_section, .menu_wrapper_bottom").removeClass("active");
                    $(".menu_nav_wrapper").animate({"right":"-80%"},200, function(){
                        $(".menu_nav_wrapper").hide();
                    });
                    $(".page, .footer").animate({"left":"0"},250);
                }
            }
            else{
                 $("<a href='#' class='overlay_menu'></a>").appendTo("body")
                // Главная
                if(that.hasClass("main_menu_footer")){
                    $(".menu_nav_wrapper, .menu_nav_wrapper ul").css({'top':'0'});
                    that.addClass("active");
                    $(".footer").addClass('index_layer');
                    $(".menu_nav_wrapper").show();
                    $(".menu_nav_wrapper").animate({"left":"0"},200);
                    $(".footer, .page").animate({"left":"80%"},200);
                    $("html, body").addClass("ovh");
                }
                else{
                    $(".header_mini, .search_content_section, .menu_wrapper_bottom").addClass("active");
                    that.addClass("active");
                    $("html, body").addClass("ovh");
                    $(".menu_nav_wrapper").show();
                    $(".menu_nav_wrapper").animate({"right":"0"},200);
                    $(".page, .footer").animate({"left":"-80%"},200);
                }
            }
            return false;
        });
        $("body").on("click", ".overlay_menu", function(){
            $(".overlay_menu").remove();
            $("html, body").removeClass("ovh");
                $(".menu_footer").removeClass("active");
                // Главная
                if($(".menu_footer").hasClass("main_menu_footer")){
                    $(".menu_nav_wrapper").animate({"left":"-80%"},200, function(){
                        $(".menu_nav_wrapper").hide();
                    });
                    $(".page, .footer").animate({"left":"0"},250, function(){
                        $("html, body").removeClass("ovh");
                    });
                }
                else{
                    $(".menu_nav_wrapper").animate({"right":"-80%"},200, function(){
                        $(".menu_nav_wrapper").hide();
												$(".search_content_section, .header_mini").removeClass("active");
                    });
                    $(".page, .footer").animate({"left":"0"},250);
                }
        });
    }
// Рейтинг
    var rate = function(){
        $("body").on("click", ".sort_container li", function(){
           $(".sort_container li").removeClass("active");
            $(this).addClass("active");
        });
    }
// Сворачивает и фиксит верхний блок при прокрутке (используеться на станице клинники и доктора)
    var hideFix = function(setting){
        if($(window).scrollTop() >= setting.point.offset().top){
            setting.wrapper.addClass(setting.add_class);
            if(flag_fixed === true && !$(".fixed_top").length){
                $("<div class='fixed_top animated fadeInDown'><div class='call_btn'><a href='tel:+7 (499) 705-35-35'></a></div></div>").prependTo("body"); 
                var header = null;//$(".header_mini").clone();
                var top_doc = setting.clone_el.clone();
                $(".fixed_top").append(header, top_doc);
 
            }else{
                $(".fixed_top").removeClass("fadeOutUp").addClass("fadeInDown");
            }
            return flag_fixed = false;
        }
        else{
            $(".fixed_top").addClass("fadeOutUp");
            setting.wrapper.removeClass(setting.add_class);
            return flag_fixed = true;
        }
    }
// Accordion на NETWORK
    var network_accordion = function(){
        $("body").on("click", ".network_accordion_wrapper .network_panel", function(){
           if($(this).hasClass("active")){
               $(this).removeClass("active");
               $(this).siblings(".network_list_wrapper").slideUp(400);
           }
            else{
                $(this).addClass("active");
                $(this).siblings(".network_list_wrapper").slideDown(400);
            }
        });
    }
// Меню доктора клинники
    var doctors_clinic_menu = function(){
//        $("body").on("touchstart", ".doctors_clinic", function(){
//           $(".content").animate({"left":"100%"}, 400, function(){
//               $(this).hide();
//           });
//            $(".fixed_top").hide();  //Прячем всплываху
//           $(".doctor_items_wrapper_menu").show().animate({"left":"0%"},400);
//            $(".footer .menu_footer a").remove();
//            $(".footer .menu_footer").append("<span class='back'></span>")
//            $(this).removeClass("doctors_clinic")
//                    .addClass("all_specialist_check")
//                    .find("span").text("Специализации");
//        });
    }
// Back footer
    var back_btn = function(){
        $("body").on("touchstart",".footer .back", function(){
            $(".doctor_items_wrapper_menu").animate({"left":"-100%"}, 400, function(){
               $(this).hide();
           });
            $(".fixed_top").show(); //Прячем всплываху
            $(".content").show().animate({"left":"0%"},400);
            $(".menu_footer .back").remove();
            $(".menu_footer").append("<a class='menu_footer_link' href='#nav-panel'></a>")
            $(".all_specialist_check").removeClass("all_specialist_check")
                    .addClass("doctors_clinic")
                    .find("span").text("Доктора клиники");
           return false;
        })
        $("body").on("touchstart",".footer .back_two", function(){
            $(".items_specialist_list_check").animate({"left":"-100%"}, 400, function(){
               $(this).hide();
           });            
            $(".doctor_items_wrapper_menu").animate({"left":"-100%"}, 400, function(){
               $(this).hide();
           }).removeClass("fix");
            $(".content").show().animate({"left":"0%"},400);       
            $(".menu_footer .back_two").remove();
            $(".menu_footer").append("<a class='menu_footer_link' href='#nav-panel'></a>")
            $(".back_check_menu").removeClass("back_check_menu")
                    .addClass("doctors_clinic")
                    .find("span").text("Доктора клиники");
           return false;
        })
    }
    var check_menu = function(){
        $("body").on("touchstart", ".all_specialist_check", function(){
            $(".doctor_items_wrapper_menu").animate({"left":"80%"}, 400, function(){
               $(this).addClass("fix");
            });
            $(".items_specialist_list_check").show().animate({"left":"0"},400);
            $(".menu").hide();
            $(this).removeClass("all_specialist_check").addClass("back_check_menu")
                .find("span").text("Назад");
            $(".footer .back").addClass("back_two").removeClass("back");
        });
    }
    var back_check_menu = function(){
        $("body").on("touchstart",".back_check_menu", function(){
            $(".items_specialist_list_check").animate({"left":"-100%"},400, function(){
                $(this).hide();
            });
            $(".doctor_items_wrapper_menu").show().animate({"left":"0"}, 500, function(){
                $(this).removeClass("fix");
            });
            $(this).removeClass("back_check_menu").addClass("all_specialist_check")
                    .find("span").text("Специализации");
            $(".menu").show();
        });
    }
    var check_list_network = function(){
        $("body").on("click", ".items_specialist_list_check a", function(){    
            if($(this).parent().hasClass("active")){
                $(this).parent().removeClass("active");
                $(this).siblings("input").prop('checked', false);
            $.ajax({
                url: $("#clinic-spec").attr('action'),
                data: $("#clinic-spec").serialize(),
                type: 'post',
                success: function(r) {
                    if (r) {
                     $(".doctor_items_wrapper").html(r);
                    }
                }
            });
            }
            else{
                $(this).parent().addClass("active");
                $(this).siblings("input").prop('checked', true);
            $.ajax({
                url: $("#clinic-spec").attr('action'),
                data: $("#clinic-spec").serialize(),
                type: 'post',
                success: function(r) {
                    if (r) {
                     $(".doctor_items_wrapper").html(r);
                         $(".title_box .count").text($(".count-doctor").text());
                        console.log(r)
                    }
                }
            });
                 $(".title_box .count").text($(".count-doctor").text());
            }
           
            return false;
        });
    }
    var order_form = function(){
    // Появление order
        $("body").on("click", ".order_btn, .order_home_btn", function(){
            var tmp = "<script id='mask' src='"+window.location.origin+"/themes/m/js/mask.js'></script>";
            if(!$("#mask").length){
                $("html head").append(tmp);
                $(".phone_text input").mask("(999)999.99.99",{placeholder: "—"})
            }
            if($(this).hasClass("order_home_btn")){
                $(".order_wrapper .title_box").hide();
                $(".order_wrapper .title_box_home").show();
                $(".order_wrapper").show().animate({"top":$(".header_mini").height()}, 400); 
            }
            else{
                $(".order_wrapper .title_box").show();
                $(".order_wrapper .title_box_home").hide();
                    if($(".page").hasClass("doctor_single")){
                        $("body").addClass("fix");
                        $(".order_wrapper .img_wrapper img").attr('src', $('.doctor_wrapper img').attr('src'));
                        $(".order_wrapper .doctor_order_name_first").text($(".doctor_wrapper .name").text());
                        $(".order_wrapper .doctor_order_name_last").text($(".doctor_wrapper .second_name").text());
                        $(".order_wrapper .doctor_category").text($(".top_doctor_single .doctor_category").text());
                        $(".order_wrapper .clinic_name span").text($(".doctor_snippet_clinic .clinic_name span").text());
                        $(".order_wrapper .address_doctor").text($(".doctor_snippet_clinic .address_doctor span").text());
                        $(".order_wrapper").show().animate({"top":$(".header_mini").height()}, 400); 
                    }
                    else if($(".page").hasClass("clinic_single")){
                        $(".order_wrapper .doctor_order_name").text($(this).attr("data-clinic_name"));
                        $(".order_wrapper .title_box span").text("на прием в клиннику");
                        $(".order_wrapper").show().animate({"top":$(".header_mini").height()}, 400); 
                    }
            }
        });
    // Закрытие формы
//        $("body").on("touchstart", ".order_wrapper .close", function(){
//           $(".order_wrapper_exit").show().animate({"top":"0"}, 400); 
//        });
    // zaeb_form 
//        $("body").on("touchstart", ".order_wrapper_exit .close , .btn_no", function(){
//           $(".order_wrapper_exit").animate({"top":"100%"}, 400, function(){
//               $(this).hide();
//           }); 
//        });
    // full_exit_form
        $("body").on("click", ".order_wrapper .close", function(){
            $("body").removeClass("fix");
            $(".order_wrapper").animate({"top":"100%"}, 400, function(){
               $(this).hide();
           }); 
        });
    // exit_succes
        $("body").on("click", ".success_order .close", function(){
            $(".success_order").animate({"top":"100%"}, 400, function(){
               $(this).hide();
           }); 
        });
    }
    var letter_search = function(){
        $('body').on('keyup', '.menu_specialist_wrapper input[type=text], .menu_subway_wrapper input[type=text]', function(e) {
            var fakeStr = $(this).val();
            $('.items-modal-list a').each(function(elm, indx) {
                var inputText = $(this).text();
                if ( inputText.toLowerCase().indexOf(fakeStr.toLowerCase()) == -1) {
                    $(this).parent().addClass('hidden');
                }else{
                    $(this).parent().removeClass('hidden');
                }
            });
        });
    }
// sort_select
    var sort_select = function(){
        $("body").on("click", ".sort_title", function(){
            $("<a href='#' class='overlay'></a>").appendTo("body");
            var list = $(".sort_container ul").remove();
            $("body").prepend(list);
            $(".page").addClass("fix");
            $(".sort_list").show();
        });
        $("body").on("click", ".overlay", function(){
           $(this).remove();
            $(".sort_table_items").hide();
            $(".page").removeClass("fix");
            if($(".sort_list").css('display') === 'block'){
                $(".sort_list").hide();
            }
            if($("#clinic-spec").css('display') === 'block'){
                $("#clinic-spec").hide();
            }
        });
    }
// record form
    var record = function(){
            $('body').on('click', '.btn_send :submit', function () {
              
                if($(".page").hasClass("doctor_single")){
                        $("#sms").val(1);
                        $("#AApiRecord_clinic_id").val($(".doctor_snippet_clinic:first").attr('data-clinic_id'));
                        $("#AApiRecord_clinic_name").val($(".doctor_snippet_clinic:first").attr('data-clinic_name'));
                        $("#AApiRecord_doctor_id").val($(".column_btn_doctor .order_btn").attr('data-doctor_id'));
                        $("#AApiRecord_doctor_name").val($(".order_btn").attr('data-doctor_name'));
                }
                else if($(".page").hasClass("clinic_single")){
                    $("#AApiRecord_doctor_id, #AApiRecord_doctor_name").remove();
                    $("#sms").val(1);
                    $("#AApiRecord_clinic_id").val($(".column_btn_doctor .order_btn").attr('data-clinic_id'));
                    $("#AApiRecord_clinic_name").val($(".column_btn_doctor .order_btn").attr('data-clinic_name'));
                }

                if(!$(".phone_text :text").val()){
                    $(".error").show();
                }else{
                    $.ajax({
                        url: "http://api.medbooking.com/send",
                        type: 'post',
                        crossDomain: true,
                        data: $(this).parents('form').serialize(),
                        success: function (r) {
                            if (r) {
                               $(".success_order").show().animate({"top":"0"}, 400);
                            }
                        }
                    });
                    setTimeout(function(){
                        $(".success_order").show().animate({"top":"100%"}, 400, function(){
                            $(this).hide();
                            $(".order_wrapper").animate({"top":"100%"}, 400, function(){
               $(this).hide();  
           }); 
                        });

                    }, 3000);
                }
                return false;
            });
    }
    var  exchange_content= function(){
        if($(".phone").length){
            $(".phone").attr("href", "tel:+7 (499) 705-39-99");
        }
    }
    var mb_redirect = function(){
        function setCookie(name, value, options) {
          options = options || {};

          var expires = options.expires;

          if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires*1000);
            expires = options.expires = d;
          }
          if (expires && expires.toUTCString) { 
            options.expires = expires.toUTCString();
          }

          value = encodeURIComponent(value);

          var updatedCookie = name + "=" + value;

          for(var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];    
            if (propValue !== true) { 
              updatedCookie += "=" + propValue;
             }
          }

          document.cookie = updatedCookie;
        }

        $("body").on("click", ".main .bottom a", function(){
           setCookie("mobilka", "1", { expires: 0, domain: ".medbooking.com" });
        });
    }
    
    var init = function(){
        //mobile_test();
        //iphone_mobile();
        exchange_content();
        mb_redirect();
        menu();
        all_specialist();
        
        rate();
        doctors_clinic_menu();
        back_btn();
        check_menu();
        back_check_menu();
        check_list_network();
        
        order_form();
        sort_select();
        letter_search();
        record();
        
    }
    return {
        init: init,
        hideFix: hideFix,
        network_accordion:network_accordion,
    }
}());
$(function(){
   mb.init(); 
});