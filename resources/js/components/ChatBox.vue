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

         <div v-if="showRecordingPlayer && !isRecording" class="flex flex-col p-3 mb-2 bg-gray-100 rounded-lg">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <button @click="cancelRecording" class="text-red-500 hover:text-red-700">
              <font-awesome-icon :icon="['fas', 'trash']" class="w-5 h-5" />
            </button>
            <span class="text-sm text-gray-600">{{ formatPlaybackTime }} / {{ formatTotalDuration }}</span>
            <button @click="togglePlayPause" class="text-gray-700 hover:text-gray-900 ml-4">
              <font-awesome-icon :icon="['fas', isPlaying ? 'pause' : 'play']" class="w-5 h-5" />
            </button>
          </div>
          <button @click="sendRecording" class="text-blue-500 hover:text-blue-700">
            <font-awesome-icon :icon="['fas', 'paper-plane']" class="w-5 h-5" />
          </button>
        </div>

        <div class="w-full h-2 bg-gray-300 rounded-full mt-2 relative cursor-pointer" @click="seekAudio">
  <div class="h-full bg-gray-600 rounded-full" :style="{ width: playbackProgress + '%' }"></div>
  <div
    class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-gray-800 rounded-full shadow"
    :style="{ left: `calc(${playbackProgress}% - 8px)` }"
    v-if="audioUrl"
  ></div>
</div>

      <audio
  ref="audioPlayer"
  :src="audioUrl"
  class="hidden"
  @ended="isPlaying = false"
  @timeupdate="updatePlaybackTime"
  @loadedmetadata="setAudioDuration"
  @error="handleAudioError"
></audio>
      </div>

      <div v-if="isRecording" class="flex items-center justify-between p-3 mb-2 bg-red-100 rounded-lg">
        <div class="flex items-center space-x-3">
          <button @click="cancelRecording" class="text-red-600 hover:text-red-800">
            <font-awesome-icon :icon="['fas', 'trash']" class="w-5 h-5" />
          </button>
          <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div> <span class="text-sm text-red-700 font-semibold">Recording... {{ formatRecordingTime }}</span>
        </div>
        <button @click="stopRecording" class="ml-2 text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-full">
          <font-awesome-icon :icon="['fas', 'stop']" class="w-4 h-4"/> Stop
        </button>
      </div>

      <div v-else-if="!showRecordingPlayer" class="flex items-center">
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
          />
          <button @click="sendMessage" class="ml-2 text-gray-500 hover:text-gray-700">
            📨
          </button>
          <button @click="startRecording" class="ml-2 text-gray-500 hover:text-gray-700">
            <font-awesome-icon :icon="['fas', 'microphone']" class="w-4 h-4"/>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// (Your existing script content remains mostly the same)

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
      recordingDuration: 0, // This is for the recording time display
      isPlaying: false, // State for playback
      playbackCurrentTime: 0, // Current position of playback
      audioDuration: 0,
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
    },
   formatPlaybackTime() {
      const minutes = Math.floor(this.playbackCurrentTime / 60);
      const seconds = Math.floor(this.playbackCurrentTime % 60);
      return `<span class="math-inline">\{minutes\.toString\(\)\.padStart\(2, '0'\)\}\:</span>{seconds.toString().padStart(2, '0')}`;
    },
    formatTotalDuration() {
      if (isNaN(this.audioDuration) || this.audioDuration === 0) {
        return '00:00';
      }
      const minutes = Math.floor(this.audioDuration / 60);
      const seconds = Math.floor(this.audioDuration % 60);
      return `<span class="math-inline">\{minutes\.toString\(\)\.padStart\(2, '0'\)\}\:</span>{seconds.toString().padStart(2, '0')}`;
    },
    playbackProgress() {
      if (this.audioDuration > 0 && !isNaN(this.audioDuration)) {
        return (this.playbackCurrentTime / this.audioDuration) * 100;
      }
      return 0;
    },
  
  },
  methods: {
    triggerFile() {
      this.$refs.fileInput.click();
    },
    handleFile(event) {
      const file = event.target.files[0];
      if (file) {
        console.log("File selected:", file.name);
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
    // We no longer need toggleRecording as start/stop are separate buttons now
    // async toggleRecording() {
    //   if (this.isRecording) {
    //     this.stopRecording();
    //   } else {
    //     await this.startRecording();
    //   }
    // },
     async startRecording() {
      try {
        this.cancelRecording(); // Clear any previous recording/player

        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        this.mediaRecorder = new MediaRecorder(stream);
        this.audioChunks = [];
        this.recordingDuration = 0;
        this.isRecording = true;
        this.isPlaying = false; // Ensure playback is off
        this.playbackCurrentTime = 0; // Reset playback time
        this.audioDuration = 0; // Reset audio duration

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
        this.isRecording = false; // Hide recording interface
        console.log('Voice recording stopped.');
      }
    },
    onRecordingDataAvailable(event) {
      this.audioChunks.push(event.data);
    },
    onRecordingStop() {
      const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
      this.audioUrl = URL.createObjectURL(audioBlob);
      this.showRecordingPlayer = true; // Show playback player
      console.log('Audio URL:', this.audioUrl);
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
    updatePlaybackTime() {
      const audioPlayer = this.$refs.audioPlayer;
      if (audioPlayer) {
        this.playbackCurrentTime = audioPlayer.currentTime;
      }
    },
    setAudioDuration() {
      const audioPlayer = this.$refs.audioPlayer;
      if (audioPlayer && isFinite(audioPlayer.duration)) {
        this.audioDuration = audioPlayer.duration;
      } else {
         this.audioDuration = 0;
      }
    },
    seekAudio(event) {
      const progressBar = event.currentTarget;
      const clickX = event.clientX - progressBar.getBoundingClientRect().left;
      const progressBarWidth = progressBar.offsetWidth;
      const seekTime = (clickX / progressBarWidth) * this.audioDuration;

      const audioPlayer = this.$refs.audioPlayer;
      if (audioPlayer && !isNaN(seekTime) && isFinite(seekTime)) {
        audioPlayer.currentTime = seekTime;
        if (!this.isPlaying) {
          audioPlayer.play().catch(error => {
            console.error('Error seeking and playing audio:', error);
            alert('Could not seek and play audio.');
          });
          this.isPlaying = true;
        }
      }
    },
    cancelRecording() {
      this.showRecordingPlayer = false;
      this.isRecording = false;
      this.audioUrl = null;
      this.recordingDuration = 0;
      this.playbackCurrentTime = 0; // Reset playback state
      this.audioDuration = 0; // Reset playback state
      this.isPlaying = false;
      if (this.$refs.audioPlayer) {
        this.$refs.audioPlayer.pause();
        this.$refs.audioPlayer.currentTime = 0;
      }
      if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
        this.mediaRecorder.stop();
        this.mediaRecorder = null;
      }
      clearInterval(this.recordingTimer);
      this.audioChunks = [];
      console.log('Recording cancelled.');
    },
    sendRecording() {
      if (this.audioUrl) {
        this.messages.push({
          id: Date.now(),
          text: `[Audio Message - ${this.formatTotalDuration}]`, // Use total duration for sent message
          fromMe: true,
        });
        this.cancelRecording();
        console.log('Recording sent!');
      } else {
        alert('No recording to send!');
      }
    }
  }
};
</script>