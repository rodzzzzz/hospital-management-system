import client from './client';

export async function listXrayOrders(params?: {
  patient_id?: number;
  status?: string;
}) {
  const { data } = await client.get('/xray/list_orders.php', { params });
  return data;
}

export async function getXrayOrder(id: number) {
  const { data } = await client.get('/xray/get_order.php', { params: { id } });
  return data;
}

export async function createXrayOrder(payload: {
  patient_id: number;
  encounter_id?: number;
  exam_type: string;
}) {
  const { data } = await client.post('/xray/create_order.php', payload);
  return data;
}

export async function saveXrayResult(payload: {
  order_id: number;
  findings: string;
  impression?: string;
}) {
  const { data } = await client.post('/xray/save_result.php', payload);
  return data;
}
