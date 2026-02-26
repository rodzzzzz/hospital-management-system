import client from './client';
import type { LoginResponse, User } from '@/types/user';

export async function login(username: string, password: string): Promise<LoginResponse> {
  const { data } = await client.post<LoginResponse>('/auth/login.php', {
    username,
    password,
  });
  return data;
}

export async function logout(): Promise<void> {
  try {
    await client.post('/auth/logout.php');
  } catch {
    // ignore errors on logout
  }
}

export async function getMe(): Promise<{ ok: boolean; user?: User }> {
  const { data } = await client.get('/auth/me.php');
  return data;
}

export async function changePassword(
  currentPassword: string,
  newPassword: string
): Promise<{ ok: boolean; error?: string }> {
  const { data } = await client.post('/auth/change_password.php', {
    current_password: currentPassword,
    new_password: newPassword,
  });
  return data;
}

export async function updateProfile(
  fullName: string
): Promise<{ ok: boolean; error?: string }> {
  const { data } = await client.post('/auth/update_profile.php', {
    full_name: fullName,
  });
  return data;
}
