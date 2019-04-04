/*!
 * jQuery Form Plugin
 * version: 3.51.0-2014.06.20
 * Requires jQuery v1.5 or later
 * Copyright (c) 2014 M. Alsup
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Project repository: https://github.com/malsup/form
 * Dual licensed under the MIT and GPL licenses.
 * https://github.com/malsup/form#copyright-and-license
 */
!function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],e):e("undefined"!=typeof jQuery?jQuery:window.Zepto)}(function(e){"use strict";function t(t){var r=t.data;t.isDefaultPrevented()||(t.preventDefault(),e(t.target).ajaxSubmit(r))}function r(t){var r=t.target,a=e(r);if(!a.is("[type=submit],[type=image]")){var n=a.closest("[type=submit]");if(0===n.length)return;r=n[0]}var i=this;if(i.clk=r,"image"==r.type)if(void 0!==t.offsetX)i.clk_x=t.offsetX,i.clk_y=t.offsetY;else if("function"==typeof e.fn.offset){var o=a.offset();i.clk_x=t.pageX-o.left,i.clk_y=t.pageY-o.top}else i.clk_x=t.pageX-r.offsetLeft,i.clk_y=t.pageY-r.offsetTop;setTimeout(function(){i.clk=i.clk_x=i.clk_y=null},100)}function a(){if(e.fn.ajaxSubmit.debug){var t="[jquery.form] "+Array.prototype.join.call(arguments,"");window.console&&window.console.log?window.console.log(t):window.opera&&window.opera.postError&&window.opera.postError(t)}}var n={};n.fileapi=void 0!==e("<input type='file'/>").get(0).files,n.formdata=void 0!==window.FormData;var i=!!e.fn.prop;e.fn.attr2=function(){if(!i)return this.attr.apply(this,arguments);var e=this.prop.apply(this,arguments);return e&&e.jquery||"string"==typeof e?e:this.attr.apply(this,arguments)},e.fn.ajaxSubmit=function(t){function r(r){var a,n,i=e.param(r,t.traditional).split("&"),o=i.length,s=[];for(a=0;o>a;a++)i[a]=i[a].replace(/\+/g," "),n=i[a].split("="),s.push([decodeURIComponent(n[0]),decodeURIComponent(n[1])]);return s}function o(a){for(var n=new FormData,i=0;i<a.length;i++)n.append(a[i].name,a[i].value);if(t.extraData){var o=r(t.extraData);for(i=0;i<o.length;i++)o[i]&&n.append(o[i][0],o[i][1])}t.data=null;var s=e.extend(!0,{},e.ajaxSettings,t,{contentType:!1,processData:!1,cache:!1,type:u||"POST"});t.uploadProgress&&(s.xhr=function(){var r=e.ajaxSettings.xhr();return r.upload&&r.upload.addEventListener("progress",function(e){var r=0,a=e.loaded||e.position,n=e.total;e.lengthComputable&&(r=Math.ceil(a/n*100)),t.uploadProgress(e,a,n,r)},!1),r}),s.data=null;var c=s.beforeSend;return s.beforeSend=function(e,r){r.data=t.formData?t.formData:n,c&&c.call(this,e,r)},e.ajax(s)}function s(r){function n(e){var t=null;try{e.contentWindow&&(t=e.contentWindow.document)}catch(r){a("cannot get iframe.contentWindow document: "+r)}if(t)return t;try{t=e.contentDocument?e.contentDocument:e.document}catch(r){a("cannot get iframe.contentDocument: "+r),t=e.document}return t}function o(){function t(){try{var e=n(g).readyState;a("state = "+e),e&&"uninitialized"==e.toLowerCase()&&setTimeout(t,50)}catch(r){a("Server abort: ",r," (",r.name,")"),s(k),j&&clearTimeout(j),j=void 0}}var r=f.attr2("target"),i=f.attr2("action"),o="multipart/form-data",c=f.attr("enctype")||f.attr("encoding")||o;w.setAttribute("target",p),(!u||/post/i.test(u))&&w.setAttribute("method","POST"),i!=m.url&&w.setAttribute("action",m.url),m.skipEncodingOverride||u&&!/post/i.test(u)||f.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"}),m.timeout&&(j=setTimeout(function(){T=!0,s(D)},m.timeout));var l=[];try{if(m.extraData)for(var d in m.extraData)m.extraData.hasOwnProperty(d)&&l.push(e.isPlainObject(m.extraData[d])&&m.extraData[d].hasOwnProperty("name")&&m.extraData[d].hasOwnProperty("value")?e('<input type="hidden" name="'+m.extraData[d].name+'">').val(m.extraData[d].value).appendTo(w)[0]:e('<input type="hidden" name="'+d+'">').val(m.extraData[d]).appendTo(w)[0]);m.iframeTarget||v.appendTo("body"),g.attachEvent?g.attachEvent("onload",s):g.addEventListener("load",s,!1),setTimeout(t,15);try{w.submit()}catch(h){var x=document.createElement("form").submit;x.apply(w)}}finally{w.setAttribute("action",i),w.setAttribute("enctype",c),r?w.setAttribute("target",r):f.removeAttr("target"),e(l).remove()}}function s(t){if(!x.aborted&&!F){if(M=n(g),M||(a("cannot access response document"),t=k),t===D&&x)return x.abort("timeout"),void S.reject(x,"timeout");if(t==k&&x)return x.abort("server abort"),void S.reject(x,"error","server abort");if(M&&M.location.href!=m.iframeSrc||T){g.detachEvent?g.detachEvent("onload",s):g.removeEventListener("load",s,!1);var r,i="success";try{if(T)throw"timeout";var o="xml"==m.dataType||M.XMLDocument||e.isXMLDoc(M);if(a("isXml="+o),!o&&window.opera&&(null===M.body||!M.body.innerHTML)&&--O)return a("requeing onLoad callback, DOM not available"),void setTimeout(s,250);var u=M.body?M.body:M.documentElement;x.responseText=u?u.innerHTML:null,x.responseXML=M.XMLDocument?M.XMLDocument:M,o&&(m.dataType="xml"),x.getResponseHeader=function(e){var t={"content-type":m.dataType};return t[e.toLowerCase()]},u&&(x.status=Number(u.getAttribute("status"))||x.status,x.statusText=u.getAttribute("statusText")||x.statusText);var c=(m.dataType||"").toLowerCase(),l=/(json|script|text)/.test(c);if(l||m.textarea){var f=M.getElementsByTagName("textarea")[0];if(f)x.responseText=f.value,x.status=Number(f.getAttribute("status"))||x.status,x.statusText=f.getAttribute("statusText")||x.statusText;else if(l){var p=M.getElementsByTagName("pre")[0],h=M.getElementsByTagName("body")[0];p?x.responseText=p.textContent?p.textContent:p.innerText:h&&(x.responseText=h.textContent?h.textContent:h.innerText)}}else"xml"==c&&!x.responseXML&&x.responseText&&(x.responseXML=X(x.responseText));try{E=_(x,c,m)}catch(y){i="parsererror",x.error=r=y||i}}catch(y){a("error caught: ",y),i="error",x.error=r=y||i}x.aborted&&(a("upload aborted"),i=null),x.status&&(i=x.status>=200&&x.status<300||304===x.status?"success":"error"),"success"===i?(m.success&&m.success.call(m.context,E,"success",x),S.resolve(x.responseText,"success",x),d&&e.event.trigger("ajaxSuccess",[x,m])):i&&(void 0===r&&(r=x.statusText),m.error&&m.error.call(m.context,x,i,r),S.reject(x,"error",r),d&&e.event.trigger("ajaxError",[x,m,r])),d&&e.event.trigger("ajaxComplete",[x,m]),d&&!--e.active&&e.event.trigger("ajaxStop"),m.complete&&m.complete.call(m.context,x,i),F=!0,m.timeout&&clearTimeout(j),setTimeout(function(){m.iframeTarget?v.attr("src",m.iframeSrc):v.remove(),x.responseXML=null},100)}}}var c,l,m,d,p,v,g,x,y,b,T,j,w=f[0],S=e.Deferred();if(S.abort=function(e){x.abort(e)},r)for(l=0;l<h.length;l++)c=e(h[l]),i?c.prop("disabled",!1):c.removeAttr("disabled");if(m=e.extend(!0,{},e.ajaxSettings,t),m.context=m.context||m,p="jqFormIO"+(new Date).getTime(),m.iframeTarget?(v=e(m.iframeTarget),b=v.attr2("name"),b?p=b:v.attr2("name",p)):(v=e('<iframe name="'+p+'" src="'+m.iframeSrc+'" />'),v.css({position:"absolute",top:"-1000px",left:"-1000px"})),g=v[0],x={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var r="timeout"===t?"timeout":"aborted";a("aborting upload... "+r),this.aborted=1;try{g.contentWindow.document.execCommand&&g.contentWindow.document.execCommand("Stop")}catch(n){}v.attr("src",m.iframeSrc),x.error=r,m.error&&m.error.call(m.context,x,r,t),d&&e.event.trigger("ajaxError",[x,m,r]),m.complete&&m.complete.call(m.context,x,r)}},d=m.global,d&&0===e.active++&&e.event.trigger("ajaxStart"),d&&e.event.trigger("ajaxSend",[x,m]),m.beforeSend&&m.beforeSend.call(m.context,x,m)===!1)return m.global&&e.active--,S.reject(),S;if(x.aborted)return S.reject(),S;y=w.clk,y&&(b=y.name,b&&!y.disabled&&(m.extraData=m.extraData||{},m.extraData[b]=y.value,"image"==y.type&&(m.extraData[b+".x"]=w.clk_x,m.extraData[b+".y"]=w.clk_y)));var D=1,k=2,A=e("meta[name=csrf-token]").attr("content"),L=e("meta[name=csrf-param]").attr("content");L&&A&&(m.extraData=m.extraData||{},m.extraData[L]=A),m.forceSync?o():setTimeout(o,10);var E,M,F,O=50,X=e.parseXML||function(e,t){return window.ActiveXObject?(t=new ActiveXObject("Microsoft.XMLDOM"),t.async="false",t.loadXML(e)):t=(new DOMParser).parseFromString(e,"text/xml"),t&&t.documentElement&&"parsererror"!=t.documentElement.nodeName?t:null},C=e.parseJSON||function(e){return window.eval("("+e+")")},_=function(t,r,a){var n=t.getResponseHeader("content-type")||"",i="xml"===r||!r&&n.indexOf("xml")>=0,o=i?t.responseXML:t.responseText;return i&&"parsererror"===o.documentElement.nodeName&&e.error&&e.error("parsererror"),a&&a.dataFilter&&(o=a.dataFilter(o,r)),"string"==typeof o&&("json"===r||!r&&n.indexOf("json")>=0?o=C(o):("script"===r||!r&&n.indexOf("javascript")>=0)&&e.globalEval(o)),o};return S}if(!this.length)return a("ajaxSubmit: skipping submit process - no element selected"),this;var u,c,l,f=this;"function"==typeof t?t={success:t}:void 0===t&&(t={}),u=t.type||this.attr2("method"),c=t.url||this.attr2("action"),l="string"==typeof c?e.trim(c):"",l=l||window.location.href||"",l&&(l=(l.match(/^([^#]+)/)||[])[1]),t=e.extend(!0,{url:l,success:e.ajaxSettings.success,type:u||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},t);var m={};if(this.trigger("form-pre-serialize",[this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-pre-serialize trigger"),this;if(t.beforeSerialize&&t.beforeSerialize(this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSerialize callback"),this;var d=t.traditional;void 0===d&&(d=e.ajaxSettings.traditional);var p,h=[],v=this.formToArray(t.semantic,h);if(t.data&&(t.extraData=t.data,p=e.param(t.data,d)),t.beforeSubmit&&t.beforeSubmit(v,this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSubmit callback"),this;if(this.trigger("form-submit-validate",[v,this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-submit-validate trigger"),this;var g=e.param(v,d);p&&(g=g?g+"&"+p:p),"GET"==t.type.toUpperCase()?(t.url+=(t.url.indexOf("?")>=0?"&":"?")+g,t.data=null):t.data=g;var x=[];if(t.resetForm&&x.push(function(){f.resetForm()}),t.clearForm&&x.push(function(){f.clearForm(t.includeHidden)}),!t.dataType&&t.target){var y=t.success||function(){};x.push(function(r){var a=t.replaceTarget?"replaceWith":"html";e(t.target)[a](r).each(y,arguments)})}else t.success&&x.push(t.success);if(t.success=function(e,r,a){for(var n=t.context||this,i=0,o=x.length;o>i;i++)x[i].apply(n,[e,r,a||f,f])},t.error){var b=t.error;t.error=function(e,r,a){var n=t.context||this;b.apply(n,[e,r,a,f])}}if(t.complete){var T=t.complete;t.complete=function(e,r){var a=t.context||this;T.apply(a,[e,r,f])}}var j=e("input[type=file]:enabled",this).filter(function(){return""!==e(this).val()}),w=j.length>0,S="multipart/form-data",D=f.attr("enctype")==S||f.attr("encoding")==S,k=n.fileapi&&n.formdata;a("fileAPI :"+k);var A,L=(w||D)&&!k;t.iframe!==!1&&(t.iframe||L)?t.closeKeepAlive?e.get(t.closeKeepAlive,function(){A=s(v)}):A=s(v):A=(w||D)&&k?o(v):e.ajax(t),f.removeData("jqxhr").data("jqxhr",A);for(var E=0;E<h.length;E++)h[E]=null;return this.trigger("form-submit-notify",[this,t]),this},e.fn.ajaxForm=function(n){if(n=n||{},n.delegation=n.delegation&&e.isFunction(e.fn.on),!n.delegation&&0===this.length){var i={s:this.selector,c:this.context};return!e.isReady&&i.s?(a("DOM not ready, queuing ajaxForm"),e(function(){e(i.s,i.c).ajaxForm(n)}),this):(a("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)")),this)}return n.delegation?(e(document).off("submit.form-plugin",this.selector,t).off("click.form-plugin",this.selector,r).on("submit.form-plugin",this.selector,n,t).on("click.form-plugin",this.selector,n,r),this):this.ajaxFormUnbind().bind("submit.form-plugin",n,t).bind("click.form-plugin",n,r)},e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")},e.fn.formToArray=function(t,r){var a=[];if(0===this.length)return a;var i,o=this[0],s=this.attr("id"),u=t?o.getElementsByTagName("*"):o.elements;if(u&&!/MSIE [678]/.test(navigator.userAgent)&&(u=e(u).get()),s&&(i=e(':input[form="'+s+'"]').get(),i.length&&(u=(u||[]).concat(i))),!u||!u.length)return a;var c,l,f,m,d,p,h;for(c=0,p=u.length;p>c;c++)if(d=u[c],f=d.name,f&&!d.disabled)if(t&&o.clk&&"image"==d.type)o.clk==d&&(a.push({name:f,value:e(d).val(),type:d.type}),a.push({name:f+".x",value:o.clk_x},{name:f+".y",value:o.clk_y}));else if(m=e.fieldValue(d,!0),m&&m.constructor==Array)for(r&&r.push(d),l=0,h=m.length;h>l;l++)a.push({name:f,value:m[l]});else if(n.fileapi&&"file"==d.type){r&&r.push(d);var v=d.files;if(v.length)for(l=0;l<v.length;l++)a.push({name:f,value:v[l],type:d.type});else a.push({name:f,value:"",type:d.type})}else null!==m&&"undefined"!=typeof m&&(r&&r.push(d),a.push({name:f,value:m,type:d.type,required:d.required}));if(!t&&o.clk){var g=e(o.clk),x=g[0];f=x.name,f&&!x.disabled&&"image"==x.type&&(a.push({name:f,value:g.val()}),a.push({name:f+".x",value:o.clk_x},{name:f+".y",value:o.clk_y}))}return a},e.fn.formSerialize=function(t){return e.param(this.formToArray(t))},e.fn.fieldSerialize=function(t){var r=[];return this.each(function(){var a=this.name;if(a){var n=e.fieldValue(this,t);if(n&&n.constructor==Array)for(var i=0,o=n.length;o>i;i++)r.push({name:a,value:n[i]});else null!==n&&"undefined"!=typeof n&&r.push({name:this.name,value:n})}}),e.param(r)},e.fn.fieldValue=function(t){for(var r=[],a=0,n=this.length;n>a;a++){var i=this[a],o=e.fieldValue(i,t);null===o||"undefined"==typeof o||o.constructor==Array&&!o.length||(o.constructor==Array?e.merge(r,o):r.push(o))}return r},e.fieldValue=function(t,r){var a=t.name,n=t.type,i=t.tagName.toLowerCase();if(void 0===r&&(r=!0),r&&(!a||t.disabled||"reset"==n||"button"==n||("checkbox"==n||"radio"==n)&&!t.checked||("submit"==n||"image"==n)&&t.form&&t.form.clk!=t||"select"==i&&-1==t.selectedIndex))return null;if("select"==i){var o=t.selectedIndex;if(0>o)return null;for(var s=[],u=t.options,c="select-one"==n,l=c?o+1:u.length,f=c?o:0;l>f;f++){var m=u[f];if(m.selected){var d=m.value;if(d||(d=m.attributes&&m.attributes.value&&!m.attributes.value.specified?m.text:m.value),c)return d;s.push(d)}}return s}return e(t).val()},e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})},e.fn.clearFields=e.fn.clearInputs=function(t){var r=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var a=this.type,n=this.tagName.toLowerCase();r.test(a)||"textarea"==n?this.value="":"checkbox"==a||"radio"==a?this.checked=!1:"select"==n?this.selectedIndex=-1:"file"==a?/MSIE/.test(navigator.userAgent)?e(this).replaceWith(e(this).clone(!0)):e(this).val(""):t&&(t===!0&&/hidden/.test(a)||"string"==typeof t&&e(this).is(t))&&(this.value="")})},e.fn.resetForm=function(){return this.each(function(){("function"==typeof this.reset||"object"==typeof this.reset&&!this.reset.nodeType)&&this.reset()})},e.fn.enable=function(e){return void 0===e&&(e=!0),this.each(function(){this.disabled=!e})},e.fn.selected=function(t){return void 0===t&&(t=!0),this.each(function(){var r=this.type;if("checkbox"==r||"radio"==r)this.checked=t;else if("option"==this.tagName.toLowerCase()){var a=e(this).parent("select");t&&a[0]&&"select-one"==a[0].type&&a.find("option").selected(!1),this.selected=t}})},e.fn.ajaxSubmit.debug=!1});

/**
 * jQuery Upload File Plugin
 * version: 2.0.7
 * @requires jQuery v1.5 or later & form plugin
 * Copyright (c) 2013 Ravishanker Kusuma
 * http://hayageek.com/
 **/
(function($) {
    var feature = {};
    feature.fileapi = $('<input type="file"/>').get(0).files !== undefined;
    feature.formdata = window.FormData !== undefined;

    $.fn.uploadFile = function(options) {
        // This is the easiest way to have default options.
        var s = $.extend({
            // These are the defaults.
            url: '',
            method: 'POST',
            enctype: 'multipart/form-data',
            formData: {},
            returnType: 'json',
            fileName: 'file',
            dynamicFormData: function() {
                return {};
            },
            maxFileSize: -1,
            maxFileAllowed: 10,
            allowedTypes: '*',
            multiple: true,
            dragDrop: true,
            autoSubmit: true,
            showAbort: true,
            showProgress: true,
            onSelect: function(files) {
                return true;
            },
            onSubmit: function(files, xhr) {
                return true;
            },
            onLoad: null,
            onSuccess: null,
            onError: null,
            onDelete: null,
            uploadClass: 'ajax-file-upload',
            uploadPanel: null,
            dragDropStr: 'Drag & drop files here.',
            noteStr: '',
            abortStr: 'Stop',
            cancelStr: 'Cancel',
            delSelector: '.delete',
            infoSelector: '.photoinfo',
            dragDropErrorStr: 'Your browser does not support drag and drop.',
            maxFileAllowedErrorStr: 'Only uploaded to a maximum of <b>%s</b> files.',
            extErrorStr: 'The file <b>%s</b> is not supported, only supports the following: %s.',
            sizeErrorStr: 'The size of the file <b>%s</b> exceeds the allowed size of %s.',
            uploadErrorStr: 'Uploading is not allowed.'
        }, options);

        var formGroup = s.uploadClass + '-' + (new Date().getTime());
        this.formGroup = formGroup;
        this.hide();

        if (s.uploadPanel) {
        	this.uploadPanel = $(s.uploadPanel);
        } else {
	        if (this.next('.' + s.uploadClass + '-panel').length > 0) {
	            this.uploadPanel = this.next('.' + s.uploadClass + '-panel');
	        } else {
	            this.uploadPanel = $('<div class="' + s.uploadClass + '-panel"></div>');
	            this.after(this.uploadPanel);
	        }
        }
        this.fileCounter = this.uploadPanel.find(s.infoSelector).length;

        if (this.next('.' + s.uploadClass + '-log').length > 0) {
            this.errorLog = this.next('.' + s.uploadClass + '-log');
        } else {
            this.errorLog = $('<div class="' + s.uploadClass + '-log"></div>');
            this.after(this.errorLog);
        }
        this.responses = [];
        //check drag drop enabled.
        if (!feature.formdata) {
            s.dragDrop = false;
        }

        var obj = this;
        var uploadLabel = $('<div>' + $(this).html() + '</div>');
        $(uploadLabel).addClass(s.uploadClass + '-button');

        if ($.isFunction(s.onLoad)) {
            s.onLoad(obj, obj.uploadPanel);
        }
        //bind delete
        this.uploadPanel.find(s.delSelector).click(function(evt) {
        	evt.preventDefault();
            if ($.isFunction(s.onDelete)) {
                s.onDelete(this, obj, obj.uploadPanel);
            }
        });

        //wait form ajax Form plugin and initialize
        (function checkAjaxFormLoaded() {
            if ($.fn.ajaxForm) {
                if (s.dragDrop) {
                    var dragDrop = $('<div class="' + s.uploadClass + '-dragdrop"></div>');
                    $(obj).before(dragDrop);
                    if (!s.dragDropStr.blank()) {
                    	$(dragDrop).append($('<div class="pb10">' + s.dragDropStr + '</div>'));
                    }
                    $(dragDrop).append(uploadLabel);
                    if (!s.noteStr.blank()) {
                    	$(dragDrop).append($('<div class="pt10">' + s.noteStr + '</div>'));
                    }
                    setDragDropHandlers(obj, s, dragDrop);
                } else {
                    $(obj).before(uploadLabel);
                }

                createCustomInputFile(obj, formGroup, s, uploadLabel);
            } else {
                window.setTimeout(checkAjaxFormLoaded, 10);
            }
        })();

        this.startUpload = function() {
            $('.' + this.formGroup).each(function(i, items) {
                if ($(this).is('form'))
                    $(this).submit();
            });
        };

        this.getResponses = function() {
            return this.responses;
        };

        function setDragDropHandlers(obj, s, ddObj) {
            ddObj.on('dragenter', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).css('border-color', '#a5a5c7');
            });

            ddObj.on('dragover', function(e) {
                e.stopPropagation();
                e.preventDefault();
            });

            ddObj.on('drop', function(e) {
                $(this).css('border-color', '#a5a5c7');
                e.preventDefault();
                var files = e.originalEvent.dataTransfer.files;
                if (!s.multiple && files.length > 1) {
                    writeErrorLog(obj, s.dragDropErrorStr, true);
                    return;
                }
                if ($.isFunction(s.onSelect) && s.onSelect(files) === false) {
                    return;
                }
                serializeAndUploadFiles(s, obj, files);
            });

            $(document).on('dragenter', function(e) {
                e.stopPropagation();
                e.preventDefault();
            });

            $(document).on('dragover', function(e) {
                e.stopPropagation();
                e.preventDefault();
                obj.css('border-color', '#a5a5c7');
            });

            $(document).on('drop', function(e) {
                e.stopPropagation();
                e.preventDefault();
                obj.css('border-color', '#a5a5c7');
            });
        }

        function getSizeStr(size) {
            var sizeStr = '';
            var sizeKB = size / 1024;
            if (parseInt(sizeKB) > 1024) {
                var sizeMB = sizeKB / 1024;
                sizeStr = sizeMB.toFixed(2) + ' MB';
            } else {
                sizeStr = sizeKB.toFixed(2) + ' KB';
            }
            return sizeStr;
        }

        function serializeData(extraData) {
            var serialized = [];
            if (jQuery.type(extraData) === 'string') {
                serialized = extraData.split('&');
            } else {
                serialized = $.param(extraData).split('&');
            }
            var len = serialized.length;
            var result = [];
            var i, part;
            for (i = 0; i < len; i++) {
                serialized[i] = serialized[i].replace(/\+/g, ' ');
                part = serialized[i].split('=');
                result.push([decodeURIComponent(part[0]), decodeURIComponent(part[1])]);
            }
            return result;
        }

        function serializeAndUploadFiles(s, obj, files) {
            for (var i = 0; i < files.length; i++) {
                if (obj.fileCounter === s.maxFileAllowed) {
                    writeErrorLog(obj, vsprintf(s.maxFileAllowedErrorStr, [s.maxFileAllowed]), true);
                    break;
                }
                if (!isFileTypeAllowed(obj, s, files[i].name)) {
                    writeErrorLog(obj, vsprintf(s.extErrorStr, [files[i].name, s.allowedTypes]), false);
                    continue;
                }
                if (s.maxFileSize !== -1 && files[i].size > s.maxFileSize) {
                    writeErrorLog(obj, vsprintf(s.sizeErrorStr, [files[i].name, getSizeStr(s.maxFileSize)]), false);
                    continue;
                }
                var ts = s;
                var fd = new FormData();
                var fileName = s.fileName.replace('[]', '');
                fd.append(fileName, files[i]);
                var extraData = s.formData;
                if (extraData) {
                    var sData = serializeData(extraData);
                    for (var j = 0; j < sData.length; j++) {
                        if (sData[j]) {
                            fd.append(sData[j][0], sData[j][1]);
                        }
                    }
                }
                ts.fileData = fd;

                var pd = new createProgressDiv(obj);
                pd.filename.html(files[i].name);
                var form = $('<form style="display: block; position: absolute; left: 150px;" class="' + obj.formGroup + '" method="' + s.method + '" action="' + s.url + '" enctype="' + s.enctype + '"></form>');
                form.appendTo('body');
                var fileArray = [];
                fileArray.push(files[i].name);
                ajaxFormSubmit(form, ts, pd, fileArray, obj);
                obj.fileCounter++;
            }
        }

        function isFileTypeAllowed(obj, s, fileName) {
            var fileExtensions = s.allowedTypes.toLowerCase().split(',');
            var ext = fileName.split('.').pop().toLowerCase();
            if (s.allowedTypes !== '*' && jQuery.inArray(ext, fileExtensions) < 0) {
                return false;
            }

            return true;
        }

        function vsprintf(str, params) {
            for (var i = 0; i < params.length; i++) {
                str = str.replace(/%s/, params[i]);
            }

            return str;
        }

        function createCustomInputFile(obj, group, s, uploadLabel) {
            var fileUploadId = 'ajax-upload-id-' + (new Date().getTime());

            var form = $('<form method="' + s.method + '" action="' + s.url + '" enctype="' + s.enctype + '"></form>');
            var fileInputStr = '<input type="file" id="' + fileUploadId + '" name="' + s.fileName + '" />';
            if (s.multiple && feature.formdata) {
            	// if it does not endwith
                if (s.fileName.indexOf('[]') !== s.fileName.length - 2) {
                    s.fileName += '[]';
                }
                fileInputStr = '<input type="file" id="' + fileUploadId + '" name="' + s.fileName + '" multiple />';
            }
            var fileInput = $(fileInputStr).appendTo(form);

            fileInput.change(function() {
                var fileArray = [];

                //support reading files
                if (this.files) {
                    for (i = 0; i < this.files.length; i++) {
                        fileArray.push(this.files[i].name);
                    }
                    if ($.isFunction(s.onSelect) && s.onSelect(this.files) === false) {
                        return;
                    }
                } else {
                    var filenameStr = $(this).val();
                    var flist = [];
                    fileArray.push(filenameStr);
                    if (!isFileTypeAllowed(obj, s, filenameStr)) {
                        writeErrorLog(obj, vsprintf(s.extErrorStr, [filenameStr, s.allowedTypes]), false);
                        return;
                    }
                    //fallback for browser without FileAPI
                    flist.push({name: filenameStr, size: 'NA'});
                    if ($.isFunction(s.onSelect) && s.onSelect(flist) === false) {
                        return;
                    }
                }

                uploadLabel.unbind('click');
                createCustomInputFile(obj, group, s, uploadLabel);

                form.addClass(group);
                //use HTML5 support and split file submission
                if (feature.fileapi && feature.formdata) {
                    form.removeClass(group); //Stop Submitting when.
                    var files = this.files;
                    serializeAndUploadFiles(s, obj, files);
                } else {
                    var fileList = '';
                    for (var i = 0; i < fileArray.length; i++) {
                        if (obj.fileCounter === s.maxFileAllowed) {
                            writeErrorLog(obj, vsprintf(s.maxFileAllowedErrorStr, [s.maxFileAllowed]), true);
                            break;
                        }
                        fileList += fileArray[i] + '<br />';
                        obj.fileCounter++;
                    }
                    var pd = new createProgressDiv(obj);
                    pd.filename.html(fileList);
                    ajaxFormSubmit(form, s, pd, fileArray, obj);
                }
            });

            var uwidth = $(uploadLabel).width() + parseInt($(uploadLabel).css('padding-left')) + parseInt($(uploadLabel).css('padding-right'));
            if (uwidth === 10) {
                uwidth = 120;
            }

            var uheight = uploadLabel.height() + parseInt($(uploadLabel).css('padding-top')) + parseInt($(uploadLabel).css('padding-bottom'));
            if (uheight === 10) {
                uheight = 35;
        	}

            uploadLabel.css({ position: 'relative', cursor: 'default' });
            fileInput.css({
                position: 'absolute',
                cursor: 'default',
                top: '0px',
                width: uwidth,
                height: uheight,
                left: '0px',
                'z-index': '100',
                opacity: '0.0',
                filter: 'alpha(opacity=0)',
                '-ms-filter': 'alpha(opacity=0)',
                '-khtml-opacity': '0.0',
                '-moz-opacity': '0.0'
            });

            form.css({
                margin: 0,
                padding: 0,
                display: 'block',
                position: 'absolute',
                left: '-550px'
            });
            form.appendTo('body');

            if (navigator.appVersion.indexOf('MSIE ') !== -1) {//IE Browser
                uploadLabel.attr('for', fileUploadId);
            } else {
                uploadLabel.on('click', function() {
                    fileInput.trigger('click');
                });
            }
        }

        function writeErrorLog(obj, logStr, autoClose) {
            $('<div class="' + s.uploadClass + '-error">' + logStr + '</div>').appendTo(obj.errorLog);
            if (autoClose) {
                setTimeout(function() {
                    obj.errorLog.slideDown('slow', function () {
                        $(this).html('');
                    });
                }, 1500);
            }
        }

        function createProgressDiv(obj) {
            this.statusBar = $('<div class="' + s.uploadClass + '-statusbar"></div>');
            this.header = $('<div class="' + s.uploadClass + '-header"></div>').appendTo(this.statusBar);
            this.filename = $('<div class="' + s.uploadClass + '-filename"></div>').appendTo(this.header);
            this.cancel = $('<a href="#" class="' + s.uploadClass + '-cancel">' + s.cancelStr + '</a>').appendTo(this.header).hide();
            this.abort = $('<a href="#" class="' + s.uploadClass + '-abort "' + obj.formGroup + '">' + s.abortStr + '</a>').appendTo(this.header).hide();
            this.progress = $('<div class="' + s.uploadClass + '-progress">').appendTo(this.statusBar).hide();
            this.progressBar = $('<div class="' + s.uploadClass + '-bar"></div>').appendTo(this.progress);
            $('<div class="' + s.uploadClass + '-clearfix"></div>').appendTo(this.statusBar);
            obj.uploadPanel.append(this.statusBar);

            return this;
        }

        function ajaxFormSubmit(form, s, pd, fileArray, obj) {
            var options = {
                cache: false,
                contentType: false,
                processData: false,
                forceSync: false,
                data: s.formData,
                formData: s.fileData,
                dataType: s.returnType,
                beforeSubmit: function(formData, $form, options) {
                    if ($.isFunction(s.onSubmit) && s.onSubmit(obj, fileArray) !== false) {
                        var dynData = s.dynamicFormData();
                        if (dynData) {
                            var sData = serializeData(dynData);
                            if (sData) {
                                for (var j = 0; j < sData.length; j++) {
                                    if (sData[j]) {
                                        if (s.fileData !== undefined) {
                                            options.formData.append(sData[j][0], sData[j][1]);
                                        } else {
                                            options.data[sData[j][0]] = sData[j][1];
                                        }
                                    }
                                }
                            }
                        }

                        return true;
                    }
                    pd.statusBar.append('<div class="' + s.uploadClass + '-error">' + s.uploadErrorStr + '</div>');
                    form.remove();
                    pd.cancel.click(function (evt) {
                    	evt.preventDefault();
                        obj.fileCounter--;
                        pd.statusBar.remove();
                    });

                    return false;
                },
                beforeSend: function(xhr, o) {
                    if ($.isFunction(s.onSubmit)) {
                        s.onSubmit(obj, fileArray, xhr);
                    }
                    pd.progress.show();
                    if (s.showAbort) {
                        pd.abort.show();
                        pd.abort.click(function(evt) {
                        	evt.preventDefault();
                            obj.fileCounter--;
                            xhr.abort();
                        });
                    }
                    //For iframe based push
                    if (!feature.formdata) {
                        pd.progressBar.width('5%');
                    } else {
                        pd.progressBar.width('1%'); //Fix for small files
                    }
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    //Fix for smaller file uploads in MAC
                    if (percentComplete > 98)
                        percentComplete = 100;

                    var percentVal = percentComplete + '%';
                    if (percentComplete > 1)
                        pd.progressBar.width(percentVal);
                    if (s.showProgress) {
                        pd.progressBar.html(percentVal);
                        pd.progressBar.css('text-align', 'center');
                    }
                },
                success: function(data, message, xhr) {
                    obj.responses.push(data);
                    pd.progressBar.width('100%');
                    if (s.showProgress) {
                        pd.progressBar.html('100%');
                        pd.progressBar.css('text-align', 'center');
                    }

                    pd.abort.hide();
                    if (!!data.error === false) {
                        if ($.isFunction(s.onSuccess)) {
                            s.onSuccess(obj, obj.uploadPanel, fileArray, data, xhr);
                        }
                        pd.statusBar.hide('slow');
                        pd.statusBar.remove();
                        form.remove();
                        //bind delete
                        obj.uploadPanel.find(s.delSelector).click(function(evt) {
                        	evt.preventDefault();
                            if ($.isFunction(s.onDelete)) {
                                s.onDelete(this, obj, obj.uploadPanel);
                            }
                        });
                    } else {
                        if ($.isFunction(s.onError)) {
                            s.onError(obj, obj.uploadPanel, fileArray, 'error', data.message);
                        }
                        obj.fileCounter--;
                        pd.progress.hide();
                        pd.statusBar.append('<div class="' + s.uploadClass + '-error">' + data.message + '</div>');
                        setTimeout(function() {
                            pd.statusBar.remove();
                            form.remove();
                        }, 2500);
                    }
                },
                error: function(xhr, status, errMsg) {
                    pd.abort.hide();
                    //we aborted it
                    if (xhr.statusText === 'abort') {
                        pd.statusBar.hide('slow');
                    } else {
                        if ($.isFunction(s.onError)) {
                            s.onError(obj, obj.uploadPanel, fileArray, status, errMsg);
                        }
                        pd.progress.hide();
                        pd.statusBar.append('<div class="' + s.uploadClass + '-error">' + errMsg + '</div>');
                        setTimeout(function() {
                            pd.statusBar.remove();
                        }, 2500);
                    }
                    obj.fileCounter--;
                    form.remove();
                }
            };

            if (s.autoSubmit) {
                form.ajaxSubmit(options);
            } else {
                if (s.showCancel) {
                    pd.cancel.show();
                    pd.cancel.click(function (evt) {
                    	evt.preventDefault();
                        obj.fileCounter--;
                        form.remove();
                        pd.statusBar.remove();
                    });
                }
                form.ajaxForm(options);
            }
        }

        return this;
    };
}(jQuery));
