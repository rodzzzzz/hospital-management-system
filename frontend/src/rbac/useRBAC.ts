import { useAuth } from '@/auth/useAuth';
import type { UserRole } from '@/types/user';

export function useRBAC() {
  const { user } = useAuth();
  const roles: UserRole[] = user?.roles ?? [];

  const isAdmin = roles.some(
    (r) => r.module.toUpperCase() === 'ADMIN'
  );

  function hasModule(module: string): boolean {
    if (isAdmin) return true;
    const m = module.toUpperCase();
    return roles.some((r) => r.module.toUpperCase() === m);
  }

  function hasRole(module: string, role: string): boolean {
    if (isAdmin) return true;
    const m = module.toUpperCase();
    return roles.some(
      (r) => r.module.toUpperCase() === m && r.role === role
    );
  }

  function hasAnyRole(module: string, roleList: string[]): boolean {
    if (isAdmin) return true;
    const m = module.toUpperCase();
    const set = new Set(roleList);
    return roles.some(
      (r) => r.module.toUpperCase() === m && set.has(r.role)
    );
  }

  function hasAnyModule(modules: string[]): boolean {
    if (isAdmin) return true;
    return modules.some((m) => hasModule(m));
  }

  return { isAdmin, hasModule, hasRole, hasAnyRole, hasAnyModule, roles };
}
