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
        <label for="email" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input name="email" value="{{ old('email', $model->email ?? '') }}" id="email" placeholder="Email" type="email" class="form-control @error('email') is-invalid @enderror" autocomplete="email" autofocus />
            @error('email')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
    </div>

    <div class="position-relative row form-group">
        <label for="name" class="col-sm-2 col-form-label">Name</label>
        <div class="col-sm-10">
            <input name="name" value="{{ old('name', $model->name ?? '') }}"  id="name" placeholder="Name" type="text" class="form-control @error('name') is-invalid @enderror" />
            @error('name')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
    </div>

    <div class="position-relative row form-group">
        <label for="photo_file" class="col-sm-2 col-form-label">Photo</label>
        <div class="col-sm-10">
            <input name="photo" value="{{ $model->photo ?? '' }}" type="text" class="form-control" />
            <input name="photo_file" id="photo_file" type="file" class="form-control-file @error('photo_file') is-invalid @enderror" />
            <small class="form-text text-muted">Only .jpeg, .jpg , .png, .gif files allowed</small>
            @error('photo')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
            @error('photo_file')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
    </div>

    <hr />

    <fieldset class="position-relative row form-group">
        <legend class="col-form-label col-sm-2">User status</legend>
        <div class="col-sm-10">
            <div class="position-relative form-check"><label class="form-check-label"><input name="is_active" value="1" type="radio" class="form-check-input" {{ old('is_active', $model->is_active ?? 1) == 1 ? 'checked' : '' }}> Active user</label></div>
            <div class="position-relative form-check"><label class="form-check-label"><input name="is_active" value="0" type="radio" class="form-check-input" {{ old('is_active', $model->is_active ?? 1) == 0 ? 'checked' : '' }}> Inactive user</label></div>
        </div>
    </fieldset>

    <hr />

    <div class="position-relative row form-group">
        <div class="col-sm-10"><strong>In case you want to set a new password</strong></div>
    </div>

    <div class="position-relative row form-group">
        <label for="password" class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-10">
            <input name="password" id="password" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" />
            @error('password')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
    </div>

    <div class="position-relative row form-group">
        <label for="confirm-password" class="col-sm-2 col-form-label">Confirm password</label>
        <div class="col-sm-10">
            <input name="password_confirmation" id="confirm-password" placeholder="Confirm password" type="password" class="form-control @error('password') is-invalid @enderror" />
        </div>
    </div>

    <hr />

    {{--<div class="position-relative row form-group">
        <label for="description" class="col-sm-2 col-form-label">Description</label>
        <div class="col-sm-10">
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
    </div>--}}

    <div class="position-relative row">
        <div class="col-sm-10 offset-sm-2">
            <button class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>

{{--@push('scripts')

@endpush--}}
