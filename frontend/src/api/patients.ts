import client from './client';
import type { Patient } from '@/types/patient';

export async function listPatients(params?: {
  search?: string;
  page?: number;
  per_page?: number;
}): Promise<{ ok: boolean; patients: Patient[]; total?: number }> {
  const { data } = await client.get('/patients/list.php', { params });
  return data;
}

export async function getPatient(id: number): Promise<{ ok: boolean; patient: Patient }> {
  const { data } = await client.get('/patients/get.php', { params: { id } });
  return data;
}

export async function createPatient(patient: Partial<Patient>): Promise<{ ok: boolean; id?: number; error?: string }> {
  const { data } = await client.post('/patients/create.php', patient);
  return data;
}

export async function updatePatient(id: number, patient: Partial<Patient>): Promise<{ ok: boolean; error?: string }> {
  const { data } = await client.post('/patients/update.php', { id, ...patient });
  return data;
}

export async function getPatientStats(): Promise<{ ok: boolean; [key: string]: unknown }> {
  const { data } = await client.get('/patients/stats.php');
  return data;
}
