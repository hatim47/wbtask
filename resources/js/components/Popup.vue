<!-- CardPopup.vue -->
<template>

  <div class="card-popup flex flex-col  overflow-y-auto " @click.self="close">
     
      <button class="close-btn absolute  top-4 right-[37.5rem] text-gray-500 z-50 hover:text-black hover:bg-slate-200 hover:rounded-full pt-0 px-[7px] pb-[3px] text-xl"
        @click.self="close">&times;</button><div class="h-40 w-full max-w-3xl rounded-t-lg shadow-lg p-6 bg-blue-900 relative">
        <img
          v-if="coverImage"
          :src="coverImage"
          alt="Cover"
          class="absolute top-0 left-1/2 transform -translate-x-1/2 h-full object-contain"
        />
        <button class="absolute bottom-2 right-2 bg-white text-sm px-2 py-1 rounded">Cover</button>
      </div>
    <div class="bg-white w-full max-w-3xl  rounded-b-lg shadow-lg p-6  h-[90vh] overflow-y-auto  overflow-x-hidden relative">


   


    
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
           
     <div v-if="isEditingDescription" class="mt-1">
      <div class="flex gap-2 mb-2">
      <button @click="toggleBold">Bold</button>
      <button @click="toggleItalic">Italic</button>
      <button @click="toggleBulletList">List</button>
      <button @click="addImage">Image</button>
    </div>
   <editor-content :editor="editor" class="prose p-3 border rounded bg-white" />
    <div class="flex gap-2 mt-2">
      <button @click="saveDescription" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
        Save
      </button>
      <button @click="cancelEditDescription" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">
        Cancel
      </button>
    </div>
  </div>

 <div v-else class="mt-1 group">
  <div
    class="description-content text-sm text-gray-800 bg-white p-3 rounded-md shadow-sm prose max-w-full transition-all duration-300 overflow-hidden"
    :class="{ 'max-h-40': !showFull, 'max-h-none': showFull }"
     :key="originalDescription"
  v-html="originalDescription"
    @click="startEditingDescription"
  ></div>

  <!-- Show More / Less only if long -->
  <div v-if="isTruncated" class="text-center mt-2">
    <button
      class="text-blue-600 text-sm hover:underline focus:outline-none"
      @click="toggleDescription"
    >
      {{ showFull ? 'Show Less' : 'Show More' }}
    </button>
  </div>

  <!-- Show Add a description if empty -->
  <div
    v-if="!originalDescription"
    class="text-sm text-gray-400 italic group-hover:bg-gray-100 p-2 rounded cursor-pointer"
    @click="startEditingDescription"
  >
    Add a more detailed description...
  </div>
</div>

          </div>




<div class="mt-6">

  <!-- Grid of Attachments -->
<div class="  mt-6 ">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-2 ">
          <h3 class="text-sm font-semibold text-gray-700  mb-2">Attachments</h3>

      </div>
      <button @click="uploadAttachments" class="text-sm bg-gray-300 text-black px-3 py-1 rounded hover:bg-gray-400">Add</button>
    </div>

    <!-- Files List -->
      <div v-for="(file, index) in visibleUploads" :key="index" class="flex items-start gap-3 py-2 border-b last:border-b-0">
        <!-- Thumbnail -->
        <div class="w-16 h-12 flex items-center justify-center bg-gray-100 rounded overflow-hidden">
          <img v-if="isImage(file.file_path)" :src="fileUrl(file.file_path)" class="object-cover w-full h-full" />
          <span v-else class="text-xs font-bold text-gray-700">{{ getFileType(file.file_path) }}</span>
        </div>

        <!-- File Info -->
        <div class="flex-1">
          <div class="flex items-center gap-2">
            <a :href="fileUrl(file.file_path)" target="_blank" class="font-medium text-gray-600 hover:underline">
              {{ file.original_name }}
            </a>
            <span v-if="file.is_cover" class="text-xs text-gray-500 px-2 py-0.5 bg-gray-200 rounded">Cover</span>
          </div>
          <div class="text-xs text-gray-500">Added  <br>
            {{ formatDate(file.created_at) }}</div>
        </div>
        <a :href="fileUrl(file.file_path)" class="ml-auto text-gray-600" target="_blank">↗</a>
        <!-- Actions -->
        <div class="relative" @click.stop v-click-outside="() => openMenu = null">
          <button @click="toggleMenu(index)" class="hover:bg-gray-200 rounded p-1">
            ⋮
          </button>
          <div v-if="openMenu === index" class="absolute right-0 mt-1 bg-white border rounded shadow w-40 z-10">
          
            <button class="block w-full px-4 py-2 text-left hover:bg-gray-100">Comment</button>
            <a :href="fileUrl(file.file_path)" download class="block w-full px-4 py-2 text-left hover:bg-gray-100">Download</a>
            <button v-if="isImage(file.file_path)" class="block w-full px-4 py-2 text-left hover:bg-gray-100" @click="makeCover(file)">Make cover</button>
            <button class="block w-full px-4 py-2 text-left text-red-600 hover:bg-gray-100" @click="deleteFile(file)">Delete</button>
          </div>
        </div>




   <!-- Delete Confirmation Modal -->
  <div>
    <!-- Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-sm text-center">
        <h2 class="text-xl font-semibold text-red-600 mb-2">Delete File?</h2>
        <p class="text-gray-700 mb-4">
          Are you sure you want to delete <strong>{{ selectedFile.original_name }}</strong>?
        </p>
        <div class="flex justify-center gap-4">
          <button
            class="px-4 py-2 rounded bg-gray-400 text-white hover:bg-gray-500"
            @click="cancelDelete"
          >
            Cancel
          </button>
          <button
            class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700"
            @click="confirmDelete"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
    </div>
<div v-if="reversedUploads.length > uploadDisplayLimit" class="mt-2 text-center">
  <button
    @click="showAllUploads = !showAllUploads"
    class="text-sm text-blue-600 hover:underline"
  >
    {{ showAllUploads ? 'Show less' : 'Show more' }}
  </button>
</div>
 

  <!-- Lightweight Confirmation Popup -->
  <div
    v-if="showConfirm"
    class="absolute top-full right-0 mt-2 w-64 bg-white border border-gray-200 rounded-xl shadow-xl z-20 p-4"
  >
    <h3 class="text-lg font-semibold text-red-600 mb-1">Confirm Deletion</h3>
    <p class="text-sm text-gray-700 mb-4">
      Delete <strong>{{ selectedFile.original_name }}</strong>?
    </p>
    <div class="flex justify-end gap-2">
      <button
        class="px-3 py-1 text-sm rounded bg-gray-100 hover:bg-gray-200"
        @click="showConfirm = false"
      >
        Cancel
      </button>
      <button
        class="px-3 py-1 text-sm rounded bg-red-600 text-white hover:bg-red-700"
        @click="confirmDelete">
        Delete
      </button>
    </div>
  </div>








  </div>
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
            class="fixed top-[27%] right-[14%] bg-white border border-gray-200 rounded-xl shadow-md w-80 p-4 z-50">
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
    class="fixed right-[15%] top-[28%] z-50 w-72 bg-white rounded-md shadow-lg border border-gray-200"
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
            class="flex items-center justify-between px-2 py-1  rounded "
          >
            <label class="flex items-center gap-2 w-full cursor-pointer">
              <input 
                type="checkbox" 
                v-model="selectedLabelIds" 
                :value="label.id"
                class="rounded text-blue-500 focus:ring-blue-500"
                 @change="handleCheckbox(label)"
              />
              <div 
                class="flex-1 h-6 rounded px-2 text-xs text-white flex items-center"
                :style="{ backgroundColor: label.color }"
              >
                {{ label.title || ' ' }}
              </div>
            </label>
            <button 
              @click.stop="startEditingLabel(label)" 
              class="text-gray-500 hover:text-gray-700  hover:bg-gray-100 text-xs p-1"
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
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Image from '@tiptap/extension-image'
import axios from 'axios'

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
   components: {
  EditorContent
  },
  props: ['data'],
directives: {
    clickOutside: clickOutsideDirective
  },
  data() {
    return {
      openMenu: null,
    showFull: false,
      isTruncated: false,
   showMembers: false,
  searchQuery: '',
  showInlineEditor: false,
  socket: null,
  localChatUser: [],
  localWorkers: [],
  showLabelSelector: false,
  showLabelEditor:false,
  labelSearch: '',
  selectedLabelIds: [],
  coverImage:'',
  isEditingDescription: false,
     editor: null,
      originalDescription: this.data.data.card.description || '',
     
  // availableLabels: [
  //   { id: 1, name: '', color: '#61bd4f' },
  //   { id: 2, name: '', color: '#f2d600' },
  //   { id: 3, name: '', color: '#eb5a46' },
  //   { id: 4, name: '', color: '#c377e0' },
  //   { id: 5, name: '', color: '#0079bf' },
  // ],
  availableLabels: [],
 showDeleteModal: false,
      selectedFile: {},
  labelTitle: '',
  labelColor: '',
  labelId: null,
  colorOptions: [
    '#61bd4f', '#f2d600', '#ff9f1a', '#eb5a46', '#c377e0',
    '#0079bf', '#00c2e0', '#51e898', '#ff78cb', '#344563',
    '#b3bac5', '#6b778c', '#fdab3d', '#519839', '#d29034',
    '#b04632', '#89609e', '#cd5a91', '#4bbf6b', '#00aecc',
  ],
  editingLabel: null,
 localUploads: [],
  showAllUploads: false,
  uploadDisplayLimit: 4,
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
   const term = this.labelSearch.toLowerCase().trim();

  return this.availableLabels.filter(label => {
    const title = label?.title ?? " "; // if title is null or undefined, use ""
    return title.toLowerCase().includes(term);
  });
  },
  isTruncated() {
      return this.data.data.card.description && this.data.data.card.description.length > 200
    },
     reversedUploads() {
    return [...this.localUploads].reverse();
  },
   upload() {
    return this.data.data.upload;
  },
  visibleUploads() {
    return this.showAllUploads
      ? this.reversedUploads
      : this.reversedUploads.slice(0, this.uploadDisplayLimit);
  }
  },
  mounted() {
    
 this.localUploads = [...(this.data?.data?.upload || [])];
const cover = this.data.data.upload.find(file => file.f_cover == 1);
if (cover) {
this.coverImage = `/wbtask/public/storage/${cover.file_path}`;
}
  this.data.data.cardLabels.forEach(label => {
    const exists = this.data.data.labels.some(l =>
      label.board_card_id != null && l.id == label.board_card_id
    );
    if (!exists) {
      this.data.data.labels.push(label);
     
    }
  });
  this.selectedLabelIds = this.data.data.labels.filter(label => {
    const match = this.data.data.cardLabels.some(cl => cl.status === 0 && cl.board_card_id === label.id);
    if (label.status === 0 || match) {
    
      return true;
    }
    return false;
  })
  .map(label => label.id);
  // this.selectedLabelIds = this.data.data.labels
  //   .filter(label => this.data.data.cardLabels.some(cl => cl.status === 0 || label.status == 0 && console.log("label",label.status,"cl", cl.status)))
  //   .map(label => label.id);
this.availableLabels=(this.data.data.labels);
    // Initialize reactive copy of chatUser
  this.localChatUser = [...(this.data.data?.chatUser || [])];
  this.localWorkers = [...(this.data.data?.workers || [])];
  this.initSocket(); 
   this.editor = new Editor({
      extensions: [StarterKit],
      content: this.originalDescription,
    })
  },
 beforeUnmount() {
    if (this.editor) this.editor.destroy()
  },
   
  watch: {
    'data.data.card.description'(newVal) {
      this.description = newVal || ''
      if (this.editor && newVal !== this.editor.getHTML()) {
        this.editor.commands.setContent(newVal || '')
      }
    },
  },
  methods: {
    handleCheckbox(label) {
    const isChecked = this.selectedLabelIds.includes(label.id);
    const newStatus = isChecked ? 0 : 1;
    if (isChecked) {
      // 🔵 Send API to insert (POST)
      fetch(`/wbtask/public/api/update-label/${label.id}`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: JSON.stringify({
          title: label.title,
          color: label.color,
          status: newStatus

        })

      })
      .then(res => {
        if (!res.ok) throw new Error('Insert failed');
         label.status = newStatus;
        this.socket.emit('label-Checked-updated', label, this.clientId);
        console.log('Label Checked show');
      })
      .catch(err => console.error(err));
    } else {
      fetch(`/wbtask/public/api/update-label/${label.id}`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
          body: JSON.stringify({
          title:  label.title,
          color: label.color,
          status: newStatus
        })
      })
      .then(res => {
        if (!res.ok) throw new Error('Remove failed');
         label.status = newStatus;
          this.socket.emit('label-Checked-updated', label, this.clientId);
        console.log('Label Check removed');
      })
      .catch(err => console.error(err));
    }  
  },
    startEditingDescription() {
      this.localDescription = this.data.data.card.description || ''
      this.isEditingDescription = true

      this.editor = new Editor({
        extensions: [StarterKit, Image],
        content: this.localDescription,
        onUpdate: ({ editor }) => {
          this.localDescription = editor.getHTML()
        },
      })
    },
    cancelEditDescription() {
      this.isEditingDescription = false
      this.editor.commands.setContent(this.description || '')
    },
    async saveDescription() {
      const html = this.editor.getHTML()
      this.originalDescription = html
      this.isEditingDescription = false

      try {
        await fetch(`/wbtask/public/api/card/${this.data.data.card.id}/description`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ description: html }),
        })
    

  this.originalDescription = html
    console.log('✅ Description saved.')

    // Emit socket event to others
    this.socket.emit('card-description-updated', {
      cardId: this.data.data.card.id,
      description: html
    })

        console.log('✅ Description saved.')
      } catch (error) {
        console.error('❌ Failed to save:', error)
      }
  },
    toggleDescription() {
      this.showFull = !this.showFull
    },
    toggleBold() {
      this.editor.chain().focus().toggleBold().run()
    },
    toggleItalic() {
      this.editor.chain().focus().toggleItalic().run()
    },
    toggleBulletList() {
      this.editor.chain().focus().toggleBulletList().run()
    },
   addImage() {
  const input = document.createElement('input');
  input.type = 'file';
 input.accept = 'image/*';
  input.onchange = async () => {
    const file = input.files[0];
    if (!file) return;
    const formData = new FormData();
    formData.append('image', file);
    formData.append('card_id', this.data.data.card.id); 
    try {
      const response = await fetch('/wbtask/public/api/upload-image', {
        method: 'POST',
        body: formData,
      });
      if (!response.ok) {
        throw new Error('Upload failed');
      }
    const result = await response.json();
    const imageUrl = `/wbtask/public/storage/${result.uploaded_files.file_path}`;
      // insert image into the editor
      this.editor.chain().focus().setImage({ src: imageUrl }).run();
      this.upload.push(result.uploaded_files);
socket.emit('card-file-uploaded', {
      cardId: this.data.data.card.id,
      file: result.uploaded_files // include id, name, path, etc.      
    });
  console.log('✅ Uploaded:', result);
    } catch (err) {
      console.error('❌ Upload failed:', err);
    }
  };
  input.click();
}, 
 uploadAttachments() {
  const input = document.createElement('input');
  input.type = 'file';
  input.accept = '*/*'; // Accept all types
  input.multiple = true;
  input.onchange = async () => {
    const files = Array.from(input.files);
    if (!files.length) return;
    const formData = new FormData();
    files.forEach(file => {
      formData.append('image[]', file); // support for multiple files
    });
    formData.append('card_id', this.data.data.card.id);
    try {
      const response = await fetch('/wbtask/public/api/upload-attachments', {
        method: 'POST',
        body: formData,
      });
      if (!response.ok) throw new Error('Upload failed');
      const result = await response.json();  
      // Optional: update your local card.uploads list
    if (Array.isArray(result.uploaded_files)) {
  this.data.data.upload.push(...result.uploaded_files);
}
   socket.emit('card-file-uploaded', {
      cardId: this.data.data.card.id,
      file: result.uploaded_files // include id, name, path, etc.
      
    });
  console.log('✅ Uploaded:', result);
  } catch (err) {
    console.error('❌ Attachment upload failed:', err);
  }
};
  input.click();
},  
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
    console.log("Back to labels clicked", this.showLabelSelector);
       setTimeout(() => {
      this.showLabelSelector = true;
    }, 10);
  },  
  createNewLabel() {
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
    this.labelTitle = label.title;
    this.labelColor = label.color;
       
    this.showInlineEditor = true;
  },  
  startEditingLabel(label) {
    this.editingLabel = label;
    this.labelId = label.id;
    this.labelTitle = label.title;
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
      const formData = new FormData();    
      formData.append('title', this.labelTitle);   
    formData.append('color', this.labelColor);
formData.append('status', 0);

     const response = fetch('/wbtask/public/api/update-label/' + parseInt(this.editingLabel.id), {

        method: 'POST',
        body: formData,
      });
      this.editingLabel.id = this.labelId;
      this.editingLabel.title = this.labelTitle;
      this.editingLabel.color = this.labelColor;

  this.socket.emit('label-updated', this.editingLabel);
  console.log('✅ label-updated:', this.editingLabel);
    } else {
      // Create new label


      fetch(`/wbtask/public/api/labels/insert`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({
          id: 0,
          title:  this.labelTitle,
          color: this.labelColor,
          card_id: this.data.data.card.id
        })
      })
      .then(res => {
        if (!res.ok) throw new Error('Remove failed');
        console.log('Label Create ');
      })
      const newLabel = {
      id: Date.now(),
      title: this.labelTitle,
      color: this.labelColor
      };
      this.availableLabels.push(newLabel);
      this.socket.emit('label-created', newLabel);
    }    
    this.showLabelEditor = false;
    this.editingLabel = null;
  },
  
  deleteLabel() {
    if (confirm('Are you sure you want to delete this label?')) {
    fetch(`/wbtask/public/api/labels/${this.editingLabel.id}`, {
          method: 'get',
          headers: { 'Content-Type': 'application/json' },
        })
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
    isImage(path) {
      return /\.(jpe?g|png|gif|webp|bmp|svg)$/i.test(path)
    },
   getFileType(path) {
  if (!path || typeof path !== 'string') {
    console.warn('Missing or invalid file path:', path);
    return 'UNKNOWN';
  }
  return path.split('.').pop().toUpperCase();
},
    formatDate(dateStr) {
      const date = new Date(dateStr)
      return date.toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' })
    },
    toggleMenu(index) {
      this.openMenu = this.openMenu === index ? null : index
    },
    editAttachment(file) {
      alert('Edit clicked for ' + file.original_name)
    },
    makeCover(file) {
       this.selectedFile = file
  fetch(`/wbtask/public/api/card/${this.data.data.card.id}/makeCover/${this.selectedFile.id}`, {
          method: 'POST',
        headers: { 'Content-Type': 'application/json' },
      })
  this.socket.emit("cover-updated", {
      cardId: this.data.data.card.id,
      cover: {
        id: file.id,
        file_path: file.file_path
      }
    });
  console.log("📥 cover send to in real-time:");
      // call API if needed
    },
    
    deleteFile(file) {
      this.selectedFile = file
      this.showDeleteModal = true
    },
    cancelDelete() {
      this.showDeleteModal = false
      this.selectedFile = {}
    },





    confirmDelete() {
      fetch(`/wbtask/public/api/card/${this.data.data.card.id}/upload/${this.selectedFile.id}`, {
          method: 'POST',
        headers: { 'Content-Type': 'application/json' },
      })
        .then(res => res.json())
        .then(data => {
          this.showDeleteModal = false
          this.selectedFile = {}

          // Emit event to parent to refresh list
       this.socket.emit("card-file-deleted", {
        cardId: this.data.data.card.id,
       fileId: data.file_id,
      });
       console.log("🗑 File deleted:", data.file_id);
 this.removeImageFromDescription(data.file_path);
           console.log('deletedFilePath',data.file_path)
             if (this.coverImage?.includes(data.file_path)) {
    this.coverImage = null;
    // 🔄 Also reset the f_cover flags
    if (Array.isArray(this.upload)) {
      this.upload.forEach(f => {
        if (f.f_cover == 1) f.f_cover = 0;
      });
    }
  }
          // Optional: close dropdown
          this.openMenu = null
        })
        .catch(err => {
          console.error('Delete failed:', err)
          this.showDeleteModal = false
          

        })
    },
    removeImageFromDescription(deletedFilePath) {
  // 1. Get current HTML from editor or raw description
  let currentHTML = this.editor?.getHTML?.() || this.data.data.card.description;
 console.log('deletedFilePath',deletedFilePath)
  // 2. Create a DOM element to parse HTML
  const div = document.createElement('div');
  div.innerHTML = currentHTML;

  // 3. Remove matching <img> tags
  const images = div.querySelectorAll('img');
  images.forEach(img => {
    if (img.src.includes(deletedFilePath)) {
      img.remove();
    }
  });
  // 4. Update editor and local data
  const newHTML = div.innerHTML;
  if (this.editor) {
    this.editor.commands.setContent(newHTML);
  }
  this.data.data.card.description = newHTML;
  console.log('sdfsdf',newHTML)
},


  fileUrl(path) {
    // Return full URL to file based on Laravel's storage
   return `http://localhost/wbtask/public/storage/${path}`; // adjust if needed based on backend
  },
    initSocket() {
      this.socket = io("http://localhost:3000"); // replace with your real backend

      this.socket.on("connect", () => {
          this.clientId = this.socket.id;
          const cardId = this.data?.data?.card?.id;
         console.log("📨 Emitting join-card with cardId:", cardId);
        console.log("✅ Connected to Socket.io");
        this.socket.emit("join-card", this.data.data.card.id); // join specific card room
  
      });

  this.socket.on('label-created', (label) => {
    console.log('New label created:', label);
    this.availableLabels.push(label);
  });

  this.socket.on('label-updated', (label) => {
    const index = this.availableLabels.findIndex(l => l.id === label.id);
    if (index !== -1) {
      this.availableLabels[index] = label;
    }
    console.log('Updated label Received:', label);
  });
this.socket.on('label-Checked-updated', ({ label, clientId }) => {
   if (clientId === this.clientId) {
    console.log("Skipped processing label update from this client:", label);
    return;
  }
  console.log('✏️ Checked Label updated:', label);
  // ✅ Find and update label in availableLabels
 const existing = this.data.data.cardLabels.find(l => l.id === label.id);

  if (existing) {
    Object.assign(existing, label);
  } else {
    this.data.data.cardLabels.push(label);
  }

  // Sync checkbox state reactively
  if (label.status === 0) {
    if (!this.selectedLabelIds.includes(label.id)) {
      this.selectedLabelIds.push(label.id);
    }
  } else {
    const index = this.selectedLabelIds.indexOf(label.id);
    if (index !== -1) {
      this.selectedLabelIds.splice(index, 1);
    }
  }

  console.log("✅ Updated selectedLabelIds:", this.selectedLabelIds);
});

        socket.on("card-file-uploaded", (data) => {
            const cardId = this.data?.data?.card?.id;
    if (data.cardId == cardId) {
       
const files = Array.isArray(data.file) ? data.file : [data.file];
this.localUploads = [...this.localUploads, ...files];

        // ✅ Method 2: Use `push` (if reactivity is properly set)
        // this.data.data.upload.unshift(newFile);
        
        console.log("📥 New file received in real-time:", files);
    }
  });

this.socket.on("cover-updated", (payload) => {
    if (payload.cardId == this.data.data.card.id) {
      const newCover = payload.cover;
      this.coverImage = `/wbtask/public/storage/${newCover.file_path}`;
      console.log("📥 cover recived  in real-time:", payload);
      // Optional: Update the whole upload array if needed
      this.data.data.upload.forEach(f => f.f_cover = f.id === newCover.id ? 1 : 0);
    }
  });



this.socket.on("card-file-deleted", ({ cardId, fileId }) => {
  if (this.data?.data?.card?.id == cardId) {
    this.localUploads = this.localUploads.filter(f => f.id != fileId);
    console.log("🗑 File deleted in real-timeeeeeeee:", fileId,cardId );
  }
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
  this.socket.on('card-description-updated', (data) => {
    if (data.cardId == this.data.data.card.id) {
      this.data.data.card.description = data.description
            this.originalDescription = data.description

   if (this.editor?.commands) {

   this.editor.commands.setContent(data.description, false);
  console.log('📝 Editor updated with new content.');
}
    console.log('Editor isReady:', !!this.editor);
   
      console.log('🔄 Real-time description update received.' ,data.description)
    }
  });

      // ✅ Listen for member remove
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
.ql-editor {
  max-height: 400px;
  overflow-y: auto;
}
.ql-editor img {
  max-width: 100% !important;
  height: auto !important;
  display: block;
  margin: 8px 0;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
::v-deep .description-content p img {
  max-width: 100%;
  max-height: 200px;
  height: auto !important;
  display: block;
  margin: 10px 0;
  border-radius: 0.5rem;
  object-fit: contain;
}
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
::v-deep .ql-editor img {
  max-width: 100% !important;
  max-height: 200px;
  height: auto !important;
  display: block;
  margin: 10px 0;
  border-radius: 8px;
  object-fit: contain;
}
</style>
