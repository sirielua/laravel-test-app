<form method="POST" action="{{ $route }}" class="form-inline">
    @method($method)
    @csrf

    <div class="input-group mb-2">
        <label class="sr-only" for="codeInput">Verification Code</label>
        <input type="text" name="code" class="form-control mr-sm-2 @error('code') is-invalid @enderror" id="codeInput">

        <button type="submit" class="btn btn-primary">Verify</button>

        @error('code')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>
</form>
