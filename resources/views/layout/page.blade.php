@extends('layout.base')

@section('body')
    <div id="app" x-data="{ sidebar_is_open: true }" data-role="layout-page" class="flex w-full h-screen overflow-hidden">
        <aside style="" class="flex flex-col h-full overflow-hidden bg-white transition-all border-r-2 border-b-gray-200"
            x-bind:class="sidebar_is_open ? 'w-80' : 'w-0'">
            <div class="flex justify-between  items-center px-6">
            <h1 id="logo"
                class="flex items-center justify-start w-full  h-16 text-2xl font-extrabold text-black tracking-widest cursor-default select-none">
                Taskly.
            </h1>
            <div id="sidebar-button" class="w-6 h-6" x-on:click="sidebar_is_open = !sidebar_is_open">
                <template x-if="sidebar_is_open">
                    <x-fas-square-caret-left  class="text-black"/>
                </template>

                {{-- <template x-if="!sidebar_is_open">
                    <x-fas-square-poll-horizontal />
                </template> --}}
            </div>
        </div>
            <section class="flex flex-col items-center justify-start w-full gap-2 overflow-x-hidden overflow-y-auto">
            

                @hasSection('app-side')
                    <div class="flex-grow w-full">
                        @yield('app-side')
                    </div>
                @endif
            </section>
        </aside>

        <div class="flex flex-col items-center content-center flex-1 h-full overflow-y-auto">
            <header data-role="app-header" class="sticky  flex items-center justify-between w-full h-16 px-6 shadow">
                <div class="flex items-center gap-4">
                    <div id="sidebar-button" class="w-6 h-6 text-black" x-on:click="sidebar_is_open = !sidebar_is_open">
                    <template x-if="!sidebar_is_open">
                        <x-fas-square-poll-horizontal />
                    </template>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <x-avatar name="{{ Auth::user()->name }}" asset="{{ Auth::user()->image_path }}" class="w-10 h-10 "
                        href="{{ route('setting') }}" />
                        <p class="text-black text-xl"> <span class="font-bold ">{{ Auth::user()->name }} </span></p>
                </div>
                    @yield('app-header')
                </div>


               
            </header>
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
