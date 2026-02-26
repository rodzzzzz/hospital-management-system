import { useState, useEffect, type FormEvent } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import {
  LineChart,
  Line,
  XAxis,
  YAxis,
  ResponsiveContainer,
  AreaChart,
  Area,
} from "recharts";
import {
  ArrowLeft,
  Heart,
  Bookmark,
  Activity,
  Droplets,
  FlaskConical,
  User,
  Plus,
  Minus,
  MessageSquare,
} from "lucide-react";
import { toast } from "sonner";
import { useModal } from "@/hooks/useModal";
import { Modal } from "@/components/ui/Modal";
import client from "@/api/client";

/* ── helpers ── */
function generateECGData() {
  const data: { x: number; y: number }[] = [];
  for (let i = 0; i < 100; i++) {
    const x = i / 100;
    const noise = Math.random() * 0.1;
    const y =
      i % 20 === 0
        ? Math.sin(x * Math.PI * 2) * 2 + noise
        : Math.sin(x * Math.PI * 2) + noise;
    data.push({ x: i, y });
  }
  return data;
}

const miniData = (arr: number[]) => arr.map((v, i) => ({ i, v }));
const heartRateData = miniData([
  65, 68, 70, 72, 68, 70, 72, 70, 68, 72, 70, 72,
]);
const bpData = miniData([95, 98, 100, 97, 96, 98, 100, 99, 97, 98, 100, 98]);

interface PatientInfo {
  id: number;
  full_name?: string;
  first_name?: string;
  last_name?: string;
  dob?: string;
  blood_type?: string;
  sex?: string;
  patient_code?: string;
  [key: string]: unknown;
}

function calcAge(dob?: string): string {
  if (!dob) return "-";
  const [y, m, d] = dob.split("-").map(Number);
  if (!y) return "-";
  const today = new Date();
  let age = today.getFullYear() - y;
  if (today < new Date(today.getFullYear(), (m || 1) - 1, d || 1)) age -= 1;
  return age >= 0 ? `${age} years` : "-";
}

export default function PatientDetail() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const recordModal = useModal();
  const [ecgData, setEcgData] = useState(generateECGData);
  const [medications, setMedications] = useState<string[]>([""]);

  const { data: patientData, isLoading } = useQuery({
    queryKey: ["patient-detail", id],
    queryFn: async () => {
      const { data } = await client.get(`/patients/get.php?id=${id}`);
      return data;
    },
    enabled: Boolean(id),
  });

  const patient: PatientInfo | null = patientData?.ok
    ? patientData.patient
    : null;
  const displayName =
    (patient?.full_name ??
      `${patient?.first_name ?? ""} ${patient?.last_name ?? ""}`.trim()) ||
    "Patient";
  const patientCode = patient?.patient_code ?? `P-${patient?.id ?? id}`;

  // Real-time ECG update (like PHP setInterval)
  useEffect(() => {
    const interval = setInterval(() => setEcgData(generateECGData()), 2000);
    return () => clearInterval(interval);
  }, []);

  const handleAddRecord = (e: FormEvent) => {
    e.preventDefault();
    const form = e.target as HTMLFormElement;
    const fd = new FormData(form);
    const record = {
      patient_id: id,
      vitals: {
        heartRate: fd.get("heartRate"),
        bloodPressure: {
          systolic: fd.get("systolic"),
          diastolic: fd.get("diastolic"),
        },
        temperature: fd.get("temperature"),
        glucoseLevel: fd.get("glucoseLevel"),
      },
      notes: fd.get("notes"),
      medications: medications.filter((m) => m.trim() !== ""),
    };
    console.log("New Record:", record);
    toast.success("Record added successfully!");
    recordModal.hide();
    form.reset();
    setMedications([""]);
  };

  if (isLoading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600" />
      </div>
    );
  }

  if (!patient) {
    return (
      <div className="text-center py-12">
        <p className="text-gray-500">Patient not found.</p>
        <button
          onClick={() => navigate("/patients")}
          className="mt-4 text-blue-600 hover:underline text-sm"
        >
          Back to patients
        </button>
      </div>
    );
  }

  return (
    <div>
      {/* Header */}
      <div className="bg-white p-6 flex items-center justify-between">
        <div className="flex items-center space-x-4">
          <button
            onClick={() => navigate("/patients")}
            className="text-gray-500 hover:text-gray-700"
          >
            <ArrowLeft className="w-5 h-5" />
          </button>
          <div>
            <h1 className="text-2xl font-semibold">
              Patient Management Dashboard
            </h1>
            <p className="text-gray-500">Patient ID: {patientCode}</p>
          </div>
        </div>
        <div className="flex items-center space-x-4">
          <button
            onClick={recordModal.show}
            className="px-4 py-2 bg-violet-600 text-white rounded-lg flex items-center space-x-2 hover:bg-violet-700"
          >
            <Plus className="w-4 h-4" />
            <span>Add Record</span>
          </button>
        </div>
      </div>

      {/* Dashboard Content */}
      <div className="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Left Column (2/3) */}
        <div className="lg:col-span-2 space-y-6">
          {/* Vital Signs */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {/* Heart Rate */}
            <div className="bg-indigo-600 rounded-2xl p-6 text-white transition-all hover:-translate-y-0.5 hover:shadow-lg">
              <div className="flex justify-between items-start">
                <div>
                  <p className="text-indigo-200">Heart Rate</p>
                  <div className="flex items-baseline mt-1">
                    <h2 className="text-4xl font-bold">70</h2>
                    <span className="ml-2 text-lg">/120</span>
                  </div>
                </div>
                <Heart className="w-5 h-5 text-indigo-200" />
              </div>
              <div className="mt-4 h-16">
                <ResponsiveContainer width="100%" height="100%">
                  <AreaChart data={heartRateData}>
                    <defs>
                      <linearGradient id="hrGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop
                          offset="0%"
                          stopColor="#ffffff"
                          stopOpacity={0.2}
                        />
                        <stop
                          offset="100%"
                          stopColor="#ffffff"
                          stopOpacity={0}
                        />
                      </linearGradient>
                    </defs>
                    <Area
                      type="monotone"
                      dataKey="v"
                      stroke="#fff"
                      strokeWidth={2}
                      fill="url(#hrGrad)"
                      dot={false}
                    />
                  </AreaChart>
                </ResponsiveContainer>
              </div>
            </div>

            {/* Blood Pressure */}
            <div className="bg-white rounded-2xl p-6 shadow-lg transition-all hover:-translate-y-0.5 hover:shadow-xl">
              <div className="flex justify-between items-start">
                <div>
                  <p className="text-gray-500">Blood Pressure</p>
                  <h2 className="text-4xl font-bold text-gray-900 mt-1">98</h2>
                </div>
                <Bookmark className="w-5 h-5 text-blue-500" />
              </div>
              <div className="mt-4 h-16">
                <ResponsiveContainer width="100%" height="100%">
                  <AreaChart data={bpData}>
                    <defs>
                      <linearGradient id="bpGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop
                          offset="0%"
                          stopColor="#3B82F6"
                          stopOpacity={0.2}
                        />
                        <stop
                          offset="100%"
                          stopColor="#3B82F6"
                          stopOpacity={0}
                        />
                      </linearGradient>
                    </defs>
                    <Area
                      type="monotone"
                      dataKey="v"
                      stroke="#3B82F6"
                      strokeWidth={2}
                      fill="url(#bpGrad)"
                      dot={false}
                    />
                  </AreaChart>
                </ResponsiveContainer>
              </div>
            </div>
          </div>

          {/* ECG Section */}
          <div className="bg-white rounded-2xl p-6 shadow-lg">
            <div className="flex justify-between items-center mb-6">
              <h3 className="text-lg font-semibold">Heart ECG</h3>
              <div className="flex items-center space-x-2 text-sm">
                <span className="text-gray-500">72 bpm</span>
                <span className="text-gray-400">Average</span>
              </div>
            </div>
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                <LineChart data={ecgData}>
                  <YAxis hide domain={[-3, 3]} />
                  <XAxis hide />
                  <Line
                    type="monotone"
                    dataKey="y"
                    stroke="#4f46e5"
                    strokeWidth={2}
                    dot={false}
                    isAnimationActive={false}
                  />
                </LineChart>
              </ResponsiveContainer>
            </div>
            <div className="flex justify-between mt-4 text-sm text-gray-500">
              {["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].map((d) => (
                <span key={d}>{d}</span>
              ))}
            </div>
          </div>

          {/* Additional Metrics */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
            {[
              {
                icon: Heart,
                bg: "bg-blue-100",
                color: "text-blue-600",
                label: "Blood Pressure",
                val: "120/80",
              },
              {
                icon: Activity,
                bg: "bg-purple-100",
                color: "text-purple-600",
                label: "Heart Rate",
                val: "85 bpm",
              },
              {
                icon: Droplets,
                bg: "bg-green-100",
                color: "text-green-600",
                label: "Glucose Level",
                val: "95 mg/dL",
              },
              {
                icon: FlaskConical,
                bg: "bg-red-100",
                color: "text-red-600",
                label: "Blood Count",
                val: "9,850",
              },
            ].map((m) => (
              <div
                key={m.label}
                className="bg-white p-4 rounded-xl shadow-lg flex items-center space-x-4"
              >
                <div className={`${m.bg} p-3 rounded-lg`}>
                  <m.icon className={`w-5 h-5 ${m.color}`} />
                </div>
                <div>
                  <p className="text-sm text-gray-500">{m.label}</p>
                  <p className="font-semibold">{m.val}</p>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Right Column (1/3) */}
        <div className="space-y-6">
          {/* Patient Info Card */}
          <div className="bg-white rounded-2xl p-6 shadow-lg">
            <div className="flex items-center space-x-4 mb-6">
              <div className="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                <User className="w-8 h-8 text-gray-400" />
              </div>
              <div>
                <h3 className="text-xl font-semibold">{displayName}</h3>
                <p className="text-gray-500">Patient ID: {patientCode}</p>
              </div>
            </div>
            <div className="space-y-4">
              {[
                { label: "Age", value: calcAge(patient.dob) },
                { label: "Blood Type", value: patient.blood_type ?? "-" },
                { label: "Sex", value: patient.sex ?? "-" },
              ].map((r) => (
                <div key={r.label} className="flex justify-between">
                  <span className="text-gray-500">{r.label}</span>
                  <span className="font-medium">{r.value}</span>
                </div>
              ))}
            </div>
          </div>

          {/* Scheduled Appointments */}
          <div className="bg-white rounded-2xl p-6 shadow-lg">
            <h3 className="text-lg font-semibold mb-4">
              Scheduled Appointments
            </h3>
            <div className="space-y-4">
              {[
                {
                  doctor: "Dr. Damian Lewis",
                  type: "Standard Consult",
                  time: "10:00am - 11:00am",
                },
                {
                  doctor: "Dr. Mike Taylor",
                  type: "Premium Consult",
                  time: "2:00pm - 3:00pm",
                },
              ].map((a) => (
                <div
                  key={a.doctor}
                  className="p-4 border border-gray-100 rounded-lg hover:bg-gray-50"
                >
                  <div className="flex justify-between items-start">
                    <div>
                      <h4 className="font-medium">{a.doctor}</h4>
                      <p className="text-sm text-gray-500">{a.type}</p>
                    </div>
                    <span className="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                      {a.time}
                    </span>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Message Button */}
          <button className="w-full bg-blue-600 text-white rounded-lg py-3 flex items-center justify-center space-x-2 hover:bg-blue-700">
            <MessageSquare className="w-4 h-4" />
            <span>Message</span>
          </button>
        </div>
      </div>

      {/* ═══ ADD RECORD MODAL ═══ */}
      <Modal
        open={recordModal.open}
        onClose={recordModal.hide}
        title="Add New Record"
        maxWidth="max-w-4xl"
      >
        <form onSubmit={handleAddRecord} className="space-y-6">
          <div className="grid grid-cols-2 gap-6">
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Heart Rate
              </label>
              <div className="flex">
                <input
                  type="number"
                  name="heartRate"
                  required
                  className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none"
                  placeholder="Enter heart rate"
                />
                <span className="ml-2 flex items-center text-gray-500">
                  bpm
                </span>
              </div>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Blood Pressure
              </label>
              <div className="flex space-x-2">
                <input
                  type="number"
                  name="systolic"
                  required
                  className="w-1/2 px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none"
                  placeholder="Systolic"
                />
                <span className="flex items-center text-gray-500">/</span>
                <input
                  type="number"
                  name="diastolic"
                  required
                  className="w-1/2 px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none"
                  placeholder="Diastolic"
                />
              </div>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Temperature
              </label>
              <div className="flex">
                <input
                  type="number"
                  name="temperature"
                  step="0.1"
                  required
                  className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none"
                  placeholder="Enter temperature"
                />
                <span className="ml-2 flex items-center text-gray-500">
                  &deg;C
                </span>
              </div>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-2">
                Glucose Level
              </label>
              <div className="flex">
                <input
                  type="number"
                  name="glucoseLevel"
                  required
                  className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none"
                  placeholder="Enter glucose level"
                />
                <span className="ml-2 flex items-center text-gray-500">
                  mg/dL
                </span>
              </div>
            </div>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-600 mb-2">
              Clinical Notes
            </label>
            <textarea
              name="notes"
              rows={4}
              className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none"
              placeholder="Enter clinical notes and observations"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-600 mb-2">
              Prescribed Medications
            </label>
            <div className="space-y-2">
              {medications.map((med, idx) => (
                <div key={idx} className="flex space-x-2">
                  <input
                    type="text"
                    value={med}
                    onChange={(e) => {
                      const copy = [...medications];
                      copy[idx] = e.target.value;
                      setMedications(copy);
                    }}
                    className="flex-1 px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none"
                    placeholder="Enter medication name and dosage"
                  />
                  {idx === 0 ? (
                    <button
                      type="button"
                      onClick={() => setMedications([...medications, ""])}
                      className="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200"
                    >
                      <Plus className="w-4 h-4" />
                    </button>
                  ) : (
                    <button
                      type="button"
                      onClick={() =>
                        setMedications(medications.filter((_, i) => i !== idx))
                      }
                      className="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200"
                    >
                      <Minus className="w-4 h-4" />
                    </button>
                  )}
                </div>
              ))}
            </div>
          </div>

          <div className="border-t border-gray-100 pt-6 flex justify-end space-x-4">
            <button
              type="button"
              onClick={recordModal.hide}
              className="px-6 py-3 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              className="px-6 py-3 bg-violet-600 text-white rounded-lg hover:bg-violet-700"
            >
              Save Record
            </button>
          </div>
        </form>
      </Modal>
    </div>
  );
}
