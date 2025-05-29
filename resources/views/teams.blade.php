@extends('layout.page')



@pushOnce('metasa')

<meta name="csrf-token" content="{{ csrf_token() }}">

@endPushOnce

{{-- @section('app-header')

    <h1 class="text-xl font-bold">Teams</h1>

@endsection --}}



@section('app-side')

    <div class="flex flex-col gap-1 px-8 pl-4 mt-2">



            <div data-role="menu-item" onclick="ModalView.show('createTeam')"

            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'hover:bg-neutral-500' : 'hover:bg-neutral-500' }}">

                <x-fas-cube class="w-6 h-6" />

                <p class="text-lg font-normal">  Add Team </p>

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

                    <x-form.select 

                    name="teamsa" 

                    icon="fas-users"  



                >

                    <option value="">Select Team</option>

                     @foreach($teams as $team )

        <option value="{{ $team->id }}" >

            {{ $team->name}}

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
                    @csrf                    <input type="hidden" name="team_id", value="">         

                    <div class="flex flex-col w-full gap-2" id="invite-container">
                        {{-- <div class="flex gap-2" id="email-tag-1">
                            <input type="hidden" value="">
                            <p class="flex-grow overflow-hidden truncate">William@email.com</p>
                            <x-form.button outline type="button" action="DOM.find('#email-tag-1')?.remove()"
                                class="!border-2 !text-sm w-min !px-4">
                                <x-fas-trash class="w-6 h-6" />
                            </x-form.button>
                        </div> --}}
                    </div>
                </form>
                <x-form.button primary type="submit" id="save-btn" form="invite-members-form">Save</x-form.button>
            </div>
        </div>
    </template>



    <template is-modal="acceptInvite">
        <div class="flex flex-col w-full gap-4 p-4">
            <h1 class="text-3xl font-bold">Team Invitess</h1>
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

            <input type="hidden" name="team_id" id="input-text-team_id"  >

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

            <from method="POST" id="update-team-form"  class="flex flex-col gap-4">

               

                <input type="hidden" name="team_id"  id="input-text-team_id">

                    <x-form.text name="team_name" label="Team's Name"  required />

                    <x-form.textarea name="team_description" label="Team's Description"

                        required />

                    <div class="flex flex-col w-full gap-2" >

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

    <div class="flex flex-col w-full h-full gap-6 px-8 py-6 overflow-auto">

        <header class="w-full">

            <h2 class="ml-6 mb-5 text-4xl font-extrabold"> Teams</h2>



            <form class="flex items-center gap-4" id="search-form" action="{{ route('searchTeam') }}" method="GET">

                @csrf

                <x-form.text icon="fas-magnifying-glass" name="team_name" placeholder="Team's name"

                    value="{{ session('__old_team_name') }}" />

                {{-- <div class="h-full min-w-min">

                    <x-form.button type="submit" primary class="h-full">

                        <x-fas-magnifying-glass class="w-4 h-4" />Search

                    </x-form.button>

                </div> --}}

            </form>

        </header>



        @if (!$invites->isEmpty())

            <section class="flex flex-col gap-6">

                <header>

                    <h2 class="ml-6 text-2xl font-bold">Pending Invites</h2>

                </header>



                <hr>



                <div class="flex flex-wrap gap-x-8 gap-y-6">

                    @foreach ($invites as $team)

                        <div onclick="ModalView.show('acceptInvite', { team_id: '{{ $team->id }}'  })"

                            class="flex flex-col h-24 transition bg-white border border-gray-200 shadow-sm cursor-pointer select-none w-72 rounded-xl hover:shadow-2xl duartion-300">

                            <header class="h-4 bg-gray-200 rounded-tl-xl rounded-tr-xl"></header>

                            <article class="flex flex-col gap-1 px-4 py-2">

                                <h3 class="overflow-hidden font-semibold truncate text-bold">{{ $team->name }}</h3>

                                <p class="flex-grow w-full text-xs break-all line-clamp-2 text-ellipsis max-h-8 ">

                                    {{ $team->description }} </p>

                            </article>

                        </div>

                    @endforeach

                </div>

            </section>

        @endif



        <section class="flex flex-col gap-6">

            <header>

                <h2 class="ml-6 text-2xl font-bold">My Teams</h2>

            </header>



            <hr>



            <div class="flex flex-wrap gap-x-8 gap-y-6">

                @if ($teams->isEmpty())

                    <div onclick="ModalView.show('createTeam')"

                        class="flex flex-col items-center justify-center gap-2 text-gray-400 transition duration-300 bg-gray-100 shadow-md cursor-pointer select-none w-72 h-52 rounded-xl hover:shadow-2xl">

                        <x-fas-plus class="w-8 h-8" />

                        <p>Create Team</p>

                    </div>

                @endif

                @foreach ($teams as $team)

                                    <div

                        class="flex select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-40 w-72 hover:shadow-2xl bg-pattern-{{ $team->pattern }} overflow-hidden">

                        <div class="flex flex-row select-none  justify-end w-full px-4 py-5 opacity-0 hover:opacity-100"  style="background-color:rgb(64 64 64 / 52%)">

                            {{-- <x-avatar name="{{ $team->name }}" asset="{{ $team->image_path }}" class="h-12" /> --}}

                               

                                <div type="button" onclick="deleteTeamModal(this)" id="team-del-btn"   data-id="{{ $team->id }}"  class="p-2 text-white transition rounded-full hover:opacity-100 ">

                                    <svg class="w-[18px] h-[18px]" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2024 Fonticons, Inc. --><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"></path></svg>                    </div>

                                    <div type="button" onclick="openEditTeamModal(this)" id="team-upd-btn"

                                    data-id="{{ $team->id }}" 

        data-name="{{ $team->name }}" 

        data-description="{{ $team->description }}" 

        data-pattern="{{ $team->pattern }}" class="p-2 text-white transition rounded-full hover:opacity-100 ">

                                        <svg class="w-[18px] h-[18px]" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2024 Fonticons, Inc. --><path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"></path></svg>                    </div>

                            </div>

                           



                        <a href="{{ route('viewTeam', ['team_id' => $team->id]) }}" class="flex flex-col w-full h-24 gap-1 px-4 py-2 bg-white border-t border-t-gray-200">

                            <h3 class="overflow-hidden font-semibold truncate text-bold">{{ $team->name }}</h3>

                            <p class="flex-grow w-full text-xs break-all line-clamp-2 text-ellipsis max-h-8 ">

                            {{ $team->description }} </p>

                        </a>

                    </div>

                @endforeach

            </div>

        </section>

    </div>



@endsection



@pushOnce('page')

    <script>

        ModalView.onShow("createTeam", (modal) =>{

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

const teamId = document.getElementById('input-text-team_id').value; // Example of fetching a value



const teamName = document.getElementById('input-text-team_name').value;

const teamDescription = document.getElementById('textarea-team_description').value;

const team_pattern = document.getElementById('pattern-field').value;



if (!teamName || !teamDescription || !team_pattern ) {



    alert("Please fill in all fields.");

    return;  // Stop execution if validation fails

}



// Optionally show a loading indicator

// PageLoader.show();



// Build the action URL (or get it dynamically from a data attribute as mentioned earlier)

const actionUrl = `team/${teamId}/update/profile`;  // Assuming teamId is already set

console.log(team_pattern,"ghj");

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

    method: 'POST',  // Ensure it's the correct HTTP method

    body: formData,  // Include the form data

    headers: {

        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // CSRF token

    'Accept': 'application/json',},

})

.then(response => response.json()) // Handle the response

.then(data => {

    // Handle the success response here

    if (data.success) {

        // Show notification using Toastr

        ToastView.notif("Notification",data.message);  // You can replace this with success/error or custom notification

        PageLoader.close();

        ModalView.close('updateTeam');

        location.reload();



    } else {

        ToastView.notif("Notification",data.message); 

       

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

