@props(['name', 'checked'])

{{-- <div data-role="form-checkbox" {{ $attributes->merge(['class' => 'flex items-center w-full ml-6 font-medium']) }}>
    <label for="{{ $name }}" class="flex items-center w-full gap-2 font-medium">
        <input type="checkbox" name="{{ $name }}" id="{{ $name }}" class="appearance-none w-5 h-5 border-2 border-red-600 rounded checked:bg-red-600"
            @isset($checked) checked @endisset>
        <div>
            {{ $slot }}
        </div>
    </label>
</div> --}}
<div data-role="form-checkbox" {{ $attributes->merge(['class' => 'flex items-center w-full ml-6 font-medium']) }}>
    <label for="{{ $name }}" class="flex items-center w-full gap-2 font-medium text-zinc-800 cursor-pointer">
        <input type="checkbox" name="{{ $name }}" id="{{ $name }}"
            class="peer hidden"
            @isset($checked) checked @endisset>
        
        <div class="w-5 h-5 border-2 border-zinc-800 rounded-md flex items-center justify-center 
            peer-checked:bg-zinc-800 transition-colors duration-200">
            <!-- Checkmark SVG -->
            <x-fas-check class=" peer-checked:block w-3 h-3 text-white" />
        </div>

        <div>
            {{ $slot }}
        </div>
    </label>
</div>
