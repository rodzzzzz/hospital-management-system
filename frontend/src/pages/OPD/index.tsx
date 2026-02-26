import { useState, type FormEvent } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  Tooltip,
  ResponsiveContainer,
} from "recharts";
import {
  CalendarCheck,
  Users,
  CheckCircle,
  Clock,
  Bell,
  AlertTriangle,
  Send,
  Tv,
  RefreshCw,
  Plus,
  FileText,
  Stethoscope,
  Receipt,
  FlaskConical,
  ImageIcon,
  ClipboardList,
  UserXIcon,
} from "lucide-react";
import { toast } from "sonner";
import { useHashTab } from "@/hooks/useHashTab";
import { useModal } from "@/hooks/useModal";
import { Modal } from "@/components/ui/Modal";
import { StatCard } from "@/components/ui/StatCard";
import { ChartCard } from "@/components/ui/ChartCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import { SearchInput } from "@/components/ui/SearchInput";
import { useDebounce } from "@/hooks/useDebounce";
import { useQueueWebSocket } from "@/hooks/useQueueWebSocket";
import client from "@/api/client";

type OPDTab =
  | "overview"
  | "nursing"
  | "consultation"
  | "billing"
  | "appointments"
  | "requests"
  | "newlab"
  | "xray"
  | "labrequests"
  | "labresults";

const TABS: { key: OPDTab; label: string; icon: React.ReactNode }[] = [
  {
    key: "overview",
    label: "Overview",
    icon: <CalendarCheck className="w-4 h-4" />,
  },
  {
    key: "nursing",
    label: "Nursing Assessment",
    icon: <Stethoscope className="w-4 h-4" />,
  },
  {
    key: "consultation",
    label: "Consultation Notes",
    icon: <FileText className="w-4 h-4" />,
  },
  { key: "billing", label: "Billing", icon: <Receipt className="w-4 h-4" /> },
  {
    key: "appointments",
    label: "Appointments",
    icon: <CalendarCheck className="w-4 h-4" />,
  },
  {
    key: "requests",
    label: "Requests",
    icon: <ClipboardList className="w-4 h-4" />,
  },
  {
    key: "newlab",
    label: "New Lab Request",
    icon: <FlaskConical className="w-4 h-4" />,
  },
  { key: "xray", label: "X-Ray", icon: <ImageIcon className="w-4 h-4" /> },
  {
    key: "labrequests",
    label: "Lab Requests",
    icon: <FlaskConical className="w-4 h-4" />,
  },
  {
    key: "labresults",
    label: "Lab Results",
    icon: <FileText className="w-4 h-4" />,
  },
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

interface QueueStation {
  id: number;
  station_name: string;
  station_display_name: string;
}

interface DisplayQueueData {
  currently_serving?: QueueItem | null;
  next_patients?: QueueItem[];
  unavailable_patients?: QueueItem[];
  queue_count?: number;
  estimated_wait_time?: number;
}

interface TransferJourney {
  station_name?: string;
}

interface RecentTransfer {
  id: number;
  patient_id: number;
  queue_number?: number;
  full_name?: string;
  patient_code?: string;
  to_station_id?: number;
  to_station_name?: string;
  current_queue_id?: number;
  current_station_id?: number;
  current_station_name?: string;
  current_status?: string;
  transferred_at?: string;
  journey?: TransferJourney[];
}

interface AppointmentRow {
  id: number;
  patient_name: string;
  doctor_name: string;
  time: string;
  status: string;
}

const inputCls =
  "w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none";

export default function OPD() {
  const [tab, setTab] = useHashTab<OPDTab>("overview");
  const [apptSearch, setApptSearch] = useState("");
  const debouncedApptSearch = useDebounce(apptSearch, 250);
  const sendModal = useModal();
  const reportModal = useModal();
  const [selectedStation, setSelectedStation] = useState("");
  const [reportStep, setReportStep] = useState<1 | 2>(1);
  const [transferSearch, setTransferSearch] = useState("");
  const [reportReason, setReportReason] = useState("");
  const [selectedTransfer, setSelectedTransfer] =
    useState<RecentTransfer | null>(null);
  const [selectedCorrectStationId, setSelectedCorrectStationId] = useState<
    number | null
  >(null);

  /* ── data ── */
  const { data: statsData } = useQuery({
    queryKey: ["opd-stats"],
    queryFn: async () => {
      const { data } = await client.get("/opd/stats.php");
      return data;
    },
    enabled: tab === "overview",
    retry: false,
  });

  const { data: queueData, refetch: refetchQueue } = useQuery({
    queryKey: ["opd-queue"],
    queryFn: async () => {
      const { data } = await client.get("/queue/display/1");
      return data;
    },
    enabled: tab === "overview",
  });

  useQueueWebSocket({ stationId: 1, enabled: tab === "overview" });

  const { data: stationsData } = useQuery({
    queryKey: ["queue-stations"],
    queryFn: async () => {
      const { data } = await client.get("/queue/stations");
      return data;
    },
    enabled: tab === "overview" || sendModal.open,
    staleTime: 60000,
  });

  const { data: recentTransfersData, refetch: refetchRecentTransfers } =
    useQuery({
      queryKey: ["opd-recent-transfers"],
      queryFn: async () => {
        const { data } = await client.get("/queue/recent-transfers/1");
        return data;
      },
      enabled: reportModal.open && reportStep === 1,
    });

  const { data: apptData, refetch: refetchAppts } = useQuery({
    queryKey: ["opd-appointments", debouncedApptSearch],
    queryFn: async () => {
      const q = debouncedApptSearch
        ? `&q=${encodeURIComponent(debouncedApptSearch)}`
        : "";
      const { data } = await client.get(
        `/opd/list_appointments.php?today=1${q}`,
      );
      return data;
    },
    enabled: tab === "overview" || tab === "appointments",
  });

  const { data: labReqData, refetch: refetchLabReqs } = useQuery({
    queryKey: ["opd-lab-requests"],
    queryFn: async () => {
      const { data } = await client.get("/opd/lab_requests.php");
      return data;
    },
    enabled: tab === "labrequests",
  });

  const { data: labResultsData } = useQuery({
    queryKey: ["opd-lab-results"],
    queryFn: async () => {
      const { data } = await client.get("/opd/lab_results.php");
      return data;
    },
    enabled: tab === "labresults",
  });

  const stats = statsData?.cards ?? {
    today_appointments: 0,
    waiting: 0,
    completed: 0,
    avg_wait: "-",
  };
  const queueDisplay: DisplayQueueData = (queueData ?? {}) as DisplayQueueData;
  const queue: QueueItem[] = queueDisplay.next_patients ?? [];
  const stations: QueueStation[] = (
    (stationsData?.stations ?? []) as QueueStation[]
  ).filter((s) => s.id !== 1);
  const appointments: AppointmentRow[] = apptData?.ok
    ? (apptData.appointments ?? [])
    : [];
  const allRecentTransfers: RecentTransfer[] = recentTransfersData?.success
    ? ((recentTransfersData.transfers ?? []) as RecentTransfer[])
    : [];
  const filteredRecentTransfers = allRecentTransfers.filter((t) => {
    const search = transferSearch.trim().toLowerCase();
    if (!search) return true;
    return (
      (t.full_name ?? "").toLowerCase().includes(search) ||
      (t.patient_code ?? "").toLowerCase().includes(search) ||
      String(t.queue_number ?? "").includes(search)
    );
  });
  const currentlyServing = queueDisplay.currently_serving ?? null;
  const waitingQueue = queue;
  const unavailable = queueDisplay.unavailable_patients ?? [];
  const waitingCount = queueDisplay.queue_count ?? stats.waiting;
  const avgWaitTime =
    typeof queueDisplay.estimated_wait_time === "number"
      ? `${queueDisplay.estimated_wait_time} min`
      : stats.avg_wait;
  const labRequests: Record<string, unknown>[] = labReqData?.ok
    ? (labReqData.requests ?? [])
    : [];
  const labResults: Record<string, unknown>[] = labResultsData?.ok
    ? (labResultsData.results ?? [])
    : [];

  const volumeData = (statsData?.charts?.volume ?? []) as {
    hour: string;
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
        station_id: 1,
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

  const reportWrongStationMut = useMutation({
    mutationFn: async () => {
      if (!selectedTransfer || !selectedCorrectStationId) {
        throw new Error("Please select patient and correct station");
      }

      const wrongStationId =
        selectedTransfer.current_station_id ?? selectedTransfer.to_station_id;
      const queueId = selectedTransfer.current_queue_id ?? selectedTransfer.id;

      if (!wrongStationId || !queueId || !selectedTransfer.patient_id) {
        throw new Error(
          "Missing transfer data. Please try selecting patient again.",
        );
      }

      const { data } = await client.post("/queue/report-error", {
        queue_id: queueId,
        patient_id: selectedTransfer.patient_id,
        wrong_station_id: wrongStationId,
        correct_station_id: selectedCorrectStationId,
        notes: reportReason.trim() || null,
      });

      if (!(data.success === true || data.ok === true)) {
        throw new Error(data.message ?? data.error ?? "Failed to report error");
      }

      return data;
    },
    onSuccess: () => {
      toast.success("Wrong station reported successfully");
      closeReportModal();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const markUnavailableMut = useMutation({
    mutationFn: async () => {
      const { data } = await client.post("/queue/call-next-mark-unavailable", {
        station_id: 1,
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

  const sendPatientMut = useMutation({
    mutationFn: async (station: string) => {
      if (!currentlyServing) {
        throw new Error(
          "Please call a patient first before sending to next station",
        );
      }

      const payload: { queue_id: number; target_station_id?: number } = {
        queue_id: currentlyServing.id,
      };
      if (station !== "discharge") {
        payload.target_station_id = Number(station);
      }

      const { data } = await client.post("/queue/complete-service", payload);
      if (!(data.success === true || data.ok === true)) {
        throw new Error(data.message ?? data.error ?? "Failed to send patient");
      }
      return { data, station };
    },
    onSuccess: ({ station }) => {
      toast.success(
        station === "discharge"
          ? "Patient discharged successfully"
          : "Patient sent to next station",
      );
      setSelectedStation("");
      sendModal.hide();
      refetchQueue();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const openSendModal = () => {
    if (!currentlyServing) {
      toast.error("Please call a patient first before sending to next station");
      return;
    }
    sendModal.show();
  };

  const closeSendModal = () => {
    setSelectedStation("");
    sendModal.hide();
  };

  const openReportModal = () => {
    setReportStep(1);
    setTransferSearch("");
    setReportReason("");
    setSelectedTransfer(null);
    setSelectedCorrectStationId(null);
    reportModal.show();
    void refetchRecentTransfers();
  };

  const closeReportModal = () => {
    setReportStep(1);
    setTransferSearch("");
    setReportReason("");
    setSelectedTransfer(null);
    setSelectedCorrectStationId(null);
    reportModal.hide();
  };

  const selectedWrongStationId =
    selectedTransfer?.current_station_id ??
    selectedTransfer?.to_station_id ??
    null;

  const correctionStations = stations.filter(
    (s) => s.id !== 1 && s.id !== selectedWrongStationId,
  );

  const selectTransferForCorrection = (transfer: RecentTransfer) => {
    if (transfer.current_status === "completed") return;
    setSelectedTransfer(transfer);
    setSelectedCorrectStationId(null);
    setReportReason("");
    setReportStep(2);
  };

  const openDisplayScreen = () => {
    window.open("/queue-display/opd", "_blank", "noopener,noreferrer");
  };

  /* ── nursing assessment form ── */
  const assessMut = useMutation({
    mutationFn: async (payload: Record<string, unknown>) => {
      const { data } = await client.post("/opd/save_assessment.php", payload);
      if (!data.ok) throw new Error(data.error ?? "Failed");
      return data;
    },
    onSuccess: () => toast.success("Assessment saved"),
    onError: (e: Error) => toast.error(e.message),
  });

  const handleAssessSubmit = (e: FormEvent) => {
    e.preventDefault();
    const fd = new FormData(e.target as HTMLFormElement);
    const payload: Record<string, unknown> = {};
    fd.forEach((v, k) => {
      payload[k] = v;
    });
    assessMut.mutate(payload);
  };

  /* ── consultation form ── */
  const consultMut = useMutation({
    mutationFn: async (payload: Record<string, unknown>) => {
      const { data } = await client.post("/opd/save_consultation.php", payload);
      if (!data.ok) throw new Error(data.error ?? "Failed");
      return data;
    },
    onSuccess: () => toast.success("Consultation note saved"),
    onError: (e: Error) => toast.error(e.message),
  });

  const handleConsultSubmit = (e: FormEvent) => {
    e.preventDefault();
    const fd = new FormData(e.target as HTMLFormElement);
    const payload: Record<string, unknown> = {};
    fd.forEach((v, k) => {
      payload[k] = v;
    });
    consultMut.mutate(payload);
  };

  /* ── billing form ── */
  const billingMut = useMutation({
    mutationFn: async (payload: Record<string, unknown>) => {
      const { data } = await client.post("/opd/add_billing_item.php", payload);
      if (!data.ok) throw new Error(data.error ?? "Failed");
      return data;
    },
    onSuccess: () => toast.success("Billing item added"),
    onError: (e: Error) => toast.error(e.message),
  });

  const handleBillingSubmit = (e: FormEvent) => {
    e.preventDefault();
    const fd = new FormData(e.target as HTMLFormElement);
    const payload: Record<string, unknown> = {};
    fd.forEach((v, k) => {
      payload[k] = v;
    });
    billingMut.mutate(payload);
  };

  /* ── new lab request form ── */
  const newLabMut = useMutation({
    mutationFn: async (payload: Record<string, unknown>) => {
      const { data } = await client.post(
        "/opd/create_lab_request.php",
        payload,
      );
      if (!data.ok) throw new Error(data.error ?? "Failed");
      return data;
    },
    onSuccess: () => {
      toast.success("Lab request created");
      refetchLabReqs();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const handleLabReqSubmit = (e: FormEvent) => {
    e.preventDefault();
    const fd = new FormData(e.target as HTMLFormElement);
    const payload: Record<string, unknown> = {};
    fd.forEach((v, k) => {
      payload[k] = v;
    });
    newLabMut.mutate(payload);
  };

  return (
    <div>
      {/* Header */}
      <div className="flex justify-between items-center mb-6 px-6 pt-6">
        <h1 className="text-2xl font-bold text-gray-900">
          Out-Patient Department
        </h1>
      </div>

      {/* Tab navigation — scrollable */}
      <div className="bg-white border-b border-gray-200 px-6 overflow-x-auto">
        <nav className="flex space-x-1 -mb-px min-w-max">
          {TABS.map((t) => (
            <button
              key={t.key}
              onClick={() => setTab(t.key)}
              className={`px-4 py-3 text-xs font-semibold rounded-t-lg border-b-2 transition-colors flex items-center gap-1.5 whitespace-nowrap ${
                tab === t.key
                  ? "border-blue-600 text-blue-600 bg-blue-50"
                  : "border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50"
              }`}
            >
              {t.icon}
              {t.label}
            </button>
          ))}
        </nav>
      </div>

      <div className="p-6">
        {/* ═══ OVERVIEW ═══ */}
        {tab === "overview" && (
          <div>
            {/* Stats */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
              <StatCard
                icon={CalendarCheck}
                label="Today's Appointments"
                value={stats.today_appointments}
                iconBg="bg-blue-100"
                iconColor="text-blue-600"
              />
              <StatCard
                icon={Users}
                label="Patients Waiting"
                value={waitingCount}
                iconBg="bg-yellow-100"
                iconColor="text-yellow-600"
              />
              <StatCard
                icon={CheckCircle}
                label="Consultations Done"
                value={stats.completed}
                iconBg="bg-green-100"
                iconColor="text-green-600"
              />
              <StatCard
                icon={Clock}
                label="Avg. Wait Time"
                value={avgWaitTime}
                iconBg="bg-purple-100"
                iconColor="text-purple-600"
              />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Appointments Table */}
              <div className="bg-white rounded-lg shadow-sm overflow-hidden">
                <div className="p-6 border-b border-gray-100">
                  <h3 className="text-lg font-semibold text-gray-900">
                    Today's Appointments
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
                          Doctor
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
                      {appointments.length === 0 ? (
                        <tr>
                          <td
                            colSpan={4}
                            className="px-6 py-8 text-center text-sm text-gray-500"
                          >
                            No appointments today
                          </td>
                        </tr>
                      ) : (
                        appointments.slice(0, 10).map((a) => (
                          <tr key={a.id}>
                            <td className="px-6 py-4 text-sm font-medium text-gray-900">
                              {a.patient_name}
                            </td>
                            <td className="px-6 py-4 text-sm text-gray-600">
                              {a.doctor_name}
                            </td>
                            <td className="px-6 py-4 text-sm text-gray-600">
                              {a.time}
                            </td>
                            <td className="px-6 py-4">
                              <StatusBadge status={a.status} />
                            </td>
                          </tr>
                        ))
                      )}
                    </tbody>
                  </table>
                </div>
              </div>

              {/* OPD Queue */}
              <div className="bg-white rounded-lg shadow-sm p-6">
                <div className="flex justify-between items-center mb-6">
                  <h3 className="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <Users className="w-5 h-5 text-blue-600" /> OPD Queue
                  </h3>
                  <div className="flex gap-2">
                    <button
                      onClick={() => callNextMut.mutate()}
                      disabled={callNextMut.isPending || !!currentlyServing}
                      className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2 text-sm font-semibold disabled:opacity-50"
                    >
                      <Bell className="w-4 h-4" /> Call Next
                    </button>
                    <button
                      onClick={openReportModal}
                      className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2 text-sm font-semibold"
                    >
                      <AlertTriangle className="w-4 h-4" /> Report Wrong Station
                    </button>
                  </div>
                </div>

                {/* Currently Serving */}
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
                      <div className="flex items-center">
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
                          className="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 text-sm font-semibold flex items-center gap-2"
                        >
                          <UserXIcon className="w-4 h-4" /> Mark Unavailable
                        </button>
                        <button
                          onClick={openSendModal}
                          className="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-semibold flex items-center gap-2"
                        >
                          <Send className="w-4 h-4" /> Send to Next Station
                        </button>
                      </div>
                    </div>
                  ) : (
                    <div className="text-center py-3 text-gray-500">
                      No patient being served
                    </div>
                  )}
                </div>

                {/* Waiting Queue */}
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
                            <span className="text-sm font-medium text-gray-800">
                              {item.full_name ?? item.patient_name}
                            </span>
                            <span className="text-xs text-gray-500">
                              {item.patient_code ?? ""}
                            </span>
                          </div>
                        </div>
                      ))}
                    </div>
                  )}
                </div>

                {/* Unavailable */}
                {unavailable.length > 0 && (
                  <div className="mb-6">
                    <h4 className="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                      <Clock className="w-4 h-4 text-orange-600" /> Unavailable
                    </h4>
                    <div className="space-y-2">
                      {unavailable.map((item) => (
                        <div
                          key={item.id}
                          onClick={() => recallUnavailableMut.mutate(item.id)}
                          className="flex items-center justify-between p-3 bg-orange-50 rounded-lg cursor-pointer hover:bg-orange-100 transition-colors"
                        >
                          <div className="text-sm font-medium text-gray-800">
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
            </div>

            {/* Charts */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
              <ChartCard title="Patient Volume by Hour">
                <ResponsiveContainer width="100%" height={256}>
                  <BarChart
                    data={
                      volumeData.length
                        ? volumeData
                        : [{ hour: "8AM", count: 0 }]
                    }
                  >
                    <XAxis dataKey="hour" tick={{ fontSize: 12 }} />
                    <YAxis tick={{ fontSize: 12 }} />
                    <Tooltip />
                    <Bar dataKey="count" fill="#3B82F6" />
                  </BarChart>
                </ResponsiveContainer>
              </ChartCard>
              <ChartCard title="Consultation Duration Analysis">
                <div className="h-64 flex items-center justify-center text-gray-400 text-sm">
                  Chart loads with API data
                </div>
              </ChartCard>
            </div>
          </div>
        )}

        {/* ═══ NURSING ASSESSMENT ═══ */}
        {tab === "nursing" && (
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-lg font-semibold text-gray-800">
                  Nursing Assessment
                </h2>
                <p className="text-sm text-gray-600 mt-1">
                  Record vitals and submit for doctor review.
                </p>
              </div>
            </div>
            <form onSubmit={handleAssessSubmit} className="mt-6 space-y-4">
              <div className="bg-white border border-gray-200 rounded-lg p-5">
                <div className="text-sm font-semibold text-gray-800">
                  New Assessment
                </div>
                <div className="mt-4 space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700">
                      Patient
                    </label>
                    <input
                      name="patient_search"
                      type="text"
                      placeholder="Search patient name / ID"
                      className={`mt-2 ${inputCls}`}
                      autoComplete="off"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">
                      Nurse Name
                    </label>
                    <input
                      name="nurse_name"
                      type="text"
                      className={`mt-1 ${inputCls}`}
                      placeholder="Optional"
                    />
                  </div>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                    {[
                      { name: "bp_systolic", label: "BP Systolic", ph: "mmHg" },
                      {
                        name: "bp_diastolic",
                        label: "BP Diastolic",
                        ph: "mmHg",
                      },
                      { name: "heart_rate", label: "Heart Rate", ph: "bpm" },
                      {
                        name: "respiratory_rate",
                        label: "Respiratory Rate",
                        ph: "/min",
                      },
                      { name: "temperature", label: "Temperature", ph: "°C" },
                      { name: "spo2", label: "SpO₂", ph: "%" },
                      { name: "weight", label: "Weight", ph: "kg" },
                      { name: "height", label: "Height", ph: "cm" },
                    ].map((f) => (
                      <div key={f.name}>
                        <label className="block text-sm font-medium text-gray-700">
                          {f.label}
                        </label>
                        <input
                          name={f.name}
                          type="number"
                          step="any"
                          className={`mt-1 ${inputCls}`}
                          placeholder={f.ph}
                        />
                      </div>
                    ))}
                  </div>

                  <div className="border-t border-gray-100 pt-4">
                    <div className="text-sm font-semibold text-gray-800">
                      History of Present Illness
                    </div>
                    <div className="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                      <div>
                        <label className="block text-sm font-medium text-gray-700">
                          When did the problem start?
                        </label>
                        <input
                          name="hpi_start"
                          type="text"
                          className={`mt-1 ${inputCls}`}
                        />
                      </div>
                      <div>
                        <label className="block text-sm font-medium text-gray-700">
                          Duration/Frequency
                        </label>
                        <input
                          name="hpi_duration"
                          type="text"
                          className={`mt-1 ${inputCls}`}
                        />
                      </div>
                      <div>
                        <label className="block text-sm font-medium text-gray-700">
                          Severity
                        </label>
                        <select
                          name="hpi_severity"
                          className={`mt-1 ${inputCls}`}
                        >
                          <option value="">Select</option>
                          <option value="mild">Mild</option>
                          <option value="moderate">Moderate</option>
                          <option value="severe">Severe</option>
                        </select>
                      </div>
                      <div>
                        <label className="block text-sm font-medium text-gray-700">
                          Associated Symptoms
                        </label>
                        <input
                          name="hpi_associated"
                          type="text"
                          className={`mt-1 ${inputCls}`}
                        />
                      </div>
                    </div>
                    <div className="mt-3">
                      <label className="block text-sm font-medium text-gray-700">
                        Aggravating/Relieving factors
                      </label>
                      <textarea
                        name="hpi_factors"
                        rows={3}
                        className={`mt-1 ${inputCls}`}
                      />
                    </div>
                  </div>

                  <div className="border-t border-gray-100 pt-4">
                    <div className="text-sm font-semibold text-gray-800">
                      Past Medical History
                    </div>
                    <div className="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2">
                      {[
                        "Diabetes",
                        "Hypertension",
                        "Asthma",
                        "Heart Disease",
                      ].map((cond) => (
                        <label
                          key={cond}
                          className="flex items-center gap-2 text-sm text-gray-800"
                        >
                          <input
                            name={`pmh_${cond.toLowerCase().replace(/\s/g, "_")}`}
                            type="checkbox"
                            className="h-4 w-4"
                          />{" "}
                          {cond}
                        </label>
                      ))}
                    </div>
                    <div className="mt-3">
                      <label className="block text-sm font-medium text-gray-700">
                        Other
                      </label>
                      <input
                        name="pmh_other"
                        type="text"
                        className={`mt-1 ${inputCls}`}
                      />
                    </div>
                  </div>

                  <div className="border-t border-gray-100 pt-4">
                    <label className="block text-sm font-medium text-gray-700">
                      Surgical History
                    </label>
                    <textarea
                      name="surgical_history"
                      rows={3}
                      className={`mt-1 ${inputCls}`}
                    />
                  </div>
                  <div className="border-t border-gray-100 pt-4">
                    <label className="block text-sm font-medium text-gray-700">
                      Current Medications
                    </label>
                    <textarea
                      name="current_medications"
                      rows={3}
                      className={`mt-1 ${inputCls}`}
                    />
                  </div>
                  <div className="border-t border-gray-100 pt-4">
                    <label className="block text-sm font-medium text-gray-700">
                      Allergies
                    </label>
                    <input
                      name="allergies"
                      type="text"
                      className={`mt-1 ${inputCls}`}
                      placeholder="Enter allergies (leave blank if none)"
                    />
                  </div>
                  <div className="border-t border-gray-100 pt-4">
                    <label className="block text-sm font-medium text-gray-700">
                      Family History
                    </label>
                    <textarea
                      name="family_history"
                      rows={3}
                      className={`mt-1 ${inputCls}`}
                    />
                  </div>
                  <div className="border-t border-gray-100 pt-4">
                    <label className="block text-sm font-medium text-gray-700">
                      Notes
                    </label>
                    <textarea
                      name="notes"
                      rows={4}
                      className={`mt-1 ${inputCls}`}
                      placeholder="Assessment notes..."
                    />
                  </div>
                  <button
                    type="submit"
                    disabled={assessMut.isPending}
                    className="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                  >
                    {assessMut.isPending ? "Saving..." : "Save Assessment"}
                  </button>
                </div>
              </div>
            </form>
          </div>
        )}

        {/* ═══ CONSULTATION NOTES ═══ */}
        {tab === "consultation" && (
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-lg font-semibold text-gray-800">
                  Consultation Notes
                </h2>
                <p className="text-sm text-gray-600 mt-1">
                  Document doctor consultation notes (SOAP format).
                </p>
              </div>
            </div>
            <form onSubmit={handleConsultSubmit} className="mt-6 space-y-4">
              <div className="bg-white border border-gray-200 rounded-lg p-5">
                <div className="text-sm font-semibold text-gray-800">
                  New Note
                </div>
                <div className="mt-4 space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700">
                      Appointment
                    </label>
                    <input
                      name="appointment_search"
                      type="text"
                      placeholder="Search patient name"
                      className={`mt-2 ${inputCls}`}
                      autoComplete="off"
                    />
                  </div>

                  <div className="border border-gray-200 rounded-lg p-4">
                    <div className="text-sm font-semibold text-gray-800">
                      SOAP Format
                    </div>
                    <div className="mt-4 space-y-4">
                      <div>
                        <div className="text-xs font-semibold text-gray-700">
                          S – Subjective
                        </div>
                        <div className="mt-2">
                          <label className="block text-xs text-gray-600">
                            Chief Complaint
                          </label>
                          <input
                            name="soap_chief_complaint"
                            type="text"
                            className={`mt-1 ${inputCls}`}
                          />
                        </div>
                      </div>
                      <div>
                        <div className="text-xs font-semibold text-gray-700">
                          O – Objective
                        </div>
                        <div className="mt-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                          <div>
                            <label className="block text-xs text-gray-600">
                              BP
                            </label>
                            <input
                              name="soap_bp"
                              type="text"
                              className={`mt-1 ${inputCls}`}
                            />
                          </div>
                          <div>
                            <label className="block text-xs text-gray-600">
                              Pulse
                            </label>
                            <input
                              name="soap_pulse"
                              type="text"
                              className={`mt-1 ${inputCls}`}
                            />
                          </div>
                          <div>
                            <label className="block text-xs text-gray-600">
                              Temp
                            </label>
                            <input
                              name="soap_temp"
                              type="text"
                              className={`mt-1 ${inputCls}`}
                            />
                          </div>
                        </div>
                        <div className="mt-3">
                          <label className="block text-xs text-gray-600">
                            Physical Examination Findings
                          </label>
                          <textarea
                            name="soap_exam"
                            rows={3}
                            className={`mt-1 ${inputCls}`}
                          />
                        </div>
                      </div>
                      <div>
                        <div className="text-xs font-semibold text-gray-700">
                          A – Assessment
                        </div>
                        <div className="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                          <div>
                            <label className="block text-xs text-gray-600">
                              Primary Diagnosis
                            </label>
                            <input
                              name="soap_primary_dx"
                              type="text"
                              className={`mt-1 ${inputCls}`}
                            />
                          </div>
                          <div>
                            <label className="block text-xs text-gray-600">
                              Differential Diagnosis
                            </label>
                            <input
                              name="soap_differential_dx"
                              type="text"
                              className={`mt-1 ${inputCls}`}
                            />
                          </div>
                        </div>
                      </div>
                      <div>
                        <div className="text-xs font-semibold text-gray-700">
                          P – Plan
                        </div>
                        <div className="mt-2 space-y-3">
                          <div>
                            <label className="block text-xs text-gray-600">
                              Investigations Ordered
                            </label>
                            <textarea
                              name="soap_investigations"
                              rows={3}
                              className={`mt-1 ${inputCls}`}
                            />
                          </div>
                          <div>
                            <label className="block text-xs text-gray-600">
                              Medications Prescribed
                            </label>
                            <textarea
                              name="soap_medications"
                              rows={3}
                              className={`mt-1 ${inputCls}`}
                            />
                          </div>
                          <div>
                            <label className="block text-xs text-gray-600">
                              Treatment/Advice
                            </label>
                            <textarea
                              name="soap_advice"
                              rows={3}
                              className={`mt-1 ${inputCls}`}
                            />
                          </div>
                          <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                              <label className="block text-xs text-gray-600">
                                Follow-up
                              </label>
                              <input
                                name="soap_followup"
                                type="text"
                                className={`mt-1 ${inputCls}`}
                              />
                            </div>
                            <div>
                              <label className="block text-xs text-gray-600">
                                Doctor's Signature
                              </label>
                              <input
                                name="soap_doctor_signature"
                                type="text"
                                className={`mt-1 ${inputCls}`}
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <button
                    type="submit"
                    disabled={consultMut.isPending}
                    className="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                  >
                    {consultMut.isPending ? "Saving..." : "Save Note"}
                  </button>
                </div>
              </div>
            </form>
          </div>
        )}

        {/* ═══ BILLING ═══ */}
        {tab === "billing" && (
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-lg font-semibold text-gray-800">OPD Billing</h2>
            <p className="text-sm text-gray-600 mt-1">
              Review and create billing items for the OPD visit.
            </p>
            <div className="mt-6">
              <input
                type="text"
                placeholder="Search patient name"
                className={inputCls}
                autoComplete="off"
              />
            </div>
            <div className="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div className="bg-white border border-gray-200 rounded-lg p-5">
                <div className="text-sm font-semibold text-gray-800">
                  Add Billing Item
                </div>
                <form onSubmit={handleBillingSubmit} className="mt-4 space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700">
                      Type
                    </label>
                    <select name="type" className={`mt-1 ${inputCls}`}>
                      <option value="misc">Misc</option>
                      <option value="service">Service</option>
                      <option value="procedure">Procedure</option>
                    </select>
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">
                      Description
                    </label>
                    <input
                      name="description"
                      type="text"
                      className={`mt-1 ${inputCls}`}
                      placeholder="e.g., Dressing change"
                    />
                  </div>
                  <div className="grid grid-cols-2 gap-3">
                    <div>
                      <label className="block text-sm font-medium text-gray-700">
                        Qty
                      </label>
                      <input
                        name="qty"
                        type="number"
                        min={1}
                        defaultValue={1}
                        className={`mt-1 ${inputCls}`}
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700">
                        Unit Price
                      </label>
                      <input
                        name="unit_price"
                        type="number"
                        min={0}
                        step={0.01}
                        defaultValue={0}
                        className={`mt-1 ${inputCls}`}
                      />
                    </div>
                  </div>
                  <button
                    type="submit"
                    disabled={billingMut.isPending}
                    className="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                  >
                    Add Item
                  </button>
                </form>
              </div>
              <div className="bg-white border border-gray-200 rounded-lg p-5 min-h-[300px]">
                <div className="text-sm font-semibold text-gray-800">
                  Summary
                </div>
                <div className="mt-4 text-sm text-gray-600">
                  Select an appointment to view billing summary.
                </div>
              </div>
            </div>
          </div>
        )}

        {/* ═══ APPOINTMENTS ═══ */}
        {tab === "appointments" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-semibold text-gray-900">
                  Appointments
                </h3>
                <p className="text-sm text-gray-600 mt-1">
                  View scheduled OPD appointments.
                </p>
              </div>
              <div className="flex items-center gap-3">
                <SearchInput
                  value={apptSearch}
                  onChange={setApptSearch}
                  placeholder="Search patient name or ID..."
                />
                <button
                  onClick={() => refetchAppts()}
                  className="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 flex items-center gap-2"
                >
                  <RefreshCw className="w-4 h-4" /> Refresh
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
                      Doctor
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
                  {appointments.length === 0 ? (
                    <tr>
                      <td
                        colSpan={4}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No appointments found
                      </td>
                    </tr>
                  ) : (
                    appointments.map((a) => (
                      <tr key={a.id}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {a.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {a.doctor_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {a.time}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={a.status} />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* ═══ APPOINTMENT REQUESTS ═══ */}
        {tab === "requests" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-semibold text-gray-900">
                  Appointment Requests
                </h3>
                <p className="text-sm text-gray-600 mt-1">
                  Doctors approve and schedule OPD appointment requests.
                </p>
              </div>
              <button className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <Plus className="w-4 h-4" /> Send Request
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
                      Requested By
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Date
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  <tr>
                    <td
                      colSpan={5}
                      className="px-6 py-8 text-center text-sm text-gray-500"
                    >
                      No pending requests
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* ═══ NEW LAB REQUEST ═══ */}
        {tab === "newlab" && (
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-lg font-semibold text-gray-800">
              New Lab Request
            </h2>
            <p className="text-sm text-gray-600 mt-1">
              Request laboratory tests for OPD patients.
            </p>
            <form onSubmit={handleLabReqSubmit} className="mt-6 space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Patient
                </label>
                <input
                  name="patient_search"
                  type="text"
                  placeholder="Search patient..."
                  className={`mt-2 ${inputCls}`}
                  autoComplete="off"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Priority
                </label>
                <select name="priority" className={`mt-1 ${inputCls}`}>
                  <option value="routine">Routine</option>
                  <option value="urgent">Urgent</option>
                  <option value="stat">STAT</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Tests Requested
                </label>
                <textarea
                  name="tests"
                  rows={4}
                  className={`mt-1 ${inputCls}`}
                  placeholder="List tests (e.g., CBC, Urinalysis, FBS)"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Clinical Notes
                </label>
                <textarea
                  name="notes"
                  rows={3}
                  className={`mt-1 ${inputCls}`}
                  placeholder="Additional notes for lab..."
                />
              </div>
              <button
                type="submit"
                disabled={newLabMut.isPending}
                className="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
              >
                {newLabMut.isPending ? "Submitting..." : "Submit Lab Request"}
              </button>
            </form>
          </div>
        )}

        {/* ═══ X-RAY ═══ */}
        {tab === "xray" && (
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-lg font-semibold text-gray-800">
              X-Ray Results & Release
            </h2>
            <p className="text-sm text-gray-600 mt-1">
              View and release X-ray results for OPD patients.
            </p>
            <div className="mt-6 text-center py-12 text-gray-400">
              X-Ray results will load from API
            </div>
          </div>
        )}

        {/* ═══ LAB REQUESTS ═══ */}
        {tab === "labrequests" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-semibold text-gray-900">
                  Lab Requests
                </h3>
                <p className="text-sm text-gray-600 mt-1">
                  All OPD lab test requests and their status.
                </p>
              </div>
              <button
                onClick={() => refetchLabReqs()}
                className="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 flex items-center gap-2"
              >
                <RefreshCw className="w-4 h-4" /> Refresh
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
                      Tests
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Priority
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
                  {labRequests.length === 0 ? (
                    <tr>
                      <td
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No lab requests
                      </td>
                    </tr>
                  ) : (
                    labRequests.map((r, i) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {String(r.patient_name ?? "")}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {String(r.tests ?? "")}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge
                            status={String(r.priority ?? "routine")}
                          />
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={String(r.status ?? "pending")} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {String(r.created_at ?? "")}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* ═══ LAB RESULTS ═══ */}
        {tab === "labresults" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100">
              <h3 className="text-lg font-semibold text-gray-900">
                Lab Results
              </h3>
              <p className="text-sm text-gray-600 mt-1">
                View completed lab results for OPD patients.
              </p>
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
                      Result
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Date
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {labResults.length === 0 ? (
                    <tr>
                      <td
                        colSpan={4}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No lab results available
                      </td>
                    </tr>
                  ) : (
                    labResults.map((r, i) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {String(r.patient_name ?? "")}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {String(r.test_name ?? "")}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {String(r.result ?? "")}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {String(r.completed_at ?? "")}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </div>

      {/* ═══ SEND PATIENT MODAL ═══ */}
      <Modal
        open={sendModal.open}
        onClose={closeSendModal}
        title="Send Patient to Next Station"
        maxWidth="max-w-lg"
      >
        <div className="space-y-4">
          <label className="block text-lg font-semibold text-gray-700">
            Select Destination Station:
          </label>
          <div className="space-y-3">
            {stations.map((s) => (
              <label
                key={s.id}
                className={`flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors ${selectedStation === String(s.id) ? "border-blue-600 bg-blue-50" : "border-gray-200 hover:bg-gray-50"}`}
              >
                <input
                  type="radio"
                  name="station"
                  value={String(s.id)}
                  checked={selectedStation === String(s.id)}
                  onChange={() => setSelectedStation(String(s.id))}
                  className="h-4 w-4 text-blue-600"
                />
                <span className="ml-3 font-medium text-gray-800">
                  {s.station_display_name}
                </span>
              </label>
            ))}

            <label
              className={`flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors ${selectedStation === "discharge" ? "border-green-600 bg-green-50" : "border-gray-200 hover:bg-gray-50"}`}
            >
              <input
                type="radio"
                name="station"
                value="discharge"
                checked={selectedStation === "discharge"}
                onChange={() => setSelectedStation("discharge")}
                className="h-4 w-4 text-green-600"
              />
              <span className="ml-3 font-medium text-gray-800">
                Complete and Discharge
              </span>
            </label>
          </div>
        </div>
        <div className="mt-6 flex justify-end gap-4">
          <button
            onClick={closeSendModal}
            className="px-6 py-3 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100"
          >
            Cancel
          </button>
          <button
            onClick={() => sendPatientMut.mutate(selectedStation)}
            disabled={!selectedStation || sendPatientMut.isPending}
            className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 flex items-center gap-2"
          >
            <Send className="w-4 h-4" />{" "}
            {sendPatientMut.isPending ? "Sending..." : "Send Patient"}
          </button>
        </div>
      </Modal>

      {/* ═══ REPORT WRONG STATION MODAL ═══ */}
      <Modal
        open={reportModal.open}
        onClose={closeReportModal}
        title="Report Wrong Station"
        maxWidth="max-w-4xl"
      >
        {reportStep === 1 ? (
          <div className="space-y-4">
            <p className="text-sm font-semibold text-gray-700">
              Select the wrongly transferred patient:
            </p>
            <SearchInput
              value={transferSearch}
              onChange={setTransferSearch}
              placeholder="Search patient name, code, or queue number"
            />

            <div className="max-h-112 overflow-y-auto space-y-3 pr-1">
              {filteredRecentTransfers.length === 0 ? (
                <div className="text-center py-10 text-gray-500 border border-gray-200 rounded-lg">
                  No recent transfers found
                </div>
              ) : (
                filteredRecentTransfers.map((t) => {
                  const isDischarged = t.current_status === "completed";
                  const currentLabel =
                    t.current_station_name &&
                    t.current_station_id &&
                    t.to_station_id &&
                    t.current_station_id !== t.to_station_id
                      ? `Currently at: ${t.current_station_name}`
                      : `Sent to: ${t.to_station_name ?? "-"}`;

                  return (
                    <button
                      key={t.id}
                      type="button"
                      disabled={isDischarged}
                      onClick={() => selectTransferForCorrection(t)}
                      className={`w-full text-left p-4 border-2 rounded-lg transition-colors ${
                        isDischarged
                          ? "border-gray-200 bg-gray-50 opacity-60 cursor-not-allowed"
                          : "border-gray-200 hover:border-red-400 hover:bg-red-50"
                      }`}
                    >
                      <div className="flex items-center justify-between gap-3">
                        <div>
                          <div className="text-base font-bold text-gray-900">
                            {t.full_name ?? "Unknown"}
                          </div>
                          <div className="text-sm text-gray-500">
                            {t.patient_code ?? ""} • Queue #
                            {t.queue_number ?? "?"}
                          </div>
                        </div>
                        <div className="text-right text-sm">
                          <div className="font-semibold text-gray-700">
                            {isDischarged ? "Discharged" : currentLabel}
                          </div>
                          <div className="text-gray-400">
                            {t.transferred_at
                              ? new Date(t.transferred_at).toLocaleTimeString(
                                  [],
                                  {
                                    hour: "2-digit",
                                    minute: "2-digit",
                                  },
                                )
                              : ""}
                          </div>
                        </div>
                      </div>
                    </button>
                  );
                })
              )}
            </div>
          </div>
        ) : (
          <div className="space-y-5">
            <div className="rounded-lg border border-red-200 bg-red-50 p-4">
              <div className="font-bold text-red-800">
                {selectedTransfer?.full_name ?? "Unknown Patient"}
              </div>
              <div className="text-sm text-red-700">
                {selectedTransfer?.current_station_name
                  ? `Currently at: ${selectedTransfer.current_station_name}`
                  : `Was sent to: ${selectedTransfer?.to_station_name ?? "-"}`}
              </div>
            </div>

            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-2">
                Reason for correction
              </label>
              <textarea
                rows={3}
                value={reportReason}
                onChange={(e) => setReportReason(e.target.value)}
                placeholder="Explain why this patient needs to be moved..."
                className="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-red-400"
              />
            </div>

            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-2">
                Select the correct station
              </label>
              <div className="max-h-64 overflow-y-auto space-y-2 pr-1">
                {correctionStations.map((s) => (
                  <button
                    key={s.id}
                    type="button"
                    onClick={() => setSelectedCorrectStationId(s.id)}
                    className={`w-full text-left p-3 border-2 rounded-lg transition-colors ${
                      selectedCorrectStationId === s.id
                        ? "border-blue-500 bg-blue-50"
                        : "border-gray-200 hover:bg-gray-50"
                    }`}
                  >
                    <div className="font-semibold text-gray-900">
                      {s.station_display_name}
                    </div>
                  </button>
                ))}
              </div>
            </div>
          </div>
        )}

        <div className="mt-6 flex items-center justify-between gap-3">
          <button
            type="button"
            onClick={
              reportStep === 1 ? closeReportModal : () => setReportStep(1)
            }
            className="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100"
          >
            {reportStep === 1 ? "Cancel" : "Back"}
          </button>

          {reportStep === 2 && (
            <button
              type="button"
              onClick={() => reportWrongStationMut.mutate()}
              disabled={
                !selectedCorrectStationId || reportWrongStationMut.isPending
              }
              className="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
            >
              {reportWrongStationMut.isPending
                ? "Reporting..."
                : "Report Error"}
            </button>
          )}
        </div>
      </Modal>
    </div>
  );
}
