var stepSStatus = new Array;
stepSStatus['step_01'] = false;
stepSStatus['step_02'] = false;
stepSStatus['step_03'] = false;

var checkElmsErrorsInTabPane = function($TabPane) {
    var $ElmsErrors = $TabPane.has('.form-group.has-error, .panel-body.has-error');
    $ElmsErrors.each(function (index) {
        var $This = $(this);
        var $Link = $('.nav.nav-tabs').find('a[href="#' + $This.attr('id') + '"]');
        $Link.addClass('bg-danger');

        if ($This.find('.panel-body.has-error').length > 0) {
            var id = $This.find('.panel-body.has-error').closest('.panel-collapse').attr('id');
            $('a[href="#' + id + '"][data-toggle="collapse"]').addClass('text-danger').append($('<i>', {
                'class': 'fa fa-exclamation-circle text-danger',
                'aria-hidden': 'true'
            })).trigger('click').children().addClass('text-danger');
        }
    });
};

var checkSteps = function(id) {
    var status = true;
    stepSStatus[id] = true;
    $('#' + id).find(':required, input.required, select.required').each( function(index) {
        var $This = $(this);
        var val = $This.val();

        if (!val || $This.parents('.input-group').closest('.form-group').hasClass('has-error')) {
            status = false;
        }
        if (!val || $This.parents('.input-group').closest('.form-group').hasClass('has-error')) {
            $This.closest('.form-group').removeClass('has-success').addClass('has-error');
            $('#' + id).find('.nav-tabs a[href="#' + $This.parents('.tab-pane').attr('id') + '"]').removeClass('bg-success').addClass('bg-danger');
        } else {
            //$This.parents('.input-group').closest('.form-group').removeClass('has-error').addClass('has-success');
            $This.closest('.form-group').removeClass('has-error').addClass('has-success');
            $('#' + id).find('.nav-tabs a[href="#' + $This.parents('.tab-pane').attr('id') + '"]').removeClass('bg-danger').addClass('bg-success');
        }
        if(!stepSStatus[id] && status) {
            stepSStatus[id] = false;
        } else {
            stepSStatus[id] = status;
        }
    });
    return stepSStatus[id];
};

var setStyleSteps = function($oElm) {
    $oElm.each(function(index, elem) {
        var $This = $(elem);

        var $InfoSuccess = $('<i>', {'class': 'info fa fa-2x fa-fw fa-check-circle', 'aria-hidden': 'true'});
        var $InfoError = $('<i>', {'class': 'info fa fa-2x fa-fw fa-exclamation-circle', 'aria-hidden': 'true', 'data-toggle': 'tooltip', 'data-placement': 'right', 'title': mandatory_txt});

        var id = $This.attr('id');
        var $Target = $('[href="#' + id +'"]').parents('[role="tab"]');
        var $TargetButton = $Target.find('a[role="button"]');
        var $TargetPanel = $Target.closest('.panel');

        $Target.removeClass('active');
        if (checkSteps(id)) {
            $Target.removeClass('active').addClass('has-success').removeClass('has-error');
            $TargetButton.find('.info').remove();
            $TargetButton.removeClass('text-danger').addClass('text-success').append($InfoSuccess.clone());

            $TargetPanel.removeClass('panel-danger').addClass('panel-success');
        } else {
            $Target.removeClass('active').addClass('has-error').removeClass('has-success');
            $TargetButton.find('.info').remove();
            $TargetButton.removeClass('text-success').addClass('text-danger').append($InfoError.clone());

            $TargetPanel.removeClass('panel-success').addClass('panel-danger');
        }
    });
};

var BtnFunctions = function() {
    var $CaractsLabelsValues = $('.caracts_labels_values');
    var $LabelsValues = $CaractsLabelsValues.find('.labels_values');

    $CaractsLabelsValues.find('.caracts_label').each(function(event,index) {
        var $This = $(this);
        $This
            .on('focus', function() {
                var $Label = $(this);
                $Label.closest('fieldset').removeClass('well-danger').removeClass('well-success');
                $Label.closest('.form-group').removeClass('has-error').removeClass('has-success');
            })
            .on('blur', function() {
                var $Value = $('#' + $(this).attr('data-relationship'));
                var $Label = $(this);

                if($Value.val() == '' && $Label.val() == '') {
                    $Label.closest('fieldset').removeClass('well-danger').removeClass('well-success');
                    $Value.closest('.form-group').removeClass('has-error').removeClass('has-success');
                    $Value.removeAttr('required');
                    $Label.closest('.form-group').removeClass('has-error').removeClass('has-success');
                    $Label.removeAttr('required');
                } else if($Value.val() != '' && $Label.val() != '') {
                    $Label.closest('fieldset').removeClass('well-danger').addClass('well-success');
                    $Value.closest('.form-group').removeClass('has-error').addClass('has-success');
                    $Value.removeAttr('required');
                    $Label.closest('.form-group').removeClass('has-error').addClass('has-success');
                    $Label.removeAttr('required');
                } else if($Value.val() != '' && $Label.val() == '') {
                    $Label.closest('fieldset').addClass('well-danger').removeClass('well-success');
                    $Value.closest('.form-group').addClass('has-error').removeClass('has-success');
                    $Value.attr('required', 'required');
                    $Value.closest('.form-group').removeClass('has-error').addClass('has-success');
                    $Value.removeAttr('required');
                } else if($Value.val() == '') {
                    $Value.closest('fieldset').addClass('well-danger').removeClass('well-success');
                    $Value.closest('.form-group').addClass('has-error').removeClass('has-success');
                    $Value.attr('required', 'required');
                }
            });
    });

    $CaractsLabelsValues.find('.caracts_value').each(function(event,index) {
        var $This = $(this);
        $This
            .on('focus', function() {
                var $Value = $(this);
                $Value.closest('fieldset').removeClass('well-danger').removeClass('well-success');
                $Value.closest('.form-group').removeClass('has-error').removeClass('has-success');
            })
            .on('blur', function() {
                var $Value = $(this);
                var $Label = $('#' + $(this).attr('data-relationship'));

                if($Value.val() == '' && $Label.val() == '') {
                    $Value.closest('fieldset').removeClass('well-danger').removeClass('well-success');
                    $Value.closest('.form-group').removeClass('has-error').removeClass('has-success');
                    $Value.removeAttr('required');
                    $Label.closest('.form-group').removeClass('has-error').removeClass('has-success');
                    $Label.removeAttr('required');
                } else if($Value.val() != '' && $Label.val() != '') {
                    $Value.closest('fieldset').removeClass('well-danger').addClass('well-success');
                    $Value.closest('.form-group').removeClass('has-error').addClass('has-success');
                    $Value.removeAttr('required');
                    $Label.closest('.form-group').removeClass('has-error').addClass('has-success');
                    $Label.removeAttr('required');
                } else if($Label.val() != '' && $Value.val() == '') {
                    $Value.closest('fieldset').addClass('well-danger').removeClass('well-success');
                    $Value.closest('.form-group').addClass('has-error').removeClass('has-success');
                    $Value.attr('required', 'required');
                    $Label.closest('.form-group').removeClass('has-error').addClass('has-success');
                    $Label.removeAttr('required');
                } else if($Label.val() == '') {
                    $Value.closest('fieldset').addClass('well-danger').removeClass('well-success');
                    $Label.closest('.form-group').addClass('has-error').removeClass('has-success');
                    $Label.attr('required', 'required');
                }
            });
    });

    $LabelsValues.sortable({
        placeholder: "ui-state-highlight"
    });
    $LabelsValues.disableSelection();

    var $btnsDelete = $CaractsLabelsValues.find('.btn-delete');
    var $btnsReload = $CaractsLabelsValues.find('.btn-reload');

    $btnsDelete.on('click', function(event) {
        event.preventDefault();
        var $This = $(this);
        //if (window.confirm("Are you sure to delete?")) {
        //$($This.attr('href')).attr('disabled', 'disabled');
        var id = $This.attr('href');
        var $Target = $(id);
        var $Inputs = $Target.find('input');
        var empty = true;

        $.each($Inputs, function() {
            if ('' != $(this).val()) {
                empty = false;
                $(this).removeAttr('data-required');
            } else {
                $(this).attr('data-required','required');
            }
        });

        if(empty) {
            $Target.remove();
        } else {
            $Inputs.removeAttr('required');
            if($Target.has('well-success')) {
                $Target.removeClass('well-success').addClass('old-well-success');
            }
            $Target.addClass('well-danger');
            $Inputs.attr('disabled', 'disabled');
            $Target.find('.form-group').removeClass('.has-success').addClass('has-error');

            $This.next('.btn-reload').removeClass('hidden').show();
            $This.hide();

            $Target.appendTo($Target.parents('.labels_values'));
            $Target.show();
        }
    });

    $btnsReload.on('click', function(event) {
        event.preventDefault();
        var $This = $(this);
        //if (window.confirm("Are you sure to reload?")) {
        //$($This.attr('href')).removeAttr('disabled');
        var id = $This.attr('href');
        var $Target = $(id);
        var $Inputs = $Target.find('input');

        $.each($Inputs, function() {
            if ('required' == $(this).attr('data-required')) {
                $(this).removeAttr('data-required');
                $(this).attr('required','required');
            } else {
                $(this).removeAttr('required');
                $(this).attr('data-required','required');
            }
        });

        if($Target.has('old-well-success')) {
            $Target.addClass('well-success').removeClass('old-well-success');
        }

        $Target.removeClass('well-danger');
        $Inputs.removeAttr('disabled');
        $Target.find('.form-group').addClass('has-success').removeClass('has-error');

        $This.prev('.btn-delete').show();
        $This.hide();

        $Target.show();
    });
};

var RenderTemplate = function(target, id, caracts_labels_id, caracts_labels_name, caracts_values_id, caracts_values_name) {
    var r = $.Deferred();
    template = '<fieldset id="' + id + '" class="well">';
    template += '    <div class="col-sm-4"><div class="form-group"><input placeholder="' + placeholder_txt + '" class="form-control caracts_label" id="' + caracts_labels_id + '" data-relationship="' + caracts_values_id + '" name="' + caracts_labels_name + '" type="text" value=""></div></div>';
    template += '    <div class="col-sm-2 text-center"><i class="fa fa-arrows-h" aria-hidden="true"></i></div>';
    template += '    <div class="col-sm-4"><div class="form-group has-success"><input placeholder="' + placeholder_txt + '" class="form-control caracts_value" id="' + caracts_values_id + '" data-relationship="' + caracts_labels_id + '" name="' + caracts_values_name + '" type="text" value=""></div></div>';
    template += '    <div class="col-sm-2 text-center">';
    template += '        <a href="#' + id + '" title="' + delete_txt + '" class="btn-delete btn btn-block btn-md btn-danger btn-exception"><i class="fa fa-trash-o fa-fw"></i>' + delete_txt + '</a>';
    template += '        <a href="#' + id + '" title="' + reload_txt + '" class="btn-reload btn btn-block btn-md btn-primary btn-exception hidden"><i class="fa fa-refresh fa-fw"></i>' + reload_txt + '</a>';
    template += '    </div>';
    template += '</fieldset>';

    target.append(template);
    return r;
};

var SetReadyToPay = function() {
    if(!stepSStatus['step_01'] || !stepSStatus['step_02']) {
        $('#ready_to_pay').val(false);
    } else {
        if(!$('#ready_to_pay').val()) {
            $('#ready_to_pay').val('ready_to_pay');
        }
    }
    //console.log(stepSStatus);
    //console.log($('#ready_to_pay').val());
};
// @TODO REFACTORING ASAP
$( function() {
    var $FormSell = $('form#form_sell');
    var $Steps = $FormSell.find('.step');
    var $LastStep = $('#step_03');
    var lang = $('html').attr('lang');
    var ck_config = {
        language: lang,
        disallowedContent: 'a[!href,target]',
        toolbarGroups: [
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'insert', groups: [ 'insert' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            '/',
            { name: 'links', groups: [ 'links' ] },
            '/',
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] },
            { name: 'about', groups: [ 'about' ] }
        ],
        removeButtons: 'Source,Link,Unlink,Anchor,Flash,Table,Smiley,PageBreak,Iframe,ImageButton,HiddenField,Button,Select,Textarea,TextField,Radio,Checkbox,Form,Replace,Find,Templates,NewPage,About,Maximize,ShowBlocks,BGColor,TextColor,Styles,Format,Font,FontSize,Image,Scayt,PasteFromWord,Print'
    };

    CKEDITOR.replace( 'ad_description', ck_config ).on( 'blur', function( e ) {
        this.updateElement();
        var $This = $('#' + e.editor.name);
        var $CurrentStep = $This.closest('.step');
        var currentVal = $This.val();
        if(currentVal.length>0) {
            $This.parents('.input-group').closest('.form-group').removeClass('has-error').addClass('has-success');
        }
        setStyleSteps($CurrentStep);
    } );
    CKEDITOR.replace( 'ad_specifications', ck_config ).on( 'blur', function( e ) {
        this.updateElement();
        var $This = $('#' + e.editor.name);
        var $CurrentStep = $This.closest('.step');
        var currentVal = $This.val();
        if(currentVal.length>0) {
            $This.parents('.input-group').closest('.form-group').removeClass('has-error').addClass('has-success');
        }
        setStyleSteps($CurrentStep);
    } );
    CKEDITOR.replace( 'ad_features', ck_config ).on( 'blur', function( e ) {
        this.updateElement();
        var $This = $('#' + e.editor.name);
        var $CurrentStep = $This.closest('.step');
        var currentVal = $This.val();
        if(currentVal.length>0) {
            $This.parents('.input-group').closest('.form-group').removeClass('has-error').addClass('has-success');
        }
        setStyleSteps($CurrentStep);
    } );
    $('[contenteditable="true"].required').attr('required', 'required');

    $Panels = $('#accordion .panel-collapse.step');
    $Panels
    /*.on('shown.bs.collapse', function () {
        var $Target = $('[href="#' + $(this).attr('id')+'"]').parents('[role="tab"]');
        $Target.addClass('active');
        $Target.closest('.panel').removeClass('panel-success')
    })*/
    .on('shown.bs.collapse', function (e) {
        var $This = $(this);
        var id = $This.attr('id');
        var $Target = $('[href="#' + id +'"]').parents('[role="tab"]');
        $Target.addClass('active');
        $Target.closest('.panel').removeClass('panel-success');

        var target = '#heading_' + id;
        var top = $(target).offset().top;
        $('body,html').animate({scrollTop:top}, 750, 'easeOutExpo');
    })
    .on('hide.bs.collapse', function () {
        var $This = $(this);
        var id = $This.attr('id');
        setStyleSteps($This);

        var $FormSell = $('form#form_sell');
        //var $FormSellButton = $FormSell.find('#btn_submit');
        /*if(!stepSStatus['step_01'] || !stepSStatus['step_02']) {
            $('#ready_to_pay').val(false);
        } else {
            if(!$('#ready_to_pay').val()) {
                $('#ready_to_pay').val('ready_to_pay');
            }
        }*/
        SetReadyToPay();
        //var $ModalFormSell = $('#msgModal_form_sell');
        //$ModalFormSell.find('.modal-body').html('<i class="text-success fa fa-check-circle fa-spin fa-5x fa-fw"></i>');
        //$ModalFormSell.modal('show');
    });

    var $NavTabs = $('.nav.nav-tabs');
    var $Tabs = $('a[data-toggle="tab"]', $NavTabs);
    $Tabs.on('show.bs.tab', function (e) {
        var $This = $(this);
        var top = $This.offset().top;
        $('body,html').animate({scrollTop:top}, 750, 'easeOutExpo');
    }).on('hidden.bs.tab', function (e) {
        //setStyleSteps($('#' + $(e.relatedTarget).closest('.step').attr('id')));
        checkElmsErrorsInTabPane($TabPane);
    });

    if($FormSell.hasClass('posted')) {
        setStyleSteps($Steps);
    }
    //if($FormSell.hasClass('ready_to_pay') || $FormSell.hasClass('preview')) {
    if($FormSell.hasClass('ready_to_pay') || $FormSell.hasClass('success')) {
    //if($FormSell.hasClass('ready_to_pay')) {
        var $BtnSubmit = $('#btn_submit');
        var $NavBtnSubmit = $('#nav_btn_submit');
        var $BtnCheck = $('#btn_check');
        var $NavBtnCheck = $('#nav_btn_check');
        var $BtnPay = $LastStep.find('.btn_pay');
        var $NavBtnPay = $('#nav_btn_pay');
        setStyleSteps($Steps);

        SetReadyToPay();

        if(!stepSStatus['step_01'] || !stepSStatus['step_02']) {
            //$('#ready_to_pay').val(false);

            $('.step_to_valid').attr('data-toggle', '');
            if(!stepSStatus['step_01']) {
                $('#step_01').collapse('show');
            }
            if(!stepSStatus['step_02']) {
                $('#step_02').collapse('show');
            }
            $BtnCheck.removeClass('hidden');
            $NavBtnCheck.removeClass('hidden');

            $BtnSubmit.addClass('hidden');
            $NavBtnSubmit.addClass('hidden');

            $BtnPay.addClass('hidden');
            $NavBtnPay.addClass('hidden');
        } else {
            $LastStep.collapse('show');

            $('.step_to_check, .step_to_valid').attr('data-toggle', '');
            if($FormSell.hasClass('ready_to_pay')) {
                if($LastStep.length) {
                    $('body,html').animate({scrollTop:$LastStep.offset().top}, 750, 'easeOutExpo');
                }
                $BtnSubmit.removeClass('hidden');
                $NavBtnSubmit.removeClass('hidden');

                $BtnCheck.addClass('hidden');
                $NavBtnCheck.addClass('hidden');

                $BtnPay.addClass('hidden');
                $NavBtnPay.addClass('hidden');

                /*if(!$('#ready_to_pay').val()) {
                    $('#ready_to_pay').val('ready_to_pay');
                }*/
            } else {
                if($LastStep.find('.charge').length) {
                    $('body,html').animate({scrollTop:$LastStep.find('.charge').offset().top}, 750, 'easeOutExpo');
                }
                $BtnSubmit.addClass('hidden');
                $NavBtnSubmit.addClass('hidden');

                $BtnCheck.addClass('hidden');
                $NavBtnCheck.addClass('hidden');

                $BtnPay.removeClass('hidden');
                $NavBtnPay.removeClass('hidden');

                if(!$('#ready_to_pay').val()) {
                    $('#ready_to_pay').val('success');
                }
            }
        }

        /*$('.step').find('input,select').each(function(){
            if($(this).val()) {
                var val = $(this).val();
                var name = $(this).attr('name');
                var id = $(this).attr('id');
                var label = $('label[for="' + $(this).attr('name') + '"]');
                console.log(
                    label.text().replace(/\s{2,}/g,' ')
                    + ' = ' +
                    name
                    + ' = ' +
                    val
                );
            }
        });*/
    }

    $LastStep.on('shown.bs.collapse', function () {
        setStyleSteps($Steps);
        var $BtnSubmit = $('#btn_submit');
        var $NavBtnSubmit = $('#nav_btn_submit');
        var $BtnCheck = $('#btn_check');
        var $NavBtnCheck = $('#nav_btn_check');
        var $BtnPay = $LastStep.find('.btn_pay');
        var $NavBtnPay = $('#nav_btn_pay');

        SetReadyToPay();

        if(!stepSStatus['step_01'] || !stepSStatus['step_02']) {
            //$('#ready_to_pay').val(false);
            $LastStep.find('.preview-and-payment').hide();
            $LastStep.find('.preview-and-payment_errors').show();

            $BtnCheck.removeClass('hidden');
            $NavBtnCheck.removeClass('hidden');
            $BtnSubmit.addClass('hidden');
            $NavBtnSubmit.addClass('hidden');
        } else {
            $LastStep.find('.preview-and-payment').show();
            $LastStep.find('.preview-and-payment_errors').hide();

            $BtnSubmit.removeClass('hidden');
            $NavBtnSubmit.removeClass('hidden');
            $BtnCheck.addClass('hidden');
            $NavBtnCheck.addClass('hidden');
        }
    });

    /////////////////////////////////////
    // ON START
    var $Photos = $('.gallery #photos');
    $Photos.sortable({
        placeholder: "col-xs-6 col-sm-4 col-md-4 col-lg-3 ui-state-highlight",
        update: function( event, ui ) {
            $Photos.find('.photo .label').each(function(index) {
                $(this).text(index+1);
            });
        }
    });
    $Photos.disableSelection();

    BtnFunctions();

    var $CaractsLabelsValues = $('.caracts_labels_values');
    var $LabelsValues = $CaractsLabelsValues.find('.labels_values');
    var $btnsAdd = $CaractsLabelsValues.find('.btn-add');

    var $Items = $LabelsValues.find('fieldset');
    $btnsAdd.on('click', function(event) {
        event.preventDefault();
        var $This = $(this);
        var $LabelsValues = $This.closest('.caracts_labels_values').find('.labels_values');
        var $Items = $LabelsValues.find('fieldset');
        var id = $This.attr('href').replace("#", '');
        var count = $Items.length;
        var caracts_labels_id = id + '_labels_' + count;
        var caracts_labels_name = id + '_labels[]';
        var caracts_values_id = id + '_values_' + count;
        var caracts_values_name = id + '_values[]';
        id += '_' + count;

        RenderTemplate($LabelsValues, id, caracts_labels_id, caracts_labels_name, caracts_values_id, caracts_values_name).done(BtnFunctions());
    });

    var $TabPane = $('.tab-pane');
    checkElmsErrorsInTabPane($TabPane);
    $('.nav.nav-tabs').find('a.bg-danger').last().trigger('click');

    if('undefined' !== typeof inputsHasError && $(inputsHasError).length) {
        var $InputsHasError = $(inputsHasError);
        var $LastInputHasError = $(lastInputHasError);
        var errorSteps = [];
        $InputsHasError.each(function(index) {
            var $This = $(this);
            var CurrentStep = $This.closest('.step');
            errorSteps.push('#' + CurrentStep.attr('id'));
            $This.parents('.input-group').closest('.form-group').removeClass('has-success').addClass('has-error');
        });
        $(errorSteps.join(',')).each(function() {
            $(this).collapse('show');
        });
    }

    // Focus and Set Has-error when lastInputHasError
    if('undefined' !== typeof inputsHasError && $(inputsHasError).length &&
        'undefined' !== typeof lastInputHasError && $(lastInputHasError).length) {
        var $LastInputHasError = $(lastInputHasError);
        //console.log(lastInputHasError);
        //console.log($LastInputHasError);
        var CurrentStep = $LastInputHasError.closest('.step');

        $LastInputHasError
            .attr('placeholder', $LastInputHasError.val())
            .val('')
            .parents('.input-group').closest('.form-group').removeClass('has-success').addClass('has-error');

        setStyleSteps(CurrentStep);
        CurrentStep.collapse('show');

        $LastInputHasError.focus().select();
        $LastInputHasError.on('blur', function(){
            if($(this).val()) {
                $(this).attr('placeholder', '');
            }
        });

        $('body,html').animate({scrollTop:$LastInputHasError.offset().top}, 750, 'easeOutExpo');
    }

    /************************************************************************/
    if($('#ad_phones').length && $('#ci_phone').length) {
        $('#ad_phones,#ci_phone').on('blur', function() {console.log('ad_phones blur');

            if(!$('#ad_phones').val().length && $('#ci_phone').val().length) {
                $('#ad_phones').val($('#ci_phone').val()).trigger('blur');
                setStyleSteps($('#ad_phones').closest('.step'));
            } else if(!$('#ci_phone').val().length && $('#ad_phones').val().length) {
                $('#ci_phone').val($('#ad_phones').val()).trigger('blur');
                setStyleSteps($('#ci_phone').closest('.step'));
            }
        });
    }/************************************************************************/
    //$FormSell.find('input, select').not(':disabled').on('change', function() {
    $FormSell.find(':required, input.required, select.required').not(':disabled').on('change', function() {
         //console.log('change', $(this).attr('name'));
         setStyleSteps($(this).closest('.step'));
     }).on('blur', function() {
         //console.log('blur', $(this).attr('name'));
        //setStyleSteps($(this).closest('.step'));
         SetReadyToPay();
         /*if(!stepSStatus['step_01'] || !stepSStatus['step_02']) {
          $('#ready_to_pay').val(false);
          } else {
          if(!$('#ready_to_pay').val()) {
          $('#ready_to_pay').val('ready_to_pay');
          }
          }*/
     });

    /*$('.password-input:required').on('blur', function(event) {
     console.log('password-input blur', $(this).attr('name'));
     var $This = $(this);
     $('#ci_password').val($This.val());
     //setStyleSteps($This.closest('.step'));
     setStyleSteps($('#step_02'));
     });*/
    $FormSell.find('input[type="password"]:required').on('blur', function(event) {
        var $This = $(this);
        if($This.val().length) {
            $This.closest('.form-group').removeClass('has-error').addClass('has-success');
        }  else {
            $This.closest('.form-group').removeClass('has-success').addClass('has-error');
        }
    });
    /*
    $FormSell.find('input[type="password"]:required').on('blur', function(event) {
        event.preventDefault();
        alert('blur');
        console.log('blur input[type="password"]:required', $(this).attr('name'));
//        var $This = $(this);
//        console.log($This.closest('.step'));
//        setStyleSteps($This.closest('.step'));

        setStyleSteps($('#step_02'));
    });
    */

    var $BtnSubmit = $('#btn_submit');
    var $NavBtnSubmit = $('#nav_btn_submit');
    var $BtnCheck = $('#btn_check');
    var $NavBtnCheck = $('#nav_btn_check');
    var $BtnPay = $LastStep.find('.btn_pay');
    var $NavBtnPay = $('#nav_btn_pay');

    var $BtnModify = $('#btn_modify');
    $BtnModify.on('click', function() {
        $('.step_to_check').attr('data-toggle', 'collapse');
        //$('#ready_to_pay').val('');
        $('#ready_to_pay').val(false);

        $BtnSubmit.addClass('hidden').hide();
        $NavBtnSubmit.addClass('hidden').hide();

        $BtnCheck.removeClass('hidden').show();
        $NavBtnCheck.removeClass('hidden').show();

        $BtnPay.addClass('hidden').hide();
        $NavBtnPay.addClass('hidden').hide();

        $Steps.collapse('show');
        $LastStep.collapse('hide');
        $LastStep.parents('.panel').slideUp();

        $BtnCheck.on('click',function(event){
            event.preventDefault();
            $FormSell.submit();
        });
    });

    $('#nav_btn_submit, #nav_btn_check, #nav_btn_pay').on('click', function(event) {
        event.preventDefault();
        var $This = $(this);
        var id = $This.attr('id');
        $('#' + id.replace('nav_', '')).trigger('click');
    });

    var $BtnSteps = $('.step .btn-step');
    $BtnSteps.on('click', function() {
        var $This = $(this);
        var target = $This.attr('data-target').replace('#','#heading_');
        var top = $(target).offset().top;
        //console.log(target);
        //console.log(top);
        $('body,html').animate({scrollTop:top}, 750, 'easeOutExpo');

        $($This.attr('data-current')).collapse('hide').promise().done(function() {
            $($This.attr('data-target')).collapse('show');
        });
    });

    $('#form_ads_edit').on('submit', function(event) {
        if($('#photos .template-upload > div').length>0) {
            event.preventDefault();
            var $PhotosMissing = $('#photos_missing');
            var $FaIcon = $('<i class="fa fa-exclamation-circle fa-2x fa-fw text-danger" aria-hidden="true"></i>');
            var message_photos_not_uploaded = $PhotosMissing.attr('data-msg_not_uploaded');
            $('.alert p', $PhotosMissing)
                .empty()
                .html(message_photos_not_uploaded)
                .prepend($FaIcon);

            $('body,html').animate({scrollTop:$PhotosMissing.offset().top}, 750, 'easeOutExpo');
        }
    });
});
