import {
  DollarSign,
  Stethoscope,
  FlaskConical,
  Pill,
  Activity,
  Radiation,
  ExternalLink,
} from "lucide-react";

const SCREENS = [
  {
    key: "opd",
    label: "OPD Display",
    href: "/queue-display/opd",
    icon: Activity,
    color: "bg-blue-50 text-blue-600",
  },
  {
    key: "doctor",
    label: "Doctor Display",
    href: "/queue-display/doctor",
    icon: Stethoscope,
    color: "bg-green-50 text-green-600",
  },
  {
    key: "pharmacy",
    label: "Pharmacy Display",
    href: "/queue-display/pharmacy",
    icon: Pill,
    color: "bg-yellow-50 text-yellow-600",
  },
  {
    key: "cashier",
    label: "Cashier Display",
    href: "/queue-display/cashier",
    icon: DollarSign,
    color: "bg-red-50 text-red-600",
  },
  {
    key: "xray",
    label: "X-Ray Display",
    href: "/queue-display/xray",
    icon: Radiation,
    color: "bg-purple-50 text-purple-600",
  },
  {
    key: "lab",
    label: "Laboratory Display",
    href: "/queue-display/lab",
    icon: FlaskConical,
    color: "bg-teal-50 text-teal-600",
  },
];

export default function Displays() {
  return (
    <div>
      <div className="px-6 pt-6 mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Display Screens</h1>
        <p className="text-sm text-gray-600 mt-1">
          Open department queue display screens for public-facing monitors.
        </p>
      </div>

      <div className="px-6">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {SCREENS.map((screen) => (
            <a
              key={screen.key}
              href={screen.href}
              target="_blank"
              rel="noopener noreferrer"
              className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow group"
            >
              <div className="flex items-center justify-between mb-4">
                <div
                  className={`w-14 h-14 rounded-xl flex items-center justify-center ${screen.color}`}
                >
                  <screen.icon className="w-7 h-7" />
                </div>
                <ExternalLink className="w-5 h-5 text-gray-300 group-hover:text-gray-500 transition-colors" />
              </div>
              <h2 className="text-lg font-semibold text-gray-800">
                {screen.label}
              </h2>
              <p className="text-sm text-gray-500 mt-1">
                Opens in a new window
              </p>
            </a>
          ))}
        </div>
      </div>
    </div>
  );
}
