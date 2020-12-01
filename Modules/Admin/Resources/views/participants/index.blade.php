@extends('admin::_layouts.master')

@section('page-title', 'Participants')
@section('title-subheading', '')

@section('breadcrumbs')
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin::home') }}">Home</a></li>
            <li class="active breadcrumb-item" aria-current="page">Participants</li>
        </ol>
    </nav>
@endsection

@section('content')
    @include('admin::participants._grid', ['dataTable' => $dataTable])
@endsection
