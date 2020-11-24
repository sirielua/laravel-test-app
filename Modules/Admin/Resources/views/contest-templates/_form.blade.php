<form method="POST" action="{{ $route }}" enctype="multipart/form-data">
    @method($method)
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="position-relative row form-group">
        <label for="name" class="col-sm-2 col-form-label">Headline</label>
        <div class="col-sm-10">
            <input name="headline" value="{{ old('headline', $model->headline ?? '') }}"  id="headline" placeholder="Headline" type="text" class="form-control @error('headline') is-invalid @enderror" />
            @error('headline')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
    </div>

    <div class="position-relative row form-group">
        <label for="name" class="col-sm-2 col-form-label">Subheadline</label>
        <div class="col-sm-10">
            <input name="subheadline" value="{{ old('subheadline', $model->subheadline ?? '') }}"  id="subheadline" placeholder="Subheadline" type="text" class="form-control @error('subheadline') is-invalid @enderror" />
            @error('subheadline')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
    </div>

    <div class="position-relative row form-group">
        <label for="description" class="col-sm-2 col-form-label">Explaining Text</label>
        <div class="col-sm-10">
            <textarea name="explaining_text" id="explaining_text" class="form-control">{{ old('explaining_text', $model->explaining_text ?? '') }}</textarea>
        </div>
    </div>

    <div class="position-relative row form-group">
        <label for="banner_file" class="col-sm-2 col-form-label">Banner</label>
        <div class="col-sm-10">
            <input name="banner" value="{{ $model->banner ?? '' }}" type="text" class="form-control" />
            <input name="banner_file" id="banner_file" type="file" class="form-control-file @error('banner_file') is-invalid @enderror" />
            <small class="form-text text-muted">Only .jpeg, .jpg , .png, .gif files allowed</small>
            @error('banner')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
            @error('banner_file')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
    </div>

    <hr />

    <fieldset class="position-relative row form-group">
        <legend class="col-form-label col-sm-2">Status</legend>
        <div class="col-sm-10">
            <div class="position-relative form-check"><label class="form-check-label"><input name="is_active" value="1" type="radio" class="form-check-input" {{ old('is_active', $model->is_active ?? 1) == 1 ? 'checked' : '' }}> Active</label></div>
            <div class="position-relative form-check"><label class="form-check-label"><input name="is_active" value="0" type="radio" class="form-check-input" {{ old('is_active', $model->is_active ?? 1) == 0 ? 'checked' : '' }}> Inactive</label></div>
        </div>
    </fieldset>

    <div class="position-relative row">
        <div class="col-sm-10 offset-sm-2">
            <button class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>

{{--@push('scripts')

@endpush--}}
