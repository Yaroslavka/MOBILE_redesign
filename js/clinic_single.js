mb.clinicSingle = (function(){
// Сворачивает и фиксит верхний блок при прокрутке
    var hideFix = mb.hideFix;
    var scrollHide = function(){
        $(window).on("scroll", function(){
           hideFix({
               wrapper : $(".page"),
               point: $(".clinic_tabs"),
               add_class: "clinic_single_hide",
               clone_el: $(".column_btn_doctor")
           });
        });
    }
    var heightServiceTable = function(){
        $(".service-clinic-table .sort-clinic .row").each(function(){
            $(this).find(".cell, .cell a,  .cell p").height($(this).height());
        });
        $("body").on("click", ".sort_table_wrapper a", function(){
           if($(this).hasClass("active")){
               $(this).removeClass("active");
               $(".sort_table_items").hide();
           } else{
               $(this).addClass("active");
                 $(".sort_table_items").show();
               $("<a class='overlay' href='#'></a>").appendTo("body");
           }
            return false;
        });
    }
    var network_table_rate = function(){
        $("body").on("click", ".title_row .clinic-rate", function(){
            if($(this).find("span").hasClass("desc")){
                $(this).find("span").removeClass("desc");
                $(this).find("span").addClass("asc");
            }
            else{
                $(this).find("span").removeClass("asc");
                $(this).find("span").addClass("desc"); 
            }
        });
    }
    var network_table_accordeon = function(){
        $("body").on("click", ".sort-clinic .clinic-name a", function(){
            if($(this).parents(".row").hasClass("active")){
                $(this).parents(".row").removeClass("active").find(".special-wrapper").slideUp(400);
               
            }
            else{
                $(".sort-clinic .row").removeClass("active");
                $(".special-wrapper").slideUp(400);
                if(!$(this).parents(".row").find(".special-wrapper .similar_doctors_item .left img").length){
                    for(var i = 0; i < $(this).parents(".row").find(".similar_doctors_item").length; i++){
                        $(this).parents(".row").find(".special-wrapper .similar_doctors_item").eq(i).find(".left a").append("<img src="+$(this).parents('.row').find('.special-wrapper .similar_doctors_item').eq(i).find(".left").attr('data-img')+" >");
                    }   
                }
                $(this).parents(".row").addClass("active").find(".special-wrapper").slideDown(400);
            }
            
            return false;
        });   
    }
    // Карта выезжалка
    var map_slide = function(){
        $("body").on("click", ".beside_subway_wrapper", function(){
                if($(this).parents(".similar_clinic_wrapper").length){
                    console.log($(this))
                    return false;
                }
                $($('.map-link').not($(this))).removeClass('active-map-link');
                var map = $(this).siblings('.map');
                map.find('.ymaps-map').remove();
                eel = map.find('.yandex-map-list')
                ymaps.ready(initSingle);
      
            var that = $(this).parents(".doctor_address_map");
            if(that.hasClass("active")){
                that.removeClass("active");
                that.find(".map").slideUp(400);
            }
            else{
                $(".map").slideUp(400);
                that.addClass("active");
                that.find(".map").slideDown(400);
            }
        });
    }
    // Табы
    var tabs = function(){
        $("body").on("click", ".clinic_tabs span", function(){
            var tab_class = $(this).attr("class").split(" ")[0],
                is_active = $(this).attr("class").split(" ")[1];
        // Добавление активов
            if($(this).hasClass("active")){
                $(this).removeClass("active");
            } 
            else{
                $(".clinic_tabs span").removeClass("active");
                $(this).addClass("active");
            }
            
       // Логика вывода блоков        
        switch (tab_class) {
          case "tab_info":
                if(!is_active){
                    $(".tab_content").height($(".info_clinic").height());
                    $(".info_clinic").show().animate({"left":"0"},400, function(){
                        $(".hide_block").hide();
                    });
                    $(".reviews_clinic_wrapper").animate({"right":"-103%"},400, function(){$(this).hide()});
                    $(".doc_ico").addClass("closeTab").find("a").hide();
                    $(".close_tab").css({"display":"block"});
                }
                else{
                    $(".tab_content").height(0);
                    $(".hide_block").show();
                    $(".info_clinic").animate({"left":"-100%"},400, function(){$(this).hide()});
                    $(".doc_ico").removeClass("closeTab").find("a").show();
                    $(".close_tab").hide();
                }
            break
              case "tab_review":
                    if(!is_active){
                        $(".tab_content").height($(".reviews_clinic_wrapper").height());
                        $(".reviews_clinic_wrapper").show().animate({"right":"0"},400, function(){
                            $(".hide_block").hide();
                        });
                        $(".info_clinic").animate({"left":"-100%"},400, function(){$(this).hide()});
                        $(".doc_ico").addClass("closeTab");
                        $(".doc_ico").addClass("closeTab").find("a").hide(); 
                        $(".close_tab").css({"display":"block"});
                    }
                    else{
                        $(".tab_content").height(0);
                        $(".hide_block").show();
                        $(".reviews_clinic_wrapper").animate({"right":"-103%"},400, function(){$(this).hide()});
                        $(".doc_ico").removeClass("closeTab");
                        $(".doc_ico").removeClass("closeTab").find("a").show();
                        $(".close_tab").hide();
                    }
                break
               default:

            }
        });
        $("body").on("click", ".close_tab", function(){
            $(".clinic_tabs .active").trigger("click")
            return false;
        });
    }
    var select_specialist = function(){
        $("body").on("click", ".select_specialist", function(){
            $("<a href='#' class='overlay'></a>").appendTo("body");
            $(".page").addClass("fix");
               $("#clinic-spec").show();
                var count_items = $(".items_specialist_list_check li").length,
                count_height = $(".items_specialist_list_check li:first").height();
             $("#clinic-spec").height(count_items * count_height - 10);
          return false;
        });  
        $("body").on("click", ".btn_wrap_spec a", function(){
            $(".page").removeClass("fix");
            $("#clinic-spec").hide();
            $(".overlay").remove();
             $('html, body').stop().animate({scrollTop: $(".doctor_items_conrainer").offset().top - 50  }, 1000);
            return false;
        });
    };
    var cooment = function(){
        $('body').on('click', '.more', function() {
            var href = $(this).attr("href");
            $.ajax({
                type: 'post',
                url: href,
                dataType: 'html',
                success: function(data) {
                    if (data) {
                        $(".reviews_clinic_items_wrapper").html(data);
                        $(".tab_content").height($(".reviews_clinic_wrapper").height());
                    }
                }
            });
            return false;
        });
    }
    // Accordion на NETWORK
    var network_accordion = mb.network_accordion;
    var init = function(){
        scrollHide();
        heightServiceTable();
        network_table_rate();
        network_table_accordeon();
        network_accordion();
        map_slide();
        tabs();
        select_specialist();
        cooment();
    }
    return {
        init: init
    }
}());
$(function(){
    mb.clinicSingle.init();
});

