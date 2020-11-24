@extends('admin::_layouts.master')

@section('page-title', 'List Contest Templates')
@section('title-subheading', '')

@section('breadcrumbs')
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin::home') }}">Home</a></li>
            <li class="active breadcrumb-item" aria-current="page">Contest Templates</li>
        </ol>
    </nav>
@endsection

@section('content')
    @include('admin::contest-templates._grid', ['dataTable' => $dataTable])
@endsection
