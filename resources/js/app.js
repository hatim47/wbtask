// import "./bootstrap";
// import Alpine from "alpinejs";
// import Croppie from "croppie";

// window.Croppie = Croppie;
// window.Alpine = Alpine;
// Alpine.start();
import "./bootstrap";         // Your bootstrap/init file
import Alpine from "alpinejs";
import Croppie from "croppie";
import { createApp, h } from "vue";
import { createVfm } from 'vue-final-modal'
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
import Popup from './components/Popup.vue';
const app = createApp(ParentChat, Popup);
const vfm = createVfm()
app.component('font-awesome-icon', FontAwesomeIcon);
app.use(vfm)
app.mount("#appp");
let currentPopup = null;
window.showPopup = function(data) {
  if (currentPopup) {
    currentPopup.close(); // This should properly clean up the previous popup
  }
  const container = document.createElement("div");
  document.body.appendChild(container);
  const modalApp = createApp({
    render() {
      return h(Popup, {
        data,
        onClose: () => {
          modalApp.unmount();
          container.remove();
          currentPopup = null;
        }
      });
    }
  });
  modalApp.mount(container);
  
  // Store reference to the current popup
  currentPopup = {
    close: () => {
      modalApp.unmount();
      if (container.parentNode) {
        container.remove();
      }
      currentPopup = null;
    },
    container,
    app: modalApp
  };
};

// Close popup when clicking outside (optional)
