import client from './client';

export async function listDoctors() {
  const { data } = await client.get('/doctor/list.php');
  return data;
}

export async function getDoctorStatus(userId: number) {
  const { data } = await client.get('/doctor/get_status.php', { params: { user_id: userId } });
  return data;
}

export async function setDoctorStatus(payload: { user_id: number; status: string }) {
  const { data } = await client.post('/doctor/set_status.php', payload);
  return data;
}
