@extends('layout.page')



@pushOnce('metasa')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endPushOnce





@section('app-side')
    <div class="flex flex-col gap-1 px-8 pl-4 mt-2">
        <div data-role="menu-item" onclick="ModalView.show('createTeam')"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'hover:bg-neutral-500' : 'hover:bg-neutral-500' }}">

            <x-fas-cube class="w-6 h-6" />

            <p class="text-lg font-normal"> Add Team </p>

        </div>

        <a href="{{ route('allmember') }}" data-role="menu-item"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'hover:bg-neutral-500' : 'hover:bg-neutral-500 ' }}">

            <x-fas-users class="w-6 h-6" />

            <p class="text-lg font-normal">Members</p>

        </a>

        <div data-role="menu-item" onclick="ModalView.show('inviteMember')"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'hover:bg-neutral-500' : 'hover:bg-neutral-500' }}">

            <x-fas-user-plus class="w-6 h-6" />

            <p class="text-lg font-normal"> Invite</p>

        </div>



        <a data-role="menu-item" href="{{ route('setting') }}"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'hover:bg-neutral-500' : 'hover:bg-neutral-500 ' }}">

            <x-fas-gear class="w-6 h-6" />

            <p class="text-lg font-normal"> Setting </p>

        </a>
        <a data-role="menu-item" href="{{ route('chats') }}"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'hover:bg-neutral-500' : 'hover:bg-neutral-500 ' }}">

            <x-fas-comments class="w-6 h-6" />

            <p class="text-lg font-normal"> chat </p>

        </a>


    </div>
@endsection



@section('content')
    <template is-modal="createTeam">

        <div class="flex flex-col w-full gap-4 p-4">

            <h1 class="text-3xl font-bold">Create Team</h1>

            <hr>

            <form action="{{ route('doCreateTeam') }}" method="POST" class="flex flex-col gap-4">

                @csrf

                <x-form.text name="team_name" label="Team's Name" required />

                <x-form.textarea name="team_description" label="Team's Description" required />

                <x-form.button class="mt-4" type="submit" primary>Submit</x-form.button>

            </form>

        </div>

    </template>

    <template is-modal="inviteMember" class="bg-red-200">

        <div class="flex flex-col w-full gap-4 p-4">

            <h1 class="text-3xl font-bold">Invite People</h1>

            <hr>

            <div class="flex flex-col gap-4">

                <label for="input-text-inv-email">Enter email address</label>

                <div class="flex gap-4">

                    <x-form.select name="teamsa" icon="fas-users">

                        <option value="">Select Team</option>

                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">

                                {{ $team->name }}

                            </option>
                        @endforeach

                    </x-form.select>

                    <x-form.text name="inv-email" icon="fas-user-plus" placeholder="name@email.com..." />

                    <x-form.button type="button" primary class="w-min" id="add-btn">

                        <x-fas-plus class="w-6 h-6" />

                        Add

                    </x-form.button>

                </div>



                <form method="POST" id="invite-members-form" action="{{ route('InviteMemberto') }}"
                    class="flex justify-center w-full p-4 overflow-hidden overflow-y-auto border-2 border-black h-80 rounded-xl">

                    @csrf

                    <input type="hidden" name="team_id", value="">



                    <div class="flex flex-col w-full gap-2" id="invite-container"> </div>
                </form>

                <x-form.button primary type="submit" id="save-btn" form="invite-members-form">Save</x-form.button>

            </div>

        </div>

    </template>



    <template is-modal="acceptInvite">

        <div class="flex flex-col w-full gap-4 p-4">

            <h1 class="text-3xl font-bold">Team Invite</h1>

            <div class="flex flex-col gap-4">

                <header class="w-full p-4 h-28" id="header-overlay">

                    <div
                        class="relative flex items-center justify-center w-20 overflow-hidden bg-black border-4 border-white rounded-full aspect-square">

                        <img id="team-image" src="" alt=""
                            class="absolute top-0 left-0 z-40 object-fill w-full h-full">

                        <p class="text-2xl font-bold text-white" id="team-initial"></p>

                    </div>

                </header>

                <hr>



                <article class="flex flex-col gap-2">

                    <p>you are invited to join team <span id="team-name" class="font-bold"></span></p>

                    <p><span class="font-bold">Description: </span><span id="team-description"></span></p>

                </article>



                <article class="flex items-center gap-2 mt-2">



                    <p>Sincerely, <span id="owner-name" class="font-bold"></span></p>

                    <div
                        class="relative flex items-center justify-center w-12 overflow-hidden bg-black border-4 border-white rounded-full aspect-square">

                        <img id="owner-image" src="" alt=""
                            class="absolute top-0 left-0 z-40 object-fill w-full h-full">

                        <p class="text-base font-bold text-white" id="owner-initial"></p>

                    </div>



                </article>



                <form class="flex gap-4">

                    <x-form.button type="submit" primary id="btn-yes">Accept</x-form.button>

                    <x-form.button type="submit" id="btn-no">Reject</x-form.button>

                </form>

            </div>

        </div>

    </template>



    <template is-modal="createTeam">

        <div class="flex flex-col w-full gap-4 p-4">

            <h1 class="text-3xl font-bold">Create Team</h1>



            <hr>

            <form action="{{ route('doCreateTeam') }}" method="POST" class="flex flex-col gap-4">

                @csrf

                <x-form.text name="team_name" label="Team's Name" required />

                <x-form.textarea name="team_description" label="Team's Description" required />



                <div class="flex flex-col w-full gap-2" x-data="{ selected: '{{ $patterns[0] }}' }">

                    <label class="pl-6">Team's Background</label>

                    <input type="hidden" id="pattern-field" name="team_pattern" x-bind:value="selected">

                    <div
                        class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">

                        @foreach ($patterns as $pattern)
                            <div x-on:click="selected = '{{ $pattern }}'"
                                x-bind:class="(selected == '{{ $pattern }}') ? 'border-black' : 'border-gray-200'"
                                class="{{ $pattern == $patterns[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-pattern-{{ $pattern }} hover:border-black">

                                <div x-bind:class="(selected == '{{ $pattern }}') ? 'opacity-100' : 'opacity-0'"
                                    class="flex items-center justify-center w-full h-full">

                                    <x-fas-circle-check class="w-6 h-6" />

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

                <x-form.button class="mt-4" type="submit" primary>Submit</x-form.button>

            </form>

        </div>

    </template>

    <template is-modal="deleteTeam">

        <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
            action="{{ route('doDeleteTeamSec') }}">

            @csrf

            {{-- <div class="flex flex-col items-center justify-center w-full h-full gap-6 p-4"> --}}

            <input type="hidden" name="team_id" id="input-text-team_id">

            <p class="mb-6 text-lg text-center"> Are you sure you want to delete this team?</p>

            <div class="flex gap-6">

                <x-form.button type="submit">Yes</x-form.button>

                <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>

            </div>

        </form>

    </template>



    <template is-modal="updateTeam">

        <div class="flex flex-col w-full gap-4 p-4">

            <h1 class="text-3xl font-bold">Edit Team</h1>

            <hr>

            {{-- <div action="{{ route('doTeamDataUpdate', ['team_id' => $team->id]) }}" method="POST" class="flex flex-col gap-4"> --}}

            {{-- @csrf --}}

            <from method="POST" id="update-team-form" class="flex flex-col gap-4">



                <input type="hidden" name="team_id" id="input-text-team_id">

                <x-form.text name="team_name" label="Team's Name" required />

                <x-form.textarea name="team_description" label="Team's Description" required />

                <div class="flex flex-col w-full gap-2">

                    <label class="pl-6">Team's Background</label>

                    <input type="hidden" id="pattern-field" name="team_pattern" x-bind:value="selected">

                    <div
                        class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">

                        @foreach ($patterns as $pattern)
                            <div x-on:click="selected = '{{ $pattern }}'"
                                x-bind:class="(selected == '{{ $pattern }}') ? 'border-black' : 'border-gray-200'"
                                class="{{ $pattern == '' ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-pattern-{{ $pattern }} hover:border-black">

                                <div x-bind:class="(selected == '{{ $pattern }}') ? 'opacity-100' : 'opacity-0'"
                                    class="flex items-center justify-center w-full h-full">

                                    <x-fas-circle-check class="w-6 h-6" />

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>



                <x-form.button class="mt-4" type="submit" id="update-btn" primary>Submit</x-form.button>

                </form>

        </div>

    </template>

    <div class="flex flex-col w-full   px-8 py-6 h-3/4 ">

        <header class="w-full">

            {{-- <h2 class="ml-6 mb-5 text-4xl font-extrabold"> Chats</h2> --}}





        </header>



        <div id="appp" class="h-[80vh]">
            <parent-chat></parent-chat>
        </div>





    </div>
@endsection



@pushOnce('page')
    <script>
        ModalView.onShow("createTeam", (modal) => {

            modal.querySelectorAll("form[method][action]").forEach(

                form => form.addEventListener("submit", () => PageLoader.show())

            );

        });









        function openEditTeamModal(button) {

            // Get data from the button     

            const teamId = button.getAttribute('data-id');

            const teamName = button.getAttribute('data-name');

            const teamDescription = button.getAttribute('data-description');

            const teamPattern = button.getAttribute('data-pattern');

            console.log(teamId);

            // Populate the modal fields with the data

            // Show the modal    



            ModalView.onShow("updateTeam", (modal) => {

                document.getElementById('input-text-team_id').value = teamId;

                document.getElementById('input-text-team_name').value = teamName;

                document.getElementById('textarea-team_description').value = teamDescription;

                const btnSubmit = modal.querySelector("#update-btn");

                btnSubmit.addEventListener("click", (event) => {







                    // Get the form and input elements

                    const form = document.getElementById('update-team-form');

                    const teamId = document.getElementById('input-text-team_id')
                        .value; // Example of fetching a value



                    const teamName = document.getElementById('input-text-team_name').value;

                    const teamDescription = document.getElementById('textarea-team_description').value;

                    const team_pattern = document.getElementById('pattern-field').value;



                    if (!teamName || !teamDescription || !team_pattern) {



                        alert("Please fill in all fields.");

                        return; // Stop execution if validation fails

                    }



                    // Optionally show a loading indicator

                    // PageLoader.show();



                    // Build the action URL (or get it dynamically from a data attribute as mentioned earlier)

                    const actionUrl = `team/${teamId}/update/profile`; // Assuming teamId is already set

                    console.log(team_pattern, "ghj");

                    // Uconsole.log(form.action,"ghj");pdate form action dynamically if needed

                    form.action = actionUrl;





                    // Prepare form data for submission

                    const formData = new FormData();

                    formData.append('team_id', teamId);

                    formData.append('team_name', teamName);

                    formData.append('team_description', teamDescription);

                    formData.append('team_pattern', team_pattern);



                    // Submit form via Fetch (AJAX)

                    fetch(form.action, {

                            method: 'POST', // Ensure it's the correct HTTP method

                            body: formData, // Include the form data

                            headers: {

                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'), // CSRF token

                                'Accept': 'application/json',
                            },

                        })

                        .then(response => response.json()) // Handle the response

                        .then(data => {

                            // Handle the success response here

                            if (data.success) {

                                // Show notification using Toastr

                                ToastView.notif("Notification", data
                                    .message
                                    ); // You can replace this with success/error or custom notification

                                PageLoader.close();

                                ModalView.close('updateTeam');

                                location.reload();



                            } else {

                                ToastView.notif("Notification", data.message);



                            }

                        })

                })

            });

            ModalView.show('updateTeam');



        }

        function deleteTeamModal(button) {

            ModalView.onShow("deleteTeam", (modal) => {

                const teamId = button.getAttribute('data-id');

                document.getElementById('input-text-team_id').value = teamId;



            });

            ModalView.show('deleteTeam');

        }





        ModalView.onShow("acceptInvite", async (modal, payload) => {

            PageLoader.show();

            const header = modal.querySelector("#header-overlay");

            const teamImage = modal.querySelector("#team-image");

            const ownerImage = modal.querySelector("#owner-image");

            const teamInitial = modal.querySelector("#team-initial");

            const teamDescription = modal.querySelector("#team-description");

            const ownerInitial = modal.querySelector("#owner-initial");

            const teamName = modal.querySelector("#team-name");

            const ownerName = modal.querySelector("#owner-name");

            const btnYes = modal.querySelector("#btn-yes");

            const btnNo = modal.querySelector("#btn-no");

            const response = await ServerRequest.get(

                `{{ url('team/${payload.team_id}/invite/' . Auth::user()->id) }}`)

            header.classList.add(`bg-pattern-${response.data.team_pattern}`);

            teamDescription.textContent = response.data.team_description;

            teamName.textContent = response.data.team_name;

            ownerName.textContent = response.data.owner_name;

            teamInitial.textContent = response.data.team_initial;

            ownerInitial.textContent = response.data.owner_initial;

            teamImage.src = response.data.team_image;

            ownerImage.src = response.data.owner_image;

            btnYes.formAction = response.data.accept_url;

            btnNo.formAction = response.data.reject_url;

            if (!response.data.team_image) teamImage.style.display = "none";

            if (!response.data.owner_image) ownerImage.style.display = "none";

            modal.querySelectorAll("a").forEach(

                link => link.addEventListener("click", () => PageLoader.show())

            );

            modal.querySelectorAll("button[type='submit']").forEach(

                form => form.addEventListener("click", () => PageLoader.show())

            );

            PageLoader.close();

        });



        ModalView.onShow("inviteMember", (modal) => {

            const addBtn = modal.querySelector('#add-btn');

            const saveBtn = modal.querySelector('#save-btn');

            const emailField = modal.querySelector('#input-text-inv-email');

            const countrySelect = modal.querySelector('#input-text-teamsa');

            const inviteList = modal.querySelector('#invite-container');

            emailField.addEventListener("keypress", () => {

                if (event.key === "Enter") {

                    event.preventDefault();

                    handleInsert();

                }

            });



            addBtn.addEventListener('click', handleInsert);

            saveBtn.addEventListener('click', () => PageLoader.show());

            function handleInsert() {

                const email = emailField.value.trim();

                const selectedValue = countrySelect.value;

                const selectedText = countrySelect.options[countrySelect.selectedIndex].text;

                if (email === "") return;

                if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) return;



                emailField.value = "";

                const id = DOM.newid();

                let emailtag = DOM.create(`

                    <div class="flex gap-2" id="email-tag-${id}">

                        <input type="hidden" name="emails[]" value="${email}">

                           <input type="hidden" name="id[]" value="${selectedValue}">

                        <p class="flex-grow overflow-hidden truncate">

                            ${email}

                        </p>

                        <p class="flex-grow overflow-hidden truncate">

                            ${selectedText}

                        </p>

                        <button onclick="DOM.find('#email-tag-${id}')?.remove()" type="button" class="flex items-center justify-center w-full gap-2 px-6 py-1 text-base font-bold border-4 border-black rounded-full bg-white text-black hover:bg-black hover:text-white !border-2 !text-sm w-min !px-4">

                                <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Free 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. --><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path></svg>

                        </button>

                    </div>

                    `);



                inviteList.append(emailtag);

            }



        })



        ModalView.onShow('updateTeam', (modal) => {

            modal.querySelectorAll("a").forEach(

                link => link.addEventListener("click", () => PageLoader.show())

            );



            modal.querySelectorAll("form[method]").forEach(

                form => form.addEventListener("submit", () => PageLoader.show())

            );

        });
    </script>
@endPushOnce
