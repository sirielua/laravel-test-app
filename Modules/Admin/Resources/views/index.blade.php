@extends('admin::_layouts.master')

@section('page-title', 'Hello World!')
@section('title-subheading', 'This is an example index page')

@section('content')
    <h1>Hello World!!</h1>

    <p>
        This view is loaded from module: {!! config('admin.name') !!}
    </p>
    
    @include('admin::users._grid', ['dataTable' => $dataTable])
@endsection
