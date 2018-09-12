// Load WOW.js on non-touch devices
var isPhoneDevice = "ontouchstart" in document.documentElement;
var recaptchaCallback = function (response) {
    if (response.length > 0) {
        console.log(response);
        var $This = $('#g-recaptcha-response');
        $This.parents('.form-group')
            .addClass('has-success')
            .removeClass('has-error')
            .find('.help-block').remove();
        //var $Form = $('form[role="form"]');
        var $Form = $This.parents('form');
        checkInputsFilled($Form);
    }
};
$(document).ready(function() {
    // vegas
    var slidesLocation = [];
    var imgInfos = {'dir': '/assets/vendor/youboat/landing/img/boats/', 'count': 5};
    var i = 0;

    for (i; i<imgInfos['count']; i++) {
        slidesLocation.push({ src: imgInfos['dir'] + i + '-min.jpg' });
    }
    $("body").vegas({
        delay: 6000,
        timer: false,
        transitionDuration: 2000,
        slides: slidesLocation,
        transition: 'swirlRight',
        animation: 'kenburns'
    });

    var $FormGetNotified = $('#formGetNotified');
    $('a[href="#formGetNotified"]').on('click', function(event) {
        event.preventDefault();
        var $This = $(this);
        if ($FormGetNotified.length > 0) {
            $('#name', $FormGetNotified).trigger('focus');
        } else {
            window.location = $This.attr('data-contact');
        }
    });

    if ($FormGetNotified.length > 0) {
        var $Inputs = $('input', $FormGetNotified).not('[type="hidden"]');
        $('button', $FormGetNotified).attr('disabled', 'disabled');

        $Inputs.on('blur', function (event) {
            event.preventDefault();
            var count = 0;
            $Inputs.filter(function (index) {
                count += ($(this).val() != '') ? 1 : 0;
            });

            if (count === $Inputs.length) {
                $('button', $FormGetNotified).removeAttr('disabled', 'disabled');

                /*if ($('.g-recaptcha').length > 0) {
                 $('.g-recaptcha').parent().removeClass('hidden');
                 }*/
            } else {
                $('button', $FormGetNotified).attr('disabled', 'disabled').removeClass('btn-success');

                /*if ($('.g-recaptcha').length > 0) {
                 $('.g-recaptcha').parent().addClass('hidden');
                 }*/
            }

            if ($('.g-recaptcha').length > 0) {
                $('.g-recaptcha').parent().removeClass('hidden');
            }

        });
        $Inputs.on('focus', function (event) {
            event.preventDefault();
            var $This = $(this);
            $This.parents('.form-group')
                .removeClass('has-error')
                .find('.help-block').remove();
        });
    }

    if (isPhoneDevice) {
        //mobile
    } else {
        //desktop
        // Initialize WOW.js
        wow = new WOW({
            offset: 50
        })
        wow.init();
    }

    // Countdown setting
    $('#clock').countdown('2016/06/30 10:00:00').on('update.countdown', function(event) {
        var $this = $(this).html(event.strftime(''
            + '<div><span>%-w</span>week%!w</div>'
            + '<div><span>%-d</span>day%!d</div>'
            + '<div><span>%H</span>hr</div>'
            + '<div><span>%M</span>min</div>'
            + '<div><span>%S</span>sec</div>'));
    });
});
