@extends('layout.auth')

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
                    Login
                </h1>
                <p class="mb-16">Login to access your account</p>
                {{-- Login Form --}}
                <form action="{{ route('doLogin') }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <div class="flex flex-col gap-6">
                        {{-- <x-form.text placeholder="email" icon="fas-user" name="email" value="{{ old('email') }}"
                            autofocus required></x-form.text> --}}
                            <div class="flex items-center justify-center w-full gap-2 px-6 py-2 text-base border border-zinc-800 rounded-[0.157rem]">
                            <input type="text" class="flex-grow outline-none bg-transparent"
                             placeholder="email"  name="email" autofocus value="{{ old('email') }}">
                            </div>

                            {{-- <div class="flex items-center justify-center w-full gap-2 px-6 py-2 text-base border-2 border-neutral-400 rounded-md">
                                <input type="password" class="flex-grow outline-none bg-transparent"
                                 placeholder="password"  name="password"  autofocus value="{{ old('email') }}">
                                 <div class=""> <x-fas-eye class="w-4 h-4" /></div>
                                </div> --}}
                                <div x-data="{ show: false }" class="flex items-center justify-center w-full gap-2 px-6 py-2 text-base border border-zinc-800 rounded-[0.157rem]">
                                    <input
                                        :type="show ? 'text' : 'password'"
                                        class="flex-grow outline-none bg-transparent"
                                        placeholder="Password"
                                        name="password"
                                        autofocus
                                        value="{{ old('email') }}"
                                    />
                                    <button type="button" @click="show = !show" class="text-zinc-800">
                                        <x-fas-eye x-show="!show" class="w-4 h-4" />
                                        <x-fas-eye-slash x-show="show" class="w-4 h-4" />
                                    </button>
                                </div>

                        {{-- <x-form.password placeholder="password" name="password" icon="fas-lock" required></x-form.password> --}}
                    </div>


                    <div data-role="action-message" class="flex flex-col gap-4">
                        @if ($errors->any())
                            <p class="ml-4 text-sm font-medium text-red-500">{{ $errors->first() }}</p>
                        @endif

                        <div  class="flex flex-row justify-between ">
                        <x-form.checkbox name="remember_me">Remember Me</x-form.checkbox>
                        <a href="{{ route('forget') }}" class="text-center w-full text-red-500 font-medium"> Forgot Password </a>
                    </div>
                        <button class="w-full px-4 py-2 font-semibold text-white bg-zinc-700 rounded-lg hover:bg-stone-200 hover:text-black">Login</button>
                        <a href="{{ route('register') }}" class="text-center font-light">Don’t have an account? <span class="text-red-500 font-medium"> Sign up. </span></a>
                        <div class="flex items-center gap-2 my-4">
                            <div class="flex-1 border-t border-gray-300"></div>
                            <span class="text-gray-400 text-sm">Or login with</span>
                            <div class="flex-1 border-t border-gray-300"></div>
                        </div>
                        <a href="{{ route('google.login') }}" class="w-full px-4 py-2 font-semibold text-white border border-zinc-800  rounded-[0.157rem]" style="display: flex;align-items: center;justify-content: space-around;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="-3 0 262 262" preserveAspectRatio="xMidYMid"><path d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" fill="#4285F4"/><path d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" fill="#34A853"/><path d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" fill="#FBBC05"/><path d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" fill="#EB4335"/></svg> 
                        </a>
                        
                        {{-- <a href="{{ route('google.login') }}" class="w-full px-4 py-2 font-semibold text-white bg-black rounded-lg hover:bg-stone-200 hover:text-black" style="display: flex;align-items: center;justify-content: space-around;">
                            
                            Login with Google 
                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="-3 0 262 262" preserveAspectRatio="xMidYMid"><path d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" fill="#4285F4"/><path d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" fill="#34A853"/><path d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" fill="#FBBC05"/><path d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" fill="#EB4335"/></svg>

                           
                        </a> --}}
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
