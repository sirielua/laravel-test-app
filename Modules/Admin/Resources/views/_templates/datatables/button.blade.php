@php
    $buttonClass = $verb != 'get' ? 'dataTableControl ' : ''
@endphp

@switch($style)
    @case('button')
        @php $buttonClass .= 'mb-2 mr-2 btn btn-' @endphp
        @break

    @case('outline')
        @php $buttonClass .= 'mb-2 mr-2 btn-transition btn btn-outline-' @endphp
        @break

    @case('no-border')
        @php $buttonClass .= 'mb-2 mr-2 border-0 btn-transition btn btn-outline-' @endphp
        @break

    @case('badge')
        @php $buttonClass .= 'mb-2 mr-2 badge badge-' @endphp
        @break

    @case('pill')
        @php $buttonClass .= 'mb-2 mr-2 badge badge-pill badge-' @endphp
        @break
@endswitch

@php
    $buttonClass .= $tag
@endphp

<a href="{{ $route }}" class="{{ $buttonClass }}" data-method="{{ $verb }}">{{ __($label) }}</a>
