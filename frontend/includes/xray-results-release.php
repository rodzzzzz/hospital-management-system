<?php
$includeXrayResultsReleaseCard = $includeXrayResultsReleaseCard ?? true;
$includeXrayResultsReleaseModal = $includeXrayResultsReleaseModal ?? true;
?>

<?php if ($includeXrayResultsReleaseCard): ?>
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Completed</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody id="xrayReleaseTbody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php if ($includeXrayResultsReleaseModal): ?>
    <div id="xrayResultModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-5xl bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">X-ray Result</div>
                        <div id="xrayResultTitle" class="text-lg font-semibold text-gray-900">-</div>
                    </div>
                    <button id="xrayResultClose" type="button" class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">Close</button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <div class="bg-gray-900 p-4">
                        <img id="xrayResultImg" src="" alt="X-ray" class="w-full h-[520px] object-contain rounded-lg bg-black" />
                        <div class="text-xs text-gray-300 mt-2">Sample image for demo purposes.</div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="rounded-lg border border-gray-200 p-3">
                                <div class="text-xs text-gray-500">Patient</div>
                                <div id="xrayResultPatient" class="text-sm font-medium text-gray-900 mt-1">-</div>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-3">
                                <div class="text-xs text-gray-500">Exam</div>
                                <div id="xrayResultExam" class="text-sm font-medium text-gray-900 mt-1">-</div>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-3">
                                <div class="text-xs text-gray-500">Status</div>
                                <div id="xrayResultStatus" class="text-sm font-medium text-gray-900 mt-1">-</div>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-3">
                                <div class="text-xs text-gray-500">Ordered</div>
                                <div id="xrayResultOrdered" class="text-sm font-medium text-gray-900 mt-1">-</div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="text-sm font-semibold text-gray-900">Report</div>
                            <div class="mt-3 space-y-3 text-sm text-gray-700">
                                <div>
                                    <div class="text-xs text-gray-500">Clinical History</div>
                                    <div id="xrayReportHistory" class="mt-1">-</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Findings</div>
                                    <div id="xrayReportFindings" class="mt-1">-</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Impression</div>
                                    <div id="xrayReportImpression" class="mt-1 font-medium text-gray-900">-</div>
                                </div>
                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                                    <div class="text-xs text-gray-500">Radiologist</div>
                                    <div id="xrayReportRadiologist" class="text-sm font-medium text-gray-900">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
