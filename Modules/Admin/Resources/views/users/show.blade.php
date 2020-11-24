@extends('admin::_layouts.master')

@section('page-title', 'User Profile')
@section('title-subheading', '')

@section('breadcrumbs')
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin::home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin::users.index') }}">Users</a></li>
            <li class="active breadcrumb-item">{{ $model->name }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-card mb-3 card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ $model->name }}</h5>
            <div class="btn-actions-pane-right">
                <a class="btn btn-xs btn-outline-alternate" href="{{ route('admin::users.edit', $model->id) }}"><i class="pe-7s-pen"></i> Edit</a>
                <a class="spoofed btn btn-xs btn-outline-danger" href="{{ route('admin::users.destroy', $model->id) }}"
                   data-confirm-msg="Are you sure?" data-ajax="false" data-method="delete" data-csrf="{{ csrf_token() }}">
                    <i class="pe-7s-pen"></i> Delete
                </a>
            </div>
        </div>

        <div class="card-body">

            <ul class="list-group">
                <li class="list-group-item">
                    <h5 class="list-group-item-heading">Name</h5>
                    <p class="list-group-item-text">{{ $model->name }}</p>
                </li>
                <li class="list-group-item">
                    <h5 class="list-group-item-heading">Email</h5>
                    <p class="list-group-item-text">{{ $model->email }}</p>
                </li>
                <li class="list-group-item">
                    <h5 class="list-group-item-heading">Photo</h5>
                    @if ($model->photo)
                        <img src = "{{ asset($model->photo) }}" style="max-width: 600px;" />
                    @else
                        <p class="list-group-item-text">No photo</p>
                    @endif
                </li>
                <li class="list-group-item">
                    <h5 class="list-group-item-heading">Is Active</h5>
                    <p class="list-group-item-text">
                        @if ($model->is_active)
                            <div class="mb-2 mr-2 badge badge-success">Active</div>
                        @else
                            <div class="mb-2 mr-2 badge badge-secondary">Inactive</div>
                        @endif
                    </p>
                </li>
                <li class="list-group-item">
                    <h5 class="list-group-item-heading">Created At</h5>
                    <p class="list-group-item-text">{{ $model->created_at->format('d.m.Y H:i P') }}</p>
                </li>
                <li class="list-group-item">
                    <h5 class="list-group-item-heading">Updated At</h5>
                    <p class="list-group-item-text">{{ $model->created_at->format('d.m.Y H:i P') }}</p>
                </li>
            </ul>
        </div>
    </div>
@endsection
