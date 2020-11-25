<div class="position-relative row form-group">
    <label for="{{ $attribute }}" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <input name="{{ $attribute }}" id="{{ $attribute }}" placeholder="{{ $label }}" type="password" class="form-control @error($attribute) is-invalid @enderror" />
        @error($attribute)
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>
</div>

<div class="position-relative row form-group">
    <label for="confirm-{{ $attribute }}" class="col-sm-2 col-form-label">{{ $confirmedLabel }}</label>
    <div class="col-sm-10">
        <input name="{{ $attribute }}_confirmation" id="confirm-{{ $attribute }}" placeholder="{{ $confirmedLabel }}" type="password" class="form-control @error($attribute) is-invalid @enderror" />
    </div>
</div>