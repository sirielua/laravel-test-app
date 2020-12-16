@extends('layouts.app')

@section('page-title', 'Verify!')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                @if ($contest->banner)<img class="card-img-top" src="{{ asset($contest->banner) }}" alt="{{ $contest->headline ?? null }}">@endif
                <div class="card-header">Step 2 of 3</div>

                <div class="card-body">
                    <h1 class="card-title">An SMS with a confirmation code has been sent to your phone number</h1>
                    <h5 class="card-title">Please enter the code from SMS below:</h5>

                    @include('participants.verify-code._form', ['route' => route('participants.confirm-code'), 'method' => 'PATCH'])

                    <hr />

                    <div class="text-center">
                        <a href="{{ route('participants.resend-verification') }}" class="spoofed btn btn-primary" data-confirm-msg="Are you sure?" data-ajax="false" data-method="patch" data-csrf="{{ csrf_token() }}">
                            Resend SMS
                        </a>
                        <a href="{{ route('participants.edit-number') }}" class="spoofed btn btn-primary" data-confirm-msg="Are you sure?" data-ajax="false" data-method="patch" data-csrf="{{ csrf_token() }}">
                            Edit Number
                        </a>
                    </div>
                </div>

                <div class="card-body" style="display: none;">
                    <x-registration-data-vars/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
