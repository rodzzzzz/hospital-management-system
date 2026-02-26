import client from './client';

export async function listPhilhealthClaims(params?: { status?: string; search?: string }) {
  const { data } = await client.get('/philhealth/list_claims.php', { params });
  return data;
}

export async function getPhilhealthClaim(id: number) {
  const { data } = await client.get('/philhealth/get_claim.php', { params: { id } });
  return data;
}

export async function createPhilhealthClaim(payload: Record<string, unknown>) {
  const { data } = await client.post('/philhealth/create_claim.php', payload);
  return data;
}

export async function listPhilhealthMembers(params?: { search?: string }) {
  const { data } = await client.get('/philhealth/list_members.php', { params });
  return data;
}

export async function getPhilhealthForm(payload: { claim_id: number; form_type: string }) {
  const { data } = await client.get('/philhealth/get_form.php', { params: payload });
  return data;
}

export async function savePhilhealthForm(payload: Record<string, unknown>) {
  const { data } = await client.post('/philhealth/save_form.php', payload);
  return data;
}
