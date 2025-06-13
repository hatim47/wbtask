@extends('layout.base')

@section('body')

    <div id="app board-background" x-data="{ sidebar_is_open: true }" data-role="layout-page" class="flex w-full h-screen overflow-hidden bg-grad-{{ $board->pattern }}">
        <aside style="background: #b5b5b5c4; border-right-color: rgba(203, 203, 203, 0.407);" class="flex flex-col h-full overflow-hidden transition-all border-r-2 "
            x-bind:class="sidebar_is_open ? 'w-80' : 'w-0'">
            <div class="flex justify-between  items-center px-6">
                <h1 id="logo"
                    class="flex items-center justify-start w-full  h-16 text-2xl font-extrabold text-white tracking-widest cursor-default select-none">
                    Taskly.
                </h1>
                <div id="sidebar-button" class="w-6 h-6" x-on:click="sidebar_is_open = !sidebar_is_open">
                    <template x-if="sidebar_is_open">
                        <x-fas-square-caret-left  class="text-white"/>
                    </template>
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
            <header style="background: #31313180" data-role="app-header" class="sticky flex items-center justify-between w-full h-24 px-6 shadow">
                <div class="flex items-center w-full gap-4">
                        <div id="sidebar-button" class="w-6 h-6 text-white" x-on:click="sidebar_is_open = !sidebar_is_open">
                        <template x-if="!sidebar_is_open">
                            <x-fas-square-poll-horizontal />
                        </template>
                    </div>
                    {{-- <div id="sidebar-button" class="w-6 h-6" x-on:click="sidebar_is_open = !sidebar_is_open">
                        <template x-if="sidebar_is_open">
                            <x-fas-square-xmark />
                        </template>

                        <template x-if="!sidebar_is_open">
                            <x-fas-square-poll-horizontal />
                        </template>
                    </div> --}}

                    @yield('app-header')
                   
                </div>


                {{-- <div class="flex items-center justify-center gap-2">
                    <p> <span class="font-bold ">Hello, </span> {{ Auth::user()->name }}</p>
                    <x-avatar name="{{ Auth::user()->name }}" asset="{{ Auth::user()->image_path }}" class="w-12 h-12"
                        href="{{ route('setting') }}" />
                </div> --}}
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
