$(document).ready(function () {

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

    $('.datatable').dataTable({
        retrieve: true,
        "iDisplayLength": 100,
        "aaSorting": [],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [0]}
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

    $("#start_date").on("dp.change", function (event) {
        $('#end_date').data("DateTimePicker").minDate(e.date);
    });
    $("#end_date").on("dp.change", function (event) {
        $('#start_date').data("DateTimePicker").maxDate(e.date);
    });

    $('select[multiple="multiple"]').select2({
        placeholder: "Please select",
        /*tags: true,*/
        tokenSeparators: [',']
    });

    /*
     * Ads's Types, Categories & SubCategories
     */
/*
    $('#adstypes_id').on('change', function(event) {
        console.log(event);

        var adstypes_id = event.target.value;

        // ajax
        $.get('/ajax-subcat?adstypes_id=' + adstypes_id, function (data) {
            console.log(data);
            // success data
            $('#categories_ids').empty();

            $.each(data, function(index, catObj) {
                $('#categories_ids').append('<option value="' + catObj.id + '">' + catObj.name + '</option>');
            });
        });

    });
    $('#categories_ids').on('change', function(event) {
        console.log(event);

        var cat_id = event.target.value;

        // ajax
        $.get('/ajax-subcat?cat_id=' + cat_id, function (data) {
            console.log(data);
            // success data
            $('#subcategories_ids').empty();

            $.each(data, function(index, subcatObj) {
                $('#subcategories_ids').append('<option value="' + subcatObj.id + '">' + subcatObj.name + '</option>');
            });
        });

    });
*/
    /*
     * Create Users's Types
     */

    $('#role_id').on('change', function(event) {
        console.log(event.target.selectedOptions);

        var role_id = event.target.value;
        var oElm = document.getElementById("role_id");
        var role_name = oElm.options[oElm.selectedIndex].text;
        $('#type').val(role_name);

    });

    $(".btn").on('click', function(){
        var $This = $(this);
        $This
            .attr('data-loading-text', '<i class="fa fa-cog fa-spin fa-fw"></i>in progress ...')
            .button('loading').delay(1000).queue(function() {
                //$(this).button('reset');
            });
    });
    $('input[type="email"], input[type="password"]').on('focus', function() { $(this).select(); });

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