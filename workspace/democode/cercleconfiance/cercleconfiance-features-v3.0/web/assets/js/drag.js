$(document).ready(function () {

    $('#dropzone').on('dragenter', function() {
        $(this).removeClass('dragover dragleave drop').addClass('dragenter');
        return false;
    });

    $('#dropzone').on('dragover', function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragenter dragleave drop').addClass('dragover');
        return false;
    });

    $('#dropzone').on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragenter dragover drop').addClass('dragleave');
        return false;
    });

    $('#dropzone').on('drop', function(e) {
        if(e.originalEvent.dataTransfer){
            if(e.originalEvent.dataTransfer.files.length) {
                // Stop the propagation of the event
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragenter dragover dragleave').addClass('drop');
                // Main function to upload
                //$('#form_file_name').val(e.originalEvent.dataTransfer.files);
                form_file_name.files = e.originalEvent.dataTransfer.files;
                //upload(e.originalEvent.dataTransfer.files);
            }
        }
        else {
            $(this).css('border', '3px dashed red');
        }
        return false;
    });
});
