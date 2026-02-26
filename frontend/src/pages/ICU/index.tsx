import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
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
  UserCheck,
  BedDouble,
  HeartPulse,
  Clock,
  RefreshCw,
} from "lucide-react";
import { useHashTab } from "@/hooks/useHashTab";
import { StatCard } from "@/components/ui/StatCard";
import { ChartCard } from "@/components/ui/ChartCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

type ICUTab =
  | "overview"
  | "patients"
  | "labs"
  | "transfers"
  | "billing"
  | "admission";
const TABS: { key: ICUTab; label: string }[] = [
  { key: "overview", label: "Overview" },
  { key: "patients", label: "Patients" },
  { key: "labs", label: "Labs / Results" },
  { key: "transfers", label: "Transfers" },
  { key: "billing", label: "Billing" },
  { key: "admission", label: "Admission Status" },
];

export default function ICU() {
  const [tab, setTab] = useHashTab<ICUTab>("overview");
  const [billingPatient, setBillingPatient] = useState("");

  /* ── data ── */
  const { data: statsData } = useQuery({
    queryKey: ["icu-stats"],
    queryFn: async () => {
      const { data } = await client.get("/icu/stats.php");
      return data;
    },
    retry: false,
  });
  const { data: analyticsData } = useQuery({
    queryKey: ["icu-analytics"],
    queryFn: async () => {
      const { data } = await client.get("/icu/analytics.php");
      return data;
    },
    enabled: tab === "overview",
  });
  const { data: patientsData, refetch: refetchPatients } = useQuery({
    queryKey: ["icu-patients"],
    queryFn: async () => {
      const { data } = await client.get("/icu/patients.php");
      return data;
    },
    enabled: tab === "patients" || tab === "billing",
  });
  const { data: labsData, refetch: refetchLabs } = useQuery({
    queryKey: ["icu-labs"],
    queryFn: async () => {
      const { data } = await client.get("/icu/labs.php");
      return data;
    },
    enabled: tab === "labs",
  });
  const { data: transfersData, refetch: refetchTransfers } = useQuery({
    queryKey: ["icu-transfers"],
    queryFn: async () => {
      const { data } = await client.get("/icu/transfers.php");
      return data;
    },
    enabled: tab === "transfers",
  });
  const { data: billingData } = useQuery({
    queryKey: ["icu-billing", billingPatient],
    queryFn: async () => {
      const { data } = await client.get(
        `/icu/billing.php?patient_id=${billingPatient}`,
      );
      return data;
    },
    enabled: tab === "billing" && !!billingPatient,
  });
  const { data: admissionData, refetch: refetchAdmission } = useQuery({
    queryKey: ["icu-admission"],
    queryFn: async () => {
      const { data } = await client.get("/icu/admission_status.php");
      return data;
    },
    enabled: tab === "admission",
  });

  const stats = statsData?.stats ?? {
    active_patients: "-",
    available_beds: "-",
    occupied_beds: "-",
    avg_los_days: "-",
  };
  const occupancy = (analyticsData?.occupancy ?? []) as {
    day: string;
    pct: number;
  }[];
  const shifts = (analyticsData?.shifts ?? []) as {
    shift: string;
    count: number;
  }[];
  const patients: any[] = patientsData?.ok ? (patientsData.patients ?? []) : [];
  const labs: any[] = labsData?.ok ? (labsData.labs ?? []) : [];
  const transfers: any[] = transfersData?.ok
    ? (transfersData.transfers ?? [])
    : [];
  const billingItems: any[] = billingData?.ok ? (billingData.items ?? []) : [];
  const admissions: any[] = admissionData?.ok
    ? (admissionData.admissions ?? [])
    : [];
  const billingRoom = billingData?.room_bed ?? "-";

  return (
    <div>
      <div className="flex justify-between items-center mb-6 px-6 pt-6">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">ICU</h1>
          <p className="text-sm text-gray-600 mt-1">
            Critical care census and bed utilization overview.
          </p>
        </div>
      </div>

      {/* Tabs */}
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
                icon={UserCheck}
                label="Active Patients"
                value={stats.active_patients}
                iconBg="bg-indigo-100"
                iconColor="text-indigo-700"
              />
              <StatCard
                icon={BedDouble}
                label="Available Beds"
                value={stats.available_beds}
                iconBg="bg-emerald-100"
                iconColor="text-emerald-700"
              />
              <StatCard
                icon={HeartPulse}
                label="Occupied Beds"
                value={stats.occupied_beds}
                iconBg="bg-rose-100"
                iconColor="text-rose-700"
              />
              <StatCard
                icon={Clock}
                label="Avg LOS (days)"
                value={stats.avg_los_days}
                iconBg="bg-amber-100"
                iconColor="text-amber-700"
              />
            </div>
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <div className="lg:col-span-2">
                <ChartCard title="Occupancy Trend (7 days)">
                  <ResponsiveContainer width="100%" height={250}>
                    <LineChart
                      data={
                        occupancy.length ? occupancy : [{ day: "Mon", pct: 0 }]
                      }
                    >
                      <XAxis dataKey="day" tick={{ fontSize: 11 }} />
                      <YAxis tick={{ fontSize: 11 }} unit="%" />
                      <Tooltip />
                      <Line
                        type="monotone"
                        dataKey="pct"
                        stroke="#6366F1"
                        strokeWidth={2}
                        dot={false}
                      />
                    </LineChart>
                  </ResponsiveContainer>
                </ChartCard>
              </div>
              <ChartCard title="Admissions by Shift">
                <ResponsiveContainer width="100%" height={250}>
                  <BarChart
                    data={shifts.length ? shifts : [{ shift: "AM", count: 0 }]}
                  >
                    <XAxis dataKey="shift" tick={{ fontSize: 11 }} />
                    <YAxis tick={{ fontSize: 11 }} />
                    <Tooltip />
                    <Bar dataKey="count" fill="#10B981" />
                  </BarChart>
                </ResponsiveContainer>
              </ChartCard>
            </div>
          </div>
        )}

        {/* ═══ PATIENTS ═══ */}
        {tab === "patients" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-semibold text-gray-900">
                  Patients List
                </h3>
                <p className="text-sm text-gray-600 mt-1">
                  Active ICU patients, bed assignment, and attending physician.
                </p>
              </div>
              <button
                onClick={() => refetchPatients()}
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
                      Bed
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Attending
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Admitted
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Diagnosis
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {patients.length === 0 ? (
                    <tr>
                      <td
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No patients
                      </td>
                    </tr>
                  ) : (
                    patients.map((p: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {p.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {p.bed}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {p.attending}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {p.admitted}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {p.diagnosis}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* ═══ LABS ═══ */}
        {tab === "labs" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-semibold text-gray-900">
                  Labs / Results
                </h3>
                <p className="text-sm text-gray-600 mt-1">
                  Pending and abnormal critical labs.
                </p>
              </div>
              <button
                onClick={() => refetchLabs()}
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
                      Bed
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Test
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Result
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Collected
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {labs.length === 0 ? (
                    <tr>
                      <td
                        colSpan={6}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No labs
                      </td>
                    </tr>
                  ) : (
                    labs.map((l: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {l.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {l.bed}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {l.test}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={l.status} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {l.result ?? "-"}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {l.collected}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* ═══ TRANSFERS ═══ */}
        {tab === "transfers" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-semibold text-gray-900">
                  Transfers / Discharge Planning
                </h3>
                <p className="text-sm text-gray-600 mt-1">
                  Stepdown candidates, transfer requests, and discharge
                  summaries.
                </p>
              </div>
              <button
                onClick={() => refetchTransfers()}
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
                      Bed
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Stepdown
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Transfer
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Destination
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      ETA
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Discharge
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {transfers.length === 0 ? (
                    <tr>
                      <td
                        colSpan={7}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No transfers
                      </td>
                    </tr>
                  ) : (
                    transfers.map((t: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {t.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {t.bed}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={t.stepdown ? "yes" : "no"} />
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={t.transfer_status ?? "-"} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {t.destination ?? "-"}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {t.eta ?? "-"}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {t.discharge_summary ? "Done" : "Pending"}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* ═══ BILLING ═══ */}
        {tab === "billing" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100">
              <h3 className="text-lg font-semibold text-gray-900">
                Admit Billing
              </h3>
              <p className="text-sm text-gray-600 mt-1">
                Billing breakdown for admitted ICU patients.
              </p>
            </div>
            <div className="p-6">
              <div className="grid grid-cols-1 lg:grid-cols-3 gap-4 items-end">
                <div>
                  <label className="block text-sm font-medium text-gray-700">
                    Patient
                  </label>
                  <select
                    value={billingPatient}
                    onChange={(e) => setBillingPatient(e.target.value)}
                    className="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <option value="">Select patient</option>
                    {patients.map((p: any) => (
                      <option key={p.id ?? p.patient_name} value={p.id}>
                        {p.patient_name}
                      </option>
                    ))}
                  </select>
                </div>
                <div className="lg:col-span-2">
                  <div className="rounded-lg border border-gray-200 p-4 bg-gray-50">
                    <div className="text-xs text-gray-500">Room / Bed</div>
                    <div className="text-sm font-semibold text-gray-900">
                      {billingRoom}
                    </div>
                  </div>
                </div>
              </div>
              <div className="mt-6 overflow-x-auto rounded-lg border border-gray-200">
                <table className="min-w-full divide-y divide-gray-200">
                  <thead className="bg-gray-50">
                    <tr>
                      <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Date
                      </th>
                      <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Category
                      </th>
                      <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Description
                      </th>
                      <th className="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                        Qty
                      </th>
                      <th className="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                        Unit
                      </th>
                      <th className="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                        Amount
                      </th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {billingItems.length === 0 ? (
                      <tr>
                        <td
                          colSpan={6}
                          className="px-4 py-8 text-center text-sm text-gray-500"
                        >
                          {billingPatient
                            ? "No billing items"
                            : "Select a patient"}
                        </td>
                      </tr>
                    ) : (
                      billingItems.map((b: any, i: number) => (
                        <tr key={i}>
                          <td className="px-4 py-3 text-sm text-gray-600">
                            {b.date}
                          </td>
                          <td className="px-4 py-3 text-sm text-gray-600">
                            {b.category}
                          </td>
                          <td className="px-4 py-3 text-sm text-gray-900">
                            {b.description}
                          </td>
                          <td className="px-4 py-3 text-sm text-right text-gray-600">
                            {b.qty}
                          </td>
                          <td className="px-4 py-3 text-sm text-right text-gray-600">
                            ₱{Number(b.unit_price).toFixed(2)}
                          </td>
                          <td className="px-4 py-3 text-sm text-right font-medium text-gray-900">
                            ₱{Number(b.amount).toFixed(2)}
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

        {/* ═══ ADMISSION STATUS ═══ */}
        {tab === "admission" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-semibold text-gray-900">
                  Admission Status
                </h3>
                <p className="text-sm text-gray-600 mt-1">
                  Track if ICU patients are still admitted or already released.
                </p>
              </div>
              <button
                onClick={() => refetchAdmission()}
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
                      Bed
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Admitted
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Released
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {admissions.length === 0 ? (
                    <tr>
                      <td
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No records
                      </td>
                    </tr>
                  ) : (
                    admissions.map((a: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {a.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {a.bed}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={a.status} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {a.admitted}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {a.released ?? "-"}
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
