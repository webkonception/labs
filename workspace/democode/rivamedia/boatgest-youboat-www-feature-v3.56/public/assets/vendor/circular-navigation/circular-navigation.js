$(document).ready(function () {

    var $Button = $('#cn-button');
    var $Wrapper = $('#cn-wrapper');
    var $Overlay = $('#cn-overlay');
    var open = false;

    var openNav = function () {
        open = true;
        $Button.html($Button.attr('data-active'));
        $Button.addClass('active');
        $Overlay.addClass('on-overlay');
        $Wrapper.addClass('opened-nav');
    };

    var closeNav = function () {
        open = false;
        $Button.html($Button.attr('data-default'));
        $Button.removeClass('active');
        $Overlay.removeClass('on-overlay');
        $Wrapper.removeClass('opened-nav');
    };

    $Button.on('click', function(event) {
        event.preventDefault();
        if(!open){
            openNav();
        } else{
            closeNav();
        }
    });
    $Overlay.on('click', function(event) {
        event.preventDefault();
        closeNav();
    });
});
////
/*
 (function(){

    var $Button = document.getElementById('cn-button'),
        $Wrapper = document.getElementById('cn-wrapper'),
        $Overlay = document.getElementById('cn-overlay');

    //open and close menu when the button is clicked
    var open = false;

    $Button.addEventListener('click', handler, false);
    $Wrapper.addEventListener('click', cnhandle, false);


    function cnhandle(e){
        e.stopPropagation();
    }

    function handler(e) {
        if (!e) {
            var e = window.event;
        }
        e.stopPropagation();//so that it doesn't trigger click event on document

        if(!open){
            openNav();
        }
        else{
            closeNav();
        }
    }
    function openNav() {
        open = true;
        $Button.innerHTML = "-";
        $Button.innerHTML = "-";
        classie.add($Button, 'active');
        classie.add($Overlay, 'on-overlay');
        classie.add($Wrapper, 'opened-nav');
    }
    function closeNav() {
        open = false;
        $Button.innerHTML = "+";
        classie.remove($Button, 'active');
        classie.remove($Overlay, 'on-overlay');
        classie.remove($Wrapper, 'opened-nav');
    }
    document.addEventListener('click', closeNav);
 })();
*/

