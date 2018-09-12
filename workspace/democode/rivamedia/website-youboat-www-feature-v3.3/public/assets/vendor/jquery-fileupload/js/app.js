/*
 * jQuery File Upload Plugin JS Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    'use strict';

    var $FileUpload = $('#fileupload');

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/server/php/',
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        maxFileSize: 3000000,
        sequentialUploads: true,
        //acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        acceptFileTypes: /^image\/(gif|jpeg|png)$/i,
        //imageMinWidth:,
        //imageMinHeight:,
        imageMaxWidth: 800,
        imageMaxHeight: 800,
        //maxNumberOfFiles: 10,
        dropZone: $('#dropzone')
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    // Load existing files:
    $('#fileupload').addClass('fileupload-processing');
    $.ajax({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: $('#fileupload').fileupload('option', 'url'),
        dataType: 'json',
        cache: false,
        context: $('#fileupload')[0]
    }).always(function () {
        $(this).removeClass('fileupload-processing');
    }).done(function (result) {
        console.log(result);
        console.log($(this));
        console.log(this);
        $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
    });

    $('#fileupload').bind('change', function (e) {
        $('#fileupload').fileupload('add', {
            fileInput: $(this)
        });
    });

    $('#fileupload').bind('submit', function (e) {
        console.log('submit');
    });

    $('button[type="submit"]', $('#fileupload')).on('click', function(e) {
        console.log('type="submit"');
    });

    $('#fileupload').fileupload({
        submit: function (e, data) {
            console.log(e);
            var $this = $(this);
            $.getJSON($('#fileupload').fileupload('option', 'url'), function (result) {
                data.formData = result; // e.g. {id: 123}
                data.jqXHR = $this.fileupload('send', data);
            });
            return false;
        }
    });
});
$(document).bind('drop dragover', function (e) {
    e.preventDefault();
});
$(document).bind('dragover', function (e) {
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
