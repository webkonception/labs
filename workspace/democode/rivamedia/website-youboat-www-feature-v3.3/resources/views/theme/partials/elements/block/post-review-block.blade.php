<div class="post-block post-review-block">
    <div class="review-status">
        <strong>{!! $review_rate !!}</strong>
        <span>{!! trans('elements.latest-reviews.out_of') !!} {!! $review_max_rate !!}</span>
    </div>
    <h3 class="post-title"><a href="{!! url($review_url) !!}" title="{!! $review_title !!}">{!! $review_title !!}</a></h3>
    <div class="post-content">
        <div class="post-actions">
            <div class="post-date">{!! $review_post_date !!}</div>
            <div class="comment-count"><i class="fa fa-thumbs-o-up"></i> {!! $review_vote_positive !!} <i class="fa fa-thumbs-o-down"></i> {!! $review_vote_negtive !!}</div>
        </div>
    </div>
</div>
