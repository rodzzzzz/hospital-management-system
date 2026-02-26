import client from './client';

export async function listCharges(params?: { patient_id?: number; status?: string }) {
  const { data } = await client.get('/cashier/list_charges.php', { params });
  return data;
}

export async function getCharge(id: number) {
  const { data } = await client.get('/cashier/get_charge.php', { params: { id } });
  return data;
}

export async function listInvoices(params?: { patient_id?: number; status?: string }) {
  const { data } = await client.get('/cashier/list_invoices.php', { params });
  return data;
}

export async function getInvoice(id: number) {
  const { data } = await client.get('/cashier/get_invoice.php', { params: { id } });
  return data;
}

export async function createInvoice(chargeId: number) {
  const { data } = await client.post('/cashier/create_invoice.php', { charge_id: chargeId });
  return data;
}

export async function addPayment(payload: {
  invoice_id: number;
  amount: number;
  method?: string;
}) {
  const { data } = await client.post('/cashier/add_payment.php', payload);
  return data;
}

export async function payCharge(chargeId: number) {
  const { data } = await client.post('/cashier/pay_charge.php', { charge_id: chargeId });
  return data;
}

export async function getCashierStats() {
  const { data } = await client.get('/cashier/stats.php');
  return data;
}
