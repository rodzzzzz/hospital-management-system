import { useState } from "react";
import { useMutation, useQuery } from "@tanstack/react-query";
import { Send } from "lucide-react";
import { toast } from "sonner";
import { Modal } from "@/components/ui/Modal";
import client from "@/api/client";

interface QueueStation {
  id: number;
  station_name: string;
  station_display_name: string;
}

interface QueueItem {
  id: number;
  queue_number: number;
  patient_name?: string;
  full_name?: string;
  patient_code?: string;
}

interface SendPatientModalProps {
  open: boolean;
  onClose: () => void;
  currentlyServing: QueueItem | null;
  currentStationId: number;
  onSuccess: () => void;
}

export function SendPatientModal({
  open,
  onClose,
  currentlyServing,
  currentStationId,
  onSuccess,
}: SendPatientModalProps) {
  const [selectedStation, setSelectedStation] = useState("");

  const { data: stationsData } = useQuery({
    queryKey: ["queue-stations"],
    queryFn: async () => {
      const { data } = await client.get("/queue/stations");
      return data;
    },
    enabled: open,
    staleTime: 60000,
  });

  const sendPatientMut = useMutation({
    mutationFn: async (station: string) => {
      if (!currentlyServing) {
        throw new Error("Please call a patient first before sending to next station");
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
      onClose();
      onSuccess();
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const stations: QueueStation[] = (
    (stationsData?.stations ?? []) as QueueStation[]
  ).filter((s) => s.id !== currentStationId);

  const handleClose = () => {
    setSelectedStation("");
    onClose();
  };

  return (
    <Modal
      open={open}
      onClose={handleClose}
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
          onClick={handleClose}
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
  );
}
