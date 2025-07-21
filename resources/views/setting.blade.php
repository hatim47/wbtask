@extends('layout.pagesetting')



{{-- @section('app-header')

    <h1 class="text-xl font-bold">Setting</h1>

@endsection --}}

@section('app-side')
    <div class="flex flex-col gap-1  pl-4 mt-2" >
    <a data-role="menu-item" href="{{ route('viewHome') }}"
       class="flex items-center justify-start w-full gap-3 px-6 py-2  text-gray-600 cursor-pointer  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
       <p class=" "> Home </p>
    </a> 
@foreach ($teams_info as $info)
        {{-- {{dd($teams_info);}} --}}
<div x-data="{ open: true }">
            <button  @click="open = !open" type="button" class="flex items-center w-full p-2 text-base font-bold text-gray-700 transition duration-75 rounded-lg group hover:bg-gray-100 " aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">

                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $info['team']->name }} Workspace </span>
                  <svg class="w-3 h-3 transform" :class="open ? 'rotate-180' : ''" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul x-show="open" x-transition    @click.outside="open = false"
             class="py-2 space-y-2" style="display: none;">
                  <li>
<a data-role="menu-item" href="{{ route('viewTeam', ['team_id' => $info['team']->id]) }}"
       class="flex items-center justify-start w-full gap-3 px-6 py-2  text-gray-600 cursor-pointer  select-none {{ Route::currentRouteName() == 'Board' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
       <p class=" "> Board </p>
    </a>                  </li>
                  <li>
 <a data-role="menu-item" href="{{ route('viewWorkspace', ['team_id' => $info['team']->id]) }}"
        class="flex items-center justify-start w-full gap-3 px-6 py-2  text-gray-600 cursor-pointer  select-none {{ Route::currentRouteName() == 'Member' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
       
        <p class=" "> Member   </p>
    </a>                  </li>
                
            </ul>
        
<hr class=" border-gray-200"> 
        
       {{-- <div class="flex items-center justify-start w-full font-bold px-6 py-1  text-gray-500 cursor-pointer  select-none"> {{ $info['team']->name }} </div> --}}
   </div>
@endforeach
     
 
    </div>
<div class="flex flex-col gap-1  pl-4 mt-2" >
  @foreach ($assign_board as $board)

                            <a href="{{ route('board', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}"  class="flex gap-3  px-6 py-2 cursor-pointer select-none transition duration-300  border-gray-200  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
                                    <div
                                        class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-6 w-6 hover:shadow-2xl bg-grad-{{ $board->pattern }} overflow-hidden">
                                        </div>
                                    <h3 class="overflow-hidden  truncate ">{{ $board->name }}</h3>

                            </a>
                        @endforeach


</div>
@endsection



@section('content')
    <template is-modal="changeProfile">
        <div class="flex flex-col items-center justify-center w-full h-full gap-6 p-4 flex-grow-1">
            <x-form.file name="picture" label="Choose Image" accept="image/png, image/jpeg, image/jpg" />
            <div class="hidden w-full h-36" id="image-editor"></div>
            <x-form.button type="button" id="btn-submit" primary>Save
            </x-form.button>
        </div>
    </template>
    <template is-modal="deleteAccount">
        <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
            action="{{ route('doDeactivateUser') }}">
            @csrf
            <input type="hidden" name="id" value="{{ Auth::user()->id }}">
            <p class="mb-6 text-lg text-center"> Are you sure you want to delete your aacount?</p>
            <div class="flex gap-6">
                <x-form.button type="submit">Yes</x-form.button>
                <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
            </div>
        </form>
    </template>

    <template is-modal="changePassword">
        <div class="w-full h-full p-4">
            <p class="mb-6 text-lg font-semibold"> Enter Your current and new password below!</p>
            <form class="flex flex-col gap-16" action="{{ route('doUserPasswordUpdate') }}" method="POST">
                @csrf
                <div class="flex flex-col gap-2 ">
                    <x-form.password name="current_password" label="Current Password" />
                    <x-form.password name="new_password" label="New Password" />
                    <x-form.password name="new_password_confirmation" label="New Password Confirmation" />

                </div>

                <x-form.button primary type="submit">Save</x-form.button>

            </form>

    </template>

        <div class="max-w-3xl  mx-auto p-6 bg-white ">
  <!-- Profile Header -->
  <div class="flex items-center  mb-8">
       <x-avatar  name="{{ Auth::user()->name }}" asset="{{ Auth::user()->image_path }}" action="ModalView.show('changeProfile')"

                            class="w-24 h-24 text-2xl shadow-md" >

                            <div class="flex flex-wrap items-center justify-center w-full h-full transition-all bg-black opacity-0 hover:opacity-70">

                                <x-fas-camera class="w-1/3 m-auto h-1/3"/>
                            </div>
                        </x-avatar> 
    <div class="flex flex-col ps-3 items-center">
      <span class="text-xl font-semibold text-gray-800">Profile and Visibility</span>
    </div>
  </div>

  <!-- Manage Personal Information -->
  <div class="text-blue-600 mb-4">
    <p  class="text-lg font-medium">Manage your personal information</p>
  </div>
  
  <div class="space-y-6 mb-4">
    <!-- Username and Bio Form -->
     <form  method="POST" action="{{ route('doUserDataUpdate') }}">
                    @csrf
    <div class="space-y-2">
      <label for="username" class="text-sm text-gray-700">Username <span class="text-red-500">*</span></label>
      <input type="text" id="username" name="name" value="{{Auth::user()->name}}" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter username">
      <input type="text" disabled value="{{Auth::user()->email}}" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter username">
    </div>
  
  
    <!-- Save Button -->
    <div class="flex justify-end mt-4">
      <button type="submit" class="bg-blue-600 text-white py-2 px-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Save</button>
    </div>
 </form>
  </div>
  <div class="w-full ">
    {{-- <x-form.button type="button" action="ModalView.show('changePassword')">Change Password</x-form.button> --}}
@unless(Auth::user()->google_id)
    <button 
        type="button" 
        class="flex items-center justify-between w-full gap-2 px-6 py-2 text-base font-bold rounded-sm text-gray-700 bg-gray-100 hover:bg-gray-300 hover:text-black"
        onclick="ModalView.show('changePassword')"
    >
        Change Password  
        <x-fas-external-link-alt class="w-4 h-4" />
    </button>
@endunless
                        </div>


</div>

{{-- 
    <div class="flex flex-col w-full h-full gap-4 p-6 overflow-x-hidden overflow-y-auto">

        <section class="flex flex-col">

            <div class="flex items-center gap-2"> <a href="javascript:void(0);" onclick="location.replace(document.referrer);"

                class="p-1 bg-white shadow-md cursor-pointer select-none rounded-full">

                <x-fas-circle-left class="w-4 h-4" /> </a> <h2 class=" text-2xl font-bold">My Account</h2>

            </div>

            <div class="overflow-hidden rounded-xl ">
                <header class="relative w-full  h-24">
                    <div class="absolute w-24 h-24 -bottom-10 left-8">
                        <x-avatar name="{{ Auth::user()->name }}" asset="{{ Auth::user()->image_path }}" action="ModalView.show('changeProfile')"
                            class="w-full h-full text-2xl shadow-md" >
                            <div class="flex flex-wrap items-center justify-center w-full h-full transition-all bg-black opacity-0 hover:opacity-70">
                                <x-fas-camera class="w-1/3 m-auto h-1/3"/>
                            </div>
                        </x-avatar>                    </div>
                </header>
               
                    <div class="grid items-center grid-cols-2 grid-rows-2 gap-x-8 gap-y-1 align-middle mt-14">
                        <label for="input-text-name" class="text-lg ps-4 font-semibold">Full Name</label>
                        <label for="input-text-email" class="text-lg ps-4 font-semibold">Email</label>                         
                        <x-form.text name="name" :value="Auth::user()->name" /> 
                        <x-form.text name="email" :value="Auth::user()->email" />   
                    </div>                  
                    <div class="flex flex-col items-center justify-start mt-4 gap-4">
                        <div class="w-full">
                            <x-form.button primary type="submit">Submit</x-form.button>
                        </div>
                          <div class="w-full">
                            {{-- <x-form.button type="button" action="ModalView.show('changePassword')">Change Password</x-form.button> --}
                        </div>
                    </div>
            </div>
        </section>



        <section class="flex flex-col bottom-0 absolute">          
            <div class="flex items-center gap-6 px-10 py-8 align-middle  pxoverflow-hidden rounded-xl">          
                <a href="{{ route('doLogout') }}" class="">
                    <x-form.button type="button">Logout Account</x-form.button>
                </a>              
                <x-form.button primary action="ModalView.show('deleteAccount')" type="button">Delete Account</x-form.button>
           </div>
        </section>



    </div>  --}}

@endsection



@pushOnce('page')

    <script>

        ModalView.onShow("changeProfile", (modal) => {

            const imageInput = modal.querySelector("#input-file-picture");

            const btnSubmit = modal.querySelector("#btn-submit");

            const imageEditorContainer = modal.querySelector("#image-editor");

            let imageEditor = new Croppie(imageEditorContainer, {

                viewport: {

                    width: 150,

                    height: 150,

                    type: 'circle'

                },



                boundary: {

                    width: 200,

                    height: 200

                }

            });



            imageInput.addEventListener("change", (event) => {

                imageEditorContainer.classList.remove("hidden");

                imageEditor.bind({

                    url: URL.createObjectURL(event.target.files[0]),

                    orientation: 1

                });

            });



            btnSubmit.addEventListener("click", async (e) => {

                try {

                    PageLoader.show();

                    const pfpBlobData = await getCropperImageBlob(imageEditor);

                    let response = await ServerRequest.post("{{ route('doUserPicturedUpdate') }}", {

                        image: pfpBlobData

                    });

                    location.reload();

                } catch (error) {

                    PageLoader.close();

                    ModalView.close();

                    let errorMessage = getResponseError(error);

                    if (error)

                        ToastView.notif("Warning", errorMessage);

                    else

                        ToastView.notif("Error", "Something went wrong please try again");

                }

            });



        });





        @if ($errors->any())

            ToastView.notif("Warning", "{{ $errors->first() }}");

        @endif

    </script>

@endPushOnce

