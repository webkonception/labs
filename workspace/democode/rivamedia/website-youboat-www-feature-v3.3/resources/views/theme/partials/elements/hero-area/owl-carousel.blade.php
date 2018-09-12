<!-- Start Hero Carousel -->
<ul class="owl-carousel carousel-alt" data-columns="1" data-autoplay="" data-pagination="no" data-arrows="yes" data-single-item="no" data-items-desktop="1" data-items-desktop-small="1" data-items-mobile="1" data-items-tablet="1">
    @for ($i = 0; $i < 5; $i++)
    <li class="item">{{ image(asset('/assets/vendor/youboat/img/boats/' . $i . '-min.jpg'), '', []) }}</li>
    @endfor
</ul>
<!-- End Hero Carousel -->
