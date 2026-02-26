import { useQuery } from "@tanstack/react-query";
import { Baby, HeartPulse, UserCheck, DoorOpen, RefreshCw } from "lucide-react";
import { StatCard } from "@/components/ui/StatCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

export default function DeliveryRoom() {
  const { data: statsData } = useQuery({
    queryKey: ["dr-stats"],
    queryFn: async () => {
      const { data } = await client.get("/dr/stats.php");
      return data;
    },
    retry: false,
  });
  const { data: laborData, refetch } = useQuery({
    queryKey: ["dr-labor"],
    queryFn: async () => {
      const { data } = await client.get("/dr/labor.php");
      return data;
    },
  });
  const { data: roomsData } = useQuery({
    queryKey: ["dr-rooms"],
    queryFn: async () => {
      const { data } = await client.get("/dr/rooms.php");
      return data;
    },
  });

  const stats = statsData?.stats ?? {
    deliveries_today: "-",
    in_labor: "-",
    newborns: "-",
    available_rooms: "-",
  };
  const laborQueue: any[] = laborData?.ok ? (laborData.patients ?? []) : [];
  const rooms: any[] = roomsData?.ok ? (roomsData.rooms ?? []) : [];

  const roomColor = (status: string) => {
    if (status === "available")
      return "bg-green-50 border-green-200 text-green-800";
    if (status === "occupied")
      return "bg-pink-50 border-pink-200 text-pink-800";
    if (status === "cleaning")
      return "bg-yellow-50 border-yellow-200 text-yellow-800";
    return "bg-gray-50 border-gray-200 text-gray-800";
  };

  return (
    <div>
      <div className="px-6 pt-6 mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Delivery Room</h1>
        <p className="text-sm text-gray-600 mt-1">
          Labor queue, delivery records, and newborn care.
        </p>
      </div>

      <div className="p-6 space-y-6">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <StatCard
            icon={Baby}
            label="Deliveries Today"
            value={stats.deliveries_today}
            iconBg="bg-pink-100"
            iconColor="text-pink-600"
          />
          <StatCard
            icon={HeartPulse}
            label="In Labor"
            value={stats.in_labor}
            iconBg="bg-red-100"
            iconColor="text-red-600"
          />
          <StatCard
            icon={UserCheck}
            label="Newborns"
            value={stats.newborns}
            iconBg="bg-blue-100"
            iconColor="text-blue-600"
          />
          <StatCard
            icon={DoorOpen}
            label="Available Rooms"
            value={stats.available_rooms}
            iconBg="bg-green-100"
            iconColor="text-green-600"
          />
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div className="lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <h3 className="text-lg font-semibold text-gray-900">
                Labor Queue
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
                      Room
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      OB-GYN
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Admitted
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Stage
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {laborQueue.length === 0 ? (
                    <tr>
                      <td
                        colSpan={6}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No patients in labor
                      </td>
                    </tr>
                  ) : (
                    laborQueue.map((p: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {p.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {p.room}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {p.doctor}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {p.admitted}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {p.stage}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={p.status} />
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
