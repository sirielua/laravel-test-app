<div class="position-relative row form-group">
    <label for="name" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <input name="{{ $attribute }}" value="{{ old($attribute, $model->$attribute ?? '') }}"  id="{{ $attribute }}" placeholder="{{ $label }}" type="text" class="form-control @error($attribute) is-invalid @enderror" />
        @if ($hint)
            <small class="form-text text-muted">{{ $hint }}</small>
        @endif
        @error($attribute)
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>
</div>