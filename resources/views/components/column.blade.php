@props(['teamid', 'isowner'])
<div style="display: none">

    @if (isset($isowner) && $isowner == true)
        <template is-modal="updateColumn">
            <div class="flex flex-col w-full gap-4 p-4">
                <form class="flex flex-col gap-4" method="POST">
                    @csrf
                    <input type="hidden" name="column_id" id="column_id">
                    <x-form.text name="column_name" label="Column's Name" required />
                    <x-form.button class="mt-4" type="submit" primary>Add</x-form.button>
                </form>
            </div>
        </template>

        <template is-modal="deleteColumn">
            <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST">
                @csrf
                <input type="hidden" name="column_id" id="column_id">
                <p class="mb-6 text-lg text-center"> Are you sure you want to delete this column?</p>
                <div class="flex gap-6">
                    <x-form.button type="submit">Yes</x-form.button>
                    <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
                </div>
            </form>
        </template>
    @endif

    <template id="column">
        <div data-role="column"
            class="flex flex-col flex-shrink-0 max-h-full border-2 shadow-lg group h-min border-slate-50 w-72 rounded-xl bg-slate-100">
            <header class="flex items-center gap-2 px-4 py-2 select-none rounded-t-xl" draggable="true">
                <h2 class="w-full overflow-hidden text-sm font-bold truncate"></h2>
                @if (isset($isowner) && $isowner == true)
                    <div type="button" id="col-upd-btn"
                        class="p-2 text-gray-600 transition rounded-full opacity-0 bg-slate-200 hover:bg-slate-300 group-hover:opacity-100 ">
                        <x-fas-pen class="w-[12px] h-[12px]" />
                    </div>
                    <div type="button" id="col-del-btn"
                        class="p-2 text-gray-600 transition rounded-full opacity-0 bg-slate-200 hover:bg-slate-300 group-hover:opacity-100 ">
                        <x-fas-trash class="w-[12px] h-[12px]" />
                    </div>
                @endif
            </header>
            <hr>
            <section class="w-full overflow-hidden overflow-y-auto">
                <div class="flex flex-col gap-3 p-2" id="card-container">

                </div>
            </section>
            
            <button id="btn-add"
                class="flex items-center gap-2 py-1 pl-4 mx-2 mb-2 text-sm transition select-none rounded-2xl hover:bg-slate-200">
                <x-fas-plus class="w-4 h-4" />
                Add Card...
            </button>
        </div>
    </template>
</div>
<div id="modal-views" data-role="modal-manager-container" class="fixed z-40 flex flex-wrap items-center justify-center w-screen h-screen overflow-hidden bg-black bg-opacity-50 backdrop-blur-sm" style="display: none;">

    <div class="min-w-[40rem] max-w-[70%] max-h-[70%] flex flex-col bg-white rounded-xl overflow-hidden">
        <header class="flex items-center justify-between flex-grow-0 w-full px-4 py-2 bg-black">
            <h1 class="text-2xl font-bold text-white" id="modal-title"></h1>
            <svg onclick="closeModal()" class="w-8 h-8 text-white" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Free 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. --><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm79 143c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path></svg>        </header>
        <div class="flex-grow w-full h-full p-4" id="modal-content">
            <div class="flex flex-col w-full gap-4 p-4">
                <form class="flex flex-col items-center justify-center " >
                   
                    <textarea
                          class="w-full px-4 py-2 mb-1 bg-white border border-gray-200 shadow cursor-pointer resize-none select-none line-clamp-3 rounded-xl"
                          id="card" cols="30" rows="3" maxlength="95"></textarea>
                          <input type="file" id="fileUpload" class=" bottom-2 right-2 opacity-0 w-full h-full cursor-pointer">
                          <label for="fileUpload" class=" bottom-2 right-2 bg-blue-500 text-white px-3 py-1 rounded-lg cursor-pointer">📁 Upload</label>
                     
                      <button id="btn-submit"
                          class="flex items-center w-lg gap-2 py-1 pl-4 mb-2 rounded-2xl bg-slate-200">
                          <x-fas-plus class="w-4 h-4" />
                          Add Card...
                      </button>
                  </form>
            </div>
        </div>
    </div>
</div>




@pushOnce('component')


<script>
// function loadNewCard() {
// alert("dsdas");
//     setTimeout(() => {
//     console.log("Emitting 'board-action'...");
//     socket.emit("board-action");
// }, 2000);
//   }
        const columnTemplate = document.querySelector("template#column");
        class Column {
            constructor(board, id, name, cards = []) {
                this.board = board;
                // this.cards = cards;
                console.log(cards);
                const content = columnTemplate.content.cloneNode(true);
                const node = document.createElement("div");
                node.append(content);
                const modal = document.querySelector("#modal-views");
                this.ref = node.children[0];
                   
                this.ref.querySelector("header > h2").textContent = name;
                this.ref.dataset.id = id;
                this.ref.dataset.name = name;
                // const fileUpload = document.getElementById("fileUpload");
                // const fileLabel = document.querySelector("label[for='fileUpload']");
                 const btnAdd = this.ref.querySelector(":scope > button#btn-add");
                // const btnSubmit = this.ref.querySelector(":scope > form >  button#btn-submit");
                // const formAdd = this.ref.querySelector(":scope > form");
              
           
       
                
                // const inputCard = this.ref.querySelector(":scope > form >  textarea#card");
                const cardContainer = this.ref.querySelector("#card-container");
                const colHeader = this.ref.querySelector(":scope > header");

    

                colHeader.setAttribute('draggable', (id != null));

                @if (isset($isowner) && $isowner == true)
                    this.ref.querySelector(":scope > header > #col-upd-btn").addEventListener("click", () => ModalView
                        .show("updateColumn", {
                            name: this.ref.dataset.name,
                            id: this.ref.dataset.id
                        }));
                    this.ref.querySelector(":scope > header > #col-del-btn").addEventListener("click", () => ModalView
                        .show("deleteColumn", {
                            name: this.ref.dataset.name,
                            id: this.ref.dataset.id
                        }));

                    ModalView.onShow("updateColumn", (modal, payload) => {
                        this.board.IS_EDITING = true;
                        const board_id = this.board.ref.dataset.id;
                        const form = modal.querySelector("form");
                        const inputTag = modal.querySelector("input#input-text-column_name");
                        const idTag = modal.querySelector("input#column_id");
                        inputTag.value = payload.name;
                        idTag.value = payload.id;
                        form.action = `{{ url('team/' . $teamid . '/board/${board_id}/column/update') }}`;
                       
                       
                        form.addEventListener("submit", (event) => {
                            loadNewCards();
                            socket.emit("board-action"); 
    PageLoader.show();

    // You can also call loadNewCards() here if needed
});
                    });
                    ModalView.onShow("deleteColumn", (modal, payload) => {
                        this.board.IS_EDITING = true;
                        const board_id = this.board.ref.dataset.id;
                        const form = modal.querySelector("form");
                        const idTag = modal.querySelector("input#column_id");
                        idTag.value = payload.id;
                        form.action = `{{ url('team/' . $teamid . '/board/${board_id}/column/delete') }}`;
                        socket.emit("board-action"); 
                       
                                       
                        form.addEventListener("submit", (event) => {
                            loadNewCards();
                           socket.emit("board-action"); 
   PageLoader.show();

   // You can also call loadNewCards() here if needed
});
                    });

                    ModalView.onClose("updateColumn", () => {
                        this.board.IS_EDITING = false;
                    });
                    ModalView.onClose("deleteColumn", () => {
                        this.board.IS_EDITING = false;
                    });
                @endif

                colHeader.addEventListener('dragstart', () => {
                    this.board.IS_EDITING = true;
                    this.ref.classList.add("is-dragging");
                    this.ref.classList.add("opacity-50");
                });

                colHeader.addEventListener('dragend', () => {
                    this.ref.classList.remove("is-dragging");
                    colHeader.setAttribute('draggable', false);
                    this.ref.classList.remove("opacity-50");
                    const board_id = this.board.ref.dataset.id;

                    ServerRequest.post(`{{ url('team/' . $teamid . '/board/${board_id}/column/reorder') }}`, {
                            middle_id: this.ref.dataset.id,
                            right_id: this.ref.nextElementSibling?.dataset?.id || null,
                            left_id: this.ref.previousElementSibling?.dataset?.id || null,
                        })
                        .then((response) => {
                            console.log("Server response received:", response);
                            this.board.IS_EDITING = false;
                            colHeader.setAttribute('draggable', true);
                            loadNewCards();
                        }) 
                        .catch((error) => {
    console.error("Error during ServerRequest.post:", error); // Log any errors
});
                });
   

                document.addEventListener("click", (event) => {
    const btnAdd = event.target.closest("#btn-add"); // Find the clicked button
    if (!btnAdd) return; // If it's not an "Add" button, exit

    const column = btnAdd.closest("[data-role='column']"); // Get the column of the clicked button
    const modal = document.querySelector("#modal-views"); // Modal reference

    if (!column || !modal) return;

    const columnId = column.dataset.id; // Get column ID
    console.log("🆕 Add button clicked in column:", columnId);

    // Show modal and track editing state
    board.IS_EDITING = true;
    btnAdd.style.display = "none";
    modal.style.display = "flex";

    // Store the selected column ID in modal for later use
    modal.dataset.columnId = columnId;
});
                
                document.addEventListener("DOMContentLoaded", () => {
    document.querySelector("#modal-views").addEventListener("click", (event) => {
        if (event.target.id == "btn-submit") {
          
            event.preventDefault(); // Prevent default form submission
            handleFormSubmit();
        }
    });
  
});
document.addEventListener("DOMContentLoaded", function() {
    function closeModal() {
     document.getElementById("modal-views").style.display = "none";
     board.IS_EDITING = false;
     let btnAddd = document.getElementById("btn-add");
    if (btnAddd) {
      btnAddd.style.display = "flex";
    }
    }
    window.closeModal = closeModal; // Ensure it's global
  });


function handleFormSubmit() {
    const submitButton = document.querySelector("#btn-submit");


if (submitButton.disabled) return;
submitButton.disabled = true;
    const inputCard = document.querySelector("#modal-views textarea#card");
    const fileUpload = document.querySelector("#modal-views input#fileUpload");
    const modal = document.querySelector("#modal-views");

    const cardValue = inputCard.value.trim();
    if (!cardValue && fileUpload.files.length === 0) {
        alert("Please enter text or upload a file!");
        return;
    }

    const formData = new FormData();
    formData.append("name", cardValue);

    if (fileUpload.files.length > 0) {
        for (const file of fileUpload.files) {
            formData.append("images[]", file);
        }
    }

    // Assuming `this.board.ref.dataset.id` and `this.ref.dataset.id` are available
    const board_id = document.querySelector("[data-role='board']").dataset.id;
    // const column_id = document.querySelector("[data-role='column']").dataset.id;
    const column_id = document.querySelector("#modal-views").dataset.columnId;
    // const board_id = this.board.ref.dataset.id;
    alert(column_id);
    // const column_id = this.ref.dataset.id;
    ServerRequest.post(
        `{{ url('team/' . $teamid . '/board/${board_id}/column/${column_id}/card') }}`,
        formData
    )
    .then((response) => {
        console.log("✅ cCard added:", response.data);
        location.reload();
          
        // Create new card UI
        // const newCard = new Card(response.data.id, cardValue);
        // newCard.mountTo(document.querySelector(`#column[data-id="${column_id}"] #card-container`));

        // Reset form fields
        inputCard.value = "";
        fileUpload.value = "";

        // Close modal
        modal.style.display = "none";
        btnAdd.style.display = "flex";  
    })
    .catch((error) => {
        console.error("❌ Error adding card:",  error.response.data);
    })
    .finally(() => {
        submitButton.disabled = false; // Re-enable after request completes
    });
}

cardContainer.addEventListener("dragover", (e) => {
                    e.preventDefault();
                    let currentDraggingCard = DOM.find("div[data-role='card'].is-dragging");
                    if (currentDraggingCard == null) return;
                    let closestBottomCardFromMouse = null;
                    let closestOffset = Number.NEGATIVE_INFINITY;
                    let staticCards = cardContainer.querySelectorAll(
                        ":scope > div[data-role='card']:not(.is-dragging)");

                    //calculate closestTask
                    staticCards.forEach((card) => {
                        let {
                            top,
                            bottom
                        } = card.getBoundingClientRect();
                        let offset = event.clientY - ((top + bottom) / 2);
                        if (offset < 0 && offset > closestOffset) {
                            closestOffset = offset;
                            closestBottomCardFromMouse = card;
                        }
                    });
                    if (closestBottomCardFromMouse) {
                        cardContainer.insertBefore(
                            currentDraggingCard,
                            closestBottomCardFromMouse
                        );
                    } else {
                        cardContainer.appendChild(currentDraggingCard);
                    }
                })   
                function getRandomColor() {
    const colors = ["#FF5733", "#29B6F6", "#FF9800", "#4CAF50", "#9C27B0", "#F44336", "#3F51B5"];
    return colors[Math.floor(Math.random() * colors.length)];
}
function getContrastColor(hex) {
    // Convert hex to RGB
    let r = parseInt(hex.substring(1, 3), 16);
    let g = parseInt(hex.substring(3, 5), 16);
    let b = parseInt(hex.substring(5, 7), 16);

    // Calculate brightness using luminance formula
    let brightness = (r * 299 + g * 587 + b * 114) / 1000;

    // Return black for bright colors, white for dark colors
    return brightness > 128 ? "#000000" : "#FFFFFF";
}


                for (const cardData of cards) {
                    const card = new Card(cardData.id, cardData.name,cardData.images,cardData.files,cardData.users,cardData.lables,cardData.notif,this.board);
                //    console.log(cardData.images,"image");
                // console.log(cardData.users,"users");
                   if (cardData.images.length > 0) {
    const imageContainer = document.createElement("div");
    imageContainer.classList.add("image-container");
    // Generate background images
    const firstImage = cardData.images[0]; 
    const imageUrl = `/taskly/storage/app/public/${firstImage.file_path}`;
    imageContainer.style.backgroundImage = `url('${imageUrl}')`;    
    imageContainer.style.backgroundSize = "contain"; // Ensures small images fit
    imageContainer.style.backgroundPosition = "center"; // Centers images
    imageContainer.style.backgroundRepeat = "no-repeat"; // Prevents tiling
    imageContainer.style.width = "100%"; // Make it flexible
    imageContainer.style.height = "150px"; // Adjust height as needed
   

    // Append to the column
    card.ref.prepend(imageContainer);}


const labelContainer = document.createElement("div");
labelContainer.classList.add("flex", "gap-2", "flex-wrap", "mt-2","mb-4");
console.log(cardData.lables,"label");
// Loop through cardData.lables to create labels
cardData.lables.forEach(label => {
    const labelElement = document.createElement("span");
    labelElement.classList.add("px-3", "py-1", "rounded-full", "cursor-pointer", "text-sm", "font-semibold","label-item");
    labelElement.style.backgroundColor = label.color;
    labelElement.dataset.text = label.text;
    // labelElement.textContent = label.text;
    console.log(label,"label");
    // Set text color based on contrast
    labelElement.style.color = getContrastColor(label.color);

    // Apply line-through if status is not 1
    // if (label.status !== 1) {
    //     labelElement.style.textDecoration = "line-through";
    //     labelElement.classList.add("opacity-50");
    // }

    // Click event to toggle status
    // labelElement.addEventListener("click", () => {
    //     if (labelElement.textContent === "") {
    //         labelElement.textContent = label.text; // Show text
    //     } else {
    //         labelElement.textContent = ""; // Hide text
    //     }
    // });

    labelElement.addEventListener("click", (event) => {
        event.stopPropagation();
        document.querySelectorAll(".label-item").forEach(label => {
        if (label.textContent === "") {
            label.textContent = label.dataset.text; // Show text
        } else {
            label.textContent = ""; // Hide text
        }

    });
});


    labelContainer.appendChild(labelElement);
});


   






    const footer = document.createElement("div");
footer.classList.add("card-footer");

const iconContainer = document.createElement("div");
iconContainer.classList.add("icon-container", "flex" ,"flex-row" ,"item-center");
if (cardData.comments == "") {
    
    const attachmentIcon = document.createElement("span");
    attachmentIcon.innerHTML = ``;
}
const attachmentIcon = document.createElement("span");
attachmentIcon.innerHTML = `<svg width="17" height="17" role="presentation" focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4 5C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H4ZM4 9C3.44772 9 3 9.44772 3 10C3 10.5523 3.44772 11 4 11H20C20.5523 11 21 10.5523 21 10C21 9.44772 20.5523 9 20 9H4ZM3 14C3 13.4477 3.44772 13 4 13H20C20.5523 13 21 13.4477 21 14C21 14.5523 20.5523 15 20 15H4C3.44772 15 3 14.5523 3 14ZM4 17C3.44772 17 3 17.4477 3 18C3 18.5523 3.44772 19 4 19H14C14.5523 19 15 18.5523 15 18C15 17.4477 14.5523 17 14 17H4Z" fill="currentColor"></path></svg> `;
const avatarContainer = document.createElement("div");
avatarContainer.classList.add("avatar-group"); // Container for all avatars
avatarContainer.style.display = "flex";
avatarContainer.style.flexDirection = "row";
const userInitialsArray = cardData.users; // Example data
// Function to generate random colors
function getRandomColor() {
    const colors = ["#FF5733", "#33FF57", "#3357FF", "#FF33A8", "#FFC300"];
    return colors[Math.floor(Math.random() * colors.length)];
}
// Loop through initials and create avatars
userInitialsArray.forEach((initials, index) => {
    const avatar = document.createElement("div");
    avatar.classList.add("card-avatar");
    avatar.style.backgroundColor = getRandomColor();
    avatar.textContent = initials;
    avatar.style.display = "flex";
    avatar.style.flexDirection = "row";
    avatar.style.position = "relative";
    avatar.style.left = `${-index * 10}px`; // Overlapping effect
    avatarContainer.appendChild(avatar);
});
const commentIcon = document.createElement("span");
commentIcon.classList.add( "flex" ,"flex-row");
commentIcon.innerHTML = `<svg width="17" height="17" role="presentation" focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.6426 17.9647C10.1123 19.46 7.62736 19.4606 6.10092 17.9691C4.57505 16.478 4.57769 14.0467 6.10253 12.5566L13.2505 5.57184C14.1476 4.6952 15.5861 4.69251 16.4832 5.56921C17.3763 6.44184 17.3778 7.85135 16.4869 8.72199L9.78361 15.2722C9.53288 15.5172 9.12807 15.5163 8.86954 15.2636C8.61073 15.0107 8.60963 14.6158 8.86954 14.3618L15.0989 8.27463C15.4812 7.90109 15.4812 7.29546 15.0989 6.92192C14.7167 6.54838 14.0969 6.54838 13.7146 6.92192L7.48523 13.0091C6.45911 14.0118 6.46356 15.618 7.48523 16.6163C8.50674 17.6145 10.1511 17.6186 11.1679 16.6249L17.8712 10.0747C19.5274 8.45632 19.5244 5.83555 17.8676 4.2165C16.2047 2.59156 13.5266 2.59657 11.8662 4.21913L4.71822 11.2039C2.42951 13.4404 2.42555 17.083 4.71661 19.3218C7.00774 21.5606 10.7323 21.5597 13.0269 19.3174L19.7133 12.7837C20.0956 12.4101 20.0956 11.8045 19.7133 11.431C19.331 11.0574 18.7113 11.0574 18.329 11.431L11.6426 17.9647Z" fill="currentColor"></path></svg> ${cardData.files}`;
const taskIcon = document.createElement("span");
taskIcon.innerHTML = `<i class="fas fa-check-square"></i> 0/1`;
const notify = document.createElement("div");
notify.classList.add("notification-badge");
// Check if cardData.notif exists and is greater than 0
const notificationCount = cardData.notif ? cardData.notif : 0;
if (notificationCount > 0) {
    notify.innerHTML = `
        <svg width="17" height="17" role="presentation" focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor" d="M12 24c1.33 0 2.41-1.08 2.41-2.41h-4.82C9.59 22.92 10.67 24 12 24zm6.36-7V11c0-3.07-1.63-5.64-4.72-6.32V4c0-.79-.64-1.43-1.43-1.43s-1.43.64-1.43 1.43v.68C7.26 5.36 5.64 7.93 5.64 11v6l-1.93 1.93c-.43.43-.07 1.07.48 1.07h16.62c.55 0 .91-.64.48-1.07L18.36 17z"></path>
        </svg>${notificationCount}`;
        document.body.appendChild(notify); // Append to body or desired container
} else {    notify.style.display = "none"; }
iconContainer.append(notify,attachmentIcon, commentIcon, avatarContainer );
footer.appendChild(iconContainer);
card.ref.appendChild(labelContainer)
card.ref.appendChild(footer);
                    card.mountTo(this);
                }             
            }
                mountTo(board) {
                this.board = board;
                board.ref.append(this.ref);
            }
            setId(id) {
                this.ref.dataset.id = id;
            }
        }


        function loadNewCards() {

    
    console.log("Emitting 'board-action'...");
    socket.emit("board-action");

  }
    </Script>
@endPushOnce
