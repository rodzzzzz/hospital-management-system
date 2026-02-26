import client from './client';

export async function listLabRequests(params?: {
  patient_id?: number;
  encounter_id?: number;
  status?: string;
}) {
  const { data } = await client.get('/lab/list_requests.php', { params });
  return data;
}

export async function getLabRequest(id: number) {
  const { data } = await client.get('/lab/get_request.php', { params: { id } });
  return data;
}

export async function createLabRequest(payload: {
  patient_id: number;
  encounter_id?: number;
  items: { test_name: string; fee?: number }[];
}) {
  const { data } = await client.post('/lab/create_request.php', payload);
  return data;
}

export async function saveLabResult(payload: {
  request_item_id: number;
  result_value: string;
  unit?: string;
  reference_range?: string;
  remarks?: string;
}) {
  const { data } = await client.post('/lab/save_result.php', payload);
  return data;
}

export async function listLabFees() {
  const { data } = await client.get('/lab/list_fees.php');
  return data;
}

export async function getLabStats() {
  const { data } = await client.get('/lab/stats.php');
  return data;
}
