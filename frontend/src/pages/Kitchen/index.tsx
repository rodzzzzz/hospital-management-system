import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import {
  Package,
  AlertTriangle,
  DollarSign,
  Wrench,
  Plus,
  Search,
} from "lucide-react";
import { useDebounce } from "@/hooks/useDebounce";
import { StatusBadge } from "@/components/ui/StatusBadge";
import client from "@/api/client";

interface KitchenItem {
  id: number;
  code: string;
  name: string;
  description: string;
  category: string;
  quantity: number;
  unit: string;
  price: number;
  expiry_date: string;
  status: string;
}

export default function Kitchen() {
  const [search, setSearch] = useState("");
  const dSearch = useDebounce(search, 250);

  const { data: statsData } = useQuery({
    queryKey: ["kitchen-stats"],
    queryFn: async () => {
      const { data } = await client.get("/kitchen/stats.php");
      return data;
    },
    retry: false,
  });

  const { data: itemsData } = useQuery({
    queryKey: ["kitchen-items", dSearch],
    queryFn: async () => {
      const q = dSearch ? `?q=${encodeURIComponent(dSearch)}` : "";
      const { data } = await client.get(`/kitchen/items.php${q}`);
      return data;
    },
  });

  const stats = statsData?.stats ?? {
    total_items: "-",
    low_stock: "-",
    total_value: "-",
    equipment_pct: "-",
  };
  const items: KitchenItem[] = itemsData?.ok ? (itemsData.items ?? []) : [];

  return (
    <div>
      <div className="px-6 pt-6 mb-6">
        <h1 className="text-2xl font-bold text-gray-800">
          Kitchen Supply Management
        </h1>
        <p className="text-gray-500">Manage and track kitchen supplies</p>
      </div>

      <div className="p-6 space-y-6">
        {/* Stats */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-blue-100 text-blue-600">
                <Package className="w-5 h-5" />
              </div>
              <div className="ml-4">
                <h2 className="text-sm font-medium text-gray-600">
                  Total Items
                </h2>
                <p className="text-2xl font-semibold text-gray-800">
                  {stats.total_items}
                </p>
              </div>
            </div>
          </div>
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <AlertTriangle className="w-5 h-5" />
              </div>
              <div className="ml-4">
                <h2 className="text-sm font-medium text-gray-600">
                  Low Stock Items
                </h2>
                <p className="text-2xl font-semibold text-gray-800">
                  {stats.low_stock}
                </p>
              </div>
            </div>
          </div>
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-green-100 text-green-600">
                <DollarSign className="w-5 h-5" />
              </div>
              <div className="ml-4">
                <h2 className="text-sm font-medium text-gray-600">
                  Total Value
                </h2>
                <p className="text-2xl font-semibold text-gray-800">
                  {stats.total_value}
                </p>
              </div>
            </div>
          </div>
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-purple-100 text-purple-600">
                <Wrench className="w-5 h-5" />
              </div>
              <div className="ml-4">
                <h2 className="text-sm font-medium text-gray-600">
                  Equipment Status
                </h2>
                <p className="text-2xl font-semibold text-gray-800">
                  {stats.equipment_pct}
                </p>
              </div>
            </div>
          </div>
        </div>

        {/* Inventory Table */}
        <div className="bg-white rounded-lg shadow">
          <div className="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 className="text-xl font-semibold text-gray-800">
              Kitchen Inventory
            </h2>
            <div className="flex items-center gap-3">
              <div className="relative">
                <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  type="text"
                  placeholder="Search items..."
                  className="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                />
              </div>
              <button className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <Plus className="w-4 h-4" /> Add New Item
              </button>
            </div>
          </div>
          <div className="overflow-x-auto">
            <table className="min-w-full">
              <thead>
                <tr className="bg-gray-50">
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Item
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Category
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Quantity
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Unit Price
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Expiry Date
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {items.length === 0 ? (
                  <tr>
                    <td
                      colSpan={6}
                      className="px-6 py-8 text-center text-sm text-gray-500"
                    >
                      No items found
                    </td>
                  </tr>
                ) : (
                  items.map((item) => (
                    <tr key={item.id} className="hover:bg-gray-50">
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="font-medium text-gray-900">
                          {item.code ? `${item.code} ` : ""}
                          {item.name}
                        </div>
                        {item.description && (
                          <div className="text-sm text-gray-500">
                            {item.description}
                          </div>
                        )}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {item.category}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm font-medium text-gray-900">
                          {item.quantity} {item.unit}
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ₱{Number(item.price).toFixed(2)}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {item.expiry_date ?? "N/A"}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <StatusBadge status={item.status} />
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
