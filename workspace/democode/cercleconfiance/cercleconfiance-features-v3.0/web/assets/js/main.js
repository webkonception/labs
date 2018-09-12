jQuery(function ($) {
    'use strict';

    if("ontouchstart" in window){
        document.documentElement.className = document.documentElement.className + " touch";
    }

    $('.navbar-collapse ul li a').on('click', function () {
        $('html, body').animate({scrollTop: $(this.hash).offset().top - 5}, 1000);
        return false;
    });

    $('#tohash').on('click', function () {
        $('html, body').animate({scrollTop: $(this.hash).offset().top - 5}, 1000);
        return false;
    });

    // accordian
    $('.accordion-toggle').on('click', function () {
        $(this).closest('.panel-group').children().each(function () {
            $(this).find('>.panel-heading').removeClass('active');
        });

        $(this).closest('.panel-heading').toggleClass('active');
    });

    //Slider
    $(document).ready(function () {
        $('a.blank').on('click', function (event) {
            event.preventDefault();
            window.open($(this).attr('href'));
        });

        var time = 5; // time in seconds

        var $progressBar,
            $bar,
            $elem,
            isPause,
            tick,
            percentTime;

        //Init the carousel
        $("#main-slider").find('.owl-carousel').owlCarousel({
            slideSpeed: 500,
            paginationSpeed: 500,
            singleItem: true,
            navigation: true,
            navigationText: [
                "<i class='fa fa-angle-left'></i>",
                "<i class='fa fa-angle-right'></i>"
            ],
            afterInit: progressBar,
            afterMove: moved,
            startDragging: pauseOnDragging,
            //autoHeight : true,
            transitionStyle: "fadeUp"
        });

        //Init progressBar where elem is $("#owl-demo")
        function progressBar(elem) {
            $elem = elem;
            //build progress bar elements
            buildProgressBar();
            //start counting
            start();
        }

        //create div#progressBar and div#bar then append to $(".owl-carousel")
        function buildProgressBar() {
            $progressBar = $("<div>", {
                id: "progressBar"
            });
            $bar = $("<div>", {
                id: "bar"
            });
            $progressBar.append($bar).appendTo($elem);
        }

        function start() {
            //reset timer
            percentTime = 0;
            isPause = false;
            //run interval every 0.01 second
            tick = setInterval(interval, 10);
        };

        function interval() {
            if (isPause === false) {
                percentTime += 1 / time;
                $bar.css({
                    width: percentTime + "%"
                });
                //if percentTime is equal or greater than 100
                if (percentTime >= 100) {
                    //slide to next item
                    $elem.trigger('owl.next')
                }
            }
        }

        //pause while dragging
        function pauseOnDragging() {
            isPause = true;
        }

        //moved callback
        function moved() {
            //clear interval
            clearTimeout(tick);
            //start again
            start();
        }
    });

    //Initiat WOW JS
    new WOW().init();

    //smoothScroll
    smoothScroll.init();

});