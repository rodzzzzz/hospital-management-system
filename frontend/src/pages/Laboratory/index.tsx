import { useQuery, useMutation } from "@tanstack/react-query";
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  Tooltip,
  ResponsiveContainer,
  LineChart,
  Line,
} from "recharts";
import {
  FlaskConical,
  Clock,
  CheckCircle,
  AlertTriangle,
  Users,
  Bell,
  Tv,
  ClipboardList,
  RefreshCw,
  UserXIcon,
} from "lucide-react";
import { toast } from "sonner";
import { useModal } from "@/hooks/useModal";
import { StatCard } from "@/components/ui/StatCard";
import { ChartCard } from "@/components/ui/ChartCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import { SendPatientModal } from "@/components/queue/SendPatientModal";
import { ReportWrongStationModal } from "@/components/queue/ReportWrongStationModal";
import { IncomingCorrectionAlert } from "@/components/queue/IncomingCorrectionAlert";
import { useQueueWebSocket } from "@/hooks/useQueueWebSocket";
import client from "@/api/client";

interface QueueItem {
  id: number;
  queue_number: number;
  patient_name?: string;
  full_name?: string;
  patient_code?: string;
  status: string;
  created_at: string;
}

interface DisplayQueueData {
  currently_serving?: QueueItem | null;
  next_patients?: QueueItem[];
  unavailable_patients?: QueueItem[];
  queue_count?: number;
  estimated_wait_time?: number;
}
interface TestRequest {
  id: number;
  patient_name: string;
  test_name: string;
  status: string;
  created_at: string;
}

export default function Laboratory() {
  const sendModal = useModal();
  const reportModal = useModal();

  /* ── data ── */
  const { data: statsData } = useQuery({
    queryKey: ["lab-stats"],
    queryFn: async () => {
      const { data } = await client.get("/laboratory/stats.php");
      return data;
    },
    retry: false,
  });

  const { data: queueData, refetch: refetchQueue } = useQuery({
    queryKey: ["queue-display", 6],
    queryFn: async () => {
      const { data } = await client.get("/queue/display/6");
      return data;
    },
  });

  useQueueWebSocket({ stationId: 6 });

  const { data: requestsData, refetch: refetchReqs } = useQuery({
    queryKey: ["lab-requests"],
    queryFn: async () => {
      const { data } = await client.get("/laboratory/requests.php");
      return data;
    },
  });

  const stats = statsData?.cards ?? {
    pending: 0,
    in_progress: 0,
    completed_today: 0,
    avg_turnaround: "-",
  };
  const queueDisplay: DisplayQueueData = (queueData ?? {}) as DisplayQueueData;
  const requests: TestRequest[] = requestsData?.ok
    ? (requestsData.requests ?? [])
    : [];
  const currentlyServing = queueDisplay.currently_serving ?? null;
  const waitingQueue = queueDisplay.next_patients ?? [];
  const unavailable = queueDisplay.unavailable_patients ?? [];
  const volumeData = (statsData?.charts?.volume ?? []) as {
    type: string;
    count: number;
  }[];
  const throughputData = (statsData?.charts?.throughput ?? []) as {
    day: string;
    count: number;
  }[];

  /* ── mutations ── */
  const callNextMut = useMutation({
    mutationFn: async () => {
      if (currentlyServing) {
        throw new Error(
          "Please complete the current patient service before calling the next patient",
        );
      }

      const { data } = await client.post("/queue/call-next", {
        station_id: 6,
      });
      if (!(data.success === true || data.ok === true)) {
        throw new Error(
          data.message ?? data.error ?? "No more patients in the waiting queue",
        );
      }
      return data;
    },
    onSuccess: () => {
      toast.success("Next patient called successfully");
      refetchQueue();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const markUnavailableMut = useMutation({
    mutationFn: async () => {
      const { data } = await client.post("/queue/call-next-mark-unavailable", {
        station_id: 6,
        notes: "Patient not available for service",
      });
      if (!(data.success === true || data.ok === true)) {
        throw new Error(
          data.message ?? data.error ?? "No more patients in the waiting queue",
        );
      }
      return data;
    },
    onSuccess: () => {
      toast.success(
        "Next patient called and previous patient marked as unavailable",
      );
      refetchQueue();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const recallUnavailableMut = useMutation({
    mutationFn: async (queueId: number) => {
      const { data } = await client.post("/queue/recall-unavailable", {
        queue_id: queueId,
        notes: "Recalled from unavailable list",
      });
      if (!(data.success === true || data.ok === true)) {
        throw new Error(
          data.message ?? data.error ?? "Unable to recall patient",
        );
      }
      return data;
    },
    onSuccess: () => {
      toast.success("Patient recalled successfully");
      refetchQueue();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const openDisplayScreen = () => {
    window.open("/queue-display/laboratory", "_blank", "noopener,noreferrer");
  };

  return (
    <div>
      <div className="flex justify-between items-center mb-6 px-6 pt-6">
        <h1 className="text-2xl font-bold text-gray-900">
          Laboratory Management
        </h1>
      </div>

      <div className="p-6">
        {/* Stats */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
          <StatCard
            icon={FlaskConical}
            label="Pending Tests"
            value={stats.pending}
            iconBg="bg-yellow-100"
            iconColor="text-yellow-600"
          />
          <StatCard
            icon={Clock}
            label="In Progress"
            value={stats.in_progress}
            iconBg="bg-blue-100"
            iconColor="text-blue-600"
          />
          <StatCard
            icon={CheckCircle}
            label="Completed Today"
            value={stats.completed_today}
            iconBg="bg-green-100"
            iconColor="text-green-600"
          />
          <StatCard
            icon={AlertTriangle}
            label="Avg. Turnaround"
            value={stats.avg_turnaround}
            iconBg="bg-purple-100"
            iconColor="text-purple-600"
          />
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Queue */}
          <div className="bg-white rounded-lg shadow-sm p-6">
            <div className="flex justify-between items-center mb-6">
              <h3 className="text-xl font-bold text-gray-900 flex items-center gap-2">
                <Users className="w-5 h-5 text-blue-600" /> Lab Queue
              </h3>
              <div className="flex gap-2">
                <button
                  onClick={() => callNextMut.mutate()}
                  disabled={callNextMut.isPending || !!currentlyServing}
                  className="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-1.5 text-sm font-semibold disabled:opacity-50"
                >
                  <Bell className="w-4 h-4" /> Call Next
                </button>
                <button
                  onClick={() => reportModal.show()}
                  className="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-1.5 text-sm font-semibold"
                >
                  <AlertTriangle className="w-4 h-4" /> Report
                </button>
              </div>
            </div>

            <div className="mb-4 p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
              <div className="flex items-center mb-1">
                <div className="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2" />
                <h4 className="text-sm font-semibold text-gray-800">
                  Currently Serving
                </h4>
              </div>
              {currentlyServing ? (
                <div>
                  <div className="py-1">
                    <span className="text-xl font-bold text-green-700">
                      #{currentlyServing.queue_number}
                    </span>
                  </div>
                  <div className="flex items-center gap-2">
                    <span className="text-sm text-gray-700 font-semibold">
                      {currentlyServing.full_name ??
                        currentlyServing.patient_name}
                    </span>
                    <span className="text-xs text-gray-600">
                      {currentlyServing.patient_code ?? ""}
                    </span>
                  </div>
                  <div className="mt-2 flex gap-2 justify-end">
                    <button
                      onClick={() => markUnavailableMut.mutate()}
                      disabled={markUnavailableMut.isPending}
                      className="px-3 py-1.5 bg-orange-600 text-white rounded-lg text-xs font-semibold hover:bg-orange-700 flex items-center gap-1 disabled:opacity-50"
                    >
                      <UserXIcon className="w-3 h-3" /> Unavailable
                    </button>
                    <button
                      onClick={() => sendModal.show()}
                      className="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-semibold hover:bg-green-700 flex items-center gap-1"
                    >
                      Send
                    </button>
                  </div>
                </div>
              ) : (
                <div className="text-center py-2 text-gray-500 text-sm">
                  No patient being served
                </div>
              )}
            </div>

            <div className="mb-4">
              <h4 className="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-1">
                <ClipboardList className="w-3.5 h-3.5 text-blue-600" /> Waiting
              </h4>
              {waitingQueue.length === 0 ? (
                <div className="text-center py-4 text-gray-400 text-sm">
                  No patients in queue
                </div>
              ) : (
                <div className="space-y-1.5">
                  {waitingQueue.map((item) => (
                    <div
                      key={item.id}
                      className="flex items-center justify-between p-2 bg-gray-50 rounded-lg"
                    >
                      <div className="flex items-center gap-2">
                        <span className="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                          #{item.queue_number}
                        </span>
                        <div className="flex flex-col">
                          <span className="text-xs font-medium text-gray-800">
                            {item.full_name ?? item.patient_name}
                          </span>
                          <span className="text-xs text-gray-500">
                            {item.patient_code ?? ""}
                          </span>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>

            {unavailable.length > 0 && (
              <div className="mb-4">
                <h4 className="text-sm font-semibold text-gray-800 mb-2">
                  Unavailable
                </h4>
                <div className="space-y-1.5">
                  {unavailable.map((item) => (
                    <div
                      key={item.id}
                      onClick={() => recallUnavailableMut.mutate(item.id)}
                      className="p-2 bg-orange-50 rounded-lg text-xs text-gray-800 cursor-pointer hover:bg-orange-100 transition-colors"
                    >
                      <div>
                        #{item.queue_number} —{" "}
                        {item.full_name ?? item.patient_name}
                        <div className="text-xs text-gray-500 mt-0.5">
                          {item.patient_code ?? ""}
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            )}

            <div className="pt-3 border-t border-gray-200">
              <button
                onClick={openDisplayScreen}
                className="w-full px-3 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 flex items-center justify-center gap-1.5 text-sm"
              >
                <Tv className="w-3.5 h-3.5" /> Display Screen
              </button>
            </div>
          </div>

          {/* Test Requests Table */}
          <div className="lg:col-span-2 space-y-6">
            <div className="bg-white rounded-lg shadow-sm overflow-hidden">
              <div className="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 className="text-lg font-semibold text-gray-900">
                  Recent Test Requests
                </h3>
                <button
                  onClick={() => refetchReqs()}
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
                        Test
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Status
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Date
                      </th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {requests.length === 0 ? (
                      <tr>
                        <td
                          colSpan={4}
                          className="px-6 py-8 text-center text-sm text-gray-500"
                        >
                          No test requests
                        </td>
                      </tr>
                    ) : (
                      requests.slice(0, 15).map((r) => (
                        <tr key={r.id}>
                          <td className="px-6 py-4 text-sm font-medium text-gray-900">
                            {r.patient_name}
                          </td>
                          <td className="px-6 py-4 text-sm text-gray-600">
                            {r.test_name}
                          </td>
                          <td className="px-6 py-4">
                            <StatusBadge status={r.status} />
                          </td>
                          <td className="px-6 py-4 text-sm text-gray-500">
                            {r.created_at}
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </div>

            {/* Charts */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <ChartCard title="Test Volume by Type">
                <ResponsiveContainer width="100%" height={200}>
                  <BarChart
                    data={
                      volumeData.length
                        ? volumeData
                        : [{ type: "CBC", count: 0 }]
                    }
                  >
                    <XAxis dataKey="type" tick={{ fontSize: 11 }} />
                    <YAxis tick={{ fontSize: 11 }} />
                    <Tooltip />
                    <Bar dataKey="count" fill="#3B82F6" />
                  </BarChart>
                </ResponsiveContainer>
              </ChartCard>
              <ChartCard title="Daily Throughput">
                <ResponsiveContainer width="100%" height={200}>
                  <LineChart
                    data={
                      throughputData.length
                        ? throughputData
                        : [{ day: "Mon", count: 0 }]
                    }
                  >
                    <XAxis dataKey="day" tick={{ fontSize: 11 }} />
                    <YAxis tick={{ fontSize: 11 }} />
                    <Tooltip />
                    <Line
                      type="monotone"
                      dataKey="count"
                      stroke="#10B981"
                      strokeWidth={2}
                      dot={false}
                    />
                  </LineChart>
                </ResponsiveContainer>
              </ChartCard>
            </div>
          </div>
        </div>
      </div>

      {/* Modals */}
      <SendPatientModal
        open={sendModal.open}
        onClose={sendModal.hide}
        currentlyServing={currentlyServing}
        currentStationId={6}
        onSuccess={refetchQueue}
      />

      <ReportWrongStationModal
        open={reportModal.open}
        onClose={reportModal.hide}
        stationId={6}
        onSuccess={refetchQueue}
      />

      <IncomingCorrectionAlert stationId={6} onCorrection={refetchQueue} />
    </div>
  );
}
