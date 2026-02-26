import client from './client';

export async function listIcuAdmissions(params?: { status?: string }) {
  const { data } = await client.get('/icu/list_admissions.php', { params });
  return data;
}

export async function getIcuAdmission(id: number) {
  const { data } = await client.get('/icu/get_admission.php', { params: { id } });
  return data;
}

export async function listIcuBeds() {
  const { data } = await client.get('/icu/list_beds.php');
  return data;
}

export async function admitToIcu(payload: {
  patient_id: number;
  encounter_id?: number;
  bed_id: number;
  diagnosis?: string;
}) {
  const { data } = await client.post('/icu/admit.php', payload);
  return data;
}

export async function dischargeFromIcu(admissionId: number) {
  const { data } = await client.post('/icu/discharge.php', { admission_id: admissionId });
  return data;
}
