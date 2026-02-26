import client from './client';

export async function listOpdAppointments(params?: {
  patient_id?: number;
  status?: string;
  date?: string;
}) {
  const { data } = await client.get('/opd/list_appointments.php', { params });
  return data;
}

export async function createOpdAppointment(payload: {
  patient_id: number;
  doctor_id?: number;
  appointment_date: string;
  chief_complaint?: string;
}) {
  const { data } = await client.post('/opd/create_appointment.php', payload);
  return data;
}

export async function listOpdNotes(params?: { encounter_id?: number }) {
  const { data } = await client.get('/opd_notes/list.php', { params });
  return data;
}

export async function createOpdNote(payload: {
  encounter_id: number;
  note_type: string;
  content: string;
}) {
  const { data } = await client.post('/opd_notes/create.php', payload);
  return data;
}

export async function listOpdAssessments(params?: { encounter_id?: number }) {
  const { data } = await client.get('/opd_assessment/list.php', { params });
  return data;
}

export async function listOpdBillingItems(params?: { encounter_id?: number }) {
  const { data } = await client.get('/opd_billing/list.php', { params });
  return data;
}

export async function listOpdFees() {
  const { data } = await client.get('/opd/list_fees.php');
  return data;
}
