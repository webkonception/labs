
    <section class="listing-block latest-news">
        <div class="listing-header clearfix">
            <h3 class="pull-left">{!! trans('elements.latest-news.title') !!}</h3>
            {!! link_trans_route('news_create', 'navigation.news_create', ['class'=>'btn btn-info pull-right']) !!}
        </div>
        @if (isset($items) && count($items) > 0)
        <div class="listing-container">
            <div class="carousel-wrapper">
                <div class="row">
                    <ul class="owl-carousel" id="news-slider" data-columns="{!! isset($data_columns) ? $data_columns : 2 !!}" data-autoplay="" data-pagination="yes" data-arrows="yes" data-single-item="no" data-items-desktop="2" data-items-desktop-small="1" data-items-tablet="2" data-items-mobile="1">
                        @foreach ($items as $item)
                        <li class="item">
                            @include('theme.partials.elements.block.post-block', $item)
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </section>
