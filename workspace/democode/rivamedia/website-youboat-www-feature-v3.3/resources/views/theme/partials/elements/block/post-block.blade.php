<div class="post-block format-standard">
    <a href="{!! url($item['rewrite_url']) !!}" class="media-box post-image {!! preg_match("/^(http|https):\/\//i", $item['rewrite_url']) ? 'blank' : '' !!} text-center" title="{!! $item['title'] !!}">
        {!! image(thumbnail(asset($item['photo']), 337, 228, false, false, true, 100), $item['title'], ['class'=>'img-responsive img-thumbnail inline']) !!}
    </a>
    <div class="post-actions">
        <div class="post-date">{!! $item['date'] !!}</div>
        {{--<div class="comment-count"><i class="icon-dialogue-text"></i> {!! $item['news_comments_count'] !!}</div>--}}
    </div>
    <h3 class="post-title"><a href="{!! url($item['rewrite_url']) !!}" {!! preg_match("/^(http|https):\/\//i", $item['rewrite_url']) ? 'class="blank"' : '' !!} title="{!! $item['title'] !!}">{!! $item['title'] !!}</a></h3>
    <div class="post-content">
        <p>{!! strlen($item['intro']) > 250 ? rtrim(mb_strimwidth($item['intro'], 0, 247)) . "..." : $item['intro'] !!}</p>
    </div>
    <div class="post-meta">
        {!! trans('elements.latest-news.posted_in') !!}: <strong>{!! $item['category'] !!}</strong>
    </div>
</div>
