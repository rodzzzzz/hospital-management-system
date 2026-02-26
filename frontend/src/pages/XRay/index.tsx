import { useState } from "react";
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
  FileText,
  Clock,
  CheckCircle,
  Timer,
  Users,
  Bell,
  Send,
  Tv,
  ClipboardList,
  AlertTriangle,
  Search,
  RefreshCw,
} from "lucide-react";
import { toast } from "sonner";
import { useHashTab } from "@/hooks/useHashTab";
import { useModal } from "@/hooks/useModal";
import { useDebounce } from "@/hooks/useDebounce";
import { StatCard } from "@/components/ui/StatCard";
import { ChartCard } from "@/components/ui/ChartCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import { SendPatientModal } from "@/components/queue/SendPatientModal";
import { ReportWrongStationModal } from "@/components/queue/ReportWrongStationModal";
import { IncomingCorrectionAlert } from "@/components/queue/IncomingCorrectionAlert";
import { useQueueWebSocket } from "@/hooks/useQueueWebSocket";
import client from "@/api/client";

type XRayTab = "overview" | "scheduling" | "worklist" | "results";
const TABS: { key: XRayTab; label: string }[] = [
  { key: "overview", label: "Overview" },
  { key: "scheduling", label: "Scheduling" },
  { key: "worklist", label: "Worklist" },
  { key: "results", label: "Results & Release" },
];

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

export default function XRay() {
  const [tab, setTab] = useHashTab<XRayTab>("overview");
  const sendModal = useModal();
  const reportModal = useModal();
  const [wlStatus, setWlStatus] = useState("");
  const [wlSearch, setWlSearch] = useState("");
  const dWlSearch = useDebounce(wlSearch, 250);

  /* ── data ── */
  const { data: statsData } = useQuery({
    queryKey: ["xray-stats"],
    queryFn: async () => {
      const { data } = await client.get("/xray/stats.php");
      return data;
    },
    retry: false,
  });
  const { data: analyticsData } = useQuery({
    queryKey: ["xray-analytics"],
    queryFn: async () => {
      const { data } = await client.get("/xray/analytics.php");
      return data;
    },
    enabled: tab === "overview",
  });
  const { data: queueData, refetch: refetchQueue } = useQuery({
    queryKey: ["queue-display", 5],
    queryFn: async () => {
      const { data } = await client.get("/queue/display/5");
      return data;
    },
    enabled: tab === "overview",
  });
  useQueueWebSocket({ stationId: 5, enabled: tab === "overview" });
  const { data: schedData } = useQuery({
    queryKey: ["xray-scheduling"],
    queryFn: async () => {
      const { data } = await client.get("/xray/scheduling.php");
      return data;
    },
    enabled: tab === "scheduling",
  });
  const { data: wlData, refetch: refetchWl } = useQuery({
    queryKey: ["xray-worklist", wlStatus, dWlSearch],
    queryFn: async () => {
      const params = new URLSearchParams();
      if (wlStatus) params.set("status", wlStatus);
      if (dWlSearch) params.set("q", dWlSearch);
      const { data } = await client.get(`/xray/list.php?${params.toString()}`);
      return data;
    },
    enabled: tab === "worklist",
  });

  const stats = statsData?.stats ?? {
    orders_today: "-",
    pending: "-",
    reported: "-",
    avg_tat: "-",
  };
  const byType = (analyticsData?.by_type ?? []) as {
    type: string;
    count: number;
  }[];
  const byTime = (analyticsData?.by_time ?? []) as {
    window: string;
    count: number;
  }[];
  const tatTrend = (analyticsData?.tat_trend ?? []) as {
    day: string;
    mins: number;
  }[];
  const queueDisplay: DisplayQueueData = (queueData ?? {}) as DisplayQueueData;
  const currentlyServing = queueDisplay.currently_serving ?? null;
  const waitingQueue = queueDisplay.next_patients ?? [];
  const unavailable = queueDisplay.unavailable_patients ?? [];
  const schedStats = schedData?.stats ?? {
    queue_total: "-",
    in_progress: "-",
    scheduled: "-",
  };
  const schedOrders: any[] = schedData?.ok ? (schedData.orders ?? []) : [];
  const worklist: any[] = wlData?.ok ? (wlData.orders ?? []) : [];

  /* ── mutations ── */
  const callNextMut = useMutation({
    mutationFn: async () => {
      if (currentlyServing) {
        throw new Error(
          "Please complete the current patient service before calling the next patient",
        );
      }

      const { data } = await client.post("/queue/call-next", {
        station_id: 5,
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
        station_id: 5,
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
    window.open("/queue-display/xray", "_blank", "noopener,noreferrer");
  };

  return (
    <div>
      <div className="flex justify-between items-center mb-6 px-6 pt-6">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">X-Ray</h1>
          <p className="text-sm text-gray-600 mt-1">
            Radiology workload snapshot and turnaround insights.
          </p>
        </div>
      </div>

      <div className="bg-white border-b border-gray-200 px-6">
        <nav className="flex space-x-1 -mb-px overflow-x-auto">
          {TABS.map((t) => (
            <button
              key={t.key}
              onClick={() => setTab(t.key)}
              className={`px-4 py-3 text-sm font-semibold rounded-t-lg border-b-2 whitespace-nowrap transition-colors ${
                tab === t.key
                  ? "border-blue-600 text-blue-600 bg-blue-50"
                  : "border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50"
              }`}
            >
              {t.label}
            </button>
          ))}
        </nav>
      </div>

      <div className="p-6">
        {/* ═══ OVERVIEW ═══ */}
        {tab === "overview" && (
          <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
              <StatCard
                icon={FileText}
                label="Orders Today"
                value={stats.orders_today}
                iconBg="bg-sky-100"
                iconColor="text-sky-700"
              />
              <StatCard
                icon={Clock}
                label="Pending"
                value={stats.pending}
                iconBg="bg-amber-100"
                iconColor="text-amber-700"
              />
              <StatCard
                icon={CheckCircle}
                label="Reported Today"
                value={stats.reported}
                iconBg="bg-emerald-100"
                iconColor="text-emerald-700"
              />
              <StatCard
                icon={Timer}
                label="Avg TAT (mins)"
                value={stats.avg_tat}
                iconBg="bg-fuchsia-100"
                iconColor="text-fuchsia-700"
              />
            </div>

            {/* Queue */}
            <div className="bg-white rounded-lg shadow-sm p-6">
              <div className="flex justify-between items-center mb-6">
                <h3 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
                  <Users className="w-6 h-6 text-blue-600" /> X-Ray Queue
                </h3>
                <div className="flex gap-2">
                  <button
                    onClick={() => callNextMut.mutate()}
                    disabled={callNextMut.isPending || !!currentlyServing}
                    className="px-4 py-3 bg-blue-600 text-white rounded-lg text-lg font-semibold hover:bg-blue-700 flex items-center gap-2 disabled:opacity-50"
                  >
                    <Bell className="w-5 h-5" /> Call Next
                  </button>
                  <button
                    onClick={() => reportModal.show()}
                    className="px-4 py-3 bg-red-600 text-white rounded-lg text-lg font-semibold hover:bg-red-700 flex items-center gap-2"
                  >
                    <AlertTriangle className="w-5 h-5" /> Report
                  </button>
                </div>
              </div>

              <div className="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                <div className="flex items-center mb-2">
                  <div className="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2" />
                  <h4 className="text-lg font-semibold text-gray-800">
                    Currently Serving
                  </h4>
                </div>
                {currentlyServing ? (
                  <div>
                    <div className="py-2">
                      <span className="text-2xl font-bold text-green-700">
                        #{currentlyServing.queue_number}
                      </span>
                    </div>
                    <div className="flex items-center gap-2">
                      <span className="ml-3 text-gray-700 font-semibold">
                        {currentlyServing.full_name ??
                          currentlyServing.patient_name}
                      </span>
                      <span className="text-sm text-gray-600">
                        {currentlyServing.patient_code ?? ""}
                      </span>
                    </div>
                    <div className="mt-4 flex gap-2 justify-end">
                      <button
                        onClick={() => markUnavailableMut.mutate()}
                        disabled={markUnavailableMut.isPending}
                        className="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-semibold hover:bg-orange-700 disabled:opacity-50"
                      >
                        Mark Unavailable
                      </button>
                      <button
                        onClick={() => sendModal.show()}
                        className="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 flex items-center gap-2"
                      >
                        <Send className="w-4 h-4" /> Send
                      </button>
                    </div>
                  </div>
                ) : (
                  <div className="text-center py-3 text-gray-500">
                    No patient being served
                  </div>
                )}
              </div>

              <div className="mb-6">
                <h4 className="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                  <ClipboardList className="w-4 h-4 text-blue-600" /> Waiting
                  Queue
                </h4>
                {waitingQueue.length === 0 ? (
                  <div className="text-center py-6 text-gray-400">
                    No patients in queue
                  </div>
                ) : (
                  <div className="space-y-2">
                    {waitingQueue.map((item) => (
                      <div
                        key={item.id}
                        className="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                      >
                        <div className="flex items-center gap-3">
                          <span className="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                            #{item.queue_number}
                          </span>
                          <div className="flex flex-col">
                            <span className="text-sm font-medium text-gray-800">
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
                <div className="mb-6">
                  <h4 className="text-lg font-semibold text-gray-800 mb-3">
                    Unavailable
                  </h4>
                  <div className="space-y-2">
                    {unavailable.map((item) => (
                      <div
                        key={item.id}
                        onClick={() => recallUnavailableMut.mutate(item.id)}
                        className="p-3 bg-orange-50 rounded-lg text-sm text-gray-800 cursor-pointer hover:bg-orange-100 transition-colors"
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

              <div className="pt-4 border-t border-gray-200">
                <button
                  onClick={openDisplayScreen}
                  className="w-full px-4 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 flex items-center justify-center gap-2"
                >
                  <Tv className="w-4 h-4" /> Open Display Screen
                </button>
              </div>
            </div>

            {/* Charts */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <ChartCard title="Exams by Type (Today)">
                <ResponsiveContainer width="100%" height={240}>
                  <BarChart
                    data={
                      byType.length ? byType : [{ type: "Chest", count: 0 }]
                    }
                  >
                    <XAxis dataKey="type" tick={{ fontSize: 11 }} />
                    <YAxis tick={{ fontSize: 11 }} />
                    <Tooltip />
                    <Bar dataKey="count" fill="#0EA5E9" />
                  </BarChart>
                </ResponsiveContainer>
              </ChartCard>
              <ChartCard title="Orders by Time Window">
                <ResponsiveContainer width="100%" height={240}>
                  <BarChart
                    data={
                      byTime.length ? byTime : [{ window: "8-10", count: 0 }]
                    }
                  >
                    <XAxis dataKey="window" tick={{ fontSize: 11 }} />
                    <YAxis tick={{ fontSize: 11 }} />
                    <Tooltip />
                    <Bar dataKey="count" fill="#8B5CF6" />
                  </BarChart>
                </ResponsiveContainer>
              </ChartCard>
            </div>
            <ChartCard title="Turnaround Trend (7 days)">
              <ResponsiveContainer width="100%" height={240}>
                <LineChart
                  data={tatTrend.length ? tatTrend : [{ day: "Mon", mins: 0 }]}
                >
                  <XAxis dataKey="day" tick={{ fontSize: 11 }} />
                  <YAxis tick={{ fontSize: 11 }} unit=" min" />
                  <Tooltip />
                  <Line
                    type="monotone"
                    dataKey="mins"
                    stroke="#F59E0B"
                    strokeWidth={2}
                    dot={false}
                  />
                </LineChart>
              </ResponsiveContainer>
            </ChartCard>
          </div>
        )}

        {/* ═══ SCHEDULING ═══ */}
        {tab === "scheduling" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100">
              <h3 className="text-lg font-semibold text-gray-900">
                Scheduling
              </h3>
              <p className="text-sm text-gray-600 mt-1">
                Assigned slots, modality availability, and queue.
              </p>
            </div>
            <div className="p-6 border-b border-gray-100">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div className="rounded-lg border border-gray-200 p-4">
                  <div className="text-xs text-gray-500">Queue Total</div>
                  <div className="text-2xl font-semibold text-gray-900 mt-1">
                    {schedStats.queue_total}
                  </div>
                </div>
                <div className="rounded-lg border border-gray-200 p-4">
                  <div className="text-xs text-gray-500">In Progress</div>
                  <div className="text-2xl font-semibold text-gray-900 mt-1">
                    {schedStats.in_progress}
                  </div>
                </div>
                <div className="rounded-lg border border-gray-200 p-4">
                  <div className="text-xs text-gray-500">Scheduled</div>
                  <div className="text-2xl font-semibold text-gray-900 mt-1">
                    {schedStats.scheduled}
                  </div>
                </div>
              </div>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Patient
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Exam
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Priority
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Scheduled
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {schedOrders.length === 0 ? (
                    <tr>
                      <td
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No scheduling data
                      </td>
                    </tr>
                  ) : (
                    schedOrders.map((o: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {o.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {o.exam}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={o.priority} />
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={o.status} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {o.scheduled}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* ═══ WORKLIST ═══ */}
        {tab === "worklist" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-semibold text-gray-900">
                  Worklist
                </h3>
                <p className="text-sm text-gray-600 mt-1">
                  Latest imaging requests and their current status.
                </p>
              </div>
              <div className="flex items-center gap-2">
                <select
                  value={wlStatus}
                  onChange={(e) => setWlStatus(e.target.value)}
                  className="px-3 py-2 border border-gray-200 rounded-lg text-sm"
                >
                  <option value="">All</option>
                  <option value="requested">Requested</option>
                  <option value="scheduled">Scheduled</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                  <option value="reported">Reported</option>
                  <option value="cancelled">Cancelled</option>
                </select>
                <div className="relative">
                  <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                  <input
                    value={wlSearch}
                    onChange={(e) => setWlSearch(e.target.value)}
                    type="text"
                    placeholder="Search patient / exam"
                    className="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm"
                  />
                </div>
                <button
                  onClick={() => refetchWl()}
                  className="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm flex items-center gap-1"
                >
                  <RefreshCw className="w-3.5 h-3.5" /> Refresh
                </button>
              </div>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Patient
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Exam
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Priority
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Ordered
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {worklist.length === 0 ? (
                    <tr>
                      <td
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No orders
                      </td>
                    </tr>
                  ) : (
                    worklist.map((o: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {o.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {o.exam}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={o.priority} />
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={o.status} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {o.ordered}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* ═══ RESULTS ═══ */}
        {tab === "results" && (
          <div className="bg-white rounded-lg shadow-sm p-6">
            <h3 className="text-lg font-semibold text-gray-900">
              Results & Release
            </h3>
            <p className="text-sm text-gray-600 mt-1">
              View and release X-ray results.
            </p>
            <div className="mt-6 text-center py-12 text-gray-400">
              <FileText className="w-12 h-12 mx-auto mb-3 text-gray-300" />
              <p>Results release panel — connects to X-ray results API.</p>
            </div>
          </div>
        )}
      </div>

      {/* Modals */}
      <SendPatientModal
        open={sendModal.open}
        onClose={sendModal.hide}
        currentlyServing={currentlyServing}
        currentStationId={5}
        onSuccess={refetchQueue}
      />

      <ReportWrongStationModal
        open={reportModal.open}
        onClose={reportModal.hide}
        stationId={5}
        onSuccess={refetchQueue}
      />

      <IncomingCorrectionAlert stationId={5} onCorrection={refetchQueue} />
    </div>
  );
}
