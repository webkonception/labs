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
    //console.log(options.cache);
    //console.log(options);
    //console.log(originalOptions);
    //console.log(jqXHR);

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
        '<span><img src="/assets/img/flags/' + country.element.value.toUpperCase() + '.png" class="img-flag" />&nbsp;' + country.text + '</span>'
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
                            //locale: locale,
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
            if ('countries_ids[]' === $This.attr('name') || 'countries_id' === $This.attr('name')) {
                $This.select2({
                    placeholder: "Please select",
                    /*tags: true,*/
                    templateResult: formatCountry,
                    tokenSeparators: [',']
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
                            //locale: locale,
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
            if ('country_id' === $This.attr('name') || 'countries_id' === $This.attr('name') || $This.hasClass('countries')) {
                //console.log($This.attr('id'));
                $This.select2({
                    placeholder: "Please select",
                    /*tags: true,*/
                    templateResult: formatCountry,
                    tokenSeparators: [',']
                });
            } else {
                $This.select2({
                    sorter: customSorter,
                    templateResult: formatRepo,
                    allowClear: true, minimumResultsForSearch: 10
                });
            }
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
    //console.log('unsetHasSuccess', $oElm.attr('id'), unhide);
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
    //console.trace();
    //console.log(what, id, params);
    if ('undefined' == typeof params) {
        var params = {};
    }
    var url = what;
    if (!/ajax-/.test(url)) {
        url = '/ajax-' + what;
    }
    if ('string' === typeof id) {
        var $oElm = $('#' + id);
    } else {
        $oElm = id;
    }
    //console.log('ajaxFill', $oElm.attr('id'));
    var token = $('meta[name="csrf-token"]').attr('content');
    $.extend({'token':token}, params);

    $oElm.parents('.form-group').removeClass('has-success');
    $oElm.empty();

    //ajax
    var fillDatas = function (data) {
        //console.log('data',data);
        data = data.responseJSON;
        if (data.length != 0) {
            var placeholder = ('undefined' != typeof $oElm.attr('data-placeholder')) ? $oElm.attr('data-placeholder') : ('undefined' != typeof $oElm.attr('data-header')) ? $oElm.attr('data-header') : '- Select -';
            if (placeholder != '') {
                $oElm.append('<option value="" selected >' + placeholder + '</option>');
            }
            //data = DataSort(data);
            //console.log('data',data);
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
        //console.log($ViewPhones.attr('id') + ' Tracking.sendTag Event :: ' + trackingName);
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
        var form_enquiry_top;
        if($(window).width() > 767 ) {
            form_enquiry_top = $('#form_enquiry').offset().top;
        } else {
            var $SiteHeaderWrapper = $(".site-header-wrapper");
            var siteHeaderWrapperHeight = $SiteHeaderWrapper.height();
            form_enquiry_top = $('#form_enquiry').offset().top - siteHeaderWrapperHeight;
        }
        $('body,html').animate({scrollTop: form_enquiry_top}, 750, 'easeOutExpo');
    });

    function flatten() {
        var flat = [];
        for (var i = 0; i < arguments.length; i++) {
            if (arguments[i] instanceof Array) {
                flat.push.apply(flat, flatten.apply(this, arguments[i]));
            } else {
                flat.push(arguments[i]);
            }
        }
        return flat;
    }
    var $AjaxForm = $('.ajax-form');
    $AjaxForm.each(function() {
        var $ThisForm = $(this);
        var AjaxFomComplete = function (data) {
            data = data.responseJSON;
            var statusCss = '';
            var message = '';
            if (data.length != 0) {
                if(data.success) {
                    statusCss = 'alert-success';
                } else {
                    statusCss = 'alert-error';
                }
                if('object' === typeof data.message) {
                    var array = $.map(data.message, function(value, index) {
                        if(value != '') {
                            $('[name="' + index + '"]').parents('div.form-group').removeClass('has-success').addClass('has-error');
                        }
                        return [value];
                    });
                    var flatten_array = flatten(array);
                    if ($.isArray(flatten_array)) {
                        message = '<ul>';
                        $.each(flatten_array, function(index, value) {
                            message += '<li>' + value + '</li>';
                        });
                        message += '</ul>';
                    }
                } else {
                    message=data.message;
                }
            } else {
                statusCss = 'alert-error';
                message = 'Internal Error'
            }
            $("#msgModal").find('.alert')
                .removeClass('alert-ajax').removeClass('alert-error').removeClass('alert-success')
                .addClass(statusCss)
                .html(message);
            $("#msgModal").modal('show');
            $("#msgModal").on('hidden.bs.modal', function () {
                $('body,html').animate({ scrollTop: $AjaxForm.offset().top }, 750, 'easeOutExpo' );
            });
            if(data.success) {
                $ThisForm.html($("#msgModal").find('.modal-body').html());
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
     * show <input Inputs Fields
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
                        //$This.parent('.input-group').parents('div.form-group')
                        $This.closest('div.form-group')
                            .removeClass('has-error')
                            .addClass('has-success');
                    } else {
                        //$This.parent('.input-group').parents('div.form-group')
                        $This.closest('div.form-group')
                            .removeClass('has-success');
                    }
                });
            });

            $InputsRequired.each(function(index, element) {
                $(element).on('blur', function (event) {
                    event.preventDefault();
                    var $This = $(this);
                    if($This.val() && $This.val() != '') {
                        //$This.parent('.input-group').parents('div.form-group')
                        $This.closest('div.form-group')
                            .removeClass('has-error')
                            .addClass('has-success');
                    } else {
                        //$This.parent('.input-group').parents('div.form-group')
                        $This.closest('div.form-group')
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
                        //$This.parent('.input-group').parents('div.form-group')
                        $This.closest('div.form-group')
                            .removeClass('has-error')
                            .addClass('has-success');
                    } else {
                        //$This.parent('.input-group').parents('div.form-group')
                        $This.closest('div.form-group')
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
                    //$This.parent('.input-group').parents('div.form-group')
                    $This.closest('div.form-group')
                        .removeClass('has-error')
                        .find('.help-block').remove();
                });
            });
            /*
            $InputsCheckbox.each(function(index, element) {
                $(element).on('change', function (event) {
                    event.preventDefault();
                    var $This = $(this);

                    if( $This.is(':checked') || $This.checked || $This.prop("checked" )){
                        $This.val('1');
                        $This.attr('checked','checked');
                        if($This.is(':required')) {
                            $This.parents('.checkbox').removeClass('has-error');
                        }
                        $This.parents('.checkbox').addClass('has-success');
                    } else {
                        $This.val('');
                        $This.removeAttr('checked');
                        $This.parents('.checkbox').removeClass('has-success');
                        if($This.is(':required')) {
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
                        if($This.is(':required')) {
                            $This.parents('.radio').removeClass('has-error');
                        }
                        $This.parents('.radio').addClass('has-success');
                    } else {
                        $This.val('');
                        $This.parents('.radio').removeClass('has-success');
                        if($This.is(':required')) {
                            $This.parents('.radio').addClass('has-error');
                        }
                    }
                    if($ThisForm.attr('id') != 'form_bod') {
                        checkInputsFilled($ThisForm);
                    }
                });
            });
            */
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

    $('#country_contracts_ids', $('body.ads')).val('').empty();
    /*
     * Dealers Caracts ID set Country Contracts IDs
     */
    $('#dealerscaracts_id', $('body.ads')).on('change', function(event) {
        var dealer_id = event.target.value;

        $('#country_contracts_ids').val('').empty();

        // ajax
        $.post('/ajax-country_contracts',
            {
                dealer_id: dealer_id
            }, function (data) {
                if(data.length != 0) {
                    // success data
                    /*$.each(data, function(index, countryContractsObj) {
                     $('#country_contracts_ids').removeAttr('disabled').append('<option value="' + countryContractsObj.id + '">' + countryContractsObj.reference + '</option>');
                     });*/
                    $.each(data, function(countryContractsId, countryContractsReference) {
                        $('#country_contracts_ids').removeAttr('disabled').append('<option value="' + countryContractsId + '">' + countryContractsReference + '</option>');
                    });
                } else {
                    $('#country_contracts_ids').attr('disabled','disabled');
                }
            });
    });

    /*
     * Create Users's Type / Role
     */
    $('#role_id').on('change', function() {
        var update = updateSelect($(this), $('#type'), false);
        var val = $(':selected', $('#type')).text();
        //console.log(val);
        if ($('.bloc_edit_btns .btn.' + val + 'scaracts_edit').length) {
            $('.bloc_edit_btns .btn').hide();
            $('.bloc_edit_btns .btn.' + val + 'scaracts_edit').show();
        }
    });

    if (undefined !== $('#role_id').val()) {
        $('#role_id').trigger('change');
    }

    $('#type').on('change', function() {
        var update = new updateSelect($(this), $('#role_id'), true);
    });

    if ($('.countries.select2').length) {
        selectSingle($('.countries.select2'));
    }

    if($('.datatable').length) {
        $('.datatable').dataTable({
            retrieve: true,
            "iDisplayLength": 10,
            "aaSorting": [],
            "aoColumnDefs": [
                //{'bSortable': false, 'aTargets': [0]}
                //{'bSortable': false, 'aTargets': [actionColIndex]}
                {'bSortable': false, 'aTargets': 'nosort'}
            ]
        });
    }
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
