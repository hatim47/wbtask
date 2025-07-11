import express from 'express';
import { createServer } from 'http';
import { Server } from 'socket.io';
import cors from 'cors';

const app = express();
app.use(cors());

const server = createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*", // Allow all origins (change for security)
        methods: ["GET", "POST"],
    },
});
app.get('/', (req, res) => {
    res.redirect('/auth/login');
});

io.on("connection", (socket) => {
    console.log("A user connected");

    socket.on("board-action", () => {
        console.log("📢 Board action received, notifying all users...");
        io.emit("board-refresh"); // Broadcast update
    });
  socket.on("card-description-updated", (data) => {
        const { cardId, description } = data;
        console.log(`📝 Card ${cardId} description updated by ${socket.id}`);
        socket.broadcast.emit("card-description-updated", {
            cardId,
            description
        });
    });
    socket.on("card-file-uploaded", (data) => {
    const { cardId, file } = data;
    console.log(`📁 File uploaded to card ${file} by ${socket.id}`);
     io.emit("card-file-uploaded", {
        cardId,
        file
    });
     const label = '';
     io.emit('label-createddd' , label);
});
socket.on("card-file-deleted", ({ cardId, fileId }) => {
  console.log("🗑 File deleted, broadcasting:", fileId);
  io.emit("card-file-deleted", { cardId, fileId });
   io.emit('label-createddd' , cardId);
});
socket.on("cover-updated", ({ cardId, cover }) => {
     console.log("🗑 File deleted, broadcasting:", cover);
io.emit('cover-updated', {
  cardId: cardId,
  cover: cover
});

 });
socket.on("comment-action", () => {
        console.log("📢 Comment action received, notifying all users...");
        io.emit("comment-refresh"); // Broadcast update
    });
socket.on("disconnect", () => {
        console.log("A user disconnected");
    });
socket.on("join-card", (cardId) => {
    socket.join(`card-${cardId}`);
    console.log(`✅ Socket ${socket.id} joined card-${cardId}`);
  });
  socket.on("join-board", (boardId) => {
    socket.join(`board-${boardId}`);
    console.log(`✅ Socket ${socket.id} joined board-${boardId}`);
  });
socket.on("member-added", ({ cardId, user }) => {
     console.log(`🚀 member-added ${cardId}`);
    io.to(`card-${cardId}`).emit("member-add", { cardId, user });
     io.emit('label-createddd' , cardId);
  });
socket.on("member-removed", ({ cardId, userId }) => {
     console.log("🚀 member-removed");
    io.to(`card-${cardId}`).emit("member-remove", { cardId, userId });
     io.emit('label-createddd' , cardId);
  });
socket.on('label-created', (label ,boardId) => {   
  io.to(`card-${label.card_id}`).emit('label-created', label );
  io.emit('label-createddd' , label);
  console.log('📥 Label created:', label.card_id);
  });
socket.on('label-updated', (label) => {
    console.log('✏️ Label updated:', label);
    io.to(`card-${label.card_id}`).emit('label-updated', label);
     io.emit('label-createddd' , label.card_id);

  });
socket.on('label-Checked-updated', ( label, clientId ) => {
    console.log('✏️Checked Label updated:', label ,clientId);
    io.to(`card-${label.card_id}`).emit('label-Checked-updated', { label, clientId });
     io.emit('label-createddd' , label.card_id);
  });
  socket.on('cardupdate',  () => {  
         const cardId = '';
       io.emit('label-createddd' , cardId);     
  });
socket.on('delete-label',  (labelId,cardId ) => {      
        io.to(`card-${cardId}`).emit('label-deleted', labelId);
      console.log(`Label ${labelId} deleted`);   
       io.emit('label-createddd' , cardId);     
  });
socket.on('commentinsert', (comment) => {
    console.log('💬 Comment inserted:', comment);
    io.to(`card-${comment.card_id}`).emit('commentinserted', comment);
     io.emit('label-createddd' , comment.card_id);
  });
socket.on('commentupdate', (comment) => {
  console.log('💬 Comment updated:', comment);
  io.to(`card-${comment.card_id}`).emit('commentupdated', comment);
});
socket.on('commentdelete', (id ,card_id) => {
  console.log('💬 Comment deleted:', id);
  io.to(`card-${card_id}`).emit('commentdeleted', id);
   io.emit('label-createddd' , card_id);
});
});
server.listen(3000, () => {
    console.log("🚀 Socket.io server running on port 3000");
});