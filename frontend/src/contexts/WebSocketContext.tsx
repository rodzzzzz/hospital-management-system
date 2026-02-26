import {
  createContext,
  useContext,
  useEffect,
  useRef,
  useState,
  useCallback,
  type ReactNode,
} from "react";

interface WebSocketContextValue {
  connected: boolean;
  subscribe: (room: string) => void;
  unsubscribe: (room: string) => void;
  on: (eventType: string, callback: (data: any) => void) => () => void;
  send: (data: any) => void;
}

const WebSocketContext = createContext<WebSocketContextValue | null>(null);

interface WebSocketProviderProps {
  children: ReactNode;
  url?: string;
}

export function WebSocketProvider({
  children,
  url = import.meta.env.VITE_WS_URL || "ws://localhost:8080",
}: WebSocketProviderProps) {
  const [connected, setConnected] = useState(false);
  const wsRef = useRef<WebSocket | null>(null);
  const reconnectTimeoutRef = useRef<number | undefined>(undefined);
  const reconnectAttemptsRef = useRef(0);
  const eventListenersRef = useRef<Map<string, Set<(data: any) => void>>>(
    new Map(),
  );
  const subscribedRoomsRef = useRef<Set<string>>(new Set());

  const connect = useCallback(() => {
    if (wsRef.current?.readyState === WebSocket.OPEN) return;

    try {
      const ws = new WebSocket(url);

      ws.onopen = () => {
        console.log("✅ WebSocket connected");
        setConnected(true);
        reconnectAttemptsRef.current = 0;

        subscribedRoomsRef.current.forEach((room) => {
          ws.send(JSON.stringify({ type: "subscribe", room }));
        });
      };

      ws.onmessage = (event) => {
        try {
          const message = JSON.parse(event.data);
          const listeners = eventListenersRef.current.get(message.type);
          if (listeners) {
            listeners.forEach((callback) => callback(message));
          }
        } catch (error) {
          console.error("Failed to parse WebSocket message:", error);
        }
      };

      ws.onerror = (error) => {
        console.error("WebSocket error:", error);
      };

      ws.onclose = () => {
        console.log("❌ WebSocket disconnected");
        setConnected(false);
        wsRef.current = null;

        const backoffDelay = Math.min(
          1000 * Math.pow(2, reconnectAttemptsRef.current),
          30000,
        );
        reconnectAttemptsRef.current++;

        console.log(
          `Reconnecting in ${backoffDelay / 1000}s (attempt ${reconnectAttemptsRef.current})...`,
        );
        reconnectTimeoutRef.current = window.setTimeout(() => {
          connect();
        }, backoffDelay);
      };

      wsRef.current = ws;
    } catch (error) {
      console.error("Failed to create WebSocket:", error);
    }
  }, [url]);

  useEffect(() => {
    connect();

    return () => {
      if (reconnectTimeoutRef.current) {
        clearTimeout(reconnectTimeoutRef.current);
      }
      if (wsRef.current) {
        wsRef.current.close();
      }
    };
  }, [connect]);

  const subscribe = useCallback((room: string) => {
    subscribedRoomsRef.current.add(room);
    if (wsRef.current?.readyState === WebSocket.OPEN) {
      wsRef.current.send(JSON.stringify({ type: "subscribe", room }));
    }
  }, []);

  const unsubscribe = useCallback((room: string) => {
    subscribedRoomsRef.current.delete(room);
    if (wsRef.current?.readyState === WebSocket.OPEN) {
      wsRef.current.send(JSON.stringify({ type: "unsubscribe", room }));
    }
  }, []);

  const on = useCallback((eventType: string, callback: (data: any) => void) => {
    if (!eventListenersRef.current.has(eventType)) {
      eventListenersRef.current.set(eventType, new Set());
    }
    eventListenersRef.current.get(eventType)!.add(callback);

    return () => {
      const listeners = eventListenersRef.current.get(eventType);
      if (listeners) {
        listeners.delete(callback);
        if (listeners.size === 0) {
          eventListenersRef.current.delete(eventType);
        }
      }
    };
  }, []);

  const send = useCallback((data: any) => {
    if (wsRef.current?.readyState === WebSocket.OPEN) {
      wsRef.current.send(JSON.stringify(data));
    }
  }, []);

  return (
    <WebSocketContext.Provider
      value={{ connected, subscribe, unsubscribe, on, send }}
    >
      {children}
    </WebSocketContext.Provider>
  );
}

export function useWebSocket() {
  const context = useContext(WebSocketContext);
  if (!context) {
    throw new Error("useWebSocket must be used within WebSocketProvider");
  }
  return context;
}
