@if(isset($routes['show']))
    <a class="btn btn-xs btn-outline-primary" href="{{ $routes['show'] }}"><i class="pe-7s-note2"></i> {{ __('View') }}</a>
@endif

@if(isset($routes['copy']))
    <a class="btn btn-xs btn-outline-alternate" href="{{ $routes['copy'] }}"><i class="pe-7s-copy-file"></i> {{ __('Copy') }}</a>
@endif

@if(isset($routes['edit']))
    <a class="btn btn-xs btn-outline-alternate" href="{{ $routes['edit'] }}"><i class="pe-7s-pen"></i> {{ __('Edit') }}</a>
@endif

@if(isset($routes['destroy']))
    @php
        $uniqid = uniqid('delete-form-')
    @endphp

    <a class="dataTableControl btn btn-xs btn-outline-danger" href="{{ $routes['destroy'] }}" 
       data-confirm-msg="Are you sure?" data-method="delete" data-csrf="{{ csrf_token() }}">
        <i class="pe-7s-pen"></i> {{ __('Delete') }}
    </a>
@endif
