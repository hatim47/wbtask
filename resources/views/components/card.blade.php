@props(['teamid'])

<template id="card" class="!bg-gray-500">
    <div data-role="card" draggable="true"
        class="w-full px-4 py-2 overflow-hidden text-sm bg-white border-2 border-white shadow-[0px_1px_1px_#091e4240,0px_0px_1px_#091e424f] line-clamp-3 rounded-lg hover:border-sky-600 "> 
    
        
           </div> 
  
  
  </template>

 


@pushOnce('head')
<style>

</style>
@endPushOnce
@pushOnce('component')


<script >
  // ✅ Vue component definition


  // ✅ Your click event logic



        const cardTemplate = document.querySelector("template#card");
        class Card {
            constructor(id, name, board) {
                this.board = board;
                const content = cardTemplate.content.cloneNode(true);
                const node = document.createElement("div");
                node.append(content);
                console.log(content);
                this.ref = node.children[0];
                this.ref.textContent = name;
                this.ref.dataset.id = id;
                this.ref.setAttribute('draggable', (id != null));
                this.ref.addEventListener("dragstart", () => {
                    this.board.IS_EDITING = true;
                    this.ref.classList.add("is-dragging");
                    this.ref.classList.toggle("!bg-gray-500");
                });
                  // });

                this.ref.addEventListener("dragend", () => {
                    this.ref.classList.remove("is-dragging");
                    this.ref.setAttribute('draggable', false);
                    this.ref.classList.toggle("!bg-gray-500");

                    const board_id = this.board.ref.dataset.id;
                    const column_id = this.ref.closest("div[data-role='column']").dataset.id;
                    const  middle_id = this.ref.dataset.id;
                    const bottom_id = this.ref.nextElementSibling?.dataset?.id || null;
                    const top_id = this.ref.previousElementSibling?.dataset?.id || null;
                    console.log("🔽 board_id:", board_id);
                    console.log("🔼 column_id:", column_id);
                    console.log("🔽 bottom_id:", bottom_id);
console.log("🔼 top_id:", top_id);
                    ServerRequest.post(`{{ url('team/'.$teamid.'/board/${board_id}/card/reorder') }}`, {
                            column_id: this.ref.closest("div[data-role='column']").dataset.id,
                            middle_id: this.ref.dataset.id,
                            bottom_id: this.ref.nextElementSibling?.dataset?.id || null,
                            top_id: this.ref.previousElementSibling?.dataset?.id || null,
                            
                        
                        })
                        .then((response) => {
                            this.board.IS_EDITING = false;
                            this.ref.setAttribute('draggable', true);
                            loadNewCards();
                            console.log(response.data);
                        });
                })

this.ref.addEventListener("click", async () => {
  const board_id = this.board.ref.dataset.id;
  const card_id = this.ref.dataset.id;
  
  try {
    const response = await ServerRequest.get(
      `{{ url('team/'.$teamid.'/board/${board_id}/card/${card_id}/view') }}`
    );
    
    window.showPopup({
      data: response.data
    });

    console.log("Card loaded successfully:", response.data);
  } catch (error) {
    console.error("Failed to load card:", error);
  }
});
   }
            setId(id) {
                this.ref.dataset.id = id;
                this.ref.setAttribute('draggable', true);
            }

            mountTo(column) {
                column.ref.querySelector("section > div#card-container").append(this.ref);
                this.board = column.board;
            }
     }

    </Script>
@endPushOnce
