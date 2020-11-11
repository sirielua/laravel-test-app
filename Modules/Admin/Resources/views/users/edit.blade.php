@extends('admin::_layouts.master')

@section('page-title', 'Edit User')
@section('title-subheading', '')

@section('breadcrumbs')
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin::home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin::users.index') }}">Users</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin::users.show', $model->id) }}">{{ $model->name }}</a></li>
            <li class="active breadcrumb-item" aria-current="page">Edit</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body"><h5 class="card-title">Edit form</h5>
            @include('admin::users._form', ['route' => route('admin::users.update', $model), 'method' => 'PUT'])
        </div>
    </div>
@endsection
