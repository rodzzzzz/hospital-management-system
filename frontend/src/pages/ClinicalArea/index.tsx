import { useNavigate } from 'react-router-dom';
import { useRBAC } from '@/rbac/useRBAC';
import {
  Stethoscope,
  Activity,
  MonitorDot,
  Baby,
  BedDouble,
  Radiation,
} from 'lucide-react';

const areas = [
  { href: '/opd', label: 'Out-Patient Department', icon: <Stethoscope size={28} />, modules: ['OPD'], color: 'bg-blue-50 text-blue-600' },
  { href: '/er', label: 'Emergency Room', icon: <Activity size={28} />, modules: ['ER'], color: 'bg-red-50 text-red-600' },
  { href: '/operating-room', label: 'Operating Room', icon: <MonitorDot size={28} />, modules: ['DOCTOR'], color: 'bg-green-50 text-green-600' },
  { href: '/delivery-room', label: 'Delivery Room', icon: <Baby size={28} />, modules: ['DOCTOR'], color: 'bg-pink-50 text-pink-600' },
  { href: '/icu', label: 'ICU', icon: <BedDouble size={28} />, modules: ['ICU'], color: 'bg-amber-50 text-amber-600' },
  { href: '/xray', label: 'X-Ray', icon: <Radiation size={28} />, modules: ['XRAY'], color: 'bg-purple-50 text-purple-600' },
];

export default function ClinicalArea() {
  const navigate = useNavigate();
  const { isAdmin, hasAnyModule } = useRBAC();

  const visibleAreas = areas.filter(
    (a) => isAdmin || hasAnyModule(a.modules)
  );

  return (
    <div>
      <h1 className="text-2xl font-semibold text-gray-800 mb-6">Clinical Area</h1>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {visibleAreas.map((area) => (
          <button
            key={area.href}
            onClick={() => navigate(area.href)}
            className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-left hover:shadow-md transition-shadow"
          >
            <div className={`w-14 h-14 rounded-xl flex items-center justify-center mb-4 ${area.color}`}>
              {area.icon}
            </div>
            <h2 className="text-lg font-semibold text-gray-800">{area.label}</h2>
          </button>
        ))}
      </div>
    </div>
  );
}
