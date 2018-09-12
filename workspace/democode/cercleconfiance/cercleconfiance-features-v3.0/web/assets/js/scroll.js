/**
 * Created by julien on 04/07/17.
 */

$(document).ready(function () {

    Link();
    $(window).scroll(function (event) {
        Link();
    });

    function Link() {
        var scroll = $(window).scrollTop();
        var scrollQSN = $('#intro').length ? $('#intro').offset().top - 10 : 0;
        var scrollServ = $('#serve').length ? $('#serve').offset().top - 10 : 0;
        var scrollObj = $('#object').length ? $('#object').offset().top - 10 : 0;
        if (scroll < scrollQSN) {
            $('li.scroll').removeClass('active');
            $('#navAcc').addClass('active');
        } else if (scroll >= scrollQSN && scroll < scrollServ) {
            $('li.scroll').removeClass('active');
            $('#navQSN').addClass('active');
        } else if (scroll >= scrollServ && scroll < scrollObj) {
            $('li.scroll').removeClass('active');
            $('#navServ').addClass('active');
        } else if (scroll >= scrollObj) {
            $('li.scroll').removeClass('active');
            $('#navObj').addClass('active');
        }
    }
});