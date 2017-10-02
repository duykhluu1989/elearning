jQuery(function($) {

    $('.slide_text').textSlider({
        timeout: 5000,
        slideTime: 750,
        loop: 1
    });


    //matchHeight columm
    $('.boxmH').matchHeight();

    // CENTERED MODALS
    // phase one - store every dialog's height
    $('.modal').each(function() {
        var t = $(this),
            d = t.find('.modal-dialog'),
            fadeClass = (t.is('.fade') ? 'fade' : '');
        // render dialog
        t.removeClass('fade')
            .addClass('invisible')
            .css('display', 'block');
        // read and store dialog height
        d.data('height', d.height());
        // hide dialog again
        t.css('display', '')
            .removeClass('invisible')
            .addClass(fadeClass);
    });
    // phase two - set margin-top on every dialog show
    $('.modal').on('show.bs.modal', function() {
        var t = $(this),
            d = t.find('.modal-dialog'),
            dh = d.data('height'),
            w = $(window).width(),
            h = $(window).height();
        // if it is desktop & dialog is lower than viewport
        // (set your own values)
        if (w > 380 && (dh + 60) < h) {
            d.css('margin-top', Math.round(0.96 * (h - dh) / 2));
        } else {
            d.css('margin-top', '');
        }
    });

    //popover
    $('[data-toggle="popover"]').popover({
        placement: 'top',
    });




    /* customer */
    $('.owl_banner').owlCarousel({
        loop: true,
        margin: 10,
        responsive: {
            0: {
                items: 1
            }
        }
    });

    $('.owl_khmp').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        navText: [
            "<i class='fa fa-chevron-left fa-2x'></i>",
            "<i class='fa fa-chevron-right fa-2x'></i>"
        ],
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            }
        }
    });

    $('.owl_chuyengia').owlCarousel({
        loop: true,
        margin: 10,
        // nav:true,
        navText: [
            "<i class='fa fa-chevron-left fa-2x'></i>",
            "<i class='fa fa-chevron-right fa-2x'></i>"
        ],
        // dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 5
            }
        }
    });

    $('.owl_markerting').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        navText: [
            "<i class='fa fa-chevron-left fa-2x'></i>",
            "<i class='fa fa-chevron-right fa-2x'></i>"
        ],
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            }
        }
    });

    $('.owl_banhang').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        navText: [
            "<i class='fa fa-chevron-left fa-2x'></i>",
            "<i class='fa fa-chevron-right fa-2x'></i>"
        ],
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            }
        }
    });

    $('.owl_QTNHTM').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        navText: [
            "<i class='fa fa-chevron-left fa-2x'></i>",
            "<i class='fa fa-chevron-right fa-2x'></i>"
        ],
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            }
        }
    });

    $('.owl_vechungtoi').owlCarousel({
        loop: true,
        // margin:30,
        // nav:true,
        navText: [
            "<i class='fa fa-chevron-left fa-2x'></i>",
            "<i class='fa fa-chevron-right fa-2x'></i>"
        ],
        // dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });

    $('.owl_giaovien').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        navText: [
            "<i class='fa fa-chevron-left fa-2x'></i>",
            "<i class='fa fa-chevron-right fa-2x'></i>"
        ],
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 5
            }
        }
    });

    $('.owl_tructuyen').owlCarousel({
        loop: true,
        margin: 15,
        dots: true,
        responsive: {
            0: {
                items: 3
            },
            600: {
                items: 3
            },
            1000: {
                items: 5
            }
        }
    });

    $('.owl_tintucnoibat').owlCarousel({
        loop: true,
        margin: 10,
        // nav:true,
        navText: [
            "<i class='fa fa-chevron-left fa-2x'></i>",
            "<i class='fa fa-chevron-right fa-2x'></i>"
        ],
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 5
            }
        }
    });


});

$(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
        $('.scroll_to_top').fadeIn();
    } else {
        $('.scroll_to_top').fadeOut();
    }
});



//---------------------------------------------------



//---------------------------------------------------



function collapseNavbar() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }
}

$(window).scroll(collapseNavbar);
$(document).ready(collapseNavbar);

// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a').click(function() {
    $(this).closest('.collapse').collapse('toggle');
});

(function($) {
    $(window).on("load", function() {
        $(".content").mCustomScrollbar();
    });


})(jQuery);



$(document).ready(function() {

    //menu hover
    // $(".dropdown").hover(
    //     function() {
    //         $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideDown("500");
    //         $(this).toggleClass('open');
    //     },
    //     function() {
    //         $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideUp("500");
    //         $(this).toggleClass('open');
    //     }
    // );

    $('ul.nav li.dropdown').hover(function() {
      $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
    }, function() {
      $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
    });

    //search on header
    $('.header').on('click', '.search-toggle', function(e) {
        var selector = $(this).data('selector');

        $(selector).toggleClass('show').find('.search-input').focus();
        $(this).toggleClass('active');

        e.preventDefault();
    });


    //close ads
    $('.qc_left .btnClose').on('click', function(e) {
        e.preventDefault();
        $('.qc_left').hide();
    });
    $('.qc_right .btnClose').on('click', function(e) {
        e.preventDefault();
        $('.qc_right').hide();
    });

    //file browser

    $("#doiavatar").filestyle({
        size: 'lg',
    });

    $('.scroll_to_top').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 800);
    });

    

});

// show hide when scroll to element
// $(document).scroll(function() {
//     var y = $(document).scrollTop();
//     var body = $('body').height();
//     var height_window = $(window).height();
//     var height_footer_bottom = 0; //$('.bottom_searchCondition').height();
//     // var h_services = $('#bottom_searchCondition').height() + $('#services').height();

//     if (y <= (body + height_footer_bottom)) {
//         setTimeout(function() {
//             $('.btnBGmoi, .btnTTmoi').removeClass('no_fixed');
//         }, 200);

//     } else {
//         setTimeout(function() {
//             $('.btnBGmoi, .btnTTmoi').addClass('no_fixed');
//         }, 200);

//     }
// });
