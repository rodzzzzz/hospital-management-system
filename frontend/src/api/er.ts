import client from './client';

export async function listErAssessments(params?: { patient_id?: number; status?: string }) {
  const { data } = await client.get('/er_assessment/list.php', { params });
  return data;
}

export async function getErAssessment(id: number) {
  const { data } = await client.get('/er_assessment/get.php', { params: { id } });
  return data;
}

export async function createErAssessment(payload: Record<string, unknown>) {
  const { data } = await client.post('/er_assessment/create.php', payload);
  return data;
}

export async function listErNotes(params?: { encounter_id?: number }) {
  const { data } = await client.get('/er_notes/list.php', { params });
  return data;
}

export async function createErNote(payload: {
  encounter_id: number;
  note_type: string;
  content: string;
}) {
  const { data } = await client.post('/er_notes/create.php', payload);
  return data;
}
