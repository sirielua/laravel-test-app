<div class="position-relative row form-group">
    <label for="{{ $attribute }}_file" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <input name="{{ $attribute }}_file" id="{{ $attribute }}_file" type="file" class="form-control-file @error($attribute.'_file') is-invalid @enderror" />
        @if ($hint)
            <small class="form-text text-muted">{{ $hint }}</small>
        @endif
        <input name="{{ $attribute }}" value="{{ $model->$attribute ?? '' }}" type="text" class="form-control" {{--readonly="readonly"--}} />

        @error($attribute)
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
        @error($attribute.'_file')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>
</div>