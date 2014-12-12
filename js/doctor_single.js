mb.doctorSingle = (function(){
   // Сворачивает и фиксит верхний блок при прокрутке
    var hideFix = mb.hideFix; 
    var scrollHide = function(){
        $(window).on("scroll", function(){
           hideFix({
               wrapper : $(".page"),
               point: $(".wrapper_doctor_address_map"),
               add_class: "clinic_single_hide",
               clone_el: $(".column_btn_doctor")
           });
        });
    }
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
// Динамический рейтинг
    var rate_dinamic = function(){
       $(".doctor_wrapper .rate_box .desc span").animate({"height": ($('.doctor_wrapper .count').text() * 10) + "%"}, 1500);
        $(".similar_doctors_item").each(function(){
           $(this).find(".rate_box .desc span").animate({"height": ($(this).find('.count').text() * 10) + "%"}, 1500);
        });
    }
    var init = function(){
        map_slide();
        rate_dinamic();
    }
    return {
        init: init
    }
}());
$(function(){
    mb.doctorSingle.init();
});