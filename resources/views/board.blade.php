@extends('layout.pages')

@section('app-header')
    <div class="flex items-center text-white gap-2">
        <h1 class="text-xl font-bold">Board: </h1>
        <p class="text-xl" id="board-title">{{ $board->name }}</p>
    </div>
@endsection

<!-- mindrelaxation7860@gmail.com -->
@section('app-side')
<div id="menu" class="flex flex-col items-center justify-start w-full">

    <a data-role="menu-item" href="{{ route('setting') }}"
        class="flex items-start justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'setting' ? 'bg-neutral-500' : 'hover:bg-neutral-500' }}">
        <x-fas-gear class="w-6 h-6" />
        <p class="text-lg font-normal"> Setting </p>
    </a>

    <a data-role="menu-item" href="{{ route('home') }}"
        class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-500' : 'hover:bg-neutral-500 ' }}">
        <x-fas-cube class="w-6 h-6" />
        <p class="text-lg font-normal"> Team </p>
    </a>
    <a data-role="menu-item" href="{{ route('viewTeam', ['team_id' => $team->id]) }}"
    class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-500' : 'hover:bg-neutral-500 ' }}">
    <x-fas-backward class="w-6 h-6" />
    <p class="text-lg font-normal">Back To Board </p>
</a>
       

        @if (Auth::user()->id == $owner->id)
        <div data-role="menu-item"  onclick="ModalView.show('updateBoard')"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer my-2 rounded-xl select-none hover:bg-neutral-500">
            <x-fas-pen class="w-6 h-6" />
            <p class="text-lg font-normal"> Edit </p>
        </div>

        <div data-role="menu-item"  onclick="ModalView.show('deleteBoard')"
        class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none hover:bg-neutral-500">
        <x-fas-trash class="w-6 h-6" />
        <p class="text-lg font-normal"> Delete </p>
    </div>
    <a data-role="menu-item" href="{{route('setting')}}"
    class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none hover:bg-neutral-500">
    <x-fas-gear class="w-6 h-6" />
   
    <p class="text-lg font-normal"> Setting </p>
</a> 
{{-- <a data-role="menu-item" href="{{route('setting')}}"
class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none hover:bg-neutral-500">

<x-fas-arrow-right-from-bracket class="w-6 h-6 transform rotate-180" />
<p class="text-lg font-normal"> leave board </p>
</a> --}}
                
           
        @endif
    </div>
@endsection


@section('content')
    <x-card teamid="{{ $board->team_id }}"/>
    <x-column teamid="{{ $board->team_id }}" :isowner="Auth::user()->id == $owner->id"/>
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

    {{-- modal declaration --}}
    @if (Auth::user()->id == $owner->id)
        <template is-modal="updateBoard">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Edit Board</h1>
                <hr>
                <form action="{{ route('updateBoard', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}"
                    method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="board_id" value="{{ $board->id }}">
                    <x-form.text name="board_name" label="Board's Name" value="{{ $board->name }}" required />

                    <div class="flex flex-col w-full gap-2" x-data="{ selected: '{{ $board->pattern }}' }">
                        <label class="pl-6">Board's Color</label>
                        <input type="hidden" id="pattern-field" name="board_pattern" x-bind:value="selected">
                        <div
                            class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                            @foreach ($patterns as $pattern)
                                <div x-on:click="selected = '{{ $pattern }}'"
                                    x-bind:class="(selected == '{{ $pattern }}') ? 'border-black' : 'border-gray-200'"
                                    class="{{ $pattern == $board->pattern ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-grad-{{ $pattern }} hover:border-black">
                                    <div x-bind:class="(selected == '{{ $pattern }}') ? 'opacity-100' : 'opacity-0'"
                                        class="flex items-center justify-center w-full h-full">
                                        <x-fas-circle-check class="w-6 h-6" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <x-form.button class="mt-4" type="submit" primary>Save</x-form.button>
                </form>
            </div>
        </template>

        <template is-modal="deleteBoard">
            <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
                action="{{ route('deleteBoard', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}">
                @csrf
                <input type="hidden" name="board_id" value="{{ $board->id }}">
                <p class="mb-6 text-lg text-center"> Are you sure you want to delete this board?</p>
                <div class="flex gap-6">
                    <x-form.button type="submit">Yes</x-form.button>
                    <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
                </div>
            </form>
        </template>
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

<script>





    // const socket = io("http://localhost:3000"); // Use correct port
    const socket = io("http://task.wbsoftech.com/", {
        path: "/socket.io",
        transports: ["websocket", "polling"]
});
    console.log(io,"ggfg");
    socket.on("connect", () => {
        console.log("✅ Connected to Socket.io server");
    });
    socket.on("board-refresh", () => {
        console.log("🔄 Board refreshed");
        board.refresh(); // Calls your existing refresh() function
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
                        PageLoader.show();
                        this.ref.innerHTML = "";
                        console.log(error);
                    });
            }
        }

        const board = new Board(@json($board));
        @if (Auth::user()->id == $owner->id)
            ModalView.onShow('deleteBoard', (modal) => {
                modal.querySelectorAll("form[action][method]").forEach(
                    form => form.addEventListener("submit", () => PageLoader.show())
                );
            });

            ModalView.onShow('updateBoard', (modal) => {
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
    </script>
@endPushOnce
