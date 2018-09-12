<!-- Latest Reviews -->
<section class="listing-block latest-reviews">
    <div class="listing-header">
        <h3>{!! trans('elements.latest-reviews.title') !!}</h3>
    </div>
    <div class="listing-container">
        @include('theme.partials.elements.block.post-review-block', [
            'review_rate'=>'3.6',
            'review_max_rate'=>5,
            'review_title'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
            'review_url'=>'#',
            'review_post_date'=>'November 29, 2014',
            'review_vote_positive'=>3,
            'review_vote_negative'=>0,
        ])
        @include('theme.partials.elements.block.post-review-block', [
            'review_rate'=>'4.1',
            'review_max_rate'=>5,
            'review_title'=>'Curabitur nec nulla lectus, non hendrerit lorem porttitor',
            'review_url'=>'#',
            'review_post_date'=>'November 14, 2014',
            'review_vote_positive'=>7,
            'review_vote_negative'=>1,
        ])
        @include('theme.partials.elements.block.post-review-block', [
            'review_rate'=>'5.0',
            'review_max_rate'=>5,
            'review_title'=>'2014 Proin enim quam, vulputate at lobortis quis',
            'review_url'=>'#',
            'review_post_date'=>'October 31, 2014',
            'review_vote_positive'=>11,
            'review_vote_negative'=>0,
        ])
    </div>
</section>