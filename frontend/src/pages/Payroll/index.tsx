import { useQuery } from "@tanstack/react-query";
import { DollarSign, Users, Clock, AlertCircle, RefreshCw } from "lucide-react";
import { useHashTab } from "@/hooks/useHashTab";
import { StatCard } from "@/components/ui/StatCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

type PayTab = "employees" | "pay-runs" | "approvals" | "reports";
const TABS: { key: PayTab; label: string }[] = [
  { key: "employees", label: "Employees" },
  { key: "pay-runs", label: "Pay Runs" },
  { key: "approvals", label: "Approvals" },
  { key: "reports", label: "Reports" },
];

export default function Payroll() {
  const [tab, setTab] = useHashTab<PayTab>("employees");

  const { data: statsData } = useQuery({
    queryKey: ["payroll-stats"],
    queryFn: async () => {
      const { data } = await client.get("/payroll/stats.php");
      return data;
    },
    retry: false,
  });
  const { data: empData, refetch: refetchEmp } = useQuery({
    queryKey: ["payroll-employees"],
    queryFn: async () => {
      const { data } = await client.get("/payroll/employees.php");
      return data;
    },
    enabled: tab === "employees",
  });
  const { data: runsData, refetch: refetchRuns } = useQuery({
    queryKey: ["payroll-runs"],
    queryFn: async () => {
      const { data } = await client.get("/payroll/runs.php");
      return data;
    },
    enabled: tab === "pay-runs",
  });
  const { data: appData, refetch: refetchApp } = useQuery({
    queryKey: ["payroll-approvals"],
    queryFn: async () => {
      const { data } = await client.get("/payroll/approvals.php");
      return data;
    },
    enabled: tab === "approvals",
  });
  const { data: reportsData } = useQuery({
    queryKey: ["payroll-reports"],
    queryFn: async () => {
      const { data } = await client.get("/payroll/reports.php");
      return data;
    },
    enabled: tab === "reports",
  });

  const stats = statsData?.stats ?? {
    total_payroll: "-",
    total_employees: "-",
    ot_hours: "-",
    pending_approvals: "-",
  };
  const employees: any[] = empData?.ok ? (empData.employees ?? []) : [];
  const runs: any[] = runsData?.ok ? (runsData.runs ?? []) : [];
  const approvals: any[] = appData?.ok ? (appData.approvals ?? []) : [];
  const reports: any[] = reportsData?.ok ? (reportsData.reports ?? []) : [];

  return (
    <div>
      <div className="px-6 pt-6 mb-6 flex justify-between items-start">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">
            Payroll Management
          </h1>
          <p className="text-sm text-gray-600 mt-1">
            Employee payslips, deductions, and payroll generation.
          </p>
        </div>
      </div>

      <div className="px-6">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
          <StatCard
            icon={DollarSign}
            label="Total Payroll"
            value={stats.total_payroll}
            iconBg="bg-blue-100"
            iconColor="text-blue-600"
          />
          <StatCard
            icon={Users}
            label="Total Employees"
            value={stats.total_employees}
            iconBg="bg-green-100"
            iconColor="text-green-600"
          />
          <StatCard
            icon={Clock}
            label="Overtime Hours"
            value={stats.ot_hours}
            iconBg="bg-yellow-100"
            iconColor="text-yellow-600"
          />
          <StatCard
            icon={AlertCircle}
            label="Pending Approvals"
            value={stats.pending_approvals}
            iconBg="bg-purple-100"
            iconColor="text-purple-600"
          />
        </div>
      </div>

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
        {tab === "employees" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <h3 className="text-lg font-semibold text-gray-900">
                Employee Payroll
              </h3>
              <button
                onClick={() => refetchEmp()}
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
                      Name
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Department
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Position
                    </th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      Base Salary
                    </th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      OT Pay
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {employees.length === 0 ? (
                    <tr>
                      <td
                        colSpan={6}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No employee data
                      </td>
                    </tr>
                  ) : (
                    employees.map((e: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {e.name ?? e.full_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {e.department}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {e.position}
                        </td>
                        <td className="px-6 py-4 text-sm text-right text-gray-900">
                          ₱
                          {Number(e.base_salary ?? 0).toLocaleString(
                            undefined,
                            { minimumFractionDigits: 2 },
                          )}
                        </td>
                        <td className="px-6 py-4 text-sm text-right text-gray-600">
                          ₱
                          {Number(e.ot_pay ?? 0).toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                          })}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={e.status} />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {tab === "pay-runs" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <h3 className="text-lg font-semibold text-gray-900">Pay Runs</h3>
              <button
                onClick={() => refetchRuns()}
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
                      Period
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Department
                    </th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      Employees
                    </th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      Gross
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Generated By
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {runs.length === 0 ? (
                    <tr>
                      <td
                        colSpan={6}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No pay runs
                      </td>
                    </tr>
                  ) : (
                    runs.map((r: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {r.period}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {r.department}
                        </td>
                        <td className="px-6 py-4 text-sm text-right text-gray-600">
                          {r.employees}
                        </td>
                        <td className="px-6 py-4 text-sm text-right text-gray-900">
                          ₱
                          {Number(r.gross ?? 0).toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                          })}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={r.status} />
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {r.generated_by}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {tab === "approvals" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <h3 className="text-lg font-semibold text-gray-900">
                Pending Approvals
              </h3>
              <button
                onClick={() => refetchApp()}
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
                      Type
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Request
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Department
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Submitted
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {approvals.length === 0 ? (
                    <tr>
                      <td
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No approvals
                      </td>
                    </tr>
                  ) : (
                    approvals.map((a: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {a.type}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {a.request}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {a.department}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {a.submitted}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={a.status} />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {tab === "reports" && (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {reports.length === 0 ? (
              <div className="col-span-full text-center py-12 text-gray-400">
                No report templates available
              </div>
            ) : (
              reports.map((r: any, i: number) => (
                <div
                  key={i}
                  className="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow cursor-pointer"
                >
                  <h4 className="text-sm font-medium text-gray-900">
                    {r.name}
                  </h4>
                  <p className="text-xs text-gray-500 mt-1">{r.description}</p>
                  <p className="text-xs text-gray-400 mt-3">
                    Last generated: {r.last_generated ?? "Never"}
                  </p>
                </div>
              ))
            )}
          </div>
        )}
      </div>
    </div>
  );
}
