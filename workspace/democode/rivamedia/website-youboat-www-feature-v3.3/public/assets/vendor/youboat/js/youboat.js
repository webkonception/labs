// @TODO REFACTORING ASAP

/*
 * Locale
 */
var locale = $('html').attr('lang');

/*
 * AJAX
 */
var localCache = {
    // timeout for cache in millis
    // @type {number}
    timeout: 30000,
    // @type {{_: number, data: {}}}
    data: {},
    remove: function (url) {
        delete localCache.data[url];
    },
    exist: function (url) {
        //console.log('exist in cache for url' + url);
        return !!localCache.data[url] && ((new Date().getTime() - localCache.data[url]._) < localCache.timeout);
    },
    get: function (url) {
        //console.log('Getting in cache for url' + url);
        return localCache.data[url].data;
    },
    set: function (url, cachedData, callback) {
        localCache.remove(url);
        localCache.data[url] = {
            _: new Date().getTime(),
            data: cachedData
        };
        if ($.isFunction(callback)) callback(cachedData);
    }
};

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    cache: true
});

$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
    if (options.cache) {
        var complete = originalOptions.complete || $.noop,
            url = originalOptions.url + '?' + $.param(originalOptions.data);
        //remove jQuery cache as we have our own localCache
        options.cache = false;
        options.beforeSend = function () {
            if (localCache.exist(url)) {
                complete(localCache.get(url));
                return false;
            }
            return true;
        };
        options.complete = function (data, textStatus) {
            localCache.set(url, data, complete);
        };
    }
    var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

    if (token) {
        jqXHR.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
    }
});
/*$.ajaxPrefilter(function (options, originalOptions, xhr) { // this will run before each request
 var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

 if (token) {
 return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
 }
 });*/

$(document).ajaxError(function (event, jqxhr, settings, thrownError) {
    if (settings.url == "ajax/missing.html") {
        $("div.log").text("Triggered ajaxError handler.");
    }
    //#console.error("Triggered ajaxError handler.", settings.url, thrownError);
});

if ('function' !== typeof recaptchaCallback) {
    function recaptchaCallback(response) {
        if (response.length > 0) {
            var $This = $('#g-recaptcha-response');
            $This.parents('.form-group')
                .removeClass('has-error')
                .find('.help-block').remove();
            //var $Form = $('form[role="form"]');
            var $Form = $This.parents('form');
            if ($Form.attr('id') != 'form_bod') {
                if ('function' === typeof checkInputsFilled) {
                    checkInputsFilled($Form);
                }
            }
        }
    }
}

var setRequiredInputs = function ($oElm) {
    $oElm.find('[required="required"]').each(function (index, element) {
        var id = $(element).attr('id');
        var $Label = $('label[for="' + id + '"]');

        if($Label.length>0) {
            if($Label.hasClass('checkbox') || $Label.hasClass('radio')) {
                $Label.append(' *').addClass('accent-color-danger').parents('.form-group').addClass('required');
            } else {
                $Label.text($Label.text() + ' *').addClass('accent-color-danger').parents('.form-group').addClass('required');
            }
        }
    });
};

var unsetRequiredInputs = function ($oElm) {
    $oElm.find('[data-required="required"]').each(function (index, element) {
        var id = $(element).attr('id');
        var $Label = $('label[for="' + id + '"]');
        if($Label.length>0) {
            $Label.text($Label.text().replace(' *', '')).removeClass('accent-color-danger').parents('.form-group').removeClass('required');
        }
    });
};

var checkInputsFilled = function ($oElm) {
    var $Inputs = $(':required', $oElm).not('[type="hidden"]');

    var $SubmitButton = $('button', $oElm);
    var count = 0;
    $Inputs.filter(function (index) {
        count += ($(this).val() && $(this).val() != '') ? 1 : 0;
    });

    /*if (count === $Inputs.length) {
     $('button', $oElm).removeAttr('disabled', 'disabled');
     } else {
     $('button', $oElm).attr('disabled', 'disabled');
     }*/

    var exceptionsClasses = '.hideShowPassword-toggle,.btn-exception';
    if (count !== $Inputs.length || $oElm.find('.has-error').length > 0) {
        $('button', $oElm).not(exceptionsClasses).attr('disabled', 'disabled').removeClass('btn-success');
        if (!$('button', $oElm).not(exceptionsClasses).hasClass('btn-default')) {
            $('button', $oElm).not(exceptionsClasses).addClass('btn-primary');
        }
    } else {
        $('button', $oElm).not(exceptionsClasses).removeAttr('disabled', 'disabled').removeClass('btn-primary').addClass('btn-success');
    }
};

function formatCountry(country) {
    if (!country.id) {
        return country.text;
    }
    var $Country = $(
        '<span><img src="/assets/img/flags/' + country.element.value.toLowerCase() + '.png" class="img-flag" />&nbsp;' + country.text + '</span>'
    );
    return $Country;
}

function formatRepo (repo) {
    if (repo.loading) {
        var $Repo = $(
        '<i class="fa fa-circle-o-notch fa-spin fa-fw"></i><span class="sr-only">Loading...</span>'
        );
        return $Repo;
    }
    return repo.text;
}

function formatRepoSelection (repo) {
    return repo.text;
}

var DataSort = function(data) {
    data.sort(function(a,b){
        a = a.text.toLowerCase();
        b = b.text.toLowerCase();
        if(a > b) {
            return 1;
        } else if (a < b) {
            return -1;
        }
        return 0;
    });
};

var customSorter = function(data) {
    if ($('#' + data[0].element.parentElement.id).hasClass('nosort')) {
        return data;
    } else {
        return DataSort(data);
    }
};

var selectMultiple = function ($oElm) {
    var token = $('meta[name="csrf-token"]').attr('content');

    if ('undefined' === typeof $oElm) {
        $oElm = $('select[multiple="multiple"].select2');
    }
    //if(jQuery.browser.mobile) {
        // mobile mode
        //$.fn.select2.defaults.set("minimumResultsForSearch", "Infinity");
        //$oElm.select2({minimumResultsForSearch: Infinity});
    //}
    $oElm.each(function(index, element) {
        var $This = $(element);
        if ($This.data("ajax--url") !== undefined) {
            $This.select2({
                ajax: {
                    type: "POST",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var queryParameters = {
                            name: params.term, // search term
                            locale: locale,
                            token: token
                        };
                        return queryParameters;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
                //sorter: customSorter,
                minimumInputLength: 2,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection,
                escapeMarkup: function (markup) {
                    return markup; // let our custom formatter work
                }
            });
        } else {
            $This.select2({
                //sorter: customSorter,
                templateResult: formatRepo,
                tags: true,
                allowClear: true,
                tokenSeparators: [',']
            });
        }
    });
};

var selectSingle = function ($oElm) {
    var token = $('meta[name="csrf-token"]').attr('content');

    if ('undefined' === typeof $oElm) {
        $oElm = $('select.select2').not('[multiple="multiple"],.noselect2');
    }
    //if (jQuery.browser.mobile) {
        // mobile mode
        //$.fn.select2.defaults.set("minimumResultsForSearch", "Infinity");
        //$oElm.select2({minimumResultsForSearch: Infinity});
    //}
    $oElm.each(function(index, element) {
        var $This = $(element);
        if ($This.data("ajax--url") !== undefined) {
            $This.select2({
                ajax: {
                    type: "POST",
                    dataType: 'json',
                    params: { // extra parameters that will be passed to ajax
                        contentType: "application/json; charset=utf-8",
                    },
                    delay: 250,
                    data: function (params) {
                        var queryParameters = {
                            name: params.term, // search term
                            locale: locale,
                            token: token
                        };
                        return queryParameters;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
                //sorter: customSorter,
                minimumInputLength: 2,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection,
                escapeMarkup: function (markup) {
                    return markup; // let our custom formatter work
                },
                allowClear: true
            });
        } else {
            $This.select2({
                sorter: customSorter,
                templateResult: formatRepo,
                allowClear: true
            });
        }
    });
};

var setHasSuccesParent = function ($oElm) {
    if ($oElm) {
        $oElm.on('change', function (event) {
           //@#console.log('setHasSuccesParent chnage');
            var $This = $(this);
            var val = event.target.value;
            if (val.length > 0 && 0 != val) {
                $This.parents('.form-group').addClass('has-success');
            } else {
                $This.parents('.form-group').removeClass('has-success');
            }
        });
    }
};

var setHasSuccess = function($oElm) {
    $oElm.removeAttr('disabled');
    $oElm.parents('.form-group').addClass('has-success');
    $oElm.parents('.form-group').slideDown('fast');
};

var unsetHasSuccess = function($oElm, unhide) {
    $oElm.val(null);

    $oElm.parents('.form-group').removeClass('has-success');
    if (unhide) {
        $oElm.removeAttr('disabled');
        $oElm.parents('.form-group').slideDown('fast');
    } else {
        $oElm.attr('disabled', 'disabled');
        $oElm.parents('.form-group').slideUp('fast');
    }
};

var ajaxFill = function (what, id, params, callback) {
    if ('undefined' == typeof params) {
        var params = {};
    }
    var url = what;
    if (!/ajax-/.test(url)) {
        url = '/' + locale + '/ajax-' + what;
    }
    if ('string' === typeof id) {
        var $oElm = $('#' + id);
    } else {
        $oElm = id;
    }
    var token = $('meta[name="csrf-token"]').attr('content');
    $.extend({'token':token}, params);

    $oElm.parents('.form-group').removeClass('has-success');
    $oElm.empty();

    //ajax
    var fillDatas = function (data) {
        data = data.responseJSON;
        if ('undefined' != typeof data && data.length != 0) {
            var placeholder = ('undefined' != typeof $oElm.attr('data-placeholder')) ? $oElm.attr('data-placeholder') : ('undefined' != typeof $oElm.attr('data-header')) ? $oElm.attr('data-header') : '- Select -';
            if (placeholder != '') {
                $oElm.append('<option value="" selected >' + placeholder + '</option>');
            }
            //data = DataSort(data);
            $.each(data, function (Id, Name) {
                if('object' === typeof Name) {
                    Id = Name.id;
                    Name = Name.name;
                }
                $oElm.append('<option value="' + Id + '">' + Name + '</option>');
            });
            $oElm.removeAttr('disabled');
            $oElm.parents('.form-group').slideDown('fast');
        } else {
            $oElm.attr('disabled', 'disabled');
            $oElm.parents('.form-group').slideUp('fast');
        }
        if (callback) {
            callback.call();
        }
    };
    var jqxhr = $.ajax({
        method: "GET",
        url: url,
        data: params,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        cache: true,
        complete: fillDatas
    });
};

var flatten = function () {
    var flat = [];
    for (var i = 0; i < arguments.length; i++) {
        if (arguments[i] instanceof Array) {
            flat.push.apply(flat, flatten.apply(this, arguments[i]));
        } else {
            flat.push(arguments[i]);
        }
    }
    return flat;
};

var ajaxForm = function ($oElm) {
    $oElm.each(function() {
        var $ThisForm = $(this);

        var AjaxFomComplete = function (request) {
            var obj = request.responseJSON;
            //var obj = JSON.parse(request.responseText);
            var statusCss = '';
            var message = '';
            if(obj.length != 0 && (obj.success && typeof obj.nomodal !== 'undefined' && obj.nomodal)) {
                // no di gui di
            } else {
                var referrer = '';
                var msgModalId = '#msgModalAjax';
                if (obj.message_referrer) {
                    referrer = obj.message_referrer;
                    msgModalId = '#msgModalAjax_'+ referrer;
                }
                if (obj.errors) {
                    msgModalId = '#msgModalAjax_error';
                }
                var $Modal = $(msgModalId);

                if (obj.length != 0) {
                    if (obj.success) {
                        statusCss = 'alert-success';
                    } else {
                        statusCss = 'alert-error';
                    }
                    if ('object' === typeof obj.message) {
                        var obj_array = $.map(obj.message, function (value, index) {
                            if (value != '') {
                                $('[name="' + index + '"]').parents('div.form-group').removeClass('has-success').addClass('has-error');
                            }
                            return [value];
                        });

                        var flatten_array = flatten(obj_array);
                        if ($.isArray(flatten_array)) {
                            message = '<ul>';
                            $.each(flatten_array, function (index, value) {
                                message += '<li>' + value + '</li>';
                            });
                            message += '</ul>';
                        }
                    } else {
                        message = obj.message;
                    }
                    if (obj.message_title) {
                        $Modal.find('.modal-header .title').remove();
                        $Modal.find('.modal-header').append(obj.message_title);
                    }
                } else {
                    statusCss = 'alert-error';
                    message = 'Internal Error'
                }
                $Modal.find('.alert')
                    .removeClass('alert-ajax').removeClass('alert-error').removeClass('alert-success')
                    .addClass(statusCss)
                    .html(message);
                $Modal.modal('show');
                $Modal.on('hidden.bs.modal', function () {
                    $('body,html').animate({scrollTop: $('#' + referrer).offset().top}, 750, 'easeOutExpo');
                    if (obj.errors) {
                        $.each(obj.errors,function(index){
                            $('#' + referrer).find('[name="' + index + '"]').parents('.form-group').removeClass('has-success').addClass('has-error');
                        });
                    }
                });
                if (obj.success && typeof obj.type === 'undefined') {
                    $ThisForm.html($Modal.find('.modal-body').html());
                }
            }
        };
        $ThisForm.on('submit', function (event) {
            event.preventDefault();
            var $This = $(this);
            var ajaxUrl = $This.attr('action');
            var formParams = $This.serialize();

            var jqxhr = $.ajax({
                method: "POST",
                url: ajaxUrl,
                data: formParams,
                //contentType: "application/json; charset=utf-8",
                //dataType: "json",
                cache: true,
                complete: AjaxFomComplete
            });
        });
    });
};


var gotoElementTop = function ($oElm) {
    var element_top;
    if($(window).width() > 767 ) {
        element_top = $oElm.offset().top;
    } else {
        var $SiteHeaderWrapper = $(".site-header-wrapper");
        var siteHeaderWrapperHeight = $SiteHeaderWrapper.height();
        element_top = $oElm.offset().top - siteHeaderWrapperHeight;
    }
    $('body,html').animate({scrollTop: element_top}, 750, 'easeOutExpo');
};

$(document).ready(function () {

    // Manage Google Analytics Event Click
    var $GAEventItems = jQuery('.GA_event');
    $GAEventItems.on('click', function (event) {
        //console.log(' == GA_event ==');
        var $This = jQuery(this),
            trackingUrl = ('undefined' != typeof $This.attr('data-url')) ? $This.attr('data-url') : '',
            trackingName = ('undefined' != typeof $This.attr('data-ga')) ? $This.attr('data-ga') : ( ('undefined' != typeof $This.attr('title')) ? $This.attr('title') : ''),
            trackingType = 'GA',
            trackingAction = 'event',
            callBack = '';
        if ($This.hasClass('blank') || $This.hasClass('external')) {
            event.preventDefault();
            callBack = function () {
                return false;
                window.open($This.attr('href'));
            };
        } else if (('INPUT' === $This.get(0).nodeName || 'BUTTON' === $This.get(0).nodeName) && 'submit' === $This.attr('type')) {
            event.preventDefault();
            callBack = function () {
                $This.parents('form').submit();
            };
        } else if ('INPUT' !== $This.get(0).nodeName) {
            event.preventDefault();
            if (!trackingUrl) {
                trackingUrl = $This.attr('href');
            }
            callBack = function () {
                jQuery(location).attr("href", trackingUrl);
            };
        } else {
            callBack = ('undefined' != typeof $This.attr('data-callback')) ? $This.attr('data-callback') : '';
        }
        Tracking.sendTag(trackingName, trackingType, trackingAction, callBack);
    });

    var $ViewPhones = $('.seller-contact-widget .view_phones');
    var $SellerPhones = $('.seller-contact-widget .phones');
    $SellerPhones.hide();
    $('a', $ViewPhones).on('click', function(event) {
        event.preventDefault();
        var $This = $(this);
        var trackingName = ('undefined' != typeof $This.attr('data-ga')) ? $This.attr('data-ga') : ( ('undefined' != typeof $This.attr('title')) ? $This.attr('title') : ''),
            trackingType = 'GA',
            trackingAction = 'event',
            callBack = function () {
                $This.parent().slideUp('fast');
                $This.parent().next('.phones').slideDown('fast');
            };
        Tracking.sendTag(trackingName, trackingType, trackingAction, callBack);
    });

    /***********************
     *
     * HomePage Google Analytics Tacking Event with CustomDimensions
     */
    //var $SearchForms = $('#search_form_for_sale, #form_filters');
    var $SearchForms = $('.homepage #form_filters');
    $SearchForms.each(function() {
        var $ThisForm = $(this);
        var $SubmitBtn = $('button[type="submit"]', $ThisForm);

        $SubmitBtn.on('click', function (event) {event.preventDefault();
            var $This = $(this);
            if ('undefined' != typeof $This.attr('data-ga')) {
                var datas = $This.parents('form').serializeArray();
                var trackingCustomDimensions = {};
                var dimension = {};
                var arr = [];
                jQuery.each(datas, function( i, field ) {
                    if(field.value) {
                        dimension = JSON.parse('{"' + field.name + '":"' + field.value + '"}');
                        arr.push(field.value);
                    }
                    $.extend(trackingCustomDimensions, dimension);
                });
                //var arr = $.map(trackingCustomDimensions, function(el) {if('' != el) return el });
                if (arr.length > 2 && 'undefined' !== typeof trackingCustomDimensions) { // because country_code & currency already setted in hidden field
                    event.preventDefault();
                    var trackingName = $This.attr('data-ga'),
                        trackingType = 'GA',
                        trackingAction = 'event',
                        callBack = function () {
                            $This.parents('form').submit();
                        };

                    var elements = trackingName.split("~");
                    var tagCategory = ('' === elements[0] || undefined === elements[0]) ? '' : elements[0],
                        tagAction = ('' === elements[1] || undefined === elements[1]) ? '' : elements[1];

                    var Tracker = ('' !== GATracker || undefined !== GATracker) ? GATracker : 'YBTracker';
                    var CustomDimensionsTracking = function() { ga(Tracker + '.send', 'event', tagCategory, tagAction, trackingCustomDimensions)};

                    Tracking.CustomDimensions(trackingName, trackingType, trackingAction, callBack, trackingCustomDimensions);
                }
            }
        });
    });

    $('a#btn_send_enquiry').on('click', function (event) {
        event.preventDefault();
        gotoElementTop($('#form_enquiry'));
    });

    var $AjaxForms = $('.ajax-form');
    ajaxForm($AjaxForms);

    //if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
    /*if (jQuery.browser.mobile) {
        $('select.selectpicker').selectpicker('mobile');
    }*/
    /*$('.void').on('click', function(event) {
        event.preventDefault();
    });*/
    //$.fn.select2.defaults.set("ajax--cache", true);

    /*if( $(window).width() <= 768 ) {
        $('.main-navigation.toggle-menu > ul > li > a').each(function() {
            var $This = $(this);
            $This.next('ul.dropdown').remove();
            $This.removeClass('sf-with-ul');
            $This.on('click', function(event) {
                alert($This.attr('href'));
            });
        });
    }*/

    /*
     * Replace the Broken Images with a Default Image
     */
    $('img').on('error', function () {
        //$(this).attr('src', '/assets/img/boat.png');
    });

    /*
     * Form disable buton submit when one filed required not filled
     * show required Inputs Fields
     * check Inputs Fields Filleds
     */
    var $Form = $('form[role="form"]');
    if ($Form.length > 0) {
        $Form.each(function(){
            var $ThisForm = $(this);
            $('input[type="password"]').not('.password-input').val('').removeAttr('value');

            setRequiredInputs($ThisForm);
            if($ThisForm.attr('id') != 'form_bod') {
                checkInputsFilled($ThisForm);
            }

            var $InputsNotRequired = $('input,select,textarea', $ThisForm).not('[required="required"]').not('[type="hidden"]');
            var $InputsRequired = $(':required', $ThisForm).not('[type="hidden"]');
            //var $InputsRequiredCheckbox = $('[type="checkbox"]:required', $ThisForm);
            var $InputsCheckbox = $('[type="checkbox"]', $ThisForm);
            //var $InputsRequiredRadio = $('[type="radio"]:required', $ThisForm);
            var $InputsRadio = $('[type="radio"]', $ThisForm);

            $InputsNotRequired.each(function(index, element) {
                $(element).on('blur', function (event) {
                    event.preventDefault();
                    var $This = $(this);
                    if($This.val() && $This.val() != '') {
                        $This.parent('.input-group').parents('div.form-group')
                            .removeClass('has-error')
                            .addClass('has-success');
                    } else {
                        $This.parent('.input-group').parents('div.form-group')
                            .removeClass('has-success');
                    }
                });
            });

            $InputsRequired.each(function(index, element) {
                $(element).on('blur', function (event) {
                    event.preventDefault();
                    var $This = $(this);
                    if($This.val() && $This.val() != '') {
                        $This.parent('.input-group').parents('div.form-group')
                            .removeClass('has-error')
                            .addClass('has-success');
                    } else {
                        $This.parent('.input-group').parents('div.form-group')
                            .removeClass('has-success')
                            .addClass('has-error');
                    }
                    if($ThisForm.attr('id') != 'form_bod') {
                        checkInputsFilled($ThisForm);
                    }
                });
                $(element).on('change', function (event) {
                    event.preventDefault();
                    var $This = $(this);
                    if($This.val() && $This.val() != '') {
                        $This.parent('.input-group').parents('div.form-group')
                            .removeClass('has-error')
                            .addClass('has-success');
                    } else {
                        $This.parent('.input-group').parents('div.form-group')
                            .removeClass('has-success')
                            .addClass('has-error');
                    }
                    if($ThisForm.attr('id') != 'form_bod') {
                        checkInputsFilled($ThisForm);
                    }
                });
                $(element).on('focus', function (event) {
                    event.preventDefault();
                    var $This = $(this);
                    $This.parent('.input-group').parents('div.form-group')
                        .removeClass('has-error')
                        .find('.help-block').remove();
                });
            });
            $InputsCheckbox.each(function(index, element) {
                $(element).on('change', function (event) {
                    event.preventDefault();
                    var $This = $(this);
                    var name = $This.attr('name');
                    var $HiddenInput = $This.next('input[type="hidden"]');

                    if( $This.is(':checked') || $This.checked || $This.prop("checked" )){
                        $HiddenInput.val(1);
                        $This.attr('value',1);
                        $This.attr('checked','checked');
                        $This.parents('.checkbox')
                            .addClass('has-success');

                        //if($This[0].hasAttribute('required')){
                        if($This.is(':required')){
                            $This.parents('.checkbox').removeClass('has-error');
                        }
                    } else {
                        $HiddenInput.val("");
                        $This.removeAttr('value');
                        $This.removeAttr('checked');
                        $This.parents('.checkbox')
                            .removeClass('has-success');

                        //if($This[0].hasAttribute('required')){
                        if($This.is(':required')){
                            $This.parents('.checkbox').addClass('has-error');
                        }
                    }
                    if($ThisForm.attr('id') != 'form_bod') {
                        checkInputsFilled($ThisForm);
                    }
                });
            });
            $InputsRadio.each(function(index, element) {
                $(element).on('change', function (event) {
                    event.preventDefault();
                    var $This = $(this);

                    if( $This.is(':checked') || $This.checked || $This.prop("checked" )){
                        $This.val('1');
                        $This.parents('.radio')
                            .removeClass('has-error')
                            .addClass('has-success');
                    } else {
                        $This.val('');
                        $This.parents('.radio')
                            .removeClass('has-success')
                            .addClass('has-error');
                    }
                    if($ThisForm.attr('id') != 'form_bod') {
                        checkInputsFilled($ThisForm);
                    }
                });
            });
        });
    }

    /*
     * Tips
     */
    $('.blank').on('click', function (event) {
        event.preventDefault();
        window.open(this.href);
    });

    $('a.btn-modal').on('click', function (event) {
        event.preventDefault();
        var $This = $(this);
        var url = $This.attr('href');
        url += ' #content';

        var $ModalTarget = $($This.attr('data-target'));
        var $ModalBody = $('.modal-body', $ModalTarget);
        var callBack = $This.attr('data-callback');

        $ModalBody.load(url, function (result) {
            $ModalTarget.modal({show: true});
            $ModalTarget.on('shown.bs.modal', function (e) {
                eval(callBack)();
            });
        });
    });

    /*
     * Loading text for button.btn
     */
    /*$('button.btn[type="submit"]', 'form[role="form"]').not('.btn-io').on('click', function () {
        var $This = $(this);
        $This
            .attr('data-loading-text', '<i class="fa fa-cog fa-spin fa-fw"></i>in progress ...')
            .button('loading').delay(1000).queue(function () {
            //$(this).button('reset');
        });
    });*/

    /*
     * Auto-select input email password to reset
     */
    $('input[name="query"], input[type="email"], input[type="password"]').on('focus', function() {
        $(this).select();
    });

    $('input[type="password"].password-input').each(function(index, element) {
        var $This = $(element);
        if($This.val().length > 0) {
            $This.trigger('change');
        }
    });

    /*
     * Swicth checbox custom
     */
    $('input[type="checkbox"].switch').on('change', function() {
        var $This = $(this);
        if ($This.is(':checked')) {
            if ($This.filter("[data-toggle='collapse']")) {
                $($This.attr('data-target')).collapse('show');
            }
        } else {
            if ($This.filter("[data-toggle='collapse']")) {
                $($This.attr('data-target')).collapse('hide');
            }
        }
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(window).resize(function () {

    });

    /*
     * datepicker
     */
    if(typeof moment === "function") {
        /*$('.datepicker').datepicker({
         autoclose: true,
         dateFormat: "{{ config('quickadmin.date_format_jquery') }}"
         });
         $('.datetimepicker').datetimepicker({
         autoclose: true,
         dateFormat: "{{ config('quickadmin.date_format_jquery') }}",
         timeFormat: "{{ config('quickadmin.time_format_jquery') }}"
         });*/
        $('.datepicker').not('#start_date').not('#end_date').datetimepicker({
            //locale: 'en_gb',
            format: "YYYY-MM-DD"
        });
        $('.datetimepicker').not('#start_date').not('#end_date').datetimepicker({
            format: "YYYY-MM-DD HH:MM:SS"
        });

        $('#start_date.datepicker').datetimepicker({
            format: "YYYY-MM-DD"
        });
        $('#end_date.datepicker').datetimepicker({
            format: "YYYY-MM-DD",
            useCurrent: false //Important! See issue #1075
        });

        $('#start_date.datetimepicker').datetimepicker({
            format: "YYYY-MM-DD HH:MM:SS"
        });
        $('#end_date.datetimepicker').datetimepicker({
            format: "YYYY-MM-DD HH:MM:SS",
            useCurrent: false //Important! See issue #1075
        });
        $("#start_date").on("dp.change", function (event) {
            $('#end_date').data("DateTimePicker").minDate(event.date);
        });
        $("#end_date").on("dp.change", function (event) {
            $('#start_date').data("DateTimePicker").maxDate(event.date);
        });
    }

    //var multiSelect = new selectMultiple();
    //var singleSelect = new selectSingle();

    if ($('select[name="countries_ids[]"]').length) {
        var multiSelectCountries = new selectMultiple($('select[name="countries_ids[]"]'));
    }

    if ($('select[name="country_id"]').length) {
        var singleSelectCountry = new selectSingle($('select[name="country_id"]'));
    }

    $(window).resize(function () {
        //selectMultiple();
        if ($('select[name="countries_ids[]"]').length) {
            selectMultiple($('select[name="countries_ids[]"]'));
        }
        //selectSingle();
        if ($('select[name="country_id"]').length) {
            var singleSelectCountry = new selectSingle($('select[name="country_id"]'));
        }
    });

    /*if ($('select').length) {
        var $Select = $('select');

        // setHasSuccesParent
        $('select').not('.select2').each(function() {
            var $This = $(this);
            var sHSP_select = new setHasSuccesParent($This);
        });
    }*/

    /*
     * Inputs Range
     */
    if ($('input[type="range"]').length) {
        var $InputRangeGroup = $('input[type="range"]');

        $InputRangeGroup.on('change', function(event) {
            event.preventDefault();
            var $This = $(this);
            if($This.val()) {
                $('#' + $This.attr('id').replace('_range', '')).val($This.val());
            }
        });
    }

    var $SearchFiltersForm = $('#Search-Filters');
    $('select', $SearchFiltersForm).on('change', function(event) {
        var $This = $(this);
        if($This.val() && $This.val() != '') {
            $('#page', $SearchFiltersForm).val('');
        }
    });

    var $ActionsBar = $('.actions-bar');
    var $ViewCountChoice = $ActionsBar.find('.view-count-choice');

    $('a.btn', $ViewCountChoice).on('click', function(event) {
        event.preventDefault();
        var $This = $(this);
        var max = $(this).text();
        var $FormFilters = $('#form_filters');
        $('a.btn.active', $ViewCountChoice).removeClass('active');
        $This.addClass('active');
        if (!$('#max').length) {
            var $Max = $('<input>', {'type': 'hidden','name': 'max', 'id': 'max'});
            $FormFilters.append($Max);
        } else {
            var $Max = $('#max', $FormFilters);
        }
        $Max.val(max);
        $FormFilters.trigger('submit');
    });

    var $ResultsSorter = $ActionsBar.find('.results-sorter');
    $('.dropdown-menu a', $ResultsSorter).on('click', function(event) {
        event.preventDefault();
        var $This = $(this);
        var sort = $(this).attr('data-sort');
        var $FormFilters = $('#form_filters');
        $('.dropdown-menu li.active', $ResultsSorter).removeClass('active');
        $This.parents('li').addClass('active');
        if (!$('#sort_by').length) {
            var $SortBy = $('<input>', {'type': 'hidden','name': 'sort_by', 'id': 'sort_by'});
            $FormFilters.append($SortBy);
        } else {
            var $SortBy = $('#sort_by', $FormFilters);
        }
        $SortBy.val(sort);
        $FormFilters.trigger('submit');
    });

    var $ActionsBar = $('.actions-bar');
    var $ViewFormatChoice = $ActionsBar.find('.view-format-choice');

    $('a.btn', $ViewFormatChoice).on('click', function(event) {
        event.preventDefault();
        var $This = $(this);
        var sort = $(this).attr('data-sort');
        var $FormFilters = $('#form_filters');
        $('a.btn.active', $ViewFormatChoice).removeClass('active');
        $This.addClass('active');
        if (!$('#results_view').length) {
            var $ResultsView = $('<input>', {'type': 'hidden','name': 'results_view', 'id': 'results_view'});
            $FormFilters.append($ResultsView);
        } else {
            var $ResultsView = $('#results_view', $FormFilters);
        }
        $ResultsView.val(sort);
        //$FormFilters.trigger('submit');
    });

    var $Pagination = $('.pagination');
    $('a', $Pagination).on('click', function(event) {
        event.preventDefault();
        var $This = $(this);
        var page = $(this).text();
        var $FormFilters = $('#form_filters');
        if (!$('#page').length) {
            var $Page = $('<input>', {'type': 'hidden','name': 'page', 'id': 'page'});
            $FormFilters.append($Page);
        } else {
            var $Page = $('#page', $FormFilters);
        }
        $Page.val(page);
        $FormFilters.trigger('submit');
    });

    /*$('#msgModal.success', $('body.newsletter')).on('hidden.bs.modal', function () {
        window.location.href = '/';
    });*/

    $('#msgModal.success', $('body')).on('hidden.bs.modal', function () {
        var $This = $(this);
        var $FormReferrer = $('#' + $This.attr('data-referrer'));
        if ($FormReferrer.length) {
            var $ModalBodyMsg = $This.find('.modal-body .alert').html();
            $ModalBodyMsg = '<div class="panel"><div class="panel-body bg-success text-success">' + $ModalBodyMsg + '</div></div>';
            $FormReferrer.html($ModalBodyMsg);
            //$('form', $('body')).remove();
            $('#msgModal.success').remove();
        }
    });
    $('#msgModal.error,#msgModal.warning', $('body')).on('hidden.bs.modal', function () {
        var $This = $(this);
        var $FormReferrer = $('#' + $This.attr('data-referrer'));
        if ($FormReferrer.length) {
            var form_top;
            if ($(window).width() > 767) {
                form_top = $('.has-error', $FormReferrer).eq(0).offset().top;
            } else {
                var $SiteHeaderWrapper = $(".site-header-wrapper");
                var siteHeaderWrapperHeight = $SiteHeaderWrapper.height();
                form_top = $('.has-error', $FormReferrer).eq(0).offset().top - siteHeaderWrapperHeight;
            }
            $('body,html').animate({scrollTop: form_top}, 750, 'easeOutExpo');
        }
    });

    $('input[type="password"]:required').on('blur', function(event) {
        var $This = $(this);
        if($This.val().length) {
            $This.closest('.form-group').removeClass('has-error').addClass('has-success');
        }  else {
            $This.closest('.form-group').removeClass('has-success').addClass('has-error');
        }
    });
});


/**
 * @classDescription Tracking Javascript Class
 */
var Tracking = {
    sendTag: function (trackingName, trackingType, trackingAction, callBack) {
        'use strict';

        var Manage = {
            UA: UA_GA,
            cookieName: cookieName_GA,
            cookieDomain: cookieDomain_GA,
            Tracker: ('' !== GATracker || undefined !== GATracker) ? GATracker : 'YBTracker',
            _Run: function () {
                try {
                    if ('GA' === trackingType || 'google' === trackingType || 'stats_google' === trackingType) {
                        if ('' !== trackingName || undefined !== trackingName) {
                            var elements = trackingName.split("~");

                            if ('event' === trackingAction) {
                                var tagCategory = ('' === elements[0] || undefined === elements[0]) ? '' : elements[0],
                                    tagAction = ('' === elements[1] || undefined === elements[1]) ? '' : elements[1],
                                    tmp = ('' === tagAction || undefined === tagAction) ? '' : tagAction.split("|"),
                                    optLabel = ('' === tmp[1] || undefined === tmp[1]) ? '' : tmp[1],
                                    optValue = 0;

                                tagAction = ('' === tmp[0] || undefined === tmp[0]) ? '' : tmp[0];

                                //console.info('%c** Tracking.sendTag ** event, tagCategory::' + tagCategory + ', tagAction::' + tagAction + ', optLabel::' + optLabel + ', optValue::' + optValue, 'color:teal');
                                ga(Manage.Tracker + '.send', 'event', tagCategory, tagAction, optLabel, optValue);
                                // optValue is a number
                            } else if ('page' === trackingAction) {
                                var tagTitle = ('' === elements[0] || undefined === elements[0]) ? '' : elements[0],
                                    trackingPage = ('' === elements[1] || undefined === elements[1]) ? '' : elements[1],
                                    tagPage = (undefined !== trackingPage) ? trackingPage : window.location.pathname + window.location.search + window.location.hash;

                                //console.info('%c** Tracking.sendTag ** pageview,  page::' + tagPage + ', title::' + tagTitle, 'color:teal');
                                ga('create', Manage.UA, 'auto', {
                                    'name': Manage.Tracker,
                                    'cookieName': Manage.cookieName,
                                    'cookieDomain': Manage.cookieDomain,
                                    'cookieExpires': 60 * 60 * 24 * 28
                                });
                                ga(Manage.Tracker + '.send', {
                                    'hitType': 'pageview',
                                    'page': tagPage,
                                    'title': tagTitle
                                });
                            }
                        }
                        if ('function' === typeof callBack) {
                            callBack.call();
                        }
                    }
                } catch (exception) {
                    //console.error('!! sendTag No tracking !!', exception);
                }
            }
        };
        Manage._Run();
    },
    CustomDimensions: function (trackingName, trackingType, trackingAction, callBack, trackingCustomDimensions) {
        'use strict';

        var Manage = {
            UA: UA_GA,
            cookieName: cookieName_GA,
            cookieDomain: cookieDomain_GA,
            Tracker: ('' !== GATracker || undefined !== GATracker) ? GATracker : 'YBTracker',
            _Run: function () {
                try {
                    if ('GA' === trackingType || 'google' === trackingType || 'stats_google' === trackingType) {
                        if ('' !== trackingName || undefined !== trackingName) {
                            var elements = trackingName.split("~");
                            var tagCategory = ('' === elements[0] || undefined === elements[0]) ? '' : elements[0],
                                tagAction = ('' === elements[1] || undefined === elements[1]) ? '' : elements[1];

                            var CustomDimensionsTracking = function() { ga(Manage.Tracker + '.send', trackingAction, tagCategory, tagAction, trackingCustomDimensions)};

                            $.when( CustomDimensionsTracking() ).then(
                                function( status ) {
                                    //console.log(status + ", things are going well", 'color:teal');
                                    if ('function' === typeof callBack) {
                                        callBack.call();
                                    }
                                },
                                function( status ) {
                                    //console.log(status + ", you fail this time", 'color:red');
                                },
                                function( status ) {
                                    //console.log(status, 'color:blue');
                                }
                            );
                        }
                    }
                } catch (exception) {
                    //console.error('!! sendTag No tracking !!', exception);
                }
            }
        };
        Manage._Run();
    }
};
