import { useEffect, useRef } from "react";
import { useQueryClient } from "@tanstack/react-query";
import { useWebSocket } from "@/contexts/WebSocketContext";

interface UseQueueWebSocketOptions {
  stationId?: number;
  enabled?: boolean;
  onUpdate?: (event: string, data: any) => void;
}

export function useQueueWebSocket({
  stationId,
  enabled = true,
  onUpdate,
}: UseQueueWebSocketOptions = {}) {
  const { connected, subscribe, unsubscribe, on } = useWebSocket();
  const queryClient = useQueryClient();
  const hasSubscribedRef = useRef(false);

  useEffect(() => {
    if (!enabled || !connected) return;

    const rooms: string[] = ["global"];
    if (stationId) {
      rooms.push(`queue-${stationId}`);
    }

    rooms.forEach((room) => subscribe(room));
    hasSubscribedRef.current = true;

    return () => {
      if (hasSubscribedRef.current) {
        rooms.forEach((room) => unsubscribe(room));
        hasSubscribedRef.current = false;
      }
    };
  }, [connected, stationId, enabled, subscribe, unsubscribe]);

  useEffect(() => {
    if (!enabled) return;

    const unsubscribeFromEvent = on("queue_update", (message: any) => {
      const { event, data } = message;

      if (onUpdate) {
        onUpdate(event, data);
      }

      if (stationId) {
        queryClient.invalidateQueries({
          queryKey: ["queue-display", stationId],
        });
      }

      queryClient.invalidateQueries({ queryKey: ["queue-display-all"] });
    });

    return unsubscribeFromEvent;
  }, [enabled, on, queryClient, stationId, onUpdate]);

  return { connected };
}

export function useCorrectionWebSocket(
  stationId: number,
  onCorrection?: (data: any) => void,
) {
  const { connected, subscribe, unsubscribe, on } = useWebSocket();
  const queryClient = useQueryClient();

  useEffect(() => {
    if (!connected || !stationId) return;

    const room = `queue-${stationId}`;
    subscribe(room);

    return () => {
      unsubscribe(room);
    };
  }, [connected, stationId, subscribe, unsubscribe]);

  useEffect(() => {
    const unsubscribeFromEvent = on("correction_alert", (message: any) => {
      if (onCorrection) {
        onCorrection(message.data);
      }

      queryClient.invalidateQueries({
        queryKey: ["pending-corrections", stationId],
      });
    });

    return unsubscribeFromEvent;
  }, [on, queryClient, stationId, onCorrection]);

  return { connected };
}

export function useChatWebSocket(
  departmentId?: number,
  onMessage?: (message: any) => void,
) {
  const { connected, subscribe, unsubscribe, on } = useWebSocket();
  const queryClient = useQueryClient();

  useEffect(() => {
    if (!connected) return;

    const rooms = ["chat-global"];
    if (departmentId) {
      rooms.push(`chat-${departmentId}`);
    }

    rooms.forEach((room) => subscribe(room));

    return () => {
      rooms.forEach((room) => unsubscribe(room));
    };
  }, [connected, departmentId, subscribe, unsubscribe]);

  useEffect(() => {
    const unsubscribeFromEvent = on("chat_message", (message: any) => {
      if (onMessage) {
        onMessage(message.data);
      }

      if (departmentId) {
        queryClient.invalidateQueries({
          queryKey: ["chat-messages", departmentId],
        });
      }
    });

    return unsubscribeFromEvent;
  }, [on, queryClient, departmentId, onMessage]);

  return { connected };
}
