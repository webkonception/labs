<!-- Start Hero Slider -->
<div class="hero-slider heroflex flexslider clearfix" data-autoplay="yes" data-pagination="no" data-arrows="yes" data-style="fade" data-speed="7000" data-pause="yes">
    <ul class="slides">
    @for ($i = 0; $i < 5; $i++)
        <li class="parallax" style="background-image:url({!! asset('assets/vendor/youboat/img/boats/' . $i . '-min.jpg') !!});"></li>
    @endfor
    </ul>
</div>
<!-- End Hero Slider -->
