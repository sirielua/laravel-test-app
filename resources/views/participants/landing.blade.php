@extends('layouts.app')

@section('page-title', 'Verify!')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                @if ($contest->banner)<img class="card-img-top" src="{{ asset($contest->banner) }}" alt="{{ $contest->headline ?? null }}">@endif
                <div class="card-header">Step 1 of 3</div>

                <div class="card-body">
                    @if ($contest->headline)<h1 class="card-title">{{ $contest->headline }}</h1>@endif
                    @if ($contest->subheadline)<h5 class="card-title">{{ $contest->subheadline }}</h5>@endif

                    @include('participants.landing._form', ['route' => route('participants.register'), 'method' => 'POST', 'data' => $data ?? []])
                    <hr />
                    @if ($contest->explaining_text)<p class="card-text">{{ $contest->explaining_text }}</p>@endif
                </div>

                <div class="card-body" style="display: none;">
                    <x-registration-data-vars/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
