import { createBrowserRouter, Navigate } from "react-router-dom";
import { lazy, Suspense, type ComponentType } from "react";
import { AppLayout } from "@/layouts/AppLayout";
import { AuthLayout } from "@/layouts/AuthLayout";
import { ProtectedRoute } from "@/auth/ProtectedRoute";
import { RequireModule } from "@/rbac/RequireModule";

function Loading() {
  return (
    <div className="flex items-center justify-center h-64">
      <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600" />
    </div>
  );
}

function wrap(factory: () => Promise<{ default: ComponentType }>) {
  const LazyComponent = lazy(factory);
  return function WrappedLazy() {
    return (
      <Suspense fallback={<Loading />}>
        <LazyComponent />
      </Suspense>
    );
  };
}

const Login = wrap(() => import("@/pages/Login"));
const Dashboard = wrap(() => import("@/pages/Dashboard"));
const PatientList = wrap(() => import("@/pages/Patients/PatientList"));
const PatientDetail = wrap(() => import("@/pages/Patients/PatientDetail"));
const MedicalRecords = wrap(() => import("@/pages/MedicalRecords"));
const ClinicalArea = wrap(() => import("@/pages/ClinicalArea"));
const OPD = wrap(() => import("@/pages/OPD"));
const ER = wrap(() => import("@/pages/ER"));
const Laboratory = wrap(() => import("@/pages/Laboratory"));
const Pharmacy = wrap(() => import("@/pages/Pharmacy"));
const Cashier = wrap(() => import("@/pages/Cashier"));
const PhilHealth = wrap(() => import("@/pages/PhilHealth"));
const HR = wrap(() => import("@/pages/HR"));
const Doctor = wrap(() => import("@/pages/Doctor"));
const ICU = wrap(() => import("@/pages/ICU"));
const XRay = wrap(() => import("@/pages/XRay"));
const Dialysis = wrap(() => import("@/pages/Dialysis"));
const Kitchen = wrap(() => import("@/pages/Kitchen"));
const Inventory = wrap(() => import("@/pages/Inventory"));
const Payroll = wrap(() => import("@/pages/Payroll"));
const Chat = wrap(() => import("@/pages/Chat"));
const PriceMaster = wrap(() => import("@/pages/PriceMaster"));
const Queue = wrap(() => import("@/pages/Queue"));
const Displays = wrap(() => import("@/pages/Displays"));
const OperatingRoom = wrap(() => import("@/pages/OperatingRoom"));
const DeliveryRoom = wrap(() => import("@/pages/DeliveryRoom"));
const Profile = wrap(() => import("@/pages/Profile"));
const QueueDisplay = wrap(() => import("@/pages/QueueDisplay"));

export const router = createBrowserRouter([
  {
    path: "/",
    element: <Navigate to="/dashboard" replace />,
  },
  {
    element: <AuthLayout />,
    children: [{ path: "/login", element: <Login /> }],
  },
  {
    path: "/queue-display/:station",
    element: <QueueDisplay />,
  },
  {
    element: <ProtectedRoute />,
    children: [
      {
        element: <AppLayout />,
        children: [
          { path: "/dashboard", element: <Dashboard /> },
          { path: "/patients", element: <PatientList /> },
          { path: "/patients/:id", element: <PatientDetail /> },
          { path: "/medical-records", element: <MedicalRecords /> },
          {
            path: "/clinical-area",
            element: (
              <RequireModule module={["ER", "OPD", "DOCTOR", "ICU", "XRAY"]}>
                <ClinicalArea />
              </RequireModule>
            ),
          },
          {
            path: "/opd",
            element: (
              <RequireModule module="OPD">
                <OPD />
              </RequireModule>
            ),
          },
          {
            path: "/er",
            element: (
              <RequireModule module="ER">
                <ER />
              </RequireModule>
            ),
          },
          {
            path: "/operating-room",
            element: (
              <RequireModule module="DOCTOR">
                <OperatingRoom />
              </RequireModule>
            ),
          },
          {
            path: "/delivery-room",
            element: (
              <RequireModule module="DOCTOR">
                <DeliveryRoom />
              </RequireModule>
            ),
          },
          {
            path: "/icu",
            element: (
              <RequireModule module="ICU">
                <ICU />
              </RequireModule>
            ),
          },
          {
            path: "/xray",
            element: (
              <RequireModule module="XRAY">
                <XRay />
              </RequireModule>
            ),
          },
          {
            path: "/laboratory",
            element: (
              <RequireModule module="LAB">
                <Laboratory />
              </RequireModule>
            ),
          },
          {
            path: "/dialysis",
            element: (
              <RequireModule module="LAB">
                <Dialysis />
              </RequireModule>
            ),
          },
          {
            path: "/cashier",
            element: (
              <RequireModule module="CASHIER">
                <Cashier />
              </RequireModule>
            ),
          },
          {
            path: "/pharmacy",
            element: (
              <RequireModule module="PHARMACY">
                <Pharmacy />
              </RequireModule>
            ),
          },
          { path: "/price-master", element: <PriceMaster /> },
          { path: "/inventory", element: <Inventory /> },
          { path: "/kitchen", element: <Kitchen /> },
          { path: "/payroll", element: <Payroll /> },
          { path: "/chat", element: <Chat /> },
          {
            path: "/hr",
            element: (
              <RequireModule module="HR">
                <HR />
              </RequireModule>
            ),
          },
          { path: "/philhealth", element: <PhilHealth /> },
          { path: "/queue", element: <Queue /> },
          { path: "/displays", element: <Displays /> },
          {
            path: "/doctor",
            element: (
              <RequireModule module="DOCTOR">
                <Doctor />
              </RequireModule>
            ),
          },
          { path: "/profile", element: <Profile /> },
        ],
      },
    ],
  },
]);
