import client from './client';

export async function listEmployees(params?: { search?: string; department_id?: number }) {
  const { data } = await client.get('/hr/list_employees.php', { params });
  return data;
}

export async function getEmployee(id: number) {
  const { data } = await client.get('/hr/get_employee.php', { params: { id } });
  return data;
}

export async function createEmployee(payload: Record<string, unknown>) {
  const { data } = await client.post('/hr/create_employee.php', payload);
  return data;
}

export async function updateEmployee(id: number, payload: Record<string, unknown>) {
  const { data } = await client.post('/hr/update_employee.php', { id, ...payload });
  return data;
}

export async function listDepartments() {
  const { data } = await client.get('/hr/list_departments.php');
  return data;
}

export async function listPositions(params?: { department_id?: number }) {
  const { data } = await client.get('/hr/list_positions.php', { params });
  return data;
}

export async function listSchedules(params?: { employee_id?: number; date?: string }) {
  const { data } = await client.get('/hr/list_schedules.php', { params });
  return data;
}
