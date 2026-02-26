import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { Users, Building2, Search, RefreshCw } from "lucide-react";
import { useHashTab } from "@/hooks/useHashTab";
import { useDebounce } from "@/hooks/useDebounce";
import { StatCard } from "@/components/ui/StatCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

type HRTab = "directory" | "departments" | "scheduling";
const TABS: { key: HRTab; label: string }[] = [
  { key: "directory", label: "Employee Directory" },
  { key: "departments", label: "Departments" },
  { key: "scheduling", label: "Scheduling" },
];

export default function HR() {
  const [tab, setTab] = useHashTab<HRTab>("directory");
  const [empSearch, setEmpSearch] = useState("");
  const dEmpSearch = useDebounce(empSearch, 250);
  const [schedDate, setSchedDate] = useState("");

  const { data: statsData } = useQuery({
    queryKey: ["hr-stats"],
    queryFn: async () => {
      const { data } = await client.get("/hr/stats.php");
      return data;
    },
    retry: false,
  });
  const { data: empData, refetch: refetchEmp } = useQuery({
    queryKey: ["hr-employees", dEmpSearch],
    queryFn: async () => {
      const q = dEmpSearch ? `?q=${encodeURIComponent(dEmpSearch)}` : "";
      const { data } = await client.get(`/hr/employees.php${q}`);
      return data;
    },
    enabled: tab === "directory",
  });
  const { data: deptData } = useQuery({
    queryKey: ["hr-departments"],
    queryFn: async () => {
      const { data } = await client.get("/hr/departments.php");
      return data;
    },
    enabled: tab === "departments",
  });
  const { data: schedData, refetch: refetchSched } = useQuery({
    queryKey: ["hr-schedules", schedDate],
    queryFn: async () => {
      const q = schedDate ? `?date=${schedDate}` : "";
      const { data } = await client.get(`/hr/schedules.php${q}`);
      return data;
    },
    enabled: tab === "scheduling",
  });

  const stats = statsData?.stats ?? {
    total_employees: "-",
    active: "-",
    departments: "-",
  };
  const employees: any[] = empData?.ok ? (empData.employees ?? []) : [];
  const departments: any[] = deptData?.ok ? (deptData.departments ?? []) : [];
  const schedules: any[] = schedData?.ok ? (schedData.schedules ?? []) : [];

  return (
    <div>
      <div className="px-6 pt-6 mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Human Resources</h1>
        <p className="text-sm text-gray-600 mt-1">
          Employee directory, departments, and shift scheduling.
        </p>
      </div>

      <div className="px-6">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <StatCard
            icon={Users}
            label="Total Employees"
            value={stats.total_employees}
            iconBg="bg-blue-100"
            iconColor="text-blue-600"
          />
          <StatCard
            icon={Users}
            label="Active"
            value={stats.active}
            iconBg="bg-green-100"
            iconColor="text-green-600"
          />
          <StatCard
            icon={Building2}
            label="Departments"
            value={stats.departments}
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
        {tab === "directory" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <h3 className="text-lg font-semibold text-gray-900">
                Employee Directory
              </h3>
              <div className="flex items-center gap-2">
                <div className="relative">
                  <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                  <input
                    value={empSearch}
                    onChange={(e) => setEmpSearch(e.target.value)}
                    type="text"
                    placeholder="Search employees..."
                    className="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm"
                  />
                </div>
                <button
                  onClick={() => refetchEmp()}
                  className="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm flex items-center gap-1"
                >
                  <RefreshCw className="w-3.5 h-3.5" />
                </button>
              </div>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Code
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Name
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Department
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Position
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
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No employees
                      </td>
                    </tr>
                  ) : (
                    employees.map((e: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {e.employee_code}
                        </td>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {e.full_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {e.department_name ?? "-"}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {e.position_name ?? "-"}
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

        {tab === "departments" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100">
              <h3 className="text-lg font-semibold text-gray-900">
                Departments
              </h3>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Department
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Head
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Employees
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {departments.length === 0 ? (
                    <tr>
                      <td
                        colSpan={4}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No departments
                      </td>
                    </tr>
                  ) : (
                    departments.map((d: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {d.name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {d.head ?? "-"}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {d.employee_count ?? "-"}
                        </td>
                        <td className="px-6 py-4">
                          <StatusBadge status={d.status} />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {tab === "scheduling" && (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
              <h3 className="text-lg font-semibold text-gray-900">
                Shift Schedules
              </h3>
              <div className="flex items-center gap-2">
                <input
                  type="date"
                  value={schedDate}
                  onChange={(e) => setSchedDate(e.target.value)}
                  className="px-3 py-2 border border-gray-200 rounded-lg text-sm"
                />
                <button
                  onClick={() => refetchSched()}
                  className="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm flex items-center gap-1"
                >
                  <RefreshCw className="w-3.5 h-3.5" />
                </button>
              </div>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Employee
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Date
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Start
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      End
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Notes
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {schedules.length === 0 ? (
                    <tr>
                      <td
                        colSpan={5}
                        className="px-6 py-8 text-center text-sm text-gray-500"
                      >
                        No schedules
                      </td>
                    </tr>
                  ) : (
                    schedules.map((s: any, i: number) => (
                      <tr key={i}>
                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                          {s.full_name ?? s.employee_name}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {s.shift_date}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {s.start_time}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-600">
                          {s.end_time}
                        </td>
                        <td className="px-6 py-4 text-sm text-gray-500">
                          {s.notes ?? "-"}
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
