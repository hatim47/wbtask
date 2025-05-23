@extends('layout.pagesetting')

@section('app-header')
    {{-- <h1 class="text-xl font-bold text-black">Setting</h1> --}}
@endsection
@section('content')
<template is-modal="deleteAccount">
    <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
    action="{{ route('leaveCard', ['team_id' => $team->id, 'board_id' => $board->id, 'card_id' => $card]) }}">
    @csrf
    {{-- <input type="hidden" name="id" value="{{ Auth::user()->id }}"> --}}
 
    <input type="hidden" name="id" id="deleteUserId">
    <p class="mb-6 text-lg text-center"> Are you sure you want to Remove from this card?</p>
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
                <x-fas-circle-left class="w-4 h-4" /> </a> <h2 class=" text-4xl font-extrabold">Member</h2>
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
                        <div
                    class="flex flex-col flex-grow w-full gap-4 py-4  overflow-x-hidden overflow-y-auto rounded-xl">
                        @foreach ($workers as $worker)
                        <div class="flex items-center  justify-between">
                        @if ($worker->id == $owner->id || $worker->id == $chatOwner->user_id)
                        <div class="flex px-3 ">
                               
                                    <x-avatar name="{{ $worker->name }}" asset="{{ $worker->image_path }}"
                                        class="!flex-shrink-0 !flex-grow-0 w-12 h-12" />
                              
                                <div class="flex items-center flex-row">
                                <label for="input-text-name" class="text-lg  px-4  font-semibold">{{ $worker->name }}</label>
                                <x-fas-crown class="w-6 h-6 text-yellow-400 !flex-shrink-0 !flex-grow-0" />
                            </div>
                            </div>
                            @else
                            <div class="flex items-center px-3">
                            <x-avatar name="{{ $worker->name }}" asset="{{ $worker->image_path }}" class="!flex-shrink-0 !flex-grow-0 w-12 h-12 mt-2"/>   
                            <label for="input-text-name" class="text-lg ps-4 font-semibold">{{ $worker->name }}</label> 
                            </div>
                        <button class="flex items-center gap-2 px-6 py-2 text-base font-bold rounded-full text-black bg-stone-200 hover:bg-black hover:text-white"  onclick="openDeleteModal({{ $worker->id }})"  type="button" >Remove</button>
                    @endif 
                </div>
                   
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
