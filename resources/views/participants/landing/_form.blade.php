<form method="POST" action="{{ $route }}">
    @method($method)
    @csrf

    {{--
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-1">
                @foreach ($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    --}}

    <div class="form-group">
        <label for="firstNameInput">First Name *</label>
        <input type="text" name="first_name" value="{{ old('first_name', $data['first_name'] ?? '') }}" class="form-control @error('first_name') is-invalid @enderror" id="firstNameInput">
        @error('first_name')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="lastNameInput">Last Name *</label>
        <input type="text" name="last_name" value="{{ old('last_name', $data['last_name'] ?? '') }}" class="form-control @error('last_name') is-invalid @enderror" id="lastNameInput">
        @error('last_name')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="phoneInput">Phone *</label>
        <input type="text" name="phone" value="{{ old('phone', $data['phone'] ?? '') }}" class="form-control @error('phone') is-invalid @enderror" id="phoneInput">
        @error('phone')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="referralInput">Referral ID</label>
        <input type="text" name="referral_id" value="{{ $data['referral_id'] ?? '' }}" class="form-control" id="referralInput" readonly>
    </div>

    <div class="form-check">
        <input type="checkbox" name="accept_terms" value="1" {{ old('accept_terms') ? 'checked' : '' }} class="form-check-input @error('accept_terms') is-invalid @enderror" id="termsCheckbox">
        <label class="form-check-label" for="termsCheckbox">I Accept Terms & Conditions</label>
        @error('accept_terms')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <div class="form-check">
        <input type="checkbox" name="confirm_age" value="1" {{ old('confirm_age') ? 'checked' : '' }} class="form-check-input @error('confirm_age') is-invalid @enderror" id="ageCheckbox">
        <label class="form-check-label" for="ageCheckbox">I'm over 18 years old</label>
        @error('confirm_age')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Join Now</button>
    </div>
</form>
