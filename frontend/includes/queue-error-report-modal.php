<!-- Unified Queue Error Report Modal -->
<div id="queueErrorReportModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-[100]">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-red-50 to-orange-50">
            <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 text-red-500"></i>
                Report Queue Error
            </h3>
            <p class="text-gray-500 mt-1">What type of error occurred?</p>
        </div>
        <div class="p-8 space-y-4">
            <button onclick="queueErrorReportChoice('self')" class="w-full p-6 bg-red-50 border-2 border-red-200 rounded-xl hover:bg-red-100 hover:border-red-400 transition-all duration-200 text-left group">
                <div class="flex items-center">
                    <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mr-5 group-hover:bg-red-200 transition-colors">
                        <i class="fas fa-user-times text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-800">I made a mistake</div>
                        <div class="text-gray-500 mt-1">I sent a patient to the wrong station and need to correct it</div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 ml-auto text-xl group-hover:text-red-400 transition-colors"></i>
                </div>
            </button>
            <button onclick="queueErrorReportChoice('previous')" class="w-full p-6 bg-orange-50 border-2 border-orange-200 rounded-xl hover:bg-orange-100 hover:border-orange-400 transition-all duration-200 text-left group">
                <div class="flex items-center">
                    <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mr-5 group-hover:bg-orange-200 transition-colors">
                        <i class="fas fa-undo-alt text-orange-600 text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-800">The previous station made a mistake</div>
                        <div class="text-gray-500 mt-1">I received a patient that shouldn't be here</div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 ml-auto text-xl group-hover:text-orange-400 transition-colors"></i>
                </div>
            </button>
        </div>
        <div class="p-4 bg-gray-50 border-t flex justify-end">
            <button onclick="queueErrorReportClose()" class="px-6 py-3 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
function queueErrorReportOpen() {
    document.getElementById('queueErrorReportModal').classList.remove('hidden');
}
function queueErrorReportClose() {
    document.getElementById('queueErrorReportModal').classList.add('hidden');
}
function queueErrorReportChoice(type) {
    queueErrorReportClose();
    if (type === 'self') {
        if (typeof qecOpenReportModal === 'function') qecOpenReportModal();
    } else if (type === 'previous') {
        if (typeof qrrOpenReportModal === 'function') qrrOpenReportModal();
    }
}
</script>
