export interface UserRole {
  module: string;
  role: string;
}

export interface User {
  id: number;
  username: string;
  full_name: string;
  status: string;
  roles: UserRole[];
}

export interface LoginResponse {
  ok: boolean;
  token?: string;
  user?: User;
  error?: string;
}

export interface AuthState {
  user: User | null;
  token: string | null;
  isAuthenticated: boolean;
  isLoading: boolean;
}
