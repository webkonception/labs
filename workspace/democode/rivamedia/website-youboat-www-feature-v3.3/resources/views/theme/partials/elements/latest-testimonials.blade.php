<?php
    $items[] = [
            'testimonial'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus. Donec facilisis fermentum sem, ac viverra ante luctus vel. Donec vel mauris quam.',
            'testimonial_avatar_img_src'=>'https://placehold.it/100x100&amp;text=Arthur Henry',
            'testimonial_author'=>'Arthur Henry',
            'testimonial_company'=>'Carsales Inc',
    ];
    $items[] = [
            'testimonial'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus. Donec facilisis fermentum sem, ac viverra ante luctus vel. Donec vel mauris quam.',
            'testimonial_avatar_img_src'=>'https://placehold.it/100x100&amp;text=Lori Bailey',
            'testimonial_author'=>'Lori Bailey',
            'testimonial_company'=>'My yacht Experts',
    ];
    $items[] = [
            'testimonial'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus. Donec facilisis fermentum sem, ac viverra ante luctus vel. Donec vel mauris quam.',
            'testimonial_avatar_img_src'=>'https://placehold.it/100x100&amp;text=Willie & Heather Obrien',
            'testimonial_author'=>'Willie &amp; Heather Obrien',
            'testimonial_company'=>'',
    ];
    $items[] = [
            'testimonial'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus. Donec facilisis fermentum sem, ac viverra ante luctus vel. Donec vel mauris quam.',
            'testimonial_avatar_img_src'=>'https://placehold.it/100x100&amp;text=Arthur Henry',
            'testimonial_author'=>'Arthur Henry',
            'testimonial_company'=>'Carsales Inc',
    ];
    $items[] = [
            'testimonial'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus. Donec facilisis fermentum sem, ac viverra ante luctus vel. Donec vel mauris quam.',
            'testimonial_avatar_img_src'=>'https://placehold.it/100x100&amp;text=Lori Bailey',
            'testimonial_author'=>'Lori Bailey',
            'testimonial_company'=>'My yacht Experts',
    ];
    $items[] = [
            'testimonial'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus. Donec facilisis fermentum sem, ac viverra ante luctus vel. Donec vel mauris quam.',
            'testimonial_avatar_img_src'=>'https://placehold.it/100x100&amp;text=Willie & Heather Obrien',
            'testimonial_author'=>'Willie &amp; Heather Obrien',
            'testimonial_company'=>'',
    ];
    unset($items);
?>
@if (isset($items) && count($items) > 0)
    <section class="listing-block latest-testimonials">
        <div class="listing-header">
            <h3>{!! trans('elements.latest-testimonials.title') !!}</h3>
        </div>
        <div class="listing-container">
            <div class="carousel-wrapper">
                <div class="row">
                    <ul class="owl-carousel carousel-fw" id="testimonials-slider" data-columns="{!! isset($data_columns) ? $data_columns : 2 !!}" data-autoplay="5000" data-pagination="{!! isset($data_columns) && count($items) > $data_columns ? 'yes' : 'no' !!}" data-arrows="no" data-single-item="no" data-items-desktop="2" data-items-desktop-small="1" data-items-tablet="1" data-items-mobile="1">
                        @foreach ($items as $item)
                        <li class="item">
                            @include('theme.partials.elements.block.testimonial-block', $item)
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endif
