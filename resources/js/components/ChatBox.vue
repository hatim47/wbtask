<template>
  <div class="w-full flex flex-col bg-white">
    <div class="flex items-center justify-between px-4 py-2 border-b">
      <div class="flex items-center space-x-3">
        <img v-if="user.avatar" :src="user.avatar" class="w-10 h-10 rounded-full" />
        <div>
          <div class="font-semibold">{{ user.name }}</div>
          <div class="text-xs text-green-500">● <span class="text-black">Online</span></div>
        </div>
      </div>
      <div class="flex items-center space-x-2">
        <button class="px-3 py-2 items-center text-sm bg-stone-100 rounded">
          <font-awesome-icon :icon="['fas', 'phone']" class="w-3 h-3 px-1"/> Call
        </button>
        <button class="px-3 py-2 text-sm bg-stone-100 rounded">
          <font-awesome-icon :icon="['fas', 'video']" class="w-3 h-3 px-1"/> Video Call
        </button>
      </div>
    </div>

    <div class="flex-1 h-1/3 overflow-y-auto p-4 space-y-6">
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

    <div class="flex flex-col p-3 border-t bg-white">
      <div v-if="showRecordingPlayer" class="flex items-center justify-between p-3 mb-2 bg-gray-100 rounded-lg">
        <div class="flex items-center space-x-3">
          <button @click="cancelRecording" class="text-red-500 hover:text-red-700">
            <font-awesome-icon :icon="['fas', 'trash']" class="w-5 h-5" />
          </button>
          <span class="text-sm text-gray-600">{{ formatRecordingTime }}</span>
          <div class="flex-1 h-1 bg-gray-300 rounded-full mx-2">
            <div class="h-full bg-gray-600 rounded-full" :style="{ width: '0%' }"></div>
          </div>
        </div>
        <div class="flex items-center space-x-2">
          <button @click="togglePlayPause" class="text-gray-700 hover:text-gray-900">
            <font-awesome-icon :icon="['fas', isPlaying ? 'pause' : 'play']" class="w-5 h-5" />
          </button>
          <button @click="sendRecording" class="text-blue-500 hover:text-blue-700">
            <font-awesome-icon :icon="['fas', 'paper-plane']" class="w-5 h-5" />
          </button>
        </div>
        <audio ref="audioPlayer" :src="audioUrl" class="hidden" @ended="isPlaying = false"></audio>
      </div>


      <div class="flex items-center">
        <button @click="triggerFile" class="text-gray-500 hover:text-gray-700 mr-3">
          📎
        </button>
        <input
          ref="fileInput"
          type="file"
          class="hidden"
          @change="handleFile"
        />

        <div class="flex items-center flex-1 rounded-full border px-4 py-2 bg-gray-100">
          <input
            v-model="newMessage"
            @keydown.enter="sendMessage"
            type="text"
            placeholder="Type a message"
            class="flex-1 bg-transparent text-sm outline-none"
            :disabled="isRecording || showRecordingPlayer"
          />
          <button @click="sendMessage" class="ml-2 text-gray-500 hover:text-gray-700" :disabled="isRecording || showRecordingPlayer">
            📨
          </button>
          <button @click="toggleRecording" class="ml-2 text-gray-500 hover:text-gray-700">
            <font-awesome-icon :icon="['fas', isRecording ? 'stop' : 'microphone']" class="w-4 h-4"/>
          </button>
        </div>
      </div>
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
        { id: 9, text: "woohoooo 🔥", fromMe: false },
        { id: 10, text: "omg, this is amazing", fromMe: false },
        { id: 11, text: "perfect! ✅", fromMe: false },
        { id: 12, text: "Wow, this is really epic", fromMe: false },
        { id: 13, text: "How are you?", fromMe: true },
        { id: 14, text: "woohoooo", fromMe: true },
        { id: 15, text: "Haha oh man", fromMe: true },
        { id: 16, text: "Haha that's terrifying 😂", fromMe: true },
        { id: 17, text: "I'll be there in 2 mins ⏰", fromMe: false },
        { id: 18, text: "woohoooo 🔥", fromMe: false }
      ],
      isRecording: false,
      audioUrl: null,
      showRecordingPlayer: false,
      mediaRecorder: null,
      audioChunks: [],
      recordingStartTime: null,
      recordingTimer: null,
      recordingDuration: 0,
      isPlaying: false,
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
    },
    formatRecordingTime() {
      const minutes = Math.floor(this.recordingDuration / 60);
      const seconds = this.recordingDuration % 60;
      return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
  },
  methods: {
    triggerFile() {
      this.$refs.fileInput.click();
    },
    handleFile(event) {
      const file = event.target.files[0];
      if (file) {
        console.log("File selected:", file.name);
        // You would typically upload this file to a server
        // For demonstration, let's add a message indicating a file was sent
        this.messages.push({
          id: Date.now(),
          text: `File attached: ${file.name}`,
          fromMe: true
        });
      }
    },
    sendMessage() {
      if (!this.newMessage.trim()) return;
      this.messages.push({
        id: Date.now(),
        text: this.newMessage,
        fromMe: true
      });
      this.newMessage = '';
    },
    async toggleRecording() {
      if (this.isRecording) {
        this.stopRecording();
      } else {
        await this.startRecording();
      }
    },
    async startRecording() {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        this.mediaRecorder = new MediaRecorder(stream);
        this.audioChunks = [];
        this.recordingDuration = 0;
        this.isRecording = true;
        this.showRecordingPlayer = false; // Hide player when starting new recording
        this.audioUrl = null; // Clear previous audio

        this.mediaRecorder.ondataavailable = this.onRecordingDataAvailable;
        this.mediaRecorder.onstop = this.onRecordingStop;

        this.mediaRecorder.start();
        this.recordingStartTime = Date.now();
        this.recordingTimer = setInterval(() => {
          this.recordingDuration = Math.floor((Date.now() - this.recordingStartTime) / 1000);
        }, 1000);

        console.log('Voice recording started...');
      } catch (error) {
        console.error('Error accessing microphone:', error);
        alert('Could not start recording. Please ensure microphone access is granted.');
      }
    },
    stopRecording() {
      if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
        this.mediaRecorder.stop();
        clearInterval(this.recordingTimer);
        this.isRecording = false;
        console.log('Voice recording stopped.');
      }
    },
    onRecordingDataAvailable(event) {
      this.audioChunks.push(event.data);
    },
    onRecordingStop() {
      const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
      this.audioUrl = URL.createObjectURL(audioBlob);
      this.showRecordingPlayer = true;
      console.log('Audio URL:', this.audioUrl);
      // Stop the stream tracks to release microphone
      if (this.mediaRecorder && this.mediaRecorder.stream) {
        this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
      }
    },
    togglePlayPause() {
      const audioPlayer = this.$refs.audioPlayer;
      if (audioPlayer) {
        if (this.isPlaying) {
          audioPlayer.pause();
        } else {
          audioPlayer.play();
        }
        this.isPlaying = !this.isPlaying;
      }
    },
    cancelRecording() {
      this.showRecordingPlayer = false;
      this.audioUrl = null;
      this.recordingDuration = 0;
      this.isPlaying = false;
      if (this.$refs.audioPlayer) {
        this.$refs.audioPlayer.pause();
        this.$refs.audioPlayer.currentTime = 0;
      }
      console.log('Recording cancelled.');
    },
    sendRecording() {
      if (this.audioUrl) {
        // Here you would typically send the audioBlob to your backend
        // For demonstration, we'll add a message indicating an audio was sent.
        this.messages.push({
          id: Date.now(),
          text: `[Audio Message - ${this.formatRecordingTime}]`,
          fromMe: true,
          // You could also store the audioUrl or a reference to it
          // audioUrl: this.audioUrl
        });
        this.cancelRecording(); // Clear the recording player after sending
        console.log('Recording sent!');
      } else {
        alert('No recording to send!');
      }
    }
  }
};
</script>