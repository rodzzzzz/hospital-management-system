import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import {
  Package,
  Pill,
  AlertTriangle,
  XCircle,
  Users,
  Bell,
  Send,
  Tv,
  ClipboardList,
  Clock,
  Plus,
  Search,
} from "lucide-react";
import { toast } from "sonner";
import { useModal } from "@/hooks/useModal";
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
interface Medicine {
  id: number;
  name: string;
  category: string;
  quantity: number;
  price: number;
  expiry_date: string;
  status: string;
}

export default function Pharmacy() {
  const sendModal = useModal();
  const reportModal = useModal();
  const [medSearch, setMedSearch] = useState("");

  /* ── data ── */
  const { data: statsData } = useQuery({
    queryKey: ["pharmacy-stats"],
    queryFn: async () => {
      const { data } = await client.get("/pharmacy/stats.php");
      return data;
    },
    retry: false,
  });

  const { data: queueData, refetch: refetchQueue } = useQuery({
    queryKey: ["queue-display", 3],
    queryFn: async () => {
      const { data } = await client.get("/queue/display/3");
      return data;
    },
  });

  useQueueWebSocket({ stationId: 3 });

  const { data: medsData } = useQuery({
    queryKey: ["pharmacy-medicines", medSearch],
    queryFn: async () => {
      const q = medSearch ? `?q=${encodeURIComponent(medSearch)}` : "";
      const { data } = await client.get(`/pharmacy/medicines.php${q}`);
      return data;
    },
  });

  const stats = statsData?.cards ?? {
    total_medicines: 0,
    receipts: 0,
    low_stock: 0,
    out_of_stock: 0,
  };
  const queueDisplay: DisplayQueueData = (queueData ?? {}) as DisplayQueueData;
  const currentlyServing = queueDisplay.currently_serving ?? null;
  const waitingQueue = queueDisplay.next_patients ?? [];
  const unavailable = queueDisplay.unavailable_patients ?? [];
  const medicines: Medicine[] = medsData?.ok ? (medsData.medicines ?? []) : [];

  /* ── mutations ── */
  const callNextMut = useMutation({
    mutationFn: async () => {
      if (currentlyServing) {
        throw new Error(
          "Please complete the current patient service before calling the next patient",
        );
      }

      const { data } = await client.post("/queue/call-next", {
        station_id: 3,
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
        station_id: 3,
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
    window.open("/queue-display/pharmacy", "_blank", "noopener,noreferrer");
  };

  return (
    <div>
      <div className="bg-white p-6 flex items-center justify-between shadow-sm">
        <h1 className="text-2xl font-semibold">Pharmacy Management</h1>
        <div className="relative">
          <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            value={medSearch}
            onChange={(e) => setMedSearch(e.target.value)}
            type="text"
            placeholder="Search medicines..."
            className="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
          />
        </div>
      </div>

      <div className="p-6 space-y-6">
        {/* Stats */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div className="bg-white rounded-2xl p-6 shadow-sm">
            <div className="flex justify-between items-start mb-4">
              <div className="bg-green-100 p-3 rounded-xl">
                <Package className="w-5 h-5 text-green-600" />
              </div>
            </div>
            <h3 className="text-3xl font-bold">{stats.total_medicines}</h3>
            <p className="text-gray-500 text-sm mt-1">Total Medicines</p>
          </div>
          <div className="bg-teal-700 text-white rounded-2xl p-6 shadow-sm">
            <div className="flex justify-between items-start mb-4">
              <div className="bg-teal-600/30 p-3 rounded-xl">
                <Pill className="w-5 h-5 text-white" />
              </div>
            </div>
            <h3 className="text-3xl font-bold">{stats.receipts}</h3>
            <p className="text-teal-100 text-sm mt-1">Patient Receipts</p>
          </div>
          <div className="bg-white rounded-2xl p-6 shadow-sm">
            <div className="flex justify-between items-start mb-4">
              <div className="bg-yellow-100 p-3 rounded-xl">
                <AlertTriangle className="w-5 h-5 text-yellow-600" />
              </div>
            </div>
            <h3 className="text-3xl font-bold">{stats.low_stock}</h3>
            <p className="text-gray-500 text-sm mt-1">Low Stock</p>
          </div>
          <div className="bg-white rounded-2xl p-6 shadow-sm">
            <div className="flex justify-between items-start mb-4">
              <div className="bg-red-100 p-3 rounded-xl">
                <XCircle className="w-5 h-5 text-red-600" />
              </div>
            </div>
            <h3 className="text-3xl font-bold">{stats.out_of_stock}</h3>
            <p className="text-gray-500 text-sm mt-1">Out of Stock</p>
          </div>
        </div>

        {/* Queue */}
        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex justify-between items-center mb-6">
            <h3 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
              <Users className="w-6 h-6 text-blue-600" /> Pharmacy Queue
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
              <ClipboardList className="w-4 h-4 text-blue-600" /> Waiting Queue
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

        {/* Medicines Table */}
        <div className="bg-white rounded-2xl shadow-sm">
          <div className="p-6 border-b border-gray-100 flex justify-between items-center">
            <div className="flex items-center gap-4">
              <h2 className="text-lg font-semibold">Medicines</h2>
              <span className="text-sm text-gray-500">
                Total {medicines.length} medicines
              </span>
            </div>
            <button className="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700">
              <Plus className="w-4 h-4" /> Add New Medicine
            </button>
          </div>
          <div className="overflow-x-auto">
            <table className="min-w-full">
              <thead>
                <tr className="bg-gray-50">
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Medicine
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Category
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Quantity
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Price
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Expiry Date
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {medicines.length === 0 ? (
                  <tr>
                    <td
                      colSpan={6}
                      className="px-6 py-8 text-center text-sm text-gray-500"
                    >
                      No medicines found
                    </td>
                  </tr>
                ) : (
                  medicines.map((m) => (
                    <tr key={m.id}>
                      <td className="px-6 py-4 text-sm font-medium text-gray-900">
                        {m.name}
                      </td>
                      <td className="px-6 py-4 text-sm text-gray-600">
                        {m.category}
                      </td>
                      <td className="px-6 py-4 text-sm text-gray-600">
                        {m.quantity}
                      </td>
                      <td className="px-6 py-4 text-sm text-gray-600">
                        ₱{Number(m.price).toFixed(2)}
                      </td>
                      <td className="px-6 py-4 text-sm text-gray-500">
                        {m.expiry_date}
                      </td>
                      <td className="px-6 py-4">
                        <StatusBadge status={m.status} />
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {/* Modals */}
      <SendPatientModal
        open={sendModal.open}
        onClose={sendModal.hide}
        currentlyServing={currentlyServing}
        currentStationId={3}
        onSuccess={refetchQueue}
      />

      <ReportWrongStationModal
        open={reportModal.open}
        onClose={reportModal.hide}
        stationId={3}
        onSuccess={refetchQueue}
      />

      <IncomingCorrectionAlert stationId={3} onCorrection={refetchQueue} />
    </div>
  );
}
