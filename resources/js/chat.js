(function (define) {
    define(['jquery', 'socket.io-client'], function ($, io) {
        return (function () {
            var settings = {
                server: {
                    host: 'http://localhost',
                    port: '8888'
                },
                user_id: 0
            };
            //var socket_io = null;

            return chat = {
                init: init
            };

            function init(options) {
                settings = $.extend(settings, options || {});

                /*socket_io = io(settings.server.host + ':' + settings.server.port);
                socket_io.on('public-chat:App\\Library\\Services\\Events\\PublicMessage', function (e) {
                    if (socket_io.id == e.socket) {
                        return;
                    }

                    $('#messagePanel').append(e.message).animate({
                        scrollTop: $('#messagePanel').prop('scrollHeight')
                    }, 1000);
                });*/

                Echo.channel('public-chat')
                    .listen('.App\\Library\\Services\\Events\\PublicMessage', (e) => {
                        $('#messagePanel').find('.no-message').remove();
                        $('#messagePanel').append(e.message).animate({
                            scrollTop: $('#messagePanel').prop('scrollHeight')
                        }, 1000);
                    });

                $('.show-chat').on('click', function (e) {
                    e.preventDefault();

                    $('#frmChat').show().find('.btn_close').on('click', function() {
                        $('#frmChat').hide();
                    });
                });

                $('#messagePanel')
                    .animate({
                        scrollTop: $('#messagePanel').prop('scrollHeight')
                    }, 1000);

                sendMessage();
            }

            function sendMessage() {
                $('#frmMessage').on('submit', function(evt) {
                    evt.preventDefault();

                    var form = $(this);
                    var message = form.find('[name="message"]').val().stripTags();

                    if (message.blank() || form.data('sending') == 1) {
                        form.find('[name="message"]').focus();
                        return;
                    }

                    $.ajax({
                        url: form.attr('action'),
                        method: form.attr('method'),
                        headers: {
                            //'X-Socket-ID': socket_io.id
                            'X-Socket-ID': Echo.socketId()
                        },
                        data: {
                            user_id: settings.user_id,
                            message: message
                        },
                        beforeSend: function() {
                            form.data('sending', 1);
                        },
                        success: function(response) {
                            form.find('[name="message"]').val('');

                            if (response.error == 0) {
                                //common.showMessage(response.message);

                                $('#messagePanel').find('.no-message').remove();
                                $('#messagePanel').append(response.data).animate({ scrollTop: $('#messagePanel').prop('scrollHeight') }, 1000);
                            } else {
                                //common.showMessage(response.message, 'error');
                            }

                            form.data('sending', 0);
                        },
                        error: function() {
                            form.data('sending', 0)
                                .find('[name="message"]').val('');
                        }
                    });
                });

                $('#frmMessage').on('keydown', '[name="message"]', function (e) {
                    var message = $(this).val().stripTags();

                    if (e.keyCode == 13 && !message.blank()) {
                        $(this).parents('form').submit();
                    }
                });
            }
        })();
    });
}(typeof define === 'function' && define.amd ? define : function(global, factory) {
    if (typeof module !== 'undefined' && module.exports) { //Node
        module.exports = factory(require('jquery'), require('socket.io-client'));
    } else {
        window.chat = factory(window.jQuery, window.io);
    }
}));
