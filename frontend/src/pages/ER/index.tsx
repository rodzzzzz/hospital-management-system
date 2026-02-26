import { useState, type FormEvent } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import {
  Ambulance,
  BedDouble,
  Stethoscope,
  ShieldCheck,
  Search,
  RefreshCw,
} from "lucide-react";
import { toast } from "sonner";
import { useHashTab } from "@/hooks/useHashTab";
import { useDebounce } from "@/hooks/useDebounce";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

type ERTab = "request" | "ward" | "feedback" | "clearance";

const TABS: { key: ERTab; label: string }[] = [
  { key: "request", label: "Lab Request" },
  { key: "ward", label: "Ward" },
  { key: "feedback", label: "Doctor Feedback" },
  { key: "clearance", label: "Clearance" },
];

const inputCls =
  "w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500";

export default function ER() {
  const [tab, setTab] = useHashTab<ERTab>("request");
  const [clearFilter, setClearFilter] = useState("all");
  const [clearSearch, setClearSearch] = useState("");
  const dClearSearch = useDebounce(clearSearch, 250);

  /* ── data ── */
  const { data: feedbackData, refetch: refetchFeedback } = useQuery({
    queryKey: ["er-feedback"],
    queryFn: async () => {
      const { data } = await client.get("/er/doctor_feedback.php");
      return data;
    },
    enabled: tab === "feedback",
  });

  const { data: wardData } = useQuery({
    queryKey: ["er-ward"],
    queryFn: async () => {
      const { data } = await client.get("/er/ward.php");
      return data;
    },
    enabled: tab === "ward",
  });

  const { data: clearanceData, refetch: refetchClearance } = useQuery({
    queryKey: ["er-clearance", clearFilter, dClearSearch],
    queryFn: async () => {
      const params = new URLSearchParams();
      if (clearFilter !== "all") params.set("type", clearFilter);
      if (dClearSearch) params.set("q", dClearSearch);
      const { data } = await client.get(`/er/clearance.php?${params}`);
      return data;
    },
    enabled: tab === "clearance",
  });

  const { data: doctorsData } = useQuery({
    queryKey: ["er-doctors"],
    queryFn: async () => {
      const { data } = await client.get("/er/doctors.php");
      return data;
    },
    enabled: tab === "request",
  });

  const feedback: Record<string, unknown>[] = feedbackData?.ok
    ? (feedbackData.requests ?? [])
    : [];
  const wardStats = wardData?.stats ?? {
    total_beds: 0,
    occupied: 0,
    available: 0,
    avg_stay: "-",
  };
  const wardPatients: Record<string, unknown>[] = wardData?.ok
    ? (wardData.patients ?? [])
    : [];
  const clearanceRows: Record<string, unknown>[] = clearanceData?.ok
    ? (clearanceData.clearances ?? [])
    : [];
  const clearanceStats = clearanceData?.stats ?? {
    pending: 0,
    approved: 0,
    discharged: 0,
    transferred: 0,
  };
  const doctors: { id: number; name: string }[] = doctorsData?.ok
    ? (doctorsData.doctors ?? [])
    : [];

  /* ── lab request mutation ── */
  const labMut = useMutation({
    mutationFn: async (payload: Record<string, unknown>) => {
      const { data } = await client.post("/er/create_lab_request.php", payload);
      if (!data.ok) throw new Error(data.error ?? "Failed");
      return data;
    },
    onSuccess: () => toast.success("Lab request submitted"),
    onError: (e: Error) => toast.error(e.message),
  });

  const handleLabSubmit = (e: FormEvent) => {
    e.preventDefault();
    const fd = new FormData(e.target as HTMLFormElement);
    const payload: Record<string, unknown> = {};
    fd.forEach((v, k) => {
      payload[k] = v;
    });
    labMut.mutate(payload);
  };

  return (
    <div>
      <header className="bg-white p-6 flex items-center justify-between">
        <h1 className="text-2xl font-semibold">ER</h1>
      </header>

      {/* Tabs */}
      <div className="bg-white border-b border-gray-200 px-6">
        <nav className="flex space-x-1 -mb-px">
          {TABS.map((t) => (
            <button
              key={t.key}
              onClick={() => setTab(t.key)}
              className={`px-5 py-3 text-sm font-semibold rounded-t-lg border-b-2 transition-colors ${
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
        {/* ═══ LAB REQUEST ═══ */}
        {tab === "request" && (
          <section className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-lg font-semibold text-gray-800">
                  ER Nurse Lab Request
                </h2>
                <p className="text-sm text-gray-600 mt-1">
                  Requests require doctor approval before Laboratory can
                  process.
                </p>
              </div>
              <div className="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                <Ambulance className="w-6 h-6 text-red-600" />
              </div>
            </div>

            <form onSubmit={handleLabSubmit} className="mt-6 space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Patient
                </label>
                <input
                  name="patient_search"
                  type="text"
                  className={`mt-2 ${inputCls}`}
                  placeholder="Search patient name / ID"
                  autoComplete="off"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Triage Level (1-5)
                </label>
                <select name="triage_level" className={`mt-2 ${inputCls}`}>
                  <option value="">Select triage level</option>
                  <option value="1">1 - Resuscitation</option>
                  <option value="2">2 - Emergent</option>
                  <option value="3">3 - Urgent</option>
                  <option value="4">4 - Less Urgent</option>
                  <option value="5">5 - Non-Urgent</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Chief Complaint
                </label>
                <input
                  name="chief_complaint"
                  type="text"
                  className={`mt-2 ${inputCls}`}
                  placeholder="e.g., Fever, Chest pain"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Vitals
                </label>
                <div className="mt-2 grid grid-cols-2 gap-3">
                  <input
                    name="vital_bp"
                    type="text"
                    className={inputCls}
                    placeholder="BP (e.g. 120/80)"
                  />
                  <input
                    name="vital_hr"
                    type="text"
                    className={inputCls}
                    placeholder="HR"
                  />
                  <input
                    name="vital_rr"
                    type="text"
                    className={inputCls}
                    placeholder="RR"
                  />
                  <input
                    name="vital_temp"
                    type="text"
                    className={inputCls}
                    placeholder="Temp"
                  />
                  <input
                    name="vital_spo2"
                    type="text"
                    className={inputCls}
                    placeholder="SpO2"
                  />
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Priority
                </label>
                <select name="priority" className={`mt-2 ${inputCls}`}>
                  <option value="routine">Routine</option>
                  <option value="urgent">Urgent</option>
                  <option value="stat">STAT</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Doctor
                </label>
                <select name="doctor_id" className={`mt-2 ${inputCls}`}>
                  <option value="">Select doctor</option>
                  {doctors.map((d) => (
                    <option key={d.id} value={d.id}>
                      {d.name}
                    </option>
                  ))}
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Requested Tests
                </label>
                <p className="text-xs text-gray-500 mt-1">
                  Standing order tests may be auto-approved (CBC, Urinalysis,
                  Pregnancy, Blood Sugar, Electrolytes).
                </p>
                <textarea
                  name="tests"
                  rows={3}
                  className={`mt-2 ${inputCls}`}
                  placeholder="List tests..."
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Requested by (Nurse)
                </label>
                <input
                  name="requested_by"
                  type="text"
                  className={`mt-2 ${inputCls}`}
                  placeholder="Nurse name"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">
                  Notes
                </label>
                <textarea
                  name="notes"
                  rows={3}
                  className={`mt-2 ${inputCls}`}
                  placeholder="Additional notes..."
                />
              </div>
              <button
                type="submit"
                disabled={labMut.isPending}
                className="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
              >
                {labMut.isPending ? "Submitting..." : "Submit Lab Request"}
              </button>
            </form>
          </section>
        )}

        {/* ═══ WARD ═══ */}
        {tab === "ward" && (
          <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
              {[
                {
                  label: "Total Beds",
                  val: wardStats.total_beds,
                  icon: BedDouble,
                  bg: "bg-blue-50",
                  color: "text-blue-600",
                },
                {
                  label: "Occupied",
                  val: wardStats.occupied,
                  icon: BedDouble,
                  bg: "bg-red-50",
                  color: "text-red-600",
                },
                {
                  label: "Available",
                  val: wardStats.available,
                  icon: BedDouble,
                  bg: "bg-green-50",
                  color: "text-green-600",
                },
                {
                  label: "Avg Stay",
                  val: wardStats.avg_stay,
                  icon: Stethoscope,
                  bg: "bg-purple-50",
                  color: "text-purple-600",
                },
              ].map((s) => (
                <div
                  key={s.label}
                  className="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between"
                >
                  <div>
                    <div className="text-xs text-gray-500">{s.label}</div>
                    <div className="text-2xl font-semibold text-gray-900">
                      {s.val}
                    </div>
                  </div>
                  <div
                    className={`w-10 h-10 rounded-xl ${s.bg} flex items-center justify-center`}
                  >
                    <s.icon className={`w-5 h-5 ${s.color}`} />
                  </div>
                </div>
              ))}
            </div>

            <div className="bg-white rounded-lg shadow-sm overflow-hidden">
              <div className="p-6 border-b border-gray-100">
                <h3 className="text-lg font-semibold text-gray-900">
                  Patient Worklist
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
                        Bed
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Triage
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Status
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Admitted
                      </th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {wardPatients.length === 0 ? (
                      <tr>
                        <td
                          colSpan={5}
                          className="px-6 py-8 text-center text-sm text-gray-500"
                        >
                          No patients in ward
                        </td>
                      </tr>
                    ) : (
                      wardPatients.map((p, i) => (
                        <tr key={i}>
                          <td className="px-6 py-4 text-sm font-medium text-gray-900">
                            {String(p.patient_name ?? "")}
                          </td>
                          <td className="px-6 py-4 text-sm text-gray-600">
                            {String(p.bed ?? "")}
                          </td>
                          <td className="px-6 py-4 text-sm text-gray-600">
                            {String(p.triage_level ?? "")}
                          </td>
                          <td className="px-6 py-4">
                            <StatusBadge status={String(p.status ?? "")} />
                          </td>
                          <td className="px-6 py-4 text-sm text-gray-500">
                            {String(p.admitted_at ?? "")}
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}

        {/* ═══ DOCTOR FEEDBACK ═══ */}
        {tab === "feedback" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-semibold text-gray-900">
                  Doctor Feedback
                </h3>
                <p className="text-sm text-gray-600 mt-1">
                  Doctor feedback for ER patients.
                </p>
              </div>
              <button
                onClick={() => refetchFeedback()}
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
                      Doctor
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Tests
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
                  {feedback.length === 0 ? (
                    <tr>
                      <td
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No feedback records
                      </td>
                    </tr>
                  ) : (
                    feedback.map((r, i) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {String(r.patient_name ?? "")}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {String(r.doctor_name ?? "")}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {String(r.tests ?? "")}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={String(r.status ?? "")} />
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

        {/* ═══ CLEARANCE ═══ */}
        {tab === "clearance" && (
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between gap-4">
              <div>
                <h2 className="text-lg font-semibold text-gray-800">
                  Clearance
                </h2>
                <p className="text-sm text-gray-600 mt-1">
                  Medical / discharge / transfer clearance tracking.
                </p>
              </div>
              <div className="flex items-center gap-2">
                <select
                  value={clearFilter}
                  onChange={(e) => setClearFilter(e.target.value)}
                  className="px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="all">All</option>
                  <option value="medical">Medical</option>
                  <option value="discharge">Discharge</option>
                  <option value="transfer">Transfer</option>
                </select>
                <div className="relative">
                  <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                  <input
                    value={clearSearch}
                    onChange={(e) => setClearSearch(e.target.value)}
                    type="text"
                    placeholder="Search patient"
                    className="pl-9 pr-3 py-2 border border-gray-200 rounded-lg w-56 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
                <button
                  onClick={() => refetchClearance()}
                  className="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800"
                >
                  Refresh
                </button>
              </div>
            </div>

            <div className="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
              {[
                {
                  label: "Pending",
                  val: clearanceStats.pending,
                  bg: "bg-yellow-50",
                  color: "text-yellow-600",
                },
                {
                  label: "Approved",
                  val: clearanceStats.approved,
                  bg: "bg-green-50",
                  color: "text-green-600",
                },
                {
                  label: "Discharged",
                  val: clearanceStats.discharged,
                  bg: "bg-blue-50",
                  color: "text-blue-600",
                },
                {
                  label: "Transferred",
                  val: clearanceStats.transferred,
                  bg: "bg-purple-50",
                  color: "text-purple-600",
                },
              ].map((s) => (
                <div
                  key={s.label}
                  className="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between"
                >
                  <div>
                    <div className="text-xs text-gray-500">{s.label}</div>
                    <div className="text-2xl font-semibold text-gray-900">
                      {s.val}
                    </div>
                  </div>
                  <div
                    className={`w-10 h-10 rounded-xl ${s.bg} flex items-center justify-center`}
                  >
                    <ShieldCheck className={`w-5 h-5 ${s.color}`} />
                  </div>
                </div>
              ))}
            </div>

            <div className="mt-6 overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Patient
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Type
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Doctor
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Date
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {clearanceRows.length === 0 ? (
                    <tr>
                      <td
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No clearance records
                      </td>
                    </tr>
                  ) : (
                    clearanceRows.map((r, i) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {String(r.patient_name ?? "")}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={String(r.type ?? "")} />
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={String(r.status ?? "")} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {String(r.doctor_name ?? "")}
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
      </div>
    </div>
  );
}
