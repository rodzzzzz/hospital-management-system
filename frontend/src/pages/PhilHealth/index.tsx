import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import {
  FileText,
  CheckCircle,
  Clock,
  AlertTriangle,
  Plus,
  Search,
  RefreshCw,
} from "lucide-react";
import { useDebounce } from "@/hooks/useDebounce";
import { StatCard } from "@/components/ui/StatCard";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

export default function PhilHealth() {
  const [search, setSearch] = useState("");
  const dSearch = useDebounce(search, 250);
  const [statusFilter, setStatusFilter] = useState("");

  const { data: statsData } = useQuery({
    queryKey: ["ph-stats"],
    queryFn: async () => {
      const { data } = await client.get("/philhealth/stats.php");
      return data;
    },
    retry: false,
  });
  const { data: claimsData, refetch } = useQuery({
    queryKey: ["ph-claims", dSearch, statusFilter],
    queryFn: async () => {
      const params = new URLSearchParams();
      if (dSearch) params.set("q", dSearch);
      if (statusFilter) params.set("status", statusFilter);
      const { data } = await client.get(
        `/philhealth/claims.php?${params.toString()}`,
      );
      return data;
    },
  });

  const stats = statsData?.stats ?? {
    total_claims: "-",
    approved: "-",
    pending: "-",
    denied: "-",
  };
  const claims: any[] = claimsData?.ok ? (claimsData.claims ?? []) : [];

  return (
    <div>
      <div className="bg-emerald-700 shadow-sm p-4 px-6 flex items-center justify-between text-white">
        <h1 className="text-xl md:text-2xl font-semibold">PhilHealth Claims</h1>
        <button className="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 flex items-center gap-2">
          <Plus className="w-4 h-4" /> New Claim
        </button>
      </div>

      <div className="p-6 space-y-6">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <StatCard
            icon={FileText}
            label="Total Claims"
            value={stats.total_claims}
            iconBg="bg-blue-100"
            iconColor="text-blue-600"
          />
          <StatCard
            icon={CheckCircle}
            label="Approved"
            value={stats.approved}
            iconBg="bg-green-100"
            iconColor="text-green-600"
          />
          <StatCard
            icon={Clock}
            label="Pending"
            value={stats.pending}
            iconBg="bg-yellow-100"
            iconColor="text-yellow-600"
          />
          <StatCard
            icon={AlertTriangle}
            label="Denied"
            value={stats.denied}
            iconBg="bg-red-100"
            iconColor="text-red-600"
          />
        </div>

        <div className="bg-white rounded-lg shadow-sm overflow-hidden">
          <div className="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 className="text-lg font-semibold text-gray-900">Claims List</h3>
            <div className="flex items-center gap-2">
              <select
                value={statusFilter}
                onChange={(e) => setStatusFilter(e.target.value)}
                className="px-3 py-2 border border-gray-200 rounded-lg text-sm"
              >
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="denied">Denied</option>
                <option value="returned">Returned</option>
              </select>
              <div className="relative">
                <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  type="text"
                  placeholder="Search claims..."
                  className="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm"
                />
              </div>
              <button
                onClick={() => refetch()}
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
                    Claim #
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Patient
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Type
                  </th>
                  <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                    Amount
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Filed
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {claims.length === 0 ? (
                  <tr>
                    <td
                      colSpan={6}
                      className="px-6 py-8 text-center text-sm text-gray-500"
                    >
                      No claims found
                    </td>
                  </tr>
                ) : (
                  claims.map((c: any, i: number) => (
                    <tr key={i}>
                      <td className="px-6 py-4 text-sm font-medium text-gray-900">
                        {c.claim_number}
                      </td>
                      <td className="px-6 py-4 text-sm text-gray-600">
                        {c.patient_name}
                      </td>
                      <td className="px-6 py-4 text-sm text-gray-600">
                        {c.claim_type}
                      </td>
                      <td className="px-6 py-4 text-sm text-right font-medium text-gray-900">
                        ₱{Number(c.amount ?? 0).toFixed(2)}
                      </td>
                      <td className="px-6 py-4 text-sm text-gray-500">
                        {c.filed_date}
                      </td>
                      <td className="px-6 py-4">
                        <StatusBadge status={c.status} />
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
