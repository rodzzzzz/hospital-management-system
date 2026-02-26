import {
  useState,
  useEffect,
  useCallback,
  useRef,
  type FormEvent,
} from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import {
  LineChart,
  Line,
  XAxis,
  YAxis,
  Tooltip,
  ResponsiveContainer,
  Legend,
  PieChart,
  Pie,
  Cell,
} from "recharts";
import {
  Users,
  Stethoscope,
  Clock,
  Bed,
  CheckCircle,
  Plus,
  User,
  RefreshCw,
} from "lucide-react";
import { toast } from "sonner";
import { useHashTab } from "@/hooks/useHashTab";
import { useDebounce } from "@/hooks/useDebounce";
import { useModal } from "@/hooks/useModal";
import { Modal } from "@/components/ui/Modal";
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { progressChip } from "@/components/ui/StatusBadge";
import client from "@/api/client";

/* ── types ── */
interface PatientRow {
  id: number;
  full_name: string;
  dob: string;
  patient_code?: string;
  initial_location?: string;
  department?: string;
  done_process?: string;
  next_procedure?: string;
  progress_status?: string;
  progress_time?: string;
  updated_at?: string;
}

interface QueueRow {
  id: number;
  created_at: string;
  payload: Record<string, string>;
}

interface StatsCards {
  total_patients: number;
  in_treatment: number;
  waiting: number;
  surgeries: number;
  discharged: number;
}

interface FlowChart {
  labels: string[];
  series: { emergency: number[]; or: number[]; pharmacy: number[] };
}

/* ── helpers ── */
function calcAge(dobStr: string): string {
  const s = (dobStr ?? "").trim();
  if (!/^\d{4}-\d{2}-\d{2}$/.test(s)) return "";
  const [y, m, d] = s.split("-").map(Number);
  if (!y || !m || !d) return "";
  const today = new Date();
  let age = today.getFullYear() - y;
  if (today < new Date(today.getFullYear(), m - 1, d)) age -= 1;
  return age < 0 ? "" : String(age);
}

const DEPT_COLORS = [
  "#EF4444",
  "#9333EA",
  "#3B82F6",
  "#22C55E",
  "#EAB308",
  "#6B7280",
];

const emptyForm = {
  id: "",
  full_name: "",
  dob: "",
  sex: "",
  blood_type: "",
  civil_status: "",
  contact: "",
  diagnosis: "",
  street_address: "",
  barangay: "",
  city: "",
  province: "",
  zip_code: "",
  emergency_contact_name: "",
  emergency_contact_relationship: "",
  emergency_contact_phone: "",
};

export default function PatientList() {
  const qc = useQueryClient();
  const [tab, setTab] = useHashTab<"dashboard" | "progress" | "queue">(
    "dashboard",
  );
  const [search, setSearch] = useState("");
  const debouncedSearch = useDebounce(search, 250);
  const patientModal = useModal();
  const confirmModal = useModal();
  const [form, setForm] = useState({ ...emptyForm });
  const [modalTitle, setModalTitle] = useState("Add New Patient");
  const [submitLabel, setSubmitLabel] = useState("Add Patient");
  const [editingQueueId, setEditingQueueId] = useState<number | null>(null);
  const [pendingConfirmId, setPendingConfirmId] = useState<number | null>(null);
  const pollRef = useRef<ReturnType<typeof setInterval> | null>(null);

  /* ── data fetching ── */
  const { data: statsData } = useQuery({
    queryKey: ["patient-stats"],
    queryFn: async () => {
      const { data } = await client.get("/patients/stats.php");
      return data;
    },
    enabled: tab === "dashboard",
    refetchInterval: false,
  });

  const { data: patientsData, refetch: refetchPatients } = useQuery({
    queryKey: ["patients-list", debouncedSearch],
    queryFn: async () => {
      const url = debouncedSearch
        ? `/patients/list.php?q=${encodeURIComponent(debouncedSearch)}`
        : "/patients/list.php";
      const { data } = await client.get(url);
      return data;
    },
    enabled: tab === "progress",
  });

  const { data: queueData, refetch: refetchQueue } = useQuery({
    queryKey: ["patient-queue"],
    queryFn: async () => {
      const { data } = await client.get("/queue/list.php?status=queued");
      return data;
    },
    enabled: tab === "queue",
  });

  // polling for progress view (8s like PHP)
  useEffect(() => {
    if (tab === "progress") {
      pollRef.current = setInterval(() => refetchPatients(), 8000);
    }
    return () => {
      if (pollRef.current) clearInterval(pollRef.current);
      pollRef.current = null;
    };
  }, [tab, refetchPatients]);

  const cards: StatsCards = statsData?.cards ?? {
    total_patients: 0,
    in_treatment: 0,
    waiting: 0,
    surgeries: 0,
    discharged: 0,
  };
  const flow: FlowChart = statsData?.charts?.flow ?? {
    labels: [],
    series: { emergency: [], or: [], pharmacy: [] },
  };
  const deptRaw: Record<string, number> = statsData?.charts?.department ?? {};
  const patients: PatientRow[] = patientsData?.ok
    ? (patientsData.patients ?? [])
    : [];
  const queueRows: QueueRow[] = queueData?.ok ? (queueData.queue ?? []) : [];

  const flowData = flow.labels.map((label: string, i: number) => ({
    month: label,
    emergency: flow.series.emergency?.[i] ?? 0,
    or: flow.series.or?.[i] ?? 0,
    pharmacy: flow.series.pharmacy?.[i] ?? 0,
  }));

  const deptData = Object.entries(deptRaw).map(([name, value]) => ({
    name: name.charAt(0).toUpperCase() + name.slice(1),
    value,
  }));

  const waitingPct =
    cards.total_patients > 0
      ? Math.round((cards.waiting / cards.total_patients) * 100)
      : 0;

  /* ── mutations ── */
  const saveMutation = useMutation({
    mutationFn: async (payload: Record<string, unknown>) => {
      const isEdit = Boolean(payload.id);
      const url = isEdit ? "/patients/update.php" : "/patients/create.php";
      const { data } = await client.post(url, payload);
      if (!data.ok) throw new Error(data.error ?? "Save failed");
      return data;
    },
    onSuccess: () => {
      patientModal.hide();
      toast.success(
        form.id
          ? "Patient updated successfully."
          : "Patient added successfully.",
      );
      qc.invalidateQueries({ queryKey: ["patients-list"] });
      qc.invalidateQueries({ queryKey: ["patient-stats"] });
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const queueUpdateMutation = useMutation({
    mutationFn: async (payload: {
      queue_id: number;
      payload: Record<string, string>;
    }) => {
      const { data } = await client.post("/queue/update.php", payload);
      if (!data.ok)
        throw new Error(data.error ?? "Failed to update queue item");
      return data;
    },
    onSuccess: () => {
      patientModal.hide();
      setEditingQueueId(null);
      toast.success("Queue item updated successfully.");
      refetchQueue();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const confirmMutation = useMutation({
    mutationFn: async (queueId: number) => {
      const { data } = await client.post("/queue/confirm.php", {
        queue_id: queueId,
      });
      if (!data.ok) throw new Error(data.error ?? "Failed to confirm patient");
      return data;
    },
    onSuccess: () => {
      confirmModal.hide();
      setPendingConfirmId(null);
      toast.success("Patient confirmed and moved to progress.");
      refetchQueue();
      refetchPatients();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const autoFillMutation = useMutation({
    mutationFn: async () => {
      const { data } = await client.get("/patients/autofill.php");
      if (!data.ok || !data.patient)
        throw new Error(data.error ?? "Failed to autofill");
      return data.patient;
    },
    onSuccess: (p: Record<string, string>) => {
      setForm((prev) => ({
        ...prev,
        full_name: p.full_name ?? "",
        dob: p.dob ?? "",
        sex: p.sex ?? "",
        blood_type: p.blood_type ?? "",
        civil_status: p.civil_status ?? "",
        contact: p.contact ?? "",
        diagnosis: p.diagnosis ?? "",
        street_address: p.street_address ?? "",
        barangay: p.barangay ?? "",
        city: p.city ?? "",
        province: p.province ?? "",
        zip_code: p.zip_code ?? "",
        emergency_contact_name: p.emergency_contact_name ?? "",
        emergency_contact_relationship: p.emergency_contact_relationship ?? "",
        emergency_contact_phone: p.emergency_contact_phone ?? "",
      }));
    },
    onError: (e: Error) => toast.error(e.message),
  });

  /* ── actions ── */
  const openAddNew = useCallback(() => {
    setForm({ ...emptyForm });
    setEditingQueueId(null);
    setModalTitle("Add New Patient");
    setSubmitLabel("Add Patient");
    patientModal.show();
  }, [patientModal]);

  const openEditQueue = useCallback(
    async (queueId: number) => {
      const item = queueRows.find((r) => r.id === queueId);
      if (!item?.payload) return;
      const p = item.payload;
      setEditingQueueId(queueId);
      setModalTitle("Edit Queue Patient");
      setSubmitLabel("Save Queue Changes");
      setForm({
        id: "",
        full_name: p.full_name ?? "",
        dob: p.dob ?? "",
        sex: p.sex ?? "",
        blood_type: p.blood_type ?? "",
        civil_status: p.civil_status ?? "",
        contact: p.contact ?? "",
        diagnosis: p.diagnosis ?? "",
        street_address: p.street_address ?? "",
        barangay: p.barangay ?? "",
        city: p.city ?? "",
        province: p.province ?? "",
        zip_code: p.zip_code ?? "",
        emergency_contact_name: p.emergency_contact_name ?? "",
        emergency_contact_relationship: p.emergency_contact_relationship ?? "",
        emergency_contact_phone: p.emergency_contact_phone ?? "",
      });
      patientModal.show();
    },
    [queueRows, patientModal],
  );

  const handleSubmit = useCallback(
    (e: FormEvent) => {
      e.preventDefault();
      if (editingQueueId !== null && !form.id) {
        const { id: _id, ...rest } = form;
        queueUpdateMutation.mutate({
          queue_id: editingQueueId,
          payload: { ...rest, initial_location: "OPD" },
        });
        return;
      }
      const payload: Record<string, unknown> = {
        ...form,
        initial_location: "OPD",
      };
      if (form.id) payload.id = Number(form.id);
      else delete payload.id;
      saveMutation.mutate(payload);
    },
    [form, editingQueueId, saveMutation, queueUpdateMutation],
  );

  const setField = (key: string, value: string) =>
    setForm((prev) => ({ ...prev, [key]: value }));

  const inputCls =
    "w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none";
  const selectCls = inputCls;

  /* ── render ── */
  return (
    <div>
      {/* Header */}
      <div className="bg-white p-6 flex items-center justify-between mb-0">
        <h1 className="text-2xl font-semibold">Patient Monitoring</h1>
        <div className="flex items-center space-x-3">
          <button
            onClick={openAddNew}
            className="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700"
          >
            <Plus className="w-4 h-4" />
            <span>Add Patient</span>
          </button>
        </div>
      </div>

      {/* Tab buttons */}
      <div className="bg-white border-b border-gray-200 px-6">
        <nav className="flex space-x-1 -mb-px">
          {(["dashboard", "progress", "queue"] as const).map((t) => (
            <button
              key={t}
              onClick={() => setTab(t)}
              className={`px-5 py-3 text-sm font-semibold rounded-t-lg border-b-2 transition-colors ${
                tab === t
                  ? "border-blue-600 text-blue-600 bg-blue-50"
                  : "border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50"
              }`}
            >
              {t === "dashboard"
                ? "Dashboard"
                : t === "progress"
                  ? "Progress"
                  : "Queue"}
            </button>
          ))}
        </nav>
      </div>

      <div className="p-6">
        {/* ═══ DASHBOARD VIEW ═══ */}
        {tab === "dashboard" && (
          <div>
            {/* 5 stat cards */}
            <div className="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
              <StatCardFancy
                icon={<Users className="w-6 h-6 text-white" />}
                value={cards.total_patients}
                unit="patients"
                label="Total Patients"
                badge="+12%"
                gradient="from-blue-500 to-blue-600"
              />
              <StatCardFancy
                icon={<Stethoscope className="w-6 h-6 text-white" />}
                value={cards.in_treatment}
                unit="patients"
                label="In Treatment"
                badge="Active"
                gradient="from-emerald-500 to-emerald-600"
              />
              <StatCardFancy
                icon={<Clock className="w-6 h-6 text-white" />}
                value={cards.waiting}
                unit="patients"
                label="Waiting"
                badge=""
                gradient="from-amber-500 to-amber-600"
                waitingBar={waitingPct}
              />
              <StatCardFancy
                icon={<Bed className="w-6 h-6 text-white" />}
                value={cards.surgeries}
                unit="surgeries"
                label="Operating Room"
                badge="In Progress"
                gradient="from-purple-500 to-purple-600"
              />
              <StatCardFancy
                icon={<CheckCircle className="w-6 h-6 text-white" />}
                value={cards.discharged}
                unit="discharged"
                label="Discharged"
                badge="Today"
                gradient="from-teal-500 to-teal-600"
              />
            </div>

            {/* Charts */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div className="bg-white p-6 rounded-lg shadow">
                <h3 className="text-lg font-semibold mb-4">Patient Flow</h3>
                <ResponsiveContainer width="100%" height={300}>
                  <LineChart
                    data={
                      flowData.length
                        ? flowData
                        : [{ month: "-", emergency: 0, or: 0, pharmacy: 0 }]
                    }
                  >
                    <XAxis dataKey="month" tick={{ fontSize: 12 }} />
                    <YAxis tick={{ fontSize: 12 }} />
                    <Tooltip />
                    <Legend />
                    <Line
                      type="monotone"
                      dataKey="emergency"
                      name="Emergency Room"
                      stroke="#3B82F6"
                      strokeWidth={2}
                      dot={false}
                    />
                    <Line
                      type="monotone"
                      dataKey="or"
                      name="Operating Room"
                      stroke="#9333EA"
                      strokeWidth={2}
                      dot={false}
                    />
                    <Line
                      type="monotone"
                      dataKey="pharmacy"
                      name="Pharmacy"
                      stroke="#22C55E"
                      strokeWidth={2}
                      dot={false}
                    />
                  </LineChart>
                </ResponsiveContainer>
              </div>

              <div className="bg-white p-6 rounded-lg shadow">
                <h3 className="text-lg font-semibold mb-4">
                  Department Distribution
                </h3>
                <ResponsiveContainer width="100%" height={300}>
                  <PieChart>
                    <Pie
                      data={
                        deptData.length
                          ? deptData
                          : [{ name: "No data", value: 1 }]
                      }
                      cx="50%"
                      cy="50%"
                      innerRadius={50}
                      outerRadius={100}
                      dataKey="value"
                      label
                    >
                      {(deptData.length
                        ? deptData
                        : [{ name: "No data", value: 1 }]
                      ).map((_entry, idx) => (
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
              </div>
            </div>
          </div>
        )}

        {/* ═══ PROGRESS VIEW ═══ */}
        {tab === "progress" && (
          <div className="bg-white rounded-lg shadow overflow-hidden">
            <div className="p-6 border-b border-gray-100">
              <div className="flex justify-between items-center">
                <h3 className="text-lg font-semibold">Current Patients</h3>
                <input
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  placeholder="Search patients..."
                  className="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
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
                      ID
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Current Location
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Done Process
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Next Procedure
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Time
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {patients.length === 0 ? (
                    <tr>
                      <td
                        colSpan={7}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No patients found
                      </td>
                    </tr>
                  ) : (
                    patients.map((p) => {
                      const age = p.dob ? calcAge(p.dob) : null;
                      const chip = progressChip(p.progress_status ?? "");
                      const doneClean = (p.done_process ?? "-")
                        .split("•")
                        .map((s) => s.trim().replace(/^done\s+/i, ""))
                        .join(" • ");
                      return (
                        <tr key={p.id}>
                          <td className="px-6 py-4 whitespace-nowrap">
                            <div className="flex items-center">
                              <div className="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <User className="w-4 h-4 text-gray-500" />
                              </div>
                              <div className="ml-4">
                                <div className="text-sm font-medium text-gray-900">
                                  {p.full_name}
                                </div>
                                <div className="text-sm text-gray-500">
                                  {age !== null ? `${age} years` : ""}
                                </div>
                              </div>
                            </div>
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {p.patient_code ?? `P-${p.id}`}
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap">
                            <div className="text-sm text-gray-900">
                              {p.initial_location ?? ""}
                            </div>
                            <div className="text-xs text-gray-500">
                              {p.department ?? ""}
                            </div>
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {doneClean}
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {p.next_procedure ?? "-"}
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap">
                            <span
                              className={`px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${chip.cls}`}
                            >
                              {chip.label}
                            </span>
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {p.progress_time ?? p.updated_at ?? ""}
                          </td>
                        </tr>
                      );
                    })
                  )}
                </tbody>
              </table>
            </div>
            <div className="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
              <span className="text-sm text-gray-700">
                Showing {patients.length} results
              </span>
            </div>
          </div>
        )}

        {/* ═══ QUEUE VIEW ═══ */}
        {tab === "queue" && (
          <div className="bg-white rounded-lg shadow overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-semibold">Queue</h3>
                <p className="text-sm text-gray-500">
                  New registrations waiting for nurse confirmation
                </p>
              </div>
              <button
                onClick={() => refetchQueue()}
                className="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2"
              >
                <RefreshCw className="w-4 h-4" /> Refresh
              </button>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase w-24">
                      Queue No
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Patient
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Location
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Chief Complaint
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Queued At
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {queueRows.length === 0 ? (
                    <tr>
                      <td
                        colSpan={6}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No patients in queue
                      </td>
                    </tr>
                  ) : (
                    queueRows.map((item) => {
                      const p = item.payload ?? {};
                      const age = p.dob ? calcAge(p.dob) : null;
                      return (
                        <tr key={item.id}>
                          <td className="px-3 py-4 whitespace-nowrap text-sm text-gray-700 text-center">
                            {item.id}
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap">
                            <div className="flex items-center">
                              <div className="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <User className="w-4 h-4 text-gray-500" />
                              </div>
                              <div className="ml-4">
                                <div className="text-sm font-medium text-gray-900">
                                  {p.full_name ?? ""}
                                </div>
                                <div className="text-sm text-gray-500">
                                  {age !== null ? `${age} years` : ""}
                                </div>
                              </div>
                            </div>
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {p.initial_location ?? ""}
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {p.diagnosis ?? "-"}
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {item.created_at}
                          </td>
                          <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div className="flex items-center gap-2">
                              <button
                                onClick={() => openEditQueue(item.id)}
                                className="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50"
                              >
                                Edit
                              </button>
                              <button
                                onClick={() => {
                                  setPendingConfirmId(item.id);
                                  confirmModal.show();
                                }}
                                className="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700"
                              >
                                Confirm
                              </button>
                            </div>
                          </td>
                        </tr>
                      );
                    })
                  )}
                </tbody>
              </table>
            </div>
            <div className="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
              <span className="text-sm text-gray-700">
                Showing {queueRows.length} results
              </span>
            </div>
          </div>
        )}
      </div>

      {/* ═══ ADD/EDIT PATIENT MODAL ═══ */}
      <Modal
        open={patientModal.open}
        onClose={patientModal.hide}
        title={modalTitle}
        maxWidth="max-w-6xl"
      >
        <div className="flex justify-end mb-4">
          <button
            type="button"
            onClick={() => autoFillMutation.mutate()}
            disabled={autoFillMutation.isPending}
            className="px-3 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50"
          >
            {autoFillMutation.isPending ? "Filling..." : "Auto Fill (AI)"}
          </button>
        </div>
        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Full Name
              </label>
              <input
                type="text"
                required
                value={form.full_name}
                onChange={(e) => setField("full_name", e.target.value)}
                className={inputCls}
                placeholder="Enter patient name"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Date of Birth
              </label>
              <input
                type="date"
                required
                value={form.dob}
                onChange={(e) => setField("dob", e.target.value)}
                className={inputCls}
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Age
              </label>
              <input
                type="text"
                readOnly
                value={calcAge(form.dob)}
                className={`${inputCls} bg-gray-50`}
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Sex
              </label>
              <select
                required
                value={form.sex}
                onChange={(e) => setField("sex", e.target.value)}
                className={selectCls}
              >
                <option value="">Select sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Blood Type
              </label>
              <select
                value={form.blood_type}
                onChange={(e) => setField("blood_type", e.target.value)}
                className={selectCls}
              >
                <option value="">Select blood type</option>
                {["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"].map(
                  (bt) => (
                    <option key={bt} value={bt}>
                      {bt}
                    </option>
                  ),
                )}
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Civil Status
              </label>
              <select
                required
                value={form.civil_status}
                onChange={(e) => setField("civil_status", e.target.value)}
                className={selectCls}
              >
                <option value="">Select civil status</option>
                {["Single", "Married", "Widowed", "Separated"].map((cs) => (
                  <option key={cs} value={cs}>
                    {cs}
                  </option>
                ))}
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Contact Number
              </label>
              <input
                type="text"
                required
                value={form.contact}
                onChange={(e) => setField("contact", e.target.value)}
                className={inputCls}
                placeholder="Enter contact number"
              />
            </div>
            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Diagnosis / Chief Complaint
              </label>
              <input
                type="text"
                value={form.diagnosis}
                onChange={(e) => setField("diagnosis", e.target.value)}
                className={inputCls}
                placeholder="e.g., CKD Stage 5 / ESRD"
              />
            </div>
            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Street Address
              </label>
              <input
                type="text"
                value={form.street_address}
                onChange={(e) => setField("street_address", e.target.value)}
                className={inputCls}
                placeholder="House no., street, subdivision"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Barangay
              </label>
              <input
                type="text"
                value={form.barangay}
                onChange={(e) => setField("barangay", e.target.value)}
                className={inputCls}
                placeholder="Enter barangay"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                City
              </label>
              <input
                type="text"
                value={form.city}
                onChange={(e) => setField("city", e.target.value)}
                className={inputCls}
                placeholder="Enter city"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Province
              </label>
              <input
                type="text"
                value={form.province}
                onChange={(e) => setField("province", e.target.value)}
                className={inputCls}
                placeholder="Enter province"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                ZIP Code
              </label>
              <input
                type="text"
                value={form.zip_code}
                onChange={(e) => setField("zip_code", e.target.value)}
                className={inputCls}
                placeholder="Enter ZIP"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Emergency Contact Name
              </label>
              <input
                type="text"
                value={form.emergency_contact_name}
                onChange={(e) =>
                  setField("emergency_contact_name", e.target.value)
                }
                className={inputCls}
                placeholder="Enter contact person"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Relationship
              </label>
              <input
                type="text"
                value={form.emergency_contact_relationship}
                onChange={(e) =>
                  setField("emergency_contact_relationship", e.target.value)
                }
                className={inputCls}
                placeholder="e.g., Spouse"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Emergency Contact Phone
              </label>
              <input
                type="text"
                value={form.emergency_contact_phone}
                onChange={(e) =>
                  setField("emergency_contact_phone", e.target.value)
                }
                className={inputCls}
                placeholder="Enter contact phone"
              />
            </div>
          </div>
          <div className="border-t border-gray-100 pt-6 flex justify-end space-x-4">
            <button
              type="button"
              onClick={patientModal.hide}
              className="px-6 py-3 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              disabled={saveMutation.isPending || queueUpdateMutation.isPending}
              className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            >
              {saveMutation.isPending || queueUpdateMutation.isPending
                ? "Saving..."
                : submitLabel}
            </button>
          </div>
        </form>
      </Modal>

      {/* ═══ QUEUE CONFIRM DIALOG ═══ */}
      <ConfirmDialog
        open={confirmModal.open}
        onClose={() => {
          confirmModal.hide();
          setPendingConfirmId(null);
        }}
        onConfirm={() => {
          if (pendingConfirmId) confirmMutation.mutate(pendingConfirmId);
        }}
        title="Confirm Patient"
        message="Confirm this patient's details and move to Patient's Progress?"
        confirmLabel="Confirm"
        loading={confirmMutation.isPending}
      />
    </div>
  );
}

/* ── Fancy stat card matching patients.php design ── */
function StatCardFancy({
  icon,
  value,
  unit,
  label,
  badge,
  gradient,
  waitingBar,
}: {
  icon: React.ReactNode;
  value: number;
  unit: string;
  label: string;
  badge: string;
  gradient: string;
  waitingBar?: number;
}) {
  return (
    <div className="group relative bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
      <div className="relative p-6">
        <div className="flex items-center justify-between">
          <div
            className={`w-14 h-14 rounded-2xl bg-gradient-to-r ${gradient} flex items-center justify-center transform transition-transform group-hover:scale-110`}
          >
            {icon}
          </div>
          {badge && (
            <span className="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">
              {badge}
            </span>
          )}
        </div>
        <div className="mt-4">
          <div className="flex items-baseline space-x-1">
            <h2 className="text-4xl font-bold text-gray-800">{value}</h2>
            <span className="text-sm font-medium text-gray-500">{unit}</span>
          </div>
          <p className="text-sm text-gray-600 mt-1">{label}</p>
          {waitingBar !== undefined && (
            <div className="w-full bg-gray-200 rounded-full h-1.5 mt-3">
              <div
                className="bg-amber-500 h-1.5 rounded-full"
                style={{ width: `${Math.min(100, waitingBar)}%` }}
              />
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
