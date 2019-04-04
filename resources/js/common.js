/************************************************ Begin Extend Javascript Object ************************************************/
$.extend(Date.prototype, {
    // returns the day of the year for this date
    getDayOfYear: function () {
        return parseInt((this.getTime() - new Date(this.getFullYear(), 0, 1).getTime()) / 86400000 + 1);
    },
    // return true if is leap year;
    leapYear: function () {
        return ((this.getFullYear() % 400 == 0) || ((this.getFullYear() % 4 == 0) && (this.getFullYear() % 100 != 0)));
    },
    /*Returns the week number for this date.  dowOffset is the day of week the week
     'starts' on for your locale - it can be from 0 to 6. If dowOffset is 1 (Monday),
     the week returned is the ISO 8601 week number.
     @param int dowOffset
     @return int*/
    getWeek: function (dowOffset) {
        dowOffset = typeof (dowOffset) === 'int' ? dowOffset : 0; // default dowOffset to zero
        var newYear = new Date(this.getFullYear(), 0, 1);
        var day = newYear.getDay() - dowOffset; // the day of week the year begins on
        day = (day >= 0 ? day : day + 7);
        var weekNum, dayNum = Math.floor((this.getTime() - newYear.getTime() - (this.getTimezoneOffset() - newYear.getTimezoneOffset()) * 60000) / 86400000) + 1;

        if (day < 4) {// if the year starts before the middle of a week
            weekNum = Math.floor((daynum + day - 1) / 7) + 1;
            if (weekNum > 52) {
                var nYear = new Date(this.getFullYear() + 1, 0, 1);
                var nDay = nYear.getDay() - dowOffset;
                nDay = nDay >= 0 ? nDay : nDay + 7;
                weekNum = nDay < 4 ? 1 : 53; // if the next year starts before the middle of the week, it is week #1 of that year
            }
        }
        else {
            weekNum = Math.floor((dayNum + day - 1) / 7);
        }

        return weekNum;
    },
    // returns the number of DAYS since the UNIX Epoch - good for comparing the date portion
    getUEDay: function () {
        return parseInt(Math.floor((this.getTime() - this.getTimezoneOffset() * 60000) / 86400000)); // must take into account the local timezone
    },
    isToday: function () {
        var curDate = new Date();
        return (this.getUEDay() == curDate.getUEDay());
    },
    format: function (mask, lang, utc) {
        var arrLanguage = {
            vi: {
                daysSName: ['CN', 'T.2', 'T.3', 'T.4', 'T.5', 'T.6', 'T.7'],
                daysFName: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
                monthsSName: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                monthsFName: ['Tháng một', 'Tháng hai', 'Tháng ba', 'Tháng tư', 'Tháng năm', 'Tháng sáu', 'Tháng bảy', 'Tháng tám', 'Tháng chín', 'Tháng mười', 'Tháng mười một', 'Tháng mười hai'],
                timeMarker: ['s', 'S', 'sa', 'SA', 'c', 'C', 'ch', 'CH']
            },
            en: {
                daysSName: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                daysFName: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                monthsSName: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                monthsFName: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                timeMarker: ['a', 'A', 'am', 'AM', 'p', 'P', 'pm', 'PM']
            }
        };

        var language = arrLanguage[lang];
        var masks = {
            'default': 'dddd dd mmm yyyy hh:MM:ss TT', // Chủ nhật 20 Tháng 10 2008 12:37:21 CH
            shortDate: 'd/m/yy', // 20/10/08
            mediumDate: 'd mmm, yyyy', // 20 Tháng 10, 2008
            longDate: 'd mmmm, yyyy', // 20 Tháng mười, 2008
            fullDate: 'dddd, d mmmm, yyyy', // Chủ nhật, 20 Tháng mười, 2008
            shortTime: 'h:MM TT', // 5:46 CH
            mediumTime: 'h:MM:ss TT', // 5:46:21 CH
            longTime: 'h:MM:ss TT Z', // 5:46:21 CH EST
            isoDate: 'yyyy-mm-dd', // 2008-10-20
            isoTime: 'HH:MM:ss', // 17:46:21
            isoDateTime: 'yyyy-mm-dd"T"HH:MM:ss', // 2008-10-20T17:46:21
            isoUtcDateTime: 'UTC:yyyy-mm-dd"T"HH:MM:ss"Z"' // 2008-10-20T22:46:21Z
        };

        var token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g;
        var timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g;
        var timezoneClip = /[^-+\dA-Z]/g;
        pad = function (val, len) {
            val = String(val);
            len = len || 2;
            while (val.length < len)
                val = '0' + val;
            return val;
        };

        // Passing date through Date applies Date.parse, if necessary
        var date = this;
        if (isNaN(date)) {
            throw new SyntaxError('invalid date');
        }

        mask = String(masks[mask] || mask || masks['default']);

        // Allow setting the utc argument via the mask
        if (mask.slice(0, 4) == 'UTC:') {
            mask = mask.slice(4);
            utc = true;
        }

        var _ = utc ? 'getUTC' : 'get',
            d = date[_ + 'Date'](),
            D = date[_ + 'Day'](),
            m = date[_ + 'Month'](),
            y = date[_ + 'FullYear'](),
            H = date[_ + 'Hours'](),
            M = date[_ + 'Minutes'](),
            s = date[_ + 'Seconds'](),
            L = date[_ + 'Milliseconds'](),
            o = utc ? 0 : date.getTimezoneOffset(),
            flags = {
                d: d, // Day of the month as digits; no leading zero for single-digit days, ex: 1.
                dd: pad(d), // Day of the month as digits; leading zero for single-digit days, ex: 01.
                ddd: language.daysSName[D], // Day of the week as a three-letter abbreviation, ex: CN.
                dddd: language.daysFName[D], // Day of the week as its full name, ex: Chủ nhật.
                m: m + 1, // Month as digits; no leading zero for single-digit months, ex: 1.
                mm: pad(m + 1), // Month as digits; leading zero for single-digit months, ex: 01.
                mmm: language.monthsSName[m], // Month as a three-letter abbreviation, ex: Tháng 1.
                mmmm: language.monthsFName[m], // Month as its full name, ex: Tháng một.
                yy: String(y).slice(2), // Year as last two digits; leading zero for years less than 10, ex: 99.
                yyyy: y, // Year represented by four digits, ex: 1999.
                h: H % 12 || 12, // Hours; no leading zero for single-digit hours (12-hour clock), ex: 1.
                hh: pad(H % 12 || 12), // Hours; leading zero for single-digit hours (12-hour clock), ex: 01.
                H: H, // Hours; no leading zero for single-digit hours (24-hour clock), ex: 15.
                HH: pad(H), // Hours; leading zero for single-digit hours (24-hour clock), ex: 24.
                M: M, // Minutes; no leading zero for single-digit minutes. Uppercase M unlike CF timeFormat's m to avoid conflict with months, ex: 1.
                MM: pad(M), // Minutes; leading zero for single-digit minutes. Uppercase MM unlike CF timeFormat's mm to avoid conflict with months, ex: 01.
                s: s, // Seconds; no leading zero for single-digit seconds, ex: 1.
                ss: pad(s), // Seconds; leading zero for single-digit seconds, ex: 01.
                l: pad(L, 3), // Milliseconds; gives 3 digits, ex: 100.
                L: pad(L > 99 ? Math.round(L / 10) : L), // Milliseconds; gives 2 digits, ex: 88.
                t: H < 12 ? language.timeMarker[0] : language.timeMarker[4], // Lowercase, single-character time marker string. No equivalent in CF, ex: s or c.
                tt: H < 12 ? language.timeMarker[2] : language.timeMarker[6], // Lowercase, two-character time marker string, ex: sa or ch.
                T: H < 12 ? language.timeMarker[1] : language.timeMarker[5], // Uppercase, single-character time marker string. Uppercase T unlike CF's t to allow for user-specified casing, ex: S or C.
                TT: H < 12 ? language.timeMarker[3] : language.timeMarker[7], // Uppercase, two-character time marker string. Uppercase TT unlike CF's tt to allow for user-specified casing, ex: SA or CH.
                Z: utc ? 'UTC' : (String(date).match(timezone) || ['']).pop().replace(timezoneClip, ''), // US timezone abbreviation, e.g. EST or MDT. With non-US timezones or in the Opera browser, the GMT/UTC offset is returned, e.g. GMT-0500 No equivalent in CF.
                o: (o > 0 ? '-' : '+') + pad(Math.floor(Math.abs(o) / 60) + Math.abs(o) % 60, 1), // GMT/UTC timezone offset, e.g. -0500 or +0230. No equivalent in CF.
                S: ['th', 'st', 'nd', 'rd'][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10] // The date's ordinal suffix (st, nd, rd, or th). Works well with d. No equivalent in CF.
            };

        return mask.replace(token, function ($0) {
            return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
        });
    }
});

$.extend(String.prototype, {
    stripTags: function (allowed) {
        allowed = (((allowed || '') + '').toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
        var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi;
        var commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
        return this.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
            return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
        });
    },
    truncate: function (length, truncation) {
        truncation = truncation ? truncation : '...';

        if (typeof (length) == 'string') {
            truncation = length;
            length = 20;
        }

        return this.length > length ? this.slice(0, length - truncation.length) + truncation : String(this);
    },
    blank: function () {
        return /^\s*$/.test(this || ' ');
    },
    empty: function () {
        return this === '';
    },
    left: function (n) {
        if (n <= 0)
            return '';
        else if (n > this.length)
            return this;
        else
            return this.substring(0, n);
    },
    right: function (n) {
        if (n <= 0)
            return '';
        else if (n > this.length)
            return this;
        else {
            var iLen = this.length;
            return this.substring(iLen, iLen - n);
        }
    },
    mid: function (star, n) {
        return n ? this.substr(star, n) : this.substr(star);
    },
    getAS: function (s) {
        return this.substr(0, this.search(s));
    },
    getBS: function (s) {
        return this.substr(this.search(s) + 1);
    },
    trim: function () {
        var reg = new RegExp('(^(\\s|' + String.fromCharCode(12288) + ')*)|((\\s|' + String.fromCharCode(12288) + ')*$)', 'g');
        return this.replace(reg, '');
    },
    getNum: function () {
        var nums = '0123456789';
        var result = '';
        for (var i = 0; i < this.length; i++)
            if (nums.indexOf(this.charAt(i)) >= 0)
                result += this.charAt(i);
        return parseInt(result, 10);
    },
    slug: function () {
        var str = this.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, '-').replace(/-+-/g, '-').replace(/^\-+|\-+$/g, '').toLowerCase();
        var from = 'àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđñç';
        var to = 'aaaaaaaaaaaaaaaaaeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyydnc';

        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from[i], 'g'), to[i]);
        }

        return str;
    },
    countWords: function () {
        return this.stripTags().trim().split(/\s+/).length;
    },
    ucFirst: function () {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }
});

$.extend(Array.prototype, {
    blank: function () {
        return (this.length === 0);
    },
    empty: function () {
        for (var i = 0; i <= this.length; i++) {
            this.shift();
        }
    },
    removeDuplicates: function (interator) {
        for (var i = 0; i < this.length; i++) {
            for (var j = this.length - 1; j > i; j--) {
                if ((interator && interator(this[i], this[j])) || this[i] == this[j]) {
                    this.splice(j, 1);
                }
            }
        }
    },
    swap: function (i, j) {
        var temp = this[i];
        this[i] = this[j];
        this[j] = temp;
    },
    inArray: function (value) {
        return this.indexOf(value) > -1;
    }
});

(function (define) {
    define(['jquery', 'toastr', 'bootbox'], function ($, toastr, bootbox) {
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

            var location = {
                latitude: 0,
                longitude: 0
            };

            return common = {
                location: location,
                init: init,
                showMessage: showMessage,
                getLocation: getLocation,
                showDatePicker: showDatePicker,
                showDateTimePicker: showDateTimePicker,
                multiSelect: multiSelect,
                uploadAvatar: uploadAvatar,
                uploadThumbnail: uploadThumbnail,
                rndStr: rndStr,
                addUrlParam: addUrlParam,
                showLoader: showLoader,
                hideLoader: hideLoader,
                showError: showError,
                getRowChecked: getRowChecked,
                createCode: createCode,
                setOnCategory: setOnCategory,
                listOnCategory: listOnCategory
            };

            ////////////////
            function init(config) {
                settings = $.extend(settings, config || {});
                toastr.options = settings.toastrOptions;

                //tooltip
                $('[data-toggle="tooltip"]').tooltip();

                //popover
                $('[data-toggle="popover"]').popover();

                // load ckeditor if detect element has attribute is data-editor
                $.each($('*[data-editor]'), function (index, obj) {
                    loadCKEditor($(obj).attr('id'), $.parseJSON($(obj).attr('data-editor')));
                });

                $('form').bind({
                    reset: function () {
                        if (typeof (CKEDITOR) != 'undefined') {
                            for (var instance in CKEDITOR.instances) {
                                CKEDITOR.instances[instance].setData();
                            }
                        }
                    },
                    submit: function () {
                        var form = $(this);

                        if (typeof (CKEDITOR) != 'undefined') {
                            for (var instance in CKEDITOR.instances) {
                                CKEDITOR.instances[instance].updateElement();
                                var content = CKEDITOR.instances[instance].getData();

                                $.each($(content).find('img'), function (i, o) {
                                    if ($(o).attr('data-component')) {
                                        return true;
                                    }

                                    if (!$(o).hasClass('component')) {
                                        $('<input/>').attr({
                                            name: 'image_content[]',
                                            type: 'hidden'
                                        }).val($(o).attr('src')).appendTo(form);
                                    }
                                });
                            }
                        }
                    }
                });
            }

            function showMessage(message, type, options) {
                if (typeof options === 'undefined') {
                    options = {};
                }

                switch (type) {
                    case 'error':
                        toastr.error(message, '', options);
                        break;
                    case 'success':
                        toastr.success(message, '', options);
                        break;
                    case 'warning':
                        toastr.warning(message, '', options);
                        break;
                    case 'info':
                    default:
                        toastr.info(message, '', options);
                        break;
                }
            }

            function getLocation() {
                if ('geolocation' in navigator){ //check geolocation available
                    //try to get user current location using getCurrentPosition() method
                    navigator.geolocation.getCurrentPosition(function(position) {
                        location = $.extend(location, position.coords || {});
                    }, function(error) {
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                console.log('User denied the request for Geolocation.');
                                break;
                            case error.POSITION_UNAVAILABLE:
                                console.log('Location information is unavailable.');
                                break;
                            case error.TIMEOUT:
                                console.log('The request to get user location timed out.');
                                break;
                            default:
                                console.log('An unknown error occurred.');
                                break;
                        }
                    });
                }else{
                    console.log('Browser doesn\'t support geolocation!');
                }
            }

            function showDatePicker(date_from, date_to) {
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayBtn: 'linked',
                    todayHighlight: true,
                    zIndexOffset: 99999,
                    pickerPosition: 'bottom-left'
                });

                if (typeof date_from === 'undefined') {
                    date_from = '.date_from';
                }

                if (typeof date_to === 'undefined') {
                    date_to = '.date_to';
                }

                $(date_from).datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayBtn: 'linked',
                    todayHighlight: true,
                    zIndexOffset: 99999
                }).on('hide', function(e) {
                    $(date_to).datepicker('setStartDate', e.date);
                    $(date_to).find('input').focus();
                }).on('click', function(e) {
                    $(this).datepicker('setEndDate', $(date_to).find('input').val());
                }).data('datepicker');

                $(date_to).datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayBtn: 'linked',
                    todayHighlight: true,
                    zIndexOffset: 99999
                }).on('click', function(e) {
                    $(this).datepicker('setStartDate', $(date_from).find('input').val());
                });
            }

            function showDateTimePicker(date_from, date_to) {
                $('.datetime').datetimepicker({
                    format: 'dd/mm/yyyy hh:ii',
                    autoclose: true,
                    todayBtn: 'linked',
                    todayHighlight: true,
                    minuteStep: 5,
                    zIndexOffset: 99999
                });

                if (typeof date_from === 'undefined') {
                    date_from = '.date_from';
                }

                if (typeof date_to === 'undefined') {
                    date_to = '.date_to';
                }

                $(date_from).datetimepicker({
                    format: 'dd/mm/yyyy hh:ii',
                    autoclose: true,
                    todayBtn: 'linked',
                    todayHighlight: true,
                    zIndexOffset: 99999
                }).on('hide', function (e) {
                    $(date_to).datetimepicker('setStartDate', e.date);
                    $(date_to).find('input').focus();
                }).on('click', function (e) {
                    $(this).datetimepicker('setEndDate', $(date_to).find('input').val());
                }).data('datepicker');

                $(date_to).datetimepicker({
                    format: 'dd/mm/yyyy hh:ii',
                    autoclose: true,
                    todayBtn: 'linked',
                    todayHighlight: true,
                    zIndexOffset: 99999
                }).on('click', function (e) {
                    $(this).datetimepicker('setStartDate', $(date_from).find('input').val());
                });
            }

            function multiSelect() {
                $('[data-multiselect="true"]').each(function() {
                    var _this = $(this);

                    if (_this.data('ajax') === 1) {
                        var url = _this.data('url');
                        var fields = _this.data('fields').split('|');
                        var field_id = fields[0];
                        var field_text = fields[1];
                        if (field_text.search(',') > -1) {
                            field_text = field_text.split(',');
                        }

                        _this.select2({
                            placeholder: _this.data('placeholder'),
                            theme: 'bootstrap',
                            multiple: true,
                            tags: _this.data('tags') || true,
                            width: _this.data('width') || '100%',
                            ajax: {
                                url: url,
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    return {
                                        q: params.term, // search term
                                        page: params.page
                                    };
                                },
                                processResults: function (data, params) {
                                    // parse the results into the format expected by Select2
                                    // since we are using custom formatting functions we do not need to
                                    // alter the remote JSON data, except to indicate that infinite
                                    // scrolling can be used
                                    params.page = params.page || 1;

                                    var results = [];
                                    if (data.total > 0) {
                                        var i = 0;
                                        while (i < data.items.length) {
                                            results[i] = {};
                                            results[i]['id'] = data.items[i][field_id];
                                            if ($.isArray(field_text)) {
                                                results[i]['text'] = data.items[i][field_text[0]] + ' - ' + data.items[i][field_text[1]];
                                            } else {
                                                results[i]['text'] = data.items[i][field_text];
                                            }
                                            i++;
                                        }
                                    }

                                    return {
                                        results: results,
                                        pagination: {
                                            more: (params.page * 30) < data.total
                                        }
                                    };
                                },
                                cache: true
                            },
                            minimumInputLength: 3,
                            escapeMarkup: function (markup) { return markup; },
                            templateResult: function (response) {
                                if (response.loading) {
                                    return response.text;
                                }

                                return response.text;
                            },
                            templateSelection: function (data) {
                                return data.text;
                            }
                        });
                    } else {
                        _this.select2({
                            placeholder: _this.data('placeholder'),
                            theme: 'bootstrap',
                            multiple: true,
                            tags: _this.data('tags') || true,
                            width: _this.data('width') || '100%'
                        });
                    }
                });
            }

            function uploadAvatar(setting) {
                $('#frmUserAvatar #fileUploader').uploadFile({
                    url: setting.url,
                    uploadPanel: setting.uploadPanel,
                    maxFileAllowed: setting.maxFileAllowed,
                    allowedTypes: setting.allowedTypes, //seperate with ','
                    maxFileSize: setting.maxFileSize, //in byte
                    maxFileAllowedErrorStr: setting.maxFileAllowedErrorStr,
                    dragDropStr: setting.dragDropStr,
                    dragDropErrorStr: setting.dragDropErrorStr,
                    uploadErrorStr: setting.uploadErrorStr,
                    extErrorStr: setting.extErrorStr,
                    sizeErrorStr: setting.sizeErrorStr,
                    onSuccess: function(instance, panel, files, data, xhr) {
                        if (instance.fileCounter > 0) {
                            instance.fileCounter--;
                        }

                        $('#avatar').val(data.filename);
                        panel.find('img').attr('src', setting.mediaUrl.replace('tmp.jpg', data.filename));
                        if (panel.find('.delete').length <= 0) {
                            $('<a href="#" class="delete"><i class="fas fa-trash"></i></a>').insertBefore(panel.find('img'));
                        }
                    },
                    onDelete: function(obj, instance, panel) {
                        instance.fileCounter--;

                        var image = $(panel.find('img'));
                        $('#avatar').val(image.data('old'));
                        image.attr('src', image.data('url-old'));
                        obj.remove();
                    }
                });
            }

            function uploadThumbnail(setting) {
                $('#frmThumbnail #fileUploader').uploadFile({
                    url: setting.url,
                    uploadPanel: setting.uploadPanel,
                    maxFileAllowed: setting.maxFileAllowed,
                    allowedTypes: setting.allowedTypes, //seperate with ','
                    maxFileSize: setting.maxFileSize, //in byte
                    maxFileAllowedErrorStr: setting.maxFileAllowedErrorStr,
                    dragDropStr: setting.dragDropStr,
                    dragDropErrorStr: setting.dragDropErrorStr,
                    uploadErrorStr: setting.uploadErrorStr,
                    extErrorStr: setting.extErrorStr,
                    sizeErrorStr: setting.sizeErrorStr,
                    onSuccess: function (instance, panel, files, data, xhr) {
                        if (instance.fileCounter > 0) {
                            instance.fileCounter--;
                        }

                        $('#thumbnail_url').val(data.filename);
                        panel.find('img').attr('src', setting.mediaUrl.replace('tmp.jpg', data.filename));
                        if (panel.find('.delete').length <= 0) {
                            $('<a href="#" class="delete"><i class="fas fa-trash"></i></a>').insertBefore(panel.find('img'));
                        }
                    },
                    onDelete: function (obj, instance, panel) {
                        instance.fileCounter--;

                        var image = $(panel.find('img'));
                        $('#thumbnail_url').val(image.data('old'));
                        image.attr('src', image.data('url-old'));
                        obj.remove();
                    }
                });
            }

            function rndStr(length) {
                if (length <= 0) {
                    length = 1;
                }

                var randomStr = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                var value = '';

                for (var i = 0; i < length; i++) {
                    value += randomStr.charAt(Math.floor(Math.random() * randomStr.length));
                }

                return value;
            }

            function addUrlParam(url, param, value) {
                var a = document.createElement('a'), regex = /(?:\?|&amp;|&)+([^=]+)(?:=([^&]*))*/gi;
                var match, str = [];
                a.href = url;

                while (match = regex.exec(a.search)) {
                    if (encodeURIComponent(param) != match[1]) {
                        str.push(match[1] + (match[2] ? '=' + match[2] : ''));
                    }
                }

                str.push(encodeURIComponent(param) + (value ? '=' + encodeURIComponent(value) : ''));
                a.search = str.join('&');

                return a.href;
            }

            function showLoader() {
                $('.loader-wrapper').show();
            }

            function hideLoader() {
                $('.loader-wrapper').hide();
            }

            function showError(errors, form) {
                if (typeof (form) === 'undefined') {
                    form = $('body');
                }

                $('.has-error', form).removeClass('has-error');

                for (var key in errors) {
                    var errorName = errorID = key;

                    if (key.indexOf('.') !== -1) {
                        //Splitting it with . as the separator
                        var arrError = key.split('.');
                        // The shift() method removes the first element from an array
                        var errorName = arrError.shift(); // Example: data
                        var errorID = errorName;

                        $.each(arrError, function (key, er) {
                            errorName = errorName + '[' + er + ']';
                            errorID = errorID + '_' + er;
                        });
                    }

                    if ($('#' + key + '-error', form).length > 0) {
                        $('#' + key + '-error', form).html(errors[key][0]).show();
                    } else {
                        if ($('#' + errorID, form).length > 0) {
                            $('<div id="' + key + '-error" class="invalid-feedback">' + errors[key][0] + '</div>').insertAfter($('#' + errorID, form));
                        } else {
                            $('<div id="' + key + '-error" class="invalid-feedback">' + errors[key][0] + '</div>').insertAfter($('[name="' + errorName + '"]', form));
                        }
                    }

                    $('#' + errorID, form).addClass('error').parents('.form-group').addClass('has-error');
                    $('[name="' + errorName + '"]', form).addClass('error').parents('.form-group').addClass('has-error');
                }
            }

            function getRowChecked() {
                var arrId = [];

                $('input:checkbox[data-for="chkAll"]:enabled:checked').each(function () {
                    arrId.push($(this).val());
                });

                return arrId;
            }

            function createCode() {
                // Create code for title
                $(':text[data-code="true"]').on('blur', function () {
                    var _this = $(this);
                    var code = $(_this.data('for'));

                    if (!_this.val().blank() && (code.val().blank() || (typeof (_this.data('force')) !== 'undefined' && _this.data('force') === 1))) {
                        code.prop('disabled', true);

                        $.ajax({
                            url: _this.data('link'),
                            data: {
                                title: _this.val()
                            },
                            success: function (data) {
                                code.val(data).prop('disabled', false);
                            }
                        });
                    }
                });

                $('button[data-code="true"]').on('click', function () {
                    $(':text[data-code="true"]').data('force', 1).trigger('blur');
                });
            }

            function setOnCategory(obj) {
                var parent = $(obj).parent().parent();
                var input = $('#' + $(parent).data('for'));
                if (!$(obj).hasClass('seton')) {
                    $(parent).find('span').removeClass('seton');
                    $(obj).addClass('seton');
                    $(obj).prev().prop('checked', true);
                    $(input).val($(obj).prev().val());
                } else {
                    $(obj).removeClass('seton');
                    $(input).val('');
                }
            }

            function listOnCategory(obj) {
                var parent = $(obj).parent().parent();
                var input = $('#' + $(parent).data('for'));
                if (!$(obj).prop('checked')) {
                    if ($(obj).next().hasClass('seton')) {
                        $(input).val('');
                    }
                    $(obj).next().removeClass('seton');
                }
            }

            /**
             * private function
             */
            function convertToSlug(title) {
                //Đổi chữ hoa thành chữ thường
                var slug = title.toLowerCase();

                //Đổi ký tự có dấu thành không dấu
                slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
                slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
                slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
                slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
                slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
                slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
                slug = slug.replace(/đ/gi, 'd');
                //Xóa các ký tự đặt biệt
                slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
                //Đổi khoảng trắng thành ký tự gạch ngang
                slug = slug.replace(/ /gi, "-");
                //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
                //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
                slug = slug.replace(/\-\-\-\-\-/gi, '-');
                slug = slug.replace(/\-\-\-\-/gi, '-');
                slug = slug.replace(/\-\-\-/gi, '-');
                slug = slug.replace(/\-\-/gi, '-');
                //Xóa các ký tự gạch ngang ở đầu và cuối
                slug = '@' + slug + '@';
                slug = slug.replace(/\@\-|\-\@|\@/gi, '');

                return slug;
            }

            function loadCKEditor(editorId, config) {
                var myConfig = $.extend({
                    toolbar: [
                        ['FontSize', 'Bold', 'Italic', 'Underline', '-', 'Strike', 'Subscript', 'Superscript'],
                        ['TextColor', 'BGColor'],
                        ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'],
                        ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                        ['Form', 'Checkbox', 'Radio', 'TextField', 'TextArea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
                        ['Undo', 'Redo', 'RemoveFormat', 'Find', 'Replace', 'SelectAll'],
                        ['Link', 'Unlink', 'Anchor'],
                        ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],
                        ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak', 'Iframe']
                    ],
                    resize_enabled: false,
                    language: 'en',
                    width: '100%',
                    height: '150px',
                    allowedContent: true,
                    coreStyles_bold: {
                        element: 'b',
                        overrides: 'strong'
                    },
                    coreStyles_italic: {
                        element: 'i',
                        overrides: 'em'
                    }
                }, config || {});

                CKEDITOR.replace(editorId, myConfig);
            }
        })();
    });
}(typeof define === 'function' && define.amd ? define : function(global, factory) {
    if (typeof module !== 'undefined' && module.exports) { //Node
        module.exports = factory(require('jquery'), require('toastr'), require('bootbox'));
    } else {
        window.common = factory(window.jQuery, window.toastr, window.bootbox);
    }
}));
