mb.doctorList = (function(){
// Поиск вверху на списке выезжалка
    var search_top = function(){
        $(".top_box_select_category .top_description").append($(".search_content_section .title").clone());
        $("body").on("click", ".search_content_section .left_box", function(){  
            $(".search_content_section").hide();
            $(".popup_search").css({"top": $(".header_mini").height()});
            $(".clinic_list .content, .network_list .content").css({"top": $(".popup_search").height()});
        });
        $("body").on("click", ".search_section .close", function(){
            $(".search_content_section").show();
            $(".popup_search").css({"top":"-100%"});
            $(".clinic_list .content, .header_mini, .network_list .content").css({"top": "0"});
        });
        $("body").on("click", ".overlay", function(){
            $(".popup_search").animate({"top":"-100%"},400, function(){
                $(".overlay").hide();
            });
        });
        $("body").on("click", ".select_category", function(){
            $(".menu_specialist_wrapper").show().animate({"left":"0"},400);
            $(".all_specialist").find("span").text("Назад");
            $(".all_specialist").removeClass("all_specialist").addClass("back_all_specialist");
            mb.letter_search();
            $(this).find(":text").blur();
        });
        $("body").on("click", ".select_subway", function(){
            $(".menu_subway_wrapper").show().animate({"left":"0", "right":"auto"},400);
            $(".all_specialist").find("span").text("Назад");
            $(".back_all_specialist").removeClass("back_all_specialist").addClass("back_all_subway");
            mb.letter_search();
            $(this).find(":text").blur();
        });
    }
// Карта выезжалка
    var map_slide = function(){
        $("body").on("click", ".beside_subway_wrapper", function(){
                $($('.map-link').not($(this))).removeClass('active-map-link');
                var map = $(this).parents(".clinic_item").find('.map');
                map.find('.ymaps-map').remove();
                eel = map.find('.yandex-map-list')
                ymaps.ready(initSingle);
            var that = $(this);
            if(that.hasClass("active")){
                that.removeClass("active");
                that.parents(".clinic_item").find('.map').slideUp(400);
            }
            else{
                $(".map").slideUp(400);
                that.addClass("active");
                that.parents(".clinic_item").find('.map').slideDown(400);
            }
        });
    }
    // Динамический рейтинг
    var rate_dinamic = function(){
        var color = {
            red: ['#b51a1a', '#f7dddd'],
            orange: ['#ffa422', '#ffe1b7'],
            blue: ['#6589ae', '#d9e3ec']
        }
				// :not(.similar_clinic_wrapper)
        $(".clinic_item").each(function(){
            if(7 < $(this).find('.count').text()){
                $(this).find('.count').css({'color':color.blue[0]});
                $(this).find(".rate_box .desc  span").css({'background':color.blue[0]});
                $(this).find(".rate_box .desc").css({'background':color.blue[1]});
            }else if(4 < $(this).find('.count').text()){
                $(this).find('.count').css({'color':color.orange[0]});
                $(this).find(".rate_box .desc  span").css({'background':color.orange[0]});
                $(this).find(".rate_box .desc").css({'background':color.orange[1]});
            }else{
                $(this).find('.count').css({'color':color.red[0]});
                $(this).find(".rate_box .desc  span").css({'background':color.red[0]});
                $(this).find(".rate_box .desc").css({'background':color.red[1]});
            }
               $(this).find(".rate_box .desc span").animate({"height": ($(this).find('.count').text() * 10) + "%"}, 1500);
        });
    }
// Accordion на NETWORK
    var network_accordion = mb.network_accordion;

    var init = function(){
        search_top();
        map_slide();
        network_accordion();
        rate_dinamic();
    }
    return {
        init: init,
    }
}());
$(function(){
    mb.doctorList.init();
});
