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
                eel = $('.yandex-map-list');
                ymaps.ready(initSingle);
                $("body").on('click', '.map', function(){
                    $(".close_wrapper_map").show();
                    $('.yandex-map-list').hide();    
                    $('#new_map').show();   
                    $(".clinic_items_wrapper .clinic_item .map").height(290)
                    eel = $(this).find('#new_map').css({'height':'40rem'});
                    console.log(eel);
                    ymaps.ready(initSingle);
                    $(".ymaps-map, .ymaps-glass, .yandex-map-list, .ymaps-glass-pane").addClass("active_map");
                });
                $("body").on("click", ".close_wrapper_map", function(){
                    $(".close_wrapper_map").hide();
                    $('#new_map').hide();    
                    $(".map").css({'height':'auto'});
                    eel = $('.yandex-map-list').show();
                    ymaps.ready(initSingle);
                    $(".ymaps-map, .ymaps-glass, .yandex-map-list, .ymaps-glass-pane").removeClass("active_map");
                })
    }
    // Табы
    var tabs = function(){
        $("body").on("click", ".clinic_tabs span", function(){
            $(".clinic_tabs span").removeClass("active");
            $(this).addClass("active");
            $('.tab_box').hide();
            $('.' + $(this).attr('data-content-tab')).show().addClass('animated fadeInLeft');
        });
    }
    var select_specialist = function(){
        $("body").on("click", ".select_specialist", function(){
            $("<a href='#' class='overlay'></a>").appendTo("body");
            $(".page").addClass("fix");
               $("#clinic-spec").show();
                var count_items = $(".items_specialist_list_check li").length,
                count_height = $(".items_specialist_list_check li:first").height();
             //$("#clinic-spec").height(count_items * count_height - 10);
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
    // Динамический рейтинг
    var rate_dinamic = function(){
        var color = {
            red: ['#b51a1a', '#f7dddd'],
            orange: ['#ffa422', '#ffe1b7'],
            blue: ['#6589ae', '#d9e3ec']
        }
        if(7 < $('.top_clinic_single .count').text()){
            $('.top_clinic_single .count').css({'color':color.blue[0]});
            $(".top_clinic_single .rate_box .desc  span").css({'background':color.blue[0]});
            $(".top_clinic_single .rate_box .desc").css({'color':color.blue[1]});
        }else if(4 < $('.doctor_wrapper .count').text()){
            $('.top_clinic_single .count').css({'color':color.orange[0]});
            $(".top_clinic_single .rate_box .desc  span").css({'background':color.orange[0]});
            $(".top_clinic_single .rate_box .desc").css({'background':color.orange[1]});
        }else{
            $('.top_clinic_single .count').css({'color':color.red[0]});
            $(".top_clinic_single .rate_box .desc  span").css({'background':color.red[0]});
            $(".top_clinic_single .rate_box .desc").css({'background':color.red[1]});
        }
        $(".top_clinic_single .rate_box .desc span").animate({"height": ($('.top_clinic_single .count').text() * 10) + "%"}, 1500);
        $(".doctor_items_wrapper_menu .doctor_item").each(function(){
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
        
        
        
        
        
        $(".similar_doctors_items .similar_doctors_item").each(function(){
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
        scrollHide();
        heightServiceTable();
        network_table_rate();
        network_table_accordeon();
        network_accordion();
        map_slide();
        tabs();
        select_specialist();
        cooment();
        rate_dinamic();
    }
    return {
        init: init
    }
}());
$(function(){
    mb.clinicSingle.init();
});

