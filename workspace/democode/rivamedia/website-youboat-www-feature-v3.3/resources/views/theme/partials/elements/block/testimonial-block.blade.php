<div class="testimonial-block">
    <blockquote>
        <p>{!! $testimonial !!}</p>
    </blockquote>
    <div class="testimonial-avatar">{!! isset($testimonial_avatar_img_src) ? image($testimonial_avatar_img_src, isset($testimonial_author) ? $testimonial_author : '', ['width'=>'60','height'=>'60']) : '' !!}</div>
    <div class="testimonial-info">
        <div class="testimonial-info-in">
            {!! isset($testimonial_author) ? '<strong>' . $testimonial_author . '</strong>' : '' !!}
            {!! isset($testimonial_company) ? '<span>' . $testimonial_company . '</span>' : '' !!}
        </div>
    </div>
</div>