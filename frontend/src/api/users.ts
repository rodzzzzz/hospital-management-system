import client from './client';

export async function listUsers(params?: { search?: string; status?: string }) {
  const { data } = await client.get('/users/list.php', { params });
  return data;
}

export async function createUser(payload: {
  username: string;
  full_name: string;
  password: string;
  roles?: { module: string; role: string }[];
}) {
  const { data } = await client.post('/users/create.php', payload);
  return data;
}

export async function updateUser(id: number, payload: Record<string, unknown>) {
  const { data } = await client.post('/users/update.php', { id, ...payload });
  return data;
}

export async function deleteUser(id: number) {
  const { data } = await client.post('/users/delete.php', { id });
  return data;
}
