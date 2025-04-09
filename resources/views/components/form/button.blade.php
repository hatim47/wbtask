@props(['primary', 'action', 'type', 'id', 'form', 'accept', 'outline'])

<button @isset($action) onclick="{{ $action }}" @endisset
    @isset($form) form="{{ $form }}" @endisset
    @isset($type) type="{{ $type }}" @endisset
    @isset($id) id="{{ $id }}" @endisset
    @if (isset($primary))
    {{ $attributes->merge(['class' => 'flex items-center justify-center w-full gap-2 px-6 py-1 text-base font-bold border-4 border-black rounded-lg  bg-black text-white hover:bg-white hover:text-black']) }}>
    @elseif (isset($outline))
    {{ $attributes->merge(['class' => 'flex items-center justify-center w-full gap-2 px-6 py-1 text-base font-bold border-4 border-black rounded-lg bg-white text-black hover:bg-black hover:text-white']) }}>
    @else
    {{ $attributes->merge(['class' => 'flex items-center justify-center w-full gap-2 px-6 py-2 text-base font-bold rounded-lg text-black bg-stone-200 hover:bg-stone-300 hover:text-black ']) }}>
    @endif
    {{ $slot }}
</button>


