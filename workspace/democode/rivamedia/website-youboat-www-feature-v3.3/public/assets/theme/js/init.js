jQuery(function ($) {
	//"use strict";

	var YOUBOAT = window.YOUBOAT || {};

	/* ==================================================
		Contact Form Validations
	================================================== */
	YOUBOAT.ContactForm = function () {
		$('.contact-form').each(function () {
			var formInstance = $(this);
			formInstance.submit(function () {

				var action = $(this).attr('action');

				$("#message").slideUp(750,function () {
					$('#message').hide();

					$('#submit')
						.after('<img src="images/assets/ajax-loader.gif" class="loader" />')
						.attr('disabled','disabled');

					$.post(
						action,
						{
							fname: $('#fname').val(),
							lname: $('#lname').val(),
							email: $('#email').val(),
							phone: $('#phone').val(),
							comments: $('#comments').val()
						},
						function (data) {
							document.getElementById('message').innerHTML = data;
							$('#message').slideDown('slow');
							$('.contact-form img.loader').fadeOut('slow',function () {$(this).remove()});
							$('#submit').removeAttr('disabled');
							if(data.match('success') != null) $('.contact-form').slideUp('slow');
						}
					);
				});
				return false;
			});
		});
	};

	/* ==================================================
		Scroll to Top
	================================================== */
	YOUBOAT.scrollToTop = function () {
		var windowWidth = $(window).width(),
			didScroll = false;

		var $arrow = $('#back-to-top');

		$arrow.on("click", function (e) {
			$('body,html').animate({ scrollTop: "0" }, 750, 'easeOutExpo' );
			e.preventDefault();
		});

		$(window).scroll(function () {
			didScroll = true;
		});

		setInterval(function () {
			if( didScroll ) {
				didScroll = false;

				if( $(window).scrollTop() > 200 ) {
					$arrow.fadeIn();
				} else {
					$arrow.fadeOut();
				}
			}
		}, 250);
	};

	/* ==================================================
	   Accordion
	================================================== */
	YOUBOAT.accordion = function () {
		var accordion_trigger = $('.accordion-heading.accordionize');

		accordion_trigger.delegate('.accordion-toggle','click', function (event) {
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).addClass('inactive');
			}
			else{
				accordion_trigger.find('.active').addClass('inactive');
				accordion_trigger.find('.active').removeClass('active');
				$(this).removeClass('inactive');
				$(this).addClass('active');
			}
			event.preventDefault();
		});
	};

	/* ==================================================
	   Toggle
	================================================== */
	YOUBOAT.toggle = function () {
		var accordion_trigger_toggle = $('.accordion-heading.togglize');

		accordion_trigger_toggle.delegate('.accordion-toggle','click', function (event) {
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).addClass('inactive');
			}
			else{
				$(this).removeClass('inactive');
				$(this).addClass('active');
			}
			event.preventDefault();
		});
	};

	/* ==================================================
	   Tooltip
	================================================== */
    if ('function' === typeof $.fn.tooltip) {
        YOUBOAT.toolTip = function () {
            $('a[data-toggle=tooltip]').tooltip();
            $('a[data-toggle=popover]').popover({html: true}).on("click", function (e) {
                e.preventDefault();
                $(this).focus();
            });
        };
    }

	/* ==================================================
	   Twitter Widget
	================================================== */
    if ('function' === typeof $.fn.twittie) {
        YOUBOAT.TwitterWidget = function () {
            $('.twitter-widget').each(function () {
                var twitterInstance = $(this);
                var twitterTweets = twitterInstance.attr("data-tweets-count") ? twitterInstance.attr("data-tweets-count") : "1";
                twitterInstance.twittie({
                    dateFormat: '%b. %d, %Y',
                    template: '<li><i class="fa fa-twitter"></i> <strong class="date">{{date}}</strong> - {{tweet}}</li>',
                    count: twitterTweets,
                    hideReplies: true
                });
            });
        };
    }

	/* ==================================================
	   Hero Flex Slider
	================================================== */
    if ('function' === typeof $.flexslider) {
        YOUBOAT.heroflex = function () {
            $('.heroflex').each(function () {
                var carouselInstance = $(this);
                var carouselAutoplay = carouselInstance.attr("data-autoplay") == 'yes' ? true : false;
                var carouselPagination = carouselInstance.attr("data-pagination") == 'yes' ? true : false;
                var carouselArrows = carouselInstance.attr("data-arrows") == 'yes' ? true : false;
                var carouselDirection = carouselInstance.attr("data-direction") ? carouselInstance.attr("data-direction") : "horizontal";
                var carouselStyle = carouselInstance.attr("data-style") ? carouselInstance.attr("data-style") : "fade";
                var carouselSpeed = carouselInstance.attr("data-speed") ? carouselInstance.attr("data-speed") : "5000";
                var carouselPause = carouselInstance.attr("data-pause") == 'yes' ? true : false;

                carouselInstance.flexslider({
                    animation: carouselStyle,
                    easing: "swing",
                    direction: carouselDirection,
                    slideshow: carouselAutoplay,
                    slideshowSpeed: carouselSpeed,
                    animationSpeed: 600,
                    initDelay: 0,
                    randomize: false,
                    pauseOnHover: carouselPause,
                    controlNav: carouselPagination,
                    directionNav: carouselArrows,
                    prevText: "",
                    nextText: ""
                });
            });
        };
    }

	/* ==================================================
	   Flex Slider
	================================================== */
    if ('function' === typeof $.flexslider) {
        YOUBOAT.galleryflex = function () {
            $('.galleryflex').each(function () {
                var carouselInstance = $(this);
                var carouselAutoplay = carouselInstance.attr("data-autoplay") == 'yes' ? true : false;
                var carouselPagination = carouselInstance.attr("data-pagination") == 'yes' ? true : false;
                var carouselArrows = carouselInstance.attr("data-arrows") == 'yes' ? true : false;
                var carouselDirection = carouselInstance.attr("data-direction") ? carouselInstance.attr("data-direction") : "horizontal";
                var carouselStyle = carouselInstance.attr("data-style") ? carouselInstance.attr("data-style") : "fade";
                var carouselSpeed = carouselInstance.attr("data-speed") ? carouselInstance.attr("data-speed") : "5000";
                var carouselPause = carouselInstance.attr("data-pause") == 'yes' ? true : false;

                carouselInstance.flexslider({
                    animation: carouselStyle,
                    easing: "swing",
                    direction: carouselDirection,
                    slideshow: carouselAutoplay,
                    slideshowSpeed: carouselSpeed,
                    animationSpeed: 600,
                    initDelay: 0,
                    randomize: false,
                    pauseOnHover: carouselPause,
                    controlNav: carouselPagination,
                    directionNav: carouselArrows,
                    prevText: "",
                    nextText: ""
                });
            });
        };
    }

	/* ==================================================
	   Owl Carousel
	================================================== */
    if ('function' === typeof $.fn.owlCarousel) {
        YOUBOAT.OwlCarousel = function () {
            $('.owl-carousel').each(function () {
                var carouselInstance = $(this);
                var carouselColumns = carouselInstance.attr("data-columns") ? carouselInstance.attr("data-columns") : "1";
                var carouselitemsDesktop = carouselInstance.attr("data-items-desktop") ? carouselInstance.attr("data-items-desktop") : "4";
                var carouselitemsDesktopSmall = carouselInstance.attr("data-items-desktop-small") ? carouselInstance.attr("data-items-desktop-small") : "3";
                var carouselitemsTablet = carouselInstance.attr("data-items-tablet") ? carouselInstance.attr("data-items-tablet") : "2";
                var carouselitemsMobile = carouselInstance.attr("data-items-mobile") ? carouselInstance.attr("data-items-mobile") : "1";
                var carouselAutoplay = carouselInstance.attr("data-autoplay") ? carouselInstance.attr("data-autoplay") : false;
                var carouselPagination = carouselInstance.attr("data-pagination") == 'yes' ? true : false;
                var carouselArrows = carouselInstance.attr("data-arrows") == 'yes' ? true : false;
                var carouselSingle = carouselInstance.attr("data-single-item") == 'yes' ? true : false;
                var carouselStyle = carouselInstance.attr("data-style") ? carouselInstance.attr("data-style") : "fade";

                carouselInstance.owlCarousel({
                    items: carouselColumns,
                    autoPlay: carouselAutoplay,
                    navigation: carouselArrows,
                    pagination: carouselPagination,
                    itemsDesktop: [1199, carouselitemsDesktop],
                    itemsDesktopSmall: [979, carouselitemsDesktopSmall],
                    itemsTablet: [768, carouselitemsTablet],
                    itemsMobile: [479, carouselitemsMobile],
                    singleItem: carouselSingle,
                    navigationText: ["<i class='fa fa-3x fa-chevron-circle-left'></i>","<i class='fa fa-3x fa-chevron-circle-right'></i>"],
                    stopOnHover: true,
                    lazyLoad: true,
                    transitionStyle: 'carouselStyle'
                });
            });
        };
    }

	/* ==================================================
	   PrettyPhoto
	================================================== */
    if ('function' === typeof $.fn.prettyPhoto) {
        YOUBOAT.PrettyPhoto = function () {
            $("a[data-rel^='prettyPhoto']").prettyPhoto({
                opacity: 0.5,
                social_tools: "",
                theme: 'pp_default', /* pp_default, light_rounded / dark_rounded / light_square / dark_square / facebook */
                deeplinking: false
            });
        };
    }

	/* ==================================================
	   Animated Counters
	================================================== */
    if ('function' === typeof $.fn.appear) {
        YOUBOAT.Counters = function () {
            $('.counters').each(function () {
                $(".timer .count").appear(function () {
                    var counter = $(this).html();
                    $(this).countTo({
                        from: 0,
                        to: counter,
                        speed: 2000,
                        refreshInterval: 60
                    });
                });
            });
        };
    }

	/* ==================================================
	   SuperFish menu
	================================================== */
    if ('function' === typeof $.fn.superfish) {
        YOUBOAT.SuperFish = function () {
            $('.sf-menu').superfish({
                delay: 200,
                animation: {opacity: 'show', height: 'show'},
                speed: 'fast',
                cssArrows: false,
                disableHI: true
            });
            $(".dd-menu > ul > li:has(ul)").find("a:first").append(" <i class='fa fa-angle-down'></i>");
            $(".dd-menu > ul > li > ul > li:has(ul)").find("a:first").append(" <i class='fa fa-angle-right'></i>");
            $(".dd-menu > ul > li > ul > li > ul > li:has(ul)").find("a:first").append(" <i class='fa fa-angle-right'></i>");
        };
    }

	/* ==================================================
	   Header Functions
	================================================== */
	YOUBOAT.StickyHeader = function () {
		//Updates scroll position
		var $header = $('.site-header');
		var $headerW = $('.site-header-wrapper');
		var $logo = $('.site-logo img');
		var $topnav = $('.top-navigation');
		var $tagline = $('.site-tagline');
		var $userbtn = $('.user-login-btn');
		var $navbar = $('.navbar');
		var $searchform = $('.navbar .search-form');
		var $bselect = $('.bootstrap-select .dropdown-menu');
		function menuScroll() {
			var lastScroll = 0;
			$(window).scroll(function (event) {
				//Sets the current scroll position
				var st = $(this).scrollTop();
				//Determines up-or-down scrolling
				if (st > lastScroll && $(window).width() > 992) {
				   //Replace this with your function call for downward-scrolling
				   $searchform.slideUp();
				   $bselect.css('visibility','hidden');
				} else {
				}

				//Updates scroll position
				lastScroll = st;
			});
		}
		if( $(window).width() > 992 ) {
			menuScroll();
		}

		setInterval(function () {
			if( $(window).scrollTop() > 30 ) {
				$header.addClass('sticky-header');
			} else {
				$header.removeClass('sticky-header');
			}
		}, 250);
	};

	/* ==================================================
		Responsive Nav Menu
	================================================== */
	YOUBOAT.MobileMenu = function () {
		// Responsive Menu Events
		$('#menu-toggle').on("click", function () {
			$(this).toggleClass("opened");
			$(".toggle-menu").slideToggle();
			  $(".site-header-wrapper").toggleClass("sticktr");
			  $(".body").toggleClass("sticktr");
			  var SHHH = $(".site-header").innerHeight();
			  var NBHH = $(".navbar").innerHeight();
			  var THHH = $(".top-header").innerHeight();
			  $(".toggle-menu").css("top",NBHH);
			  $(".header-v2 .toggle-menu").css("top",SHHH);
			  $(".header-v3 .toggle-menu").css("top",SHHH + THHH);
			return false;
		});
		$(window).resize(function () {
			if($("#menu-toggle").hasClass("opened")) {
				$(".toggle-menu").css("display","block");
			} else {
				$("#menu-toggle").css("display","none");
			}
            $(".toggle-menu .dropdown").css("display","none");
		});
	};

	/* ==================================================
	   IsoTope Portfolio
	================================================== */
    if ('function' === typeof $.Isotope) {
        YOUBOAT.IsoTope = function () {
            $("ul.sort-source").each(function () {

                var source = $(this);
                var destination = $("ul.sort-destination[data-sort-id=" + $(this).attr("data-sort-id") + "]");

                if (destination.get(0)) {

                    $(window).load(function () {

                        destination.isotope({
                            itemSelector: ".grid-item",
                            layoutMode: 'sloppyMasonry'
                        });

                        source.find("a").on("click", function (e) {

                            e.preventDefault();

                            var $this = $(this),
                                filter = $this.parent().attr("data-option-value");

                            source.find("li.active").removeClass("active");
                            $this.parent().addClass("active");

                            destination.isotope({
                                filter: filter
                            });

                            if (window.location.hash != "" || filter.replace(".", "") != "*") {
                                self.location = "#" + filter.replace(".", "");
                            }

                            return false;

                        });

                        $(window).on("hashchange", function (e) {

                            var hashFilter = "." + location.hash.replace("#", ""),
                                hash = (hashFilter == "." || hashFilter == ".*" ? "*" : hashFilter);

                            source.find("li.active").removeClass("active");
                            source.find("li[data-option-value='" + hash + "']").addClass("active");

                            destination.isotope({
                                filter: hash
                            });

                        });

                        var hashFilter = "." + (location.hash.replace("#", "") || "*");

                        var initFilterEl = source.find("li[data-option-value='" + hashFilter + "'] a");

                        if (initFilterEl.get(0)) {
                            source.find("li[data-option-value='" + hashFilter + "'] a").click();
                        } else {
                            source.find("li:first-child a").click();
                        }

                    });
                }
            });

            $(window).load(function () {
                var IsoTopeCont = $(".isotope-grid");
                IsoTopeCont.isotope({
                    itemSelector: ".grid-item",
                    layoutMode: 'sloppyMasonry'
                });
                if ($(".grid-holder").length > 0) {
                    var $container_blog = $('.grid-holder');
                    $container_blog.isotope({
                        itemSelector: '.grid-item'
                    });

                    $(window).resize(function () {
                        var $container_blog = $('.grid-holder');
                        $container_blog.isotope({
                            itemSelector: '.grid-item'
                        });
                    });
                }
            });
        };
    }

	/* ==================================================
	   Password
	================================================== */
    if ('undefined' !== typeof $.passy) {
        YOUBOAT.Password = function () {
            var $input = $('.password-input');
            var $output = $('.password-output');

            $.passy.requirements.length.min = 6;

            var feedback = [
                {width: '25%', color: '#d9534f'},
                {width: '50%', color: '#f0ad4e'},
                {width: '75%', color: '#5bc0de'},
                {width: '100%', color: '#5cb85c'}
            ];

            $input.passy(function (strength, valid) {
                $output.css('background-color', feedback[strength].color);
                $output.css('width', feedback[strength].width);
            });

            $('.password-generate').on("click", function () {
                $input.passy('generate', 8);
            });
            $('.password-input').hidePassword(true);
        };
    }

	/* ==================================================
	   Search Results Listing
	================================================== */
	YOUBOAT.RESULTS = function () {
		var $tallestCol;
		$('#results-holder').each(function () {
		    var $tallestCol = 0;
            var i = 0;
            var j = 0;
            var $Item = $(this).find('.result-item');
            $Item.each(function (index) {
                if ($Item.length>0) {
                    //$tallestCol = ($(this).height() > $tallestCol) ? $(this).height() : $tallestCol;
                    var height = $(this).find('.result-item-image').height() + $(this).find('.result-item-in').height() + 2;
                    if (i==3) {
                        i = 1;
                        $tallestCol = 0;
                        j = index;
                    } else {
                        i++;
                    }
                    $tallestCol = (height > $tallestCol) ? height : $tallestCol;
                    for(var z=1; z<=i;z++) {
                        $('#results-holder .result-item').eq(z+j-1).css('height',$tallestCol);
                    }
                    }
            });
		});
    };

    /* ==================================================
     ADSRECENT Listing
     ================================================== */
    YOUBOAT.ADSRECENT = function () {
        $('.owl-wrapper').each(function () {
            var $tallestCol = 0;
            var $Item = $(this).find('.vehicle-block');
            if ($Item.length>0) {
                $Item.each(function () {
                    var height = $(this).height();
                    $tallestCol = (height > $tallestCol) ? height : $tallestCol;
                });
                $('.owl-wrapper .vehicle-block').css('height',$tallestCol);
            }
        });
	};

	/* ==================================================
	   Pricing Tables
	================================================== */
	var $tallestCol;
	YOUBOAT.pricingTable = function () {
		$('.pricing-table').each(function () {
			$tallestCol = 0;
			$(this).find('> div .features').each(function () {
				($(this).height() > $tallestCol) ? $tallestCol = $(this).height() : $tallestCol = $tallestCol;
			});
			if($tallestCol == 0) $tallestCol = 'auto';
			$(this).find('> div .features').css('height',$tallestCol);
		});
	};

	/* ==================================================
	   Init Functions
	================================================== */
	$(document).ready(function () {
        if ('function' === typeof YOUBOAT.ContactForm) {
            YOUBOAT.ContactForm();
        }
        if ('function' === typeof YOUBOAT.scrollToTop) {
            YOUBOAT.scrollToTop();
        }
        if ('function' === typeof YOUBOAT.accordion) {
            YOUBOAT.accordion();
        }
        if ('function' === typeof YOUBOAT.toggle) {
            YOUBOAT.toggle();
        }
        if ('function' === typeof YOUBOAT.toolTip) {
            YOUBOAT.toolTip();
        }
        if ('function' === typeof YOUBOAT.TwitterWidget) {
            YOUBOAT.TwitterWidget();
        }
        if ('function' === typeof YOUBOAT.galleryflex) {
            YOUBOAT.galleryflex();
        }
        if ('function' === typeof YOUBOAT.OwlCarousel) {
            YOUBOAT.OwlCarousel();
        }
        if ('function' === typeof YOUBOAT.PrettyPhoto) {
            YOUBOAT.PrettyPhoto();
        }
        if ('function' === typeof YOUBOAT.SuperFish) {
            YOUBOAT.SuperFish();
        }
        if ('function' === typeof YOUBOAT.Counters) {
            YOUBOAT.Counters();
        }
        if ('function' === typeof YOUBOAT.IsoTope) {
            YOUBOAT.IsoTope();
        }
        if ('function' === typeof YOUBOAT.StickyHeader) {
            YOUBOAT.StickyHeader();
        }
        if ('function' === typeof YOUBOAT.heroflex) {
            YOUBOAT.heroflex();
        }
        if ('function' === typeof YOUBOAT.Password) {
            YOUBOAT.Password();
        }
        if ('function' === typeof YOUBOAT.pricingTable) {
            YOUBOAT.pricingTable();
        }
        if ('function' === typeof YOUBOAT.MobileMenu) {
            YOUBOAT.MobileMenu();
        }
        if ('function' === typeof $.fn.selectpicker) {
            $('.selectpicker').selectpicker({container: 'body'});
        }
	});

	// Any Button Scroll to section
	$('.scrollto').on("click", function () {
		$.scrollTo( this.hash, 800, { easing:'easeOutQuint' });
		return false;
	});

	$(document).ready(function () {
        if ('function' === typeof $.fn.sticky) {
            // Sticky Blocks
            var $SiteHeaderWrapper = $(".site-header-wrapper");
            var siteHeaderWrapperHeight = $SiteHeaderWrapper.height();

            var $SiteFooter = $(".site-footer");
            var siteFooterHeight = $SiteFooter.height();

            var toffset = siteHeaderWrapperHeight - 23 + 23;
            //var soffset = siteHeaderWrapperHeight + 64;
            var soffset = siteHeaderWrapperHeight;
            var goffset = siteHeaderWrapperHeight + 19;
            var boffset = siteFooterHeight + 90;

            if($(window).width() > 767 ) {
                $(".tsticky").sticky({topSpacing:toffset});
                $(".tbsticky").sticky({topSpacing:soffset, bottomSpacing: boffset});
                $(".tbssticky").sticky({topSpacing:goffset, bottomSpacing: boffset});
            }
        }

		$('.dropdown-toggle.selectpicker').on("click",function (e) {
			$('.bootstrap-select .dropdown-menu').css("visibility", "visible");
			e.preventDefault;
		});

		// Add Listing Form Page
        /*
        var $listingFormWrapper = $('.listing-form-wrapper');
        var $ListingFormSteps = $('.listing-form-steps', $listingFormWrapper);
        var $ListingProgressBar = $(".listing-form-progress .progress-bar", $listingFormWrapper);
        var $ListingFormStepsLi = $(".listing-form-steps li", $listingFormWrapper);

        $ListingFormStepsLi.on("click", function () {
            var $This = $(this);
            $This.removeClass("completed");
            $ListingFormStepsLi.removeClass("active");
            $This.addClass("active");
            $This.prevAll().addClass("completed");
		});
        $('li:nth-child(1)', $ListingFormSteps).on("click", function () {
			$ListingProgressBar.attr("data-appear-progress-animation","0%").width("0%");
		});
		$('li:nth-child(2)', $ListingFormSteps).on("click", function () {
			$ListingProgressBar.attr("data-appear-progress-animation","25%").width("25%");
		});
		$('li:nth-child(3)', $ListingFormSteps).on("click", function () {
			$ListingProgressBar.attr("data-appear-progress-animation","50%").width("50%");
		});
		$('li:nth-child(4)', $ListingFormSteps).on("click", function () {
			$ListingProgressBar.attr("data-appear-progress-animation","75%").width("75%");
		});
		$('li:nth-child(5)', $ListingFormSteps).on("click", function () {
			$ListingProgressBar.attr("data-appear-progress-animation","100%").width("100%");
		});
        */
        var $Registeredv = $('.listing-add-form .registeredv');
        $Registeredv.on("click", function () {
			$(".registration-details").slideDown();
		});
        var $NoRegisteredv = $('.listing-add-form .noregisteredv');
        $NoRegisteredv.on("click", function () {
			$(".registration-details").slideUp();
		});

		// Listing Page
        var $ToggleMake = $(".toggle-make a");
        $ToggleMake.on("click", function () {
			$(".by-type-options").slideToggle();
			return false;
		});

        /*var $SearchTrigger = $(".search-trigger");
        $SearchTrigger.on("click", function () {
			$(".search-form").slideToggle();
			return false;
		});*/

		var GridView;
		function GridViewfunction () {
			var GridView = setTimeout(function () {
                var $ResultsHolder = $("#results-holder");
                var $ResultsListView = $("#results-list-view");
                var $ResultsGridView = $("#results-grid-view");
					$ResultsHolder.removeClass("results-list-view");
					$ResultsHolder.addClass("results-grid-view");
					$ResultsListView.removeClass("active");
					$ResultsGridView.addClass("active");
					YOUBOAT.RESULTS();
					$(".waiting").hide();
					$('body,html').animate({ scrollTop: "212" }, 750, 'easeOutExpo' );
			},800);
		}
		function GridViewStopfunction () {
			clearTimeout(GridView);
		}

		var ListView;
		function ListViewfunction () {
			var ListView = setTimeout(function () {
                var $ResultsHolder = $("#results-holder");
                var $ResultsListView = $("#results-list-view");
                var $ResultsGridView = $("#results-grid-view");
                $ResultsHolder.removeClass("results-grid-view");
				$ResultsHolder.addClass("results-list-view");
				$ResultsGridView.removeClass("active");
				$ResultsListView.addClass("active");
				$ResultsHolder.find(".result-item").css("height","auto");
				$(".waiting").hide();
				$('body,html').animate({ scrollTop: "212" }, 750, 'easeOutExpo' );
			},800);
		}

		function ListViewStopfunction () {
			clearTimeout(ListView);
		}

        var $ResultsGridView = $("#results-grid-view");
        $ResultsGridView.on("click", function () {
			$(".waiting").fadeIn();
			GridViewfunction ();
			GridViewStopfunction ();
			return false;
		});

        var $ResultsListView = $("#results-list-view");
        $ResultsListView.on("click", function () {
			$(".waiting").fadeIn();
			ListViewfunction ();
			ListViewStopfunction ();
			return false;
		});

        var $ResultsHolder = $("#results-holder");
        if($ResultsHolder.hasClass("results-grid-view")) {
			YOUBOAT.RESULTS();
		}

        var $AdsRecentsHolder = $(".recent-vehicles");
        if($AdsRecentsHolder.length>0) {
            YOUBOAT.ADSRECENT();
        }
		//* Advanced Search Trigger
        var $SearchAdvancedTrigger = $('.search-advanced-trigger');
        $SearchAdvancedTrigger.on("click", function (event) {
            event.preventDefault();
            var $This = $(this);
			if ($This.hasClass('basic')) {
                $('#btn_basic_search').hide();
                //$This.removeClass('basic btn-primary');
                //$This.addClass('advanced btn-default');
                $This.removeClass('basic');
                $This.addClass('advanced');
                $(".advanced-search-row").slideDown();
				$This.html($This.attr('data-basic_search') + ' <i class="fa fa-arrow-up"></i>');
			} else {
                $('#btn_basic_search').show();
                //$This.removeClass('advanced btn-default');
                //$This.addClass('basic btn-primary');
				$This.removeClass('advanced');
                $This.addClass('basic');
				$(".advanced-search-row").slideUp();
				$This.html($This.attr('data-advanced_search') + ' <i class="fa fa-arrow-down"></i>');
			}
            //return false;
		});

        var $ShowFilters = $("#Show-Filters");
        $ShowFilters.on("click", function () {
			$("#Search-Filters").slideToggle();
            $("#Search-Filters .filters-sidebar").css({'width':'100%'});
		});

		// Tabs deep linking
		$('a[data-toggle="tab"]').on("click", function (e) {
			e.preventDefault();
			$('a[href="' + $(this).attr('href') + '"]').tab('show');
		});

		// Vehicle Details Clone
		$( ".badge-premium-listing" ).clone().appendTo( ".single-listing-actions" );
	});

	// FITVIDS

    if ('function' === typeof $.fn.fitVids) {
        $(".fw-video, .format-video .post-media").fitVids();
    }

	$(window).load(function () {
		$(".format-image").each(function () {
			$(this).find(".media-box").append("<span class='zoom'><span class='icon'><i class='icon-expand'></i></span></span>");
		});
		$(".format-standard").each(function () {
			$(this).find(".media-box").append("<span class='zoom'><span class='icon'><i class='icon-plus'></i></span></span>");
		});
		$(".format-video").each(function () {
			$(this).find(".media-box").append("<span class='zoom'><span class='icon'><i class='icon-music-play'></i></span></span>");
		});
		$(".format-link").each(function () {
			$(this).find(".media-box").append("<span class='zoom'><span class='icon'><i class='fa fa-link'></i></span></span>");
		});
		$(".additional-images .owl-carousel .item-video").each(function () {
			$(this).append("<span class='icon'><i class='fa fa-play'></i></span>");
		});
		YOUBOAT.StickyHeader();
	});

	// Icon Append
	$('.basic-link').append(' <i class="fa fa-angle-right"></i>');
	$('.basic-link.backward').prepend(' <i class="fa fa-angle-left"></i> ');
	$('ul.checks li, .add-features-list li').prepend('<i class="fa fa-check"></i> ');
	$('ul.angles li, .widget_categories ul li a, .widget_archive ul li a, .widget_recent_entries ul li a, .widget_recent_comments ul li a, .widget_links ul li a, .widget_meta ul li a').prepend('<i class="fa fa-angle-right"></i> ');
	$('ul.chevrons li').prepend('<i class="fa fa-chevron-right"></i> ');
	$('ul.carets li, ul.inline li, .filter-options-list li').prepend('<i class="fa fa-caret-right"></i> ');
	$('a.external').prepend('<i class="fa fa-external-link"></i> ');

	// Animation Appear
	var AppDel;
	function AppDelfunction ($appd) {
		$appd.addClass("appear-animation");
		if(!$("html").hasClass("no-csstransitions") && $(window).width() > 767) {
			$appd.appear(function () {
				var delay = ($appd.attr("data-appear-animation-delay") ? $appd.attr("data-appear-animation-delay") : 1);
				if(delay > 1) $appd.css("animation-delay", delay + "ms");
				$appd.addClass($appd.attr("data-appear-animation"));
				setTimeout(function () {
					$appd.addClass("appear-animation-visible");
				}, delay);
				clearTimeout();
			}, {accX: 0, accY: -150});
		} else {
			$appd.addClass("appear-animation-visible");
		}
	}
	function AppDelStopfunction () {
		clearTimeout(AppDel);
	}
	$("[data-appear-animation]").each(function () {
		var $this = $(this);
		AppDelfunction ($this);
		AppDelStopfunction ();
	});
	// Animation Progress Bars

	var AppAni;
	function AppAnifunction ($anim) {
		$anim.appear(function () {
			var delay = ($anim.attr("data-appear-animation-delay") ? $anim.attr("data-appear-animation-delay") : 1);
			if(delay > 1) $anim.css("animation-delay", delay + "ms");
			$anim.addClass($anim.attr("data-appear-animation"));
			setTimeout(function () {
				$anim.animate({
					width: $anim.attr("data-appear-progress-animation")
				}, 1500, "easeOutQuad", function () {
					$anim.find(".progress-bar-tooltip").animate({
						opacity: 1
					}, 500, "easeOutQuad");
				});
			}, delay);
			clearTimeout();
		}, {accX: 0, accY: -50});
	}
	function AppAniStopfunction () {
		clearTimeout(AppAni);
	}
	$("[data-appear-progress-animation]").each(function () {
		var $this = $(this);
		AppAnifunction ($this);
		AppAniStopfunction ();
	});

	// Parallax Jquery Callings
	if ('function' === typeof $.fn.parallax) {
        if(!Modernizr.touch) {
            $(window).on('load', function () {
                parallaxInit();
            });
        }
        function parallaxInit() {
            $('.parallax1').parallax("50%", 0.1);
            $('.parallax2').parallax("50%", 0.1);
            $('.parallax3').parallax("50%", 0.1);
            $('.parallax4').parallax("50%", 0.1);
            $('.parallax5').parallax("50%", 0.1);
            $('.parallax6').parallax("50%", 0.1);
            $('.parallax7').parallax("50%", 0.1);
            $('.parallax8').parallax("50%", 0.1);
            /*add as necessary*/
        }
    }

	// Window height/Width Getter Classes
	var wheighter = $(window).height();
	var wwidth = $(window).width();
	$(".wheighter").css("height",wheighter);
	$(".wwidth").css("width",wwidth);
	$(window).resize(function () {
		var wheighter = $(window).height();
		var wwidth = $(window).width();
		$(".wheighter").css("height",wheighter);
		$(".wwidth").css("width",wwidth);
	});
});