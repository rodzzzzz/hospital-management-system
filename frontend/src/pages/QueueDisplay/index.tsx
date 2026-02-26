import { useState, useEffect, useRef, useCallback } from "react";
import { useParams } from "react-router-dom";
import { useQuery, useQueryClient } from "@tanstack/react-query";
import axios from "axios";
import { useQueueWebSocket } from "@/hooks/useQueueWebSocket";

const STATION_COLORS: Record<
  string,
  { serving: string; next: string; doctors: string; waiting: string }
> = {
  opd: {
    serving: "bg-green-100 border-green-300 text-green-600",
    next: "bg-blue-50 border-blue-300",
    doctors: "bg-purple-50 border-purple-300",
    waiting: "bg-gray-100 border-gray-300",
  },
  doctor: {
    serving: "bg-green-100 border-green-300 text-green-600",
    next: "bg-emerald-50 border-emerald-300",
    doctors: "bg-purple-50 border-purple-300",
    waiting: "bg-gray-100 border-gray-300",
  },
  pharmacy: {
    serving: "bg-yellow-100 border-yellow-300 text-yellow-600",
    next: "bg-amber-50 border-amber-300",
    doctors: "bg-purple-50 border-purple-300",
    waiting: "bg-gray-100 border-gray-300",
  },
  cashier: {
    serving: "bg-red-100 border-red-300 text-red-600",
    next: "bg-rose-50 border-rose-300",
    doctors: "bg-purple-50 border-purple-300",
    waiting: "bg-gray-100 border-gray-300",
  },
  xray: {
    serving: "bg-cyan-100 border-cyan-300 text-cyan-600",
    next: "bg-sky-50 border-sky-300",
    doctors: "bg-purple-50 border-purple-300",
    waiting: "bg-gray-100 border-gray-300",
  },
  lab: {
    serving: "bg-teal-100 border-teal-300 text-teal-600",
    next: "bg-emerald-50 border-emerald-300",
    doctors: "bg-purple-50 border-purple-300",
    waiting: "bg-gray-100 border-gray-300",
  },
};

function playNotificationSound() {
  try {
    const audio = new Audio(
      "data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZURE",
    );
    audio.volume = 0.3;
    audio.play().catch(() => {});
  } catch (_) {}
}

function selectFemaleVoice(
  utterance: SpeechSynthesisUtterance,
  voices: SpeechSynthesisVoice[],
) {
  const femaleNames = [
    "Microsoft Zira",
    "Google US English Female",
    "Samantha",
    "Victoria",
    "Karen",
    "Moira",
    "Tessa",
    "female",
    "woman",
  ];
  for (const fn of femaleNames) {
    const v = voices.find(
      (v) =>
        v.lang.startsWith("en") &&
        v.name.toLowerCase().includes(fn.toLowerCase()),
    );
    if (v) {
      utterance.voice = v;
      return;
    }
  }
  const eng = voices.find((v) => v.lang.startsWith("en"));
  if (eng) {
    utterance.voice = eng;
    utterance.pitch = 1.5;
  }
}

const api = axios.create({ baseURL: "/api" });

export default function QueueDisplay() {
  const { station } = useParams<{ station: string }>();
  const qc = useQueryClient();
  const [time, setTime] = useState(new Date());
  const prevServingRef = useRef<string | null>(null);
  const prevQueueCountRef = useRef<number>(0);
  const [confirmedCorrections, setConfirmedCorrections] = useState<any[]>([]);
  const correctionAnnouncedIds = useRef<Set<number>>(new Set());
  const correctionDismissTimers = useRef<
    Record<string, ReturnType<typeof setTimeout>>
  >({});
  const carouselRef = useRef<HTMLDivElement>(null);
  const carouselActive = useRef(false);
  const carouselToken = useRef(0);
  const carouselTimeout = useRef<ReturnType<typeof setTimeout> | null>(null);

  // Clock
  useEffect(() => {
    const t = setInterval(() => setTime(new Date()), 1000);
    return () => clearInterval(t);
  }, []);

  // Get station ID from name
  const { data: stationsData } = useQuery({
    queryKey: ["display-stations"],
    queryFn: async () => {
      const { data } = await api.get("/queue/stations");
      return data;
    },
    staleTime: 60000,
  });
  const stations: any[] = stationsData?.stations ?? [];
  const stationObj = stations.find((s: any) => s.station_name === station);
  const stationId = stationObj?.id;

  // Fetch display data
  const { data: displayData, refetch } = useQuery({
    queryKey: ["queue-display", stationId],
    queryFn: async () => {
      const { data } = await api.get(`/queue/display/${stationId}`);
      return data;
    },
    enabled: !!stationId,
  });

  useQueueWebSocket({ stationId: stationId ?? undefined });

  // Fetch doctors
  const { data: doctorsResp } = useQuery({
    queryKey: ["display-doctors"],
    queryFn: async () => {
      const { data } = await api.get("/doctor/list.php");
      return data;
    },
  });
  const doctors: any[] = (doctorsResp?.ok ? doctorsResp.doctors : [])
    .filter((d: any) => d.status === "available")
    .sort((a: any, b: any) =>
      (a.full_name ?? "").localeCompare(b.full_name ?? ""),
    );

  // TTS announcement when serving changes (with onvoiceschanged fallback)
  const announce = useCallback(
    (name: string) => {
      if (!("speechSynthesis" in window)) return;
      window.speechSynthesis.cancel();
      const stationLabel =
        stationObj?.station_display_name ?? station ?? "the station";
      const utterance = new SpeechSynthesisUtterance(
        `Patient ${name}, please proceed to ${stationLabel}`,
      );
      utterance.lang = "en-US";
      utterance.rate = 0.9;
      utterance.pitch = 1.2;
      utterance.volume = 1;
      const voices = window.speechSynthesis.getVoices();
      if (voices.length === 0) {
        window.speechSynthesis.onvoiceschanged = () => {
          const v2 = window.speechSynthesis.getVoices();
          selectFemaleVoice(utterance, v2);
          window.speechSynthesis.speak(utterance);
        };
      } else {
        selectFemaleVoice(utterance, voices);
        window.speechSynthesis.speak(utterance);
      }
    },
    [station, stationObj],
  );

  // QEC TTS announcement
  const announceCorrection = useCallback(
    (patientName: string, stationName: string) => {
      if (!("speechSynthesis" in window)) return;
      window.speechSynthesis.cancel();
      const text = `Attention. ${patientName}, please proceed to ${stationName}. ${patientName}, please proceed to ${stationName}.`;
      const utterance = new SpeechSynthesisUtterance(text);
      utterance.rate = 0.85;
      utterance.pitch = 1.0;
      utterance.volume = 1.0;
      utterance.lang = "en-US";
      setTimeout(() => window.speechSynthesis.speak(utterance), 200);
    },
    [],
  );

  useEffect(() => {
    const currentName = displayData?.currently_serving?.full_name ?? null;
    if (currentName && currentName !== prevServingRef.current) {
      announce(currentName);
    }
    prevServingRef.current = currentName;
  }, [displayData?.currently_serving?.full_name, announce]);

  // Notification sound when queue count increases
  useEffect(() => {
    const queueCount = displayData?.queue_count ?? 0;
    if (
      queueCount > prevQueueCountRef.current &&
      prevQueueCountRef.current > 0
    ) {
      playNotificationSound();
    }
    prevQueueCountRef.current = queueCount;
  }, [displayData?.queue_count]);

  // Keyboard shortcut 'R' to refresh
  useEffect(() => {
    const handler = (e: KeyboardEvent) => {
      if (e.key === "r" || e.key === "R") {
        refetch();
        qc.invalidateQueries({ queryKey: ["display-doctors"] });
      }
    };
    document.addEventListener("keydown", handler);
    return () => document.removeEventListener("keydown", handler);
  }, [refetch, qc]);

  // Fullscreen on double-click
  useEffect(() => {
    const handler = () => {
      if (!document.fullscreenElement)
        document.documentElement.requestFullscreen?.();
      else document.exitFullscreen?.();
    };
    document.addEventListener("dblclick", handler);
    return () => document.removeEventListener("dblclick", handler);
  }, []);

  // Poll confirmed corrections for QEC display overlay
  useEffect(() => {
    if (!stationId) return;
    let cancelled = false;
    const poll = async () => {
      try {
        const { data } = await api.get(
          `/queue/confirmed-corrections/${stationId}`,
        );
        if (!cancelled && data.success && data.corrections?.length > 0) {
          for (const c of data.corrections) {
            if (!correctionAnnouncedIds.current.has(c.id)) {
              correctionAnnouncedIds.current.add(c.id);
              setConfirmedCorrections((prev) => [...prev, c]);
              announceCorrection(
                c.full_name ?? "Patient",
                c.correct_station_name ?? "the correct station",
              );
              // Auto-dismiss after 20 seconds
              const timerId = setTimeout(() => {
                setConfirmedCorrections((prev) =>
                  prev.filter((x) => x.id !== c.id),
                );
              }, 20000);
              correctionDismissTimers.current[c.id] = timerId;
            }
          }
        }
      } catch {}
    };
    poll();
    const iv = setInterval(poll, 5000);
    return () => {
      cancelled = true;
      clearInterval(iv);
      Object.values(correctionDismissTimers.current).forEach(clearTimeout);
    };
  }, [stationId, announceCorrection]);

  // Doctors carousel auto-scroll
  const stopCarousel = useCallback(() => {
    carouselActive.current = false;
    carouselToken.current += 1;
    if (carouselTimeout.current) {
      clearTimeout(carouselTimeout.current);
      carouselTimeout.current = null;
    }
  }, []);

  const startCarousel = useCallback(() => {
    stopCarousel();
    const listInner = carouselRef.current;
    if (!listInner || listInner.children.length <= 1) return;
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

    listInner.style.transition = "none";
    listInner.style.transform = "translateY(0)";
    carouselActive.current = true;
    const token = carouselToken.current;

    const advance = () => {
      if (!carouselActive.current || token !== carouselToken.current) return;
      if (!listInner || listInner.children.length <= 1) {
        stopCarousel();
        return;
      }

      const firstItem = listInner.firstElementChild as HTMLElement;
      if (!firstItem) {
        carouselTimeout.current = setTimeout(advance, 600);
        return;
      }
      const itemH = firstItem.getBoundingClientRect().height;
      const gap = parseFloat(window.getComputedStyle(listInner).gap) || 0;
      const step = itemH + gap;

      listInner.style.transition = "transform 2s linear";
      listInner.style.transform = `translateY(-${step}px)`;

      const onEnd = () => {
        if (!carouselActive.current || token !== carouselToken.current) return;
        listInner.style.transition = "none";
        listInner.style.transform = "translateY(0)";
        if (listInner.firstElementChild)
          listInner.appendChild(listInner.firstElementChild);
        void listInner.offsetHeight; // force reflow
        carouselTimeout.current = setTimeout(advance, 0);
      };
      listInner.addEventListener("transitionend", onEnd, { once: true });
    };
    carouselTimeout.current = setTimeout(advance, 800);
  }, [stopCarousel]);

  // Restart carousel when doctors change
  useEffect(() => {
    if (doctors.length > 1) {
      // Small delay to let DOM render
      const t = setTimeout(() => startCarousel(), 100);
      return () => {
        clearTimeout(t);
        stopCarousel();
      };
    } else {
      stopCarousel();
    }
  }, [doctors, startCarousel, stopCarousel]);

  const colors = STATION_COLORS[station ?? ""] ?? STATION_COLORS.opd;
  const currentlyServing = displayData?.currently_serving;
  const nextPatients = displayData?.next_patients ?? [];
  const nextThree = nextPatients.slice(0, 2);
  const remaining = nextPatients.slice(2);

  const timeStr = time.toLocaleTimeString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: false,
  });
  const dateStr = time.toLocaleDateString("en-US", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });

  return (
    <div
      className="h-screen w-screen flex flex-col overflow-hidden bg-[#f8fafc]"
      style={{ fontFamily: "'Inter', sans-serif" }}
    >
      {/* Header */}
      <div className="bg-white/95 backdrop-blur-sm shadow-[0_2px_20px_rgba(0,0,0,0.08)] px-8 py-6 flex justify-between items-center">
        <div className="flex items-center gap-4">
          <img
            src="/hospital/resources/logo.png"
            alt="Logo"
            className="w-20 h-20 object-contain"
            onError={(e) => {
              (e.target as HTMLImageElement).style.display = "none";
            }}
          />
          <div>
            <h1 className="text-4xl font-extrabold text-gray-900">
              {stationObj?.station_display_name ?? station ?? "Queue Display"}
            </h1>
            <p className="text-lg text-gray-500">
              Patient Queue Management System
            </p>
          </div>
        </div>
        <div className="text-right">
          <div className="text-5xl font-semibold text-gray-900 tabular-nums">
            {timeStr}
          </div>
          <div className="text-base text-gray-500">{dateStr}</div>
        </div>
      </div>

      {/* Queue Section */}
      <div
        className="flex-1 flex gap-8 p-8"
        style={{ height: "calc(100vh - 180px)" }}
      >
        {/* Left Column */}
        <div className="flex-1 flex flex-col gap-6">
          {/* Currently Serving */}
          <div
            className={`flex-1 rounded-2xl border flex flex-col items-center justify-center text-center p-10 shadow-[0_4px_20px_rgba(0,0,0,0.08)] ${colors.serving}`}
          >
            <h2 className="text-2xl font-semibold text-gray-700 mb-4">
              Now Serving
            </h2>
            {currentlyServing ? (
              <div>
                <div className="relative w-fit mx-auto">
                  <div className="absolute inset-0 bg-green-400 rounded-full w-20 h-20 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 animate-ping opacity-30" />
                  <div className="text-[8rem] font-extrabold leading-none relative">
                    {currentlyServing.queue_number}
                  </div>
                </div>
                <div className="text-[2.75rem] font-semibold text-gray-900 mt-4 line-clamp-1">
                  {currentlyServing.full_name}
                </div>
              </div>
            ) : (
              <div>
                <div className="text-[8rem] font-extrabold leading-none text-gray-300">
                  ---
                </div>
                <div className="text-[2.75rem] font-semibold text-gray-400 mt-4">
                  No patient being served
                </div>
              </div>
            )}
          </div>

          {/* Next in Queue */}
          <div
            className={`flex-1 rounded-2xl border p-6 shadow-[0_4px_20px_rgba(0,0,0,0.08)] flex flex-col ${colors.next}`}
          >
            <h2 className="text-2xl font-semibold text-gray-800 mb-6">
              Next in Queue
            </h2>
            <div className="flex-1 flex flex-col gap-4">
              {nextThree.length === 0 ? (
                <div className="text-center text-gray-400 py-8">
                  No patients in queue
                </div>
              ) : (
                nextThree.map((p: any) => (
                  <div
                    key={p.id}
                    className="flex items-center p-6 bg-blue-100 border border-blue-300 rounded-lg"
                  >
                    <div className="text-5xl font-bold min-w-[60px] text-center">
                      {p.queue_number}
                    </div>
                    <div className="flex-1 ml-6 flex justify-between items-center">
                      <div className="text-xl font-semibold text-gray-900 line-clamp-1">
                        {p.full_name}
                      </div>
                    </div>
                  </div>
                ))
              )}
            </div>
          </div>
        </div>

        {/* Right Column */}
        <div className="flex-1 flex flex-col gap-6">
          {/* Available Doctors */}
          <div
            className={`flex-1 rounded-2xl border p-4 shadow-[0_4px_20px_rgba(0,0,0,0.08)] flex flex-col ${colors.doctors}`}
          >
            <h2 className="text-xl font-semibold text-gray-800 mb-3">
              Available Doctors
            </h2>
            <div
              className="flex-1 overflow-hidden relative"
              style={{ maxHeight: 300 }}
            >
              {doctors.length === 0 ? (
                <div className="text-center text-gray-400 py-6">
                  No doctors available
                </div>
              ) : (
                <div
                  ref={carouselRef}
                  className="flex flex-col gap-3"
                  style={{ willChange: "transform" }}
                >
                  {doctors.map((doc: any, i: number) => (
                    <div
                      key={doc.id ?? i}
                      className="flex items-center px-4 py-3 bg-[#f8fafc] rounded-lg transition-all hover:translate-x-1 hover:shadow-md"
                    >
                      <div className="w-4 h-4 rounded-full bg-green-500 shadow-[0_0_0_3px_rgba(16,185,129,0.2)] mr-4 relative">
                        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-2 h-2 bg-white rounded-full" />
                      </div>
                      <div className="flex-1 text-[1.375rem] font-medium text-gray-900">
                        {doc.full_name}
                      </div>
                      <div className="text-[1.0625rem] font-medium text-green-600 ml-2">
                        Available
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>

          {/* Waiting List */}
          <div
            className={`flex-1 rounded-2xl border p-6 shadow-[0_4px_20px_rgba(0,0,0,0.08)] flex flex-col relative overflow-hidden ${colors.waiting}`}
          >
            <h2 className="text-2xl font-semibold text-gray-800 mb-6">
              Waiting List
            </h2>
            <div className="flex-1 flex flex-col gap-4 overflow-hidden">
              {remaining.length === 0 ? (
                <div className="text-center text-gray-400 py-4">
                  No additional patients waiting
                </div>
              ) : (
                remaining.map((p: any) => (
                  <div
                    key={p.id}
                    className="flex items-center p-6 bg-gray-50 border border-gray-300 rounded-lg"
                  >
                    <div className="text-5xl font-bold min-w-[60px] text-center">
                      {p.queue_number}
                    </div>
                    <div className="flex-1 ml-6 flex justify-between items-center">
                      <div className="text-xl font-semibold text-gray-900 line-clamp-1">
                        {p.full_name}
                      </div>
                    </div>
                  </div>
                ))
              )}
            </div>
            <div className="absolute bottom-0 left-0 right-0 h-36 bg-linear-to-b from-transparent to-white pointer-events-none z-10" />
          </div>
        </div>
      </div>

      {/* Footer */}
      <div className="bg-white/95 backdrop-blur-sm shadow-[0_-2px_20px_rgba(0,0,0,0.08)] px-8 py-4 flex justify-between items-center">
        <div className="text-xl font-bold text-gray-900">
          DRBMJRAH MEMORIAL HOSPITAL
        </div>
        <div className="text-gray-600">
          📞 +63917 513 9979 <span className="mx-4">|</span> 📍 Brgy. Mabul,
          Malabang, Lanao Del Sur, BARMM 9300
        </div>
      </div>

      {/* QEC Display Overlay — confirmed corrections */}
      {confirmedCorrections.length > 0 && (
        <div
          className="fixed inset-0 z-100 pointer-events-none"
          style={{
            animation: "qecPulse 2s ease-in-out infinite",
            boxShadow: "inset 0 0 80px 40px rgba(220,38,38,0.6)",
          }}
        >
          <style>{`@keyframes qecPulse { 0%,100% { box-shadow: inset 0 0 80px 40px rgba(220,38,38,0.6); } 50% { box-shadow: inset 0 0 100px 50px rgba(220,38,38,0.15); } } @keyframes qecSlideIn { from { transform: translateY(-40px) scale(0.95); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }`}</style>
          <div className="absolute inset-0 flex items-center justify-center p-8">
            <div className="w-full max-w-5xl space-y-6">
              {confirmedCorrections.map((c: any) => (
                <div
                  key={c.id}
                  className="bg-white rounded-2xl shadow-2xl p-8 border-4 border-red-500 pointer-events-auto"
                  style={{ animation: "qecSlideIn 0.6s ease-out" }}
                >
                  <div className="text-center">
                    <div
                      className="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-4"
                      style={{ animation: "pulse 1s ease-in-out infinite" }}
                    >
                      <span className="text-red-600 text-5xl">⚠</span>
                    </div>
                    <h2 className="text-4xl font-black text-red-700 mb-3">
                      PATIENT REDIRECTED
                    </h2>
                    <p className="text-xl text-gray-600 mb-6">
                      This patient was sent here by mistake and has been
                      redirected
                    </p>
                    <div className="bg-red-50 border border-red-200 rounded-xl p-6 mb-4 text-left">
                      <div className="flex items-center gap-4">
                        <div className="w-20 h-20 bg-red-600 text-white rounded-xl flex items-center justify-center">
                          <span className="text-3xl font-bold">
                            {c.queue_number ?? "?"}
                          </span>
                        </div>
                        <div className="text-left">
                          <div className="text-3xl font-black text-gray-900">
                            {c.full_name ?? "Patient"}
                          </div>
                          <div className="text-lg text-gray-600">
                            {c.patient_code ?? ""}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div className="bg-green-50 border border-green-200 rounded-xl p-6 mb-4 text-left">
                      <div className="flex items-center justify-between gap-4 flex-wrap">
                        <span className="font-bold text-green-700 text-2xl">
                          Please proceed to:
                        </span>
                        <span className="inline-block px-8 py-3 bg-green-600 text-white rounded-xl font-black text-3xl">
                          {c.correct_station_name ?? "?"}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
