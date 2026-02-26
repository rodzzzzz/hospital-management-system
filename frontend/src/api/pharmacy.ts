import client from './client';

export async function listMedicines(params?: { search?: string }) {
  const { data } = await client.get('/pharmacy/list_medicines.php', { params });
  return data;
}

export async function getMedicine(id: number) {
  const { data } = await client.get('/pharmacy/get_medicine.php', { params: { id } });
  return data;
}

export async function createMedicine(payload: {
  name: string;
  generic_name?: string;
  category?: string;
  unit_price?: number;
  stock?: number;
}) {
  const { data } = await client.post('/pharmacy/create_medicine.php', payload);
  return data;
}

export async function updateMedicine(id: number, payload: Record<string, unknown>) {
  const { data } = await client.post('/pharmacy/update_medicine.php', { id, ...payload });
  return data;
}

export async function listResits(params?: { patient_id?: number; status?: string }) {
  const { data } = await client.get('/pharmacy/list_resits.php', { params });
  return data;
}

export async function createResit(payload: {
  patient_id: number;
  encounter_id?: number;
  items: { medicine_id: number; qty: number; instructions?: string }[];
}) {
  const { data } = await client.post('/pharmacy/create_resit.php', payload);
  return data;
}
