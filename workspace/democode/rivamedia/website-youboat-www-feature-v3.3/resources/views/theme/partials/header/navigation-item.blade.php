@if (count($items) > 1)
<li>
    <a href="#" class="void">{!! $item_title !!}</a>
    <ul class="dropdown">
    @foreach($items as $item)
    <?php
        $title  = isset($item['title']) ? htmlspecialchars_decode(ucfirst(mb_strtolower(trans('navigation.'.  $item['title'])))) : '__TITLE__';
        $url    = isset($item['url']) ? url(trans_route($currentLocale, 'routes.' . $item['url'])) : '';
        $new    = isset($item['new']) ? '<span class="label label-danger">New</span>' : '';
    ?>
        @if (isset($url))
        <li {!! ($item['url'] == $currentRoute) ? 'class="active"' : '' !!}>
            <a {!! !empty($url) ? 'href="' . $url.'"' : '' !!} title="{!! strip_tags($title) !!}">
                {!! $title !!}
                {!! $new !!}
            </a>
        </li>
        @endif
    @endforeach
    </ul>
</li>
@else

<?php
    $title  = isset($items[0]['title']) ? htmlspecialchars_decode(ucwords(mb_strtolower($items[0]['title']))) : '__TITLE__';
    // $url    = isset($items[0]['url']) ? url(trans_route($currentLocale, 'routes.' . $items[0]['url'])) : '';
    $url    = isset($items[0]['url']) ? $items[0]['url'] : '';
    $new    = isset($items[0]['new']) ? '<span class="label label-danger">New</span>' : '';
?>
<a {!! !empty($url) ? 'href="' . $url.'"' : '' !!} title="{!! strip_tags($title) !!}">
    {!! $title !!}
    {!! $new !!}
</a>
@endif
