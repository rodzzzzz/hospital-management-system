interface StatusBadgeProps {
  status: string;
  className?: string;
}

const statusMap: Record<string, { label: string; cls: string }> = {
  completed: { label: 'Completed', cls: 'bg-green-100 text-green-800' },
  confirmed: { label: 'Confirmed', cls: 'bg-green-100 text-green-800' },
  active: { label: 'Active', cls: 'bg-blue-100 text-blue-800' },
  pending: { label: 'Pending', cls: 'bg-yellow-100 text-yellow-800' },
  waiting: { label: 'Waiting', cls: 'bg-amber-100 text-amber-800' },
  registered: { label: 'Registered', cls: 'bg-gray-100 text-gray-800' },
  cancelled: { label: 'Cancelled', cls: 'bg-red-100 text-red-800' },
  rejected: { label: 'Rejected', cls: 'bg-red-100 text-red-800' },
  discharged: { label: 'Discharged', cls: 'bg-teal-100 text-teal-800' },
  in_progress: { label: 'In Progress', cls: 'bg-blue-100 text-blue-800' },
  paid: { label: 'Paid', cls: 'bg-green-100 text-green-800' },
  unpaid: { label: 'Unpaid', cls: 'bg-red-100 text-red-800' },
  stat: { label: 'STAT', cls: 'bg-red-100 text-red-800' },
  urgent: { label: 'Urgent', cls: 'bg-orange-100 text-orange-800' },
  routine: { label: 'Routine', cls: 'bg-gray-100 text-gray-800' },
};

export function progressChip(status: string) {
  const s = (status ?? '').toString().trim();
  const sl = s.toLowerCase();
  if (sl === 'completed') return { label: 'Completed', cls: 'bg-green-100 text-green-800' };
  if (sl.includes('billing')) return { label: s, cls: 'bg-amber-100 text-amber-800' };
  if (sl.includes('awaiting')) return { label: s, cls: 'bg-amber-100 text-amber-800' };
  if (sl.includes('lab: in progress')) return { label: s, cls: 'bg-blue-100 text-blue-800' };
  if (sl.includes('lab: collected')) return { label: s, cls: 'bg-blue-100 text-blue-800' };
  if (sl.includes('lab: approved')) return { label: s, cls: 'bg-blue-100 text-blue-800' };
  if (sl.includes('lab: pending')) return { label: s, cls: 'bg-gray-100 text-gray-800' };
  if (sl.includes('rejected') || sl.includes('cancelled')) return { label: s, cls: 'bg-red-100 text-red-800' };
  if (sl.includes('lab completed')) return { label: s, cls: 'bg-green-100 text-green-800' };
  if (sl === '' || sl === 'registered') return { label: 'Registered', cls: 'bg-gray-100 text-gray-800' };
  return { label: s, cls: 'bg-gray-100 text-gray-800' };
}

export function StatusBadge({ status, className = '' }: StatusBadgeProps) {
  const key = status.toLowerCase().replace(/\s+/g, '_');
  const mapped = statusMap[key] ?? progressChip(status);
  return (
    <span
      className={`px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${mapped.cls} ${className}`}
    >
      {mapped.label}
    </span>
  );
}
