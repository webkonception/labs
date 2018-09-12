/*global window, $ */
$(function () {
    'use strict';

    var fileUploadUrl = '/server/php/';
    var $FileUpload = $('#fileupload');
    if($FileUpload.length > 0) {
        var custom_dir = $FileUpload.attr('data-custom_dir');
        var host = $FileUpload.attr('data-host');
        var filename_prefix = 'photo-';
        var params = '';
        params = '&host=' + host;
        params += '&filename_prefix=' + filename_prefix;
        var $filesContainer = $('.gallery #photos');
        var $BtnsDelete = $filesContainer.find('button.delete');
        var $InputsPhoto = $filesContainer.find('.photo input[name="upload_photos[]"]')

        $BtnsDelete.on('click', function (event) {
            event.preventDefault();
            var $This = $(this);
            $This.closest('.photo').remove();
        });

        var reOrderPositions = function($oElm) {
            //console.log('reOrderPositions');
            //console.log($oElm.children().length);
            $oElm.children().each(function(index) {
                var $This = $(this);
                var position = index+1;
                //console.log('position', position);
                //console.log($This.find('.sort'));
                $This.find('.sort').text(position);
            });
        };

        $FileUpload.fileupload({
            url: fileUploadUrl,
            //method: 'POST',
            dataType: 'json',
            formData: [{ name: 'custom_dir', value: custom_dir },{ name: 'host', value: host },{ name: 'filename_prefix', value: filename_prefix }],
            autoUpload: false,
            uploadsDeletable: true,
            prependFiles: false,
            save_original: true,

            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            //acceptFileTypes: /^image\/(gif|jpeg|png)$/i,
            maxFileSize: 3000000,
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),

            ////
            sequentialUploads: true,
            //imageMinWidth:,
            //imageMinHeight:,
            imageMaxWidth: 800,
            imageMaxHeight: 800,

            // Define if resized images should be cropped or only scaled:
            imageCrop: false,

            // Disable the resize image functionality by default:
            disableImageResize: false,
            // The maximum width of the preview images:
            previewMaxWidth: 170,
            // The maximum height of the preview images:
            previewMaxHeight: 114,
            // Create the preview using the Exif data thumbnail:
            previewThumbnail: false,
            // Define if preview images should be cropped or only scaled:
            previewCrop: true,
            // Define if preview images should be resized as canvas elements:
            previewCanvas: true,

            //maxNumberOfFiles: 10,

            dropZone: $('#dropzone'),
            filesContainer: $filesContainer,

            //uploadTemplateId: null,
            uploadTemplateId: null,
            downloadTemplateId: null,

            uploadTemplate: function (o) {
                var rows = $();
                var first = false;
                $.each(o.files, function (index, file) {
                    var template = '';
                    if(first) {
                      template += '<div class="col-xs-12"><hr></div>';
                      first = false;
                    }
                    template += '<div class="photo col-xs-12 col-sm-6 col-md-4 col-lg-3 ui-sortable-handle template-upload fade">';
                    template += '   <div class="text-center row well well-success">';
                    template += '       <div class="col-xs-10">';
                    template += '           <div class="preview"></div>';
                    template += '           <div class="name"></div>';
                    template += '           <div><strong class="error text-danger"></strong></div>';
                    template += '       </div>';

                    template += '       <div class="pull-right">';
                    if (!index && !o.options.autoUpload) {
                        template += '       <button class="btn btn-primary start btn-exception" title="Start" disabled>';
                        template += '           <i class="glyphicon glyphicon-upload"></i>';
                        template += '       </button>';
                    }
                    if (!index) {
                        template += '       <button class="btn btn-warning cancel btn-exception" title="Cancel">';
                        template += '           <i class="glyphicon glyphicon-ban-circle"></i>';
                        template += '       </button>';
                    }
                    template += '       </div>';

                    template += '       <div class="col-xs-12">';
                    template += '           <div class="progressbar">';
                    template += '             <p class="size hidden">Processing...</p>';
                    template += '           <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>';
                    template += '       </div>';

                    template += '   </div>';
                    template += '</div>';


                    var row = $(template);

                    row.find('.name').text(file.name);
                    row.find('.size').text(o.formatFileSize(file.size));
                    if (file.error) {
                        row.find('.error').text(file.error);
                    }
                    rows = rows.add(row);
                });
                return rows;
            },
            downloadTemplate: function (o) {
                var rows = $();
                $.each(o.files, function (index, file) {
                    $FileUpload.addClass('fileupload-processing');
                    //console.log('downloadTemplate :: ', index);
                    var template =
                                '<div class="photo col-xs-12 col-sm-6 col-md-4 col-lg-3 ui-sortable-handle template-download fade">';
                    template += '   <div class="text-center row well">';
                    template += '       <div class="col-xs-10">';
                    template += '           <div class="preview"></div>';
                    template += '           <div class="name"></div>';
                    if (file.error) {
                        template += '       <div><span class="label label-danger">Error</span><strong class="error text-danger"></strong></div>';
                    }
                    template += '           <div class="size hidden"></div>';
                    template += '       </div>';
                    template += '       <div class="pull-right">';
                    template += '           <div class="sort label label-primary">' + (index + 1) + '</div>';
                    if (file.deleteUrl) {
                        template += '       <button class="btn btn-danger delete"';
                        if (file.deleteWithCredentials) {
                            template += ' data-xhr-fields="{\'withCredentials\':true}"';
                        }
                        template += '>';
                        template += '           <i class="glyphicon glyphicon-trash"></i>';
                        template += '       </button>';
                        template += '       <input type="checkbox" name="delete" value="1" class="toggle">';
                    } else {
                        template += '       <button class="btn btn-warning cancel btn-exception">';
                        template += '           <i class="glyphicon glyphicon-ban-circle"></i>';
                        template += '       </button>';
                    }
                    template += '       </div>';
                    template += '   </div>';
                    template += '</div>';

                    var row = $(template);

                    row.find('.size').text(o.formatFileSize(file.size));

                    if (file.error) {
                        row.find('.name').text(file.name);
                        row.find('.error').text(file.error);
                        row.find('.preview').html('<div class="text-danger"><i class="fa fa-ban fa-3x"></i></div>');
                    } else {
                        row.find('.name').append($('<a></a>').text(file.name));
                        row.find('.name').append($('<input/>', {'class': 'form-control', 'name': 'upload_photos[]', 'id': 'ad_photos_' + index, 'type': 'hidden', 'value': file.path + '/' + file.name }));

                        if (file.thumbnailUrl) {
                            row.find('.preview').append(
                                $('<a></a>').addClass('item').append(
                                    $('<img>').addClass('thumbnail img-responsive inline').prop('src', file.thumbnailUrl)
                                ).append($('<span class="fa-stack fa-2x"><i class="fa fa-square fa-stack-2x fa-inverse"></i><i class="fa fa-arrows-alt fa-stack-1x"></i></span>'))
                            );
                        } else {
                            row.find('.preview').append(
                                $('<a></a>').addClass('item').append(
                                    $('<img>').addClass('thumbnail img-responsive inline').prop('src', file.path + '/thumbnail/' + file.name )
                                ).append($('<span class="fa-stack fa-2x"><i class="fa fa-square fa-stack-2x fa-inverse"></i><i class="fa fa-arrows-alt fa-stack-1x"></i></span>'))
                            );
                        }
                        row.find('a')
                            .attr('data-gallery', '')
                            .prop('href', file.url);
                        row.find('button.delete')
                            .attr('data-cache', false)
                            .attr('data-type', file.deleteType)
                            .attr('data-custom_dir', custom_dir)
                            //.attr('data-url', file.deleteUrl + '&custom_dir=' + custom_dir);
                            .attr('data-url', file.deleteUrl + '&custom_dir=' + custom_dir + params);
                    }
                    rows = rows.add(row);
                });
                return rows;
            }
      })
      //Callback Options
      .on('fileuploadadd', function (e, data) {
          //console.log('fileuploadadd');
          })

      .on('fileuploadsubmit', function (e, data) {
          //console.log('fileuploadsubmit');
          })

      .on('fileuploadsend', function (e, data) {
          //console.log('fileuploadsend');
          })

      .on('fileuploaddone', function (e, data) {
          //console.log('fileuploaddone');

          //console.log(data.result);
          //console.log(data.result.files);
          if (data.result.is_valid) {
            $("#photos").prepend("<a href='" + data.result.url + "'>" + data.result.name + "</a><br>");
          }
          })
      .on('fileuploadfail', function (e, data) {
          //console.log('fileuploadfail');
          })
      .on('fileuploadalways', function (e, data) {
          //console.log('fileuploadalways');
          })
      .on('fileuploadprogress', function (e, data) {
          //console.log('fileuploadprogress');
          })
      .on('fileuploadprogressall', function (e, data) {
          //console.log('fileuploadprogressall');
          //console.log('fileuploadprogressall');
          var progress = parseInt(data.loaded / data.total * 100, 10);
          var strProgress = progress + "%";
          $('.fileupload-progress').removeClass('fade');
          $(".progress-bar").css({"width": strProgress});
          $(".progress-bar").text(strProgress);
          })
      .on('fileuploadstart', function (e) {
          //console.log('fileuploadstart');
          $("#modal-progress").modal("show");
          })
      .on('fileuploadstop', function (e) {
          //console.log('fileuploadstop');
          //console.log('fileuploadstop');
          $("#modal-progress").modal("hide");
          $(".progress-bar").css({"width": 0});
          $(".progress-bar").text('');
          })
      .on('fileuploadchange', function (e, data) {
          //console.log('fileuploadchange');
          })
      .on('fileuploadpaste', function (e, data) {
          //console.log('fileuploadpaste');
          })
      .on('fileuploaddrop', function (e, data) {
          //console.log('fileuploaddrop');
          })
      .on('fileuploaddragover', function (e) {
          //console.log('fileuploaddragover');
          })
      .on('fileuploadchunksend', function (e, data) {
          //console.log('fileuploadchunksend');
          })
      .on('fileuploadchunkdone', function (e, data) {
          //console.log('fileuploadchunkdone');
          })
      .on('fileuploadchunkfail', function (e, data) {
          //console.log('fileuploadchunkfail');
          })
      .on('fileuploadchunkalways', function (e, data) {
          //console.log('fileuploadchunkalways');
          })

      //Processing Callback Options
      .on('fileuploadprocessstart', function (e) {
          //console.log('fileuploadprocessstart');
          })
      .on('fileuploadprocess', function (e, data) {
          //console.log('fileuploadprocess');
          $FileUpload.addClass('fileupload-processing');
          })
      .on('fileuploadprocessdone', function (e, data) {
            //console.log('fileuploadprocessdone');
            })
      .on('fileuploadprocessfail', function (e, data) {
            //console.log('fileuploadprocessfail');
            })
      .on('fileuploadprocessalways', function (e, data) {
            //console.log('fileuploadprocessalways');
            })
      .on('fileuploadprocessstop', function (e) {
            //console.log('fileuploadprocessstop');
          $FileUpload.removeClass('fileupload-processing');
          })

      //Additional Callback Options for the UI version
      .on('fileuploaddestroyed', function (e, data) {
          //console.log('fileuploaddestroyed');
          // data.context: download row,
          // data.url: deletion url,
          // data.type: deletion request type, e.g. "DELETE",
          // data.dataType: deletion response type, e.g. "json"
          //console.log('fileuploaddestroy');
          //console.log(data);
          //console.log(data.url);
          //console.log(data.context);
          //console.log(data.dataType);
          //console.log(data.type);
          reOrderPositions($filesContainer.find('.template-download'));
       })
      .on('fileuploadadded', function (e, data) {
          //console.log('fileuploadadded');
          })
      .on('fileuploadsent', function (e, data) {
          //console.log('fileuploadsent');
          })
      .on('fileuploadcompleted', function (e, data) {
          //console.log('fileuploadcompleted');
          })

      .on('fileuploadfailed', function (e, data) {
          //console.log('fileuploadfailed');
          })

      .on('fileuploadfinished', function (e, data) {
          //console.log('fileuploadfinished');
          $FileUpload.removeClass('fileupload-processing');
          })

      .on('fileuploadstarted', function (e) {
          //console.log('fileuploadstarted');
          })
      .on('fileuploadstopped', function (e) {
          //console.log('fileuploadstopped');
          reOrderPositions($filesContainer.find('.template-download'));
          })

            /*.on('fileuploadprocessalways', function (e, data) {
                //console.log('fileuploadprocessalways');
                var index = data.index,
                    file = data.files[index],
                    node = $(data.context.children()[index]);
                if (file.preview) {
                    node
                        .prepend('<br>')
                        .prepend(file.preview);
                }
                if (file.error) {
                    node
                        .append('<br>')
                        .append($('<span class="text-danger"/>').text(file.error));
                }
                if (index + 1 === data.files.length) {
                    data.context.find('button')
                        .text('Upload')
                        .prop('disabled', !!data.files.error);
                }
            })*/
        /*
        .on('fileuploaddone', function (e, data) {
            //console.log('fileuploaddone');
            //console.log(data.result.files[0]);
            $.each(data.result.files, function (index, file) {
                if (file.url) {
                    var link = $('<a>')
                        .attr('target', '_blank')
                        .prop('href', file.url);
                    $(data.context.children()[index])
                        .wrap(link);
                } else if (file.error) {
                    var error = $('<span class="text-danger"/>').text(file.error);
                    $(data.context.children()[index])
                        .append('<br>')
                        .append(error);
                }
            });
        }).on('fileuploadfail', function (e, data) {
            //console.log('fileuploadfail');
            $.each(data.files, function (index) {
                var error = $('<span class="text-danger"/>').text('File upload failed.');
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            });
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled')
         */
        ;

        // Load existing files:
        $FileUpload.addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $FileUpload.fileupload('option', 'url'),
            data: { custom_dir: custom_dir, host: host, filename_prefix: filename_prefix },
            dataType: 'json',
            cache: false,
            context: $FileUpload[0]
        })
      .done(function (result) {
            if(typeof existing_files != undefined && '' != existing_files) {
                result = existing_files;
            }
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });

    }
});

$(document).on('drop dragover', function (e) {
    e.preventDefault();
});

$(document).on('dragover', function (e) {
    var dropZone = $('#dropzone'),
        timeout = window.dropZoneTimeout;
    if (!timeout) {
        dropZone.addClass('in');
    } else {
        clearTimeout(timeout);
    }
    var found = false,
        node = e.target;
    do {
        if (node === dropZone[0]) {
            found = true;
            break;
        }
        node = node.parentNode;
    } while (node != null);
    if (found) {
        dropZone.addClass('hover');
    } else {
        dropZone.removeClass('hover');
    }
    window.dropZoneTimeout = setTimeout(function () {
        window.dropZoneTimeout = null;
        dropZone.removeClass('in hover');
    }, 100);
});
