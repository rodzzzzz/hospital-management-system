import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
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
  LineChart,
  Line,
} from "recharts";
import {
  Package,
  Activity,
  Wrench,
  DollarSign,
  Plus,
  Search,
} from "lucide-react";
import { useDebounce } from "@/hooks/useDebounce";
import { StatCard } from "@/components/ui/StatCard";
import { ChartCard } from "@/components/ui/ChartCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

const PIE_COLORS = ["#3B82F6", "#10B981", "#F59E0B", "#EF4444", "#8B5CF6"];

export default function Inventory() {
  const [search, setSearch] = useState("");
  const dSearch = useDebounce(search, 250);

  const { data: statsData } = useQuery({
    queryKey: ["inv-stats"],
    queryFn: async () => {
      const { data } = await client.get("/inventory/stats.php");
      return data;
    },
    retry: false,
  });
  const { data: chartsData } = useQuery({
    queryKey: ["inv-charts"],
    queryFn: async () => {
      const { data } = await client.get("/inventory/charts.php");
      return data;
    },
  });
  const { data: itemsData } = useQuery({
    queryKey: ["inv-items", dSearch],
    queryFn: async () => {
      const q = dSearch ? `?q=${encodeURIComponent(dSearch)}` : "";
      const { data } = await client.get(`/inventory/items.php${q}`);
      return data;
    },
  });

  const stats = statsData?.stats ?? {
    total_equipment: "-",
    active_usage: "-",
    maintenance_due: "-",
    total_value: "-",
  };
  const usageTrend = (chartsData?.usage_trend ?? []) as {
    day: string;
    value: number;
  }[];
  const categories = (chartsData?.categories ?? []) as {
    name: string;
    value: number;
  }[];
  const maintenance = (chartsData?.maintenance ?? []) as {
    name: string;
    value: number;
  }[];
  const valueTrend = (chartsData?.value_trend ?? []) as {
    month: string;
    value: number;
  }[];
  const items: any[] = itemsData?.ok ? (itemsData.items ?? []) : [];

  return (
    <div>
      <div className="bg-white p-6 flex items-center justify-between">
        <h1 className="text-2xl font-semibold">Medical Equipment Inventory</h1>
        <button className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
          <Plus className="w-4 h-4" /> Add New
        </button>
      </div>

      <div className="p-6 space-y-6">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <StatCard
            icon={Package}
            label="Total Equipment"
            value={stats.total_equipment}
            iconBg="bg-blue-100"
            iconColor="text-blue-600"
          />
          <StatCard
            icon={Activity}
            label="Active Usage"
            value={stats.active_usage}
            iconBg="bg-green-100"
            iconColor="text-green-600"
          />
          <StatCard
            icon={Wrench}
            label="Maintenance Due"
            value={stats.maintenance_due}
            iconBg="bg-yellow-100"
            iconColor="text-yellow-600"
          />
          <StatCard
            icon={DollarSign}
            label="Total Value"
            value={stats.total_value}
            iconBg="bg-gray-100"
            iconColor="text-gray-600"
          />
        </div>

        <ChartCard title="Equipment Usage Analytics">
          <ResponsiveContainer width="100%" height={280}>
            <LineChart
              data={usageTrend.length ? usageTrend : [{ day: "1", value: 0 }]}
            >
              <XAxis dataKey="day" tick={{ fontSize: 11 }} />
              <YAxis tick={{ fontSize: 11 }} />
              <Tooltip />
              <Line
                type="monotone"
                dataKey="value"
                stroke="#3B82F6"
                strokeWidth={2}
                dot={false}
              />
            </LineChart>
          </ResponsiveContainer>
        </ChartCard>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <ChartCard title="Equipment Categories">
            <ResponsiveContainer width="100%" height={240}>
              <PieChart>
                <Pie
                  data={
                    categories.length ? categories : [{ name: "N/A", value: 1 }]
                  }
                  cx="50%"
                  cy="50%"
                  outerRadius={80}
                  dataKey="value"
                >
                  {(categories.length
                    ? categories
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
          <ChartCard title="Maintenance Status">
            <ResponsiveContainer width="100%" height={240}>
              <BarChart
                data={
                  maintenance.length ? maintenance : [{ name: "N/A", value: 0 }]
                }
              >
                <XAxis dataKey="name" tick={{ fontSize: 11 }} />
                <YAxis tick={{ fontSize: 11 }} />
                <Tooltip />
                <Bar dataKey="value" fill="#F59E0B" />
              </BarChart>
            </ResponsiveContainer>
          </ChartCard>
          <ChartCard title="Equipment Value Trend">
            <ResponsiveContainer width="100%" height={240}>
              <LineChart
                data={
                  valueTrend.length ? valueTrend : [{ month: "Jan", value: 0 }]
                }
              >
                <XAxis dataKey="month" tick={{ fontSize: 11 }} />
                <YAxis tick={{ fontSize: 11 }} />
                <Tooltip />
                <Line
                  type="monotone"
                  dataKey="value"
                  stroke="#10B981"
                  strokeWidth={2}
                  dot={false}
                />
              </LineChart>
            </ResponsiveContainer>
          </ChartCard>
        </div>

        <div className="bg-white rounded-lg shadow-sm overflow-hidden">
          <div className="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 className="text-lg font-semibold">Equipment Inventory</h2>
            <div className="relative">
              <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                type="text"
                placeholder="Search equipment..."
                className="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm"
              />
            </div>
          </div>
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Equipment
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Category
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Status
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Usage
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {items.length === 0 ? (
                  <tr>
                    <td
                      colSpan={4}
                      className="px-6 py-8 text-center text-sm text-gray-500"
                    >
                      No equipment found
                    </td>
                  </tr>
                ) : (
                  items.map((item: any, i: number) => (
                    <tr key={i}>
                      <td className="px-6 py-4">
                        <div className="text-sm font-medium text-gray-900">
                          {item.name}
                        </div>
                        {item.code && (
                          <div className="text-sm text-gray-500">
                            ID: {item.code}
                          </div>
                        )}
                      </td>
                      <td className="px-6 py-4">
                        <StatusBadge status={item.category} />
                      </td>
                      <td className="px-6 py-4">
                        <StatusBadge status={item.status} />
                      </td>
                      <td className="px-6 py-4">
                        <div className="w-full bg-gray-200 rounded-full h-2">
                          <div
                            className="bg-blue-600 h-2 rounded-full"
                            style={{ width: `${item.usage_pct ?? 0}%` }}
                          />
                        </div>
                        <span className="text-xs text-gray-600">
                          {item.usage_pct ?? 0}%
                        </span>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
}
