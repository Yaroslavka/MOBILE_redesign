mb.blog = (function(){
    var footer_menu = function(){
        $("body").on("click", ".theme_blog_wrap a", function(){
            if  ( $(this).hasClass("active") ){
                $(".content").show();
                $(this).removeClass("active");
                $(".menu_specialist_wrapper").css({"left":"100%"}).hide();
            }
            else{
                $(".content").hide();
                $(this).addClass("active");
                $(".menu_specialist_wrapper").show().css({"left":"0"});                                      
           }
            return false;
        });
    }

    var init = function(){
        footer_menu();
    }
    return {
        init: init
    }
}());
$(function(){
    mb.blog.init();
    $(".blog_content .title").nextAll("ul").addClass("content_items");
});