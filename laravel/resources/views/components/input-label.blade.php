@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-zinc-600 mb-1']) }}>
    {{ $value ?? $slot }}
</label>
