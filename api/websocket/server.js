import { WebSocketServer } from 'ws';
import http from 'http';
import mysql from 'mysql2/promise';

const WS_PORT = process.env.WS_PORT || 8080;
const HTTP_PORT = process.env.HTTP_PORT || 8081;

const dbConfig = {
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'hospital_system',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
};

const pool = mysql.createPool(dbConfig);

const rooms = new Map();

const wss = new WebSocketServer({ port: WS_PORT });

console.log(`🚀 WebSocket Server running on port ${WS_PORT}`);

wss.on('connection', (ws, req) => {
  console.log('New client connected from', req.socket.remoteAddress);
  
  ws.isAlive = true;
  ws.rooms = new Set();

  ws.on('pong', () => {
    ws.isAlive = true;
  });

  ws.on('message', (message) => {
    try {
      const data = JSON.parse(message.toString());
      handleClientMessage(ws, data);
    } catch (error) {
      console.error('Error parsing message:', error);
    }
  });

  ws.on('close', () => {
    console.log('Client disconnected');
    ws.rooms.forEach(room => {
      const roomClients = rooms.get(room);
      if (roomClients) {
        roomClients.delete(ws);
        if (roomClients.size === 0) {
          rooms.delete(room);
        }
      }
    });
  });

  ws.send(JSON.stringify({ type: 'connected', message: 'Connected to hospital queue WebSocket server' }));
});

function handleClientMessage(ws, data) {
  const { type, room } = data;

  switch (type) {
    case 'subscribe':
      if (room) {
        subscribeToRoom(ws, room);
      }
      break;
    case 'unsubscribe':
      if (room) {
        unsubscribeFromRoom(ws, room);
      }
      break;
    case 'ping':
      ws.send(JSON.stringify({ type: 'pong' }));
      break;
    default:
      console.log('Unknown message type:', type);
  }
}

function subscribeToRoom(ws, room) {
  if (!rooms.has(room)) {
    rooms.set(room, new Set());
  }
  rooms.get(room).add(ws);
  ws.rooms.add(room);
  console.log(`Client subscribed to room: ${room}`);
  ws.send(JSON.stringify({ type: 'subscribed', room }));
}

function unsubscribeFromRoom(ws, room) {
  const roomClients = rooms.get(room);
  if (roomClients) {
    roomClients.delete(ws);
    if (roomClients.size === 0) {
      rooms.delete(room);
    }
  }
  ws.rooms.delete(room);
  console.log(`Client unsubscribed from room: ${room}`);
  ws.send(JSON.stringify({ type: 'unsubscribed', room }));
}

function broadcastToRoom(room, message) {
  const roomClients = rooms.get(room);
  if (roomClients) {
    const payload = JSON.stringify(message);
    roomClients.forEach(client => {
      if (client.readyState === 1) {
        client.send(payload);
      }
    });
    console.log(`Broadcasted to room ${room}:`, message.type, message.event);
  }
}

const heartbeatInterval = setInterval(() => {
  wss.clients.forEach((ws) => {
    if (ws.isAlive === false) {
      return ws.terminate();
    }
    ws.isAlive = false;
    ws.ping();
  });
}, 30000);

wss.on('close', () => {
  clearInterval(heartbeatInterval);
});

const httpServer = http.createServer((req, res) => {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (req.method === 'OPTIONS') {
    res.writeHead(200);
    res.end();
    return;
  }

  if (req.method === 'POST' && req.url === '/broadcast') {
    let body = '';
    req.on('data', chunk => {
      body += chunk.toString();
    });
    req.on('end', () => {
      try {
        const data = JSON.parse(body);
        handleBroadcast(data);
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ success: true }));
      } catch (error) {
        console.error('Broadcast error:', error);
        res.writeHead(400, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ success: false, error: error.message }));
      }
    });
  } else if (req.method === 'GET' && req.url === '/health') {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({ 
      status: 'ok', 
      clients: wss.clients.size,
      rooms: rooms.size 
    }));
  } else {
    res.writeHead(404);
    res.end();
  }
});

httpServer.listen(HTTP_PORT, () => {
  console.log(`📡 HTTP Broadcast Server running on port ${HTTP_PORT}`);
});

function handleBroadcast(data) {
  const { type, event, rooms: targetRooms, payload } = data;
  
  const message = {
    type,
    event,
    data: payload,
    timestamp: new Date().toISOString()
  };

  if (targetRooms && Array.isArray(targetRooms)) {
    targetRooms.forEach(room => {
      broadcastToRoom(room, message);
    });
  }

  if (targetRooms && targetRooms.includes('global')) {
    broadcastToRoom('global', message);
  }
}

process.on('SIGINT', () => {
  console.log('\n🛑 Shutting down WebSocket server...');
  clearInterval(heartbeatInterval);
  wss.close(() => {
    console.log('WebSocket server closed');
    pool.end(() => {
      console.log('Database pool closed');
      process.exit(0);
    });
  });
});

console.log('💡 Available rooms: global, queue-{stationId}, chat-{departmentId}');
