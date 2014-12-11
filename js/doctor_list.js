mb.doctorList = (function(){
// Поиск вверху на списке выезжалка
    var search_top = function(){
        $("body").on("click", ".search_content_section .right_box", function(){
            if($(this).hasClass("cl")){
                $(this).removeClass("cl");
                $(".popup_search").css({"top":"-100%"}).removeClass("active");
                $(".doctor_list .content, .header_mini").css({"top": "0"});
            }
            else{
                $(this).addClass("cl");
                $(".popup_search").css({"top":"0"});
                  $(".doctor_list .content, .header_mini").css({"top": $(".popup_search").height()});
            }
        });
        $("body").on("click", ".overlay", function(){
            $(".popup_search").animate({"top":"-100%"},400, function(){
                $(".overlay").hide();
            });
        });
// Выезжалка на прокрутке
//        $(window).on("touchmove",function(){
//            if($(window).scrollTop() === 0){
//
//                $(".popup_search").css({"top":"0"}).addClass("active");
//                $(".doctor_list .content, .header_mini").css({"top": $(".popup_search").height()});
//            }
//        });
//        $(window).on("touchmove", function(){
//            if($(window).scrollTop() > $(".popup_search").height()  && $(".popup_search").hasClass("active")){
//                $(".popup_search").css({"top":"-100%"}).removeClass("active");
//                $(".doctor_list .content, .header_mini").css({"top": "0"});
//
//            }
//        });
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
                var map = $(this).parents(".doctor_address_map").find('.map');
                map.find('.ymaps-map').remove();
                eel = map.find('.yandex-map-list')
                ymaps.ready(initSingle);
      
            var that = $(this);
            if(that.hasClass("active")){
                that.removeClass("active");
                that.siblings(".map").slideUp(400);
            }
            else{
                $(".map").slideUp(400);
                that.addClass("active");
                that.siblings(".map").slideDown(400);
            }
        });
    }
// Вывод определенного количества станций метро
    var count_subway = function(count){
        $(".beside_subway_wrapper").each(function(){
            $(this).find(".beside_subway").eq(count).nextAll().hide();
        });
    }
// Динамический рейтинг
    var rate_dinamic = function(){
        $(".doctor_item").each(function(){
           $(this).find(".rate_box .desc span").animate({"height": ($(this).find('.count').text() * 10) + "%"}, 1500);
        });
    }
    var init = function(){
        search_top();
        map_slide();
        count_subway(2);
        rate_dinamic();
    }
    return {
        init: init
    }
}());
$(function(){
    mb.doctorList.init();
});