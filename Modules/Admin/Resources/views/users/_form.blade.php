<form method="POST" action="{{ $route }}" enctype="multipart/form-data">
    @method($method)
    @csrf

    @if ($errors->any())
        <x-architect::errors-list :errors="$errors->all()" />
    @endif

    <x-architect::text-input label="Email" attribute="email" :model="$model ?? null" />
    <x-architect::text-input label="Name" attribute="name" :model="$model ?? null" />
    <x-architect::file-input label="Photo" attribute="photo" :model="$model ?? null" hint="Only .jpeg, .jpg , .png, .gif files allowed" />

    <hr />

    <x-architect::radio-input label="Is Active" attribute="is_active" :options="[1 => 'Active', 0 => 'Inactive']" default="1" :model="$model ?? null"  />

    <hr />

    <x-architect::form-group>
        <strong>In case you want to set a new password</strong>
    </x-architect::form-group>

    <x-architect::password-input label="Password" attribute="password" :model="$model ?? null" />

    <hr />

    <x-architect::submit label="Submit" />
</form>

{{--@push('scripts')

@endpush--}}
