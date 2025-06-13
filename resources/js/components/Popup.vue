<!-- CardPopup.vue -->
<template>

  <div class="card-popup  " @click.self="close">
    <div class="bg-white w-full max-w-3xl h-[90vh] rounded-lg shadow-lg p-6 relative">

      <button class="close-btn absolute top-4 right-4 text-gray-500 hover:text-black text-xl"
        @click.self="close">&times;</button>
      <div class="mb-6">
        <h2 class="popup-title text-2xl font-semibold">{{ data && data.data.card ? data.data.card.name : 'Loading...' }}
        </h2>
        <div class="text-sm text-gray-500 mt-1">in list <span class="font-medium">To Do</span></div>
      </div>
      <div class="grid grid-cols-[568px_minmax(0,1fr)] grid-rows-[auto_auto] gap-x-4 gap-y-2 ">
        <div>


          <!-- Description -->
          <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Description</h3>
            <p class="popup-description text-gray-800 text-sm bg-gray-50 p-2 rounded">{{ data && data.data.card ?
              data.data.card.description : 'Loading...' }}</p>
          </div>


        </div>
        <div class="flex flex-col">
          <!-- Team -->
          <button id="membersBtn" @click="showMembers = !showMembers"
            class="text-sm px-3 mb-2 py-1 rounded bg-gray-200 hover:bg-gray-300">
            Members
          </button>

          <!-- Members Popover -->
          <div v-if="showMembers"
            class="absolute top-20 -right-80 bg-white border border-gray-200 rounded-xl shadow-md w-80 p-4 z-50">
            <div class=" flex  mb-1">

              <h3 class="text-lg font-semibold mb-3">Members</h3>
              <button class="close-btn absolute top-4 right-4 text-gray-500 hover:text-black text-xl"
                @click="closePopover">&times;</button>
            </div>

            <!-- Close Button (×) -->

            <!-- Search -->
            <input v-model="searchQuery" type="text" placeholder="Search members"
              class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm mb-4" />

            <!-- Card Members -->


            <!-- Board Members -->
            <div v-if="filteredCardMembers.length">
              <h4 class="text-gray-700 font-semibold text-sm mb-2">Card members</h4>
              <ul>
                <li v-for="member in filteredCardMembers" :key="member.user.id" @click="removeUserFromCard(member)"
                  class="flex items-center gap-2 p-2 hover:bg-red-50 cursor-pointer group">
                  <span class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">
                    {{ member.user?.name?.charAt(0) }}
                  </span>
                  <span class="flex-1">
                    {{ member.user?.name }}
                    <span class="text-xs text-gray-500 ml-2">{{ member.status }}</span>
                  </span>
                  <span class="invisible group-hover:visible text-red-500">
                    ✕
                  </span>
                </li>
              </ul>
            </div>

            <div v-if="filteredBoardMembers.length" class="mb-4">
              <h4 class="text-gray-700 font-semibold text-sm mb-2">Board members</h4>
              <ul>
                <li v-for="member in filteredBoardMembers" :key="member.id"
                  class="text-gray-800 text-sm py-1 flex items-center justify-between hover:bg-gray-50">
                  <div class="flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-medium">
                      {{ member.name[0] }} <!-- First letter avatar -->
                    </span>
                    {{ member.name }}
                  </div>

                  <!-- + Button to add user -->
                  <button @click="addUserToCard(member)" class="text-green-500 hover:text-green-700 text-lg font-bold"
                    title="Add to card">
                    +
                  </button>
                </li>
              </ul>
            </div>
          </div>







<div class="relative mb-2">
  <!-- Label button trigger -->
  <button 
    @click="toggleLabelSelector"
    class="mb-2 flex items-center justify-center w-full px-4 py-1 rounded-sm bg-gray-200 hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors"
  >
    Label
  </button>

  <!-- Label selector popover -->
  <div 
    v-if="showLabelSelector"
    v-click-outside="() => showLabelSelector = false"
    class="absolute left-0 top-12 z-50 w-72 bg-white rounded-md shadow-lg border border-gray-200"
  >
    <div class="p-2">
      <!-- Show label list by default -->
      <template v-if="!showLabelEditor">
        <h3 class="text-sm font-semibold px-2 py-1 text-gray-700">Labels</h3>
        
        <input 
          v-model="labelSearch" 
          placeholder="Search labels..."
          class="w-full px-2 py-1 mb-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500"
        />

        <div class="max-h-60 overflow-y-auto">
          <div 
            v-for="label in filteredLabels" 
            :key="label.id"
            class="flex items-center justify-between px-2 py-1 hover:bg-gray-100 rounded cursor-pointer"
          >
            <label class="flex items-center gap-2 w-full cursor-pointer">
              <input 
                type="checkbox" 
                v-model="selectedLabelIds" 
                :value="label.id"
                class="rounded text-blue-500 focus:ring-blue-500"
              />
              <div 
                class="flex-1 h-6 rounded px-2 text-xs text-white flex items-center"
                :style="{ backgroundColor: label.color }"
              >
                {{ label.name || 'Unnamed' }}
              </div>
            </label>
            <button 
              @click.stop="startEditingLabel(label)" 
              class="text-gray-500 hover:text-gray-700 text-xs p-1"
            >
              ✏️
            </button>
          </div>
        </div>

        <button 
          @click="startCreatingLabel"
          class="w-full text-left px-2 py-1 mt-1 text-sm text-gray-700 hover:bg-gray-100 rounded"
        >
          ➕ Create a new label
        </button>
      </template>

      <!-- Show editor when creating/editing -->
      <template v-else>
        
         <button 
          @click="backToLabels"
          class="flex items-center text-sm text-gray-600 hover:text-gray-800 mb-3"
        >
          ← Back to labels
        </button>
    
        <div class="flex justify-between items-center mb-3">
          <span class="font-medium text-sm text-gray-700">
            {{ editingLabel?.id ? 'Edit label' : 'Create label' }}
          </span>
          <button 
            @click="cancelEditing"
            class="text-gray-500 hover:text-gray-700"
          >
            ✖
          </button>
        </div>

        <!-- Color Preview -->
        <div 
          class="h-8 w-full rounded mb-3 flex items-center justify-center text-white text-xs"
          :style="{ backgroundColor: labelColor }"
        >
          {{ labelTitle || 'Label preview' }}
        </div>

        <!-- Title -->
        <label class="text-xs text-gray-600 mb-1 block">Title</label>
        <input 
          v-model="labelTitle" 
          type="text" 
          class="w-full px-2 py-1 text-sm border border-gray-300 rounded mb-3 focus:outline-none focus:ring-1 focus:ring-blue-500"
        />

        <!-- Color Grid -->
        <label class="text-xs text-gray-600 mb-1 block">Select a color</label>
        <div class="grid grid-cols-5 gap-2 mb-3">
          <div
            v-for="color in colorOptions"
            :key="color"
            :style="{ backgroundColor: color }"
            class="h-6 cursor-pointer rounded hover:opacity-90"
            @click="labelColor = color"
            :class="{ 'ring-2 ring-offset-1 ring-blue-500': labelColor === color }"
          ></div>
        </div>

        <div class="flex justify-between pt-2 border-t border-gray-200">
          <button 
            v-if="editingLabel?.id"
            @click="deleteLabel" 
            class="text-red-600 hover:text-red-800 text-sm"
          >
            Delete
          </button>
          <button 
            @click="saveLabel" 
            class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700"
          >
            Save
          </button>
        </div>
      </template>
    </div>
  </div>
</div>







   


          <!-- Workers -->
          <button id="workersBtn"
            class="flex items-center justify-center w-full mb-2 px-4 py-1 rounded-sm  bg-gray-200 hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors">Workers</button>

          <div id="workersPopover" class="hidden absolute bg-white shadow-lg rounded p-4 mt-2 border z-50 w-32">
            <h4 class="font-semibold mb-2 text-sm text-gray-700">Assigned Workers</h4>
            <div id="workersList" class="space-y-1"></div>
          </div>

          <!-- Owner -->


          <!-- Attachments -->
          <div
            class="mb-2 flex items-center justify-center w-full mb-2 px-4 py-1 rounded-sm  bg-gray-200 hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors">
            Attachments
          </div>

          <!-- Activity -->
          
        </div>
      </div>
    </div>
  </div>
</template>
<script>

import { io } from "socket.io-client";
const clickOutsideDirective = {
  mounted(el, binding) {
    setTimeout(() => {
      el._clickOutsideHandler = (event) => {
        if (!(el === event.target || el.contains(event.target))) {
          binding.value(event);
        }
      };
      document.addEventListener('click', el._clickOutsideHandler);
    }, 0);
  },
  unmounted(el) {
    document.removeEventListener('click', el._clickOutsideHandler);
  }
};
export default {
  props: ['data'],
directives: {
    clickOutside: clickOutsideDirective
  },
  data() {
    return {
   showMembers: false,
  searchQuery: '',
  showInlineEditor: false,
  socket: null,
  editorTop: 0,
  editorLeft: 0,
  localChatUser: [],
  localWorkers: [],
  showLabelSelector: false,
  labelSearch: '',
  selectedLabelIds: [],
  availableLabels: [
    { id: 1, name: '', color: '#61bd4f' },
    { id: 2, name: '', color: '#f2d600' },
    { id: 3, name: '', color: '#eb5a46' },
    { id: 4, name: '', color: '#c377e0' },
    { id: 5, name: '', color: '#0079bf' },
  ],
  labelTitle: '',
  labelColor: '',
  colorOptions: [
    '#61bd4f', '#f2d600', '#ff9f1a', '#eb5a46', '#c377e0',
    '#0079bf', '#00c2e0', '#51e898', '#ff78cb', '#344563',
    '#b3bac5', '#6b778c', '#fdab3d', '#519839', '#d29034',
    '#b04632', '#89609e', '#cd5a91', '#4bbf6b', '#00aecc',
  ],
  editingLabel: null, // Added this missing declaration
};
    
  },

  computed: {
    filteredCardMembers() {
      return this.localChatUser.filter(member => {
        const name = member.user?.name || '';
        return name.toLowerCase().includes(this.searchQuery.toLowerCase());
      });
    },
    filteredBoardMembers() {
      const existingUserIds = this.localChatUser.map(member => member.user?.id);
      return this.localWorkers.filter(worker =>
        worker.name.toLowerCase().includes(this.searchQuery.toLowerCase()) &&
        !existingUserIds.includes(worker.id)
      );
    },
    filteredLabels() {
    return this.availableLabels.filter(label =>
      label.name.toLowerCase().includes(this.labelSearch.toLowerCase())
    );
  }

  },


  mounted() {
    // Initialize reactive copy of chatUser
    this.localChatUser = [...(this.data.data?.chatUser || [])];
     this.localWorkers = [...(this.data.data?.workers || [])];
     
    this.initSocket();
  },

  beforeUnmount() {
    if (this.socket) {
      this.socket.disconnect();
    }
  },

  methods: {

     toggleLabelSelector() {
    this.showLabelSelector = !this.showLabelSelector;
    if (this.showLabelSelector) {
      this.currentView = 'list'; // Reset to list view when opening
      this.$nextTick(() => {
        const searchInput = this.$el.querySelector('input[placeholder="Search labels..."]');
        if (searchInput) searchInput.focus();
      });
    }
  },
// 
  // For navigating BACK to label list from edit/create view
backToLabels() {
      this.showLabelSelector = true;
      this.showLabelEditor = false;
      this.editingLabel = null;

      this.$nextTick(() => {
        const input = this.$el.querySelector('input[placeholder="Search labels..."]');
        if (input) input.focus();
      });
      console.log("Back to labels clicked", this.showLabelSelector);
       setTimeout(() => {
      this.showLabelSelector = true;
    }, 10);
    },



  cancelEditing() {
    this.showLabelEditor = false;
    this.editingLabel = null;
     this.showLabelSelector = true;
  },
  
  startCreatingLabel() {
    this.editingLabel = null;
    this.labelTitle = 'New Label';
    this.labelColor = this.colorOptions[0];
    this.showLabelEditor = true;
  },
  
  createNewLabel() {
    // Initialize a new label
   // Initialize a new label
  this.editingLabel = { 
    id: Date.now(), // Temporary ID
    name: 'New Label',
    color: this.colorOptions[0] // Default to first color
  };
  this.labelTitle = this.editingLabel.name;
  this.labelColor = this.editingLabel.color;
  
  // Position editor near the bottom of the label selector popover
  const labelSelector = this.$el.querySelector('.relative > div[v-click-outside]');
  if (labelSelector) {
    const rect = labelSelector.getBoundingClientRect();
    this.editorTop = rect.bottom + window.scrollY;
    this.editorLeft = rect.left + window.scrollX;
  }
  
  this.showInlineEditor = true;
  this.showLabelSelector = false;

  },
  
  editLabel(label, event) {
    this.editingLabel = label;
    this.labelTitle = label.name;
    this.labelColor = label.color;
    
    const rect = event.currentTarget.getBoundingClientRect();
    this.editorTop = rect.bottom + window.scrollY;
    this.editorLeft = rect.left + window.scrollX;
    
    this.showInlineEditor = true;
  },
  



  
  startEditingLabel(label) {
    this.editingLabel = label;
    this.labelTitle = label.name;
    this.labelColor = label.color;
    this.showLabelEditor = true;
  },
  
  
  
  saveLabel() {
    if (!this.labelTitle.trim()) {
      alert('Label name cannot be empty');
      return;
    }
    
    if (this.editingLabel) {
      // Update existing label
      this.editingLabel.name = this.labelTitle;
      this.editingLabel.color = this.labelColor;
    } else {
      // Create new label
      const newLabel = {
        id: Date.now(),
        name: this.labelTitle,
        color: this.labelColor
      };
      this.availableLabels.push(newLabel);
    }
    
    this.showLabelEditor = false;
    this.editingLabel = null;
  },
  
  deleteLabel() {
    if (confirm('Are you sure you want to delete this label?')) {
      this.availableLabels = this.availableLabels.filter(
        l => l.id !== this.editingLabel.id
      );
      this.selectedLabelIds = this.selectedLabelIds.filter(
        id => id !== this.editingLabel.id
      );
      this.showLabelEditor = false;
      this.editingLabel = null;
    }
  },
    initSocket() {
      this.socket = io("http://localhost:3000"); // replace with your real backend

      this.socket.on("connect", () => {
          const cardId = this.data?.data?.card?.id;
         console.log("📨 Emitting join-card with cardId:", cardId);
        console.log("✅ Connected to Socket.io");
        this.socket.emit("join-card", this.data.data.card.id); // join specific card room
  
      });
      

      // ✅ Listen for member add
    this.socket.on("member-add", (user) => {
      console.log("📥 Received member-add", user);
     const exists = this.localChatUser.some(m => m.user.id == user.user.id);
      if (!exists) {
        this.localChatUser.push({ user: { id: user.user.id, name: user.user.name } });
      }
            
       this.localChatUser = this.localChatUser.filter(Boolean);
      console.log("📦 Updated localChatUser:", this.localChatUser);
      


});

this.socket.on("member-remove", (userId) => {
  console.log("🗑️ Received member-removed", userId);
  this.localChatUser = this.localChatUser.filter(m => m.user.id !== userId.userId);

});
    },

    async addUserToCard(worker) {
      try {
        await axios.post('/wbtask/public/api/team/user/add', {
          card_id: this.data.data.card.id,
          user_id: worker.id,
          status: 'Member'
        });

        // 🔥 Emit socket event to others
      //  this.localChatUser.push({ user: { id: worker.id, name: worker.name } });
      this.localChatUser = this.localChatUser.filter(Boolean);

    // 2. Then emit to others via socket
    this.socket.emit("member-added", {
      cardId: this.data.data.card.id,
      user: {
        id: worker.id,
        name: worker.name,
        email: worker.email
      }
    });

      } catch (err) {
        console.error("❌ Failed to add user:", err.response?.data || err.message);
      }
    },

    async removeUserFromCard(member) {
      try {
        await axios.post(`/wbtask/public/api/board/${this.data.data.board.id}/card/${this.data.data.card.id}/leave`, {
          user_id: member.user.id
        });

        // 🔥 Emit socket event
      // this.localChatUser = this.localChatUser.filter(m => m.user.id !== member.user.id);
  // this.localChatUser = this.localChatUser.filter(Boolean);
    // 2. Notify others
    this.socket.emit("member-removed", {
      
      cardId: this.data.data.card.id,
      userId: member.user.id
    });

      } catch (err) {
        console.error("❌ Failed to remove user:", err.response?.data || err.message);
      }
    },

    close() {
      this.$el.remove();
    },

    closePopover() {
      this.showMembers = false;
    }
  }
};

</script>

<style scoped>
.card-popup {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
