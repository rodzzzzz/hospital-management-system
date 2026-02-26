import { useState, useEffect, useRef, useCallback } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import {
  RefreshCw,
  Play,
  Pause,
  Bell,
  CheckCircle,
  UserPlus,
  ArrowRightLeft,
  UserMinus,
  Monitor,
  Settings,
  Cog,
  AlertTriangle,
  Search,
} from "lucide-react";
import { toast } from "sonner";
import { useModal } from "@/hooks/useModal";
import { Modal } from "@/components/ui/Modal";
import { useQueueWebSocket } from "@/hooks/useQueueWebSocket";
import client from "@/api/client";

const STATION_ROUTES: Record<string, string> = {
  opd: "/opd",
  doctor: "/doctor",
  pharmacy: "/pharmacy",
  cashier: "/cashier",
  xray: "/xray",
  lab: "/laboratory",
};

function playSound() {
  try {
    const audio = new Audio(
      "data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZURE",
    );
    audio.play().catch(() => {});
  } catch (_) {}
}

function playAlertSound() {
  try {
    const ctx = new (
      window.AudioContext || (window as any).webkitAudioContext
    )();
    [440, 880].forEach((freq, i) => {
      const osc = ctx.createOscillator();
      const gain = ctx.createGain();
      osc.connect(gain);
      gain.connect(ctx.destination);
      osc.frequency.value = freq;
      osc.type = "sine";
      gain.gain.value = 0.3;
      osc.start(ctx.currentTime + i * 0.3);
      osc.stop(ctx.currentTime + i * 0.3 + 0.25);
    });
  } catch (_) {}
}

const STATION_META: Record<
  string,
  { borderColor: string; bgColor: string; accent: string; iconBg: string }
> = {
  opd: {
    borderColor: "#3b82f6",
    bgColor: "bg-blue-600",
    accent: "text-blue-600",
    iconBg: "bg-blue-100 text-blue-600",
  },
  doctor: {
    borderColor: "#10b981",
    bgColor: "bg-green-600",
    accent: "text-green-600",
    iconBg: "bg-green-100 text-green-600",
  },
  pharmacy: {
    borderColor: "#f59e0b",
    bgColor: "bg-yellow-600",
    accent: "text-yellow-600",
    iconBg: "bg-yellow-100 text-yellow-600",
  },
  cashier: {
    borderColor: "#ef4444",
    bgColor: "bg-red-600",
    accent: "text-red-600",
    iconBg: "bg-red-100 text-red-600",
  },
  xray: {
    borderColor: "#06b6d4",
    bgColor: "bg-cyan-600",
    accent: "text-cyan-600",
    iconBg: "bg-cyan-100 text-cyan-600",
  },
  lab: {
    borderColor: "#14b8a6",
    bgColor: "bg-teal-600",
    accent: "text-teal-600",
    iconBg: "bg-teal-100 text-teal-600",
  },
};

export default function Queue() {
  const qc = useQueryClient();
  const [autoRefresh, setAutoRefresh] = useState(true);
  const addModal = useModal();
  const [addPatientId, setAddPatientId] = useState("");
  const [addStationId, setAddStationId] = useState("");

  // Fetch stations
  const { data: stationsData } = useQuery({
    queryKey: ["queue-stations"],
    queryFn: async () => {
      const { data } = await client.get("/queue/stations");
      return data;
    },
    staleTime: 60000,
  });
  const stations: any[] = stationsData?.stations ?? [];
  const stationMap: Record<string, any> = {};
  stations.forEach((s: any) => {
    stationMap[s.station_name] = s;
  });

  // Fetch all display data
  const { data: displayAllData, refetch } = useQuery({
    queryKey: ["queue-display-all"],
    queryFn: async () => {
      const { data } = await client.get("/queue/display/all");
      return data;
    },
  });

  useQueueWebSocket({ enabled: autoRefresh });
  const displays: Record<string, any> = displayAllData?.displays ?? {};

  // Fetch patients for add modal
  const { data: patientsData } = useQuery({
    queryKey: ["patients-list"],
    queryFn: async () => {
      const { data } = await client.get("/patients/list.php");
      return data;
    },
    enabled: addModal.open,
  });
  const patients: any[] = patientsData?.patients ?? patientsData?.data ?? [];

  // Call next mutation
  const callNextMut = useMutation({
    mutationFn: async (stationId: number) => {
      const { data } = await client.post("/queue/call-next", {
        station_id: stationId,
      });
      if (!data.success)
        throw new Error(data.message ?? "No patients in queue");
      return data;
    },
    onSuccess: (data) => {
      toast.success(`Called: ${data.patient?.full_name ?? "Next patient"}`);
      playSound();
      qc.invalidateQueries({ queryKey: ["queue-display-all"] });
    },
    onError: (e: Error) => toast.error(e.message),
  });

  // Complete service mutation
  const completeMut = useMutation({
    mutationFn: async (queueId: number) => {
      const { data } = await client.post("/queue/complete-service", {
        queue_id: queueId,
      });
      if (!data.success) throw new Error(data.message ?? "Failed to complete");
      return data;
    },
    onSuccess: () => {
      toast.success("Service completed");
      qc.invalidateQueries({ queryKey: ["queue-display-all"] });
    },
    onError: (e: Error) => toast.error(e.message),
  });

  // Add patient mutation
  const addMut = useMutation({
    mutationFn: async () => {
      const { data } = await client.post("/queue/add", {
        patient_id: parseInt(addPatientId),
        station_id: parseInt(addStationId),
      });
      if (!data.success) throw new Error(data.message ?? "Failed to add");
      return data;
    },
    onSuccess: () => {
      toast.success("Patient added to queue");
      addModal.hide();
      setAddPatientId("");
      setAddStationId("");
      qc.invalidateQueries({ queryKey: ["queue-display-all"] });
    },
    onError: (e: Error) => toast.error(e.message),
  });

  // ---- Queue Error Correction state ----
  const reportModal = useModal();
  const [qecStationId, setQecStationId] = useState<number | null>(null);
  const [qecStep, setQecStep] = useState<1 | 2>(1);
  const [qecTransfers, setQecTransfers] = useState<any[]>([]);
  const [qecSearch, setQecSearch] = useState("");
  const [qecSelectedTransfer, setQecSelectedTransfer] = useState<any>(null);
  const [qecCorrectStationId, setQecCorrectStationId] = useState<number | null>(
    null,
  );
  const [qecNotes, setQecNotes] = useState("");
  const [qecLoadingTransfers, setQecLoadingTransfers] = useState(false);
  const [pendingCorrections, setPendingCorrections] = useState<any[]>([]);
  const announcedAlertIds = useRef<Set<number>>(new Set());

  const openReportModal = useCallback(
    async (stationId: number) => {
      setQecStationId(stationId);
      setQecStep(1);
      setQecSelectedTransfer(null);
      setQecCorrectStationId(null);
      setQecNotes("");
      setQecSearch("");
      reportModal.show();
      setQecLoadingTransfers(true);
      try {
        const { data } = await client.get(
          `/queue/recent-transfers/${stationId}`,
        );
        setQecTransfers(data.transfers ?? []);
      } catch {
        setQecTransfers([]);
      }
      setQecLoadingTransfers(false);
    },
    [reportModal],
  );

  const reportErrorMut = useMutation({
    mutationFn: async () => {
      if (!qecSelectedTransfer || !qecCorrectStationId)
        throw new Error("Missing data");
      const wrongStationId =
        qecSelectedTransfer.current_station_id ||
        qecSelectedTransfer.to_station_id;
      const { data } = await client.post("/queue/report-error", {
        queue_id:
          qecSelectedTransfer.current_queue_id || qecSelectedTransfer.id,
        patient_id: qecSelectedTransfer.patient_id,
        wrong_station_id: wrongStationId,
        correct_station_id: qecCorrectStationId,
        notes: qecNotes || null,
      });
      if (!data.success) throw new Error(data.message ?? "Failed");
      return data;
    },
    onSuccess: () => {
      toast.success("Error reported! The station staff will be alerted.");
      reportModal.hide();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const confirmCorrectionMut = useMutation({
    mutationFn: async (errorLogId: number) => {
      const { data } = await client.post("/queue/confirm-correction", {
        error_log_id: errorLogId,
      });
      if (!data.success) throw new Error(data.message ?? "Failed");
      return data;
    },
    onSuccess: (data) => {
      toast.success(
        `Patient redirected to ${data.result?.correct_station_name ?? "correct station"}`,
      );
      setPendingCorrections((prev) =>
        prev.filter((c) => c.id !== data.result?.error_log_id),
      );
      qc.invalidateQueries({ queryKey: ["queue-display-all"] });
    },
    onError: (e: Error) => toast.error(e.message),
  });

  // Poll pending corrections for ALL stations
  useEffect(() => {
    if (stations.length === 0) return;
    let cancelled = false;
    const poll = async () => {
      try {
        const allPending: any[] = [];
        for (const st of stations) {
          const { data } = await client.get(
            `/queue/pending-corrections/${st.id}`,
          );
          if (data.success && data.corrections) {
            allPending.push(...data.corrections);
          }
        }
        if (!cancelled) {
          setPendingCorrections(allPending);
          for (const c of allPending) {
            if (!announcedAlertIds.current.has(c.id)) {
              announcedAlertIds.current.add(c.id);
              playAlertSound();
            }
          }
        }
      } catch {}
    };
    poll();
    const iv = setInterval(poll, 5000);
    return () => {
      cancelled = true;
      clearInterval(iv);
    };
  }, [stations]);

  const filteredTransfers = qecSearch
    ? qecTransfers.filter((t) => {
        const s = qecSearch.toLowerCase();
        return (
          (t.full_name ?? "").toLowerCase().includes(s) ||
          (t.patient_code ?? "").toLowerCase().includes(s) ||
          String(t.queue_number ?? "").includes(s)
        );
      })
    : qecTransfers;

  const preferredOrder = [
    "opd",
    "doctor",
    "pharmacy",
    "cashier",
    "xray",
    "lab",
  ];
  const orderedStationNames = preferredOrder.filter((n) => displays[n]);
  Object.keys(displays).forEach((n) => {
    if (!orderedStationNames.includes(n)) orderedStationNames.push(n);
  });

  return (
    <div>
      {/* Header */}
      <div className="bg-white rounded-xl shadow-sm p-6 mx-6 mt-6 flex flex-col gap-4 md:flex-row md:justify-between md:items-center">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">
            Queue Management Dashboard
          </h1>
          <p className="text-gray-600 mt-1 text-sm">
            Real-time patient queue monitoring across all stations
          </p>
        </div>
        <div className="flex items-center gap-3">
          <button
            onClick={() => refetch()}
            className="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 flex items-center gap-2"
          >
            <RefreshCw className="w-4 h-4" /> Refresh
          </button>
          <button
            onClick={() => setAutoRefresh(!autoRefresh)}
            className={`px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 text-white ${autoRefresh ? "bg-green-600 hover:bg-green-700" : "bg-gray-500 hover:bg-gray-600"}`}
          >
            {autoRefresh ? (
              <Pause className="w-4 h-4" />
            ) : (
              <Play className="w-4 h-4" />
            )}
            Auto Refresh: {autoRefresh ? "ON" : "OFF"}
          </button>
        </div>
      </div>

      {/* Station Overview Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 px-6 mt-6">
        {orderedStationNames.map((stName) => {
          const d = displays[stName];
          const meta = STATION_META[stName] ?? {
            borderColor: "#6b7280",
            bgColor: "bg-gray-600",
            accent: "text-gray-600",
            iconBg: "bg-gray-100 text-gray-600",
          };
          const station = d?.station;
          return (
            <div
              key={stName}
              className="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow"
            >
              <div className="flex items-center justify-between mb-4">
                <div className="flex items-center space-x-3">
                  <div
                    className={`w-12 h-12 rounded-full flex items-center justify-center ${meta.iconBg}`}
                  >
                    <Settings className="w-5 h-5" />
                  </div>
                  <div>
                    <h3 className="font-semibold text-gray-900">
                      {station?.station_display_name ?? stName}
                    </h3>
                    <p className="text-sm text-gray-500">Queue Management</p>
                  </div>
                </div>
              </div>
              <div className="space-y-3">
                <div className="flex justify-between items-center">
                  <span className="text-sm text-gray-600">
                    Currently Serving
                  </span>
                  <span className={`font-semibold ${meta.accent}`}>
                    {d?.currently_serving
                      ? d.currently_serving.queue_number
                      : "None"}
                  </span>
                </div>
                <div className="flex justify-between items-center">
                  <span className="text-sm text-gray-600">Waiting</span>
                  <span className="font-semibold">{d?.queue_count ?? 0}</span>
                </div>
                <div className="flex justify-between items-center">
                  <span className="text-sm text-gray-600">Est. Wait</span>
                  <span className="font-semibold">
                    {d?.estimated_wait_time ?? 0} min
                  </span>
                </div>
              </div>
              <div className="mt-4 flex gap-2">
                <button
                  onClick={() =>
                    window.open(`/queue-display/${stName}`, "_blank")
                  }
                  className={`flex-1 px-3 py-2 text-white rounded text-sm ${meta.bgColor} hover:opacity-90 flex items-center justify-center gap-1`}
                >
                  <Monitor className="w-3.5 h-3.5" /> Display
                </button>
                <button
                  onClick={() => {
                    const route = STATION_ROUTES[stName];
                    if (route) window.location.href = route;
                  }}
                  className="flex-1 px-3 py-2 bg-gray-600 text-white rounded text-sm hover:bg-gray-700 flex items-center justify-center gap-1"
                >
                  <Cog className="w-3.5 h-3.5" /> Manage
                </button>
              </div>
            </div>
          );
        })}
      </div>

      {/* Queue Details */}
      <div className="grid grid-cols-1 xl:grid-cols-2 gap-6 px-6 mt-6">
        {orderedStationNames.map((stName) => {
          const d = displays[stName];
          const meta = STATION_META[stName] ?? {
            borderColor: "#6b7280",
            bgColor: "bg-gray-600",
            accent: "text-gray-600",
            iconBg: "bg-gray-100 text-gray-600",
          };
          const station = d?.station;
          const stationId = station?.id;
          return (
            <div
              key={stName}
              className="bg-white rounded-lg shadow-sm overflow-hidden"
              style={{ borderLeft: `4px solid ${meta.borderColor}` }}
            >
              <div className={`${meta.bgColor} text-white p-4`}>
                <h3 className="text-lg font-semibold">
                  {station?.station_display_name ?? stName}
                </h3>
              </div>
              <div className="p-4">
                <div className="mb-4">
                  <span className="text-sm text-gray-600">
                    Currently Serving:
                  </span>
                  {d?.currently_serving ? (
                    <div>
                      <div className={`text-lg font-semibold ${meta.accent}`}>
                        {d.currently_serving.full_name}
                      </div>
                      <div className="text-sm text-gray-500">
                        {d.currently_serving.queue_number}
                      </div>
                    </div>
                  ) : (
                    <div className="text-gray-400">No one being served</div>
                  )}
                </div>
                <div className="mb-4">
                  <span className="text-sm text-gray-600">Queue Count: </span>
                  <span className="text-lg font-semibold">
                    {d?.queue_count ?? 0}
                  </span>
                </div>
                <div className="space-y-2 max-h-64 overflow-y-auto mb-4">
                  {(d?.next_patients ?? []).length === 0 ? (
                    <div className="text-center text-gray-400 py-4">
                      No patients in queue
                    </div>
                  ) : (
                    (d.next_patients ?? []).map((p: any, i: number) => (
                      <div
                        key={p.id ?? i}
                        className="flex items-center justify-between p-2 bg-gray-50 rounded"
                      >
                        <div className="flex items-center space-x-3">
                          <span className="text-lg font-semibold text-gray-600">
                            {i + 1}
                          </span>
                          <div>
                            <div className="font-medium">{p.full_name}</div>
                            <div className="text-sm text-gray-600">
                              {p.queue_number}
                            </div>
                          </div>
                        </div>
                        <div className="text-sm text-gray-500">
                          Est. {i * 15} min
                        </div>
                      </div>
                    ))
                  )}
                </div>
                <div className="flex gap-2 flex-wrap">
                  <button
                    onClick={() => {
                      if (stationId) callNextMut.mutate(stationId);
                    }}
                    disabled={callNextMut.isPending}
                    className={`px-3 py-1.5 text-white rounded text-sm flex items-center gap-1 ${meta.bgColor} hover:opacity-90 disabled:opacity-50`}
                  >
                    <Bell className="w-3.5 h-3.5" /> Call Next
                  </button>
                  <button
                    onClick={() => {
                      if (d?.currently_serving?.id)
                        completeMut.mutate(d.currently_serving.id);
                      else toast.warning("No patient currently being served");
                    }}
                    disabled={completeMut.isPending}
                    className="px-3 py-1.5 bg-green-600 text-white rounded text-sm flex items-center gap-1 hover:bg-green-700 disabled:opacity-50"
                  >
                    <CheckCircle className="w-3.5 h-3.5" /> Complete
                  </button>
                  <button
                    onClick={() => {
                      if (stationId) openReportModal(stationId);
                    }}
                    className="px-3 py-1.5 bg-red-600 text-white rounded text-sm flex items-center gap-1 hover:bg-red-700"
                  >
                    <AlertTriangle className="w-3.5 h-3.5" /> Report Error
                  </button>
                </div>
              </div>
            </div>
          );
        })}
      </div>

      {/* Quick Actions */}
      <div className="mx-6 mt-6 mb-6 bg-white rounded-lg shadow-sm p-6">
        <h3 className="text-lg font-semibold mb-4">Quick Actions</h3>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button
            onClick={addModal.show}
            className="px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center gap-2"
          >
            <UserPlus className="w-4 h-4" /> Add Patient to Queue
          </button>
          <button
            onClick={() => toast.info("Move patient feature coming soon")}
            className="px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center justify-center gap-2"
          >
            <ArrowRightLeft className="w-4 h-4" /> Move Patient
          </button>
          <button
            onClick={() => toast.info("Remove patient feature coming soon")}
            className="px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center justify-center gap-2"
          >
            <UserMinus className="w-4 h-4" /> Remove Patient
          </button>
        </div>
      </div>

      {/* Add Patient Modal */}
      <Modal
        open={addModal.open}
        onClose={addModal.hide}
        title="Add Patient to Queue"
      >
        <div className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Patient
            </label>
            <select
              value={addPatientId}
              onChange={(e) => setAddPatientId(e.target.value)}
              className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Select Patient</option>
              {patients.map((p: any) => (
                <option key={p.id} value={p.id}>
                  {p.full_name} ({p.patient_code})
                </option>
              ))}
            </select>
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Station
            </label>
            <select
              value={addStationId}
              onChange={(e) => setAddStationId(e.target.value)}
              className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Select Station</option>
              {stations.map((s: any) => (
                <option key={s.id} value={s.id}>
                  {s.station_display_name}
                </option>
              ))}
            </select>
          </div>
          <div className="flex justify-end gap-3 pt-2">
            <button
              onClick={addModal.hide}
              className="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              onClick={() => addMut.mutate()}
              disabled={!addPatientId || !addStationId || addMut.isPending}
              className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            >
              Add to Queue
            </button>
          </div>
        </div>
      </Modal>

      {/* Report Wrong Station Modal */}
      <Modal
        open={reportModal.open}
        onClose={reportModal.hide}
        title="Report Wrong Station"
        maxWidth="max-w-4xl"
      >
        {qecStep === 1 ? (
          <div>
            <p className="text-sm text-gray-600 mb-4">
              Select the wrongly transferred patient:
            </p>
            <div className="relative mb-4">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <input
                type="text"
                value={qecSearch}
                onChange={(e) => setQecSearch(e.target.value)}
                placeholder="Search patient name or code..."
                className="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-red-400 focus:outline-none"
              />
            </div>
            <div className="space-y-3 max-h-96 overflow-y-auto">
              {qecLoadingTransfers ? (
                <div className="text-center py-8 text-gray-400">
                  Loading transfers...
                </div>
              ) : filteredTransfers.length === 0 ? (
                <div className="text-center py-8 text-gray-500">
                  No recent transfers found today
                </div>
              ) : (
                filteredTransfers.map((t: any) => {
                  const isDischarged = t.current_status === "completed";
                  return (
                    <div
                      key={t.id}
                      onClick={() => {
                        if (!isDischarged) {
                          setQecSelectedTransfer(t);
                          setQecStep(2);
                          setQecCorrectStationId(null);
                        }
                      }}
                      className={`p-4 border-2 rounded-xl ${isDischarged ? "border-gray-200 opacity-50 cursor-not-allowed" : "border-gray-200 cursor-pointer hover:border-red-400 hover:bg-red-50"}`}
                    >
                      <div className="flex items-center justify-between">
                        <div className="flex items-center gap-3">
                          <div className="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                            <AlertTriangle className="w-5 h-5" />
                          </div>
                          <div>
                            <div className="font-bold text-gray-800">
                              {t.full_name ?? "Unknown"}
                            </div>
                            <div className="text-sm text-gray-500">
                              {t.patient_code ?? ""} · Queue #
                              {t.queue_number ?? "?"}
                            </div>
                          </div>
                        </div>
                        <div className="text-right">
                          <div className="text-sm font-semibold text-gray-700">
                            {isDischarged ? (
                              <span className="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-bold">
                                Discharged
                              </span>
                            ) : t.current_station_name ? (
                              <>
                                Currently at:{" "}
                                <span className="text-blue-600">
                                  {t.current_station_name}
                                </span>
                              </>
                            ) : (
                              <>
                                Sent to:{" "}
                                <span className="text-red-600">
                                  {t.to_station_name}
                                </span>
                              </>
                            )}
                          </div>
                          <div className="text-xs text-gray-400">
                            {t.transferred_at
                              ? new Date(t.transferred_at).toLocaleTimeString(
                                  "en-US",
                                  { hour: "2-digit", minute: "2-digit" },
                                )
                              : ""}
                          </div>
                        </div>
                      </div>
                      {t.journey?.length > 0 && (
                        <div className="mt-2 p-2 bg-blue-50 border border-blue-200 rounded text-sm text-gray-700">
                          Journey: {t.to_station_name} →{" "}
                          {t.journey
                            .map((j: any) => j.station_name)
                            .join(" → ")}
                        </div>
                      )}
                    </div>
                  );
                })
              )}
            </div>
          </div>
        ) : (
          <div>
            {qecSelectedTransfer && (
              <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div className="flex items-center gap-3">
                  <AlertTriangle className="w-6 h-6 text-red-500" />
                  <div>
                    <div className="font-bold text-red-800">
                      {qecSelectedTransfer.full_name}
                    </div>
                    <div className="text-sm text-red-600">
                      {qecSelectedTransfer.current_station_name ? (
                        <>
                          Currently at:{" "}
                          <strong>
                            {qecSelectedTransfer.current_station_name}
                          </strong>
                        </>
                      ) : (
                        <>
                          Wrongly sent to:{" "}
                          <strong>{qecSelectedTransfer.to_station_name}</strong>
                        </>
                      )}
                    </div>
                  </div>
                </div>
              </div>
            )}
            <div className="mb-4">
              <label className="block text-sm font-bold text-gray-700 mb-2">
                Reason for correction:
              </label>
              <textarea
                value={qecNotes}
                onChange={(e) => setQecNotes(e.target.value)}
                rows={2}
                className="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-red-400 focus:outline-none"
                placeholder="Explain why this patient needs to be moved..."
              />
            </div>
            <label className="block text-sm font-bold text-gray-700 mb-3">
              Select the CORRECT station:
            </label>
            <div className="space-y-2 max-h-64 overflow-y-auto">
              {stations
                .filter((s) => {
                  const wrongId =
                    qecSelectedTransfer?.current_station_id ||
                    qecSelectedTransfer?.to_station_id;
                  return s.id !== wrongId && s.id !== qecStationId;
                })
                .map((s: any) => (
                  <div
                    key={s.id}
                    onClick={() => setQecCorrectStationId(s.id)}
                    className={`p-4 border-2 rounded-xl cursor-pointer transition-all ${qecCorrectStationId === s.id ? "ring-4 ring-blue-500 bg-blue-50 border-blue-400" : "border-gray-200 hover:border-blue-400 hover:bg-blue-50"}`}
                  >
                    <div className="font-bold text-gray-800">
                      {s.station_display_name}
                    </div>
                  </div>
                ))}
            </div>
            <div className="flex justify-between items-center mt-4 pt-4 border-t">
              <button
                onClick={() => {
                  setQecStep(1);
                  setQecCorrectStationId(null);
                }}
                className="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50"
              >
                ← Back
              </button>
              <button
                onClick={() => reportErrorMut.mutate()}
                disabled={!qecCorrectStationId || reportErrorMut.isPending}
                className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
              >
                {reportErrorMut.isPending ? "Reporting..." : "⚠ Report Error"}
              </button>
            </div>
          </div>
        )}
      </Modal>

      {/* Incoming Correction Alert Overlay */}
      {pendingCorrections.length > 0 && (
        <div
          className="fixed inset-0 z-[100] bg-black/60 flex items-center justify-center p-8"
          style={{
            animation: "pulse 2s ease-in-out infinite",
            boxShadow: "inset 0 0 80px 40px rgba(220,38,38,0.4)",
          }}
        >
          <style>{`@keyframes pulse { 0%,100% { box-shadow: inset 0 0 60px 30px rgba(220,38,38,0.7); } 50% { box-shadow: inset 0 0 80px 40px rgba(220,38,38,0.3); } }`}</style>
          <div className="space-y-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            {pendingCorrections.map((c: any) => (
              <div
                key={c.id}
                className="bg-white rounded-2xl shadow-2xl p-8 border-4 border-red-500"
              >
                <div className="text-center mb-6">
                  <div className="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                    <AlertTriangle className="w-10 h-10 text-red-600" />
                  </div>
                  <h2 className="text-3xl font-black text-red-700">
                    WRONG STATION ALERT
                  </h2>
                  <p className="text-lg text-gray-600 mt-2">
                    A patient was sent here by mistake
                  </p>
                </div>
                {c.notes && (
                  <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <div className="text-sm font-bold text-yellow-700 mb-1">
                      Reason from reporting staff:
                    </div>
                    <div className="text-gray-800">{c.notes}</div>
                  </div>
                )}
                <div className="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
                  <div className="flex items-center gap-4">
                    <div className="w-16 h-16 bg-red-600 text-white rounded-xl flex items-center justify-center">
                      <span className="text-2xl font-bold">
                        {c.queue_number ?? "?"}
                      </span>
                    </div>
                    <div>
                      <div className="text-2xl font-black text-gray-900">
                        {c.full_name ?? "Unknown"}
                      </div>
                      <div className="text-gray-600">
                        {c.patient_code ?? ""}
                      </div>
                    </div>
                  </div>
                </div>
                <div className="text-center mb-6">
                  <span className="text-red-700 font-bold text-xl">
                    Should go to:{" "}
                  </span>
                  <span className="px-4 py-2 bg-red-600 text-white rounded-lg font-black text-xl">
                    {c.correct_station_name ?? "?"}
                  </span>
                  <div className="text-sm text-gray-500 mt-2">
                    Reported by: {c.reported_by_name ?? "Staff"} at{" "}
                    {c.reported_at
                      ? new Date(c.reported_at).toLocaleTimeString("en-US", {
                          hour: "2-digit",
                          minute: "2-digit",
                        })
                      : ""}
                  </div>
                </div>
                <div className="text-center">
                  <button
                    onClick={() => confirmCorrectionMut.mutate(c.id)}
                    disabled={confirmCorrectionMut.isPending}
                    className="px-8 py-4 bg-red-600 text-white rounded-xl hover:bg-red-700 text-lg font-black disabled:opacity-50"
                  >
                    {confirmCorrectionMut.isPending
                      ? "Processing..."
                      : "✓ Confirm & Redirect Patient"}
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
