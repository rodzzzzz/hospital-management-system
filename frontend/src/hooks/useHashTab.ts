import { useState, useEffect, useCallback } from 'react';

export function useHashTab<T extends string>(defaultTab: T): [T, (tab: T) => void] {
  const read = (): T => {
    const raw = window.location.hash.replace(/^#/, '').toLowerCase();
    return (raw || defaultTab) as T;
  };

  const [tab, setTabState] = useState<T>(read);

  const setTab = useCallback((t: T) => {
    window.location.hash = t;
  }, []);

  useEffect(() => {
    const handler = () => setTabState(read());
    window.addEventListener('hashchange', handler);
    return () => window.removeEventListener('hashchange', handler);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  return [tab, setTab];
}
