import { Navigate } from 'react-router-dom';
import { useRBAC } from './useRBAC';
import type { ReactNode } from 'react';

interface Props {
  module: string | string[];
  children: ReactNode;
  fallback?: string;
}

export function RequireModule({ module, children, fallback = '/dashboard' }: Props) {
  const { hasModule, hasAnyModule } = useRBAC();

  const allowed = Array.isArray(module)
    ? hasAnyModule(module)
    : hasModule(module);

  if (!allowed) {
    return <Navigate to={fallback} replace />;
  }

  return <>{children}</>;
}
