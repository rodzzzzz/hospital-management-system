import client from './client';

export async function listPrices(params?: { category?: string; search?: string }) {
  const { data } = await client.get('/price_master/list.php', { params });
  return data;
}

export async function getPrice(id: number) {
  const { data } = await client.get('/price_master/get.php', { params: { id } });
  return data;
}

export async function createPrice(payload: {
  name: string;
  category: string;
  price: number;
}) {
  const { data } = await client.post('/price_master/create.php', payload);
  return data;
}

export async function updatePrice(id: number, payload: Record<string, unknown>) {
  const { data } = await client.post('/price_master/update.php', { id, ...payload });
  return data;
}
