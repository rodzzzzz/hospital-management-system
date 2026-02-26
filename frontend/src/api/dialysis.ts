import client from './client';

export async function listDialysisPatients() {
  const { data } = await client.get('/dialysis/patients.php');
  return data;
}

export async function listDialysisSessions(params?: { patient_id?: number; date?: string }) {
  const { data } = await client.get('/dialysis/sessions.php', { params });
  return data;
}

export async function getDialysisSession(id: number) {
  const { data } = await client.get('/dialysis/session.php', { params: { id } });
  return data;
}

export async function listDialysisMachines() {
  const { data } = await client.get('/dialysis/machines.php');
  return data;
}

export async function getDialysisSchedule(params?: { date?: string }) {
  const { data } = await client.get('/dialysis/schedule.php', { params });
  return data;
}

export async function getDialysisStats() {
  const { data } = await client.get('/dialysis/stats.php');
  return data;
}

export async function getDialysisAnalytics(params?: { period?: string }) {
  const { data } = await client.get('/dialysis/analytics.php', { params });
  return data;
}
