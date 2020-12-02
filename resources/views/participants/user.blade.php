@extends('layouts.app')

@section('page-title', 'Hello!')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                @if ($contest->banner)<img class="card-img-top" src="{{ asset($contest->banner) }}" alt="{{ $contest->headline ?? null }}">@endif
                <div class="card-header">Hello {{ $participant->first_name }}</div>

                <div class="card-body">
                    <h1 class="card-title">Increase your chance of winning!</h1>
                    <h5 class="card-title">So far you have won <strong>{{ (1 + $participant->referral_quantity) }} Ticket(s)</strong> to the T-shirt Sweepstake. Invite friends and get one more ticket for every friend who joins the sweepstake too!</h5>

                    <h5 class="card-title">Use the buttons below to share directly on social media:</h5>
                    <p class="card-text">
                        <a href="#" class="btn btn-dark">Facebook</a>
                        <a href="#" class="btn btn-success">What'sUp</a>
                        <a href="#" class="btn btn-info">Twitter</a>
                        <a href="#" class="btn btn-primary">Telegramm</a>
                    </p>

                    <h5 class="card-title">Or copy the link below and share it wherever you like!</h5>

                    @include('participants.share._copy_referral_link', ['url' => route('participants.referral', $participant->id)])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
