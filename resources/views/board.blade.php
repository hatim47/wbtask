{{-- @php dump('VIEW: ' . __FILE__, $board->id ?? 'not set') @endphp --}}
@extends('layout.pages')
@push('metasa')
   <meta name="id" content="{{ Auth::id() }}">
@endpush

@section('app-header')
<div class="flex items-center justify-between  w-full px-6">
    <div class="flex items-center text-white gap-2">
        <h1 class="text-xl font-bold">Board: </h1>
        {{-- {{dd($board)}} --}}
<div x-data="{
         title: @js($board->name ?? ''),
        original: @js($board->name ?? ''),
        editing: false,
        csrfToken: @js(csrf_token()),
        saveUrl: @js(isset($board) ? route('updateBoard', [$board->team_id, $board->id]) : ''),
        init() {
           if (!this.saveUrl) {
                console.error('Missing board data!');
                this.title = 'Error: Board not loaded';
            }
        console.log('Board ID:', {{ $board->id ?? 'null' }});
        },
        enableEdit() {
            this.editing = true;
            setTimeout(() => this.$refs.input.focus(), 50);
        },
        cancel() {
            this.title = this.original;
            this.editing = false;
        },
        save() {
            if (this.title === this.original) return this.cancel();
            fetch(this.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({board_name: this.title})
            })
            .then(res => res.ok ? this.original = this.title : Promise.reject())
            .catch(() => this.title = this.original)
            .finally(() => this.editing = false);
        }
    }"
    class="text-xl font-semibold cursor-pointer">
    <span x-show="!editing" @click="enableEdit" x-text="title" id="board-title"
          class="hover:border-gray-100 hover:border px-2 py-1 rounded transition"></span>
    <input x-show="editing" x-model="title" x-ref="input"
           @blur="save" @keydown.enter="save" @keydown.escape="cancel"
           class="border-b border-blue-500 outline-none w-full px-2 text-black py-1 bg-gray-50">
</div> 
    </div>
       <div class="flex items-center ">
  <!-- Circle 1 (HN) -->
  @foreach ($members as $index => $member)
    <div class="relative flex items-center gap-4 {{ $index > 0 ? '-ml-2' : '' }}">
        <x-avatar name="{{ $member->name }}" asset="{{ $member->image_path }}"
            class="!flex-shrink-0 !flex-grow-0 w-8 z-10" />

        @if ($member->pivot->status === 'Owner')
            <!-- Small crown icon positioned on top-right -->
            <div class="absolute -top-1 -right-1 z-10 bg-white rounded-full p-0.5 shadow">
                <svg class="w-3 h-3  text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 3l2.39 4.84 5.35.78-3.87 3.77.91 5.31L10 15.89l-4.78 2.81.91-5.31L2.26 8.62l5.35-.78L10 3z" />
                </svg>
            </div>
        @endif
    </div>
@endforeach
  <!-- Circle 2 (H) -->
@if ($owner->contains('user_id', Auth::user()->id))
  <!-- Share Button -->
  <button onclick="ModalView.show('sharebtn')" class="flex items-center space-x-1 px-3 mx-3 py-1 bg-gray-200 rounded border text-sm font-medium text-gray-700 hover:bg-white">
    <svg xmlns="http://www.w3.org/2000/svg" id="Filled" viewBox="0 0 24 24" width="18" height="18"><path d="M17,11H13V7a1,1,0,0,0-2,0v4H7a1,1,0,0,0,0,2h4v4a1,1,0,0,0,2,0V13h4a1,1,0,0,0,0-2Z"/></svg>
    <span>Share</span>
  </button>
@endif
  <!-- Ellipsis Icon -->
  <button class="ml-1 p-1">
    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
      <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
    </svg>
  </button>
{{-- {{dd($user);}} --}}
<div class="relative inline-block text-left">
  <!-- Avatar Button -->
  <button onclick="toggleDropdown()" class="focus:outline-none">
   <x-avatar name="{{ $user->name }}" asset="{{ $user->image_path }}"
            class="!flex-shrink-0 !flex-grow-0  border-white border w-10 z-10" />
  </button>


  <div
    id="dropdown"
    class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 z-50"
  >
    <div class="p-4">
      <p class="text-sm font-medium text-gray-800">{{$user->name}}</p>
      <p class="text-xs text-gray-500">{{$user->email}}</p>
    </div>
    <hr />
   
      <a
       href="{{ route('doLogout') }}"
        class="w-full flex text-left text-gray-800 px-3 py-2 rounded-md text-sm font-medium"
      >
        Log out
      </a>
   
  </div>
</div>
</div>

</div>

@endsection

<!-- mindrelaxation7860@gmail.com -->
@section('app-side')
<div id="menu" class="flex flex-col items-center justify-start w-full">
        {{-- {{dd($teams_info);}} --}}
 <a data-role="menu-item" href="{{ route('viewHome') }}"
       class="flex items-center justify-start w-full gap-3 px-6 py-2  text-white cursor-pointer  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
       <p class=" "> Home </p>
    </a> 
  <a data-role="menu-item" href="{{ route('viewTeam', ['team_id' => $team->id]) }}"
       class="flex items-center justify-start w-full gap-3 px-6 py-2  text-white cursor-pointer  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
       <p class=" "> Board </p>
    </a>    
 <a data-role="menu-item" href="{{ route('viewWorkspace', ['team_id' => $team->id]) }}"
        class="flex items-center justify-start w-full gap-3 px-6 py-2  text-white cursor-pointer  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
        <p class=" "> Member   </p>
    </a>  
    
 <a data-role="menu-item" href="{{ route('setting') }}"
        class="flex items-center justify-start w-full gap-3 px-6 py-2  text-white cursor-pointer  select-none {{ Route::currentRouteName() == 'Setting' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
       
        <p class=" "> Setting   </p>
    </a>                     
    </div>

    <div class="flex flex-col gap-1  pl-4 mt-2" >
  @foreach ($assign_board as $boards)

                            <a href="{{ route('board', ['board_id' => $boards->id, 'team_id' => $boards->team_id]) }}"  class="flex gap-3  px-6 py-2 cursor-pointer select-none transition duration-300  border-gray-200  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
                                    <div
                                        class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-6 w-6 hover:shadow-2xl bg-grad-{{ $boards->pattern }} overflow-hidden">
                                        </div>
                                    <h3 class="overflow-hidden text-white truncate ">{{ $boards->name }}</h3>

                            </a>
                        @endforeach
 </div>   


@endsection



@section('content')

    <x-card teamid="{{ $board->team_id }}"/>
    <x-column teamid="{{ $board->team_id }}" :isowner="$owner->contains('user_id', Auth::user()->id)"/>
  
    <div id="board-background"
        class="w-full h-full min-h-full overflow-hidden overflow-x-scroll bg-grad-{{ $board->pattern }}">
        <section class="flex h-full min-w-full gap-4 p-4">
            <div class="flex h-full gap-4" id="column-container" data-role="board" data-id="{{ $board->id }}">
            </div>
            <div onclick="ModalView.show('addCol')"
                class="flex flex-col flex-shrink-0 gap-2 px-4 py-4 transition shadow-lg cursor-pointer select-none h-min w-72 rounded-3xl bg-slate-100 hover:scale-105 hover:relative">
                <div class="flex items-center justify-between gap-4 text-black">
                    <p>Add...</p>
                    <x-fas-plus class="w-4 h-4" />
                </div>

            </div>
        </section>
    </div>

    
<template is-modal="sharebtn">
<div class="  bg-black bg-opacity-50 flex items-center justify-center ">
    <div class="bg-white   w-full  ">
        <h2 class="text-lg font-semibold mb-4">Share board</h2>

        <!-- Invite Form -->
       <div 
    x-data="inviteForm()" 
    class="flex items-center gap-2 mb-4"
>
    <input type="email" x-model="email" placeholder="Email address or name"
           class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">

    <select x-model="role" class="border rounded-lg px-2 py-1 text-sm">
        <option value="Member">Member</option>
        <option value="Owner">Owner</option>
    </select>

    <button 
       @click.prevent="submitInvite" 
        class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2 text-sm"
    >
        Share
    </button>

     <div x-show="message" x-text="message"
         :class="statusClass"
         x-transition
       class="text-sm px-4 fixed z-50 line-clamp-2 text-wrap w-[500px] py-2 rounded mt-2"
         x-cloak> Unexpected server response</div>
</div>

        <!-- Public Link Section -->
        {{-- <div class="bg-gray-100 p-3 rounded-lg flex justify-between items-center text-sm mb-4">
            <div>
                Anyone with the link can join as a member<br>
                <a href="#" class="text-blue-600 underline text-xs">Copy link</a> ·
                <a href="#" class="text-blue-600 underline text-xs">Delete link</a>
            </div>
            <button class="text-sm border rounded px-2 py-1">Change permissions</button>
        </div> --}}

        <!-- Tabs -->
        <div class="flex border-b mb-2 text-sm">
            <button class="px-3 py-2 font-medium border-b-2 border-blue-500 text-blue-600">Board members <span class="ml-1 text-xs bg-gray-200 rounded-full px-2">2</span></button>
            <button class="px-3 py-2 text-gray-500">Join requests</button>
        </div>

        <!-- Member List -->
        <div class="space-y-3 max-h-60 overflow-y-auto">
            @foreach ($members as $member)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <x-avatar :name="$member->name" :asset="$member->image_path" class="w-10 h-10" />
                        <div>
                            <div class="font-medium">{{ $member->name }} 
                                @if ($member->id === auth()->id()) <span class="text-sm text-gray-500">(you)</span> @endif
                            </div>
                            <div class="text-sm text-gray-500">{{ '@' . $member->username }} • Workspace admin</div>
                        </div>
                    </div>
                  <div x-data="roleUpdater('{{ $member->id }}', '{{ $member->pivot->status }}')">
    <select x-model="role"
            @change="updateRole"
            class="border rounded px-2 py-1 text-sm">
        <option value="Owner">Owner</option>
        <option value="Member">Member</option>
    </select>

    <!-- optional status message -->
    <p x-text="message" class="text-xs mt-1 text-green-600" x-show="message"></p>
</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

 </template>











    {{-- modal declaration --}}
    @if ($owner->contains('user_id', Auth::user()->id))
        {{-- <template is-modal="updateBoard">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Edit Board</h1>
                <hr>
                <form action="{{ route('updateBoard', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}"
                    method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="board_id" value="{{ $board->id }}">
                    <x-form.text name="board_name" label="Board's Name" value="{{ $board->name }}" required />

                    <x-form.button class="mt-4" type="submit" primary>Save</x-form.button>
                </form>
            </div>
        </template> --}}

        {{-- <template is-modal="deleteBoard">
            <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
                action="{{ route('deleteBoard', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}">
                @csrf
                <input type="hidden" name="board_id" value="{{ $board->id }}">
                <p class="mb-6 text-lg text-center">Are you sure you want to delete this board?</p>
                <div class="flex gap-6">
                    <x-form.button type="submit">Yes</x-form.button>
                    <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
                </div>
            </form>
        </template> --}}
    @endif
    <template is-modal="addCol">
        <div class="flex flex-col w-full gap-4 p-4">
            <form class="flex flex-col gap-4">
                <x-form.text name="column_name" label="Column's Name" required />
                <x-form.button class="mt-4" type="submit" primary>Add</x-form.button>
            </form>
        </div>
    </template>
   
@endsection


@pushOnce('head')
<style>
    .card-avatar {
    /* position: absolute; */
    bottom: 5px;
    right: 5px;
    width: 24px;
    height: 24px;
    /* background-color: #29b6f6;  */
    color: white;
    font-size: 10px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
.card-footer {
    display: flex;
    justify-content: space-between;
    padding: 5px 10px;
    font-size: 14px;
    color: #666;
}

.icon-container {
    display: flex;
    gap: 10px;
    align-items: center;
}

.icon {
    display: flex;
    align-items: center;
    gap: 5px;
}
      /* Notification Badge */
.notification-badge {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 24px;
    background-color: #d32f2f; /* Red background */
    color: #fff;
    font-size: 12px;
    
    border-radius: 6px;
    padding: 0;
    cursor: pointer;
}

        .notification-badge svg {
            width: 14px;
            height: 14px;
            fill: white;
            margin-right: 3px;
        }

        /* Hide notification if count is zero */
        .hidden {
            display: none;
        }
</style>
@endPushOnce
@pushOnce('page')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.1/echo.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/colorthief@2.6.0/dist/color-thief.min.js"></script>
<script>




const boardId = @json($board->id);
    {{--  const socket = io("http://localhost:3000"); --}}
    const socket = io("https://task.wbsoftech.com/", {
        path: "/socket.io",
        transports: ["websocket", "polling"]
});
    console.log(io,"ggfg");
    socket.on("connect", () => {
        console.log("✅ Connected t00000000o Socket.io server"); 
          socket.emit("join-board", boardId); 
    });
    socket.on("board-refresh", () => {
        console.log("🔄 Board refreshed");
        board.refresh(); // Calls your existing refresh() function
    });
   socket.on("label-createddd", (label) => {
        console.log("🔄 Label created",label);
        board.refresh();
    });


        class Board {
                constructor(boardJson) {
                this.id = boardJson.id;
                this.DRAG_MODE = null;
                this.IS_EDITING = false;
                this.ref = DOM.find("#column-container");
                this.background = DOM.find("#board-background");
                this.title = DOM.find("#board-title");

                this.columnList = [];
                for (const column of boardJson.columns) {
                    this.addCol(
                        column.id,
                        column.name,
                        column.cards,                        
                    )
                }     
                this.ref.addEventListener("dragover", (e) => {
                    e.preventDefault();
                    let currentDraggingCol = DOM.find("div[data-role='column'].is-dragging");
                    if (currentDraggingCol == null) return;
                    let closestBottomColFromMouse = null;
                    let closestOffset = Number.NEGATIVE_INFINITY;
                    let staticCols = this.ref.querySelectorAll(
                        ":scope > div[data-role='column']:not(.is-dragging)");

                    //calculate closestTask
                    staticCols.forEach((card) => {
                        let {
                            left,
                            right
                        } = card.getBoundingClientRect();

                        let offset = event.clientX - ((left + right) / 2);

                        if (offset < 0 && offset > closestOffset) {
                            closestOffset = offset;
                            closestBottomColFromMouse = card;
                        }
                    });
                    if (closestBottomColFromMouse) {
                        this.ref.insertBefore(
                            currentDraggingCol,
                            closestBottomColFromMouse
                        );
                       
                    } else {
                       
                        this.ref.appendChild(currentDraggingCol);
                    }
                })
            }

            addCol(id, name, cards) {
                let column = new Column(this, id, name, cards);
                this.columnList.push(column);
                column.mountTo(this);
            }
            refresh() {
                if (this.IS_EDITING) return;
                ServerRequest.get(`{{ route('boardJson', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}`)
                    .then(response => {
                        if (this.IS_EDITING) return;
                        this.IS_EDITING = true;
                        this.columnList = [];
                        const json = response.data;
                            console.log("json.name",json)
                        //update board
                        this.title.textContent = json.name;
                        if (!this.background.classList.contains("bg-grad-" + json.pattern)) {
                            this.background.classList.remove(
                                ...Array.from(this.background.classList.entries())
                                .map(([, c]) => c)
                                .filter(c => c.startsWith('bg-grad')));
                            this.background.classList.add("bg-grad-" + json.pattern);
                        }
                        //update columns and cards
                        this.ref.innerHTML = "";
                        for (const column of json.columns) {
                            this.addCol(
                                column.id,
                                column.name,
                                column.cards,
                            )
                        }
                        this.IS_EDITING = false;
                        console.log("[BOARD]: refreshed...");
                    }).catch((error) => {
                        console.log("ERROR");
                      
                        this.ref.innerHTML = "";
                        console.log(error);
                    });
            }

        }
        const board = new Board(@json($board));
        @if ($owner->contains('user_id', Auth::user()->id))
            ModalView.onShow('deleteBoard', (modal) => {
                modal.querySelectorAll("form[action][method]").forEach(
                    form => form.addEventListener("submit", () => PageLoader.show())
                );
            });
           
        @endif

        ModalView.onShow("addCol", (modal) => {
            board.IS_EDITING = true;
            modal.querySelector("#input-text-column_name").focus();
            modal.querySelector("form").addEventListener("submit", (e) => {
                e.preventDefault();
                const colName = modal.querySelector("#input-text-column_name").value.trim();
                if (colName === "") {
                    ModalView.close();
                    board.IS_EDITING = false;
                    return;
                }

                const column = new Column(board, null, colName);
                column.mountTo(board);
                ModalView.close();
                ServerRequest.post(
                    `{{ route('addCol', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}`, {
                        board_id: `{{ $board->id }}`,
                        column_name: colName
                    }).then(response => {
                    column.setId(response.data.id);
                    socket.emit("board-action"); 
                }).then(response => {
                    board.IS_EDITING = false
                });
            });
        });

        @if ($errors->any())
            ToastView.notif("Warning", "{{ $errors->first() }}");
        @endif


function inviteForm() {
    return {
        email: '',
        role: 'Member',
        message: '',
        statusClass: '',

        async submitInvite() {
            this.message = '';
            try {
                const response = await fetch('{{ route("invite.user", ["team_id" => $team->id, "board_id" => $board->id]) }}', {
                    method: 'POST',
                    headers: {
                         'Content-Type': 'application/json',
  'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        invitemail: this.email,
                        role: this.role
                    })
                });

                 const text = await response.text();
                let data;

                try {
                    data = JSON.parse(text);
                } catch (e) {
                    throw new Error("Unexpected server response.");
                }

                if (!response.ok) {
                    throw new Error(data.message || 'Something went wrong');
                }

                this.message = data.message || 'User invited successfully!';
                this.statusClass = 'bg-white text-green-800 border border-green-300';

                // Optionally emit socket event:
               

            } catch (error) {
                this.message = error.message + 'Error occurredError occurredError occurredError occurredError occurred' || 'Error occurred.';
                this.statusClass = 'bg-white text-red-800 border border-red-300';
            }

            // Auto hide after 4 seconds
            setTimeout(() => {
                this.message = '';
                this.statusClass = '';
            }, 4000);

        this.board.refresh();  


        }
    };
}

function roleUpdater(userId, initialRole) {
    return {
        role: initialRole,
        userids : userId,
        message: '',

        async updateRole() {
            this.message = '';
            try {
               const response = await fetch('{{ route("update.role", ["team_id" => $team->id, "board_id" => $board->id ]) }}', {
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ role: this.role ,userids: this.userids })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to update role');
                }

                this.message = data.message || 'Role updated!';
                setTimeout(() => this.message = '', 3000);
            } catch (err) {
                this.message = 'Error updating role';
                setTimeout(() => this.message = '', 3000);
            }
        }
    }
}
  function toggleDropdown() {
    const dropdown = document.getElementById("dropdown");
    dropdown.classList.toggle("hidden");
  }

  function handleLogout() {
    // Example: clear token and redirect
    localStorage.removeItem("authToken");
    alert("Logged out!");
    window.location.href = "/login";
  }

  // Optional: close dropdown when clicking outside
  window.addEventListener("click", function (e) {
    const dropdown = document.getElementById("dropdown");
    if (!e.target.closest("button") && !e.target.closest("#dropdown")) {
      dropdown.classList.add("hidden");
    }
  });
function initializeBoardTitleEditor() {
    const componentDefinition = {
        title: '',
        original: '',
        editing: false,
        csrfToken: '',
        saveUrl: '',

        init(params) {
            this.title = params.title;
            this.original = params.title;
            this.csrfToken = params.csrfToken;
            this.saveUrl = params.saveUrl;
            this.$watch('title', () => {});
        },

        enableEdit() {
            this.editing = true;
            setTimeout(() => this.$refs.input.focus(), 50);
        },

        cancel() {
            this.title = this.original;
            this.editing = false;
        },

        save() {
            if (this.title === this.original || !this.title.trim()) {
                return this.cancel();
            }

            const headers = new Headers();
            headers.append('Content-Type', 'application/json');
            headers.append('X-CSRF-TOKEN', this.csrfToken);

            fetch(this.saveUrl, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ board_name: this.title }),
                credentials: 'same-origin'
            })
            .then(response => response.ok ? this.original = this.title : Promise.reject())
            .catch(() => this.title = this.original)
            .finally(() => this.editing = false);
        }
    };

    // Check if Alpine is available
    if (typeof Alpine !== 'undefined') {
        // Check if component already exists
        if (!Alpine.data('boardTitleEditor')) {
            Alpine.data('boardTitleEditor', () => componentDefinition);
        }
    } else {
        // Fallback for when Alpine loads later
        document.addEventListener('alpine:init', () => {
            Alpine.data('boardTitleEditor', () => componentDefinition);
        });
    }
}
function closeModal() {
  document.getElementById("modal-views").style.display = "none";
  board.IS_EDITING = false;
  let btnAddd = document.getElementById("btn-add");
  if (btnAddd) {
    btnAddd.style.display = "flex";
  }
}
const boardWrapper = document.getElementById("board-background");

let isDown = false;
let startX;
let scrollLeft;

boardWrapper.addEventListener("mousedown", (e) => {
  isDown = true;
  startX = e.pageX - boardWrapper.offsetLeft;
  scrollLeft = boardWrapper.scrollLeft;
});

boardWrapper.addEventListener("mouseleave", () => {
  isDown = false;
});

boardWrapper.addEventListener("mouseup", () => {
  isDown = false;
});

boardWrapper.addEventListener("mousemove", (e) => {
  if (!isDown) return;
  e.preventDefault();
  const x = e.pageX - boardWrapper.offsetLeft;
  const walk = (x - startX) * 2; // speed multiplier
  boardWrapper.scrollLeft = scrollLeft - walk;
});


// Initialize the component
initializeBoardTitleEditor();
    </script>
@endPushOnce
