var progressBar = function($oElm, counter, updateCss) {
    updateCss = ('undefined' == typeof updateCss) ? true :  updateCss;
    $oElm.parent('.progress').addClass('active');

    $oElm
        .show()
        .attr('aria-valuenow', counter)
        .data("origWidth", counter+'%')
        .width(counter+'%')
        .animate({
                width: $(this).data('origWidth'),
            },
            {
                //duration: 2000,
                duration: 'fast',
                progress: function(obj) {
                    if(!$(this).parent('.progress').find('.loading').length) {
                        $(this).parent('.progress').find('.title')
                            .append('<span class="loading"><i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span></span>');
                    }

                    //var $oElm = $(obj.elem);
                    //var progress_value = $(this).attr('aria-valuenow');
                    var progress_value = obj.elem.attributes['aria-valuenow'].nodeValue;

                    var status = '';
                    if(progress_value <=25){
                        status = '-danger';
                    } else if(progress_value >25 && progress_value <=50) {
                        status = '-warning';
                    } else if(progress_value >50 && progress_value <=75) {
                        status = '-info';
                    } else if(progress_value >75 && progress_value <=100) {
                        status = '-success';
                    }
                    //$(this).removeClass('progress-bar-danger progress-bar-warning progress-bar-info progress-bar-success');
                    //$(this).addClass('progress-bar' + status);
                    //$(this).html(progress_value + '%', status);
                    if(updateCss) {
                        obj.elem.className = 'progress-bar progress-bar-striped active ' + 'progress-bar' + status;
                    }
                    obj.elem.innerHTML = progress_value + '%';
                },
                complete: function() {
                    var progress_value = $(this).attr('aria-valuenow');
                    if('100' == progress_value) {
                        //console.log('complete');
                        $(this).append( " Complete" );
                        $(this).removeClass('active');
                        $(this).parent('.progress').removeClass('active').find('.loading').remove();
                    }
                }
            });
};

$(document).ready(function () {

});