<!-- Queue Error Correction: Report Wrong Station Modal -->
<div id="qecReportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[70]">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] mx-4 flex flex-col">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
            <h3 class="text-3xl font-bold text-red-700 flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 text-3xl"></i>
                Report Wrong Station
            </h3>
            <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl p-2" onclick="qecCloseReportModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 flex-1 overflow-y-auto">
            <div id="qecStep1" class="">
                <label class="block text-xl font-bold text-gray-700 mb-4">Select the wrongly transferred patient:</label>
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="qecPatientSearch" placeholder="Search patient name or code..." 
                               class="w-full px-4 py-3 pl-12 text-lg border-2 border-gray-200 rounded-lg focus:border-red-400 focus:ring-red-400 focus:outline-none">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                    </div>
                </div>
                <div id="qecTransferList" class="space-y-3 max-h-[42rem] overflow-y-auto pr-2">
                    <div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i><p class="mt-2">Loading recent transfers...</p></div>
                </div>
            </div>
            <div id="qecStep2" class="hidden">
                <div id="qecSelectedPatientInfo" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg"></div>
                <div class="mb-6">
                    <label for="qecReasonField" class="block text-xl font-bold text-gray-700 mb-2">Reason for correction:</label>
                    <textarea id="qecReasonField" rows="3" class="w-full border-2 border-gray-200 rounded-lg p-4 text-lg focus:border-red-400 focus:ring-red-400 focus:outline-none" placeholder="Explain why this patient needs to be moved..."></textarea>
                </div>
                <label class="block text-xl font-bold text-gray-700 mb-4">Select the CORRECT station:</label>
                <div id="qecCorrectStationList" class="space-y-3 max-h-[28rem] overflow-y-auto pr-2"></div>
            </div>
        </div>
        <div class="p-6 bg-gray-50 border-t flex justify-between items-center flex-shrink-0">
            <button type="button" id="qecBackBtn" class="hidden px-6 py-3 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold" onclick="qecBackToStep1()">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </button>
            <div class="flex gap-4 ml-auto">
                <button type="button" class="px-6 py-3 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold" onclick="qecCloseReportModal()">Cancel</button>
                <button type="button" id="qecSubmitBtn" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 text-lg font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed" disabled onclick="qecSubmitReport()">
                    <i class="fas fa-exclamation-circle mr-2"></i> Report Error
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Queue Error Correction: Incoming Alert Overlay (cannot be dismissed without confirming) -->
<div id="qecAlertOverlay" class="fixed inset-0 z-[100] hidden" style="pointer-events:auto;">
    <style>
        @keyframes qecBorderPulse {
            0%, 100% { box-shadow: inset 0 0 60px 30px rgba(220, 38, 38, 0.7); }
            50% { box-shadow: inset 0 0 80px 40px rgba(220, 38, 38, 0.3); }
        }
        @keyframes qecShake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        #qecAlertOverlay.active {
            animation: qecBorderPulse 2s ease-in-out infinite;
            background: rgba(0, 0, 0, 0.6);
        }
        .qec-alert-card {
            animation: qecShake 0.8s ease-in-out;
        }
    </style>
    <div class="absolute inset-0 flex items-center justify-center p-8">
        <div id="qecAlertContent" class="space-y-6 w-full max-w-6xl"></div>
    </div>
</div>
