import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import {
  Users,
  Bell,
  AlertTriangle,
  Tv,
  Clock,
  ClipboardList,
  Search,
  RefreshCw,
  FileText,
  UserXIcon,
} from "lucide-react";
import { toast } from "sonner";
import { useHashTab } from "@/hooks/useHashTab";
import { useModal } from "@/hooks/useModal";
import { useDebounce } from "@/hooks/useDebounce";
import { StatusBadge } from "@/components/ui/StatusBadge";
import { SendPatientModal } from "@/components/queue/SendPatientModal";
import { ReportWrongStationModal } from "@/components/queue/ReportWrongStationModal";
import { IncomingCorrectionAlert } from "@/components/queue/IncomingCorrectionAlert";
import { useQueueWebSocket } from "@/hooks/useQueueWebSocket";
import client from "@/api/client";

type DoctorTab = "queue" | "findings";

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
interface FindingsRow {
  id: number;
  patient_name: string;
  patient_code: string;
  patient_type: string;
  lab_tests: number;
  assessments: number;
}

export default function Doctor() {
  const [tab, setTab] = useHashTab<DoctorTab>("queue");
  const sendModal = useModal();
  const reportModal = useModal();
  const [findingsSearch, setFindingsSearch] = useState("");
  const dFindingsSearch = useDebounce(findingsSearch, 250);

  /* ── data ── */
  const { data: queueData, refetch: refetchQueue } = useQuery({
    queryKey: ["queue-display", 2],
    queryFn: async () => {
      const { data } = await client.get("/queue/display/2");
      return data;
    },
    enabled: tab === "queue",
  });

  useQueueWebSocket({ stationId: 2, enabled: tab === "queue" });

  const { data: findingsData, refetch: refetchFindings } = useQuery({
    queryKey: ["doctor-findings", dFindingsSearch],
    queryFn: async () => {
      const q = dFindingsSearch
        ? `?q=${encodeURIComponent(dFindingsSearch)}`
        : "";
      const { data } = await client.get(`/doctor/findings.php${q}`);
      return data;
    },
    enabled: tab === "findings",
  });

  const queueDisplay: DisplayQueueData = (queueData ?? {}) as DisplayQueueData;
  const currentlyServing = queueDisplay.currently_serving ?? null;
  const waitingQueue = queueDisplay.next_patients ?? [];
  const unavailable = queueDisplay.unavailable_patients ?? [];
  const findings: FindingsRow[] = findingsData?.ok
    ? (findingsData.patients ?? [])
    : [];

  /* ── mutations ── */
  const callNextMut = useMutation({
    mutationFn: async () => {
      if (currentlyServing) {
        throw new Error(
          "Please complete the current patient service before calling the next patient",
        );
      }

      const { data } = await client.post("/queue/call-next", {
        station_id: 2,
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
        station_id: 2,
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
    window.open("/queue-display/doctor", "_blank", "noopener,noreferrer");
  };

  return (
    <div>
      {/* Header */}
      <header className="bg-white shadow-sm border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-16">
            <h1 className="text-2xl font-semibold text-gray-900">
              Doctor Portal
            </h1>
          </div>
        </div>
      </header>

      {/* Tabs */}
      <div className="bg-white border-b border-gray-200 px-6">
        <nav className="flex space-x-1 -mb-px">
          <button
            onClick={() => setTab("queue")}
            className={`px-5 py-3 text-sm font-semibold rounded-t-lg border-b-2 transition-colors flex items-center gap-2 ${
              tab === "queue"
                ? "border-blue-600 text-blue-600 bg-blue-50"
                : "border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50"
            }`}
          >
            <Users className="w-4 h-4" /> Queue
          </button>
          <button
            onClick={() => setTab("findings")}
            className={`px-5 py-3 text-sm font-semibold rounded-t-lg border-b-2 transition-colors flex items-center gap-2 ${
              tab === "findings"
                ? "border-blue-600 text-blue-600 bg-blue-50"
                : "border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50"
            }`}
          >
            <FileText className="w-4 h-4" /> Patient's Findings
          </button>
        </nav>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {/* ═══ QUEUE ═══ */}
        {tab === "queue" && (
          <section className="bg-white rounded-lg shadow-sm p-6">
            <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
              <h3 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <Users className="w-6 h-6 text-blue-600" /> Doctor's Queue
              </h3>
              <div className="flex flex-wrap gap-2">
                <button
                  onClick={() => callNextMut.mutate()}
                  disabled={callNextMut.isPending || !!currentlyServing}
                  className="px-4 py-3 bg-blue-600 text-white rounded-lg text-lg font-semibold hover:bg-blue-700 flex items-center gap-2 disabled:opacity-50"
                >
                  <Bell className="w-5 h-5" /> Call Next Patient
                </button>
                <button
                  onClick={() => reportModal.show()}
                  className="px-4 py-3 bg-red-600 text-white rounded-lg text-lg font-semibold hover:bg-red-700 flex items-center gap-2"
                >
                  <AlertTriangle className="w-5 h-5" /> Report Wrong Station
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
                      className="px-4 py-3 bg-orange-600 text-white rounded-lg text-lg font-semibold hover:bg-orange-700 flex items-center gap-2 disabled:opacity-50"
                    >
                      <UserXIcon className="w-4 h-4" /> Mark Unavailable
                    </button>
                    <button
                      onClick={() => sendModal.show()}
                      className="px-4 py-3 bg-green-600 text-white rounded-lg text-lg font-semibold hover:bg-green-700 flex items-center gap-2"
                    >
                      Send to Next Station
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
                <div className="text-center py-8 text-gray-400">
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

            {/* Unavailable */}
            {unavailable.length > 0 && (
              <div className="mb-6">
                <h4 className="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                  <Clock className="w-4 h-4 text-orange-600" /> Unavailable
                  Patients
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
          </section>
        )}

        {/* ═══ FINDINGS ═══ */}
        {tab === "findings" && (
          <section className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
              <div>
                <h3 className="text-lg font-semibold text-gray-900 flex items-center gap-2">
                  <FileText className="w-5 h-5 text-blue-600" /> Patient's
                  Findings
                </h3>
                <p className="text-sm text-gray-600 mt-1">
                  View lab tests, nurse assessments, and x-ray results for each
                  patient.
                </p>
              </div>
              <div className="flex items-center gap-3">
                <div className="relative">
                  <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                  <input
                    value={findingsSearch}
                    onChange={(e) => setFindingsSearch(e.target.value)}
                    type="text"
                    className="pl-9 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Search patient name / code"
                  />
                </div>
                <button
                  onClick={() => refetchFindings()}
                  className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
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
                      Patient ID
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Type
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Lab Tests
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Assessments
                    </th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {findings.length === 0 ? (
                    <tr>
                      <td
                        colSpan={6}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No patients found
                      </td>
                    </tr>
                  ) : (
                    findings.map((p) => (
                      <tr key={p.id}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {p.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {p.patient_code}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={p.patient_type} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {p.lab_tests}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {p.assessments}
                        </td>
                        <td className="px-6 py-4 text-right">
                          <button className="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700">
                            View All
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </section>
        )}
      </div>

      {/* ═══ MODALS ═══ */}
      <SendPatientModal
        open={sendModal.open}
        onClose={sendModal.hide}
        currentlyServing={currentlyServing}
        currentStationId={2}
        onSuccess={refetchQueue}
      />

      <ReportWrongStationModal
        open={reportModal.open}
        onClose={reportModal.hide}
        stationId={2}
        onSuccess={refetchQueue}
      />

      <IncomingCorrectionAlert stationId={2} onCorrection={refetchQueue} />
    </div>
  );
}
