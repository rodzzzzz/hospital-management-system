import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  Tooltip,
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell,
  Legend,
} from "recharts";
import {
  DollarSign,
  Clock,
  Receipt,
  XCircle,
  Users,
  Bell,
  Send,
  Tv,
  ClipboardList,
  AlertTriangle,
  Search,
  Plus,
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

type CashierTab = "dashboard" | "charges" | "payments";
const TABS: { key: CashierTab; label: string }[] = [
  { key: "dashboard", label: "Dashboard" },
  { key: "charges", label: "Pending Charges" },
  { key: "payments", label: "Payments" },
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
interface Transaction {
  id: number;
  invoice_id: string;
  patient_name: string;
  amount: number;
  date: string;
  status: string;
}
interface Charge {
  id: number;
  charge_id: string;
  patient_name: string;
  source: string;
  total: number;
  status: string;
  created_at: string;
}
interface Payment {
  id: number;
  invoice_id: string;
  patient_name: string;
  total: number;
  paid: number;
  change: number;
  status: string;
  date: string;
}

const PIE_COLORS = ["#3B82F6", "#10B981", "#F59E0B", "#EF4444", "#8B5CF6"];

export default function Cashier() {
  const [tab, setTab] = useHashTab<CashierTab>("dashboard");
  const sendModal = useModal();
  const reportModal = useModal();
  const [chargeSearch, setChargeSearch] = useState("");
  const dChargeSearch = useDebounce(chargeSearch, 250);
  const [paymentSearch, setPaymentSearch] = useState("");
  const dPaymentSearch = useDebounce(paymentSearch, 250);

  /* ── data ── */
  const { data: statsData } = useQuery({
    queryKey: ["cashier-stats"],
    queryFn: async () => {
      const { data } = await client.get("/cashier/stats.php");
      return data;
    },
    retry: false,
  });

  const { data: queueData, refetch: refetchQueue } = useQuery({
    queryKey: ["queue-display", 4],
    queryFn: async () => {
      const { data } = await client.get("/queue/display/4");
      return data;
    },
    enabled: tab === "dashboard",
  });

  useQueueWebSocket({ stationId: 4, enabled: tab === "dashboard" });

  const { data: txData } = useQuery({
    queryKey: ["cashier-transactions"],
    queryFn: async () => {
      const { data } = await client.get("/cashier/transactions.php");
      return data;
    },
    enabled: tab === "dashboard",
  });

  const { data: chargesData } = useQuery({
    queryKey: ["cashier-charges", dChargeSearch],
    queryFn: async () => {
      const q = dChargeSearch ? `?q=${encodeURIComponent(dChargeSearch)}` : "";
      const { data } = await client.get(`/cashier/charges.php${q}`);
      return data;
    },
    enabled: tab === "charges",
  });

  const { data: paymentsData } = useQuery({
    queryKey: ["cashier-payments", dPaymentSearch],
    queryFn: async () => {
      const q = dPaymentSearch
        ? `?q=${encodeURIComponent(dPaymentSearch)}`
        : "";
      const { data } = await client.get(`/cashier/payments.php${q}`);
      return data;
    },
    enabled: tab === "payments",
  });

  const stats = statsData?.cards ?? {
    today_revenue: "₱0.00",
    pending_bills: 0,
    month_revenue: "₱0.00",
    voided_today: 0,
  };
  const queueDisplay: DisplayQueueData = (queueData ?? {}) as DisplayQueueData;
  const currentlyServing = queueDisplay.currently_serving ?? null;
  const waitingQueue = queueDisplay.next_patients ?? [];
  const unavailable = queueDisplay.unavailable_patients ?? [];
  const transactions: Transaction[] = txData?.ok
    ? (txData.transactions ?? [])
    : [];
  const charges: Charge[] = chargesData?.ok ? (chargesData.charges ?? []) : [];
  const payments: Payment[] = paymentsData?.ok
    ? (paymentsData.payments ?? [])
    : [];
  const hourlyData = (statsData?.charts?.hourly ?? []) as {
    hour: string;
    count: number;
  }[];
  const deptData = (statsData?.charts?.department ?? []) as {
    name: string;
    value: number;
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
        station_id: 4,
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
        station_id: 4,
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
    window.open("/queue-display/cashier", "_blank", "noopener,noreferrer");
  };

  return (
    <div>
      <div className="flex justify-between items-center mb-6 px-6 pt-6">
        <h1 className="text-2xl font-bold text-gray-900">
          Cashier &amp; Billing
        </h1>
        <button className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
          <Plus className="w-4 h-4" /> Create New Bill
        </button>
      </div>

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
        {/* ═══ DASHBOARD ═══ */}
        {tab === "dashboard" && (
          <div className="space-y-6">
            {/* Stats */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
              <StatCard
                icon={DollarSign}
                label="Today's Revenue"
                value={stats.today_revenue}
                iconBg="bg-green-100"
                iconColor="text-green-600"
              />
              <StatCard
                icon={Clock}
                label="Pending Bills"
                value={stats.pending_bills}
                iconBg="bg-yellow-100"
                iconColor="text-yellow-600"
              />
              <StatCard
                icon={Receipt}
                label="Month-to-Date Revenue"
                value={stats.month_revenue}
                iconBg="bg-blue-100"
                iconColor="text-blue-600"
              />
              <StatCard
                icon={XCircle}
                label="Voided (Today)"
                value={stats.voided_today}
                iconBg="bg-red-100"
                iconColor="text-red-600"
              />
            </div>

            {/* Queue */}
            <div className="bg-white rounded-lg shadow-sm p-6">
              <div className="flex justify-between items-center mb-6">
                <h3 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
                  <Users className="w-6 h-6 text-blue-600" /> Cashier Queue
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
                  <h4 className="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <Clock className="w-4 h-4 text-orange-600" /> Unavailable
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
              <ChartCard title="Hourly Transaction Volume">
                <ResponsiveContainer width="100%" height={240}>
                  <BarChart
                    data={
                      hourlyData.length
                        ? hourlyData
                        : [{ hour: "8am", count: 0 }]
                    }
                  >
                    <XAxis dataKey="hour" tick={{ fontSize: 11 }} />
                    <YAxis tick={{ fontSize: 11 }} />
                    <Tooltip />
                    <Bar dataKey="count" fill="#3B82F6" />
                  </BarChart>
                </ResponsiveContainer>
              </ChartCard>
              <ChartCard title="Revenue by Department">
                <ResponsiveContainer width="100%" height={240}>
                  <PieChart>
                    <Pie
                      data={
                        deptData.length ? deptData : [{ name: "N/A", value: 1 }]
                      }
                      cx="50%"
                      cy="50%"
                      outerRadius={80}
                      dataKey="value"
                      label
                    >
                      {(deptData.length
                        ? deptData
                        : [{ name: "N/A", value: 1 }]
                      ).map((_, idx) => (
                        <Cell
                          key={idx}
                          fill={PIE_COLORS[idx % PIE_COLORS.length]}
                        />
                      ))}
                    </Pie>
                    <Tooltip />
                    <Legend />
                  </PieChart>
                </ResponsiveContainer>
              </ChartCard>
            </div>

            {/* Recent Transactions */}
            <div className="bg-white rounded-lg shadow-sm overflow-hidden">
              <div className="p-6 border-b border-gray-100">
                <h3 className="text-lg font-semibold text-gray-900">
                  Recent Transactions
                </h3>
              </div>
              <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200">
                  <thead className="bg-gray-50">
                    <tr>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Invoice ID
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Patient
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
                    {transactions.length === 0 ? (
                      <tr>
                        <td
                          colSpan={5}
                          className="px-6 py-8 text-center text-sm text-gray-500"
                        >
                          No transactions
                        </td>
                      </tr>
                    ) : (
                      transactions.slice(0, 15).map((t) => (
                        <tr key={t.id}>
                          <td className="px-6 py-4 text-sm font-medium text-gray-900">
                            {t.invoice_id}
                          </td>
                          <td className="px-6 py-4 text-sm text-gray-600">
                            {t.patient_name}
                          </td>
                          <td className="px-6 py-4 text-sm text-gray-600">
                            ₱{Number(t.amount).toFixed(2)}
                          </td>
                          <td className="px-6 py-4 text-sm text-gray-500">
                            {t.date}
                          </td>
                          <td className="px-6 py-4">
                            <StatusBadge status={t.status} />
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

        {/* ═══ CHARGES ═══ */}
        {tab === "charges" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex justify-between items-center">
              <h3 className="text-lg font-semibold text-gray-900">
                Pending Charges
              </h3>
              <div className="relative">
                <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input
                  value={chargeSearch}
                  onChange={(e) => setChargeSearch(e.target.value)}
                  type="text"
                  placeholder="Search patient code or name..."
                  className="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Charge ID
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Patient
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Source
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Total
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Created
                    </th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {charges.length === 0 ? (
                    <tr>
                      <td
                        colSpan={7}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No pending charges
                      </td>
                    </tr>
                  ) : (
                    charges.map((c) => (
                      <tr key={c.id}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {c.charge_id}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {c.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {c.source}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          ₱{Number(c.total).toFixed(2)}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={c.status} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {c.created_at}
                        </td>
                        <td className="px-6 py-4 text-right">
                          <button className="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700">
                            Process
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* ═══ PAYMENTS ═══ */}
        {tab === "payments" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex justify-between items-center">
              <h3 className="text-lg font-semibold text-gray-900">Payments</h3>
              <div className="relative">
                <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input
                  value={paymentSearch}
                  onChange={(e) => setPaymentSearch(e.target.value)}
                  type="text"
                  placeholder="Search patient or method..."
                  className="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Invoice ID
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Patient
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Total
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Paid
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Change
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
                  {payments.length === 0 ? (
                    <tr>
                      <td
                        colSpan={7}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No payments
                      </td>
                    </tr>
                  ) : (
                    payments.map((p) => (
                      <tr key={p.id}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {p.invoice_id}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {p.patient_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          ₱{Number(p.total).toFixed(2)}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          ₱{Number(p.paid).toFixed(2)}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          ₱{Number(p.change).toFixed(2)}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={p.status} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {p.date}
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

      {/* Modals */}
      <SendPatientModal
        open={sendModal.open}
        onClose={sendModal.hide}
        currentlyServing={currentlyServing}
        currentStationId={4}
        onSuccess={refetchQueue}
      />

      <ReportWrongStationModal
        open={reportModal.open}
        onClose={reportModal.hide}
        stationId={4}
        onSuccess={refetchQueue}
      />

      <IncomingCorrectionAlert stationId={4} onCorrection={refetchQueue} />
    </div>
  );
}
