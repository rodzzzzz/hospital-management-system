import { useQuery } from "@tanstack/react-query";
import {
  LineChart,
  Line,
  BarChart,
  Bar,
  XAxis,
  YAxis,
  Tooltip,
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell,
  Legend,
} from "recharts";
import { Users, CalendarCheck, Activity, DollarSign } from "lucide-react";
import { useAuth } from "@/auth/useAuth";
import { StatCard } from "@/components/ui/StatCard";
import { ChartCard } from "@/components/ui/ChartCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

interface Appointment {
  id: number;
  patient_name: string;
  type: string;
  date: string;
  time: string;
  status: string;
}

const DEPT_COLORS = ["#3B82F6", "#10B981", "#F59E0B", "#6B7280"];

const fallbackPatientData = [
  { month: "Jan", patients: 65 },
  { month: "Feb", patients: 78 },
  { month: "Mar", patients: 90 },
  { month: "Apr", patients: 85 },
  { month: "May", patients: 95 },
  { month: "Jun", patients: 100 },
];

const fallbackRevenueData = [
  { month: "Jan", revenue: 30000 },
  { month: "Feb", revenue: 35000 },
  { month: "Mar", revenue: 32000 },
  { month: "Apr", revenue: 38000 },
  { month: "May", revenue: 40000 },
  { month: "Jun", revenue: 42000 },
];

const fallbackDeptData = [
  { name: "Emergency", value: 40 },
  { name: "Surgery", value: 25 },
  { name: "Pediatrics", value: 20 },
  { name: "Other", value: 15 },
];

const fallbackAppointments: Appointment[] = [
  {
    id: 1,
    patient_name: "Sarah Johnson",
    type: "General Checkup",
    date: "Sep 15, 2023",
    time: "10:00 AM",
    status: "confirmed",
  },
  {
    id: 2,
    patient_name: "Michael Brown",
    type: "Follow-up",
    date: "Sep 15, 2023",
    time: "11:30 AM",
    status: "pending",
  },
];

export default function Dashboard() {
  const { user } = useAuth();

  const { data: statsData } = useQuery({
    queryKey: ["dashboard-stats"],
    queryFn: async () => {
      const { data } = await client.get("/patients/stats.php");
      return data;
    },
    retry: false,
  });

  const { data: apptData } = useQuery({
    queryKey: ["dashboard-appointments"],
    queryFn: async () => {
      const { data } = await client.get("/opd/list_appointments.php?limit=5");
      return data;
    },
    retry: false,
  });

  const cards = statsData?.cards;
  const appointments: Appointment[] = apptData?.ok
    ? apptData.appointments
    : fallbackAppointments;

  return (
    <div className="p-6">
      <div className="mb-6">
        <h1 className="text-2xl font-semibold text-gray-800">Dashboard</h1>
        <p className="text-sm text-gray-500 mt-1">
          Welcome back, {user?.full_name ?? user?.username ?? "User"}
        </p>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <StatCard
          icon={Users}
          label="Total Patients"
          value={cards?.total_patients?.toLocaleString() ?? "1,287"}
          trend="+4.75%"
          trendUp={true}
          iconBg="bg-blue-100"
          iconColor="text-blue-600"
        />
        <StatCard
          icon={CalendarCheck}
          label="Appointments"
          value={cards?.appointments?.toLocaleString() ?? "965"}
          trend="+2.5%"
          trendUp={true}
          iconBg="bg-green-100"
          iconColor="text-green-600"
        />
        <StatCard
          icon={Activity}
          label="Operations"
          value={cards?.operations?.toLocaleString() ?? "128"}
          trend="-1.2%"
          trendUp={false}
          iconBg="bg-red-100"
          iconColor="text-red-600"
        />
        <StatCard
          icon={DollarSign}
          label="Revenue"
          value={
            cards?.revenue ? `₱${(cards.revenue / 1000).toFixed(0)}K` : "₱315K"
          }
          trend="+8.4%"
          trendUp={true}
          iconBg="bg-purple-100"
          iconColor="text-purple-600"
        />
      </div>

      {/* Charts */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <ChartCard title="Patient Overview">
          <ResponsiveContainer width="100%" height={280}>
            <LineChart data={fallbackPatientData}>
              <XAxis dataKey="month" tick={{ fontSize: 12 }} />
              <YAxis tick={{ fontSize: 12 }} />
              <Tooltip />
              <Line
                type="monotone"
                dataKey="patients"
                stroke="#3B82F6"
                strokeWidth={2}
                dot={false}
              />
            </LineChart>
          </ResponsiveContainer>
        </ChartCard>

        <ChartCard title="Revenue Overview">
          <ResponsiveContainer width="100%" height={280}>
            <BarChart data={fallbackRevenueData}>
              <XAxis dataKey="month" tick={{ fontSize: 12 }} />
              <YAxis tick={{ fontSize: 12 }} />
              <Tooltip
                formatter={(v) => `₱${Number(v ?? 0).toLocaleString()}`}
              />
              <Bar dataKey="revenue" fill="#3B82F680" stroke="#3B82F6" />
            </BarChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      {/* Appointments + Department Overview */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
          <h3 className="text-lg font-semibold mb-4">Upcoming Appointments</h3>
          <div className="overflow-x-auto">
            <table className="min-w-full">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Patient
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Time
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {appointments.map((apt) => (
                  <tr key={apt.id}>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center">
                        <div className="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                          <Users className="w-4 h-4 text-gray-500" />
                        </div>
                        <div className="ml-4">
                          <div className="text-sm font-medium text-gray-900">
                            {apt.patient_name}
                          </div>
                          <div className="text-sm text-gray-500">
                            {apt.type}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {apt.date}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {apt.time}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <StatusBadge status={apt.status} />
                    </td>
                  </tr>
                ))}
                {appointments.length === 0 && (
                  <tr>
                    <td
                      colSpan={4}
                      className="px-6 py-8 text-center text-sm text-gray-500"
                    >
                      No upcoming appointments
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>

        <ChartCard title="Department Overview">
          <ResponsiveContainer width="100%" height={280}>
            <PieChart>
              <Pie
                data={fallbackDeptData}
                cx="50%"
                cy="50%"
                innerRadius={50}
                outerRadius={90}
                dataKey="value"
                label={({
                  name,
                  percent,
                }: {
                  name?: string;
                  percent?: number;
                }) => `${name ?? ""} ${((percent ?? 0) * 100).toFixed(0)}%`}
                labelLine={false}
              >
                {fallbackDeptData.map((_entry, idx) => (
                  <Cell
                    key={idx}
                    fill={DEPT_COLORS[idx % DEPT_COLORS.length]}
                  />
                ))}
              </Pie>
              <Tooltip />
              <Legend />
            </PieChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>
    </div>
  );
}
