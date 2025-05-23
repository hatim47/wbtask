@extends('layout.reset')

@pushOnce('head')
<style>

    </style>
    @endpushOnce
@section('form')
    
<div class="flex flex-col items-start justify-center w-full h-full" data-role="page-login">
    <h1 class="flex flex-col font-bold ps-20 items-start w-full pb-16 text-4xl text-black">Taskly. </h1>
        <div class="flex flex-col items-center w-full pb-40 pt-4 bg-white justify-cente">
        
            <div data-role="login-container" class="w-full max-w-lg px-10">
                <h1 class="mb-3 text-3xl  font-bold justify-start">
                    Set a password

                </h1>
                <p class="mb-16">Your previous password has been reseted. Please set a new password for your account.</p>
                {{-- Login Form --}}
                <form action="{{ route('forgot') }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <div x-data="{ show: false }" class="flex items-center justify-center w-full gap-2 ps-4 pe-2 py-2 text-base border border-zinc-800 rounded-[0.157rem]">
                        <input
                            :type="show ? 'text' : 'password'"
                            class="flex-grow outline-none bg-transparent"
                            placeholder="Create Password"
                            name="pass"
                            autofocus
                            value="{{ old('email') }}"
                        />
                        <button type="button" @click="show = !show" class="text-gray-800">
                            <x-fas-eye x-show="!show" class="w-4 h-4" />
                            <x-fas-eye-slash x-show="show" class="w-4 h-4" />
                        </button>
                    </div>
                    <div x-data="{ show: false }" class="flex items-center justify-center w-full gap-2 ps-4 mb-8 pe-2 py-2 text-base border border-zinc-800 rounded-[0.157rem]">
                        <input
                            :type="show ? 'text' : 'password'"
                            class="flex-grow outline-none bg-transparent"
                            placeholder="Re-enter Password"
                            name="repass"
                            autofocus
                            value="{{ old('email') }}"
                        />
                        <button type="button" @click="show = !show" class="text-gray-800">
                            <x-fas-eye x-show="!show" class="w-4 h-4" />
                            <x-fas-eye-slash x-show="show" class="w-4 h-4" />
                        </button>
                    </div>
                    <div data-role="action-message" class="flex flex-col gap-4 mt-12">
                        @if ($errors->any())
                            <p class="ml-4 text-sm font-medium text-red-500">{{ $errors->first() }}</p>
                        @endif                  
                        <button class="w-full px-4 py-2  font-semibold text-white bg-zinc-700 rounded-lg hover:bg-stone-200 hover:text-black">Submit</button>
                      
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
