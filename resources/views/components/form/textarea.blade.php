@props(['name', 'icon', 'label', 'placeholder', 'required', 'autofocus', 'value', 'rows'])

<div data-role="form-textarea"
    {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center w-full gap-2']) }}>
    @isset($label)
        <label for="textarea-{{ $name }}" class="w-full pl-6 font-bold">{{ $label }}</label>
    @endisset
    <div class="flex bg-stone-200 items-center justify-center w-full gap-2 px-6 py-1 text-base rounded-lg">
        @isset($icon)
            <div class="w-4 h-4">@svg($icon)</div>
        @endisset
        <textarea class="flex-grow outline-none resize-none bg-transparent"
            @isset($placeholder) placeholder="{{ $placeholder }}" @endisset name="{{ $name }}"
            id="textarea-{{ $name }}" @isset($required) required @endisset
            @isset($autofocus) autofocus @endisset
            @isset($rows) rows="{{ $rows }}" @endisset>@isset($value){{ $value }}@endisset</textarea>
    </div>
</div>
