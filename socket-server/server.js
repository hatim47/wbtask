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

});

server.listen(3000, () => {
    console.log("🚀 Socket.io server running on port 3000");
});