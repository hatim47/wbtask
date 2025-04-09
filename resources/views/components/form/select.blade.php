@props(['name', 'icon', 'placeholder', 'label', 'required', 'autofocus', 'value'])

<div data-role="form-text"
    {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center w-full gap-2']) }}>
    @isset($label)
        <label for="input-text-{{ $name }}" class="w-full pl-6">{{ $label }}</label>
    @endisset
    <div class="flex items-center  justify-center bg-stone-200 w-full gap-2 px-6 py-2 text-base rounded-lg">
        @isset($icon)
            <div class="w-4 h-4">@svg($icon)</div>
        @endisset
        <select class="flex-grow outline-none bg-transparent"
            @isset($placeholder) placeholder="{{ $placeholder }}" @endisset name="{{ $name }}"
            id="input-text-{{ $name }}" @isset($required) required @endisset
            @isset($autofocus) autofocus @endisset
            @isset($value) value="{{ $value }}" @endisset>
            {{ $slot }}
        </select>
        {{-- <x-fas-caret-down class="w-6 h-6"/> --}}
    </div>
</div>
