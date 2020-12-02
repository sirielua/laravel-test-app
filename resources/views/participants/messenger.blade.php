@extends('layouts.app')

@section('page-title', 'Final Step!')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                @if ($contest->banner)<img class="card-img-top" src="{{ asset($contest->banner) }}" alt="{{ $contest->headline ?? null }}">@endif
                <div class="card-header">Final Step</div>

                <div class="card-body">
                    <h1 class="card-title">Important!</h1>

                    <p class="card-text">
                        Last Step! Connect with our Facebook messenger to receive the winners announcement
                    </p>

                    <button class="btn btn-success">Connect and be informed via Facebook Messenger</button>
                </div>

                <div class="card-body" style="display: none;">
                    <x-registration-data-vars/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
