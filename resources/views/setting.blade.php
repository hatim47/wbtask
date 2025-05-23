@extends('layout.pagesetting')



{{-- @section('app-header')

    <h1 class="text-xl font-bold">Setting</h1>

@endsection --}}





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
                <form class="px-10 mb-4" method="POST" action="{{ route('doUserDataUpdate') }}">
                    @csrf
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
                            <x-form.button type="button" action="ModalView.show('changePassword')">Change Password</x-form.button>

                        </div>

                    </div>

                </form>

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



    </div>

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

