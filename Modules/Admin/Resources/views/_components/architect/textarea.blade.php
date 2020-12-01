<div class="position-relative row form-group">
    <label for="description" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <textarea name="{{ $attribute }}" id="{{ $attribute }}" class="form-control">{{ old($attribute, $model->$attribute ?? '') }}</textarea>
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