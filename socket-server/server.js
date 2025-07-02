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

        // Broadcast to all users except sender
        socket.broadcast.emit("card-description-updated", {
            cardId,
            description
        });
    });
    socket.on("card-file-uploaded", (data) => {
    const { cardId, file } = data;
    console.log(`📁 File uploaded to card ${file} by ${socket.id}`);

    // Broadcast to other users (except the sender)
     io.emit("card-file-uploaded", {
        cardId,
        file
    });
});
socket.on("card-file-deleted", ({ cardId, fileId }) => {
  console.log("🗑 File deleted, broadcasting:", fileId);
  io.emit("card-file-deleted", { cardId, fileId });
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

  socket.on("member-added", ({ cardId, user }) => {
     console.log(`🚀 member-added ${cardId}`);
    io.to(`card-${cardId}`).emit("member-add", { cardId, user });
  });

  socket.on("member-removed", ({ cardId, userId }) => {
     console.log("🚀 member-removed");
    io.to(`card-${cardId}`).emit("member-remove", { cardId, userId });
  });


 socket.on('label-created', (label) => {
    console.log('📥 Label created:', label);

    // Broadcast to all other clients
   io.to(`card-${label.card_id}`).emit('label-created', label);
  });

  socket.on('label-updated', (label) => {
    console.log('✏️ Label updated:', label);

    // Broadcast to all other clients
    io.to(`card-${label.card_id}`).emit('label-updated', label);
  });
 socket.on('label-Checked-updated', ( label, clientId ) => {
    console.log('✏️Checked Label updated:', label ,clientId);

    // Broadcast to all other clients
    io.to(`card-${label.card_id}`).emit('label-Checked-updated', { label, clientId });
  });
});


server.listen(3000, () => {
    console.log("🚀 Socket.io server running on port 3000");
});