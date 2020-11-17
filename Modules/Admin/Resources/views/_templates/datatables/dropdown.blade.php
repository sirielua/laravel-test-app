
<div class="dropdown d-inline-block">
    @php
        $dropdownClass = 'mb-2 mr-2 dropdown-toggle btn btn-sm ' . ($options['style'] == 'outline' ? 'btn-outline-' : 'btn-') . $options['tag']
    @endphp

    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="{{ $dropdownClass }}">{{ $options['label'] }}</button>

    {{--<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
        <a href="#" type="button" tabindex="0" class="dataTableControl dropdown-item btn btn-transition" data-method="PATCH">Activate</a>
        <a href="#" type="button" tabindex="0" class="dataTableControl dropdown-item btn btn-transition btn-outline-danger" data-method="PATCH">Deactivate</a>
    </div>--}}

    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
        @foreach ($options['options'] as $option)
            @php
                $optionClass = ($option['verb'] != 'get' ? 'dataTableControl ' : '') . 'dropdown-item btn btn-transition' . ($option['tag'] ? ' btn-outline-' . $option['tag'] : '')
            @endphp

            <a href="{{ $routes[$option['route']] }}" type="button" tabindex="0" class="{{ $optionClass }}" data-method="{{ $option['verb'] }}">{{ $option['label'] }}</a>
        @endforeach
    </div>
</div>
