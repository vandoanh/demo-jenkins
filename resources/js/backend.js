(function (define) {
    define(['jquery', 'bootbox'], function ($, bootbox) {
        return (function () {
            var settings = {
                menuClass: '.sidebar-menu',
                subMenuClass: '.sub-menu',
                openClass: 'menu-open',
                activeClass: 'active',
                animationSpeed: 'fast',
                screenSize: 1025,
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

            return backend = {
                init: init
            };

            ////////////////
            function init(config) {
                settings = $.extend(settings, config || {});

                common.init(settings);

                toggleMenu();
                treeMenu();
                fixHeight();
                $(window, '.wrapper').resize(function () {
                    fixHeight();
                });
                common.showDatePicker();

                //check menu active in sidebar
                var menu_item_active = $('ul' + settings.subMenuClass + ' li.' + settings.activeClass);
                menu_item_active.parent().addClass(settings.openClass).show();
                menu_item_active.parents('li.menu-item').addClass(settings.activeClass);

                //scroll to top
                $('#scrolltop a').on('click', function (evt) {
                    evt.preventDefault();
                    $('body,html').animate({ scrollTop: 0 }, 500);
                });
                $(window).scroll(function () {
                    if ($(this).scrollTop() > 30) {
                        $('#scrolltop').show();
                    } else {
                        $('#scrolltop').hide();
                    }
                });

                // change on select
                $('#frm-search').on('change', 'select:not([data-multiselect="true"])', function() {
                    $('#frm-search button:submit').trigger('click');
                });

                //dropdown submenu
                $('.dropdown-submenu > .dropdown-item').on('click', function(evt) {
                    evt.preventDefault();

                    $(this).next('.dropdown-menu').show();
                    $(this).parent().addClass('hover');

                    return false;
                });

                // click checkbox check all
                $(document).on('click', '#chkAll', function() {
                    $(this).parents('table').find('input:checkbox[data-for="chkAll"]:enabled').prop('checked', $(this).is(':checked'));
                });
                $(document).on('click', ':checkbox[data-for="chkAll"]:enabled', function() {
                    var parent = $(this).parents('table');

                    if (!$(this).is(':checked')) {
                        $(parent).find(':checkbox#chkAll').prop('checked', false);
                    } else {
                        if ($(parent).find(':checkbox[data-for="chkAll"]:enabled').length === $(parent).find(':checkbox[data-for="chkAll"]:enabled:checked').length) {
                            $(parent).find(':checkbox#chkAll').prop('checked', true);
                        }
                    }
                });

                //select item in paging
                $(document).on('change', 'select[data-pagination="true"]', function () {
                    var link = window.location.href;
                    link = common.addUrlParam(link, 'page', 1);
                    link = common.addUrlParam(link, 'item', $(this).val());

                    window.location.href = link;
                });

                //tooltip
                $('[data-toggle="tooltip"]').tooltip();

                //popover
                $('[data-toggle="popover"]').popover();

                // change status
                initChangeStatus();

                // click delete
                initDelete();
            }

            /**
             * private function
             */
            function toggleMenu() {
                //Enable sidebar toggle
                $('.main-header .sidebar-toggle').on('click', function(evt) {
                    evt.preventDefault();

                    $('.app').toggleClass('collapsed');
                    $(this).toggleClass('change');
                });

                $('.content-mask').on('click', function() {
                    //Enable hide menu when clicking on the content-wrapper on small screens
                    if ($(window).width() < settings.screenSize && $('.app').hasClass('collapsed')) {
                        $('body').removeClass('collapsed');
                        $('.main-header .sidebar-toggle').removeClass('change');
                    }
                });
            }

            function treeMenu() {
                $(settings.menuClass).on('click',  'li a', function(e) {
                    // Get the clicked link and the next element
                    var $this = $(this);
                    var checkElement = $this.next();

                    // Check if the next element is a menu and is visible
                    if ((checkElement.is(settings.subMenuClass)) && (checkElement.is(':visible'))) {
                        // Close the menu
                        checkElement.slideUp(settings.animationSpeed, function () {
                            checkElement.removeClass(settings.openClass);
                        });
                        checkElement.parent('li').removeClass(settings.activeClass);
                    }
                    // If the menu is not visible
                    else if ((checkElement.is(settings.subMenuClass)) && (!checkElement.is(':visible'))) {
                        // Get the parent menu
                        var parent = $this.parents('ul').first();
                        // Close all open menus within the parent
                        var ul = parent.find('ul:visible').slideUp(settings.animationSpeed);
                        // Remove the menu-open class from the parent
                        ul.removeClass(settings.openClass);
                        // Get the parent li
                        var parent_li = $this.parent('li');

                        // Open the target menu and add the menu-open class
                        checkElement.slideDown(settings.animationSpeed, function () {
                            // Add the class active to the parent li
                            checkElement.addClass(settings.openClass);
                            parent.children('li.' + settings.activeClass).removeClass(settings.activeClass);
                            parent_li.addClass(settings.activeClass);

                            if ($(window).width() < settings.screenSize && !$('.app').hasClass('collapsed')) {
                                $('body').addClass('collapsed');
                                $('.main-header .sidebar-toggle').addClass('change');
                            }
                        });
                    }
                    // if this isn't a link, prevent the page from being redirected
                    if (checkElement.is(settings.subMenuClass)) {
                        e.preventDefault();
                    }
                });
            }

            function fixHeight() {
                //Get window height and the wrapper height
                var neg = $('.content-wrapper .main-header').outerHeight() + $('.content-wrapper .main-footer').outerHeight();
                var window_height = $(window).height();

                //Set the min-height of the content and sidebar based on the height of the document.
                $('.content-wrapper .content').css('min-height', (window_height - neg) + 65);
            }

            function initChangeStatus() {
                // click button change status
                $(document).on('click', '[data-status="true"]', function(evt) {
                    evt.preventDefault();

                    var _this = $(this);
                    var multi = _this.data('multi') || false;
                    var arrId = [];

                    if (multi) {
                        arrId = common.getRowChecked();
                    } else {
                        arrId.push(_this.data('id') || 0);
                    }

                    if (!arrId.blank()) {
                        var link = _this.data('link') || _this.attr('href');

                        $.ajax({
                            url: link,
                            method: 'post',
                            dataType: 'json',
                            data: {
                                id: arrId
                            },
                            beforeSend: function () {
                                common.showLoader();
                            },
                            success: function (response) {
                                common.hideLoader();

                                if (response.error === 0) {
                                    common.showMessage(response.message, 'info', {
                                        onHidden: function() {
                                            window.location.reload(true);
                                        }
                                    });
                                } else {
                                    showMessage(response.message, 'error');
                                }
                            },
                            error: function (response) {
                                common.hideLoader();
                                common.showMessage(response.message, 'error');
                            }
                        });
                    } else {
                        if (multi) {
                            $('#chkAll').prop('checked', false);
                        }

                        bootbox.alert('No item is selected!');
                    }
                });
            }

            function initDelete() {
                $(document).on('click', '[data-delete="true"]', function(evt) {
                    evt.preventDefault();

                    var _this = $(this);
                    var multi = _this.data('multi') || false;
                    var message = _this.data('message');
                    var arrId = [];

                    if (multi) {
                        arrId = common.getRowChecked();
                    } else {
                        arrId.push(_this.data('id') || 0);
                    }

                    if (!arrId.blank()) {
                        bootbox.confirm(message, function(result) {
                            if (result) {
                                var reload = _this.data('reload') || false;
                                var parent = _this.data('parent') || 'tr';
                                var url = _this.data('link') || _this.attr('href');

                                $.ajax({
                                    url: url,
                                    method: 'delete',
                                    data: {
                                        id: arrId
                                    },
                                    beforeSend: function () {
                                        common.showLoader();
                                    },
                                    success: function (response) {
                                        common.hideLoader();

                                        if (multi) {
                                            $('#chkAll').prop('checked', false);
                                        }

                                        if (response.error === 0) {
                                            _this.parents(parent).remove();

                                            common.showMessage(response.message, 'info', {
                                                onHidden: function() {
                                                    if (reload) {
                                                        window.location.reload(true);
                                                    }
                                                }
                                            });
                                        } else {
                                            common.showMessage(response.message, 'error');
                                        }
                                    },
                                    error: function (response) {
                                        common.hideLoader();

                                        if (multi) {
                                            $('#chkAll').prop('checked', false);
                                        }

                                        common.showMessage(response.statusText, 'error');
                                    }
                                });
                            }
                        });
                    } else {
                        if (multi) {
                            $('#chkAll').prop('checked', false);
                        }

                        bootbox.alert('No item is selected!');
                    }
                });
            }
        })();
    });
}(typeof define === 'function' && define.amd ? define : function(global, factory) {
    if (typeof module !== 'undefined' && module.exports) { //Node
        module.exports = factory(require('jquery'), require('bootbox'));
    } else {
        window.backend = factory(window.jQuery, window.bootbox);
    }
}));
