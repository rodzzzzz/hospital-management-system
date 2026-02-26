import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import {
  Users,
  FileText,
  FlaskConical,
  Receipt,
  DollarSign,
  Search,
  RefreshCw,
  Plus,
} from "lucide-react";
import { toast } from "sonner";
import { useHashTab } from "@/hooks/useHashTab";
import { useDebounce } from "@/hooks/useDebounce";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

type MRTab =
  | "dashboard"
  | "patients"
  | "encounters"
  | "er-forms"
  | "lab-results"
  | "resita"
  | "billing";
const TABS: { key: MRTab; label: string }[] = [
  { key: "dashboard", label: "Dashboard" },
  { key: "patients", label: "Patients" },
  { key: "encounters", label: "Encounters" },
  { key: "er-forms", label: "ER Forms" },
  { key: "lab-results", label: "Lab Results" },
  { key: "resita", label: "Resita" },
  { key: "billing", label: "Billing" },
];

export default function MedicalRecords() {
  const [tab, setTab] = useHashTab<MRTab>("dashboard");
  const [selectedPatient, setSelectedPatient] = useState<any>(null);
  const [selectedEncounter, setSelectedEncounter] = useState<any>(null);
  const [patientSearch, setPatientSearch] = useState("");
  const dPatientSearch = useDebounce(patientSearch, 300);
  const [encounterType, setEncounterType] = useState("ER");

  /* ── data ── */
  const { data: dashData } = useQuery({
    queryKey: ["mr-dashboard", selectedPatient?.id, selectedEncounter?.id],
    queryFn: async () => {
      const params = new URLSearchParams();
      if (selectedPatient?.id) params.set("patient_id", selectedPatient.id);
      if (selectedEncounter?.id)
        params.set("encounter_id", selectedEncounter.id);
      const { data } = await client.get(
        `/medical-records/dashboard.php?${params.toString()}`,
      );
      return data;
    },
    enabled: tab === "dashboard",
  });
  const { data: searchData } = useQuery({
    queryKey: ["mr-patient-search", dPatientSearch],
    queryFn: async () => {
      const { data } = await client.get(
        `/medical-records/patients.php?q=${encodeURIComponent(dPatientSearch)}`,
      );
      return data;
    },
    enabled: tab === "patients" && dPatientSearch.length >= 2,
  });
  const { data: encountersData, refetch: refetchEnc } = useQuery({
    queryKey: ["mr-encounters", selectedPatient?.id],
    queryFn: async () => {
      const { data } = await client.get(
        `/medical-records/encounters.php?patient_id=${selectedPatient?.id}`,
      );
      return data;
    },
    enabled: tab === "encounters" && !!selectedPatient?.id,
  });
  const { data: erData } = useQuery({
    queryKey: ["mr-er", selectedEncounter?.id],
    queryFn: async () => {
      const { data } = await client.get(
        `/medical-records/er_forms.php?encounter_id=${selectedEncounter?.id}`,
      );
      return data;
    },
    enabled: tab === "er-forms" && !!selectedEncounter?.id,
  });
  const { data: labData } = useQuery({
    queryKey: ["mr-lab", selectedEncounter?.id],
    queryFn: async () => {
      const { data } = await client.get(
        `/medical-records/lab_results.php?encounter_id=${selectedEncounter?.id}`,
      );
      return data;
    },
    enabled: tab === "lab-results" && !!selectedEncounter?.id,
  });
  const { data: resitaData } = useQuery({
    queryKey: ["mr-resita", selectedEncounter?.id],
    queryFn: async () => {
      const { data } = await client.get(
        `/medical-records/resita.php?encounter_id=${selectedEncounter?.id}`,
      );
      return data;
    },
    enabled: tab === "resita" && !!selectedEncounter?.id,
  });
  const { data: billingData } = useQuery({
    queryKey: ["mr-billing", selectedEncounter?.id],
    queryFn: async () => {
      const { data } = await client.get(
        `/medical-records/billing.php?encounter_id=${selectedEncounter?.id}`,
      );
      return data;
    },
    enabled: tab === "billing" && !!selectedEncounter?.id,
  });

  const dash = dashData?.stats ?? {
    er_forms: 0,
    lab_results: 0,
    resita: 0,
    invoices: 0,
  };
  const patients: any[] = searchData?.ok ? (searchData.patients ?? []) : [];
  const encounters: any[] = encountersData?.ok
    ? (encountersData.encounters ?? [])
    : [];
  const erForms: any[] = erData?.ok ? (erData.forms ?? []) : [];
  const labResults: any[] = labData?.ok ? (labData.results ?? []) : [];
  const receipts: any[] = resitaData?.ok ? (resitaData.receipts ?? []) : [];
  const billingItems: any[] = billingData?.ok ? (billingData.items ?? []) : [];

  const createEncMut = useMutation({
    mutationFn: async () => {
      const { data } = await client.post("/medical-records/encounters.php", {
        patient_id: selectedPatient?.id,
        type: encounterType,
      });
      if (!data.ok) throw new Error(data.error ?? "Failed");
      return data;
    },
    onSuccess: () => {
      toast.success("Encounter created");
      refetchEnc();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  return (
    <div>
      <div className="bg-white p-6 flex items-center justify-between shadow-sm">
        <h1 className="text-2xl font-semibold">Medical Records</h1>
      </div>

      {/* Patient Banner */}
      <div className="px-6 pt-4">
        <div className="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <div className="min-w-0">
            <div className="text-xs text-gray-500">Selected Patient</div>
            <div className="text-base font-semibold text-gray-900 truncate">
              {selectedPatient?.full_name ?? "None"}
            </div>
            <div className="text-sm text-gray-600 truncate">
              {selectedEncounter
                ? `Encounter #${selectedEncounter.id} — ${selectedEncounter.type}`
                : "No encounter selected"}
            </div>
          </div>
          <button
            onClick={() => {
              setTab("patients");
            }}
            className="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-sm"
          >
            Change Patient
          </button>
        </div>
      </div>

      {/* Tabs */}
      <div className="bg-white border-b border-gray-200 px-6 mt-4">
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
        {/* DASHBOARD */}
        {tab === "dashboard" && (
          <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
              {[
                {
                  icon: FileText,
                  label: "ER Forms",
                  value: dash.er_forms,
                  bg: "bg-blue-50",
                  color: "text-blue-600",
                },
                {
                  icon: FlaskConical,
                  label: "Lab Results",
                  value: dash.lab_results,
                  bg: "bg-green-50",
                  color: "text-green-600",
                },
                {
                  icon: Receipt,
                  label: "Resita",
                  value: dash.resita,
                  bg: "bg-purple-50",
                  color: "text-purple-600",
                },
                {
                  icon: DollarSign,
                  label: "Invoices",
                  value: dash.invoices,
                  bg: "bg-amber-50",
                  color: "text-amber-600",
                },
              ].map((c) => (
                <div key={c.label} className="bg-gray-50 rounded-lg p-4">
                  <div className="flex items-center gap-2 mb-1">
                    <div className={`p-2 rounded-lg ${c.bg}`}>
                      <c.icon className={`w-4 h-4 ${c.color}`} />
                    </div>
                    <span className="text-xs text-gray-500">{c.label}</span>
                  </div>
                  <div className="text-2xl font-semibold text-gray-900">
                    {c.value}
                  </div>
                </div>
              ))}
            </div>
            {!selectedPatient && (
              <p className="text-sm text-gray-500">
                Select a patient from the Patients tab to view their records.
              </p>
            )}
          </div>
        )}

        {/* PATIENTS */}
        {tab === "patients" && (
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between mb-4">
              <div>
                <h2 className="text-lg font-semibold text-gray-800">
                  Patients
                </h2>
                <p className="text-sm text-gray-600 mt-1">
                  Select a patient to view encounters and full medical record.
                </p>
              </div>
              <Users className="w-8 h-8 text-green-600" />
            </div>
            <div className="relative">
              <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input
                value={patientSearch}
                onChange={(e) => setPatientSearch(e.target.value)}
                type="text"
                placeholder="Search patient name / code / PhilHealth PIN"
                className="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            {patients.length > 0 && (
              <div className="mt-3 border border-gray-200 rounded-lg divide-y divide-gray-100 max-h-64 overflow-y-auto">
                {patients.map((p: any) => (
                  <button
                    key={p.id}
                    onClick={() => {
                      setSelectedPatient(p);
                      setSelectedEncounter(null);
                      setTab("encounters");
                      toast.success(`Selected: ${p.full_name}`);
                    }}
                    className="w-full text-left px-4 py-3 hover:bg-blue-50 transition-colors"
                  >
                    <div className="font-medium text-gray-900 text-sm">
                      {p.full_name}
                    </div>
                    <div className="text-xs text-gray-500">
                      {p.patient_code ?? ""}{" "}
                      {p.philhealth_pin ? `• PIN: ${p.philhealth_pin}` : ""}
                    </div>
                  </button>
                ))}
              </div>
            )}
          </div>
        )}

        {/* ENCOUNTERS */}
        {tab === "encounters" && (
          <div className="bg-white rounded-lg shadow overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h2 className="text-lg font-semibold text-gray-900">
                  Encounters
                </h2>
                <p className="text-sm text-gray-500">
                  View visits grouped by encounter.
                </p>
              </div>
              <div className="flex items-center gap-2">
                <select
                  value={encounterType}
                  onChange={(e) => setEncounterType(e.target.value)}
                  className="px-3 py-2 border border-gray-200 rounded-lg text-sm"
                >
                  <option value="ER">ER</option>
                  <option value="OPD">OPD</option>
                  <option value="IPD">IPD</option>
                  <option value="PHARMACY">PHARMACY</option>
                </select>
                <button
                  onClick={() => createEncMut.mutate()}
                  disabled={!selectedPatient || createEncMut.isPending}
                  className="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm hover:bg-gray-800 disabled:opacity-50 flex items-center gap-1.5"
                >
                  <Plus className="w-3.5 h-3.5" /> Create
                </button>
                <button
                  onClick={() => refetchEnc()}
                  className="px-3 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50 flex items-center gap-1"
                >
                  <RefreshCw className="w-3.5 h-3.5" /> Refresh
                </button>
              </div>
            </div>
            {!selectedPatient ? (
              <div className="p-6 text-center text-sm text-gray-500">
                Select a patient first
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200">
                  <thead className="bg-gray-50">
                    <tr>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        ID
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Type
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Date
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Status
                      </th>
                      <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                        Action
                      </th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {encounters.length === 0 ? (
                      <tr>
                        <td
                          colSpan={5}
                          className="px-6 py-8 text-center text-sm text-gray-500"
                        >
                          No encounters
                        </td>
                      </tr>
                    ) : (
                      encounters.map((enc: any) => (
                        <tr key={enc.id}>
                          <td className="px-6 py-4 text-sm font-medium text-gray-900">
                            #{enc.id}
                          </td>
                          <td className="px-6 py-4 text-sm text-gray-600">
                            {enc.type}
                          </td>
                          <td className="px-6 py-4 text-sm text-gray-500">
                            {enc.created_at}
                          </td>
                          <td className="px-6 py-4">
                            <StatusBadge status={enc.status ?? "active"} />
                          </td>
                          <td className="px-6 py-4 text-right">
                            <button
                              onClick={() => {
                                setSelectedEncounter(enc);
                                setTab("dashboard");
                                toast.success(`Encounter #${enc.id} selected`);
                              }}
                              className="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700"
                            >
                              Select
                            </button>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        )}

        {/* ER FORMS */}
        {tab === "er-forms" && (
          <div className="bg-white rounded-lg shadow overflow-hidden">
            <div className="p-6 border-b border-gray-100">
              <h3 className="text-lg font-semibold text-gray-900">ER Forms</h3>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Form
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Date
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {erForms.length === 0 ? (
                    <tr>
                      <td
                        colSpan={3}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        {selectedEncounter
                          ? "No ER forms"
                          : "Select an encounter"}
                      </td>
                    </tr>
                  ) : (
                    erForms.map((f: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {f.form_name ?? f.id}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {f.created_at}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={f.status} />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* LAB RESULTS */}
        {tab === "lab-results" && (
          <div className="bg-white rounded-lg shadow overflow-hidden">
            <div className="p-6 border-b border-gray-100">
              <h3 className="text-lg font-semibold text-gray-900">
                Lab Results (Released)
              </h3>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Test
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Result
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Date
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
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
                        {selectedEncounter
                          ? "No lab results"
                          : "Select an encounter"}
                      </td>
                    </tr>
                  ) : (
                    labResults.map((r: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {r.test_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {r.result ?? "-"}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {r.released_at}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={r.status} />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* RESITA */}
        {tab === "resita" && (
          <div className="bg-white rounded-lg shadow overflow-hidden">
            <div className="p-6 border-b border-gray-100">
              <h3 className="text-lg font-semibold text-gray-900">
                Resita (Receipts)
              </h3>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Receipt #
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Amount
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Date
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {receipts.length === 0 ? (
                    <tr>
                      <td
                        colSpan={4}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        {selectedEncounter
                          ? "No receipts"
                          : "Select an encounter"}
                      </td>
                    </tr>
                  ) : (
                    receipts.map((r: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {r.receipt_no}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          ₱{Number(r.amount).toFixed(2)}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {r.date}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={r.status} />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* BILLING */}
        {tab === "billing" && (
          <div className="bg-white rounded-lg shadow overflow-hidden">
            <div className="p-6 border-b border-gray-100">
              <h3 className="text-lg font-semibold text-gray-900">Billing</h3>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Invoice
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Description
                    </th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      Amount
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Date
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {billingItems.length === 0 ? (
                    <tr>
                      <td
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        {selectedEncounter
                          ? "No billing items"
                          : "Select an encounter"}
                      </td>
                    </tr>
                  ) : (
                    billingItems.map((b: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {b.invoice_id}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {b.description}
                        </td>
                        <td className="px-6 py-4 text-sm text-right text-gray-900">
                          ₱{Number(b.amount).toFixed(2)}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {b.date}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={b.status} />
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
