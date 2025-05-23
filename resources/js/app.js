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
app.mount("#appp");