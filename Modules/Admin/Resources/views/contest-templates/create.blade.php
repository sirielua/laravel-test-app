@extends('admin::_layouts.master')

@section('page-title', 'Create Contest Template')
@section('title-subheading', '')

@section('breadcrumbs')
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin::home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin::contest-templates.index') }}">Contest Templates</a></li>
            <li class="active breadcrumb-item" aria-current="page">Create</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body"><h5 class="card-title">Create form</h5>
            @include('admin::contest-templates._form', ['route' => route('admin::contest-templates.store'), 'method' => 'POST'])
        </div>
    </div>
@endsection
