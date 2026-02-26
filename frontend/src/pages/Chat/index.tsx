import { useState, useRef, useEffect } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { Send, Paperclip } from "lucide-react";
import { toast } from "sonner";
import { useChatWebSocket } from "@/hooks/useQueueWebSocket";
import client from "@/api/client";

const DEPARTMENTS = [
  { key: "announcements", label: "Announcements" },
  { key: "ER", label: "ER" },
  { key: "OPD", label: "OPD" },
  { key: "LAB", label: "LAB" },
  { key: "PHARMACY", label: "PHARMACY" },
  { key: "CASHIER", label: "CASHIER" },
  { key: "HR", label: "HR" },
];

function initials(label: string) {
  const parts = label.trim().split(/\s+/).filter(Boolean);
  if (parts.length === 0) return "--";
  if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
  return (parts[0][0] + parts[1][0]).toUpperCase();
}

export default function Chat() {
  const qc = useQueryClient();
  const [activeDept, setActiveDept] = useState<string | null>(null);
  const [message, setMessage] = useState("");
  const messagesEndRef = useRef<HTMLDivElement>(null);

  const { data: messagesData } = useQuery({
    queryKey: ["chat-messages", activeDept],
    queryFn: async () => {
      const { data } = await client.get(
        `/chat/messages.php?channel=${encodeURIComponent(activeDept ?? "")}`,
      );
      return data;
    },
    enabled: !!activeDept,
  });

  useChatWebSocket(activeDept ? parseInt(activeDept) : undefined);

  const messages: any[] = messagesData?.ok ? (messagesData.messages ?? []) : [];

  const sendMut = useMutation({
    mutationFn: async () => {
      const { data } = await client.post("/chat/send.php", {
        channel: activeDept,
        body: message,
      });
      if (!data.ok) throw new Error(data.error ?? "Send failed");
      return data;
    },
    onSuccess: () => {
      setMessage("");
      qc.invalidateQueries({ queryKey: ["chat-messages", activeDept] });
    },
    onError: (e: Error) => toast.error(e.message),
  });

  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [messages.length]);

  return (
    <div className="flex h-[calc(100vh-4rem)]">
      {/* Department List */}
      <div className="w-64 border-r border-gray-200 bg-white overflow-y-auto">
        <div className="p-4 border-b border-gray-100">
          <h2 className="text-sm font-semibold text-gray-900">Channels</h2>
        </div>
        <div className="px-2 py-2 space-y-1">
          {DEPARTMENTS.map((dept) => (
            <button
              key={dept.key}
              onClick={() => setActiveDept(dept.key)}
              className={`w-full flex items-center gap-3 px-3 py-3 rounded-lg text-left transition-colors ${
                activeDept === dept.key
                  ? "bg-blue-50 border border-blue-200"
                  : "hover:bg-gray-50"
              }`}
            >
              <div className="relative">
                <div className="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-700">
                  {initials(dept.label)}
                </div>
                <div className="absolute bottom-0 right-0 w-2 h-2 bg-green-500 border-2 border-white rounded-full" />
              </div>
              <div className="flex-1 min-w-0">
                <div className="text-sm font-medium text-gray-900">
                  {dept.label}
                </div>
                <div className="text-xs text-gray-500 truncate">
                  Start conversation
                </div>
              </div>
            </button>
          ))}
        </div>
      </div>

      {/* Chat Area */}
      <div className="flex-1 flex flex-col bg-white">
        {/* Header */}
        <div className="h-16 px-6 border-b flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-700">
              {activeDept ? initials(activeDept) : "--"}
            </div>
            <div className="text-sm font-semibold text-gray-900">
              {activeDept ?? "Select a department"}
            </div>
          </div>
        </div>

        {/* Messages */}
        <div className="flex-1 bg-gray-50 overflow-y-auto p-6 space-y-4">
          {!activeDept ? (
            <div className="h-full flex items-center justify-center">
              <div className="text-center text-sm text-gray-500">
                Select a department to start chatting
              </div>
            </div>
          ) : messages.length === 0 ? (
            <div className="h-full flex items-center justify-center">
              <div className="text-center">
                <div className="text-sm font-semibold text-gray-700">
                  {activeDept}
                </div>
                <div className="text-xs text-gray-500">
                  Send the first message.
                </div>
              </div>
            </div>
          ) : (
            <>
              {messages.map((msg: any, i: number) => (
                <div
                  key={i}
                  className={`flex ${msg.is_me ? "justify-end" : "justify-start"}`}
                >
                  <div
                    className={`max-w-[70%] ${msg.is_me ? "text-right" : "text-left"}`}
                  >
                    <div className="text-xs text-gray-500 mb-1">
                      {msg.is_me ? "You" : msg.sender_name}
                      {msg.sender_role ? ` (${msg.sender_role})` : ""}
                    </div>
                    <div
                      className={`inline-block px-4 py-2 rounded-2xl ${
                        msg.is_me
                          ? "bg-blue-600 text-white rounded-tr-md"
                          : "bg-white text-gray-800 border border-gray-200 rounded-tl-md"
                      }`}
                    >
                      {msg.body}
                    </div>
                  </div>
                </div>
              ))}
              <div ref={messagesEndRef} />
            </>
          )}
        </div>

        {/* Composer */}
        <div className="bg-white border-t p-4">
          <div className="flex items-center gap-3">
            <button className="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
              <Paperclip className="w-5 h-5" />
            </button>
            <input
              value={message}
              onChange={(e) => setMessage(e.target.value)}
              onKeyDown={(e) => {
                if (e.key === "Enter" && message.trim() && activeDept)
                  sendMut.mutate();
              }}
              type="text"
              placeholder="Type a message"
              disabled={!activeDept}
              className="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
            />
            <button
              onClick={() => {
                if (message.trim() && activeDept) sendMut.mutate();
              }}
              disabled={!activeDept || !message.trim() || sendMut.isPending}
              className="w-10 h-10 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Send className="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
