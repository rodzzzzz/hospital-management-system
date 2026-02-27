<!-- Queue Return Request: Report Modal (Station B initiates) -->
<div id="qrrReportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[70]">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] mx-4 flex flex-col">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
            <h3 class="text-3xl font-bold text-orange-700 flex items-center">
                <i class="fas fa-bell mr-3 text-3xl"></i>
                Notify Previous Station
            </h3>
            <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl p-2" onclick="qrrCloseReportModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 flex-1 overflow-y-auto">
            <div id="qrrStep1" class="">
                <label class="block text-xl font-bold text-gray-700 mb-4">Select the wrongly received patient:</label>
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="qrrPatientSearch" placeholder="Search patient name or code..."
                               class="w-full px-4 py-3 pl-12 text-lg border-2 border-gray-200 rounded-lg focus:border-orange-400 focus:ring-orange-400 focus:outline-none">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                    </div>
                </div>
                <div id="qrrTransferList" class="space-y-3 max-h-[42rem] overflow-y-auto pr-2">
                    <div class="text-center py-8"><i class="fas fa-spinner fa-spin text-orange-500 text-3xl"></i><p class="mt-2">Loading incoming transfers...</p></div>
                </div>
            </div>
            <div id="qrrStep2" class="hidden">
                <div id="qrrSelectedPatientInfo" class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-lg"></div>
                <div class="mb-6">
                    <label for="qrrReasonField" class="block text-xl font-bold text-gray-700 mb-2">Reason for return request:</label>
                    <textarea id="qrrReasonField" rows="3" class="w-full border-2 border-gray-200 rounded-lg p-4 text-lg focus:border-orange-400 focus:ring-orange-400 focus:outline-none" placeholder="Explain why this patient should not be at your station..."></textarea>
                </div>
                <label class="block text-xl font-bold text-gray-700 mb-4">Select the CORRECT station:</label>
                <div id="qrrCorrectStationList" class="space-y-3 max-h-[28rem] overflow-y-auto pr-2"></div>
            </div>
        </div>
        <div class="p-6 bg-gray-50 border-t flex justify-between items-center flex-shrink-0">
            <button type="button" id="qrrBackBtn" class="hidden px-6 py-3 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold" onclick="qrrBackToStep1()">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </button>
            <div class="flex gap-4 ml-auto">
                <button type="button" class="px-6 py-3 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold" onclick="qrrCloseReportModal()">Cancel</button>
                <button type="button" id="qrrSubmitBtn" class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 text-lg font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed" disabled onclick="qrrSubmitRequest()">
                    <i class="fas fa-paper-plane mr-2"></i> Send Notification
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Queue Return Request: Incoming Alert Overlay for Station A (confirm/reject) -->
<div id="qrrAlertOverlay" class="fixed inset-0 z-[100] hidden" style="pointer-events:auto;">
    <style>
        @keyframes qrrBorderPulse {
            0%, 100% { box-shadow: inset 0 0 60px 30px rgba(234, 88, 12, 0.7); }
            50% { box-shadow: inset 0 0 80px 40px rgba(234, 88, 12, 0.3); }
        }
        @keyframes qrrShake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        #qrrAlertOverlay.active {
            animation: qrrBorderPulse 2s ease-in-out infinite;
            background: rgba(0, 0, 0, 0.6);
        }
        .qrr-alert-card {
            animation: qrrShake 0.8s ease-in-out;
        }
    </style>
    <div class="absolute inset-0 flex items-center justify-center p-8">
        <div id="qrrAlertContent" class="space-y-6 w-full max-w-6xl max-h-[90vh] overflow-y-auto"></div>
    </div>
</div>

<!-- Queue Return Request: Rejection Alert Overlay for Station B (blocking) -->
<div id="qrrRejectionOverlay" class="fixed inset-0 z-[100] hidden" style="pointer-events:auto;">
    <style>
        @keyframes qrrRejectPulse {
            0%, 100% { box-shadow: inset 0 0 60px 30px rgba(220, 38, 38, 0.7); }
            50% { box-shadow: inset 0 0 80px 40px rgba(220, 38, 38, 0.3); }
        }
        #qrrRejectionOverlay.active {
            animation: qrrRejectPulse 2s ease-in-out infinite;
            background: rgba(0, 0, 0, 0.6);
        }
    </style>
    <div class="absolute inset-0 flex items-center justify-center p-8">
        <div id="qrrRejectionContent" class="space-y-6 w-full max-w-5xl"></div>
    </div>
</div>

<!-- Queue Return Request: Reject Reason Modal (Station A provides reason) -->
<div id="qrrRejectReasonModal" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center z-[110]">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4 flex flex-col">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-2xl font-bold text-red-700 flex items-center">
                <i class="fas fa-times-circle mr-3"></i>
                Reject Return Request
            </h3>
        </div>
        <div class="p-6">
            <div id="qrrRejectPatientInfo" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-lg"></div>
            <label for="qrrRejectReasonField" class="block text-lg font-bold text-gray-700 mb-2">Reason for rejection (required):</label>
            <textarea id="qrrRejectReasonField" rows="4" class="w-full border-2 border-gray-200 rounded-lg p-4 text-lg focus:border-red-400 focus:ring-red-400 focus:outline-none" placeholder="Explain why this patient IS supposed to be at that station..."></textarea>
        </div>
        <div class="p-6 bg-gray-50 border-t flex justify-end gap-4">
            <button type="button" class="px-6 py-3 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold" onclick="qrrCloseRejectModal()">Cancel</button>
            <button type="button" id="qrrRejectSubmitBtn" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 text-lg font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed" disabled onclick="qrrSubmitReject()">
                <i class="fas fa-times-circle mr-2"></i> Confirm Rejection
            </button>
        </div>
    </div>
</div>
