// import "./bootstrap";
// import Alpine from "alpinejs";
// import Croppie from "croppie";

// window.Croppie = Croppie;
// window.Alpine = Alpine;
// Alpine.start();
import "./bootstrap";         // Your bootstrap/init file
import Alpine from "alpinejs";
import Croppie from "croppie";
import { createApp } from "vue";
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPhone, faVideo, faMicrophone, faStop, faTrash, faPlay, faPause, faPaperPlane } from '@fortawesome/free-solid-svg-icons';

/* add icons to the library */
library.add(faPhone, faVideo, faMicrophone, faStop, faTrash, faPlay, faPause, faPaperPlane);

// Setup Alpine
window.Alpine = Alpine;
Alpine.start();

// Setup Croppie globally if needed
window.Croppie = Croppie;
import ParentChat from './components/ParentChat.vue';
// Setup Vue
// const app = createApp({
//   data() {
//     return {
//       message: "Hello from Vue!",
//     };
//   },
//   mounted() {
//     console.log("Vue app mounted");
//   },
// });
const app = createApp(ParentChat);

app.component('font-awesome-icon', FontAwesomeIcon);
app.mount("#appp");