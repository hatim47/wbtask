<template>
  <div class="w-80 bg-white h-[82vh] p-4  overflow-y-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
      <button class="text-gray-600 hover:text-black">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <h2 class="text-lg font-semibold">Messages</h2>
      <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">12</span>
    </div>

    <!-- Search -->
    <div class="mb-4">
      <input
        type="text"
        placeholder="Search messages"
        class="w-full px-4 py-2 bg-gray-100 text-sm rounded-md focus:outline-none"
        v-model="search"
      />
    </div>

    <!-- Chat list -->
    <ul>
      <li
        v-for="user in filteredUsers"
        :key="user.id"
        @click="$emit('select-user', user)"
        class="flex items-center p-3 mb-2 rounded-lg cursor-pointer hover:bg-gray-100"
      >
       <div class="w-10 h-10 mr-3 rounded-full bg-gray-300 flex items-center justify-center text-white text-sm font-semibold overflow-hidden">
  <img
    v-if="user.avatar"
    :src="user.avatar"
    class="w-full h-full object-cover"
    alt="Avatar"
  />
  <span v-else>
    {{ user.name.charAt(0).toUpperCase() }}
  </span>
</div>
        <div class="flex-1">
          <div class="flex justify-between text-sm">
            <span class="font-semibold">{{ user.name }}</span>
            <span class="text-gray-400">{{ user.time }}</span>
          </div>
          <div class="text-xs text-gray-500 truncate">{{ user.lastMessage }}</div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'UserChat',
  props: ['users'],
  data() {
    return {
      search: ''
    };
  },
  computed: {
    filteredUsers() {
      return this.users.filter(user =>
        user.name.toLowerCase().includes(this.search.toLowerCase())
      );
    }
  }
};
</script>