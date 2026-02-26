import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { Plus } from "lucide-react";
import { toast } from "sonner";
import { useHashTab } from "@/hooks/useHashTab";
import { useModal } from "@/hooks/useModal";
import { Modal } from "@/components/ui/Modal";
import client from "@/api/client";

type PMTab =
  | "laboratory-fees"
  | "opd-fees"
  | "radiology-fees"
  | "procedure-fees"
  | "room-fees"
  | "discounts-packages";
const TABS: { key: PMTab; label: string }[] = [
  { key: "laboratory-fees", label: "Laboratory Fees" },
  { key: "opd-fees", label: "OPD Fees" },
  { key: "radiology-fees", label: "Radiology Fees" },
  { key: "procedure-fees", label: "Procedure Fees" },
  { key: "room-fees", label: "Room Fees" },
  { key: "discounts-packages", label: "Discounts / Packages" },
];

export default function PriceMaster() {
  const qc = useQueryClient();
  const [tab, setTab] = useHashTab<PMTab>("laboratory-fees");

  const labMod = useModal();
  const opdMod = useModal();
  const [labForm, setLabForm] = useState({
    test_code: "",
    test_name: "",
    price: "",
  });
  const [opdForm, setOpdForm] = useState({
    fee_code: "",
    fee_name: "",
    price: "",
  });

  const { data: labData } = useQuery({
    queryKey: ["pm-lab-fees"],
    queryFn: async () => {
      const { data } = await client.get("/price_master/list_lab_fees.php");
      return data;
    },
    enabled: tab === "laboratory-fees",
  });
  const { data: opdData } = useQuery({
    queryKey: ["pm-opd-fees"],
    queryFn: async () => {
      const { data } = await client.get("/price_master/list_opd_fees.php");
      return data;
    },
    enabled: tab === "opd-fees",
  });

  const labFees: any[] = labData?.ok ? (labData.fees ?? []) : [];
  const opdFees: any[] = opdData?.ok ? (opdData.fees ?? []) : [];

  const saveLabMut = useMutation({
    mutationFn: async () => {
      const { data } = await client.post(
        "/price_master/save_lab_fee.php",
        labForm,
      );
      if (!data.ok) throw new Error(data.error ?? "Failed");
      return data;
    },
    onSuccess: () => {
      toast.success("Lab fee saved");
      labMod.hide();
      qc.invalidateQueries({ queryKey: ["pm-lab-fees"] });
    },
    onError: (e: Error) => toast.error(e.message),
  });

  const saveOpdMut = useMutation({
    mutationFn: async () => {
      const { data } = await client.post(
        "/price_master/save_opd_fee.php",
        opdForm,
      );
      if (!data.ok) throw new Error(data.error ?? "Failed");
      return data;
    },
    onSuccess: () => {
      toast.success("OPD fee saved");
      opdMod.hide();
      qc.invalidateQueries({ queryKey: ["pm-opd-fees"] });
    },
    onError: (e: Error) => toast.error(e.message),
  });

  return (
    <div>
      <div className="px-6 pt-6 mb-4">
        <h1 className="text-2xl font-bold text-gray-900">Price Master</h1>
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
        {/* LAB FEES */}
        {tab === "laboratory-fees" && (
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div className="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
              <div>
                <div className="text-lg font-semibold text-gray-900">
                  Laboratory Fees
                </div>
                <div className="text-sm text-gray-600">
                  Set fixed prices per laboratory test code.
                </div>
              </div>
              <button
                onClick={() => {
                  setLabForm({ test_code: "", test_name: "", price: "" });
                  labMod.show();
                }}
                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2 text-sm"
              >
                <Plus className="w-4 h-4" /> Add / Update Fee
              </button>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                      Test Code
                    </th>
                    <th className="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                      Test Name
                    </th>
                    <th className="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">
                      Price
                    </th>
                    <th className="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {labFees.length === 0 ? (
                    <tr>
                      <td
                        colSpan={4}
                        className="px-4 py-6 text-center text-sm text-gray-500"
                      >
                        No fees configured.
                      </td>
                    </tr>
                  ) : (
                    labFees.map((f: any, i: number) => (
                      <tr key={i} className="hover:bg-gray-50">
                        <td className="px-4 py-3 text-sm font-semibold text-gray-900">
                          {f.test_code}
                        </td>
                        <td className="px-4 py-3 text-sm text-gray-800">
                          {f.test_name}
                        </td>
                        <td className="px-4 py-3 text-sm text-right text-gray-800">
                          ₱{f.price}
                        </td>
                        <td className="px-4 py-3 text-right">
                          <button
                            onClick={() => {
                              setLabForm({
                                test_code: f.test_code,
                                test_name: f.test_name,
                                price: f.price,
                              });
                              labMod.show();
                            }}
                            className="px-3 py-1.5 text-sm bg-gray-900 text-white rounded-lg hover:bg-gray-800"
                          >
                            Edit
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* OPD FEES */}
        {tab === "opd-fees" && (
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div className="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
              <div>
                <div className="text-lg font-semibold text-gray-900">
                  OPD Fees
                </div>
                <div className="text-sm text-gray-600">
                  Set fixed prices for OPD services.
                </div>
              </div>
              <button
                onClick={() => {
                  setOpdForm({ fee_code: "", fee_name: "", price: "" });
                  opdMod.show();
                }}
                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2 text-sm"
              >
                <Plus className="w-4 h-4" /> Add / Update Fee
              </button>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                      Fee Code
                    </th>
                    <th className="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                      Fee Name
                    </th>
                    <th className="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">
                      Price
                    </th>
                    <th className="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {opdFees.length === 0 ? (
                    <tr>
                      <td
                        colSpan={4}
                        className="px-4 py-6 text-center text-sm text-gray-500"
                      >
                        No fees configured.
                      </td>
                    </tr>
                  ) : (
                    opdFees.map((f: any, i: number) => (
                      <tr key={i} className="hover:bg-gray-50">
                        <td className="px-4 py-3 text-sm font-semibold text-gray-900">
                          {f.fee_code}
                        </td>
                        <td className="px-4 py-3 text-sm text-gray-800">
                          {f.fee_name}
                        </td>
                        <td className="px-4 py-3 text-sm text-right text-gray-800">
                          ₱{f.price}
                        </td>
                        <td className="px-4 py-3 text-right">
                          <button
                            onClick={() => {
                              setOpdForm({
                                fee_code: f.fee_code,
                                fee_name: f.fee_name,
                                price: f.price,
                              });
                              opdMod.show();
                            }}
                            className="px-3 py-1.5 text-sm bg-gray-900 text-white rounded-lg hover:bg-gray-800"
                          >
                            Edit
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* COMING SOON sections */}
        {(tab === "radiology-fees" ||
          tab === "procedure-fees" ||
          tab === "room-fees" ||
          tab === "discounts-packages") && (
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div className="text-lg font-semibold text-gray-900 capitalize">
              {tab.replace(/-/g, " ")}
            </div>
            <div className="text-sm text-gray-600 mt-1">Coming soon.</div>
            <div className="mt-4 text-sm text-gray-500">
              This section will manage {tab.replace(/-/g, " ")} pricing.
            </div>
          </div>
        )}
      </div>

      {/* Lab Fee Modal */}
      <Modal
        open={labMod.open}
        onClose={labMod.hide}
        title="Add / Update Laboratory Fee"
      >
        <div className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700">
              Test Code
            </label>
            <input
              value={labForm.test_code}
              onChange={(e) =>
                setLabForm({ ...labForm, test_code: e.target.value })
              }
              className="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg"
              placeholder="e.g. CBC"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700">
              Test Name
            </label>
            <input
              value={labForm.test_name}
              onChange={(e) =>
                setLabForm({ ...labForm, test_name: e.target.value })
              }
              className="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg"
              placeholder="e.g. Complete Blood Count"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700">
              Price
            </label>
            <input
              value={labForm.price}
              onChange={(e) =>
                setLabForm({ ...labForm, price: e.target.value })
              }
              type="number"
              step="0.01"
              className="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg"
              placeholder="0.00"
            />
          </div>
          <div className="flex justify-end gap-3 pt-2">
            <button
              onClick={labMod.hide}
              className="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              onClick={() => saveLabMut.mutate()}
              disabled={saveLabMut.isPending}
              className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            >
              Save
            </button>
          </div>
        </div>
      </Modal>

      {/* OPD Fee Modal */}
      <Modal
        open={opdMod.open}
        onClose={opdMod.hide}
        title="Add / Update OPD Fee"
      >
        <div className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700">
              Fee Code
            </label>
            <input
              value={opdForm.fee_code}
              onChange={(e) =>
                setOpdForm({ ...opdForm, fee_code: e.target.value })
              }
              className="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg"
              placeholder="e.g. consultation"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700">
              Fee Name
            </label>
            <input
              value={opdForm.fee_name}
              onChange={(e) =>
                setOpdForm({ ...opdForm, fee_name: e.target.value })
              }
              className="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg"
              placeholder="e.g. OPD Consultation"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700">
              Price
            </label>
            <input
              value={opdForm.price}
              onChange={(e) =>
                setOpdForm({ ...opdForm, price: e.target.value })
              }
              type="number"
              step="0.01"
              className="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg"
              placeholder="0.00"
            />
          </div>
          <div className="flex justify-end gap-3 pt-2">
            <button
              onClick={opdMod.hide}
              className="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              onClick={() => saveOpdMut.mutate()}
              disabled={saveOpdMut.isPending}
              className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            >
              Save
            </button>
          </div>
        </div>
      </Modal>
    </div>
  );
}
