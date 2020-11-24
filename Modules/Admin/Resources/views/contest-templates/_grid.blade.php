@section('grid-controls')
    <div class="btn-actions-pane-right">
        <div class="dropdown dropleft d-inline-block">
            <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="mb-2 mr-2 dropdown-toggle btn btn-focus">Selected</button>
            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
                <button class="dropdown-item btn btn-transition btn-outline-success"
                    onclick="if(!confirm('Are you sure?')) return false; $(this).closest('.dataTableUpdate').find('input[name=action]').val('activate');">
                    Activate
                </button>
                <button class="dropdown-item btn btn-transition btn-outline-secondary"
                    onclick="if(!confirm('Are you sure?')) return false; $(this).closest('.dataTableUpdate').find('input[name=action]').val('deactivate');">
                    Deactivate
                </button>
                <button class="dropdown-item btn btn-transition btn-outline-danger"
                    onclick="if(!confirm('Are you sure?')) return false; $(this).closest('.dataTableUpdate').find('input[name=action]').val('delete');">
                    Delete
                </button>
            </div>
        </div>
        <a href="{{ route('admin::contest-templates.create') }}" type="button" class="mb-2 mr-2 btn btn-success"><i class="pe-7s-paint"></i> Create</a>
    </div>
@endsection

<div class="row">
    <div class="col lg-12">
        <form action="{{ route('admin::contest-templates.batch-update') }}" method="POST" class="dataTableUpdate">
            <input type="hidden" name="action" value="" />
            @method('PATCH')
            @csrf

            <div class="main-card mb-3 card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contest Templates</h5>
                    @yield('grid-controls')
                </div>
                <div class="card-body">
                    {{ $dataTable->table($attributes = [], $drawFooter = false, $drawSearch = true) }}
                </div>
                <div class="card-footer">
                    <h5 class="card-title">Contest Templates</h5>
                    @yield('grid-controls')
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
