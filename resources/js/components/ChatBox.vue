<template>
  <div class="w-full flex-col h-screen bg-white">
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-2 border-b">
      <div class="flex items-center space-x-3">
        <img v-if="user.avatar" :src="user.avatar" class="w-10 h-10 rounded-full" />
        <div>
          <div class="font-semibold">{{ user.name }}</div>
          <div class="text-xs text-green-500">● Online</div>
        </div>
      </div>
      <div class="flex items-center space-x-2">
        <button class="px-3 py-1 text-sm border rounded"> Call</button>
        <button class="px-3 py-1 text-sm border rounded"> Video Call</button>
      </div>
    </div>

    <!-- Messages -->
    <div class="flex-1 overflow-y-auto p-4 space-y-6">
      <div v-for="(msgGroup, index) in groupedMessages" :key="index" class="space-y-2">
        <div
          v-for="msg in msgGroup.messages"
          :key="msg.id"
          class="flex"
          :class="{ 'justify-end': msg.fromMe, 'justify-start': !msg.fromMe }"
        >
          <div
            class="max-w-xs px-3 py-2 rounded-lg text-sm shadow"
            :class="msg.fromMe ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-800'"
          >
            {{ msg.text }}
          </div>
        </div>
      </div>
    </div>

    <!-- Input -->
    <div class="flex items-center px-4 py-2 border-t">
      <input
        type="text"
        v-model="newMessage"
        placeholder="Type a message"
        class="flex-1 px-3 py-2 text-sm border rounded-full bg-gray-100 focus:outline-none"
        @keydown.enter="sendMessage"
      />
      <button @click="sendMessage" class="ml-3 text-xl text-gray-500">🎤</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ['selectedUser'],
  data() {
    return {
      newMessage: '',
      messages: [
        { id: 1, text: "omg, this is amazing", fromMe: false },
        { id: 2, text: "perfect! ✅", fromMe: false },
        { id: 3, text: "Wow, this is really epic", fromMe: false },
        { id: 4, text: "How are you?", fromMe: true },
        { id: 5, text: "woohoooo", fromMe: true },
        { id: 6, text: "Haha oh man", fromMe: true },
        { id: 7, text: "Haha that's terrifying 😂", fromMe: true },
        { id: 8, text: "I'll be there in 2 mins ⏰", fromMe: false },
        { id: 9, text: "woohoooo 🔥", fromMe: false }
      ]
    };
  },
  computed: {
    user() {
      return this.selectedUser || {
        name: 'Florencio Dorrance',
        avatar: 'https://i.pravatar.cc/100?img=3'
      };
    },
    groupedMessages() {
      // Optional: group messages by sender/receiver for cleaner display
      const groups = [];
      let lastFromMe = null;
      let group = null;
      this.messages.forEach(msg => {
        if (msg.fromMe !== lastFromMe) {
          group = { fromMe: msg.fromMe, messages: [] };
          groups.push(group);
        }
        group.messages.push(msg);
        lastFromMe = msg.fromMe;
      });
      return groups;
    }
  },
  methods: {
    sendMessage() {
      if (!this.newMessage.trim()) return;
      this.messages.push({
        id: Date.now(),
        text: this.newMessage,
        fromMe: true
      });
      this.newMessage = '';
    }
  }
};
</script>