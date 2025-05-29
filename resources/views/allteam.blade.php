@extends('layout.page')

@section('app-header')
    {{-- <div class="flex items-center gap-2">
        <h1 class="text-xl font-bold">Team:</h1>
        <p class="text-xl">{{ $team->name }}</p>
    </div> --}}
@endsection

@section('app-side')
    <div class="flex flex-col gap-1  pl-4 mt-2" >
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
       class="flex items-center justify-start w-full gap-3 px-6 py-2  text-gray-600 cursor-pointer  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
       <p class=" "> Board </p>
    </a>                  </li>
                  <li>
 <a data-role="menu-item" href="{{ route('viewWorkspace', ['team_id' => $info['team']->id]) }}"
        class="flex items-center justify-start w-full gap-3 px-6 py-2  text-gray-600 cursor-pointer  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
       
        <p class=" "> Member   </p>
    </a>                  </li>
                 
            </ul>
        
<hr class=" border-gray-200"> 
        
       {{-- <div class="flex items-center justify-start w-full font-bold px-6 py-1  text-gray-500 cursor-pointer  select-none"> {{ $info['team']->name }} </div> --}}
   </div>
@endforeach
     
 
    </div>
<div class="flex flex-col gap-1  pl-4 mt-2" >
@foreach ($teams_info as $info)

  @foreach ($info['boards'] as $board)

                            <a href="{{ route('board', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}"  class="flex gap-3  px-6 py-2 cursor-pointer select-none transition duration-300  border-gray-200  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
                                    <div
                                        class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-6 w-6 hover:shadow-2xl bg-grad-{{ $board->pattern }} overflow-hidden">
                                        </div>
                                    <h3 class="overflow-hidden  truncate ">{{ $board->name }}</h3>

                            </a>
                        @endforeach

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


        {{-- <template is-modal="createBoard">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Create Board</h1>
                <hr>
                <form action="{{ route('createBoard', ['team_id' => $team->id]) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                    <x-form.text name="board_name" label="Board's Name" required />
                    <div class="flex flex-col w-full gap-2" x-data="{ selected: '{{ $backgrounds[0] }}' }">
                        <label class="pl-6">Board's Color</label>
                        <input type="hidden" id="pattern-field" name="board_pattern" x-bind:value="selected">
                        <div
                            class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                            @foreach ($backgrounds as $pattern)
                                <div x-on:click="selected = '{{ $pattern }}'"
                                    x-bind:class="(selected == '{{ $pattern }}') ? 'border-black' : 'border-gray-200'"
                                    class="{{ $pattern == $backgrounds[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-grad-{{ $pattern }} hover:border-black">
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
        </template> --}}

        
    

        {{-- <template is-modal="inviteMember" class="bg-red-200">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Invite People</h1>
                <hr>
                <div class="flex flex-col gap-4">
                    <label for="input-text-inv-email">Enter email address</label>
                    <div class="flex gap-4">
                    <form method="POST" id="invite-members-form" action="{{ route('InviteMemberto') }}">
                      @csrf
                       <input type="hidden" name="team_id" value="{{ $team->id }}"> 
                        <x-form.text name="inv-email" icon="fas-user-plus" placeholder="name@email.com..." />                      
                        </form>
                    </div>
                    <x-form.button primary type="submit" id="save-btn" form="invite-members-form">Save</x-form.button>
                </div>
            </div>
        </template> --}}
       
        {{-- <template is-modal="deleteTeam">
            <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
                action="{{ route('doDeleteTeam', ['team_id' => $team->id]) }}">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                <p class="mb-6  text-center"> Are you sure you want to delete this team?</p>
                <div class="flex gap-6">
                    <x-form.button type="submit">Yes</x-form.button>
                    <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
                </div>
            </form>
        </template> --}}
   
        {{-- <template is-modal="leaveTeam">
            <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
                action="{{ route('doLeaveTeam', ['team_id' => $team->id]) }}">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                <p class="mb-6  text-center"> Are you sure you want to leave this team?</p>
                <div class="flex gap-6">
                    <x-form.button type="submit">Yes</x-form.button>
                    <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
                </div>
            </form>
        </template> --}}
    

    <div class="flex flex-col w-full h-full overflow-auto">
 

        <div class="flex flex-grow gap-8 px-6 py-4 overflow-hidden">
            {{-- page left section --}}
            <section class="flex flex-col flex-grow h-full gap-6">

                <section class="flex flex-col gap-4">
                    <header class="flex items-center justify-between gap-2 pl-1">
                        <h2 class="text-2xl font-bold">Boards</h2>
                         <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center space-x-2"
                          onclick="ModalView.show('inviteMember')">
        {{-- User Plus Icon --}}
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zm-6 9a5 5 0 0110 0v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2a5 5 0 015-5z"/>
        </svg>
        <span>Invite Workspace members</span>
    </button>
                    </header>

   


                    {{-- Search Bar --}}
                    {{-- <form class="flex items-center w-full gap-4" id="search-form" action="{{ route('searchBoard', ['team_id' => $team->id]) }}"
                        method="GET">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $team->id }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <x-form.search icon="fas-search" name="board_name" placeholder="Boards's name"
                            value="{{ session('__old_board_name') }}" />

                        
                    </form> --}}

                    
                     @foreach ($teams_info as $info)
 <template is-modal="createBoard{{ $info['team']->id }}">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Create Board</h1>
                <hr>
                <form action="{{ route('createBoard', ['team_id' =>$info['team']->id]) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $info['team']->id }}">
                    <x-form.text name="board_name" label="Board's Name" required />
                    <div class="flex flex-col w-full gap-2" x-data="{ selected: '{{ $backgrounds[0] }}' }">
                        <label class="pl-6">Board's Color</label>
                        <input type="hidden" id="pattern-field" name="board_pattern" x-bind:value="selected">
                        <div
                            class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                            @foreach ($backgrounds as $pattern)
                                <div x-on:click="selected = '{{ $pattern }}'"
                                    x-bind:class="(selected == '{{ $pattern }}') ? 'border-black' : 'border-gray-200'"
                                    class="{{ $pattern == $backgrounds[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-grad-{{ $pattern }} hover:border-black">
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





                       {{-- <h3 class="text-lg font-semibold text-gray-700">{{ $info['team']->name }} Boards</h3> --}}
 <div class="flex flex-wrap mt-2 gap-x-8 gap-y-6">
                       
        <h3 class="text-lg font-semibold text-gray-700">{{ $info['team']->name }} Boards</h3>
                        <hr class="w-full border-gray-200">
                       
                         
<div onclick="ModalView.show('createBoard{{ $info['team']->id }}')"
                                class="flex flex-col items-center justify-center gap-2 text-gray-600 transition duration-300 bg-gray-100 shadow-md cursor-pointer select-none w-72 h-32 rounded-xl hover:shadow-2xl">
                                <x-fas-plus class="w-8 h-8" />
                                <p>Create Board</p>
                            </div>
                        @foreach ($info['boards'] as $board)

                            <a href="{{ route('board', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}"
                                class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-32 w-72 hover:shadow-2xl bg-grad-{{ $board->pattern }} overflow-hidden">
                                <div class="flex-grow w-full p-4">

                                </div>
                                <article class="flex flex-col w-full gap-1 px-4 py-2 bg-white border-t border-t-gray-200">
                                    <h3 class="overflow-hidden font-semibold truncate text-bold">{{ $board->name }}</h3>
                                </article>
                            </a>
                        @endforeach 


                               
                    </div>
                         @endforeach
                </section>

            </section>

            {{-- page right section --}}
            {{-- <aside class="flex flex-col h-full gap-4 w-72">
                <h2 class="ml-4 text-2xl font-bold">Members</h2>                
                <div
                    class="flex flex-col flex-grow w-full gap-2 p-4 overflow-x-hidden overflow-y-auto border-2 border-gray-200 rounded-xl">
                    <div class="flex items-center gap-4">
                        <x-avatar name="{{ $owner->name }}" asset="{{ $owner->image_path }}"
                            class="!flex-shrink-0 !flex-grow-0 w-12" />
                        <p class="flex-grow truncate">{{ $owner->name }}</p>
                        <x-fas-crown class="w-6 h-6 text-yellow-400 !flex-shrink-0 !flex-grow-0" />
                    </div>

                    @foreach ($members as $member)
                        <div class="flex items-center gap-4">
                            <x-avatar name="{{ $member->name }}" asset="{{ $member->image_path }}"
                                class="!flex-shrink-0 !flex-grow-0 w-12" />
                            <p class="w-40 truncate">{{ $member->name }}</p>
                        </div>
                    @endforeach
                </div>
            </aside> --}}
        </div>

    </div>
@endsection

@pushOnce('page')
    <script>
          document.querySelectorAll('[data-collapse-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.collapseToggle);
            target.classList.toggle('hidden');
        });
    });
            ModalView.onShow('createBoard', (modal) => {
              
                modal.querySelectorAll("form[action][method]").forEach(
                    form => form.addEventListener("submit", () => PageLoader.show())
                );
            });


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
                        {{-- let response = await ServerRequest.post("{{ route('doChangeTeamImage', ['team_id' => $team->id]) }}", {
                            image: pfpBlobData,
                            team_id: `{{ $team->id }}`
                        }); --}}
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

            ModalView.onShow('manageMember', (modal) => {
                const searchMember = modal.querySelector("#input-text-member-name");
                const memberCards = modal.querySelectorAll(`div [data-role="member-card"]`);
                const saveBtn = modal.querySelector("#save-btn");

                memberCards.forEach(card => card.addEventListener("click", () => {
                    card.classList.toggle("bg-red-200");
                    card.classList.toggle("is-delete");
                }))

                searchMember.addEventListener("input", (event) => {
                    let search = event.target.value?.toLowerCase().trim();
                    memberCards.forEach(card => {
                        if (card.classList.contains("is-delete"))
                            return;
                        let name = card.dataset.name.toLowerCase();
                        let email = card.dataset.email.toLowerCase();
                        card.style.display = "flex";
                        if (!name.includes(search) && !email.includes(search))
                            card.style.display = "none";
                    });
                });

                saveBtn.addEventListener("click", async () => {
                    PageLoader.show();
                    let deleteEmailList = Array.from(memberCards)
                        .filter(card => card.classList.contains("is-delete"))
                        .map(card => card.dataset.email);

                    try {
                        {{-- await ServerRequest.post("{{ route('deleteTeamMember', ['team_id' => $team->id]) }}", {
                            team_id: `{{ $team->id }}`,
                            user_id: `{{ Auth::user()->id }}`,
                            emails: deleteEmailList,
                        }); --}}
                        location.reload();
                    } catch (error) {
                        console.log(error);
                        PageLoader.close();
                        ModalView.close();
                        let errorMessage = getResponseError(error);
                        if (errorMessage)
                            ToastView.notif("Warning", errorMessage);
                        else
                            ToastView.notif("Error", "Something went wrong please try again");
                    }
                })
            });

            ModalView.onShow("inviteMember", (modal) => {
              
                const saveBtn = modal.querySelector('#save-btn');
                const emailField = modal.querySelector('#input-text-inv-email');
                const inviteList = modal.querySelector('#invite-container');

                emailField.addEventListener("keypress", () => {
                    if (event.key === "Enter") {
                        event.preventDefault();
                        handleInsert();
                    }
                });               
                saveBtn.addEventListener('click', () => PageLoader.show());
            })
            
            {{-- @if (Auth::user()->id == $owner->id) --}}
            ModalView.onShow('deleteTeam', (modal) => {
                modal.querySelectorAll("form[action][method]").forEach(
                    form => form.addEventListener("submit", () => PageLoader.show())
                );
            });

            ModalView.onShow('updateTeam', (modal) => {
                modal.querySelectorAll("a").forEach(
                    link => link.addEventListener("click", () => PageLoader.show())
                );

                modal.querySelectorAll("form[action][method]").forEach(
                    form => form.addEventListener("submit", () => PageLoader.show())
                );
            });


        {{-- @endif --}}

        @if ($errors->any())
            ToastView.notif("Warning", "{{ $errors->first() }}");
        @endif
    </script>
@endPushOnce
