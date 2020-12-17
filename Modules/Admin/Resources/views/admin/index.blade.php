@extends('admin::_layouts.master')

@section('page-title', env('APP_NAME'))
@section('title-subheading', 'Administration panel')

@section('content')
    <x-admin::dashboard-quik-stats />

    @include('admin::users._grid', ['dataTable' => $dataTable])
@endsection
