(function (define) {
    define(['jquery', 'bootbox'], function ($, bootbox) {
        return (function () {
            var settings = {
                url_get_widget: '',
                url_get_comment: '',
                url_post_comment: '',
                url_update_view: '',
                url_update_comment_like: '',
                post_id: 0,
                parent_id: 0,
                max_time_allow_like: '',
                
            };

            return interaction = {
                init: init,
                showComment: showComment,
                getWidget: getWidget,
                updateView: updateView
            };

            ////////////////
            function init(configs) {
                settings = $.extend(settings, configs || {});

                getWidget();
            }

            function showComment() {
                var params = $('#box_comment').data('params');

                settings.post_id = params.post_id;

                $.ajax({
                    url: settings.url_get_comment,
                    data: params,
                    success: function (data) {
                        $('#box_comment').html(data);
                        $('.comments__like').each(function (index, tag) {
                            id = $(tag).data('id');
                            keyStorage = 'key_current_time_comment_like_' + id;
                            currentTime = parseInt($.now());
                            currentTimeStorage = parseInt(localStorage.getItem(keyStorage));
                            if(isNaN(currentTimeStorage) || settings.max_time_allow_like - parseInt((currentTime - currentTimeStorage) / 60000) <= 0){
                                $(tag).find('i').removeClass('ic-sprite--heart--fill');
                                $(tag).find('i').addClass('ic-sprite--heart--empty');
                            }else{
                                $(tag).find('i').removeClass('ic-sprite--heart--empty');
                                $(tag).find('i').addClass('ic-sprite--heart--fill');
                            }
                        });
                    }
                });

                //click reply button
                $('#box_comment').on('click', '.reply', function (e) {
                    e.preventDefault();

                    settings.parent_id = $(this).data('reply');

                    if ($(this).parents('.comments__detail').find('#comment_reply_wrapper').length == 0) {
                        $('#comment_reply_wrapper').appendTo($(this).parents('.comments__detail')).show();
                    } else {
                        $('#comment_reply_wrapper').toggle();
                    }
                });

                $('#box_comment').on('click', '#comment_form', function (e) {
                    e.preventDefault();
                    settings.parent_id = 0;

                    sendComment($(this));
                });

                $('#box_comment').on('click', '#comment_reply_form', function (e) {
                    e.preventDefault();

                    sendComment($(this));
                });

                //pagination
                $('#box_comment').on('click', '.pagination a.page-link', function (e) {
                    e.preventDefault();

                    var link = $(this).attr('href');

                    $.ajax({
                        url: link,
                        success: function (data) {
                            $('#box_comment').html(data);
                            $('body,html').animate({
                                scrollTop: $('#box_comment').offset().top
                            }, 500);
                        }
                    });
                });

                //click like
                $('#box_comment').on('click', '.comments__like i', function() {
                    updateLikeComment($(this));
                });
            }

            function getWidget() {
                var arrCommentId = $.map($('[data-type="widget"][data-widgettype="comment"]'), function (o, i) {
                    var id = $(o).data('widgetid');

                    $(o).addClass('widget-comment-' + id)
                        .removeAttr('data-type')
                        .removeAttr('data-widgettype')
                        .removeAttr('data-widgetid');

                    return id;
                });

                var arrPostId = $.map($('[data-type="widget"][data-widgettype="post"]'), function (o, i) {
                    var id = $(o).data('widgetid');

                    $(o).addClass('widget-post-' + id)
                        .removeAttr('data-type')
                        .removeAttr('data-widgettype')
                        .removeAttr('data-widgetid');

                    return id;
                });

                $.ajax({
                    url: settings.url_get_widget,
                    data: {
                        cid: arrCommentId,
                        pid: arrPostId
                    },
                    success: function (response) {
                        if (response.error == 0) {
                            $.each(response.data, function (type, data) {
                                $.each(data, function (index, value) {
                                    var widget = $('.widget-' + type + '-' + index);

                                    switch (type) {
                                        case 'post':
                                            widget.html('(' + value + ')');
                                            break;
                                        default:
                                        case 'comment':
                                            widget.html(value);
                                            if (value < 1) {
                                                widget.hide();
                                            }
                                            break;
                                    }
                                });
                            });
                        }
                    }
                });
            }

            function updateView(post_id) {
                var keyStorage = 'key_current_time_post_' + post_id;
                var currentTime = parseInt($.now());
                var currentTimeStorage = parseInt(localStorage.getItem(keyStorage));
                var addTime = parseInt(30*60*1000);
                var endTime = currentTimeStorage + addTime;

                if (isNaN(currentTimeStorage) || currentTime >= endTime) {
                    $.ajax({
                        url: settings.url_update_view,
                        method: 'post',
                        data: {
                            id: post_id
                        }
                    });

                    localStorage.setItem(keyStorage, currentTime);
                }
            }

            /**
             * Private function
             */
            function sendComment(form) {
                var content = form.find('[name="content"]').val().stripTags();
                var old_text = form.find('button:submit').html();

                if (content.blank()) {
                    return false;
                }

                $.ajax({
                    url: settings.url_post_comment,
                    method: 'post',
                    data: {
                        post_id: settings.post_id,
                        parent_id: settings.parent_id,
                        content: content
                    },
                    beforeSend: function () {
                        form.find('button:submit').html('<i class="fas fa-spin fa-spinner"></i>').prop('disabled', true);
                    },
                    success: function (response) {
                        form.find('button:submit').prop('disabled', false).html(old_text);
                        form.find('[name="content"]').val('');

                        bootbox.alert(response.message);

                        showComment();
                        $('body,html').animate({
                            scrollTop: $('#box_comment').offset().top
                        }, 500);
                    },
                    error: function() {
                        form.find('button:submit').prop('disabled', false).html(old_text);
                    }
                });
            }

            function updateLikeComment(_this) {
                var id = _this.parent().data('id');
                var keyStorage = 'key_current_time_comment_like_' + id;
                var totalLike = parseInt(_this.parent().find('.comments__count').html());
                var currentTime = parseInt($.now());
                var currentTimeStorage = parseInt(localStorage.getItem(keyStorage));
                var addTime = parseInt(settings.max_time_allow_like * 60 * 1000);
                var endTime = currentTimeStorage + addTime;

                if (isNaN(currentTimeStorage) || currentTime >= endTime) {
                    $.ajax({
                        url: settings.url_update_comment_like,
                        method: 'post',
                        data: {
                            id: id
                        },
                        success: function (response) {
                            _this.removeClass('ic-sprite--heart--empty');
                            _this.addClass('ic-sprite--heart--fill');
                            _this.parent().find('.comments__count').html(totalLike + 1);
                        }
                    });

                    localStorage.setItem(keyStorage, currentTime);
                } else {
                    bootbox.alert('Bạn vừa like, xin hãy đợi ' + (settings.max_time_allow_like - parseInt((currentTime - currentTimeStorage) / 60000)) + ' phút nữa để có thể tiếp tục.');
                }
            }
        })();
    });
}(typeof define === 'function' && define.amd ? define : function(global, factory) {
    if (typeof module !== 'undefined' && module.exports) { //Node
        module.exports = factory(require('jquery'), require('bootbox'));
    } else {
        window.interaction = factory(window.jQuery, window.bootbox);
    }
}));
