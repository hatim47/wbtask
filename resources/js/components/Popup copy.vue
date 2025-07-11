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
    <div class="bg-gray-100 w-full max-w-3xl  rounded-b-lg shadow-lg p-3  h-[90vh] overflow-y-auto  overflow-x-hidden relative">


   


    
      <div class="mb-6">
        <h2 class="popup-title text-2xl font-semibold">{{ data && data.data.card ? data.data.card.name : 'Loading...' }}
        </h2>
        <div class="text-sm text-gray-500 mt-1">in list <span class="font-medium">To Do</span></div>
      </div>
      <div class="grid grid-cols-[568px_minmax(0,1fr)] grid-rows-[auto_auto] gap-x-4 gap-y-2 ">
        <div>


          <!-- Description -->
          <div class="mb-4">
             <div class="flex items-center gap-2  ">
           <svg width="20" height="20" role="presentation" focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4 5C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H4ZM4 9C3.44772 9 3 9.44772 3 10C3 10.5523 3.44772 11 4 11H20C20.5523 11 21 10.5523 21 10C21 9.44772 20.5523 9 20 9H4ZM3 14C3 13.4477 3.44772 13 4 13H20C20.5523 13 21 13.4477 21 14C21 14.5523 20.5523 15 20 15H4C3.44772 15 3 14.5523 3 14ZM4 17C3.44772 17 3 17.4477 3 18C3 18.5523 3.44772 19 4 19H14C14.5523 19 15 18.5523 15 18C15 17.4477 14.5523 17 14 17H4Z" fill="currentColor"></path></svg>
            <h3 class="font-semibold text-gray-700 mb-1">Description</h3>
               </div>
     <div v-if="isEditingDescription" class="mt-1">
      <div class="flex gap-2 mb-2">
      <button @click="toggleBold" class="editor-btn" title="Bold">
        <!-- Bold SVG -->
      <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M6 4h8a4 4 0 0 1 0 8H6z" />
  <path d="M6 12h9a4 4 0 0 1 0 8H6z" />
</svg>
      </button>   
      <button @click="toggleItalic" class="editor-btn" title="Italic">
        <!-- Italic SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <line x1="19" y1="4" x2="10" y2="4" />
  <line x1="14" y1="20" x2="5" y2="20" />
  <line x1="15" y1="4" x2="9" y2="20" />
</svg>
      </button>
      <button @click="toggleBulletList" class="editor-btn" title="Bullet list">
        <!-- Bullet List SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <line x1="8" y1="6" x2="21" y2="6" />
  <line x1="8" y1="12" x2="21" y2="12" />
  <line x1="8" y1="18" x2="21" y2="18" />
  <circle cx="4" cy="6" r="1" />
  <circle cx="4" cy="12" r="1" />
  <circle cx="4" cy="18" r="1" />
</svg>
      </button>
      <button @click="addImage" class="editor-btn" title="Add image">
        <!-- Image SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
  <circle cx="8.5" cy="8.5" r="1.5" />
  <path d="M21 15l-5-5L5 21" />
</svg>
      </button>
    </div>
   <editor-content :editor="editor" class=" p-3 border rounded bg-white" />
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
      <div class="flex items-center gap-2  ">
          <svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="14" height="14"><path d="M22.95,9.6a1,1,0,0,0-1.414,0L10.644,20.539a5,5,0,1,1-7.072-7.071L14.121,2.876a3,3,0,0,1,4.243,4.242L7.815,17.71a1.022,1.022,0,0,1-1.414,0,1,1,0,0,1,0-1.414l9.392-9.435a1,1,0,0,0-1.414-1.414L4.987,14.882a3,3,0,0,0,0,4.243,3.073,3.073,0,0,0,4.243,0L19.778,8.532a5,5,0,0,0-7.071-7.07L2.158,12.054a7,7,0,0,0,9.9,9.9L22.95,11.018A1,1,0,0,0,22.95,9.6Z"/></svg>    
<h3 class=" font-semibold text-gray-700">Attachments</h3>
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
  <!---------------------------------------------------------                ---------------------------------------------------------------------------------------------------------------
  -----------------------------------------------------------                     ----------------------------------------------------------------------------------------------------------------
  ---------------------------------------------------------                       -----------------------------------------------------------------------------------------------------------------------------
  -----------------------------------------------------------------------             💬 Add New Comment--------------------------------------------------------------------------------------------
  --------------------------------------------------                                 --------------------------------------------------------------------------------------------------->


 <div class="comment-section max-w-xl">
    <!-- 🔹 Comment Editor -->
    <div class="mb-4">
    <h3 class="text-lg font-medium mb-2">Add a comment</h3>
<div v-if="isEditingComment">
    <!-- Toolbar -->
    <div class="flex flex-wrap gap-2 mb-2">
      <button @click="toggleBoldd" class="editor-btn" title="Bold">
        <!-- Bold SVG -->
      <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M6 4h8a4 4 0 0 1 0 8H6z" />
  <path d="M6 12h9a4 4 0 0 1 0 8H6z" />
</svg>
      </button>   
      <button @click="toggleItalicc" class="editor-btn" title="Italic">
        <!-- Italic SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <line x1="19" y1="4" x2="10" y2="4" />
  <line x1="14" y1="20" x2="5" y2="20" />
  <line x1="15" y1="4" x2="9" y2="20" />
</svg>
      </button>
       <button @click="toggleBulletListt" class="editor-btn" title="Bullet list">
        <!-- Bullet List SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <line x1="8" y1="6" x2="21" y2="6" />
  <line x1="8" y1="12" x2="21" y2="12" />
  <line x1="8" y1="18" x2="21" y2="18" />
  <circle cx="4" cy="6" r="1" />
  <circle cx="4" cy="12" r="1" />
  <circle cx="4" cy="18" r="1" />
</svg>
      </button>

      <button @click="addImageToComment" class="editor-btn" title="Add image">
        <!-- Image SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
  <circle cx="8.5" cy="8.5" r="1.5" />
  <path d="M21 15l-5-5L5 21" />
</svg>
      </button>
    </div>







    <!-- Editor Content -->
    <EditorContent :editor="commentEditor" class="editor-box" />

    <!-- Submit -->
    <div class="text-right mt-2">
      <button
        @click="postComment"
        :disabled="loading"
        class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 disabled:opacity-50"
      >
        {{ loading ? 'Posting...' : 'Post' }}
      </button>
    </div>
</div>
<div
  v-else
  class="comment-preview text-sm text-gray-800 bg-white p-3 rounded-xl shadow-sm prose max-w-full cursor-pointer hover:bg-gray-50"
  v-html="'<p class=\'text-gray-400\'>Click to write a comment...</p>'"
  @click="startEditingComment"
></div>

  </div>

    <!-- 🔽 Render Comments -->
    <div v-for="comment in reversedComments" :key="comment.id" class="flex items-start gap-3 mb-4 group">
       <div v-if="comment.user?.image_path">
      <img
        :src="getFullImagePath(comment.user.image_path)"
        class="w-10 h-10 rounded-full object-cover"
        alt="Avatar"
      />
    </div>
    <div v-else class="w-10 h-10 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold uppercase">
      {{ getInitials(comment.user.name) }}
    </div>


     <div class="bg-white p-3 rounded-xl  w-full relative">
    <div class="flex justify-between items-center">
      <div class="text-sm font-medium text-gray-900">
        {{ comment.user.name }}
        <span class="text-xs text-gray-500 ml-2">{{ formatDate(comment.created_at) }}</span>
      </div>
      <div v-if="canEditComment(comment)" class="text-xs text-blue-600 space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        <button @click="startEditing(comment)">Edit</button>
        <button @click="deleteComment(comment.id)">Delete</button>
      </div>
    </div>

    <div v-if="editingComment?.id === comment.id">
      <EditorContent :editor="editEditor" class="border p-2 rounded mt-2" />
      <div class="text-right mt-2 space-x-2">
        <button @click="cancelCommentEditing">Cancel</button>
        <button @click="submitEdit">Save</button>
      </div>
    </div>
    <div v-else class="mt-1 prose prose-sm" v-html="comment.content"></div>
  </div>



    </div>
  </div>



        </div>
        <div class="flex flex-col">
          <!-- Team -->
          <button id="membersBtn" @click="showMembers = !showMembers"
            class=" text-black px-3 mb-2 py-1 rounded flex gap-3 items-center bg-gray-200 hover:bg-gray-300">
          <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" viewBox="0 0 24 24" height="14" width="14" data-name="Layer 1"><path d="m8 0c-3.309 0-6 2.691-6 6s2.691 6 6 6 6-2.691 6-6-2.691-6-6-6zm0 10c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4zm2.992 5.187c-.068.547-.563.938-1.116.868-.287-.036-.579-.054-.876-.054-3.859 0-7 3.14-7 7 0 .552-.447 1-1 1s-1-.448-1-1c0-4.962 4.037-9 9-9 .377 0 .755.023 1.124.07.549.069.937.568.868 1.117zm7.008 7.813c0 .552-.447 1-1 1h-1c-1.654 0-3-1.346-3-3v-6c0-1.654 1.346-3 3-3h1c.553 0 1 .448 1 1s-.447 1-1 1h-1c-.552 0-1 .449-1 1v6c0 .551.448 1 1 1h1c.553 0 1 .448 1 1zm5.996-4.979c0 .543-.21 1.011-.599 1.349l-2.199 1.939c-.189.167-.426.25-.661.25-.276 0-.553-.114-.75-.339-.365-.414-.325-1.046.089-1.411l.917-.809h-2.792c-.553 0-1-.448-1-1s.447-1 1-1h2.658l-.783-.691c-.414-.365-.454-.997-.089-1.411.365-.415 1-.452 1.411-.089l2.207 1.946c.369.314.592.788.592 1.266z"/></svg>  Members
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
                <li v-for="member in filteredBoardMembers" :key="member.id"  @click="addUserToCard(member)" 
                  class="text-gray-800 text-sm p-2 cursor-pointer flex items-center justify-between hover:bg-gray-50">
                  <div class="flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-medium">
                      {{ member.name[0] }} <!-- First letter avatar -->
                    </span>
                    {{ member.name }}
                  </div>

                  <!-- + Button to add user -->
                  <button class="text-gray-500 hover:text-gray-700 "                   >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <line x1="12" y1="5" x2="12" y2="19"/>
    <line x1="5" y1="12" x2="19" y2="12"/>
  </svg>
                  </button>
                </li>
              </ul>
            </div>
          </div>







<div class="relative mb-2">
  <!-- Label button trigger -->
  <button 
    @click="toggleLabelSelector"
     class="w-full text-black px-3 py-1 rounded flex gap-3 items-center bg-gray-200 hover:bg-gray-300">
  <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="14" height="14"><path d="M7.707,9.256c.391,.391,.391,1.024,0,1.414-.391,.391-1.024,.391-1.414,0-.391-.391-.391-1.024,0-1.414,.391-.391,1.024-.391,1.414,0Zm13.852,6.085l-.565,.565c-.027,1.233-.505,2.457-1.435,3.399l-3.167,3.208c-.943,.955-2.201,1.483-3.543,1.487h-.017c-1.335,0-2.59-.52-3.534-1.464L1.882,15.183c-.65-.649-.964-1.542-.864-2.453l.765-6.916c.051-.456,.404-.819,.858-.881l6.889-.942c.932-.124,1.87,.193,2.528,.851l7.475,7.412c.387,.387,.697,.823,.931,1.288,.812-1.166,.698-2.795-.342-3.835L12.531,2.302c-.229-.229-.545-.335-.851-.292l-6.889,.942c-.549,.074-1.052-.309-1.127-.855-.074-.547,.309-1.051,.855-1.126L11.409,.028c.921-.131,1.869,.191,2.528,.852l7.589,7.405c1.946,1.945,1.957,5.107,.032,7.057Zm-3.438-1.67l-7.475-7.412c-.223-.223-.536-.326-.847-.287l-6.115,.837-.679,6.14c-.033,.303,.071,.601,.287,.816l7.416,7.353c.569,.57,1.322,.881,2.123,.881h.01c.806-.002,1.561-.319,2.126-.893l3.167-3.208c1.155-1.17,1.149-3.067-.014-4.229Z"/></svg>
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
          

          <!-- Owner -->


          <!-- Attachments -->
          <button
          class=" text-black px-3 mb-2 py-1 rounded flex gap-2 items-center bg-gray-200 hover:bg-gray-300" @click="uploadAttachments">
<svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="14" height="14"><path d="M22.95,9.6a1,1,0,0,0-1.414,0L10.644,20.539a5,5,0,1,1-7.072-7.071L14.121,2.876a3,3,0,0,1,4.243,4.242L7.815,17.71a1.022,1.022,0,0,1-1.414,0,1,1,0,0,1,0-1.414l9.392-9.435a1,1,0,0,0-1.414-1.414L4.987,14.882a3,3,0,0,0,0,4.243,3.073,3.073,0,0,0,4.243,0L19.778,8.532a5,5,0,0,0-7.071-7.07L2.158,12.054a7,7,0,0,0,9.9,9.9L22.95,11.018A1,1,0,0,0,22.95,9.6Z"/></svg>    

Attachments
</button>
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
import Mention from '@tiptap/extension-mention'
import BulletList from '@tiptap/extension-bullet-list'
import ListItem from '@tiptap/extension-list-item'
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
   showConfirm: false,
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
  isEditingComment:false,
     editor: null,
      originalDescription: this.data.data.card.description || '',
       commentEditor: null,
      comments: this.data.data.cardcomment,       // list of comments
      newComment: '',     // text of the new comment
      loading: false,
      authUserId: document.querySelector('meta[name="id"]')?.getAttribute('content') || null,
   editingComment: null,
      editEditor: null,
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
     filteredUsers() {
    return this.filteredCardMembers.find(m => m.user?.id == this.authUserId);
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
   reversedComments() {
    return [...this.comments].reverse();
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
     this.fetchCard();
   
 this.localUploads = [...(this.data?.data?.upload || [])];
const cover = this.data.data.upload.find(file => file.f_cover == 1);
if (cover) {
this.coverImage = `http://task.wbsoftech.com/storage/app/public/${cover.file_path}`;
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
this.availableLabels=(this.data.data.labels);
  this.localChatUser = [...(this.data.data?.chatUser || [])];
  this.localWorkers = [...(this.data.data?.workers || [])];
  this.initSocket(); 
   this.editor = new Editor({
      extensions: [StarterKit,  Image], 
      content: this.originalDescription,
    })
  },
 beforeUnmount() {
    if (this.editor) this.editor.destroy()
      if (this.commentEditor) this.commentEditor.destroy()
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
    async  fetchCard() {
      try {
        const response = await fetch(`http://task.wbsoftech.com/api/notify/${this.data.data.card.id}/${this.authUserId}`);
        if (!response.ok) throw new Error("Network error");
        const data = await response.json();
this.socket.emit('cardupdate');
      } catch (error) {
        console.error("Error loading card data:", error);
      }

    },
     canEditComment(comment) {
    if (!comment?.user || !this.authUserId) return false;
  const commentUserId = parseInt(comment.user.id);
  const currentUserId = parseInt(this.authUserId);
  if (commentUserId !== currentUserId) return false;

  return new Date() - new Date(comment.created_at) < 6 * 60 * 60 * 1000;
  },
 startEditing(comment) {
      this.editingComment = comment;
      this.editEditor = new Editor({
        content: comment.content,
        extensions: [StarterKit,Image],
      });
    },
    startEditingComment() {
  this.localComment =  ''
  this.isEditingComment = true

  this.commentEditor = new Editor({
    content: this.localComment,
    extensions: [
      StarterKit,
      Image,
      Mention.configure({
        HTMLAttributes: {
          class: 'mention',
        },
        suggestion: {
          items: ({ query }) => {
            const search = typeof query === 'string' ? query.toLowerCase() : ''
            return this.filteredCardMembers
              .filter(member => member.user?.name?.toLowerCase().includes(search))
              .map(member => ({
                id: member.user.id,
                label: member.user.name,
              }))
          },

          render: () => {
            let component
            let popup

            return {
              onStart: props => {
                component = document.createElement('div')
                component.classList.add(
                  'bg-white',
                  'border',
                  'rounded',
                  'shadow-lg',
                  'text-sm',
                  'z-50'
                )
                component.style.position = 'absolute'
                component.style.zIndex = 9999
                component.style.padding = '4px'

                props.items.forEach(item => {
                  const el = document.createElement('div')
                  el.textContent = item.label
                  el.className = 'px-2 py-1 hover:bg-blue-100 cursor-pointer'
                  el.addEventListener('click', () => {
                    props.command({ id: item.id, label: item.label })
                  })
                  component.appendChild(el)
                })

                document.body.appendChild(component)
                popup = props.clientRect
                updatePosition()
              },
              onUpdate(props) {
                component.innerHTML = ''
                props.items.forEach(item => {
                  const el = document.createElement('div')
                  el.textContent = item.label
                  el.className = 'px-2 py-1 hover:bg-blue-100 cursor-pointer'
                  el.addEventListener('click', () => {
                    props.command({ id: item.id, label: item.label })
                  })
                  component.appendChild(el)
                })
                popup = props.clientRect
                updatePosition()
              },
              onExit() {
                component.remove()
              },
            }

            function updatePosition() {
              if (popup && component) {
                const box = popup()
                if (box) {
                  component.style.top = box.top + window.scrollY + 'px'
                  component.style.left = box.left + window.scrollX + 'px'
                }
              }
            }
          },
        },
      }),
    ],
    editorProps: {
      attributes: {
        class: 'prose prose-sm p-2 border border-gray-300 rounded min-h-[80px]',
      },
    },   
  })
},
    cancelCommentEditing() {
      this.editingComment = null;
      if (this.editEditor) {
        this.editEditor.destroy();
        this.editEditor = null;
      }
    },
    async submitEdit() {
      const html = this.editEditor.getHTML();
      const id = this.editingComment.id;
      try {
        const res = await fetch(`http://task.wbsoftech.com/api/comments/${id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ content: html }),
        });

        if (!res.ok) throw new Error("Update failed");
        const result = await res.json();
        this.socket.emit('commentupdate', result.comment);
        this.editingComment.content = html;
        this.cancelCommentEditing();
      } catch (err) {
        console.error("❌ Error updating comment:", err);
      }
    },
    async deleteComment(id) {
      if (!confirm("Delete this comment?")) return;

     try {
    const res = await fetch(`http://task.wbsoftech.com/api/comments/${id}/delete`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
    });
    if (!res.ok) throw new Error("Delete failed");
    const result = await res.json();
    this.socket.emit('commentdelete', id, this.data.data.card.id  );
  } catch (err) {
    console.error("❌ Error deleting comment:", err);
  }
    },
   postComment() {
      const html = this.commentEditor.getHTML()
      if (!html || html === '<p></p>') return
      this.loading = true
      const response =  fetch('http://task.wbsoftech.com/api/comments', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            content: html,
            card_id: this.data.data.card.id, 
            user_id: this.authUserId,
            board_id: this.data.data.board.id
          })
        })
.then(response => {
  if (!response.ok) {
    throw new Error('Failed to submit comment');
  }
  return response.json(); 
})
.then(result => { 
  const user = this.filteredUsers?.user;
  result.comment.user = user || {
    name: 'Unknown',
    image_path: null
  };
   this.socket.emit('commentinsert', result.comment);       
     })
.catch(err => {
        console.error('❌ Error submitting comment:', err);
      });
         setTimeout(() => {
        this.commentEditor.commands.clearContent()
        this.loading = false
      }, 300)
    },    
    formatDate(dateStr) {
      const date = new Date(dateStr)
      return date.toLocaleString()
    },
  getFullImagePath(path) {
       const base = 'http://task.wbsoftech.com/';
    if (path) {
      const shortPath = path.slice(0, 2); 
      return base + '/' + path;
    }
    return ;
  },
  getInitials(name) {
      if (!name) return '';
      const parts = name.trim().split(' ');
      const first = parts[0]?.[0] || '';
      const last = parts[1]?.[0] || '';
      return (first + last).toUpperCase();
    },
  handleCheckbox(label) {
    const isChecked = this.selectedLabelIds.includes(label.id);
if (!label.card_id) {
    label.card_id = this.data.data.card.id;
  }
    const newStatus = isChecked ? 0 : 1;
    if (isChecked) {
      fetch(`http://task.wbsoftech.com/api/update-label/${label.id}`, {
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
      })
      .catch(err => console.error(err));
    } else {
      fetch(`http://task.wbsoftech.com/api/update-label/${label.id}`, {
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
        await fetch(`http://task.wbsoftech.com/api/card/${this.data.data.card.id}/description`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ description: html }),
        })  
  this.originalDescription = html
    this.socket.emit('card-description-updated', {
      cardId: this.data.data.card.id,
      description: html
    })
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
  if (this.editor) {
    this.editor.chain().focus().toggleBulletList().run();
  }
},
  toggleBulletListt() {
  if (this.commentEditor) {
     this.commentEditor.chain().focus().toggleBulletList().run();
  }
},
 toggleBoldd() {
      this.commentEditor.chain().focus().toggleBold().run();
    },
    toggleItalicc() {
      this.commentEditor.chain().focus().toggleItalic().run();
    },
    buttonClass(format) {
      const active = this.commentEditor?.isActive(format);
      return `px-2 py-1 border rounded ${active ? 'bg-gray-800 text-white' : 'bg-gray-100 text-black'}`;
    },
addImageToComment() {
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
      const response = await fetch('http://task.wbsoftech.com/api/upload-image', {
        method: 'POST',
        body: formData,
      });
      if (!response.ok) throw new Error('Upload failed');
      const result = await response.json();
      const imageUrl = `http://task.wbsoftech.com/storage/app/public/${result.uploaded_files.file_path}`;
      this.commentEditor.chain().focus().setImage({ src: imageUrl }).run();
socket.emit('card-file-uploaded', {
      cardId: this.data.data.card.id,
      file: result.uploaded_files       
    });
    } catch (err) {
      console.error('❌ Upload failed:', err);
    }
  };
  input.click();
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
      const response = await fetch('http://task.wbsoftech.com/api/upload-image', {
        method: 'POST',
        body: formData,
      });
      if (!response.ok) {
        throw new Error('Upload failed');
      }
    const result = await response.json();
    const imageUrl = `http://task.wbsoftech.com/storage/app/public/${result.uploaded_files.file_path}`;
      this.editor.chain().focus().setImage({ src: imageUrl }).run();
      this.upload.push(result.uploaded_files);
socket.emit('card-file-uploaded', {
      cardId: this.data.data.card.id,
      file: result.uploaded_files    
    });
    } catch (err) {
      console.error('❌ Upload failed:', err);
    }
  };
  input.click();
}, 
 uploadAttachments() {
  const input = document.createElement('input');
  input.type = 'file';
  input.accept = '*/*'; 
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
      const response = await fetch('http://task.wbsoftech.com/api/upload-attachments', {
        method: 'POST',
        body: formData,
      });
      if (!response.ok) throw new Error('Upload failed');
      const result = await response.json();  
    if (Array.isArray(result.uploaded_files)) {
  this.data.data.upload.push(...result.uploaded_files);
}   socket.emit('card-file-uploaded', {
      cardId: this.data.data.card.id,
      file: result.uploaded_files // include id, name, path, etc.
   });
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

backToLabels() {
      this.showLabelSelector = true;
      this.showLabelEditor = false;
      this.editingLabel = null;
      this.$nextTick(() => {
        const input = this.$el.querySelector('input[placeholder="Search labels..."]');
        if (input) input.focus();
      });
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
    id: Date.now(), 
    name: 'New Label',
    color: this.colorOptions[0] 
  };
  this.labelTitle = this.editingLabel.name;
  this.labelColor = this.editingLabel.color;
  
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
      const formData = new FormData();    
      formData.append('title', this.labelTitle);   
    formData.append('color', this.labelColor);
formData.append('status', 0);
     const response = fetch('http://task.wbsoftech.com/api/update-label/' + parseInt(this.editingLabel.id), {
        method: 'POST',
        body: formData,
      });
      this.editingLabel.id = this.labelId;
      this.editingLabel.title = this.labelTitle;
      this.editingLabel.color = this.labelColor;

  this.socket.emit('label-updated', this.editingLabel);
    } else {
      fetch(`http://task.wbsoftech.com/api/labels/insert`, {
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
         return res.json();    
      })
      .then(data => {
    const newLabel = {
      id: data.label.id,
      card_id: this.data.data.card.id,
      title: this.labelTitle,
      color: this.labelColor
      };
      this.socket.emit('label-created', newLabel);
       })     
    }    
    this.showLabelEditor = false;
    this.editingLabel = null;
  },
  
  deleteLabel() {
    if (confirm('Are you sure you want to delete this label?')) {
      let superid;
       if (this.editingLabel && 'board_card_id' in this.editingLabel) {
   superid = (
    this.editingLabel.board_card_id == '0' ||
    this.editingLabel.board_card_id == 0 ||
    this.editingLabel.board_card_id == null
  ) ? 0 : this.editingLabel.id;
} else {
  superid = this.editingLabel.id;
}

    fetch(`http://task.wbsoftech.com/api/labels/${this.editingLabel.id}`, {
          method: 'POST',
          headers: {'Content-Type': 'application/json','Accept': 'application/json',},
          body: JSON.stringify({superid: superid }),           
        })
         .then(response => response.json())
         .then(data => {
      // Success handling
      alert('Label deleted successfully');
       this.socket.emit('delete-label', data.data.id, this.data.data.card.id);
    })
    .catch(error => {
      console.error(error);
      alert('An error occurred while deleting the label.');
    });
     this.backToLabels();
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
  fetch(`http://task.wbsoftech.com/api/card/${this.data.data.card.id}/makeCover/${this.selectedFile.id}`, {
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
      fetch(`http://task.wbsoftech.com/api/card/${this.data.data.card.id}/upload/${this.selectedFile.id}`, {
          method: 'POST',
        headers: { 'Content-Type': 'application/json' },
      })
        .then(res => res.json())
        .then(data => {
          this.showDeleteModal = false
          this.selectedFile = {}
       this.socket.emit("card-file-deleted", {
        cardId: this.data.data.card.id,
       fileId: data.file_id,
      });
 this.removeImageFromDescription(data.file_path);
    if (this.coverImage?.includes(data.file_path)) {
    this.coverImage = null;
    if (Array.isArray(this.upload)) {
      this.upload.forEach(f => {
        if (f.f_cover == 1) f.f_cover = 0;
      });
    }
  }
          this.openMenu = null
        })
        .catch(err => {
          console.error('Delete failed:', err)
          this.showDeleteModal = false        
        })
    },
    removeImageFromDescription(deletedFilePath) {
  let currentHTML = this.editor?.getHTML?.() || this.data.data.card.description;
  const div = document.createElement('div');
  div.innerHTML = currentHTML;
  const images = div.querySelectorAll('img');
  images.forEach(img => {
    if (img.src.includes(deletedFilePath)) {
      img.remove();
    }
  });
  const newHTML = div.innerHTML;
  if (this.editor) {
    this.editor.commands.setContent(newHTML);
  }
  this.data.data.card.description = newHTML;
 
},


  fileUrl(path) {
   return `http://task.wbsoftech.com/storage/app/public/${path}`;
  },
    initSocket() {
      // this.socket = io("http://localhost:3000"); 
this.socket = io("http://task.wbsoftech.com/", {
        path: "/socket.io",
        transports: ["websocket", "polling"]
});
      this.socket.on("connect", () => {
          this.clientId = this.socket.id;
          const cardId = this.data?.data?.card?.id;
        this.socket.emit("join-card", this.data.data.card.id); 
  
      });

  this.socket.on('label-created', (label) => {
    this.availableLabels.push(label);
  });

  this.socket.on('label-updated', (label) => {
    const index = this.availableLabels.findIndex(l => l.id === label.id);
    if (index !== -1) {
      this.availableLabels[index] = label;
    }
  });
this.socket.on('label-Checked-updated', ({ label, clientId }) => {
   if (clientId === this.clientId) {
    return;
  }
 const existing = this.data.data.cardLabels.find(l => l.id === label.id);
  if (existing) {
    Object.assign(existing, label);
  } else {
    this.data.data.cardLabels.push(label);
  }
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
});
        socket.on("card-file-uploaded", (data) => {
            const cardId = this.data?.data?.card?.id;
    if (data.cardId == cardId) {       
const files = Array.isArray(data.file) ? data.file : [data.file];
this.localUploads = [...this.localUploads, ...files];
    }
  });
this.socket.on("cover-updated", (payload) => {
    if (payload.cardId == this.data.data.card.id) {
      const newCover = payload.cover;
      this.coverImage = `http://task.wbsoftech.com/storage/app/public/${newCover.file_path}`;
      this.data.data.upload.forEach(f => f.f_cover = f.id === newCover.id ? 1 : 0);
    }
  });
this.socket.on("card-file-deleted", ({ cardId, fileId }) => {
  if (this.data?.data?.card?.id == cardId) {
    this.localUploads = this.localUploads.filter(f => f.id != fileId);
  }
});
    this.socket.on("member-add", (user) => {
     const exists = this.localChatUser.some(m => m.user.id == user.user.id);
      if (!exists) {
        this.localChatUser.push({ user: { id: user.user.id, name: user.user.name } });
      }            
      this.localChatUser = this.localChatUser.filter(Boolean);
      


});
  this.socket.on('card-description-updated', (data) => {
    if (data.cardId == this.data.data.card.id) {
      this.data.data.card.description = data.description
            this.originalDescription = data.description

   if (this.editor?.commands) {

   this.editor.commands.setContent(data.description, false);
}
   
    }
  });



this.socket.on("commentinserted", (comment) => {
   this.comments.push(comment);
});


this.socket.on("commentupdated", (comment) => {
  const index = this.comments.findIndex(c => c.id == comment.id);
 if (index != -1) {
    this.comments[index].content = comment.content;
  }
});


this.socket.on("commentdeleted", (id) => {
  this.comments = this.comments.filter(c => c.id != id);
});

this.socket.on("member-remove", (userId) => {
  this.localChatUser = this.localChatUser.filter(m => m.user.id !== userId.userId);
});

 this.socket.on('label-deleted', (deletedId) => {
    this.availableLabels = this.availableLabels.filter(
        l => l.id != deletedId
      );
      this.selectedLabelIds = this.selectedLabelIds.filter(
        id => id != deletedId
      );
    });

    },

    async addUserToCard(worker) {
      try {
        await axios.post('http://task.wbsoftech.com/api/team/user/add', {
          card_id: this.data.data.card.id,
          user_id: worker.id,
          status: 'Member'
        });

    this.localChatUser = this.localChatUser.filter(Boolean);

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
        await axios.post(`http://task.wbsoftech.com/api/board/${this.data.data.board.id}/card/${this.data.data.card.id}/leave`, {
          user_id: member.user.id
        });


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
::v-deep ul, ol {
  list-style: disc;
  margin-left: 1.25rem;
  padding-left: 1.25rem;
}
::v-deep li {
  margin: 0.25em 0;
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
}.editor-btn {
  background-color: #f4f5f7;
  border: 1px solid #dfe1e6;
  border-radius: 6px;
  padding: 6px;
  cursor: pointer;
  transition: background 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}
.editor-btn:hover {
  background-color: #ebecf0;
}::v-deep .editor-box > div {
  min-height: 80px;
  border: 1px solid #dfe1e6;
  border-radius: 6px;
  padding: 8px;  
  background-color: white;
}
</style>
