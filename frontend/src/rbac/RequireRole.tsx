import { Navigate } from 'react-router-dom';
import { useRBAC } from './useRBAC';
import type { ReactNode } from 'react';

interface Props {
  module: string;
  role: string | string[];
  children: ReactNode;
  fallback?: string;
}

export function RequireRole({ module, role, children, fallback = '/dashboard' }: Props) {
  const { hasRole, hasAnyRole } = useRBAC();

  const allowed = Array.isArray(role)
    ? hasAnyRole(module, role)
    : hasRole(module, role);

  if (!allowed) {
    return <Navigate to={fallback} replace />;
  }

  return <>{children}</>;
}
