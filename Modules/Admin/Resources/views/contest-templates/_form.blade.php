<form method="POST" action="{{ $route }}" enctype="multipart/form-data">
    @method($method)
    @csrf

    @if ($errors->any())
        <x-architect::errors-list :errors="$errors->all()" />
    @endif

    <x-architect::text-input label="Headline" attribute="headline" :model="$model ?? null" />
    <x-architect::text-input label="Subheadline" attribute="subheadline" :model="$model ?? null" />
    <x-architect::textarea label="Explaining Text" attribute="explaining_text" :model="$model ?? null" />
    <x-architect::file-input label="Banner" attribute="banner" :model="$model ?? null" hint="Only .jpeg, .jpg , .png, .gif files allowed" />

    <hr />

    <x-architect::radio-input label="Is Active" attribute="is_active" :options="[2 => 'Active', 0 => 'Inactive']" default="1" :model="$model ?? null"  />

    <hr />

    <x-architect::submit label="Submit" />
</form>

{{--@push('scripts')

@endpush--}}
