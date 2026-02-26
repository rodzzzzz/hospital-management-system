import client from './client';
import type { Encounter } from '@/types/encounter';

export async function listEncounters(params?: {
  patient_id?: number;
  status?: string;
}): Promise<{ ok: boolean; encounters: Encounter[] }> {
  const { data } = await client.get('/encounters/list.php', { params });
  return data;
}

export async function createEncounter(encounter: {
  patient_id: number;
  encounter_type: string;
  chief_complaint?: string;
}): Promise<{ ok: boolean; id?: number; error?: string }> {
  const { data } = await client.post('/encounters/create.php', encounter);
  return data;
}

export async function closeEncounter(id: number): Promise<{ ok: boolean; error?: string }> {
  const { data } = await client.post('/encounters/close.php', { id });
  return data;
}
