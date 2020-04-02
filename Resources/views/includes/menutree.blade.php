@foreach($items as $item)
    <div class="nav-item list-group-item {{ $item['class'] }} @if($item['active'])active @endif">
        {!! $item['link'] !!}
        @if($item['hasChildren'])
            <i class="float-right fa fa-caret-down @if($item['hasActiveChild'] or $item['active']) rotated @endif" data-toggle="collapse" data-target="#menugroup-{{ $item['item']->id }}"></i>
        @endif
    </div>
    @if($item['hasChildren'])
        <div class="list-group collapse @if($item['hasActiveChild'] or $item['active']) show @endif" id="menugroup-{{ $item['item']->id }}">
            @include('menu@includes.menutree', ['items' => $item['children']])
        </div>
    @endif
@endforeach
