@extends('layout.base')



@pushOnce('head')

@endPushOnce



@section('body')

    <div class="flex flex-row   w-full max-w-screen-2xl mx-auto py-6">



      

        

        <div class="flex items-center content-center w-full max-w-screen-lg  h-full lg:bg-none">

          

            @yield('form')

        </div>

        

        <div class="items-center content-center hidden w-full  none  lg:flex">

       <img src="/public/image/user/login.png" alt="" class="">



    </div>

</div>

@endsection

