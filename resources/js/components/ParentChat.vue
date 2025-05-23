<template>
  <div class="parent-chat">
      <user-chat :users="users" @select-user="selectUser" />
      <chat-box :selectedUser="selectedUser" />
  </div>
</template>

<script>
import UserChat from './UserChat.vue';
import ChatBox from './ChatBox.vue';

export default {
  components: {
    UserChat,
    ChatBox
  },
  data() {
    return {
      users: [
        { id: 1, name: 'User 1' },
        { id: 2, name: 'User 2' },
        { id: 3, name: 'User 3' }
      ],
      selectedUser: null
    };
  },
  methods: {
    selectUser(user) {
      this.selectedUser = user;
    }
  }
};
</script>

<style scoped>
.parent-chat {
  display: flex;
  flex-direction: row;
}
</style>