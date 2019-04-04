(function (define) {
    define(['jquery'], function ($) {
        return (function () {
            var settings = {
                toastrOptions: {
                    closeButton: true,
                    debug: false,
                    newestOnTop: true,
                    progressBar: false,
                    positionClass: 'toast-bottom-right',
                    preventDuplicates: false,
                    onclick: null,
                    showDuration: '300',
                    hideDuration: '1000',
                    timeOut: '3000',
                    extendedTimeOut: '1000',
                    showEasing: 'swing',
                    hideEasing: 'linear',
                    showMethod: 'fadeIn',
                    hideMethod: 'fadeOut'
                }
            };

            return frontend = {
                init: init
            };

            ////////////////
            function init(configs) {
                settings = $.extend(settings, configs || {});

                //show notice board
                $('.show-notice').popover({
                    html: true,
                    content: function () {
                        return $('#notice_content').html();
                    }
                });

                // Profile popup
                $('[data-toggle="user__profile"]').popover({
                    html: true,
                    content: function () {
                        return $('#user__profile--content').html();
                    }
                }).on('show.bs.popover hide.bs.popover', function () {
                    var arrow = $('.ic-sprite--arrow');
                    var clicked = arrow.hasClass('ic-sprite--arrow--down');

                    if (clicked) {
                        arrow.removeClass('ic-sprite--arrow--down');
                        arrow.addClass('ic-sprite--arrow--up');
                    } else {
                        arrow.removeClass('ic-sprite--arrow--up');
                        arrow.addClass('ic-sprite--arrow--down');
                    }
                });

                common.init();

                $('nav .dropdown').hover(function () {
                    var $this = $(this);
                    $this.addClass('show');
                    $this.find('> a').attr('aria-expanded', true);
                    $this.find('.dropdown-menu').addClass('show');
                }, function () {
                    var $this = $(this);
                    $this.removeClass('show');
                    $this.find('> a').attr('aria-expanded', false);
                    $this.find('.dropdown-menu').removeClass('show');
                });

                //scroll to top
                $('#gotop').on('click', function (evt) {
                    evt.preventDefault();

                    $('body,html').animate({
                        scrollTop: 0
                    }, 500);
                });
            }
        })();
    });
}(typeof define === 'function' && define.amd ? define : function(global, factory) {
    if (typeof module !== 'undefined' && module.exports) { //Node
        module.exports = factory(require('jquery'));
    } else {
        window.frontend = factory(window.jQuery);
    }
}));
