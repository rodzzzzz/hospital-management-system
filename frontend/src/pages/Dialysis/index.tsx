import { type FormEvent } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import {
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
import { Users, CheckCircle, Clock, Plus } from "lucide-react";
import { toast } from "sonner";
import { useModal } from "@/hooks/useModal";
import { Modal } from "@/components/ui/Modal";
import { StatCard } from "@/components/ui/StatCard";
import { ChartCard } from "@/components/ui/ChartCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

const UTIL_COLORS = ["#3B82F6", "#10B981", "#F59E0B"];

export default function Dialysis() {
  const scheduleModal = useModal();

  /* ── data ── */
  const { data: statsData } = useQuery({
    queryKey: ["dialysis-stats"],
    queryFn: async () => {
      const { data } = await client.get("/dialysis/stats.php");
      return data;
    },
    retry: false,
  });
  const { data: sessionsData, refetch: refetchSessions } = useQuery({
    queryKey: ["dialysis-sessions"],
    queryFn: async () => {
      const { data } = await client.get("/dialysis/sessions.php");
      return data;
    },
  });
  const { data: machinesData } = useQuery({
    queryKey: ["dialysis-machines"],
    queryFn: async () => {
      const { data } = await client.get("/dialysis/machines.php");
      return data;
    },
  });
  const { data: chartsData } = useQuery({
    queryKey: ["dialysis-charts"],
    queryFn: async () => {
      const { data } = await client.get("/dialysis/charts.php");
      return data;
    },
  });

  const stats = statsData?.stats ?? {
    sessions_today: "-",
    available_machines: "-",
    avg_treatment: "-",
  };
  const sessions: any[] = sessionsData?.ok ? (sessionsData.sessions ?? []) : [];
  const machines: any[] = machinesData?.ok ? (machinesData.machines ?? []) : [];
  const hourlyLoad = (chartsData?.hourly ?? []) as {
    hour: string;
    count: number;
  }[];
  const utilization = (chartsData?.utilization ?? []) as {
    name: string;
    value: number;
  }[];

  const scheduleMut = useMutation({
    mutationFn: async (payload: Record<string, unknown>) => {
      const { data } = await client.post("/dialysis/schedule.php", payload);
      if (!data.ok) throw new Error(data.error ?? "Failed");
      return data;
    },
    onSuccess: () => {
      scheduleModal.hide();
      toast.success("Session scheduled");
      refetchSessions();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const handleSchedule = (e: FormEvent) => {
    e.preventDefault();
    const fd = new FormData(e.target as HTMLFormElement);
    const payload: Record<string, unknown> = {};
    fd.forEach((v, k) => {
      payload[k] = v;
    });
    scheduleMut.mutate(payload);
  };

  const machineColor = (status: string) => {
    if (status === "available")
      return "bg-green-50 border-green-200 text-green-800";
    if (status === "in_use") return "bg-blue-50 border-blue-200 text-blue-800";
    if (status === "maintenance")
      return "bg-yellow-50 border-yellow-200 text-yellow-800";
    return "bg-gray-50 border-gray-200 text-gray-800";
  };

  return (
    <div>
      <div className="flex justify-between items-center mb-6 px-6 pt-6">
        <h1 className="text-2xl font-bold text-gray-900">
          Dialysis Unit Dashboard
        </h1>
        <button
          onClick={() => scheduleModal.show()}
          className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
        >
          <Plus className="w-4 h-4" /> Schedule Session
        </button>
      </div>

      <div className="p-6 space-y-6">
        {/* Stats */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <StatCard
            icon={Users}
            label="Today's Sessions"
            value={stats.sessions_today}
            iconBg="bg-blue-100"
            iconColor="text-blue-600"
          />
          <StatCard
            icon={CheckCircle}
            label="Available Machines"
            value={stats.available_machines}
            iconBg="bg-green-100"
            iconColor="text-green-600"
          />
          <StatCard
            icon={Clock}
            label="Avg. Treatment Time"
            value={stats.avg_treatment}
            iconBg="bg-purple-100"
            iconColor="text-purple-600"
          />
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Schedule Table */}
          <div className="lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100">
              <h3 className="text-lg font-semibold text-gray-900">
                Today's Dialysis Schedule
              </h3>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Patient
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Machine
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Time
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {sessions.length === 0 ? (
                    <tr>
                      <td
                        colSpan={4}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No sessions today
                      </td>
                    </tr>
                  ) : (
                    sessions.map((s: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {s.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {s.machine_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {s.time_range ?? `${s.start_time} - ${s.end_time}`}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={s.status} />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>

          {/* Machine Status */}
          <div className="bg-white rounded-lg shadow-sm p-6">
            <h3 className="text-lg font-semibold text-gray-900 mb-4">
              Machine Status
            </h3>
            {machines.length === 0 ? (
              <div className="text-center py-6 text-gray-400 text-sm">
                No machine data
              </div>
            ) : (
              <div className="grid grid-cols-2 gap-4">
                {machines.map((m: any, i: number) => (
                  <div
                    key={i}
                    className={`p-3 rounded-lg border text-sm font-medium ${machineColor(m.status)}`}
                  >
                    <div className="font-semibold">{m.name}</div>
                    <div className="text-xs mt-1 capitalize">
                      {m.status?.replace("_", " ")}
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>

        {/* Charts */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <ChartCard title="Hourly Patient Load">
            <ResponsiveContainer width="100%" height={240}>
              <BarChart
                data={
                  hourlyLoad.length ? hourlyLoad : [{ hour: "8AM", count: 0 }]
                }
              >
                <XAxis dataKey="hour" tick={{ fontSize: 11 }} />
                <YAxis tick={{ fontSize: 11 }} />
                <Tooltip />
                <Bar dataKey="count" fill="#3B82F6" />
              </BarChart>
            </ResponsiveContainer>
          </ChartCard>
          <ChartCard title="Machine Utilization">
            <ResponsiveContainer width="100%" height={240}>
              <PieChart>
                <Pie
                  data={
                    utilization.length
                      ? utilization
                      : [{ name: "Available", value: 1 }]
                  }
                  cx="50%"
                  cy="50%"
                  innerRadius={50}
                  outerRadius={80}
                  dataKey="value"
                >
                  {(utilization.length
                    ? utilization
                    : [{ name: "Available", value: 1 }]
                  ).map((_, idx) => (
                    <Cell
                      key={idx}
                      fill={UTIL_COLORS[idx % UTIL_COLORS.length]}
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

      {/* Schedule Session Modal */}
      <Modal
        open={scheduleModal.open}
        onClose={scheduleModal.hide}
        title="Schedule Dialysis Session"
        maxWidth="max-w-lg"
      >
        <form onSubmit={handleSchedule} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700">
              Patient
            </label>
            <input
              name="patient_name"
              type="text"
              required
              placeholder="Patient name or ID"
              className="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700">
              Machine
            </label>
            <select
              name="machine_id"
              required
              className="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Select machine</option>
              {machines
                .filter((m: any) => m.status === "available")
                .map((m: any) => (
                  <option key={m.id} value={m.id}>
                    {m.name}
                  </option>
                ))}
            </select>
          </div>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700">
                Date
              </label>
              <input
                name="date"
                type="date"
                required
                className="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">
                Time Slot
              </label>
              <select
                name="time_slot"
                required
                className="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"
              >
                <option value="morning">Morning (8AM - 12PM)</option>
                <option value="afternoon">Afternoon (1PM - 5PM)</option>
                <option value="evening">Evening (6PM - 10PM)</option>
              </select>
            </div>
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700">
              Notes
            </label>
            <textarea
              name="notes"
              rows={3}
              placeholder="Any specific instructions..."
              className="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div className="flex justify-end gap-4 pt-2">
            <button
              type="button"
              onClick={scheduleModal.hide}
              className="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              disabled={scheduleMut.isPending}
              className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            >
              Schedule
            </button>
          </div>
        </form>
      </Modal>
    </div>
  );
}
