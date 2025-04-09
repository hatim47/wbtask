@extends('layout.pagesetting')

{{-- @section('app-header')
    <h1 class="text-xl font-bold">Setting</h1>
@endsection --}}
@section('content')
<template is-modal="deleteAccount">
    <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
    action="">
    @csrf
    {{-- <input type="hidden" name="id" value="{{ Auth::user()->id }}"> --}}
 
    <input type="hidden" name="id" id="deleteUserId">
    <p class="mb-6 text-lg text-center">Are you sure you want to Remove from this card?</p>
    <div class="flex gap-6">
        <x-form.button type="submit">Yes</x-form.button>
        <x-form.button type="button" action="ModalView.close()">No</x-form.button>
    </div>
    </form>
</template>


    <div class="flex flex-col w-full h-full gap-4 p-6 overflow-x-hidden overflow-y-auto">
        <section class="flex flex-col">
            <div class="flex items-center gap-2"> <a href="javascript:void(0);" onclick="location.replace(document.referrer);"
                class="p-1 bg-white shadow-md cursor-pointer select-none rounded-full">
                <x-fas-circle-left class="w-4 h-4" /> </a> <h2 class="text-4xl font-extrabold">All Member</h2>
            </div>
            <div class="overflow-hidden rounded-xl ">
                {{-- <header class="relative w-full  h-24">
                    <div class="absolute w-24 h-24 -bottom-10 left-8">
                        <x-avatar name="{{ Auth::user()->name }}" asset="{{ Auth::user()->image_path }}" action="ModalView.show('changeProfile')"
                            class="w-full h-full text-2xl shadow-md" >
                            <div class="flex flex-wrap items-center justify-center w-full h-full transition-all bg-black opacity-0 hover:opacity-70">
                                <x-fas-camera class="w-1/3 m-auto h-1/3"/>
                            </div>
                        </x-avatar>
                    </div>
                </header> --}}
          
                    <div class="flex flex-col  align-middle mt-14">
                        <form class="flex items-center gap-4" id="search-form" action="{{ route('searchTeam') }}" method="GET">
                            @csrf
                            <x-form.search icon="fas-magnifying-glass" name="team_name" placeholder="Team's name"
                                value="{{ session('__old_team_name') }}" />
                            {{-- <div class="h-full min-w-min">
                                <x-form.button type="submit" primary class="h-full">
                                    <x-fas-magnifying-glass class="w-4 h-4" />Search
                                </x-form.button>
                            </div> --}}
                        </form>
                <div class="flex flex-col flex-grow w-full gap-6 p-4 overflow-x-hidden overflow-y-auto  rounded-xl">
                  
                    @php
                    $shown = [];
                @endphp
                    @foreach ($team as $member)

                    @if ($member->users->isEmpty())
                        <p class="text-gray-500">No members yet.</p>
                    @else
                    @foreach ($member->users as $user)
                    @if (!in_array($user->id, $shown))
                        <div class="flex items-center gap-1">
                               
                                <x-avatar name="{{ $user->name }}" asset="{{ $user->image_path }}"
                                    class="!flex-shrink-0 !flex-grow-0 w-12" />
                                    <p class="w-40 truncate font-medium ps-4">{{ $user->name }}</p>
                                    @php $shown[] = $user->id; @endphp
                                </div>
                                @endif
                                @endforeach
                    @endif


                       
                    @endforeach
                </div>

                    </div>
                  
                
            </div>
        </section>


    </div>
@endsection

@pushOnce('page')
    <script>
    function openDeleteModal(userId) {
        ModalView.show('deleteAccount'); // Show the modal
        document.getElementById('deleteUserId').value = userId; // Set the user ID
    }
    </script>
@endPushOnce
