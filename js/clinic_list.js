mb.doctorList = (function(){
// Поиск вверху на списке выезжалка
    var search_top = function(){
        $("body").on("click", ".search_content_section .right_box", function(){
            if($(this).hasClass("cl")){
                $(this).removeClass("cl");
                $(".popup_search").css({"top":"-100%"}).removeClass("active");
                $(".clinic_list .content, .header_mini, .network_list .content").css({"top": "0"});
            }
            else{
                console.log("sdfg")
                $(this).addClass("cl");
                $(".popup_search").css({"top":"0"});
                $(".clinic_list .content, .header_mini, .network_list .content").css({"top": $(".popup_search").height()});
            }
        }); 
// Выезжалка на прокрутке
//         $(window).on("touchmove",function(){
//             console.log("touchmove")
//            if($(window).scrollTop() === 0){
//                $(".popup_search").css({"top":"0"}).addClass("active");
//                $(".clinic_list .content, .header_mini").css({"top": $(".popup_search").height()});
//            }
//        });
//        $(window).on("touchmove", function(){
//            if($(window).scrollTop() > $(".popup_search").height()  && $(".popup_search").hasClass("active")){
//                $(".popup_search").css({"top":"-100%"}).removeClass("active");
//                $(".clinic_list .content, .header_mini").css({"top": "0"});
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
// Accordion на NETWORK
    var network_accordion = mb.network_accordion;

    var init = function(){
        search_top();
        map_slide();
        network_accordion();
  
    }
    return {
        init: init,
    }
}());
$(function(){
    mb.doctorList.init();
});
