import type { LucideIcon } from 'lucide-react';

interface StatCardProps {
  icon: LucideIcon;
  label: string;
  value: string | number;
  trend?: string;
  trendUp?: boolean;
  iconBg?: string;
  iconColor?: string;
  accentColor?: string;
}

export function StatCard({
  icon: Icon,
  label,
  value,
  trend,
  trendUp,
  iconBg = 'bg-blue-100',
  iconColor = 'text-blue-600',
  accentColor,
}: StatCardProps) {
  return (
    <div className="group relative bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
      {accentColor && (
        <div className={`absolute inset-0 ${accentColor} opacity-50`} />
      )}
      <div className="relative p-6">
        <div className="flex items-center justify-between">
          <div className={`w-14 h-14 rounded-2xl ${iconBg} flex items-center justify-center transform transition-transform group-hover:scale-110`}>
            <Icon className={`w-6 h-6 ${iconColor}`} />
          </div>
          {trend && (
            <span
              className={`text-xs font-semibold px-2 py-1 rounded-full ${
                trendUp !== false
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700'
              }`}
            >
              {trend}
            </span>
          )}
        </div>
        <div className="mt-4">
          <h2 className="text-3xl font-bold text-gray-800">{value}</h2>
          <p className="text-sm text-gray-600 mt-1">{label}</p>
        </div>
      </div>
    </div>
  );
}
