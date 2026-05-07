@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-zinc-300 focus:border-violet-500 focus:ring-violet-500 rounded-lg shadow-sm text-sm']) }}>
