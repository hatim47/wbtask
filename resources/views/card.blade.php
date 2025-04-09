@extends('layout.page')

@section('app-header')
    {{-- <h1 class="text-xl font-bold">Card</h1> --}}
@endsection


@section('app-side')
    {{-- <div class="flex flex-col gap-1 px-8 pl-4 mt-2">
        <a class="w-full p-2 border-2 border-gray-200 cursor-pointer select-none rounded-xl"
            href="{{ route('board', ['team_id' => $team->id, 'board_id' => $board->id]) }}">
            <div class="flex items-center w-full gap-2">
                <div class="w-16 flex-shrink-0 border-2 border-black h-16 rounded-2xl bg-grad-{{ $board->pattern }}"></div>
                <article class="flex flex-col gap-2 text-sm">
                    <h2 class="font-bold">{{ $board->name }}</h2>
                    
                   
                </article>
            </div>
        </a>
    </div>   --}}
        <div class="flex flex-col items-center justify-start w-full">

          

            <div data-role="menu-item" href="{{ route('home') }}"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-500' : 'hover:bg-neutral-500 ' }}">
            <x-fas-cube class="w-6 h-6" />
                <p class="text-lg font-normal"> Team </p>
            </div> 
            <a data-role="menu-item" href="{{ route('viewTeam', ['team_id' => $team->id]) }}"
                class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-500' : 'hover:bg-neutral-500 ' }}">
                <x-fas-square class="w-6 h-6" />
                <p class="text-lg font-normal">Board </p>
            </a>
          {{-- {{dd(Auth::user())}} --}}
            @if ($workers->contains(Auth::user()))
           
            <div data-role="menu-item" onclick="ModalView.show('editCard')"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer my-2 rounded-xl select-none hover:bg-neutral-500">
            <x-fas-pen class="w-6 h-6" />
            <p class="text-lg font-normal"> Edit </p>
        </div>



      
            
                {{-- <div data-role="menu-item" onclick="ModalView.show('assignSelf')"
                    class="flex items-center w-full gap-3 px-6 py-2 text-black cursor-pointer select-none hover:bg-black hover:text-white">
                    <x-fas-plus class="w-4 h-4" />
                    <p> Assign Me </p>
                </div> --}}
            @endif
            @if (Auth::user()->id == $owner->id || Auth::user()->id == $chatOwner->user_id  )
         
            <div data-role="menu-item" onclick="ModalView.show('manageMember')"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer my-2 rounded-xl select-none hover:bg-neutral-500">
            <x-fas-user-plus class="w-6 h-6" />
            <p class="text-lg font-normal">Invite</p>
        </div> 
               
                <div data-role="menu-item" onclick="ModalView.show('deleteCard')"
                class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer my-2 rounded-xl select-none hover:bg-neutral-500">
                    <x-fas-trash class="w-4 h-4" />
                    <p class="text-lg font-normal">Delete</p>
                </div> 
            @endif
            <a data-role="menu-item" href="{{ route('setting') }}"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer rounded-xl select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-500' : 'hover:bg-neutral-500 ' }}">
            <x-fas-gear class="w-6 h-6" />
                <p class="text-lg font-normal"> Setting </p>
            </a> 
            @if (Auth::user()->id != $owner->id || Auth::user()->id != $chatOwner->user_id  )
            <div data-role="menu-item" onclick="ModalView.show('leaveCard')"
            class="flex items-center justify-start w-4/5 gap-3 px-6 py-2 text-sm text-white cursor-pointer my-2 rounded-xl select-none hover:bg-neutral-500">
            <x-fas-arrow-right-from-bracket class="w-6 h-6 transform rotate-180" />
            <p class="text-lg font-normal"> Quit Card </p>
        </div> 
            @endif
    
</div>
    
   
    <template is-modal="manageMember" class="bg-red-200">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Manage Members</h1>
                <hr>
                <div class="flex flex-col gap-4">
                    <x-form.text label="Team member" name="member-name" icon="fas-search" />

                    <section
                        class="flex justify-center w-full p-4 overflow-hidden overflow-y-auto border-2 border-black h-80 rounded-xl">
                        <div class="flex flex-wrap w-full max-w-[34rem] min-h-full gap-2">

                            @foreach ($members as $member)

                        



                                <div data-role="member-card" data-email="{{ $member->id }}"
                                    data-name="{{ $member->name }}"
                                    class="flex flex-col items-center justify-center w-32 gap-2 p-2 overflow-hidden border-2 border-gray-300 cursor-pointer select-none h-36 rounded-xl">
                                    <x-avatar name="{{ $member->name }}" asset="{{ $member->image_path }}"
                                        class="!flex-shrink-0 !flex-grow-0 w-12" />
                                    <p class="w-full h-8 text-xs font-bold text-center line-clamp-2">{{ $member->name }}
                                    </p>
                                    <p class="w-full h-8 text-xs font-normal text-center line-clamp-2">
                                        {{ $member->email }}
                                    </p>
                                 
                                </div>
                            @endforeach

                        </div>
                    </section>

                    <x-form.button primary type="button" id="save-btn">Add</x-form.button>
                </div>
            </div>
        </template>

@endsection

@section('content')

    <div class="flex flex-col w-full h-full">
        {{-- <header class="w-full h-24 flex items-center p-6 bg-pattern-{{ $team->pattern }} border-b border-gray-200">
        </header> --}}

        {{-- page content --}}
        <div class="flex flex-grow gap-8 px-2 py-4 overflow-hidden">

            {{-- left section --}}
            <section class="flex flex-col flex-grow h-full px-2 pr-6 overflow-x-hidden overflow-y-scroll">
                <article class="flex flex-row gap-2 justify-between pt-4  pb-6 border-b-2">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('board', ['team_id' => $team->id, 'board_id' => $board->id]) }}"
                     class="p-1 bg-white shadow-md cursor-pointer select-none rounded-full">
                     <x-fas-circle-left class="w-4 h-4" /> </a>
                        <h2 class="text-4xl font-bold">{{ $card->name }}</h2>
                    </div>
                    @php
                    
                    $i=1;
                @endphp
<div class="flex flex-row-reverse">
<a href="{{ route('viewmember', ['card_id' => $card->id,'team_id' => $team->id,'board_id' => $board->id]) }}" class="flex items-center flex-row">
    @foreach ($workers as $worker)
    @if ($worker->id == $owner->id || $worker->id == $chatOwner->user_id)
        <div class="flex items-center relative" style="left: -{{$i * 10}}px; z-index:{{$i -1}}; ">
            <div class="flex items-center flex-col-reverse">
                <x-avatar name="{{ $worker->name }}" asset="{{ $worker->image_path }}"
                    class="!flex-shrink-0 !flex-grow-0 w-6" />
                <x-fas-crown class="w-2 h-2 text-yellow-400 !flex-shrink-0 !flex-grow-0" />
            </div>
        </div>
        @else
        <div class="flex items-center relative " style="left: -{{$i * 10}}px; z-index:{{$i -1}};">
                <x-avatar name="{{ $worker->name }}" asset="{{ $worker->image_path }}"
                    class="!flex-shrink-0 !flex-grow-0 w-6 mt-2" />    
        </div>
@endif 
@php
    
$i++;
@endphp
    @endforeach
    </a>
<div  id="taskList" class=" flex flex-row pe-6">
                   
                 
</div>
</div>
</article>
<article class="flex flex-col gap-2 ">
                    

                    <div class="w-full h-32 p-2 px-6 mt-4 rounded ">
                        @if ($card->description)
                        <div class="flex items-start pb-1">  <x-fas-align-left class="w-6 h-6 pe-2" />Description </div>
                        <p class="text-base line-clamp-4">
                             {{ $card->description }}
                               
                            @else
                            <div class="flex items-start  w-full h-full ">
                                <x-fas-align-left class="w-6 h-6 pe-2" />  No Description
                                   
                              
                            </div>
                            
                        </p>
                        @endif
                    </div> 
                   </article>   <article class="flex flex-col w-full h-3/4">
                    <div x-data="{ showModal: false, modalImage: '' }" class="p-4 rounded-lg ">
                        @if ($workers->contains(Auth::user()))
                        <div class="flex flex-row  mt-2  justify-between">
                            <h3 class="text-lg px-2 font-semibold flex items-center">
                                <x-fas-paperclip class="w-6 h-6 pe-2" /> Attachments                    
                            </h3>
                            {{-- Upload Button --}}
                            <button id="addAttachmentBtn" class="px-4 py-2 bg-neutral-700 text-white rounded hover:bg-neutral-300 hover:text-black">Add</button>
                        </div>
                            {{-- File Input (Hidden) --}}
                            <input type="file" id="fileInput" multiple class="hidden">
                    
                            {{-- Uploaded Files List --}}
                         @else
                         <h3 class="text-lg font-semibold flex items-center">
                            <x-fas-paperclip class="w-6 h-6 px-2" /> Attachments                   
                        </h3>
                            @endif
                            <div x-show="showModal" x-transition class="fixed inset-0 bg-black absolute bg-opacity-70 flex items-center justify-center">
                                <div class="bg-white p-4 rounded-lg shadow-lg max-w-lg">
                                    <button @click.prevent="showModal = false" class="absolute top-20 right-40 text-white">Close</button>
                                    <img :src="modalImage" class="w-full rounded">
                                </div>
                            </div>
                        <div class="mt-2 h-3/4 overflow-auto">
                            @foreach ($upload as $file)
                                <div class="flex items-center p-2  rounded-lg">
                                    @if (in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']))
                                  
                                    <div 
                                    x-data="{ bgColor: '#f3f4f6' }"
                                    :style="'background: ' + bgColor + ' url({{ asset('../storage/app/public/' . $file->file_path) }}) center/contain no-repeat;'"
                                    class="w-14 h-14 rounded-lg border-2 shadow-md   cursor-pointer"
                                    @click="showModal = true; modalImage = '{{ asset('../storage/app/public/' . $file->file_path) }}'"
                                    x-init="setContrastBackground('{{ asset('../storage/app/public/' . $file->file_path) }}', value => bgColor = value)">
                                </div>
                                    @else
                                        <span class="w-14 h-14 flex items-center justify-center bg-gray-300 rounded text-sm font-bold">
                                            {{ strtoupper(pathinfo($file->file_path, PATHINFO_EXTENSION)) }}
                                        </span>
                                    @endif
                    
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">{{ $file->file_name }}</p>
                                        <p class="text-xs text-gray-500">Added {{ $file->created_at->diffForHumans() }}</p>
                                    </div>
                    
                                    @if (in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']))
                                        <button class="ml-auto text-blue-600" @click.prevent="showModal = true; modalImage = '{{ asset('../storage/app/public/' . $file->file_path) }}'"><x-fas-download class="w-6 h-6 text-neutral-700" /></button>
                                    @else
                                        <a href="{{ asset('../storage/app/public/' . $file->file_path) }}" class="ml-auto text-blue-600" target="_blank"> <x-fas-download class="w-6 h-6 text-neutral-700" /></a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                     
                    </div>
                   
                </article>

             
                @if ($workers->contains(Auth::user()))
                <div class="flex flex-col items-center justify-start w-full gap-2">   

                <h3 for="textarea-comment" class="w-full pl-6 flex flex-row"><x-fas-bars-staggered class="w-4 h-4" />&nbsp; &nbsp;  Write a Comment</h3>
            </div>
                    <form class="flex items-center w-full gap-4 mt-3 px-4" rows="30" id="search-form"
                        action="{{ route('commentCard', ['team_id' => $team->id, 'board_id' => $board->id, 'card_id' => $card->id]) }}"
                        method="POST">
                        @csrf
                        {{-- <x-form.textarea name="content" id="comment-box" placeholder="Add Comment.." required /> --}}
                        {{-- <textarea name="content"  id="comment-box" placeholder="Add Comment.."> </textarea> --}}
                        <x-avatar name="{{ Auth::user()->name }}" asset="{{ Auth::user()->image_path }}" class="w-6 h-6" />

                        <div data-role="form-textarea" class="flex flex-col items-center justify-center w-full gap-2">                                                    
                            <div class="flex items-center justify-center shadow-md w-full gap-2 px-6 py-2 text-base rounded-lg bg-slate-100">
                                <textarea class="flex-grow outline-none bg-transparent resize-none" 
                                          placeholder="Type here..." 
                                          name="content" 
                                         id="comment-box"
                                          required rows="1">
                                </textarea>
                                <div id="mention-suggestions" class="suggestions-dropdown"></div>
                            </div>
                        </div>
                       
                        <button type="button" id="submit-btn" class="px-4 py-2 bg-neutral-700 text-white rounded-lg hover:bg-neutral-300 hover:text-black">
                            Comment
                        </button>
                    </form>
             
                @endif
               

                <div id="event-container" class="flex flex-col w-full h-4 ps-4 pe-10 gap-6 mt-4">
    
</div>

            </section>

            {{-- right-section --}}
            {{-- <aside class="flex flex-col h-full gap-4 w-72">
                <h2 class="ml-4 text-2xl font-bold">Wokers</h2>
                <div
                    class="flex flex-col flex-grow w-full gap-2 p-4 overflow-x-hidden overflow-y-auto truncate border-2 border-gray-200 rounded-xl"> --}}
                    {{-- @foreach ($workers as $worker)
                    @if ($worker->id == $owner->id || $worker->id == $chatOwner->user_id)
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-4">
                                <x-avatar name="{{ $worker->name }}" asset="{{ $worker->image_path }}"
                                    class="!flex-shrink-0 !flex-grow-0 w-12" />
                                <p class="w-40 truncate">{{ $worker->name }}</p>
                                <x-fas-crown class="w-6 h-6 text-yellow-400 !flex-shrink-0 !flex-grow-0" />
                            </div>
                        </div>
                        @else
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-4">
                                <x-avatar name="{{ $worker->name }}" asset="{{ $worker->image_path }}"
                                    class="!flex-shrink-0 !flex-grow-0 w-12" />
                                <p class="w-40 truncate">{{ $worker->name }}</p>
                               
                            </div>
                        </div>
@endif
                    @endforeach --}}
                {{-- </div>

            </aside> --}}
        </div>
    </div>

    @if (Auth::user()->id == $owner->id)
        <template is-modal="deleteCard">
            <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
                action="{{ route('deleteCard', ['team_id' => $team->id, 'board_id' => $board->id, 'card_id' => $card->id]) }}">
                @csrf
                <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                <p class="mb-6 text-lg text-center"> Are you sure you want to delete this card?</p>
                <div class="flex gap-6">
                    <x-form.button type="submit">Yes</x-form.button>
                    <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
                </div>
            </form>
        </template>
    @endif
    <template is-modal="assignSelf">
        <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
            action="{{ route('assignSelf', ['team_id' => $team->id, 'board_id' => $board->id, 'card_id' => $card->id]) }}">
            @csrf
            <input type="hidden" name="id" value="{{ Auth::user()->id }}">
            <p class="mb-6 text-lg text-center"> Are you sure you want to join this card?</p>
            <div class="flex gap-6">
                <x-form.button type="submit">Yes</x-form.button>
                <x-form.button type="button" action="ModalView.close()">No</x-form.button>
            </div>
        </form>
    </template>

    <template is-modal="leaveCard">
        <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
            action="{{ route('leaveCard', ['team_id' => $team->id, 'board_id' => $board->id, 'card_id' => $card->id]) }}">
            @csrf
            <input type="hidden" name="id" value="{{ Auth::user()->id }}">
            <p class="mb-6 text-lg text-center"> Are you sure you want to qit this card?</p>
            <div class="flex gap-6">
                <x-form.button type="submit">Yes</x-form.button>
                <x-form.button type="button" action="ModalView.close()">No</x-form.button>
            </div>
        </form>
    </template>
    
    <template is-modal="editCard">
        <div class="flex flex-col w-full gap-4 p-4">
            <h1 class="text-3xl font-bold">Edit Card</h1>
            <hr>
            <form
                action="{{ route('updateCard', ['team_id' => $team->id, 'board_id' => $board->id, 'card_id' => $card->id]) }}"
                method="POST" class="flex flex-col gap-4">
                @csrf
                <x-form.textarea name="card_name" label="Card's Title" required value="{{ $card->name }}" />    
                    <x-form.textarea name="card_description" label="Card's Description" value="{{ $card->description }}" />

                    <div class="w-full flex flex-row items-center justify-between ps-4 py-2 ">
                <label  class="font-bold">labels</label>                  
                    <form class="flex flex-row w-5/6" id="lablesa">
                        @csrf
                        <div class="flex items-center w-full border-b-2 border-slate-200 py-2 pe-4 flex-col">
                            <input
                                class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                                type="text" name="label" placeholder="Add Lable">
                            </div>
                                <div class="flex flex-row items-center">
                                <div x-data="{ color: '#ff0000' }" class="relative w-6 h-6 mx-4 rounded-full overflow-hidden border-1 border-slate-200">
                                    <input name="rang" type="color" x-model="color" class="absolute z-10 inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <div class="absolute inset-0 rounded-full" :style="'background-color: ' + color"></div>
                                </div>
                            <button 
                                class="flex-shrink-0 hover:bg-slate-600 hover:text-slate-100 text-slate-600 py-1 px-2"
                                type="button" id="lable-btn" >
                                <x-fas-plus class="w-6 h-6" />
                            </button>
                        </div>
                    </form>
                   


                </div>
                <div id="taskList123" class="flex flex-col">  
    
                </div>
                <x-form.button class="mt-4" type="submit" primary>Submit</x-form.button>
            </form>
        </div>
      
    </template>
@php 
$names = array_map(function($item) {
    return $item['user']['name'];
}, $chatUser);
@endphp


@endsection
@pushOnce('head')
<style>
.suggestions-dropdown {
    position: absolute;
    background: white;
    border: 1px solid #ccc;
    border-radius: 5px;
    max-height: 200px;
    overflow-y: auto;
    display: none;
    width: 200px;
    z-index: 1000;
}

.suggestion-item {
    padding: 8px;
    cursor: pointer;
}

.suggestion-item:hover {
    background: #f0f0f0;
}
</style>
@endPushOnce
@pushOnce('page')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.min.js"></script>  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.min.js"></script>

<script>
     const socket = io("http://localhost:3000"); // Use correct port
//     const socket = io("http://task.wbsoftech.com", {
//     path: "/socket-server/socket.io",
//     transports: ["polling"]
// });
    console.log(io,"ggfg");

    socket.on("connect", () => {
        console.log("✅ Connected to Socket.io server");
    });





function fetchTasks() {
    var cardId = {{ $card->id }};
    let url = "{{ route('lable.List', ['id' => '__CARD_ID__']) }}".replace('__CARD_ID__', cardId);
    fetch(url) // Fetch all tasks from backend
        .then(response => response.json())
        .then(data => {
            appendTasks(data); // Call function to append tasks
          
        })
        .catch(error => console.error("Error:", error));

}
function getContrastColor(hex) {
    // Convert hex to RGB
    let r = parseInt(hex.substring(1, 3), 16);
    let g = parseInt(hex.substring(3, 5), 16);
    let b = parseInt(hex.substring(5, 7), 16);

    // Calculate luminance
    let luminance = (0.2126 * r + 0.7152 * g + 0.0722 * b) / 255;

    // Return white for dark backgrounds, black for light backgrounds
    return luminance < 0.5 ? "#ffffff" : "#000000";
}



function appendTasks(tasks) {
    let ul = document.getElementById("taskList");
    let modal = document.querySelector('[is-modal="editCard"]');
    let ull = modal.querySelectorAll("#taskList123");
    // let ull = document.getElementById("taskList123");
    ul.innerHTML = ""; // Clear existing tasks before appending new ones
if (ull) {
    console.log("task",ull)
}
    ull.innerHTML = "";
    tasks.forEach(task => {
        let li = document.createElement("div");
        li.classList.add("py-1","m-2", "rounded-lg", "cursor-pointer" ,"flex","flex-row" ,"justify-center");
        li.style.backgroundColor = task.color;
        li.dataset.taskId = task.id;
        li.dataset.status = task.status; // Store status for toggling
        let lii = document.createElement("div");
        lii.classList.add("py-1","m-2", "rounded-lg", "cursor-pointer" ,"flex","flex-row" ,"justify-center");
        lii.dataset.taskId = task.id;
        lii.dataset.status = task.status;


        let div = document.createElement("div");
        div.classList.add("flex", "items-center");

        let label = document.createElement("label");
        label.classList.add("mx-2", "block");

        let span = document.createElement("span");
        span.classList.add("text-md", "font-normal");
        span.textContent = task.text;
        
        // Adjust text color for contrast
        let textColor = getContrastColor(task.color);
        span.style.color = textColor;

        // Apply line-through if status is not 1
        if (task.status !== 1) {
            span.style.textDecoration = "line-through";
            span.classList.add("opacity-50"); 
        }

        label.appendChild(span);
        div.appendChild(label);
        li.appendChild(div);
        ul.appendChild(li);
        // ull.appendChild(lii);








        
     // 🛑 Add Remove Button
     let removeBtn = document.createElement("button");
        removeBtn.textContent = "X";
        removeBtn.classList.add("bg-blue-500", "text-white", "px-3", "py-1", "rounded-xl", "ml-3", "hover:bg-red-700");

        // 🔥 Remove task when clicked
        removeBtn.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent triggering status change when clicking the button

            let taskId = li.dataset.taskId;

            ServerRequest.post("{{ route('delete.lable') }}", { id: taskId })
            .then(response => {
                if (response.data.success) {
                    li.remove(); // ✅ Remove from UI
                } else {
                    console.error("Failed to delete task", response.data);
                }
            }).catch(error => console.error("Error:", error));
        });

        // li.appendChild(removeBtn); // ✅ Append button to list item
        ul.appendChild(li);
   









        // ✅ Click event for updating status
        li.addEventListener("click", function () {
            let taskId = this.dataset.taskId;
            let newStatus = this.dataset.status == 1 ? 0 : 1; // Toggle status

            ServerRequest.post("{{ route('status.lable') }}", {
                id: taskId,
                status: newStatus
            }).then(response => {
             
                    if (response.data.success) {  // ✅ Access response.data.success
        li.dataset.status = newStatus;

        if (newStatus === 0) {
            span.style.textDecoration = "line-through";
            span.classList.add("opacity-50");
        } else {
            span.style.textDecoration = "none";
            span.classList.remove("opacity-50");
        }
    } else {
        console.error("Failed to update task status", response.data);
    }
}).catch(error => console.error("Error:", error));
        });
    });
}

// Call fetchTasks to load tasks when the page loads
fetchTasks();



  
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('a[target="_blank"]').forEach(link => {
        link.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent triggering page loader
            document.getElementById("page-loader").style.display = "none"; // Hide loader
        });
    });
});
function setContrastBackground(imageSrc, callback) {
    let img = new Image();
    img.crossOrigin = "Anonymous"; // Avoid CORS issues
    img.src = imageSrc;

    img.onload = function () {
        let canvas = document.createElement("canvas");
        let ctx = canvas.getContext("2d");
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);

        let pixelData = ctx.getImageData(0, 0, 1, 1).data; // Get top-left pixel
        let r = pixelData[0], g = pixelData[1], b = pixelData[2];

        let brightness = (r * 299 + g * 587 + b * 114) / 1000; // Perceived brightness
        let contrastColor = brightness > 125 ? "#000" : "#fff"; // Choose black or white

        callback(contrastColor); // Apply contrast background
    };
}
document.addEventListener("DOMContentLoaded", function () {
            const fileInput = document.getElementById("fileInput");
            const addAttachmentBtn = document.getElementById("addAttachmentBtn");
            const uploadedFilesDiv = document.getElementById("uploadedFiles");
            var csrfToken = "{{ csrf_token() }}";
            // Open File Picker when button is clicked
            addAttachmentBtn.addEventListener("click", function () {
                fileInput.click();
            });

            // Handle File Selection
            fileInput.addEventListener("change", function (event) {
                const files = event.target.files;
                if (files.length > 0) {
                    uploadFiles(files);
                }
            });

            // Upload Files via AJAX
            function uploadFiles(files) {
                let formData = new FormData();
                // let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                var cardId = {{ $card->id }};
                for (let file of files) {
                    formData.append("attachments[]", file);
                }
                formData.append("card_id", cardId);
                formData.append("user_id","{{ Auth::user()->id }}");
                // alert(cardId);
                fetch("{{ route('upload.attachments') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); 
                    } else {
                        alert("Upload failed.");
                    }
                })
                .catch(error => console.error("Error:", error));
            }

        });
        ModalView.onShow('editCard', (modal) => {
            modal.querySelectorAll("form[action][method]").forEach(
                form => form.addEventListener("submit", () => PageLoader.show())
            );
            var cardId = {{ $card->id }};
    let url = "{{ route('lable.List', ['id' => '__CARD_ID__']) }}".replace('__CARD_ID__', cardId);
    fetch(url) // Fetch all tasks from backend
        .then(response => response.json())
        .then(data => {
            appendT(data);
        })
        .catch(error => console.error("Error:", error));


            function appendT(tasks) {
            let ull = modal.querySelector("#taskList123");
    // let ull = document.getElementById("taskList123");
    ull.innerHTML = ""; // Clear existing tasks before appending new ones
if (ull) {
    console.log("task",ull)
}
    ull.innerHTML = "";
    tasks.forEach(task => {
        let lii = document.createElement("div");
        lii.classList.add("py-1","bg-stone-200","my-2", "rounded-lg", "cursor-pointer" ,"flex","flex-row" ,"justify-between" ,"items-center");
        lii.dataset.taskId = task.id;
        lii.dataset.status = task.status;


        let div = document.createElement("div");
        div.classList.add("flex", "items-center");

        let label = document.createElement("div");
        label.classList.add("mx-2", "rounded-full" ,"w-4" ,"h-4");
        label.style.backgroundColor = task.color;
        let span = document.createElement("h6");
        span.classList.add("text-md", "font-normal","text-black" ,"ps-4");
        span.textContent = task.text;
        
        // Adjust text color for contrast
        let textColor = getContrastColor(task.color);
        // span.style.color = textColor;

        // Apply line-through if status is not 1
        if (task.status !== 1) {
            span.style.textDecoration = "line-through";
            span.classList.add("opacity-50"); 
        }

        // label.appendChild(span);
        div.appendChild(label);
        lii.appendChild(span);
        lii.appendChild(div);
        // ul.appendChild(li);
         ull.appendChild(lii);

     // 🛑 Add Remove Button
     let removeBtn = document.createElement("button");
        removeBtn.textContent = "X";
        removeBtn.classList.add( "text-slate-800", "px-3", "py-1", "ml-3");

        // 🔥 Remove task when clicked
        removeBtn.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent triggering status change when clicking the button

            let taskId = lii.dataset.taskId;

            ServerRequest.post("{{ route('delete.lable') }}", { id: taskId })
            .then(response => {
                if (response.data.success) {
                    lii.remove(); // ✅ Remove from UI
                } else {
                    console.error("Failed to delete task", response.data);
                }
            }).catch(error => console.error("Error:", error));
        });

        div.appendChild(removeBtn); // ✅ Append button to list item
        ull.appendChild(lii);
    });

            }

        });

        ModalView.onShow('leaveCard', (modal) => {
            modal.querySelectorAll("form[action][method]").forEach(
                form => form.addEventListener("submit", () => PageLoader.show())
            );
        });

        ModalView.onShow('assignSelf', (modal) => {
            modal.querySelectorAll("form[action][method]").forEach(
                form => form.addEventListener("submit", () => PageLoader.show())
            );
        });
        @if (Auth::user()->id == $owner->id)
            ModalView.onShow('deleteCard', (modal) => {
                modal.querySelectorAll("form[action][method]").forEach(
                    form => form.addEventListener("submit", () => PageLoader.show())
                );
            });
        @endif

        

        
        document.addEventListener("DOMContentLoaded", () => {
            @if ($workers->contains(Auth::user()))


    const textarea = document.querySelector("#comment-box");
    const suggestionBox = document.createElement("div");
    suggestionBox.classList.add("suggestions-dropdown");
    document.body.appendChild(suggestionBox);
const members = <?php echo json_encode($names); ?>;
// // console.log(member); 

//     // Static member list
//     const members = ["Alice", "Bob", "Charlie", "David", "Emma"];
    textarea.addEventListener("input", (event) => {
        const cursorPos = textarea.selectionStart;
        const text = textarea.value.slice(0, cursorPos);
        const atIndex = text.lastIndexOf("@");
        if (!textarea) return;
        if (atIndex !== -1) {
            const query = text.slice(atIndex + 1).trim();
            const filteredMembers = members.filter(name =>
                name.toLowerCase().startsWith(query.toLowerCase())
            );
            showSuggestions(filteredMembers, textarea, atIndex);
        } else {
            suggestionBox.style.display = "none";
        }
    });
   


    document.getElementById("submit-btn").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent default form submission
    const form = document.getElementById("search-form");
    const formData = new FormData(form); // Get form data
    const url = form.getAttribute("action");
    fetch(url, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value // CSRF Token
        },
        body: formData
    })
    .then(response => response.json()) // Convert response to JSON
    .then(data => {
        if (data.status === "success") {          
            form.reset(); // Clear form after submission           
            loadNewCards();
            // Optional: Update UI dynamically without reloading
            // let commentSection = document.getElementById("comment-section");
            // let newComment = document.createElement("div");
            // newComment.innerHTML = `<p><strong>User ${data.user_id}:</strong> ${data.content}</p>`;
            // commentSection.appendChild(newComment);
        }
    })
    .catch(error => {        
        console.error("Error:", error);
    });
});

@endif
    async function fetchDataById() {
    try {
        const response = await fetch(`/wbtask/public/get-comment/<?php echo $card->id; ?>`);
        // const response = await fetch(`get-comment/<?php echo$card->id; ?>`); // Replace with your actual URL
        if (!response.ok) {
            throw new Error('Data not found');
        }
        const data = await response.json();  // Parse JSON response
        console.log(data,"jh jfgfhg");  // You can handle the data here as needed
        // return data;  // Return the data from the API
        
        appendEvents(data);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}
fetchDataById();
    function showSuggestions(filteredMembers, textarea, atIndex) {
        suggestionBox.innerHTML = "";
        suggestionBox.style.display = filteredMembers.length ? "block" : "none";
        filteredMembers.forEach(member => {
            const item = document.createElement("div");
            item.classList.add("suggestion-item");
            item.textContent = member;
            item.addEventListener("click", () => insertMention(member, textarea, atIndex));
            suggestionBox.appendChild(item);
        });
        const rect = textarea.getBoundingClientRect();
        suggestionBox.style.left = rect.left + "px";
        suggestionBox.style.top = rect.top + textarea.scrollHeight + "px";
    }
    function insertMention(name, textarea, atIndex) {
        const text = textarea.value;
        textarea.value = text.substring(0, atIndex) + "@" + name + " " + text.substring(atIndex + 1);
        suggestionBox.style.display = "none";
        textarea.focus();
    }
    function timeAgo(dateString) {
    const now = new Date();
    const past = new Date(dateString);
    const seconds = Math.floor((now - past) / 1000);
    const intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60,
        second: 1,
    };
    for (const [unit, value] of Object.entries(intervals)) {
        const count = Math.floor(seconds / value);
        if (count >= 1) {
            return `${count} ${unit}${count > 1 ? 's' : ''} ago`;
        }
    }
    return "just now";
}
    function createAvatar(name, asset) {
        // Create the avatar container div
        const avatarDiv = document.createElement('div');
        avatarDiv.classList.add('flex-grow-0', 'flex-shrink-0', 'w-6', 'h-6');
        // Create an image tag for the avatar
        const avatarImg = document.createElement('img');
        avatarImg.classList.add('rounded-full');
        avatarImg.src = '/public/'+ asset; // Use the asset path for the image
        avatarImg.alt = name;  // Use the name as the alt text for accessibility
        // Append the image to the avatar div
        avatarDiv.appendChild(avatarImg);
        return avatarDiv;
    }
    function appendEvents(events) {
    const eventContainer = document.getElementById('event-container');

    if (!eventContainer) {
        console.error("❌ Error: 'event-container' not found in DOM.");
        return;
    }
    if (!events || events.length === 0) {
        console.warn("⚠️ No events to append.");
        return;
    }
    
    eventContainer.innerHTML = ""; // Clear previous messages
    events.forEach(event => {
        const eventDiv = document.createElement('div');
        eventDiv.classList.add('flex', 'flex-row', 'items-start');
        const eventInnerDiv = document.createElement('div');
        eventInnerDiv.classList.add('flex', 'items-start', 'w-full', 'gap-3');
        // ✅ Fix: Prevent undefined `user` errors
        const name = event.user?.name || "Unknown User"; 
        const avatarPath = event.user?.image_path || "default-avatar.jpg";
        const avatarDiv = createAvatar(name, avatarPath);
        eventInnerDiv.appendChild(avatarDiv);
        // Content section
        const contentDiv = document.createElement('div');
        contentDiv.classList.add('flex-grow', 'w-full', 'min-h-full', 'px-4', 'py-2', 'bg-slate-100', 'rounded-xl');
        const nameAndContent = document.createElement('p');
        nameAndContent.classList.add('text-base', 'font-bold');
        nameAndContent.textContent = name;
        contentDiv.appendChild(nameAndContent);
        // ✅ Fix: Prevent undefined `content`
        if (event.type !== 'event' && event.content) {
            const extraContent = document.createElement('p');
            extraContent.textContent = event.content;
            contentDiv.appendChild(extraContent);
        }
        eventInnerDiv.appendChild(contentDiv);
        // Time section
        const timeP = document.createElement('p');
        timeP.classList.add('px-4', 'text-xs', 'text-gray-700');
        timeP.textContent = timeAgo(event.created_at);
        // Append elements
        eventDiv.appendChild(eventInnerDiv);
        eventDiv.appendChild(timeP);
        eventContainer.appendChild(eventDiv);
    });
}
    function loadNewCards() {
        fetchDataById();    
console.log("Emitting 'board-action'...");
socket.emit("comment-action");
}
    socket.on("comment-refresh", () => {
        console.log("🔄 Board refreshedD");
        fetchDataById(); // Calls your existing refresh() function
    });
});

ModalView.onShow("inviteMember", (modal) => {
                const addBtn = modal.querySelector('#add-btn');
                const saveBtn = modal.querySelector('#save-btn');
                const emailField = modal.querySelector('#input-text-inv-email');
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
                    if (email === "") return;
                    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) return;
                    emailField.value = "";
                    const id = DOM.newid();
                    let emailtag = DOM.create(`
                    <div class="flex gap-2" id="email-tag-${id}">
                        <input type="hidden" name="emails[]" value="${email}">
                        <p class="flex-grow overflow-hidden truncate">
                            ${email}
                        </p>
                       
                    </div>
                    `);
                    inviteList.append(emailtag);
                }
            })
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
                        let email = card.dataset.email;
                        card.style.display = "flex";
                        if (!name.includes(search) && !email.includes(search))
                            card.style.display = "none";
                    });
                });
                var cardId = {{ $card->id }};
                saveBtn.addEventListener("click", async () => {
                    PageLoader.show();
                    let deleteEmailList = Array.from(memberCards)
                        .filter(card => card.classList.contains("is-delete"))
                        .map(card => card.dataset.email);
                    try {
                        await ServerRequest.post("{{ route('AddTeamMember') }}", {
                            card_id: cardId,
                            emails: deleteEmailList,
                        });
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


    document.getElementById("lable-btn").addEventListener("click", function (e) {
    e.preventDefault(); // Prevent default form submission
    var cardId = {{ $card->id }};
    let form = document.getElementById("lablesa");
    let formData = new FormData(form);
    formData.append("card_id", cardId);
    const storeTaskUrl = "{{ route('tasks.store') }}"; 
    fetch(storeTaskUrl, {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest", // Helps Laravel detect AJAX requests
            "Accept": "application/json"
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // console.log("✅ Task Added:", data);
            // alert(data.message);
            fetchTasks();
           
            form.reset(); // Reset form fields after submission
        } else {
            console.log("⚠️ Validation Failed:", data);
        }
    })
    .catch(error => console.error("❌ Error:", error));
});






    </script>
@endPushOnce
