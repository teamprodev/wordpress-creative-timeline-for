jQuery(document).ready(function($) { 
    "use strict";
    if(jQuery(".ctwp-classic").hasClass('tilt')){
        var $element = jQuery('.ctwp-classic.tilt .ctwp-data-container');
        var $window = jQuery(window);
        $window.on('scroll resize', check_for_fade);
        $window.trigger('scroll');
        function check_for_fade() { 
            var window_height = $window.height();   
            $.each($element, function (event) {
                var $element = jQuery(this);
                var element_height = $element.outerHeight();
                var element_offset = $element.offset().top;
                var space = window_height - (element_height + element_offset -jQuery(window).scrollTop());
                if (space < 60) {
                    $element.addClass("non-focus");
                    jQuery( ".ctwp-classic .ctwp-data-container" ).css( "transition", "all 0.3s ease-in-out" );
                } else {
                    $element.removeClass("non-focus");
                    jQuery( ".ctwp-classic .ctwp-data-container" ).css( "transition", "all 0.3s ease-in-out" );
                }
            });
        };
    }
    if(jQuery(".ctwp-classic").hasClass('inandout')){
        var items = document.querySelectorAll('.ctwp-classic.inandout .ctwp-data-container');
        function isElementInViewport(el) {
            var rect = el.getBoundingClientRect();
            return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <=
                (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }    
        function callbackFunc() {
            for (var i = 0; i < items.length; i++) {
                if (isElementInViewport(items[i])) {
                    items[i].classList.add("in-view");
                }
            }
        }
        // listen for events
        window.addEventListener("load", callbackFunc);
        window.addEventListener("resize", callbackFunc);
        window.addEventListener("scroll", callbackFunc);
    }
    if(jQuery(".ctwp-artistic").hasClass('fadeup')){
        jQuery(window).scroll(function(){
            jQuery('.ctwp-artistic.fadeup .ctwp-data-container').each(function(){        
                var zero = "0deg", angle = "180deg";
                
                if(isScrolledIntoViewfadeup(jQuery(this))){        
                    jQuery(this).css({
                        'opacity':1,
                        'visibility':'visible',
                        '-webkit-transform': 'rotateX(' + zero + ')',
                        'transform': 'rotateX('+zero+')'
                    });
                }
            });
        });
        function isScrolledIntoViewfadeup(elem){
            var $elem = $(elem);
            var $window = $(window);
        
            var docViewTop = $window.scrollTop();
            var docViewBottom = docViewTop + $window.height();
        
            var elemTop = $elem.offset().top;
            var elemBottom = elemTop + $elem.height();
        
            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        }
    }
    if(jQuery(".ctwp-artistic").hasClass('fadeleftright')){
        jQuery(window).scroll(function(){
            //Fade-in-right
            jQuery('.ctwp-artistic.fadeleftright .ctwp-entry:nth-child(odd) .ctwp-data-container').each(function(){        
                var point = "0px", side = "50px";
                
                if(isScrolledIntoViewfadeleftright(jQuery(this))){        
                    jQuery(this).css({
                        'opacity':1,
                        'visibility':'visible',
                        '-webkit-transform': 'translateX(' + point + ')',
                        'transform': 'translateX(' + point + ')'                    
                    });
                }
            });
            //Fade-in-left
            jQuery('.ctwp-artistic.fadeleftright .ctwp-entry:nth-child(even) .ctwp-data-container').each(function(){        
                var point = "0px", move = "-50px";
                
                if(isScrolledIntoViewfadeleftright(jQuery(this))){        
                    jQuery(this).css({
                        'opacity':1,
                        'visibility':'visible',
                        '-webkit-transform': 'translateX(' + point + ')',
                        'transform': 'translateX(' + point + ')'                    
                    });
                }
            });
        });
        function isScrolledIntoViewfadeleftright(elem){
            var $elem = $(elem);
            var $window = $(window);
        
            var docViewTop = $window.scrollTop();
            var docViewBottom = docViewTop + $window.height();
        
            var elemTop = $elem.offset().top;
            var elemBottom = elemTop + $elem.height();
        
            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        }
    }
});

