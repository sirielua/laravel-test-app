@if ($errors)
    <div class="alert alert-danger mb-4">
        <ul class="mb-1">
            @foreach ($errors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
