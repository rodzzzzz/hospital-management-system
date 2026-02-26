import client from './client';

export async function listChatThreads() {
  const { data } = await client.get('/chat/threads/get.php');
  return data;
}

export async function listChatMessages(threadId: number) {
  const { data } = await client.get('/chat/messages/list.php', { params: { thread_id: threadId } });
  return data;
}

export async function sendChatMessage(payload: {
  thread_id: number;
  content: string;
}) {
  const { data } = await client.post('/chat/messages/send.php', payload);
  return data;
}

export async function markThreadRead(threadId: number) {
  const { data } = await client.post('/chat/threads/mark_read.php', { thread_id: threadId });
  return data;
}

export async function listChatModules() {
  const { data } = await client.get('/chat/modules/list.php');
  return data;
}
