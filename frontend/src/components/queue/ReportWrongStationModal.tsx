import { useState, useEffect } from "react";
import { useMutation, useQuery } from "@tanstack/react-query";
import { toast } from "sonner";
import {
  Search,
  TriangleAlert,
  X,
  ArrowLeft,
  AlertCircle,
  Route,
} from "lucide-react";
import client from "@/api/client";

interface QueueStation {
  id: number;
  station_name: string;
  station_display_name: string;
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

interface ReportWrongStationModalProps {
  open: boolean;
  onClose: () => void;
  stationId: number;
  onSuccess: () => void;
}

export function ReportWrongStationModal({
  open,
  onClose,
  stationId,
  onSuccess,
}: ReportWrongStationModalProps) {
  const [reportStep, setReportStep] = useState<1 | 2>(1);
  const [transferSearch, setTransferSearch] = useState("");
  const [reportReason, setReportReason] = useState("");
  const [selectedTransfer, setSelectedTransfer] =
    useState<RecentTransfer | null>(null);
  const [selectedCorrectStationId, setSelectedCorrectStationId] = useState<
    number | null
  >(null);

  const { data: recentTransfersData, refetch: refetchRecentTransfers } =
    useQuery({
      queryKey: ["recent-transfers", stationId],
      queryFn: async () => {
        const { data } = await client.get(
          `/queue/recent-transfers/${stationId}`,
        );
        return data;
      },
      enabled: open && reportStep === 1,
    });

  const { data: stationsData } = useQuery({
    queryKey: ["queue-stations"],
    queryFn: async () => {
      const { data } = await client.get("/queue/stations");
      return data;
    },
    enabled: open,
    staleTime: 60000,
  });

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

  const stations: QueueStation[] = (stationsData?.stations ??
    []) as QueueStation[];

  const selectedWrongStationId =
    selectedTransfer?.current_station_id ??
    selectedTransfer?.to_station_id ??
    null;

  const correctionStations = stations.filter(
    (s) => s.id !== stationId && s.id !== selectedWrongStationId,
  );

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
      handleClose();
      onSuccess();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const selectTransferForCorrection = (transfer: RecentTransfer) => {
    if (transfer.current_status === "completed") return;
    setSelectedTransfer(transfer);
    setSelectedCorrectStationId(null);
    setReportReason("");
    setReportStep(2);
  };

  const handleClose = () => {
    setReportStep(1);
    setTransferSearch("");
    setReportReason("");
    setSelectedTransfer(null);
    setSelectedCorrectStationId(null);
    onClose();
  };

  useEffect(() => {
    if (open) {
      void refetchRecentTransfers();
    }
  }, [open, refetchRecentTransfers]);

  if (!open) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[70]">
      <div className="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] mx-4 flex flex-col">
        {/* Header */}
        <div className="p-6 border-b border-gray-100 flex items-center justify-between shrink-0">
          <h3 className="text-3xl font-bold text-red-700 flex items-center">
            <TriangleAlert className="mr-3 w-8 h-8" />
            Report Wrong Station
          </h3>
          <button
            type="button"
            className="text-gray-400 hover:text-gray-600 text-2xl p-2"
            onClick={handleClose}
          >
            <X className="w-6 h-6" />
          </button>
        </div>

        {/* Body */}
        <div className="p-6 flex-1 overflow-y-auto">
          {reportStep === 1 ? (
            <div>
              <label className="block text-xl font-bold text-gray-700 mb-4">
                Select the wrongly transferred patient:
              </label>
              <div className="mb-4">
                <div className="relative">
                  <input
                    type="text"
                    value={transferSearch}
                    onChange={(e) => setTransferSearch(e.target.value)}
                    placeholder="Search patient name or code..."
                    className="w-full px-4 py-3 pl-12 text-lg border-2 border-gray-200 rounded-lg focus:border-red-400 focus:ring-red-400 focus:outline-none"
                  />
                  <Search className="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
                </div>
              </div>
              <div className="space-y-3 max-h-[42rem] overflow-y-auto pr-2">
                {filteredRecentTransfers.length === 0 ? (
                  <div className="text-center py-8 text-gray-500">
                    <p>No recent transfers found</p>
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
                            <div className="text-lg font-bold text-gray-900">
                              {t.full_name ?? "Unknown"}
                            </div>
                            <div className="text-base text-gray-500">
                              {t.patient_code ?? ""} • Queue #
                              {t.queue_number ?? "?"}
                            </div>
                          </div>
                          <div className="text-right text-base">
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
            <div>
              <div className="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div className="flex items-center gap-4 mb-3">
                  <TriangleAlert className="text-red-500 w-8 h-8" />
                  <div>
                    <div className="text-xl font-bold text-red-800">
                      {selectedTransfer?.full_name ?? "Unknown Patient"}
                    </div>
                    <div className="text-base text-red-600">
                      {selectedTransfer?.current_station_name
                        ? `Currently at: ${selectedTransfer.current_station_name}`
                        : `Was wrongly sent to: ${selectedTransfer?.to_station_name ?? "-"}`}
                    </div>
                  </div>
                </div>

                {selectedTransfer?.journey &&
                  selectedTransfer.journey.length > 0 && (
                    <div className="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                      <div className="flex items-center gap-2 mb-2">
                        <Route className="text-blue-600 w-5 h-5" />
                        <span className="text-lg font-bold text-blue-800">
                          Patient Journey
                        </span>
                      </div>
                      <div className="text-base text-gray-700 font-medium">
                        {[
                          selectedTransfer.to_station_name,
                          ...selectedTransfer.journey.map(
                            (j) => j.station_name,
                          ),
                        ]
                          .filter(Boolean)
                          .join(" → ")}
                      </div>
                    </div>
                  )}
              </div>

              <div className="mb-6">
                <label
                  htmlFor="reasonField"
                  className="block text-xl font-bold text-gray-700 mb-2"
                >
                  Reason for correction:
                </label>
                <textarea
                  id="reasonField"
                  rows={3}
                  value={reportReason}
                  onChange={(e) => setReportReason(e.target.value)}
                  placeholder="Explain why this patient needs to be moved..."
                  className="w-full border-2 border-gray-200 rounded-lg p-4 text-lg focus:border-red-400 focus:ring-red-400 focus:outline-none"
                />
              </div>

              <label className="block text-xl font-bold text-gray-700 mb-4">
                Select the CORRECT station:
              </label>
              <div className="space-y-3 max-h-[28rem] overflow-y-auto pr-2">
                {correctionStations.map((s) => (
                  <button
                    key={s.id}
                    type="button"
                    onClick={() => setSelectedCorrectStationId(s.id)}
                    className={`w-full text-left p-4 border-2 rounded-lg transition-colors ${
                      selectedCorrectStationId === s.id
                        ? "border-blue-500 bg-blue-50"
                        : "border-gray-200 hover:bg-gray-50"
                    }`}
                  >
                    <div className="text-lg font-semibold text-gray-900">
                      {s.station_display_name}
                    </div>
                  </button>
                ))}
              </div>
            </div>
          )}
        </div>

        {/* Footer */}
        <div className="p-6 bg-gray-50 border-t flex justify-between items-center shrink-0">
          {reportStep === 2 && (
            <button
              type="button"
              onClick={() => setReportStep(1)}
              className="px-6 py-3 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold"
            >
              <ArrowLeft className="inline w-5 h-5 mr-2" /> Back
            </button>
          )}
          <div className="flex gap-4 ml-auto">
            <button
              type="button"
              onClick={handleClose}
              className="px-6 py-3 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold"
            >
              Cancel
            </button>
            {reportStep === 2 && (
              <button
                type="button"
                onClick={() => reportWrongStationMut.mutate()}
                disabled={
                  !selectedCorrectStationId || reportWrongStationMut.isPending
                }
                className="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 text-lg font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed"
              >
                <AlertCircle className="inline w-5 h-5 mr-2" />
                {reportWrongStationMut.isPending
                  ? "Reporting..."
                  : "Report Error"}
              </button>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
