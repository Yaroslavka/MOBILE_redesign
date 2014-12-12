mb.doctorList = (function(){
// Поиск вверху на списке выезжалка
    var search_top = function(){
        $(".top_box_select_category .top_description").append($(".search_content_section .title").clone());
        $("body").on("click", ".search_content_section .left_box", function(){  
            $(".search_content_section").hide();
            $(".popup_search").css({"top": $(".header_mini").height()});
            $(".doctor_list .content").css({"top": $(".popup_search").height()});
        });
        $("body").on("click", ".search_section .close", function(){
            $(".search_content_section").show();
            $(".popup_search").css({"top":"-100%"});
            $(".doctor_list .content, .header_mini").css({"top": "0"});
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
// динамический пейджер
    var pager_dinamic = function(){
    $(window).on("scroll", function(){
        if (($(window).innerHeight() + $(window).scrollTop()) >= $(document).height()){
            window.location.href = $(".paginator li.selected").next().find("a").attr("href");
        }
    });
    }
    var init = function(){
        search_top();
        map_slide();
        count_subway(2);
        rate_dinamic();
        pager_dinamic();
    }
    return {
        init: init
    }
}());
$(function(){
    mb.doctorList.init();
   
});