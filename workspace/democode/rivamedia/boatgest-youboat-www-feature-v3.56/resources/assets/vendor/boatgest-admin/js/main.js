var updateUserType = function updateUserType() {
    var oElm = document.getElementById("role_id");
    var role_name = oElm.options[oElm.selectedIndex].text;
    $('#type').val(role_name);
};

var updateSelect = function updateSelect($oElm, $oElmTarget, index) {
    if (index) {
        var index = $(':selected', $oElm).index();
        $oElmTarget.val(index);
    } else {
        var name = $(':selected', $oElm).text();
        $oElmTarget.val(name);
    }
};
/*
function formatCountry (country) {
    console.log(country);

    if (!country.id) { return country.text; }
    var $Country = $(
        '<span><img src="/assets/img/flags/' + country.element.value.toLowerCase() + '.png" class="img-flag" />&nbsp;' + country.text + '</span>'
    );
    return $Country;
};*/
/*
var selectMultiple = function selectMultiple($oElm) {
    if ($oElm) {
        if ('countries_ids[]' === $oElm.attr('name') || 'countries_id' === $oElm.attr('name')) {
            $oElm.select2({
                placeholder: "Please select",
                //tags: true,
                templateResult: formatCountry,
                tokenSeparators: [',']
            });
        }
    } else {
        $('select[multiple="multiple"]').select2({
            placeholder: "Please select",
            //tags: true,
            tokenSeparators: [',']
        });
    }
};*/
/*
var selectSingle = function selectSingle($oElm) {
    if ($oElm) {
        if ('country_id' === $oElm.attr('name') || 'countries_id' === $oElm.attr('name')) {
            $oElm.select2({
                placeholder: "Please select",
                //tags: true,
                templateResult: formatCountry,
                tokenSeparators: [',']
            });
        } else {
            $oElm.select2({
                placeholder: "Please select",
                //tags: true,
                tokenSeparators: [',']
            });
        }
    } else {
        $('select').not('[multiple="multiple"],.noselect2').select2({
            placeholder: "Please select",
            //tags: true,
            tokenSeparators: [',']
        });
    }
};*/

$(document).ready(function () {

    /*
     * Menu
     */
    var activeSubSub = $(document).find('.active-subsub');
    if (activeSubSub.length > 0) {

        activeSubSub.parents('ul').show();
        activeSubSub.parents('ul').parents('li').find('.arrow').addClass('open');
        activeSubSub.parents('ul').parents('li').addClass('open active');

        activeSubSub.parents('li').parents('ul').show();
        activeSubSub.parents('li').parents('ul').parents('li').find('.arrow').addClass('open');
        activeSubSub.parents('li').parents('ul').parents('li').addClass('open active');
    } else {
        var activeSub = $(document).find('.active-sub');
        if (activeSub.length > 0) {
            activeSub.parent().show();
            activeSub.parent().parent().find('.arrow').addClass('open');
            activeSub.parent().parent().addClass('open active');
        }
    }

    var actionColIndex = $('.datatable').find('th').not('nosort').length-1;
    $('.datatable').dataTable({
        retrieve: true,
        "iDisplayLength": 100,
        "aaSorting": [],
        "aoColumnDefs": [
            //{'bSortable': false, 'aTargets': [0]}
            //{'bSortable': false, 'aTargets': [actionColIndex]}
            {'bSortable': false, 'aTargets': 'nosort'}
        ]
    });

    $('.ckeditor').each(function () {
        CKEDITOR.replace($(this));
    })

    $('.mass').click(function () {
        if ($(this).is(":checked")) {
            $('.single').each(function () {
                if ($(this).is(":checked") == false) {
                    $(this).click();
                }
            });
        } else {
            $('.single').each(function () {
                if ($(this).is(":checked") == true) {
                    $(this).click();
                }
            });
        }
    });

    $('#delete').click(function () {
        if (window.confirm("Are you sure?")) {
            var send = $('#send');
            var mass = $('.mass').is(":checked");
            if (mass == true) {
                send.val('mass');
            } else {
                var toDelete = [];
                $('.single').each(function () {
                    if ($(this).is(":checked")) {
                        toDelete.push($(this).data('id'));
                    }
                });
                send.val(JSON.stringify(toDelete));
            }
            $('#massDelete').submit();
        }
    });

    $('.page-sidebar').on('click', 'li > a', function (e) {

        if ($('body').hasClass('page-sidebar-closed') && $(this).parent('li').parent('.page-sidebar-menu').size() === 1) {
            return;
        }

        var hasSubMenu = $(this).next().hasClass('sub-menu');

        if ($(this).next().hasClass('sub-menu always-open')) {
            return;
        }

        var parent = $(this).parent().parent();
        var the = $(this);
        var menu = $('.page-sidebar-menu');
        var sub = $(this).next();

        var autoScroll = menu.data("auto-scroll");
        var slideSpeed = parseInt(menu.data("slide-speed"));
        var keepExpand = menu.data("keep-expanded");

        if (keepExpand !== true) {
            //parent.children('li.open').children('a').children('.arrow').removeClass('open');
            //parent.children('li.open').children('.sub-menu:not(.always-open)').slideUp(slideSpeed);
            //parent.children('li.open').removeClass('open');
            parent.find('li.open').find('a').find('.arrow').removeClass('open');
            parent.find('li.open').find('.sub-menu:not(.always-open)').slideUp(slideSpeed);
            parent.find('li.open').removeClass('open');
        }

        var slideOffeset = -200;

        if (sub.is(":visible")) {
            $('.arrow', $(this)).removeClass("open");
            $(this).parent().removeClass("open");
            sub.slideUp(slideSpeed, function () {
                if (autoScroll === true && $('body').hasClass('page-sidebar-closed') === false) {
                    if ($('body').hasClass('page-sidebar-fixed')) {
                        menu.slimScroll({
                            'scrollTo': (the.position()).top
                        });
                    }
                }
            });
        } else if (hasSubMenu) {
            $('.arrow', $(this)).addClass("open");
            $(this).parent().addClass("open");
            sub.slideDown(slideSpeed, function () {
                if (autoScroll === true && $('body').hasClass('page-sidebar-closed') === false) {
                    if ($('body').hasClass('page-sidebar-fixed')) {
                        menu.slimScroll({
                            'scrollTo': (the.position()).top
                        });
                    }
                }
            });
        }
        if (hasSubMenu == true || $(this).attr('href') == '#') {
            e.preventDefault();
        }
    });

    /*
     * Refence Country Contract Formatter
     */
    var updateReference = function () {
        var $DealerId =  $('.countrycontracts #dealer_id');
        var $Reference =  $('.countrycontracts #reference');
        var $StartDate =  $('.countrycontracts #start_date');
        var selectedDealerText = $(':selected', $DealerId).text();
        var startDateVal = $StartDate.val().length ? $StartDate.val() : moment(new Date()).format("YYYY-MM-DD")
        if($(':selected', $DealerId).val()) {
            $Reference.val( selectedDealerText + '_' + startDateVal);
        } else {
            $Reference.val('');
        }
    }
    if ($('body.countrycontracts').length) {
        $('#dealer_id', '.countrycontracts').on('change', function(event) {
            var updateRef = new updateReference();
        });
        $('#start_date', '.countrycontracts').on('dp.change', function(event) {
            var updateRef = new updateReference();
        });
    }
    /*
     * datepicker
     */

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
        format : "YYYY-MM-DD"
    });
    $('.datetimepicker').not('#start_date').not('#end_date').datetimepicker({
        format : "YYYY-MM-DD HH:MM:SS"
    });

    $('#start_date.datepicker').datetimepicker({
        format : "YYYY-MM-DD"
    });
    $('#end_date.datepicker').datetimepicker({
        format : "YYYY-MM-DD",
        useCurrent: false //Important! See issue #1075
    });

    $('#start_date.datetimepicker').datetimepicker({
        format : "YYYY-MM-DD HH:MM:SS"
    });
    $('#end_date.datetimepicker').datetimepicker({
        format : "YYYY-MM-DD HH:MM:SS",
        useCurrent: false //Important! See issue #1075
    });
    if ($('body.countrycontracts').length) {
        $("#start_date").on("dp.change", function (event) {
            $('#end_date').data("DateTimePicker").minDate(event.date);
        });
    } else {
        $("#start_date").on("dp.change", function (event) {
            $('#end_date').data("DateTimePicker").minDate(event.date);
        });
    }
    $("#end_date").on("dp.change", function (event) {
        $('#start_date').data("DateTimePicker").maxDate(event.date);
    });

    //var multiSelect = new selectMultiple();
    //var singleSelect = new selectSingle();

    if ($('select[name="countries_ids[]"]').length) {
        var multiSelectCountries = new selectMultiple($('select[name="countries_ids[]"]'));
    }

    if ($('select[name="country_id"]').length) {
        var singleSelectCountry = new selectSingle($('select[name="country_id"]'));
    }

   /* if ($('select[name="countries_id"]').length) {
        var singleSelectCountries = new selectSingle($('select[name="countries_id"]'));
    }*/

    $(window).resize(function() {
        //selectMultiple();
        if ($('select[name="countries_ids[]"]').length) {
            selectMultiple($('select[name="countries_ids[]"]'));
        }
        //selectSingle();countries_id
        if ($('select[name="country_id"]').length) {
            var singleSelectCountry = new selectSingle($('select[name="country_id"]'));
        }
        if ($('select[name="countries_id"]').length) {
            var singleSelectCountries = new selectSingle($('select[name="countries_id"]'));
        }
    });

    /*
     * Loading text for button.btn
     */
    $("button.btn").not('.btn-io,.btn-exception, #addField').on('click', function(){
        var $This = $(this);
        $This
            .attr('data-loading-text', '<i class="fa fa-cog fa-spin fa-fw"></i>in progress ...')
            .button('loading').delay(1000).queue(function() {
                $(this).button('reset');
            });
    });

    /*
     * Auto-select input email password to reset
     */
    $('input[type="email"], input[type="password"]').on('focus', function() {
        $(this).select();
    });

    /*
     * Swicth checbox custom
     */
    $('input[type="checkbox"].switch').each(function(index, element) {
        var $oElm = $(element);
        $oElm.on('change', function() {
            var $This = $(this);
            var $Target = $('input#'+$This.attr('data-target'));
            var old = $Target.val();

            $Target.val($This.attr('data-default'));
            $Target.attr('value',$This.attr('data-default'));

            $This.val($This.attr('data-default'));
            $This.attr('value',$This.attr('data-default'));

            $This.attr('data-default',old);
        });
    });

    /*
     * Floating button action
     */
    $('.fab').hover(function () {
        $(this).toggleClass('active');
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

});