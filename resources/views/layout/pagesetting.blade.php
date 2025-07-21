@extends('layout.base')



@section('body')

    <div id="app" x-data="{ sidebar_is_open: true }" data-role="layout-page" class="flex w-full h-screen overflow-hidden">

        <aside class="flex flex-col h-full overflow-hidden transition-all bg-stone-50"
            x-bind:class="sidebar_is_open ? 'w-80' : 'w-0'">
            <h1 id="logo" class="flex items-center justify-center w-full h-16 text-2xl font-extrabold text-black tracking-widest cursor-default select-none">
                Taskly.
            </h1>
            <section class="flex flex-col items-center justify-start w-full gap-2 overflow-x-hidden overflow-y-auto">
                @hasSection('app-side')
                    <div class="flex-grow w-full">
                        @yield('app-side')
                    </div>
                @endif
            </section>
        </aside>



        <div class="flex flex-col items-center content-center flex-1 h-full overflow-y-auto">
            <div class="flex-grow w-full overflow-y-auto">
                @yield('content')
            </div>
        </div>
    </div>
@endsection



@pushOnce('component')

    <x-server-request-script />

@endPushOnce



@pushOnce('page')

    <script>

        document.querySelectorAll("a").forEach(

            link => link.addEventListener("click", () => PageLoader.show())

        );



        document.querySelectorAll("form[action][method]").forEach(

            form => form.addEventListener("submit", () => PageLoader.show())

        );

    </script>

@endPushOnce

