import { useEffect, useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { AlertTriangle, CheckCircle, MessageSquare } from "lucide-react";
import { toast } from "sonner";
import { useCorrectionWebSocket } from "@/hooks/useQueueWebSocket";
import client from "@/api/client";

interface QueueCorrection {
  id: number;
  queue_number: string;
  patient_id: number;
  full_name: string;
  patient_code: string;
  wrong_station_id: number;
  wrong_station_name: string;
  correct_station_id: number;
  correct_station_name: string;
  notes?: string;
  reported_by_name: string;
  reported_at: string;
}

interface IncomingCorrectionAlertProps {
  stationId: number;
  onCorrection?: () => void;
}

export function IncomingCorrectionAlert({
  stationId,
  onCorrection,
}: IncomingCorrectionAlertProps) {
  const [hasPlayedSound, setHasPlayedSound] = useState(false);

  const { data: correctionsData } = useQuery({
    queryKey: ["pending-corrections", stationId],
    queryFn: async () => {
      const { data } = await client.get(
        `/queue/pending-corrections/${stationId}`,
      );
      return data;
    },
    retry: false,
  });

  useCorrectionWebSocket(stationId);

  const corrections: QueueCorrection[] =
    correctionsData?.corrections ?? ([] as QueueCorrection[]);
  const hasCorrections = corrections.length > 0;

  // Play alert sound when corrections appear
  useEffect(() => {
    if (hasCorrections && !hasPlayedSound) {
      playAlertSound();
      setHasPlayedSound(true);
    } else if (!hasCorrections) {
      setHasPlayedSound(false);
    }
  }, [hasCorrections, hasPlayedSound]);

  const confirmMutation = useMutation({
    mutationFn: async (errorLogId: number) => {
      const { data } = await client.post("/queue/confirm-correction", {
        error_log_id: errorLogId,
      });
      if (!data.success) {
        throw new Error(data.message ?? "Failed to confirm correction");
      }
      return data;
    },
    onSuccess: (data) => {
      toast.success(
        `Patient redirected to ${data.result?.correct_station_name || "correct station"}`,
      );
      if (onCorrection) {
        onCorrection();
      }
    },
    onError: (e: Error) => {
      toast.error(e.message);
    },
  });

  const playAlertSound = () => {
    try {
      const ctx = new (
        window.AudioContext || (window as any).webkitAudioContext
      )();
      // Play two-tone alert
      [440, 880].forEach((freq, i) => {
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.value = freq;
        osc.type = "sine";
        gain.gain.value = 0.3;
        osc.start(ctx.currentTime + i * 0.3);
        osc.stop(ctx.currentTime + i * 0.3 + 0.25);
      });
    } catch (e) {
      // Audio context not supported
    }
  };

  if (!hasCorrections) {
    return null;
  }

  return (
    <div
      className="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-[9999] animate-in fade-in duration-300"
      style={{ zIndex: 9999 }}
    >
      <div className="max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        {corrections.map((correction) => {
          const reportedTime = new Date(
            correction.reported_at,
          ).toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
          });

          return (
            <div
              key={correction.id}
              className="bg-white rounded-2xl shadow-2xl p-8 border-4 border-red-500 animate-in slide-in-from-top duration-500"
            >
              {/* Alert Header */}
              <div className="text-center mb-6">
                <div className="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4 animate-pulse">
                  <AlertTriangle className="text-red-600 w-10 h-10" />
                </div>
                <h2 className="text-3xl font-black text-red-700">
                  WRONG STATION ALERT
                </h2>
                <p className="text-lg text-gray-600 mt-2">
                  A patient was sent here by mistake
                </p>
              </div>

              {/* Reason (if provided) */}
              {correction.notes && (
                <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                  <div className="flex items-center gap-2 text-sm font-bold text-yellow-700 mb-1">
                    <MessageSquare className="w-4 h-4" />
                    <span>Reason from reporting staff:</span>
                  </div>
                  <div className="text-lg text-gray-800">
                    {correction.notes}
                  </div>
                </div>
              )}

              {/* Patient Info */}
              <div className="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
                <div className="flex items-center gap-4 w-full">
                  <div className="w-20 h-20 bg-red-600 text-white rounded-xl flex items-center justify-center shrink-0">
                    <span className="text-3xl font-bold">
                      {correction.queue_number || "?"}
                    </span>
                  </div>
                  <div className="flex-1">
                    <div className="text-3xl font-black text-gray-900">
                      {correction.full_name || "Unknown Patient"}
                    </div>
                    <div className="text-lg text-gray-600">
                      {correction.patient_code || ""}
                    </div>
                  </div>
                </div>
              </div>

              {/* Correct Station Info */}
              <div className="mb-6">
                <div className="flex items-center justify-center gap-3 text-2xl flex-wrap">
                  <span className="text-red-700 font-bold">Should go to:</span>
                  <span className="px-4 py-2 bg-red-600 text-white rounded-lg font-black text-2xl">
                    {correction.correct_station_name || "?"}
                  </span>
                </div>
                <div className="text-center mt-3 text-sm text-gray-500">
                  Reported by: {correction.reported_by_name || "Staff"} at{" "}
                  {reportedTime}
                </div>
              </div>

              {/* Confirm Button */}
              <div className="text-center">
                <button
                  onClick={() => confirmMutation.mutate(correction.id)}
                  disabled={confirmMutation.isPending}
                  className="px-10 py-5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-xl font-black transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-3 mx-auto"
                >
                  {confirmMutation.isPending ? (
                    <>
                      <div className="w-6 h-6 border-4 border-white border-t-transparent rounded-full animate-spin" />
                      <span>Processing...</span>
                    </>
                  ) : (
                    <>
                      <CheckCircle className="w-6 h-6" />
                      <span>Confirm &amp; Redirect Patient</span>
                    </>
                  )}
                </button>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
