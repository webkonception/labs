$( function() {
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
    CKEDITOR.replace( 'ad_description', ck_config );
    CKEDITOR.replace( 'ad_specifications', ck_config );
    CKEDITOR.replace( 'ad_features', ck_config );

    var $TabPane = $('.tab-pane');
    var $ElmsErrors = $TabPane.has('.form-group.has-error, .panel-body.has-error');
    $ElmsErrors.each(function(index) {
        var $This = $(this);
        var $NavTabs = $('.nav.nav-tabs');
        var $Link = $NavTabs.find('a[href="#' + $This.attr('id') + '"]');
        $Link.addClass('bg-danger')
            .html('<span class="text-danger">' + $Link.html() + '</span>')
            .append($('<i>', {'class': 'fa fa-exclamation-circle text-danger', 'aria-hidden': 'true'}));

        if($This.find('.panel-body.has-error').length > 0) {
            var id = $This.find('.panel-body.has-error').closest('.panel-collapse').attr('id');
            $('a[href="#' + id + '"][data-toggle="collapse"]').append($('<i>', {'class': 'fa fa-exclamation-circle text-danger', 'aria-hidden': 'true'})).trigger('click').children().addClass('text-danger');
        }
    });

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
                }
            });

            if(empty) {
                $Target.remove();
            } else {
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
        template = '<fieldset id="' + id + '" class="well well-white">';
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
} );
