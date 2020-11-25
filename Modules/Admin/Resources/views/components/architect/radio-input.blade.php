<fieldset class="position-relative row form-group">
    <legend class="col-form-label col-sm-2">{{ $label }}</legend>
    <div class="col-sm-10">
            @foreach ($options as $value => $option)
                <div class="position-relative form-check">
                    <label class="form-check-label">
                        <input name="{{ $attribute }}" value="{{ $value }}" type="radio" class="form-check-input" {{ old($attribute, $model->$attribute ?? $default) == $value ? 'checked' : '' }}> {{ $option }}
                    </label>
                </div>
            @endforeach

        @error($attribute)
            <div class="invalid-feedback" role="alert" style="display: block;">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>
    <div class="col-sm-10">
        @if ($hint)
            <small class="form-text text-muted">{{ $hint }}</small>
        @endif
    </div>
</fieldset>
