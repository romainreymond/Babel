var App = function () {
    
    var handleSize = function () {
        var height = $(window).height() - $('.header').height() + 1;
        $('.nav').attr('style', 'height:' + height + 'px !important');
    }
    
    var handleSidebarHover = function () {
        if ($('body').hasClass('sidebar-open')) {
            return;
        }
        $('.nav').mouseleave(function () {
            var body = $('body');
            if ((body.hasClass('sidebar-hover-on') === false && $(this).hasClass('sidebar-hovering')) || body.hasClass('sidebar-open')) {
                return;
            }
            $(this).addClass('sidebar-hovering');
            $(this).stop().animate({width:'35px'},200,function(){
                $(this).removeClass('sidebar-hovering');
                $('body').addClass('sidebar-close').removeClass('sidebar-hover-on');;
                $('#search_input').blur();});
        });
        $('.nav').mouseenter(function () {
            var body = $('body');
            if ((body.hasClass('sidebar-close') === false && $(this).hasClass('sidebar-hovering')) || body.hasClass('sidebar-open')) {
                return;
            }
            body.removeClass('sidebar-close').addClass('sidebar-hover-on');
            $(this).addClass('sidebar-hovering');
            $(this).stop().animate({width:'225px'},250,
                            function(){
                                $(this).removeClass('sidebar-hovering');});
        });
    }
    
    var handlePlaceholder = function () {
        $('input[placeholder]').each(function(){  
        
            var input = $(this);        
            
            $(input).val(input.attr('placeholder'));
                    
            $(input).focus(function(){
                 if (input.val() == input.attr('placeholder')) {
                     input.val('');
                 }
            });
            
            $(input).blur(function(){
                if (input.val() == '' || input.val() == input.attr('placeholder')) {
                    input.val(input.attr('placeholder'));
                }
            });
        });
    }
    
    var handleSidebarToggler = function () {
        $('.sidebar-toggler').click(function () {
            if ($('body').hasClass('sidebar-open'))
            {
                $('body').removeClass('sidebar-open').addClass('sidebar-hover-on');
                $.cookie('sidebar_close', '1', { path: '/' });
                handleSidebarHover();
            }
            else
            {
                if ($('body').hasClass('sidebar-close'))
                    $('body').removeClass('sidebar-close');
                if ($('body').hasClass('sidebar-hover-on'))
                    $('body').removeClass('sidebar-hover-on');
                $.cookie('sidebar_close', '0', { path: '/' });
                $('body').addClass('sidebar-open');
                $('.nav').css('width', '');
            }
        });
    }
	
    return {
        init: function () {
            handleSidebarHover();
            handleSidebarToggler();
            handleSize();
            handlePlaceholder();
            $( window ).resize(function () {
                handleSize();
            });
        }
    };
}();