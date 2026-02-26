import { useQuery } from "@tanstack/react-query";
import {
  Calendar,
  Activity,
  CheckCircle,
  DoorOpen,
  RefreshCw,
} from "lucide-react";
import { StatCard } from "@/components/ui/StatCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

export default function OperatingRoom() {
  const { data: statsData } = useQuery({
    queryKey: ["or-stats"],
    queryFn: async () => {
      const { data } = await client.get("/or/stats.php");
      return data;
    },
    retry: false,
  });
  const { data: schedData, refetch } = useQuery({
    queryKey: ["or-schedule"],
    queryFn: async () => {
      const { data } = await client.get("/or/schedule.php");
      return data;
    },
  });
  const { data: roomsData } = useQuery({
    queryKey: ["or-rooms"],
    queryFn: async () => {
      const { data } = await client.get("/or/rooms.php");
      return data;
    },
  });

  const stats = statsData?.stats ?? {
    scheduled: "-",
    in_progress: "-",
    completed: "-",
    available_rooms: "-",
  };
  const schedule: any[] = schedData?.ok ? (schedData.cases ?? []) : [];
  const rooms: any[] = roomsData?.ok ? (roomsData.rooms ?? []) : [];

  const roomColor = (status: string) => {
    if (status === "available")
      return "bg-green-50 border-green-200 text-green-800";
    if (status === "in_use") return "bg-red-50 border-red-200 text-red-800";
    if (status === "cleaning")
      return "bg-yellow-50 border-yellow-200 text-yellow-800";
    return "bg-gray-50 border-gray-200 text-gray-800";
  };

  return (
    <div>
      <div className="px-6 pt-6 mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Operating Room</h1>
        <p className="text-sm text-gray-600 mt-1">
          Surgery schedule, active cases, and theatre status.
        </p>
      </div>

      <div className="p-6 space-y-6">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <StatCard
            icon={Calendar}
            label="Scheduled Today"
            value={stats.scheduled}
            iconBg="bg-blue-100"
            iconColor="text-blue-600"
          />
          <StatCard
            icon={Activity}
            label="In Progress"
            value={stats.in_progress}
            iconBg="bg-orange-100"
            iconColor="text-orange-600"
          />
          <StatCard
            icon={CheckCircle}
            label="Completed"
            value={stats.completed}
            iconBg="bg-green-100"
            iconColor="text-green-600"
          />
          <StatCard
            icon={DoorOpen}
            label="Available Rooms"
            value={stats.available_rooms}
            iconBg="bg-purple-100"
            iconColor="text-purple-600"
          />
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div className="lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <h3 className="text-lg font-semibold text-gray-900">
                Surgery Schedule
              </h3>
              <button
                onClick={() => refetch()}
                className="px-3 py-1.5 bg-gray-900 text-white rounded-lg text-sm hover:bg-gray-800 flex items-center gap-1.5"
              >
                <RefreshCw className="w-3.5 h-3.5" /> Refresh
              </button>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Patient
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Procedure
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Surgeon
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Room
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
                  {schedule.length === 0 ? (
                    <tr>
                      <td
                        colSpan={6}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No cases scheduled
                      </td>
                    </tr>
                  ) : (
                    schedule.map((c: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {c.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {c.procedure}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {c.surgeon}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {c.room}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {c.time}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={c.status} />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-sm p-6">
            <h3 className="text-lg font-semibold text-gray-900 mb-4">
              Room Status
            </h3>
            {rooms.length === 0 ? (
              <div className="text-center py-6 text-gray-400 text-sm">
                No room data
              </div>
            ) : (
              <div className="grid grid-cols-2 gap-4">
                {rooms.map((r: any, i: number) => (
                  <div
                    key={i}
                    className={`p-3 rounded-lg border text-sm font-medium ${roomColor(r.status)}`}
                  >
                    <div className="font-semibold">{r.name}</div>
                    <div className="text-xs mt-1 capitalize">
                      {r.status?.replace("_", " ")}
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
