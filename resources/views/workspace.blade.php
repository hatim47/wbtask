@extends('layout.page')



@pushOnce('metasa')

<meta name="csrf-token" content="{{ csrf_token() }}">

@endPushOnce

{{-- @section('app-header')

    <h1 class="text-xl font-bold">Teams</h1>

@endsection --}}



@section('app-side')

    <div class="flex flex-col gap-1 px-8 pl-4 mt-2">


 <a data-role="menu-item" href="{{ route('viewHome') }}"
       class="flex items-center justify-start w-full gap-3 px-6 py-2  text-gray-600 cursor-pointer  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
       <p class=" "> Home </p>
    </a> 
            <a data-role="menu-item" href="{{ route('viewTeam', ['team_id' => $team->id]) }}"
       class="flex items-center justify-start w-full gap-3 px-6 py-2  text-gray-600 cursor-pointer  select-none {{ Route::currentRouteName() == 'home' ? 'bg-neutral-100' : 'hover:bg-neutral-200 ' }}">
       <p class=" "> Board </p>
    </a> 

            


    </div>




<div class="flex flex-col gap-1  pl-4 mt-2" >




  @foreach ($assign_board   as $board)

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

    

    
<template is-modal="inviteMember" class="bg-red-200">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Invite People</h1>
                <hr>
                <div class="flex flex-col gap-4 ">
                    <label for="input-text-inv-email">Enter email address</label>
                    <div class="flex gap-4">
                    <form method="POST" class="w-full" id="invite-members-form" action="{{ route('InviteMemberto') }}">
                      @csrf
                       <input type="hidden" name="team_id" value="{{ $team->id }}"> 
                        <x-form.email name="inv-email" icon="fas-user-plus" placeholder="name@email.com..." />                      
                        </form>
                    </div>
                    <x-form.button primary type="submit" id="save-btn" form="invite-members-form">Save</x-form.button>
                </div>
            </div>
        </template>


    


    
    
    

    

    <div class="flex flex-col w-full h-full gap-6 px-8 py-6 overflow-auto">

        <header class="w-full">

            <h2 class="ml-6 mb-5 text-4xl font-extrabold"> Teams</h2>



            <form class="flex items-center gap-4 " id="search-form" action="{{ route('searchTeam') }}" method="GET">

                @csrf

                <x-form.text icon="fas-magnifying-glass" name="team_name" placeholder="Team's name"

                    value="{{ session('__old_team_name') }}" />

             

            </form>

        </header>



        <div class="flex p-6 gap-8 font-sans bg-white">
  <!-- Sidebar -->
 
  <!-- Main Content -->
  <div class="flex-1 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold">{{$team->name}} Workspace</h2>
        {{-- <span class="text-sm text-gray-500 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 17v.01M12 3C7.03 3 3 7.03 3 12c0 4.97 4.03 9 9 9s9-4.03 9-9c0-4.97-4.03-9-9-9zm0 14c-2.76 0-5-2.24-5-5 0-.85.24-1.65.66-2.33L12 17z" stroke-width="2"/></svg> Private</span> --}}
      </div>
      <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"   onclick="ModalView.show('inviteMember')">Invite Workspace members</button>
    </div>

<div x-data="{ tab: 'members' }" class="flex flex-col md:flex-row gap-6 space-y-6 md:space-y-0">

  <!-- Left panel (Tabs) -->
  <div class="md:w-1/2 space-y-4">
    <div class="text-lg font-semibold">
      Collaborators 
      <span class="text-sm text-gray-500">{{ count($members) }} / 10 +</span>
    </div>

    <!-- Tabs -->
    <nav class="flex flex-col gap-2">
      <button 
        class="px-4 py-2 text-left rounded" 
        :class="tab === 'members' ? 'bg-blue-100 text-blue-800' : 'text-gray-600 hover:bg-gray-100'" 
        @click="tab = 'members'">
        Workspace members ({{ count($members) }})
      </button>

      <button 
        class="px-4 py-2 text-left rounded" 
        :class="tab === 'requests' ? 'bg-blue-100 text-blue-800' : 'text-gray-600 hover:bg-gray-100'" 
        @click="tab = 'requests'">
        Join requests ({{ count($invites) }})
      </button>
    </nav>
  </div>

  <!-- Right panel (Tab content) -->
  <div class="w-full space-y-4">
    <!-- Invite Section -->
    <div>
      <h3 class="font-semibold text-lg">Invite members to join you</h3>
      <p class="text-sm text-gray-600 mb-2">Anyone with an invite link can join this free Workspace.</p>
    </div>

    <!-- Members Tab Content -->
  <div x-show="tab === 'members'" x-transition>
  @foreach ($members as $member)
    <div class="flex items-center justify-between border rounded px-4 py-3">
      <div class="flex items-center gap-3">
        <div class="bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold text-white">
          {{ strtoupper(substr($member['name'], 0, 1)) }}
        </div>
        <div>
          <div class="font-medium">{{ $member['name'] }}</div>
          <div class="text-sm text-gray-500">{{ $member['name'] }} • Last active {{ $member['created_at'] }}</div>
        </div>
      </div>
      <div x-data="{ showBoards: false }" class="flex flex-col gap-2 relative">
        <div class="flex items-center gap-2">
          <button 
            @click="showBoards = !showBoards"
            class="text-sm text-gray-600 border px-2 py-1 rounded hover:bg-gray-100"
            type="button"
          >
            View boards
          </button>
          <span class="bg-gray-200 text-xs px-2 py-1 rounded">{{ $member['status'] }}</span>

          @if ($owner->id == Auth::id())
           @if ($member['user_id'] != Auth::id())
            <form x-data="{ open: false }"
                  action="{{ route('deleteTeamMember', [ $board['team_id']]) }}" 
                  method="POST" 
                  @submit.prevent="open = true"
                  class="inline"
            >
              @csrf         
              <input type="hidden" name="emails" value="{{ $member['email'] }}">
              <button type="submit" class="bg-red-500 text-white px-3 py-2 rounded-md mx-3 text-xs hover:font-bold">Remove</button>

              <div x-show="open" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded shadow-xl text-center space-y-4">
                  <h2 class="text-lg font-semibold">Confirm Removal</h2>
                  <p>Are you sure you want to remove this member?</p>
                  <div class="flex justify-center space-x-4">
                    <button @click="open = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button @click="$el.closest('form').submit()" class="px-4 py-2 bg-red-500 text-white rounded">Yes, Remove</button>
                  </div>
                </div>
              </div>
            </form>
            @endif

          @else
            @if($member['user_id'] === Auth::id())
              <form x-data="{ open: false }" x-ref="form" method="POST" action="{{ route('deleteTeamMember', [ $board['team_id']]) }}" class="inline">
                @csrf
                <input type="hidden" name="emails" value="{{ $member['email'] }}">

                <button 
                  type="button" 
                  @click="open = true" 
                  class="bg-red-400 text-white px-3 py-2 rounded-md mx-3 text-xs hover:font-bold"
                  x-transition
                >
                  Leave
                </button>

                <div 
                  x-show="open" 
                  x-cloak 
                  class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                  @keydown.escape.window="open = false"
                >
                  <div class="bg-white p-6 rounded shadow-xl text-center space-y-4 w-80">
                    <h2 class="text-lg font-semibold">Confirm Removal</h2>
                    <p>Are you sure you want to remove this member?</p>
                    <div class="flex justify-center space-x-4 mt-4">
                      <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                      <button type="button" @click="open = false; $nextTick(() => $refs.form.submit())" class="px-4 py-2 bg-red-500 text-white rounded">Yes, Leave</button>
                    </div>
                  </div>
                </div>
              </form>
            @else
              <div class="px-6 mx-3"></div>
            @endif
          @endif
        </div>

        <!-- Board list tooltip -->
        <div x-show="showBoards"
             @click.away="showBoards = false"
             x-transition
             class="absolute z-10 mt-2 w-64 bg-white border border-gray-300 rounded flex flex-col shadow-lg p-3 space-y-1"
        >
          @if(count($member['boards']) > 0)
            @foreach($member['boards'] as $board)
              <div class="flex items-center justify-between p-1 rounded text-sm">
                <div class="flex items-center gap-2">
                  <span class="inline-block w-4 h-4 rounded-full bg-grad-{{ $board['pattern'] }}"></span>
                  <span class="font-medium text-gray-800">{{ $board['board_name'] }}</span>
                  <span class="text-xs text-gray-500">({{ $board['status'] }})</span>
                </div>

                <form 
                  x-data="{ open: false }"
                  x-ref="form"
                  method="POST"
                  @submit.prevent="open = true"
                  @click.stop
                  @keydown.escape.window="open = false"
                  @click.away="open = false"
                  class="inline"
                  @if($member['user_id'] === Auth::id() && $board['status'] == 'Owner')
                    action="{{ route('deleteBoard', [$board['team_id'] ,$board['board_id']]) }}" 
                  @elseif($member['user_id'] === Auth::id() && $board['status'] == 'Member')
                    action="{{ route('removeMember', [ $board['team_id'] ,$board['board_id']]) }}"
                    {{ $board['team_id'] }}
                  @endif
                >
                  @csrf
                  <input type="hidden" name="user_id" value="{{ $member['user_id'] }}">
                  
                  @if($member['user_id'] === Auth::id() && $board['status'] == 'Owner')
                    <button type="button" @click="open = true" class="text-red-600 px-3 py-2 text-xs hover:bg-red-500 hover:text-white hover:rounded">Deletee</button>
                  @elseif($member['user_id'] === Auth::id() && $board['status'] == 'Member')
                    <button type="button" @click="open = true" class="text-red-600 px-3 py-2 text-xs hover:bg-red-500 hover:text-white hover:rounded">leave</button>
                  @endif

                  <div 
                    x-show="open" 
                    x-cloak 
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                  >
                    <div class="bg-white p-6 rounded shadow-xl text-center space-y-4 w-80">
                      <h2 class="text-lg font-semibold">Confirm Removal</h2>
                      <p>Are you sure you want to remove this member From this Board ?</p>
                      <div class="flex justify-center space-x-4 mt-4">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                        <button type="button" @click="open = false; $nextTick(() => $refs.form.submit())" class="px-4 py-2 bg-red-500 text-white rounded">Yes, Leave</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            @endforeach
          @else
            <div class="text-sm text-gray-500 p-2">No boards assigned to this user.</div>
          @endif
        </div>
      </div>
    </div>
  @endforeach
</div>


    <!-- Join Requests Tab Content -->
    <div x-show="tab === 'requests'" x-transition>
       @foreach ($invites as $team)
            <div class="flex items-center justify-between border rounded px-4 py-3">
          <div class="flex items-center gap-3">
            <div class="bg-gray-400 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold text-white">
              {{ strtoupper(substr($team->name, 0, 1)) }}
            </div>
            <div>
              <div class="font-medium">{{ $team->name }}</div>
              <div class="text-sm text-gray-500">{{ $team->name }}</div>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <button class="text-sm text-green-600 border px-2 py-1 rounded hover:bg-green-100" onclick="ModalView.show('acceptInvite', { team_id: '{{ $team->id }}'  })">Approve</button>
            <button class="text-sm text-red-600 border px-2 py-1 rounded hover:bg-red-100">Decline</button>
          </div>
        </div>
      @endforeach














    </div>
  </div>
</div>
    </div>
</div>



       

    </div>



@endsection



@pushOnce('page')

    <script>
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
                {{-- saveBtn.addEventListener('click', () => PageLoader.show()); --}}
            })
    </script>

@endPushOnce

