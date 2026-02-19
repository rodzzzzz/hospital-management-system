<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ER - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <?php
        $erCanNurse = isset($authUser) && is_array($authUser) && function_exists('auth_user_has_role')
            ? auth_user_has_role($authUser, 'ER', 'ER Nurse')
            : false;
        $erCanNpPa = isset($authUser) && is_array($authUser) && function_exists('auth_user_has_role')
            ? auth_user_has_role($authUser, 'ER', 'NP/PA')
            : false;
        ?>

        <main class="ml-16 lg:ml-80 flex-1 overflow-auto">
            <header class="bg-white p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold">ER</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-500"></i>
                    </button>
                    <?php include __DIR__ . '/includes/profile-dropdown.php'; ?>
                </div>
            </header>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <section id="erNewRequestSection" class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 id="erRequestTitle" class="text-lg font-semibold text-gray-800">ER Nurse Lab Request</h2>
                                    <p id="erRequestSubtitle" class="text-sm text-gray-600 mt-1">Requests require doctor approval before Laboratory can process.</p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                                    <i class="fas fa-truck-medical text-red-600 text-xl"></i>
                                </div>
                            </div>

                            <div id="erAlert" class="hidden mt-4 px-4 py-3 rounded-lg text-sm"></div>

                            <form id="erLabForm" class="mt-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Patient</label>
                                    <input id="patientSearch" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search patient name / ID" autocomplete="off">
                                    <input id="patientId" type="hidden">
                                    <div id="patientResults" class="mt-2 border border-gray-200 rounded-lg overflow-hidden hidden"></div>
                                    <div id="patientSelected" class="mt-2 text-xs text-gray-600"></div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Triage Level (1-5)</label>
                                    <select id="triageLevel" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select triage level</option>
                                        <option value="1">1 - Resuscitation</option>
                                        <option value="2">2 - Emergent</option>
                                        <option value="3">3 - Urgent</option>
                                        <option value="4">4 - Less Urgent</option>
                                        <option value="5">5 - Non-Urgent</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Chief Complaint</label>
                                    <input id="chiefComplaint" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Fever, Chest pain">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Vitals</label>
                                    <div class="mt-2 grid grid-cols-2 gap-3">
                                        <input id="vitalBp" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="BP (e.g. 120/80)">
                                        <input id="vitalHr" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="HR">
                                        <input id="vitalRr" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="RR">
                                        <input id="vitalTemp" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Temp">
                                        <input id="vitalSpo2" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="SpO2">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Priority</label>
                                    <select id="priority" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="routine">Routine</option>
                                        <option value="urgent">Urgent</option>
                                        <option value="stat">STAT</option>
                                    </select>
                                </div>

                                <div id="erDoctorSection">
                                    <label class="block text-sm font-medium text-gray-700">Doctor</label>
                                    <select id="erDoctor" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Loading doctors...</option>
                                    </select>
                                    <div id="erDoctorSelected" class="mt-2 text-xs text-gray-600"></div>
                                    <div id="erDoctorAvailability" class="mt-2"></div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Requested Tests</label>
                                    <p class="text-xs text-gray-500 mt-1">Standing order tests may be auto-approved (CBC, Urinalysis, Pregnancy, Blood Sugar, Electrolytes).</p>
                                    <input id="erTestSearch" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Type test name / code" autocomplete="off">
                                    <div id="erTestList" class="mt-2 grid grid-cols-1 gap-2"></div>
                                </div>

                                <div>
                                    <label id="requestedByLabel" class="block text-sm font-medium text-gray-700">Requested by (Nurse)</label>
                                    <input id="requestedBy" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Nurse Anna">
                                </div>

                                <div id="erApprovedBySection" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700">Approved by</label>
                                    <input id="approvedBy" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Doctor Name">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea id="notes" rows="3" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Optional"></textarea>
                                </div>

                                <div class="pt-2">
                                    <button id="erSubmitBtn" type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Submit for Doctor Approval</button>
                                </div>
                            </form>
                        </section>

                        <section id="erWardSection" class="hidden">
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-800">Ward</h2>
                                        <p class="text-sm text-gray-600 mt-1">Bed management, ward notes, geriatric & teen (PDEA) tracking.</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <select id="wardFilter" class="px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="all">All</option>
                                            <option value="senior">Senior / Old</option>
                                            <option value="pdea">PDEA (Teens)</option>
                                        </select>
                                        <input id="wardSearch" type="text" placeholder="Search patient / bed" class="px-3 py-2 border border-gray-200 rounded-lg w-56 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        <button id="wardRefreshBtn" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                                    </div>
                                </div>

                                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                        <div>
                                            <div class="text-xs text-gray-500">Occupied</div>
                                            <div id="wardStatOccupied" class="text-2xl font-semibold text-gray-900">0</div>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                            <i class="fas fa-bed text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                        <div>
                                            <div class="text-xs text-gray-500">Isolation</div>
                                            <div id="wardStatIsolation" class="text-2xl font-semibold text-gray-900">0</div>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center">
                                            <i class="fas fa-shield-virus text-yellow-600"></i>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                        <div>
                                            <div class="text-xs text-gray-500">High Fall-Risk</div>
                                            <div id="wardStatFallRisk" class="text-2xl font-semibold text-gray-900">0</div>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                                            <i class="fas fa-person-falling text-red-600"></i>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                        <div>
                                            <div class="text-xs text-gray-500">Avg Ward Time (hrs)</div>
                                            <div id="wardStatAvgHours" class="text-2xl font-semibold text-gray-900">0</div>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                                            <i class="fas fa-hourglass-half text-purple-600"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-semibold text-gray-800">Beds by Category</div>
                                            <div class="text-xs text-gray-500">count</div>
                                        </div>
                                        <div class="mt-4">
                                            <canvas id="wardChartCategory" height="120"></canvas>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-semibold text-gray-800">Ward Turnover (7 days)</div>
                                            <div class="text-xs text-gray-500">patients</div>
                                        </div>
                                        <div class="mt-4">
                                            <canvas id="wardChartTurnover" height="120"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-semibold text-gray-800">Isolation Rate</div>
                                            <div class="text-xs text-gray-500">%</div>
                                        </div>
                                        <div class="mt-4">
                                            <canvas id="wardChartIsolation" height="120"></canvas>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-semibold text-gray-800">Fall-Risk Trend (7 days)</div>
                                            <div class="text-xs text-gray-500">patients</div>
                                        </div>
                                        <div class="mt-4">
                                            <canvas id="wardChartFallRisk" height="120"></canvas>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-semibold text-gray-800">Avg LOS by Category</div>
                                            <div class="text-xs text-gray-500">hrs</div>
                                        </div>
                                        <div class="mt-4">
                                            <canvas id="wardChartLos" height="120"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">Worklist</div>
                                            <div class="text-xs text-gray-500 mt-1">Nurse station view (who is in which bed) with flags.</div>
                                        </div>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bed</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Flags</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="wardWorklistTbody" class="bg-white divide-y divide-gray-200"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <div class="lg:col-span-2 space-y-6">
                                        <div class="bg-white border border-gray-200 rounded-lg p-5">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-800">Bed & Room Management</div>
                                                    <div class="text-xs text-gray-500 mt-1">Bed assignment, transfers, isolation & fall-risk flags.</div>
                                                </div>
                                            </div>
                                            <div class="mt-4 overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bed</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Assigned Patient</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Isolation</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fall-Risk</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="wardBedsTbody" class="divide-y divide-gray-200"></tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="bg-white border border-gray-200 rounded-lg p-5">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-800">Progress Notes / Ward Notes</div>
                                                    <div id="wardSelectedPatientMeta" class="text-xs text-gray-500 mt-1">Select a patient from Worklist.</div>
                                                </div>
                                                <button id="wardSaveDailyBtn" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" disabled>Save Daily Note</button>
                                            </div>

                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700">Daily Progress Note</label>
                                                <textarea id="wardDailyNote" rows="4" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write daily progress note..."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-6">
                                        <div class="bg-white border border-gray-200 rounded-lg p-5">
                                            <div class="text-sm font-semibold text-gray-800">Discharge / Transfer Prep</div>
                                            <div class="text-xs text-gray-500 mt-1">Ready for discharge checklist and transfer details.</div>

                                            <div class="mt-4 space-y-2">
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="wardChkVitals" type="checkbox" class="h-4 w-4"> Vitals stable</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="wardChkLabs" type="checkbox" class="h-4 w-4"> Labs/Imaging reviewed</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="wardChkMeds" type="checkbox" class="h-4 w-4"> Medications reconciled</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="wardChkEducation" type="checkbox" class="h-4 w-4"> Discharge instructions given</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="wardChkFollowup" type="checkbox" class="h-4 w-4"> Follow-up arranged</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="wardChkReturn" type="checkbox" class="h-4 w-4"> Return precautions documented</label>
                                            </div>

                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700">Transfer (receiving facility)</label>
                                                <input id="wardTransferFacility" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg" placeholder="e.g., City General Hospital" />
                                                <div class="mt-3 grid grid-cols-1 gap-3">
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Transfer Notes</label>
                                                        <textarea id="wardTransferNotes" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg"></textarea>
                                                    </div>
                                                </div>
                                                <div class="mt-3 grid grid-cols-1 gap-2">
                                                    <button id="wardSaveDischargeBtn" type="button" class="w-full px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800" disabled>Save Prep</button>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                        <button id="wardPrintTransferBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50" disabled>Print Transfer Form</button>
                                                        <button id="wardPrintDischargeBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50" disabled>Print Discharge Checklist</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="wardPdeaPanel" class="bg-white border border-gray-200 rounded-lg p-5 hidden">
                                            <div class="text-sm font-semibold text-gray-800">PDEA (Teens)</div>
                                            <div class="mt-4 grid grid-cols-1 gap-3">
                                                <div>
                                                    <label class="block text-[11px] text-gray-600">Teen Intake / Screening</label>
                                                    <textarea id="wardPdeaScreen" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg" placeholder="Substance/mental health/risk behavior screening..."></textarea>
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] text-gray-600">Counseling / Brief Intervention</label>
                                                    <textarea id="wardPdeaCounsel" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg"></textarea>
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] text-gray-600">Case Management / Social Work</label>
                                                    <textarea id="wardPdeaCase" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg"></textarea>
                                                </div>
                                                <div class="grid grid-cols-1 gap-3">
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Consent / Guardianship</label>
                                                        <input id="wardPdeaConsent" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg" placeholder="Guardian name / consent status" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Follow-up Plan</label>
                                                        <textarea id="wardPdeaFollowup" rows="2" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="erDoctorFeedbackSection" class="bg-white rounded-lg shadow-sm overflow-hidden hidden">
                            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Doctor Feedback</h3>
                                    <p class="text-sm text-gray-600 mt-1">Doctor feedback for ER patients.</p>
                                </div>
                                <button id="refreshErDoctorFeedback" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tests</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="erDoctorFeedbackTbody" class="bg-white divide-y divide-gray-200"></tbody>
                                </table>
                            </div>
                        </section>

                        <section id="erClearanceSection" class="hidden">
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-800">Clearance</h2>
                                        <p class="text-sm text-gray-600 mt-1">Medical / discharge / transfer clearance tracking.</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <select id="clearanceFilter" class="px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="all">All</option>
                                            <option value="medical">Medical</option>
                                            <option value="discharge">Discharge</option>
                                            <option value="transfer">Transfer</option>
                                        </select>
                                        <input id="clearanceSearch" type="text" placeholder="Search patient" class="px-3 py-2 border border-gray-200 rounded-lg w-56 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        <button id="clearanceRefreshBtn" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                                    </div>
                                </div>

                                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                        <div>
                                            <div class="text-xs text-gray-500">Pending</div>
                                            <div id="clearanceStatPending" class="text-2xl font-semibold text-gray-900">0</div>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center">
                                            <i class="fas fa-hourglass-half text-yellow-600"></i>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                        <div>
                                            <div class="text-xs text-gray-500">Cleared</div>
                                            <div id="clearanceStatCleared" class="text-2xl font-semibold text-gray-900">0</div>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                                            <i class="fas fa-circle-check text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                        <div>
                                            <div class="text-xs text-gray-500">High Priority</div>
                                            <div id="clearanceStatHigh" class="text-2xl font-semibold text-gray-900">0</div>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                                            <i class="fas fa-triangle-exclamation text-red-600"></i>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                        <div>
                                            <div class="text-xs text-gray-500">Avg Time to Clear (hrs)</div>
                                            <div id="clearanceStatAvg" class="text-2xl font-semibold text-gray-900">0</div>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                            <i class="fas fa-clock text-blue-600"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <div class="lg:col-span-2">
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-800">Clearance Queue</div>
                                                    <div class="text-xs text-gray-500 mt-1">Select an item to review and clear.</div>
                                                </div>
                                            </div>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="clearanceTbody" class="bg-white divide-y divide-gray-200"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-6">
                                        <div class="bg-white border border-gray-200 rounded-lg p-5">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-800">Clearance Review</div>
                                                    <div id="clearanceSelectedMeta" class="text-xs text-gray-500 mt-1">Select an item from the queue.</div>
                                                </div>
                                                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                                                    <i class="fas fa-clipboard-check text-green-600"></i>
                                                </div>
                                            </div>

                                            <div class="mt-4 space-y-2">
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="clearanceChkVitals" type="checkbox" class="h-4 w-4"> Vitals stable</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="clearanceChkLabs" type="checkbox" class="h-4 w-4"> Labs/Imaging reviewed</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="clearanceChkMeds" type="checkbox" class="h-4 w-4"> Medications reconciled</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="clearanceChkPlan" type="checkbox" class="h-4 w-4"> Plan explained / instructions given</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="clearanceChkFollowup" type="checkbox" class="h-4 w-4"> Follow-up arranged</label>
                                            </div>

                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700">Clearance Notes</label>
                                                <textarea id="clearanceNotes" rows="3" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg" placeholder="Notes for clearance / receiving unit"></textarea>
                                            </div>

                                            <div class="mt-4 grid grid-cols-1 gap-2">
                                                <button id="clearanceMarkClearedBtn" type="button" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700" disabled>Mark Cleared</button>
                                                <button id="clearancePrintBtn" type="button" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50" disabled>Print Clearance</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="erRequestsSection" class="bg-white rounded-lg shadow-sm overflow-hidden hidden">
                            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">ER Lab Requests</h3>
                                    <p class="text-sm text-gray-600 mt-1">Track requests and approval status.</p>
                                </div>
                                <button id="refreshErRequests" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tests</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="erRequestsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                                </table>
                            </div>
                        </section>

                        <section id="erLabResultsSection" class="bg-white rounded-lg shadow-sm overflow-hidden hidden">
                            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Lab Test Result</h3>
                                    <p class="text-sm text-gray-600 mt-1">Completed lab requests.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input id="erLabResultsSearch" type="text" placeholder="Search..." class="w-64 max-w-[50vw] px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off">
                                    <button id="refreshErLabResults" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tests</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Released</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap w-44">Status</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="erLabResultsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                                </table>
                            </div>

                            <div id="erLabResultsResultViewPanel" class="hidden border-t border-gray-100">
                                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Result View</h3>
                                        <p class="text-sm text-gray-600 mt-1">Read-only lab test result.</p>
                                    </div>
                                    <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="closeErLabResultsResultView()">Close</button>
                                </div>
                                <div id="erLabResultsResultViewContent" class="p-6"></div>
                            </div>
                        </section>

                        <section id="erXrayResultsSection" class="bg-white rounded-lg shadow-sm overflow-hidden hidden">
                            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Xray Result</h3>
                                    <p class="text-sm text-gray-600 mt-1">Completed imaging studies.</p>
                                </div>
                                <button id="refreshErXrayResults" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                            </div>
                            <div>
                                <?php
                                $includeXrayResultsReleaseCard = true;
                                $includeXrayResultsReleaseModal = false;
                                include __DIR__ . '/includes/xray-results-release.php';
                                ?>
                            </div>
                        </section>

                        <section id="erNursingAssessmentSection" class="p-6 hidden">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Nursing Assessment</h2>
                                    <p class="text-sm text-gray-600 mt-1">Save and review nursing assessments per patient encounter.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button id="erAssessHistoryBtn" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Assessment History</button>
                                </div>
                            </div>

                            <div id="erAssessAlert" class="hidden mt-4 px-4 py-3 rounded-lg text-sm"></div>

                            <div class="mt-6">
                                <div class="bg-white border border-gray-200 rounded-lg p-5">
                                    <div class="text-sm font-semibold text-gray-800">New Assessment</div>

                                    <form id="erAssessForm" class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Patient</label>
                                            <input id="erAssessPatientSearch" type="text" placeholder="Search patient name / ID" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off">
                                            <input id="erAssessPatientId" type="hidden" value="">
                                            <div id="erAssessPatientResults" class="mt-2 border border-gray-200 rounded-lg max-h-56 overflow-auto hidden"></div>
                                            <div id="erAssessPatientSelected" class="mt-2 text-xs text-gray-600"></div>
                                            <div class="mt-2 text-xs text-gray-600">Encounter: <span id="erAssessEncounterMeta">-</span></div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nurse Name</label>
                                            <input id="erAssessNurseName" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Optional">
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">BP Systolic</label>
                                                <input id="erAssessBpSys" type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="mmHg">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">BP Diastolic</label>
                                                <input id="erAssessBpDia" type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="mmHg">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Heart Rate</label>
                                                <input id="erAssessHr" type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="bpm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Respiratory Rate</label>
                                                <input id="erAssessRr" type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="/min">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Temperature</label>
                                                <input id="erAssessTemp" type="number" step="0.1" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="°C">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">SpO₂</label>
                                                <input id="erAssessSpo2" type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="%">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Weight</label>
                                                <input id="erAssessWeight" type="number" step="0.1" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="kg">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Height</label>
                                                <input id="erAssessHeight" type="number" step="0.1" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="cm">
                                            </div>
                                        </div>

                                        <div class="border-t border-gray-100 pt-4">
                                            <div class="text-sm font-semibold text-gray-800">History of Present Illness</div>
                                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">When did the problem start?</label>
                                                    <input id="erAssessHpiStart" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" />
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Duration/Frequency</label>
                                                    <input id="erAssessHpiDuration" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" />
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Severity (mild/moderate/severe)</label>
                                                    <select id="erAssessHpiSeverity" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                                                        <option value="">Select</option>
                                                        <option value="mild">mild</option>
                                                        <option value="moderate">moderate</option>
                                                        <option value="severe">severe</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Associated Symptoms</label>
                                                    <input id="erAssessHpiAssociated" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" />
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <label class="block text-sm font-medium text-gray-700">Aggravating/Relieving factors</label>
                                                <textarea id="erAssessHpiFactors" rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                                            </div>
                                        </div>

                                        <div class="border-t border-gray-100 pt-4">
                                            <div class="text-sm font-semibold text-gray-800">Past Medical History</div>
                                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="erAssessPmhDiabetes" type="checkbox" class="h-4 w-4"> Diabetes</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="erAssessPmhHypertension" type="checkbox" class="h-4 w-4"> Hypertension</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="erAssessPmhAsthma" type="checkbox" class="h-4 w-4"> Asthma</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input id="erAssessPmhHeartDisease" type="checkbox" class="h-4 w-4"> Heart Disease</label>
                                            </div>
                                            <div class="mt-3">
                                                <label class="block text-sm font-medium text-gray-700">Other</label>
                                                <input id="erAssessPmhOther" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" />
                                            </div>
                                        </div>

                                        <div class="border-t border-gray-100 pt-4">
                                            <label class="block text-sm font-medium text-gray-700">Surgical History (if any)</label>
                                            <textarea id="erAssessSurgicalHistory" rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                                        </div>

                                        <div class="border-t border-gray-100 pt-4">
                                            <label class="block text-sm font-medium text-gray-700">Current Medications</label>
                                            <textarea id="erAssessCurrentMedications" rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                                        </div>

                                        <div class="border-t border-gray-100 pt-4">
                                            <div class="text-sm font-semibold text-gray-800">Allergies</div>
                                            <div class="mt-3">
                                                <input id="erAssessAllergiesOther" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter allergies (leave blank if none)" />
                                            </div>
                                        </div>

                                        <div class="border-t border-gray-100 pt-4">
                                            <label class="block text-sm font-medium text-gray-700">Family History (relevant conditions)</label>
                                            <textarea id="erAssessFamilyHistory" rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                                        </div>

                                        <div class="border-t border-gray-100 pt-4">
                                            <div class="text-sm font-semibold text-gray-800">Social History</div>
                                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-700">Smoking</div>
                                                    <div class="mt-2 flex items-center gap-4">
                                                        <label class="flex items-center gap-2 text-sm text-gray-800"><input type="radio" name="erAssessSmoking" value="yes" class="h-4 w-4"> Yes</label>
                                                        <label class="flex items-center gap-2 text-sm text-gray-800"><input type="radio" name="erAssessSmoking" value="no" class="h-4 w-4"> No</label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-700">Alcohol</div>
                                                    <div class="mt-2 flex items-center gap-4">
                                                        <label class="flex items-center gap-2 text-sm text-gray-800"><input type="radio" name="erAssessAlcohol" value="yes" class="h-4 w-4"> Yes</label>
                                                        <label class="flex items-center gap-2 text-sm text-gray-800"><input type="radio" name="erAssessAlcohol" value="no" class="h-4 w-4"> No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <label class="block text-sm font-medium text-gray-700">Occupation</label>
                                                <input id="erAssessOccupation" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" />
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                                            <textarea id="erAssessNotes" rows="4" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Assessment notes..."></textarea>
                                        </div>

                                        <div class="pt-2">
                                            <button id="erAssessSaveBtn" type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Assessment</button>
                                            <button id="erAssessCancelEditBtn" type="button" class="hidden w-full mt-2 px-4 py-3 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Cancel Edit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>

                        <section id="erConsultationNotesSection" class="p-6 hidden">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Consultation Notes</h2>
                                    <p class="text-sm text-gray-600 mt-1">Add and review ER consultation notes per patient encounter.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button id="erConsultAutoFillBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Auto Fill (AI)</button>
                                    <button id="erConsultHistoryBtn" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Notes History</button>
                                </div>
                            </div>

                            <div id="erConsultAlert" class="hidden mt-4 px-4 py-3 rounded-lg text-sm"></div>

                            <div class="mt-6">
                                <div class="bg-white border border-gray-200 rounded-lg p-5">
                                    <div class="text-sm font-semibold text-gray-800">New Note</div>

                                    <div class="mt-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Patient</label>
                                            <input id="erConsultPatientSearch" type="text" placeholder="Search patient name" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off">
                                            <input id="erConsultPatientId" type="hidden" value="">
                                            <div id="erConsultPatientResults" class="mt-2 border border-gray-200 rounded-lg max-h-56 overflow-auto hidden"></div>
                                            <div id="erConsultPatientSelected" class="mt-2 text-xs text-gray-600"></div>
                                            <div class="mt-2 text-xs text-gray-600">Encounter: <span id="erConsultEncounterMeta">-</span></div>
                                        </div>

                                        <div class="mt-4 p-4 border border-gray-200 rounded-lg bg-white">
                                            <div class="text-sm font-semibold text-gray-800">Doctor Consultation Form</div>

                                            <div class="mt-4">
                                                <div class="text-xs font-semibold text-gray-700">Patient Information</div>
                                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Full Name</label>
                                                        <input id="erConsultPatientName" readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Date of Birth / Age</label>
                                                        <input id="erConsultPatientDobAge" readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Gender</label>
                                                        <input id="erConsultPatientGender" readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <div class="text-xs font-semibold text-gray-700">Date of Visit</div>
                                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Date of Visit</label>
                                                        <input id="erConsultVisitDate" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-6">
                                                <div class="text-xs font-semibold text-gray-700">🩺 Doctor Consultation Note (SOAP Format)</div>
                                                <div class="mt-2">
                                                    <label class="block text-[11px] text-gray-600">Chief Complaint</label>
                                                    <input id="erSoapChiefComplaint" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                </div>
                                                <div class="mt-4">
                                                    <div class="text-xs font-semibold text-gray-700">O – Objective</div>
                                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                                                        <div>
                                                            <label class="block text-[11px] text-gray-600">BP</label>
                                                            <input id="erSoapBp" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] text-gray-600">Pulse</label>
                                                            <input id="erSoapPulse" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] text-gray-600">Temp</label>
                                                            <input id="erSoapTemp" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <label class="block text-[11px] text-gray-600">Physical Examination Findings</label>
                                                        <textarea id="erSoapExam" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"></textarea>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <div class="text-xs font-semibold text-gray-700">A – Assessment</div>
                                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-[11px] text-gray-600">Primary Diagnosis</label>
                                                            <input id="erSoapPrimaryDx" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] text-gray-600">Differential Diagnosis (if any)</label>
                                                            <input id="erSoapDifferentialDx" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <div class="text-xs font-semibold text-gray-700">P – Plan</div>
                                                    <div class="mt-2">
                                                        <label class="block text-[11px] text-gray-600">Investigations Ordered</label>
                                                        <textarea id="erSoapInvestigations" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"></textarea>
                                                    </div>
                                                    <div class="mt-3">
                                                        <label class="block text-[11px] text-gray-600">Medications Prescribed</label>
                                                        <textarea id="erSoapMedications" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"></textarea>
                                                    </div>
                                                    <div class="mt-3">
                                                        <label class="block text-[11px] text-gray-600">Treatment/Advice</label>
                                                        <textarea id="erSoapAdvice" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"></textarea>
                                                    </div>
                                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-[11px] text-gray-600">Follow-up</label>
                                                            <input id="erSoapFollowUp" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] text-gray-600">Doctor’s Name & Signature</label>
                                                            <input id="erSoapDoctorSignature" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pt-3">
                                                <button id="erConsultSaveBtn" type="button" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Note</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="erDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Request Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleModal('erDetailsModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="erDetailsContent" class="p-6 max-h-[70vh] overflow-y-auto"></div>
            <div class="p-6 bg-gray-50 border-t flex justify-end">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('erDetailsModal')">Close</button>
            </div>
        </div>
    </div>

    <div id="erDoctorFeedbackModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Doctor Feedback</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleErDoctorFeedbackModal(false)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="erDoctorFeedbackModalContent" class="p-6 max-h-[70vh] overflow-y-auto"></div>
            <div class="p-6 bg-gray-50 border-t flex justify-end">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleErDoctorFeedbackModal(false)">Close</button>
            </div>
        </div>
    </div>

    <div id="erAssessHistoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Assessment History</h3>
                    <div class="text-sm text-gray-600 mt-1">View nursing assessments and search by patient.</div>
                </div>
                <div class="flex items-center gap-2">
                    <button id="erAssessHistoryModalRefreshBtn" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                    <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('erAssessHistoryModal')">Close</button>
                </div>
            </div>
            <div class="p-6 max-h-[75vh] overflow-y-auto">
                <div id="erAssessHistoryListContainer">
                    <div class="mb-4">
                        <input id="erAssessHistorySearch" type="text" placeholder="Search patient name or ID..." class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off" />
                    </div>
                    <div id="erAssessHistory" class="text-sm text-gray-600">Select a patient to view assessments.</div>
                </div>
                <div id="erAssessDetailContainer" class="hidden">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="showErAssessHistoryList()">← Back</button>
                        <div class="flex items-center gap-3">
                            <button type="button" id="erAssessDockEditBtn" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700" disabled>Edit</button>
                            <button type="button" id="erAssessDockSendRequestBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 <?php echo $erCanNpPa ? 'hidden' : ''; ?>" disabled>Send Request</button>
                            <button type="button" id="erAssessDockPrintBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Print</button>
                        </div>
                    </div>
                    <div id="erAssessDetailContent" class="border border-gray-200 rounded-lg bg-white p-4 max-h-[65vh] overflow-y-auto text-sm text-gray-700"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="erAssessSendRequestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Send ER Assessment</h3>
                    <div class="text-sm text-gray-600 mt-1">Submit selected nursing assessment to a doctor.</div>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleErAssessSendRequestModal(false)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Patient</label>
                    <input id="erAssessSendReqPatient" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" readonly>
                    <div id="erAssessSendReqPatientMeta" class="mt-2 text-xs text-gray-500"></div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Doctor</label>
                    <select id="erAssessSendReqDoctor" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                        <option value="">Select doctor</option>
                    </select>
                    <div id="erAssessSendReqDoctorSelected" class="mt-2 text-xs text-gray-600"></div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="erAssessSendReqNotes" rows="3" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg" placeholder="Optional..."></textarea>
                </div>
            </div>
            <div class="p-6 bg-gray-50 border-t flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleErAssessSendRequestModal(false)">Cancel</button>
                <button id="erAssessSendReqSubmitBtn" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 <?php echo $erCanNpPa ? 'hidden' : ''; ?>" disabled>Send Request</button>
            </div>
        </div>
    </div>

    <div id="erAssessSendSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Sent Successfully</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleErAssessSendSuccessModal(false)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="erAssessSendSuccessText" class="text-sm text-gray-700">Request sent successfully.</div>
            </div>
            <div class="p-6 bg-gray-50 border-t flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800" onclick="toggleErAssessSendSuccessModal(false)">OK</button>
            </div>
        </div>
    </div>

    <div id="erConsultSubmitSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[70]">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Submitted Successfully</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleErConsultSubmitSuccessModal(false)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="erConsultSubmitSuccessText" class="text-sm text-gray-700">Submitted successfully.</div>
            </div>
            <div class="p-6 bg-gray-50 border-t flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800" onclick="toggleErConsultSubmitSuccessModal(false)">OK</button>
            </div>
        </div>
    </div>

    <div id="erConsultSavedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[80]">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-sm mx-4">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="text-sm font-semibold text-gray-900">Consultation Note</div>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleErConsultSavedModal(false)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-check text-emerald-600"></i>
                    </div>
                    <div>
                        <div class="text-base font-semibold text-gray-900">Saved successfully</div>
                        <div id="erConsultSavedText" class="mt-1 text-sm text-gray-600">The consultation note has been saved successfully.</div>
                    </div>
                </div>
            </div>
            <div class="p-5 bg-gray-50 border-t flex justify-end">
                <button type="button" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700" onclick="toggleErConsultSavedModal(false)">OK</button>
            </div>
        </div>
    </div>

    <div id="erConsultHistoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <div class="text-lg font-semibold text-gray-900">Notes History</div>
                    <div id="erConsultHistoryModalMeta" class="mt-1 text-xs text-gray-500"></div>
                </div>
                <div class="flex items-center gap-2">
                    <button id="erConsultHistoryModalBackBtn" type="button" class="hidden px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">Back</button>
                    <button id="erConsultHistoryModalRefreshBtn" type="button" class="px-3 py-1.5 text-sm bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                    <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('erConsultHistoryModal')">Close</button>
                </div>
            </div>
            <div class="p-6 max-h-[75vh] overflow-y-auto">
                <div id="erConsultHistorySearchWrap" class="mb-4">
                    <input id="erConsultHistoryPatientSearch" type="text" placeholder="Search patient name or ID..." class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off" />
                    <div id="erConsultHistoryPatientResults" class="hidden mt-2 border border-gray-200 rounded-lg overflow-hidden"></div>
                </div>
                <div id="erConsultHistoryModalList" class="border border-gray-200 rounded-lg p-4 bg-white text-sm text-gray-700">Search a patient to load notes.</div>
            </div>
        </div>
    </div>

    <div id="wardTransferModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Move / Transfer Bed</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleModal('wardTransferModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="wardTransferModalMeta" class="text-sm text-gray-700">-</div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Move to bed</label>
                    <select id="wardTransferTargetBed" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                        <option value="">Select bed</option>
                    </select>
                    <div class="mt-2 text-xs text-gray-500">Only empty beds are shown.</div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Reason / Notes</label>
                    <textarea id="wardTransferReason" rows="3" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg" placeholder="e.g., Isolation requirement / family request / unit transfer"></textarea>
                </div>
            </div>
            <div class="p-6 bg-gray-50 border-t flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('wardTransferModal')">Cancel</button>
                <button id="wardTransferConfirmBtn" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" disabled>Confirm Move</button>
            </div>
        </div>
    </div>

    <div id="wardToast" class="fixed bottom-6 right-6 z-50 hidden">
        <div class="bg-gray-900 text-white text-sm px-4 py-3 rounded-lg shadow-lg flex items-center gap-3">
            <i id="wardToastIcon" class="fas fa-circle-check"></i>
            <div id="wardToastText">Saved</div>
        </div>
    </div>

    <div id="clearanceToast" class="fixed bottom-6 right-6 z-50 hidden">
        <div class="bg-gray-900 text-white text-sm px-4 py-3 rounded-lg shadow-lg flex items-center gap-3">
            <i id="clearanceToastIcon" class="fas fa-circle-check"></i>
            <div id="clearanceToastText">Saved</div>
        </div>
    </div>

    <?php
    $includeXrayResultsReleaseCard = false;
    $includeXrayResultsReleaseModal = true;
    include __DIR__ . '/includes/xray-results-release.php';
    ?>

    <?php include __DIR__ . '/includes/xray-results-release-js.php'; ?>

    <script>
        const ER_CAN_NURSE = <?php echo $erCanNurse ? 'true' : 'false'; ?>;
        const ER_CAN_NPPA = <?php echo $erCanNpPa ? 'true' : 'false'; ?>;

        function toggleModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }

        function escapeHtml(s) {
            return (s ?? '').toString()
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function setErConsultHistoryViewingMode(isViewing) {
            const wrap = document.getElementById('erConsultHistorySearchWrap');
            const results = document.getElementById('erConsultHistoryPatientResults');
            const input = document.getElementById('erConsultHistoryPatientSearch');
            const backBtn = document.getElementById('erConsultHistoryModalBackBtn');

            const viewing = !!isViewing;
            if (wrap) wrap.classList.toggle('hidden', viewing);
            if (backBtn) backBtn.classList.toggle('hidden', !viewing);
            if (!viewing) {
                if (results) {
                    results.classList.add('hidden');
                    results.innerHTML = '';
                }
                if (input) input.value = '';
            }
        }

        function calcAgeFromDob(dob) {
            if (!dob || typeof dob !== 'string') return '';
            const m = dob.match(/^(\d{4})-(\d{2})-(\d{2})/);
            if (!m) return '';
            const y = parseInt(m[1], 10);
            const mo = parseInt(m[2], 10) - 1;
            const d = parseInt(m[3], 10);
            const birth = new Date(y, mo, d);
            if (isNaN(birth.getTime())) return '';
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            const mDiff = today.getMonth() - birth.getMonth();
            if (mDiff < 0 || (mDiff === 0 && today.getDate() < birth.getDate())) age--;
            if (age < 0 || age > 150) return '';
            return String(age);
        }

        function setErConsultPatientInfo(p) {
            const nameEl = document.getElementById('erConsultPatientName');
            const dobAgeEl = document.getElementById('erConsultPatientDobAge');
            const genderEl = document.getElementById('erConsultPatientGender');

            const fullName = (p && (p.full_name || p.name)) ? String(p.full_name || p.name) : '';
            const dob = (p && p.dob) ? String(p.dob) : '';
            const sex = (p && (p.sex || p.gender)) ? String(p.sex || p.gender) : '';
            const age = calcAgeFromDob(dob);
            const dobAge = dob ? (age ? (dob + ' / ' + age) : dob) : (age ? age : '');

            if (nameEl) nameEl.value = fullName;
            if (dobAgeEl) dobAgeEl.value = dobAge;
            if (genderEl) genderEl.value = sex;
        }

        const ER_DEFAULT_TESTS = [
            { test_code: 'cbc', test_name: 'Complete Blood Count (CBC)', price: null },
            { test_code: 'urinalysis', test_name: 'Urinalysis', price: null },
            { test_code: 'rbs', test_name: 'Random Blood Sugar (RBS)', price: null },
            { test_code: 'fbs', test_name: 'Fasting Blood Sugar (FBS)', price: null },
            { test_code: 'bun', test_name: 'BUN', price: null },
            { test_code: 'creatinine', test_name: 'Creatinine', price: null },
            { test_code: 'electrolytes', test_name: 'Electrolytes (Na/K/Cl)', price: null },
            { test_code: 'pregnancy', test_name: 'Pregnancy Test', price: null },
        ];

        let ER_DOCTORS = [];

        function availabilityChip(status, effectiveAvailable) {
            const s = (status ?? '').toString();
            if (effectiveAvailable === false) {
                if (s === 'on_leave') return { cls: 'bg-red-50 text-red-700 border border-red-200', label: 'On Leave' };
                if (s === 'busy') return { cls: 'bg-yellow-50 text-yellow-700 border border-yellow-200', label: 'Busy' };
                return { cls: 'bg-yellow-50 text-yellow-700 border border-yellow-200', label: 'Not Available' };
            }
            if (s === 'on_leave') return { cls: 'bg-red-50 text-red-700 border border-red-200', label: 'On Leave' };
            if (s === 'busy') return { cls: 'bg-yellow-50 text-yellow-700 border border-yellow-200', label: 'Busy' };
            return { cls: 'bg-green-50 text-green-700 border border-green-200', label: 'Available' };
        }

        function getErSelectedDoctorId() {
            const el = document.getElementById('erDoctor');
            if (!el) return null;
            const v = (el.value || '').toString().trim();
            if (!v) return null;
            if (/^\d+$/.test(v)) return Number(v);
            return null;
        }

        function getErSelectedDoctor() {
            const id = getErSelectedDoctorId();
            if (!id) return null;
            return (Array.isArray(ER_DOCTORS) ? ER_DOCTORS : []).find(d => Number(d.id) === Number(id)) || null;
        }

        async function loadErDoctors() {
            const sel = document.getElementById('erDoctor');
            if (!sel) return;

            try {
                const res = await fetch('api/opd/list_doctors.php', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok || !Array.isArray(json.doctors)) {
                    throw new Error('Failed');
                }

                ER_DOCTORS = json.doctors;
                const prev = (sel.value || '').toString();
                sel.innerHTML = '<option value="">Select doctor</option>';
                ER_DOCTORS.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = String(d.id);
                    opt.textContent = (d.full_name || d.username || '').toString();
                    sel.appendChild(opt);
                });
                if (prev) sel.value = prev;
            } catch (e) {
                sel.innerHTML = '<option value="">Unable to load doctors</option>';
                ER_DOCTORS = [];
            }

            renderErDoctorsDropdown('erAssessSendReqDoctor');
            refreshErAssessSendReqState();

            refreshErDoctorAvailability();
        }

        async function refreshErDoctorAvailability() {
            const doc = getErSelectedDoctor();
            const selectedEl = document.getElementById('erDoctorSelected');
            const avEl = document.getElementById('erDoctorAvailability');
            if (selectedEl) selectedEl.textContent = '';
            if (avEl) avEl.innerHTML = '';

            if (selectedEl) {
                if (doc) {
                    const username = (doc.username || '').toString();
                    selectedEl.textContent = username ? ('Selected: ' + (doc.full_name || '') + ' (' + username + ')') : ('Selected: ' + (doc.full_name || ''));
                } else {
                    selectedEl.textContent = '';
                }
            }

            const doctorId = getErSelectedDoctorId();
            if (!doctorId) return;

            if (avEl) {
                avEl.innerHTML = '<span class="px-2 py-1 text-xs rounded-full bg-gray-50 text-gray-700 border border-gray-200">Checking availability...</span>';
            }

            try {
                const url = 'api/opd/check_doctor_availability.php?doctor_id=' + encodeURIComponent(String(doctorId));
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) throw new Error('Failed');

                const status = (json.availability && json.availability.status) ? String(json.availability.status) : 'available';
                const effective = (json.effective_available === undefined) ? true : !!json.effective_available;
                const chip = availabilityChip(status, effective);
                const reason = (json.reason || '').toString();
                const updatedAt = (json.availability && json.availability.updated_at) ? String(json.availability.updated_at) : '';

                if (avEl) {
                    avEl.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${escapeHtml(chip.label)}</span>
                            ${reason ? `<span class="text-xs text-gray-500">${escapeHtml(reason)}</span>` : ''}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">${updatedAt ? ('Last updated: ' + escapeHtml(updatedAt)) : ''}</div>
                    `;
                }
            } catch (e) {
                if (avEl) {
                    avEl.innerHTML = '<span class="px-2 py-1 text-xs rounded-full bg-gray-50 text-gray-700 border border-gray-200">Availability unknown</span>';
                }
            }
        }

        function renderErTestList(tests) {
            const listEl = document.getElementById('erTestList');
            if (!listEl) return;

            const rows = Array.isArray(tests) ? tests : [];
            if (rows.length === 0) {
                listEl.innerHTML = '<div class="text-sm text-gray-500">No laboratory fees configured. Configure in Price Master → Laboratory Fees.</div>';
                return;
            }

            listEl.innerHTML = rows.map(t => {
                const code = (t.test_code || '').toString().trim();
                const name = (t.test_name || '').toString().trim();
                const price = (t.price === null || t.price === undefined || t.price === '') ? null : Number(t.price);
                const label = price !== null && !Number.isNaN(price)
                    ? `${name} (${code.toUpperCase()}) - ₱${price.toFixed(2)}`
                    : `${name} (${code.toUpperCase()})`;
                return `
                    <label class="flex items-center gap-2 text-sm text-gray-800" data-test-label="1" data-test-code="${escapeHtml(code.toLowerCase())}" data-test-name="${escapeHtml(name.toLowerCase())}">
                        <input type="checkbox" class="testChk" value="${escapeHtml(code.toLowerCase())}">
                        ${escapeHtml(label)}
                    </label>
                `;
            }).join('');
        }

        async function loadErLabResults() {
            const tbody = document.getElementById('erLabResultsTbody');
            if (!tbody) return;

            const panel = document.getElementById('erLabResultsResultViewPanel');
            const content = document.getElementById('erLabResultsResultViewContent');
            if (panel) panel.classList.add('hidden');
            if (content) content.innerHTML = '';

            const res = await fetch('api/lab/list_requests.php?status=completed', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                return;
            }

            const rows = Array.isArray(json.requests) ? json.requests : [];
            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">No lab results found.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(r => {
                const reqNo = escapeHtml(r.request_no || ('#' + String(r.id)));
                const name = escapeHtml(r.full_name || '');
                const code = escapeHtml(r.patient_code || '');
                const tests = escapeHtml(r.tests || '');
                const releasedBy = escapeHtml(r.released_by || '');
                const releasedAt = r.released_at ? escapeHtml(new Date(r.released_at).toLocaleString()) : '';
                const cashier = String(r.cashier_status || '').toLowerCase();
                let statusLabel = 'Completed';
                let statusCls = 'bg-green-100 text-green-800';
                if (cashier === 'submitted') {
                    statusLabel = 'Submitted to Cashier';
                    statusCls = 'bg-green-100 text-green-800';
                } else if (cashier === 'pending_fee') {
                    statusLabel = 'Pending Fee';
                    statusCls = 'bg-yellow-100 text-yellow-800';
                }

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${reqNo}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(new Date(r.created_at).toLocaleString())}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${name}</div>
                            <div class="text-sm text-gray-500">ID: ${code}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800">${tests}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(r.chief_complaint || '')}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800">${releasedBy || '-'}</div>
                            <div class="text-xs text-gray-500">${releasedAt || ''}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full ${statusCls}">${escapeHtml(statusLabel)}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="viewErLabResult(${Number(r.id)})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');

            applyErLabResultsFilter();
        }

        function applyErLabResultsFilter() {
            const input = document.getElementById('erLabResultsSearch');
            const tbody = document.getElementById('erLabResultsTbody');
            if (!tbody) return;

            const q = (input ? input.value : '').toString().trim().toLowerCase();
            const trs = Array.from(tbody.querySelectorAll('tr'));
            if (!q) {
                trs.forEach(tr => { tr.style.display = ''; });
                return;
            }

            trs.forEach(tr => {
                const text = (tr.textContent || '').toLowerCase();
                tr.style.display = text.includes(q) ? '' : 'none';
            });
        }

        function erFormatPatientAddress(r) {
            const parts = [r.street_address, r.barangay, r.city, r.province, r.zip_code]
                .map(x => (x ?? '').toString().trim())
                .filter(Boolean);
            return parts.join(', ');
        }

        function erCalculateAgeFromDob(dob) {
            const s = (dob ?? '').toString().trim();
            if (!s || s === '0000-00-00') return '';
            const d = new Date(s);
            if (Number.isNaN(d.getTime())) return '';
            const today = new Date();
            let age = today.getFullYear() - d.getFullYear();
            const m = today.getMonth() - d.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < d.getDate())) age--;
            return String(Math.max(0, age));
        }

        function erFormatDateIssued(d) {
            try {
                const dt = (d instanceof Date) ? d : new Date(d);
                if (Number.isNaN(dt.getTime())) return '';
                return dt.toLocaleDateString([], { year: 'numeric', month: '2-digit', day: '2-digit' });
            } catch (e) {
                return '';
            }
        }

        function erNormalizeTestName(name) {
            return (name ?? '').toString().trim().toLowerCase();
        }

        function erIsUrinalysisTest(name) {
            const n = erNormalizeTestName(name);
            return n === 'urinalysis' || n.includes('urinalysis');
        }

        function erIsCbcTest(name) {
            const n = erNormalizeTestName(name);
            return n === 'complete blood count (cbc)' || n === 'cbc' || n.includes('complete blood count') || n.includes('cbc');
        }

        function erIsRbsTest(name) {
            const n = erNormalizeTestName(name);
            return n === 'random blood sugar (rbs)' || n === 'rbs' || n.includes('random blood sugar') || n.includes('rbs');
        }

        function erIsBunTest(name) {
            const n = erNormalizeTestName(name);
            return n === 'bun' || n.includes('bun');
        }

        function erIsCreatinineTest(name) {
            const n = erNormalizeTestName(name);
            return n === 'creatinine' || n.includes('creatinine');
        }

        function erIsElectrolytesTest(name) {
            const n = erNormalizeTestName(name);
            return n === 'electrolytes (na/k/cl)' || n === 'electrolytes' || n.includes('electrolytes') || n.includes('na/k/cl');
        }

        function erParseLabeledResultText(resultText) {
            const out = {};
            const text = (resultText ?? '').toString();
            const lines = text.split(/\r?\n/);
            for (const rawLine of lines) {
                const line = rawLine.trim();
                if (!line) continue;
                const m = line.match(/^([A-Za-z0-9\s\-/()]+)\s*:\s*(.*)$/);
                if (!m) continue;
                const key = m[1]
                    .trim()
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '_')
                    .replace(/^_+|_+$/g, '');
                out[key] = (m[2] ?? '').toString();
            }
            return out;
        }

        function erFormalLineInput(opts) {
            const value = escapeHtml(opts.value || '');
            const unit = escapeHtml(opts.unit || '');
            const label = escapeHtml(opts.label || '');
            return `
                <div class="grid grid-cols-12 items-center gap-2">
                    <div class="text-xs font-semibold text-gray-800 col-span-4">${label}</div>
                    <div class="col-span-7">
                        <input class="w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1" value="${value}" readonly />
                    </div>
                    <div class="col-span-1 text-right">
                        ${unit ? `<div class="text-xs text-gray-600 whitespace-nowrap">${unit}</div>` : ''}
                    </div>
                </div>
            `;
        }

        function erRenderCbcEntryCard(it) {
            const parsed = erParseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';
            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">COMPLETE BLOOD COUNT (CBC)</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-4">
                                    ${erFormalLineInput({ label: 'HEMOGLOBIN', value: v('hemoglobin'), unit: 'g/dL' })}
                                    ${erFormalLineInput({ label: 'HEMATOCRIT', value: v('hematocrit'), unit: '%' })}
                                    ${erFormalLineInput({ label: 'WBC', value: v('wbc'), unit: '' })}
                                </div>
                                <div class="space-y-4">
                                    ${erFormalLineInput({ label: 'RBC', value: v('rbc'), unit: '' })}
                                    ${erFormalLineInput({ label: 'PLATELET', value: v('platelet'), unit: '' })}
                                    ${erFormalLineInput({ label: 'OTHERS', value: v('others'), unit: '' })}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function erRenderRbsEntryCard(it) {
            const parsed = erParseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';
            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">BLOOD SUGAR</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${erFormalLineInput({ label: 'RESULT', value: v('blood_sugar'), unit: 'mg/dL' })}
                                ${erFormalLineInput({ label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function erRenderBunEntryCard(it) {
            const parsed = erParseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';
            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">BUN</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${erFormalLineInput({ label: 'BUN', value: v('bun'), unit: 'mg/dL' })}
                                ${erFormalLineInput({ label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function erRenderCreatinineEntryCard(it) {
            const parsed = erParseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';
            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">CREATININE</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${erFormalLineInput({ label: 'CREATININE', value: v('creatinine'), unit: 'mg/dL' })}
                                ${erFormalLineInput({ label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function erRenderElectrolytesEntryCard(it) {
            const parsed = erParseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';
            const sodium = v('sodium_na') || v('sodium') || '';
            const potassium = v('potassium_k') || v('potassium') || '';
            const chloride = v('chloride_cl') || v('chloride') || '';
            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">ELECTROLYTES (Na/K/Cl)</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${erFormalLineInput({ label: 'SODIUM (Na)', value: sodium, unit: 'mmol/L' })}
                                ${erFormalLineInput({ label: 'POTASSIUM (K)', value: potassium, unit: 'mmol/L' })}
                                ${erFormalLineInput({ label: 'CHLORIDE (Cl)', value: chloride, unit: 'mmol/L' })}
                                ${erFormalLineInput({ label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function erRenderUrinalysisEntryCard(it) {
            const parsed = erParseLabeledResultText(it.result_text || '');
            const v = (k) => escapeHtml(parsed[k] ?? '');
            const block = (label, field) => `
                <div class="grid grid-cols-3 items-center gap-2">
                    <div class="text-xs font-semibold text-gray-700">${escapeHtml(label)}</div>
                    <input class="col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" value="${v(field)}" readonly />
                </div>
            `;

            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="text-center text-base font-extrabold tracking-wide">URINALYSIS</div>

                            <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    ${block('COLOR', 'color')}
                                    ${block('TRANSPARENCY', 'transparency')}
                                    ${block('PROTEIN', 'protein')}
                                    ${block('GLUCOSE', 'glucose')}
                                    ${block('PH', 'ph')}
                                    ${block('SPECIFIC GRAVITY', 'specific_gravity')}
                                </div>

                                <div class="space-y-3">
                                    ${block('WBC', 'wbc')}
                                    ${block('RBC', 'rbc')}
                                    ${block('CAST', 'cast')}
                                    ${block('BACTERIA', 'bacteria')}
                                    ${block('EPITHELIAL CELLS', 'epithelial_cells')}
                                    ${block('CRYSTALS', 'crystals')}
                                    ${block('OTHERS', 'others')}
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-3">
                                ${block('PREGNANCY TEST', 'pregnancy_test')}
                                ${block('SYPHILIS TEST', 'syphilis_test')}
                                ${block('HIV TEST / SD BIOLINE HIV 1/2', 'hiv_test_sd_bioline_hiv_1_2')}
                                ${block('HEPATITIS B SCREENING (HBsAg)', 'hepatitis_b_screening_hbsag')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function erRenderInvoiceHtml(inv) {
            if (!inv) {
                return '<div class="text-sm text-gray-600">No invoice found for this lab result.</div>';
            }

            const items = Array.isArray(inv.items) ? inv.items : [];
            const status = (inv.status || '').toString();
            const total = (inv.total || '0.00').toString();
            const paid = (inv.paid || '0.00').toString();
            const balance = (inv.balance || '0.00').toString();

            return `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <div class="text-xs font-semibold text-gray-600">INVOICE #</div>
                            <div class="text-sm font-semibold text-gray-900">${escapeHtml(String(inv.id || ''))}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-600">STATUS</div>
                            <div class="text-sm font-semibold text-gray-900">${escapeHtml(status.toUpperCase())}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-600">DATE</div>
                            <div class="text-sm font-semibold text-gray-900">${escapeHtml(String(inv.created_at || ''))}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-semibold text-gray-600">TOTAL</div>
                            <div class="text-sm font-extrabold text-gray-900">₱${escapeHtml(total)}</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${items.map(it => `
                                    <tr>
                                        <td class="px-3 py-2 text-sm text-gray-800">${escapeHtml(String(it.description || ''))}</td>
                                        <td class="px-3 py-2 text-sm text-gray-800 text-right">${escapeHtml(String(it.qty ?? ''))}</td>
                                        <td class="px-3 py-2 text-sm text-gray-800 text-right">₱${escapeHtml(String(it.unit_price || '0.00'))}</td>
                                        <td class="px-3 py-2 text-sm text-gray-800 text-right">₱${escapeHtml(String(it.subtotal || '0.00'))}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2"></div>
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Paid</span>
                                <span class="font-semibold text-gray-900">₱${escapeHtml(paid)}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Balance</span>
                                <span class="font-semibold text-gray-900">₱${escapeHtml(balance)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        async function loadErLabResultInvoice(requestId) {
            const container = document.getElementById('erLabResultInvoiceContainer');
            if (!container) return;

            container.innerHTML = '<div class="text-sm text-gray-600">Loading...</div>';
            const url = 'api/cashier/get_invoice_by_source.php?source_module=lab_request&source_id=' + encodeURIComponent(String(requestId));
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                container.innerHTML = '<div class="text-sm text-red-600">Unable to load invoice.</div>';
                return;
            }

            container.innerHTML = erRenderInvoiceHtml(json.invoice || null);
        }

        async function viewErLabResult(requestId) {
            const panel = document.getElementById('erLabResultsResultViewPanel');
            const content = document.getElementById('erLabResultsResultViewContent');
            if (!panel || !content) return;

            panel.classList.remove('hidden');
            content.innerHTML = '<div class="text-sm text-gray-600">Loading...</div>';

            const res = await fetch('api/lab/get_request.php?id=' + encodeURIComponent(String(requestId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load result.</div>';
                try { panel.scrollIntoView({ behavior: 'smooth', block: 'start' }); } catch (e) {}
                return;
            }

            const r = json.request || {};
            const items = Array.isArray(json.items) ? json.items : [];
            const age = erCalculateAgeFromDob(r.dob);
            const sex = (r.sex ?? '').toString();
            const addr = erFormatPatientAddress(r);
            const dateIssued = erFormatDateIssued(new Date());
            const releasedByValue = (items[0]?.released_by || '').toString();

            content.innerHTML = `
                <div class="space-y-4" data-er-readonly-view="1">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Released by / MedTech name</div>
                        <div class="p-6">
                            <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg" value="${escapeHtml(releasedByValue)}" readonly />
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Patient’s Basic Info</div>
                        <div class="p-6">
                            <div class="text-center">
                                <div class="text-sm font-semibold text-gray-900"></div>
                            </div>

                            <div class="mt-5 space-y-4">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                    <div class="lg:col-span-2">
                                        <div class="text-xs font-semibold text-gray-800">NAME:</div>
                                        <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(r.full_name || '')}</div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">AGE:</div>
                                            <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(age)}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">SEX:</div>
                                            <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(sex)}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                    <div class="lg:col-span-2">
                                        <div class="text-xs font-semibold text-gray-800">ADDRESS:</div>
                                        <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(addr)}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">DATE ISSUED:</div>
                                        <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(dateIssued)}</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">BLOOD TYPE:</div>
                                        <input id="erLabViewHeaderBloodType" type="text" value="${escapeHtml((r.blood_type || '').toString())}" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" readonly />
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">HEMATOCRIT:</div>
                                            <input id="erLabViewHeaderHematocrit" type="text" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" readonly />
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">HEMOGLOBIN:</div>
                                            <input id="erLabViewHeaderHemoglobin" type="text" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">BLOOD SUGAR:</div>
                                        <div class="mt-1 flex items-end gap-2">
                                            <input id="erLabViewHeaderBloodSugar" type="text" class="flex-1 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" readonly />
                                            <div class="text-xs text-gray-700 whitespace-nowrap">mg/dL</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">REQUEST NO:</div>
                                            <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(r.request_no || '')}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">PATIENT ID:</div>
                                            <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(r.patient_code || '')}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">ER Lab Request Form</div>
                        <div class="p-6 space-y-2">
                            <div><span class="text-xs text-gray-500">Chief Complaint:</span> <span class="text-sm font-semibold">${escapeHtml(r.chief_complaint || '')}</span></div>
                            <div><span class="text-xs text-gray-500">Triage Level:</span> <span class="text-sm font-semibold">${escapeHtml(r.triage_level || '')}</span></div>
                            <div><span class="text-xs text-gray-500">Notes:</span> <span class="text-sm font-semibold">${escapeHtml(r.notes || '')}</span></div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Lab Result Form</div>
                        <div class="p-6 space-y-4">
                            ${items.map(it => {
                                let card = '';
                                if (erIsUrinalysisTest(it.test_name || '')) {
                                    card = erRenderUrinalysisEntryCard(it, r);
                                } else if (erIsCbcTest(it.test_name || '')) {
                                    card = erRenderCbcEntryCard(it, r);
                                } else if (erIsRbsTest(it.test_name || '')) {
                                    card = erRenderRbsEntryCard(it, r);
                                } else if (erIsBunTest(it.test_name || '')) {
                                    card = erRenderBunEntryCard(it, r);
                                } else if (erIsCreatinineTest(it.test_name || '')) {
                                    card = erRenderCreatinineEntryCard(it, r);
                                } else if (erIsElectrolytesTest(it.test_name || '')) {
                                    card = erRenderElectrolytesEntryCard(it, r);
                                } else {
                                    card = `
                                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                                            <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                                            <div class="p-4">
                                                <textarea class="w-full px-3 py-2 border border-gray-200 rounded-lg" rows="5" readonly>${escapeHtml(it.result_text || '')}</textarea>
                                            </div>
                                        </div>
                                    `;
                                }

                                const releasedAt = it.released_at ? ('(' + escapeHtml(it.released_at) + ')') : '';
                                const releasedLine = (it.released_by || it.released_at)
                                    ? `<div class="text-xs text-gray-500 mt-2">Released by: ${escapeHtml(it.released_by || '')} ${releasedAt}</div>`
                                    : '';
                                return `
                                    <div>
                                        ${card}
                                        ${releasedLine}
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Laboratory Billing</div>
                        <div class="p-6" id="erLabResultInvoiceContainer">
                            <div class="text-sm text-gray-600">Loading...</div>
                        </div>
                    </div>
                </div>
            `;

            const cbcItem = items.find(x => erIsCbcTest(x.test_name || ''));
            const rbsItem = items.find(x => erIsRbsTest(x.test_name || ''));
            const cbcParsed = cbcItem ? erParseLabeledResultText(cbcItem.result_text || '') : {};
            const rbsParsed = rbsItem ? erParseLabeledResultText(rbsItem.result_text || '') : {};
            const hb = (cbcParsed.hemoglobin ?? '').toString();
            const hct = (cbcParsed.hematocrit ?? '').toString();
            const bs = (rbsParsed.blood_sugar ?? '').toString();
            const hbEl = document.getElementById('erLabViewHeaderHemoglobin');
            const hctEl = document.getElementById('erLabViewHeaderHematocrit');
            const bsEl = document.getElementById('erLabViewHeaderBloodSugar');
            if (hbEl) hbEl.value = hb.trim();
            if (hctEl) hctEl.value = hct.trim();
            if (bsEl) bsEl.value = bs.trim();

            await loadErLabResultInvoice(Number(requestId));

            try { panel.scrollIntoView({ behavior: 'smooth', block: 'start' }); } catch (e) {}
        }

        function closeErLabResultsResultView() {
            const panel = document.getElementById('erLabResultsResultViewPanel');
            const content = document.getElementById('erLabResultsResultViewContent');
            if (content) content.innerHTML = '';
            if (panel) panel.classList.add('hidden');
        }

        function filterErTestList(q) {
            const needle = (q || '').toString().trim().toLowerCase();
            document.querySelectorAll('#erTestList [data-test-label="1"]').forEach(el => {
                if (!needle) {
                    el.classList.remove('hidden');
                    return;
                }
                const code = (el.getAttribute('data-test-code') || '').toLowerCase();
                const name = (el.getAttribute('data-test-name') || '').toLowerCase();
                const ok = code.includes(needle) || name.includes(needle);
                el.classList.toggle('hidden', !ok);
            });
        }

        function showErAssessAlert(type, text) {
            const el = document.getElementById('erAssessAlert');
            if (!el) return;
            el.classList.remove('hidden');
            el.classList.remove('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            el.classList.remove('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            if (type === 'ok') {
                el.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                el.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            el.textContent = text;
            try { el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); } catch (e) {}
        }

        let erAssessCache = [];
        let erAssessEncounterCache = null;
        let erAssessAllCache = [];
        let erAssessHistorySelectedId = 0;
        let erAssessHistorySelectedPatientName = '';
        let erAssessHistorySelectedPatientCode = '';
        let erAssessHistorySelectedWhen = '';
        let erAssessEditingId = null;

        function setErAssessEncounterMeta(encounter) {
            const meta = document.getElementById('erAssessEncounterMeta');
            if (!meta) return;
            if (encounter && encounter.encounter_no) {
                meta.textContent = String(encounter.encounter_no) + ' • ' + String(encounter.status || '') + (encounter.started_at ? (' • Started: ' + String(encounter.started_at)) : '');
            } else {
                meta.textContent = '-';
            }
        }

        async function loadErAssessmentsByPatient(patientId) {
            const res = await fetch('api/er_assessment/list.php?patient_id=' + encodeURIComponent(String(patientId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                throw new Error((json && json.error) ? json.error : 'Failed to load assessments');
            }
            erAssessEncounterCache = json.encounter || null;
            erAssessCache = Array.isArray(json.assessments) ? json.assessments : [];

            setErAssessEncounterMeta(erAssessEncounterCache);
        }

        function refreshErAssessDockSendRequestState() {
            const btn = document.getElementById('erAssessDockSendRequestBtn');
            if (!btn) return;
            btn.disabled = !erAssessHistorySelectedId;
        }

        function renderErDoctorsDropdown(selectId) {
            const sel = document.getElementById(selectId);
            if (!sel) return;
            const prev = (sel.value || '').toString();
            sel.innerHTML = '<option value="">Select doctor</option>';
            (Array.isArray(ER_DOCTORS) ? ER_DOCTORS : []).forEach(d => {
                const opt = document.createElement('option');
                opt.value = String(d.id);
                opt.textContent = (d.full_name || d.username || '').toString();
                sel.appendChild(opt);
            });
            if (prev) sel.value = prev;
        }

        function toggleErAssessSendRequestModal(show) {
            const el = document.getElementById('erAssessSendRequestModal');
            if (!el) return;
            if (show) {
                el.style.zIndex = '9999';
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }

        function toggleErAssessSendSuccessModal(show, text) {
            const el = document.getElementById('erAssessSendSuccessModal');
            if (!el) return;
            const msg = document.getElementById('erAssessSendSuccessText');
            if (msg && typeof text === 'string' && text.trim() !== '') {
                msg.textContent = text;
            } else if (msg && show) {
                msg.textContent = 'Request sent successfully.';
            }
            if (show) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }

        function toggleErConsultSubmitSuccessModal(show, text) {
            const el = document.getElementById('erConsultSubmitSuccessModal');
            if (!el) return;
            const msg = document.getElementById('erConsultSubmitSuccessText');
            if (msg && typeof text === 'string' && text.trim() !== '') {
                msg.textContent = text;
            } else if (msg && show) {
                msg.textContent = 'Submitted successfully.';
            }
            if (show) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }

        function toggleErConsultSavedModal(show, text) {
            const el = document.getElementById('erConsultSavedModal');
            if (!el) return;
            const msg = document.getElementById('erConsultSavedText');
            if (msg && typeof text === 'string' && text.trim() !== '') {
                msg.textContent = text;
            } else if (msg && show) {
                msg.textContent = 'The consultation note has been saved successfully.';
            }
            if (show) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }

        function refreshErAssessSendReqState() {
            const btn = document.getElementById('erAssessSendReqSubmitBtn');
            const sel = document.getElementById('erAssessSendReqDoctor');
            if (!btn || !sel) return;
            const docId = (sel.value || '').toString().trim();
            btn.disabled = !(erAssessHistorySelectedId && docId);
        }

        async function loadErAssessmentsAll(q) {
            const qs = (q || '').toString().trim();
            const url = 'api/er_assessment/list.php?all=1' + (qs ? ('&q=' + encodeURIComponent(qs)) : '');
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                throw new Error((json && json.error) ? json.error : 'Failed to load assessments');
            }
            erAssessAllCache = Array.isArray(json.assessments) ? json.assessments : [];
        }

        function parseVitalsJson(v) {
            if (!v) return null;
            if (typeof v === 'object') return v;
            try { return JSON.parse(String(v)); } catch (e) { return null; }
        }

        function renderVitals(vitals) {
            const v = vitals || {};
            const bpSys = ((v.bp_sys ?? v.bp_systolic) ?? '').toString();
            const bpDia = ((v.bp_dia ?? v.bp_diastolic) ?? '').toString();
            const bp = (bpSys || bpDia) ? (bpSys + '/' + bpDia).replace(/^\/+|\/$/g, '') : ((v.bp ?? '') || '').toString();

            return `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div><span class="text-xs text-gray-500">BP:</span> <span class="font-semibold">${escapeHtml(bp || '-')}</span></div>
                        <div><span class="text-xs text-gray-500">RR:</span> <span class="font-semibold">${escapeHtml(((v.rr ?? '') || '-').toString())}</span></div>
                        <div><span class="text-xs text-gray-500">SpO₂:</span> <span class="font-semibold">${escapeHtml(((v.spo2 ?? '') || '-').toString())}</span></div>
                        <div><span class="text-xs text-gray-500">Height:</span> <span class="font-semibold">${escapeHtml(((v.height ?? '') || '-').toString())}</span></div>
                    </div>
                    <div class="space-y-2">
                        <div><span class="text-xs text-gray-500">HR:</span> <span class="font-semibold">${escapeHtml(((v.hr ?? '') || '-').toString())}</span></div>
                        <div><span class="text-xs text-gray-500">Temp:</span> <span class="font-semibold">${escapeHtml(((v.temp ?? '') || '-').toString())}</span></div>
                        <div><span class="text-xs text-gray-500">Weight:</span> <span class="font-semibold">${escapeHtml(((v.weight ?? '') || '-').toString())}</span></div>
                    </div>
                </div>
            `;
        }

        function closeErAssessDock() {
            const el = document.getElementById('erAssessDockContent');
            if (el) el.innerHTML = 'Select an assessment to view.';
            const sendBtn = document.getElementById('erAssessDockSendRequestBtn');
            if (sendBtn) sendBtn.disabled = true;
            const editBtn = document.getElementById('erAssessDockEditBtn');
            if (editBtn) editBtn.disabled = true;
        }

        function shouldUseErAssessDock() {
            const m = document.getElementById('erAssessHistoryModal');
            if (!m || m.classList.contains('hidden')) return false;
            return window.matchMedia && window.matchMedia('(min-width: 1024px)').matches;
        }

        function normalizeErAssessmentJson(assessExtraRaw) {
            const assessExtra = (assessExtraRaw && typeof assessExtraRaw === 'object') ? assessExtraRaw : {};
            const pmh = (assessExtra.pmh && typeof assessExtra.pmh === 'object') ? assessExtra.pmh : {
                diabetes: !!assessExtra.pmh_diabetes,
                hypertension: !!assessExtra.pmh_hypertension,
                asthma: !!assessExtra.pmh_asthma,
                heart_disease: !!assessExtra.pmh_heart_disease,
                other: assessExtra.pmh_other || '',
            };
            const social = (assessExtra.social && typeof assessExtra.social === 'object') ? assessExtra.social : {
                smoking: assessExtra.social_smoking || '',
                alcohol: assessExtra.social_alcohol || '',
                occupation: assessExtra.occupation || '',
            };
            const hpi = (assessExtra.hpi && typeof assessExtra.hpi === 'object') ? assessExtra.hpi : {
                start: assessExtra.hpi_start || '',
                duration: assessExtra.hpi_duration || '',
                severity: assessExtra.hpi_severity || '',
                associated: assessExtra.hpi_associated || '',
                factors: assessExtra.hpi_factors || '',
            };

            return { assessExtra, pmh, social, hpi };
        }

        async function openErAssessDetailsFromHistory(assessmentId) {
            erAssessHistorySelectedId = Number(assessmentId || 0);
            refreshErAssessDockSendRequestState();

            const row = (Array.isArray(erAssessAllCache) ? erAssessAllCache : []).find(r => Number(r.id) === Number(assessmentId)) || null;
            if (!row) {
                const detailContent = document.getElementById('erAssessDetailContent');
                if (detailContent) detailContent.innerHTML = '<div class="text-sm text-red-600">Assessment not found.</div>';
                showErAssessDetail();
                return;
            }

            erAssessHistorySelectedPatientName = (row.full_name || '').toString();
            erAssessHistorySelectedPatientCode = (row.patient_code || '').toString();
            erAssessHistorySelectedWhen = row.created_at ? new Date(row.created_at).toLocaleString() : '';

            const vitals = parseVitalsJson(row.vitals_json) || {};
            const assessExtraRaw = parseVitalsJson(row.assessment_json) || {};
            const { assessExtra, pmh, social, hpi } = normalizeErAssessmentJson(assessExtraRaw);

            const detailHtml = `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-800">Patient</div>
                            <div class="text-sm text-gray-700 mt-2">${escapeHtml(row.full_name || '')}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(row.patient_code || '')}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-800">Assessment</div>
                            <div class="text-xs text-gray-500 mt-2">When: ${row.created_at ? escapeHtml(new Date(row.created_at).toLocaleString()) : ''}</div>
                            <div class="text-xs text-gray-500">Nurse: ${escapeHtml(row.nurse_name || '-')}</div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Vitals</div>
                        <div class="mt-3">${renderVitals(vitals)}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">History of Present Illness</div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                            <div><span class="text-xs text-gray-500">Start:</span> <span class="font-semibold">${escapeHtml(hpi.start || '-')}</span></div>
                            <div><span class="text-xs text-gray-500">Duration/Frequency:</span> <span class="font-semibold">${escapeHtml(hpi.duration || '-')}</span></div>
                            <div><span class="text-xs text-gray-500">Severity:</span> <span class="font-semibold">${escapeHtml(hpi.severity || '-')}</span></div>
                            <div><span class="text-xs text-gray-500">Associated Symptoms:</span> <span class="font-semibold">${escapeHtml(hpi.associated || '-')}</span></div>
                        </div>
                        <div class="mt-3 text-sm text-gray-700"><span class="text-xs text-gray-500">Aggravating/Relieving:</span> <span class="font-semibold">${escapeHtml(hpi.factors || '-')}</span></div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Past Medical History</div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-700">
                            <div><span class="font-semibold">Diabetes:</span> ${pmh.diabetes ? 'Yes' : 'No'}</div>
                            <div><span class="font-semibold">Hypertension:</span> ${pmh.hypertension ? 'Yes' : 'No'}</div>
                            <div><span class="font-semibold">Asthma:</span> ${pmh.asthma ? 'Yes' : 'No'}</div>
                            <div><span class="font-semibold">Heart Disease:</span> ${pmh.heart_disease ? 'Yes' : 'No'}</div>
                        </div>
                        <div class="mt-3 text-sm text-gray-700"><span class="font-semibold">Other:</span> ${escapeHtml(pmh.other || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Surgical History</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(assessExtra.surgical_history || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Current Medications</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(assessExtra.current_medications || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Allergies</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(assessExtra.allergies_other || assessExtra.allergies || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Family History</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(assessExtra.family_history || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Social History</div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-700">
                            <div><span class="font-semibold">Smoking:</span> ${escapeHtml(social.smoking || '-')}</div>
                            <div><span class="font-semibold">Alcohol:</span> ${escapeHtml(social.alcohol || '-')}</div>
                            <div class="md:col-span-2"><span class="font-semibold">Occupation:</span> ${escapeHtml(social.occupation || '-')}</div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Notes</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(row.notes || '-')}</div>
                    </div>
                </div>
            `;

            const detailContent = document.getElementById('erAssessDetailContent');
            if (detailContent) detailContent.innerHTML = detailHtml;
            showErAssessDetail();

            const printBtn = document.getElementById('erAssessDockPrintBtn');
            if (printBtn) {
                printBtn.onclick = function () {
                    const w = window.open('', '_blank');
                    if (!w) return;
                    w.document.write('<html><head><title>Nursing Assessment</title>');
                    w.document.write('<meta charset="utf-8"/>');
                    w.document.write('</head><body>');
                    w.document.write(detailHtml);
                    w.document.write('</body></html>');
                    w.document.close();
                    w.focus();
                    w.print();
                };
            }

            const editBtn = document.getElementById('erAssessDockEditBtn');
            if (editBtn) editBtn.disabled = !erAssessHistorySelectedId;
        }

        function setErAssessEditMode(id) {
            erAssessEditingId = (id && Number(id) > 0) ? Number(id) : null;
            const saveBtn = document.getElementById('erAssessSaveBtn');
            const cancelBtn = document.getElementById('erAssessCancelEditBtn');
            if (saveBtn) saveBtn.textContent = erAssessEditingId ? 'Update Assessment' : 'Save Assessment';
            if (cancelBtn) cancelBtn.classList.toggle('hidden', !erAssessEditingId);
        }

        function fillErAssessmentFormFromRow(row) {
            if (!row) return;

            const pidEl = document.getElementById('erAssessPatientId');
            const input = document.getElementById('erAssessPatientSearch');
            const selected = document.getElementById('erAssessPatientSelected');
            if (pidEl) pidEl.value = String(Number(row.patient_id || 0) || '');
            if (input) input.value = (row.patient_code || '').toString();
            if (selected) selected.textContent = 'Selected: ' + (row.full_name || '') + ' (' + (row.patient_code || '') + ')';

            erAssessEncounterCache = { id: Number(row.encounter_id || 0), encounter_no: row.encounter_no || '' };
            setErAssessEncounterMeta(erAssessEncounterCache);

            const vitals = parseVitalsJson(row.vitals_json) || {};
            const assessExtraRaw = parseVitalsJson(row.assessment_json) || {};
            const { pmh, social, hpi, assessExtra } = normalizeErAssessmentJson(assessExtraRaw);

            const setVal = (id, v) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.value = (v ?? '').toString();
            };

            setVal('erAssessBpSys', vitals.bp_sys ?? vitals.bp_systolic ?? vitals.bp);
            setVal('erAssessBpDia', vitals.bp_dia ?? vitals.bp_diastolic ?? '');
            setVal('erAssessHr', vitals.hr);
            setVal('erAssessRr', vitals.rr);
            setVal('erAssessTemp', vitals.temp);
            setVal('erAssessSpo2', vitals.spo2);
            setVal('erAssessWeight', vitals.weight);
            setVal('erAssessHeight', vitals.height);

            setVal('erAssessNurseName', row.nurse_name);

            setVal('erAssessHpiStart', hpi.start);
            setVal('erAssessHpiDuration', hpi.duration);
            setVal('erAssessHpiSeverity', hpi.severity);
            setVal('erAssessHpiAssociated', hpi.associated);
            setVal('erAssessHpiFactors', hpi.factors);

            const setCheck = (id, v) => {
                const el = document.getElementById(id);
                if (el) el.checked = !!v;
            };
            setCheck('erAssessPmhDiabetes', pmh.diabetes);
            setCheck('erAssessPmhHypertension', pmh.hypertension);
            setCheck('erAssessPmhAsthma', pmh.asthma);
            setCheck('erAssessPmhHeartDisease', pmh.heart_disease);
            setVal('erAssessPmhOther', pmh.other);

            setVal('erAssessSurgicalHistory', assessExtra.surgical_history);
            setVal('erAssessCurrentMedications', assessExtra.current_medications);
            setVal('erAssessAllergiesOther', assessExtra.allergies_other);
            setVal('erAssessFamilyHistory', assessExtra.family_history);
            setVal('erAssessOccupation', social.occupation);
            setVal('erAssessNotes', row.notes);

            document.querySelectorAll('input[name="erAssessSmoking"], input[name="erAssessAlcohol"]').forEach(el => {
                el.checked = false;
            });
            const smoke = (social.smoking || '').toString().trim().toLowerCase();
            if (smoke) {
                const el = document.querySelector(`input[name="erAssessSmoking"][value="${CSS.escape(smoke)}"]`);
                if (el) el.checked = true;
            }
            const alcohol = (social.alcohol || '').toString().trim().toLowerCase();
            if (alcohol) {
                const el = document.querySelector(`input[name="erAssessAlcohol"][value="${CSS.escape(alcohol)}"]`);
                if (el) el.checked = true;
            }
        }

        function showErAssessDetail() {
            const listContainer = document.getElementById('erAssessHistoryListContainer');
            const detailContainer = document.getElementById('erAssessDetailContainer');
            if (listContainer) listContainer.classList.add('hidden');
            if (detailContainer) detailContainer.classList.remove('hidden');
        }

        function showErAssessHistoryList() {
            const listContainer = document.getElementById('erAssessHistoryListContainer');
            const detailContainer = document.getElementById('erAssessDetailContainer');
            if (listContainer) listContainer.classList.remove('hidden');
            if (detailContainer) detailContainer.classList.add('hidden');
        }

        function renderErAssessmentsAllTable(rows) {
            const history = document.getElementById('erAssessHistory');
            if (!history) return;

            const r = Array.isArray(rows) ? rows : [];
            if (!r.length) {
                history.innerHTML = '<div class="text-sm text-gray-500">No assessments found.</div>';
                closeErAssessDock();
                return;
            }

            history.innerHTML = `
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">When</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nurse</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${r.map(a => {
                                const when = a.created_at ? escapeHtml(new Date(a.created_at).toLocaleString()) : '';
                                const nurse = escapeHtml(a.nurse_name || '-');
                                const patient = escapeHtml(a.full_name || '');
                                const code = escapeHtml(a.patient_code || '');
                                const id = Number(a.id || 0);
                                return `
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-700">${when}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">${patient}<div class="text-xs text-gray-500">${code}</div></td>
                                        <td class="px-4 py-2 text-sm text-gray-700">${nurse}</td>
                                        <td class="px-4 py-2 text-right">
                                            <button type="button" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="openErAssessDetailsFromHistory(${id})">View</button>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        async function erAssessPatientSearch(q) {
            const resultsEl = document.getElementById('erAssessPatientResults');
            if (!resultsEl) return;
            if (!q) {
                resultsEl.classList.add('hidden');
                resultsEl.innerHTML = '';
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">Searching...</div>';

            const res = await fetch('api/patients/list.php?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>';
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients.slice(0, 10) : [];
            if (rows.length === 0) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>';
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = rows.map(p => {
                const name = escapeHtml(p.full_name || '');
                const code = escapeHtml(p.patient_code || ('P-' + String(p.id)));
                return `
                    <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50" data-id="${Number(p.id)}" data-name="${name}" data-code="${code}">
                        <div class="text-sm font-medium text-gray-900">${name}</div>
                        <div class="text-xs text-gray-500">${code}</div>
                    </button>
                `;
            }).join('');

            resultsEl.querySelectorAll('button[data-id]').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name') || '';
                    const code = btn.getAttribute('data-code') || '';
                    const pidEl = document.getElementById('erAssessPatientId');
                    const input = document.getElementById('erAssessPatientSearch');
                    const selected = document.getElementById('erAssessPatientSelected');
                    if (pidEl) pidEl.value = id;
                    if (input) input.value = code;
                    if (selected) selected.textContent = 'Selected: ' + name + ' (' + code + ')';
                    resultsEl.classList.add('hidden');
                    resultsEl.innerHTML = '';

                    try {
                        await loadErAssessmentsByPatient(Number(id));
                    } catch (e) {
                        showErAssessAlert('err', e && e.message ? e.message : 'Failed to load assessments');
                    }
                });
            });

        }

        const erAssessHistoryBtn = document.getElementById('erAssessHistoryBtn');
        if (erAssessHistoryBtn) {
            erAssessHistoryBtn.addEventListener('click', async () => {
                toggleModal('erAssessHistoryModal');
                try {
                    const listEl = document.getElementById('erAssessHistory');
                    if (listEl) listEl.innerHTML = '<div class="text-sm text-gray-500">Loading...</div>';
                    await loadErAssessmentsAll('');
                    renderErAssessmentsAllTable(erAssessAllCache);
                    closeErAssessDock();
                } catch (e) {
                    showErAssessAlert('err', e && e.message ? e.message : 'Failed to load assessments');
                }
            });
        }

        const erAssessCancelEditBtn = document.getElementById('erAssessCancelEditBtn');
        if (erAssessCancelEditBtn) {
            erAssessCancelEditBtn.addEventListener('click', () => {
                setErAssessEditMode(null);
            });
        }

        const erAssessDockEditBtn = document.getElementById('erAssessDockEditBtn');
        if (erAssessDockEditBtn) {
            erAssessDockEditBtn.addEventListener('click', () => {
                if (!erAssessHistorySelectedId) {
                    showErAssessAlert('err', 'Select an assessment from history');
                    return;
                }
                const row = (Array.isArray(erAssessAllCache) ? erAssessAllCache : []).find(r => Number(r.id) === Number(erAssessHistorySelectedId)) || null;
                if (!row) {
                    showErAssessAlert('err', 'Assessment not found');
                    return;
                }
                fillErAssessmentFormFromRow(row);
                setErAssessEditMode(erAssessHistorySelectedId);
                toggleModal('erAssessHistoryModal');
                const section = document.getElementById('erNursingAssessmentSection');
                if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        let erAssessSearchTimer = null;
        const erAssessSearchEl = document.getElementById('erAssessPatientSearch');
        if (erAssessSearchEl) {
            erAssessSearchEl.addEventListener('input', (e) => {
                const q = (e.target.value || '').toString().trim();
                clearTimeout(erAssessSearchTimer);
                erAssessSearchTimer = setTimeout(() => erAssessPatientSearch(q), 250);
            });
        }

        const erAssessHistoryModalRefreshBtn = document.getElementById('erAssessHistoryModalRefreshBtn');
        if (erAssessHistoryModalRefreshBtn) {
            erAssessHistoryModalRefreshBtn.addEventListener('click', async () => {
                try {
                    const q = (document.getElementById('erAssessHistorySearch')?.value || '').toString();
                    const listEl = document.getElementById('erAssessHistory');
                    if (listEl) listEl.innerHTML = '<div class="text-sm text-gray-500">Loading...</div>';
                    await loadErAssessmentsAll(q);
                    renderErAssessmentsAllTable(erAssessAllCache);
                    closeErAssessDock();
                } catch (e) {
                    showErAssessAlert('err', e && e.message ? e.message : 'Failed to load assessments');
                }
            });
        }

        let erAssessHistorySearchTimer = null;
        const erAssessHistorySearchEl = document.getElementById('erAssessHistorySearch');
        if (erAssessHistorySearchEl) {
            erAssessHistorySearchEl.addEventListener('input', (e) => {
                const q = (e.target.value || '').toString();
                clearTimeout(erAssessHistorySearchTimer);
                erAssessHistorySearchTimer = setTimeout(async () => {
                    try {
                        const listEl = document.getElementById('erAssessHistory');
                        if (listEl) listEl.innerHTML = '<div class="text-sm text-gray-500">Loading...</div>';
                        await loadErAssessmentsAll(q);
                        renderErAssessmentsAllTable(erAssessAllCache);
                        closeErAssessDock();
                    } catch (e2) {
                        const listEl = document.getElementById('erAssessHistory');
                        const msg = (e2 && e2.message) ? String(e2.message) : 'Unable to load assessments.';
                        if (listEl) listEl.innerHTML = '<div class="text-sm text-red-600">' + escapeHtml(msg) + '</div>';
                    }
                }, 250);
            });
        }

        function getErAssessRadioValue(name) {
            const el = document.querySelector(`input[name="${name}"]:checked`);
            return el ? (el.value || '').toString() : '';
        }

        const erAssessForm = document.getElementById('erAssessForm');
        if (erAssessForm) {
            erAssessForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const pid = (document.getElementById('erAssessPatientId')?.value || '').toString().trim();
                if (!pid) {
                    showErAssessAlert('err', 'Select a patient');
                    return;
                }

                const encId = (erAssessEncounterCache && erAssessEncounterCache.id) ? Number(erAssessEncounterCache.id) : null;
                const nurseName = (document.getElementById('erAssessNurseName')?.value || '').toString().trim();

                const vitals = {
                    bp_sys: (document.getElementById('erAssessBpSys')?.value || '').toString().trim(),
                    bp_dia: (document.getElementById('erAssessBpDia')?.value || '').toString().trim(),
                    hr: (document.getElementById('erAssessHr')?.value || '').toString().trim(),
                    rr: (document.getElementById('erAssessRr')?.value || '').toString().trim(),
                    temp: (document.getElementById('erAssessTemp')?.value || '').toString().trim(),
                    spo2: (document.getElementById('erAssessSpo2')?.value || '').toString().trim(),
                    weight: (document.getElementById('erAssessWeight')?.value || '').toString().trim(),
                    height: (document.getElementById('erAssessHeight')?.value || '').toString().trim(),
                };

                const assessment = {
                    hpi_start: (document.getElementById('erAssessHpiStart')?.value || '').toString().trim(),
                    hpi_duration: (document.getElementById('erAssessHpiDuration')?.value || '').toString().trim(),
                    hpi_severity: (document.getElementById('erAssessHpiSeverity')?.value || '').toString().trim(),
                    hpi_associated: (document.getElementById('erAssessHpiAssociated')?.value || '').toString().trim(),
                    hpi_factors: (document.getElementById('erAssessHpiFactors')?.value || '').toString().trim(),
                    pmh_diabetes: !!document.getElementById('erAssessPmhDiabetes')?.checked,
                    pmh_hypertension: !!document.getElementById('erAssessPmhHypertension')?.checked,
                    pmh_asthma: !!document.getElementById('erAssessPmhAsthma')?.checked,
                    pmh_heart_disease: !!document.getElementById('erAssessPmhHeartDisease')?.checked,
                    pmh_other: (document.getElementById('erAssessPmhOther')?.value || '').toString().trim(),
                    surgical_history: (document.getElementById('erAssessSurgicalHistory')?.value || '').toString().trim(),
                    current_medications: (document.getElementById('erAssessCurrentMedications')?.value || '').toString().trim(),
                    allergies_other: (document.getElementById('erAssessAllergiesOther')?.value || '').toString().trim(),
                    family_history: (document.getElementById('erAssessFamilyHistory')?.value || '').toString().trim(),
                    social_smoking: getErAssessRadioValue('erAssessSmoking'),
                    social_alcohol: getErAssessRadioValue('erAssessAlcohol'),
                    occupation: (document.getElementById('erAssessOccupation')?.value || '').toString().trim(),
                };

                const notes = (document.getElementById('erAssessNotes')?.value || '').toString().trim();

                const payload = {
                    patient_id: Number(pid),
                    encounter_id: encId,
                    nurse_name: nurseName || null,
                    triage_level: null,
                    vitals,
                    assessment,
                    notes: notes || null,
                };

                const isEditing = !!erAssessEditingId;
                const url = isEditing ? 'api/er_assessment/update.php' : 'api/er_assessment/create.php';
                const body = isEditing ? { ...payload, id: Number(erAssessEditingId) } : payload;

                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body),
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    showErAssessAlert('err', (json && json.error) ? json.error : (isEditing ? 'Failed to update assessment' : 'Failed to save assessment'));
                    return;
                }

                if (isEditing) {
                    setErAssessEditMode(null);
                }

                const resetIds = [
                    'erAssessBpSys','erAssessBpDia','erAssessHr','erAssessRr','erAssessTemp','erAssessSpo2','erAssessWeight','erAssessHeight',
                    'erAssessHpiStart','erAssessHpiDuration','erAssessHpiSeverity','erAssessHpiAssociated','erAssessHpiFactors',
                    'erAssessPmhOther','erAssessSurgicalHistory','erAssessCurrentMedications','erAssessAllergiesOther','erAssessFamilyHistory','erAssessOccupation',
                    'erAssessNotes'
                ];
                resetIds.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });
                ['erAssessPmhDiabetes','erAssessPmhHypertension','erAssessPmhAsthma','erAssessPmhHeartDisease'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.checked = false;
                });
                document.querySelectorAll('input[name="erAssessSmoking"], input[name="erAssessAlcohol"]').forEach(el => {
                    el.checked = false;
                });

                showErAssessAlert('ok', 'Assessment saved');
                try {
                    await loadErAssessmentsByPatient(Number(pid));
                    const modalOpen = !document.getElementById('erAssessHistoryModal')?.classList.contains('hidden');
                    if (modalOpen) {
                        const q = (document.getElementById('erAssessHistorySearch')?.value || '').toString();
                        await loadErAssessmentsAll(q);
                        renderErAssessmentsAllTable(erAssessAllCache);
                        closeErAssessDock();
                    }
                } catch (e) {
                }
            });
        }

        window.toggleErAssessSendRequestModal = toggleErAssessSendRequestModal;

        const erAssessDockSendBtn = document.getElementById('erAssessDockSendRequestBtn');
        if (erAssessDockSendBtn) {
            erAssessDockSendBtn.addEventListener('click', () => {
                if (!erAssessHistorySelectedId) {
                    showErAssessAlert('err', 'Select an assessment from history');
                    return;
                }
                const pName = erAssessHistorySelectedPatientName || '';
                const pCode = erAssessHistorySelectedPatientCode || '';
                const when = erAssessHistorySelectedWhen || '';
                const pInput = document.getElementById('erAssessSendReqPatient');
                const pMeta = document.getElementById('erAssessSendReqPatientMeta');
                if (pInput) pInput.value = pName || '-';
                if (pMeta) {
                    pMeta.textContent = [pCode ? ('Selected: ' + pName + ' - ' + pCode) : ('Selected: ' + pName), when ? ('Assessment: ' + when) : ''].filter(Boolean).join(' • ');
                }
                renderErDoctorsDropdown('erAssessSendReqDoctor');
                const dSel = document.getElementById('erAssessSendReqDoctorSelected');
                if (dSel) dSel.textContent = '';
                const notes = document.getElementById('erAssessSendReqNotes');
                if (notes) notes.value = '';
                refreshErAssessSendReqState();
                toggleErAssessSendRequestModal(true);
            });
        }

        const erAssessSendReqDoctorSel = document.getElementById('erAssessSendReqDoctor');
        if (erAssessSendReqDoctorSel) {
            erAssessSendReqDoctorSel.addEventListener('change', () => {
                const id = (erAssessSendReqDoctorSel.value || '').toString().trim();
                const doc = (Array.isArray(ER_DOCTORS) ? ER_DOCTORS : []).find(d => String(d.id) === String(id)) || null;
                const selectedEl = document.getElementById('erAssessSendReqDoctorSelected');
                if (selectedEl) {
                    selectedEl.textContent = doc ? ('Selected: ' + ((doc.full_name || doc.username || '').toString())) : '';
                }
                refreshErAssessSendReqState();
            });
        }

        const erAssessSendReqSubmitBtn = document.getElementById('erAssessSendReqSubmitBtn');
        if (erAssessSendReqSubmitBtn) {
            erAssessSendReqSubmitBtn.addEventListener('click', async () => {
                const sel = document.getElementById('erAssessSendReqDoctor');
                const docIdRaw = (sel?.value || '').toString().trim();
                const doctorId = docIdRaw && /^\d+$/.test(docIdRaw) ? Number(docIdRaw) : null;
                if (!doctorId) {
                    showErAssessAlert('err', 'Select a doctor');
                    return;
                }
                if (!erAssessHistorySelectedId) {
                    showErAssessAlert('err', 'Select an assessment from history');
                    return;
                }

                erAssessSendReqSubmitBtn.disabled = true;
                const oldText = erAssessSendReqSubmitBtn.textContent;
                erAssessSendReqSubmitBtn.textContent = 'Sending...';
                try {
                    const res = await fetch('api/er_assessment/submit_to_doctor.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ er_assessment_id: Number(erAssessHistorySelectedId), doctor_id: Number(doctorId) })
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        throw new Error((json && json.error) ? json.error : 'Failed to submit');
                    }
                    toggleErAssessSendRequestModal(false);
                    const doc = (Array.isArray(ER_DOCTORS) ? ER_DOCTORS : []).find(d => String(d.id) === String(doctorId)) || null;
                    const docName = (doc && (doc.full_name || doc.username)) ? (doc.full_name || doc.username).toString() : '';
                    toggleErAssessSendSuccessModal(true, docName ? ('Request sent successfully to ' + docName + '.') : 'Request sent successfully.');
                } catch (e) {
                    showErAssessAlert('err', e && e.message ? e.message : 'Failed to submit');
                } finally {
                    erAssessSendReqSubmitBtn.textContent = oldText;
                    refreshErAssessSendReqState();
                }
            });
        }

        async function loadErTestsFromFees() {
            let tests = [];
            try {
                const res = await fetch('api/price_master/list_lab_fees.php', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (res.ok && json && json.ok && Array.isArray(json.fees)) {
                    tests = json.fees;
                }
            } catch (e) {}

            if (!Array.isArray(tests) || tests.length === 0) {
                tests = ER_DEFAULT_TESTS;
            }

            tests = tests
                .filter(t => (t && (t.test_code || '').toString().trim() !== ''))
                .slice()
                .sort((a, b) => ((a.test_name || a.test_code || '').toString()).localeCompare((b.test_name || b.test_code || '').toString()));

            renderErTestList(tests);
            filterErTestList(document.getElementById('erTestSearch')?.value || '');
        }

        function showAlert(type, text) {
            const el = document.getElementById('erAlert');
            if (!el) return;
            el.classList.remove('hidden');
            el.classList.remove('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            el.classList.remove('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            if (type === 'ok') {
                el.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                el.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            el.textContent = text;
            try { el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); } catch (e) {}
        }

        function showErConsultAlert(type, text) {
            const el = document.getElementById('erConsultAlert');
            if (!el) return;
            el.classList.remove('hidden');
            el.classList.remove('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            el.classList.remove('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            if (type === 'ok') {
                el.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                el.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            el.textContent = text;
            try { el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); } catch (e) {}
        }

        let erConsultNotesCache = [];
        let erConsultEncounterCache = null;
        let erConsultHistoryPatientId = '';
        let erConsultEditingNoteId = null;

        function getErRadioValue(name) {
            const el = document.querySelector(`input[name="${name}"]:checked`);
            return el ? (el.value || '').toString() : '';
        }

        function buildErConsultNoteText() {
            const v = (id) => (document.getElementById(id)?.value || '').toString().trim();

            const lines = [];

            lines.push('Patient Name: ' + v('erConsultPatientName'));
            lines.push('Date: ' + v('erConsultVisitDate'));
            lines.push('Age/Gender: ' + v('erConsultPatientDobAge') + ' / ' + v('erConsultPatientGender'));
            lines.push('');

            lines.push('🩺 Doctor Consultation Note (SOAP Format)');
            lines.push('');
            lines.push('S – Subjective');
            lines.push('Chief Complaint: ' + v('erSoapChiefComplaint'));
            lines.push('');
            lines.push('O – Objective');
            lines.push('Vital Signs: BP: ' + v('erSoapBp') + '  Pulse: ' + v('erSoapPulse') + '  Temp: ' + v('erSoapTemp'));
            lines.push('Physical Examination Findings:');
            lines.push(v('erSoapExam'));
            lines.push('');
            lines.push('A – Assessment');
            lines.push('Primary Diagnosis: ' + v('erSoapPrimaryDx'));
            lines.push('Differential Diagnosis (if any): ' + v('erSoapDifferentialDx'));
            lines.push('');
            lines.push('P – Plan');
            lines.push('Investigations Ordered:');
            lines.push(v('erSoapInvestigations'));
            lines.push('Medications Prescribed:');
            lines.push(v('erSoapMedications'));
            lines.push('Treatment/Advice:');
            lines.push(v('erSoapAdvice'));
            lines.push('Follow-up: ' + v('erSoapFollowUp'));
            lines.push('Doctor’s Name & Signature: ' + v('erSoapDoctorSignature'));

            return lines.join('\n');
        }

        function setErConsultEditingMode(noteId) {
            const btn = document.getElementById('erConsultSaveBtn');
            if (!noteId) {
                erConsultEditingNoteId = null;
                if (btn) btn.textContent = 'Save Note';
                return;
            }
            erConsultEditingNoteId = Number(noteId);
            if (btn) btn.textContent = 'Update Note';
        }

        function applyParsedConsultNoteToErForm(parsed) {
            if (!parsed) return;
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.value = (val ?? '').toString();
            };

            setVal('erConsultPatientName', parsed.patientName || '');
            setVal('erConsultVisitDate', parsed.visitDate || '');
            const rawDobAgeGender = (parsed.dobAgeGender || '').toString();
            if (rawDobAgeGender.includes(' / ')) {
                const parts = rawDobAgeGender.split(' / ').map(s => (s ?? '').toString().trim()).filter(Boolean);
                if (parts.length >= 2) {
                    const gender = parts[parts.length - 1];
                    const dobAge = parts.slice(0, -1).join(' / ');
                    setVal('erConsultPatientDobAge', dobAge);
                    setVal('erConsultPatientGender', gender);
                } else {
                    setVal('erConsultPatientDobAge', rawDobAgeGender);
                }
            } else {
                setVal('erConsultPatientDobAge', rawDobAgeGender);
            }

            setVal('erSoapChiefComplaint', parsed.soapChiefComplaint || '');
            setVal('erSoapBp', parsed.soapBp || '');
            setVal('erSoapPulse', parsed.soapPulse || '');
            setVal('erSoapTemp', parsed.soapTemp || '');
            setVal('erSoapExam', parsed.soapExam || '');
            setVal('erSoapPrimaryDx', parsed.soapPrimaryDx || '');
            setVal('erSoapDifferentialDx', parsed.soapDifferentialDx || '');
            setVal('erSoapInvestigations', parsed.soapInvestigations || '');
            setVal('erSoapMedications', parsed.soapMedications || '');
            setVal('erSoapAdvice', parsed.soapAdvice || '');
            setVal('erSoapFollowUp', parsed.soapFollowUp || '');
            setVal('erSoapDoctorSignature', parsed.soapDoctorSignature || '');
        }

        function erConsultHasAnyInput() {
            const v = (id) => (document.getElementById(id)?.value || '').toString().trim();

            const ids = [
                'erSoapChiefComplaint','erSoapBp','erSoapPulse','erSoapTemp','erSoapExam','erSoapPrimaryDx','erSoapDifferentialDx',
                'erSoapInvestigations','erSoapMedications','erSoapAdvice','erSoapFollowUp','erSoapDoctorSignature'
            ];
            if (ids.some(id => v(id) !== '')) return true;

            return false;
        }

        function collectErConsultAiFields() {
            const fields = [];
            const pushInput = (id, label, type = 'text') => {
                const el = document.getElementById(id);
                if (!el) return;
                fields.push({ kind: 'input', name: id, type, label, current_value: (el.value ?? '').toString() });
            };
            const pushSelect = (id, label) => {
                const el = document.getElementById(id);
                if (!el) return;
                const options = Array.from(el.options || []).map(o => ({ value: o.value, label: o.textContent || '' })).filter(o => o.value !== '');
                fields.push({ kind: 'select', name: id, label, options, current_value: (el.value ?? '').toString() });
            };
            const pushCheckbox = (id, label) => {
                const el = document.getElementById(id);
                if (!el) return;
                fields.push({ kind: 'checkbox', name: id, label, current_checked: !!el.checked });
            };
            const pushRadio = (name, label) => {
                const radios = Array.from(document.querySelectorAll(`input[name="${name}"]`));
                if (!radios.length) return;
                const options = radios.map(r => ({ value: r.value || '', label: (r.parentElement && r.parentElement.textContent) ? r.parentElement.textContent.trim() : '' })).filter(o => o.value !== '');
                const selected = radios.find(r => r.checked) || null;
                fields.push({ kind: 'radio', name, label, options, current_value: selected ? (selected.value || '').toString() : '' });
            };

            pushInput('erSoapChiefComplaint', 'SOAP Chief Complaint');
            pushInput('erSoapBp', 'SOAP BP');
            pushInput('erSoapPulse', 'SOAP Pulse');
            pushInput('erSoapTemp', 'SOAP Temp');
            pushInput('erSoapExam', 'SOAP Physical Examination Findings', 'text');
            pushInput('erSoapPrimaryDx', 'SOAP Primary Diagnosis');
            pushInput('erSoapDifferentialDx', 'SOAP Differential Diagnosis');
            pushInput('erSoapInvestigations', 'SOAP Investigations Ordered', 'text');
            pushInput('erSoapMedications', 'SOAP Medications Prescribed', 'text');
            pushInput('erSoapAdvice', 'SOAP Treatment/Advice', 'text');
            pushInput('erSoapFollowUp', 'SOAP Follow-up');
            pushInput('erSoapDoctorSignature', 'Doctor Name & Signature');

            return fields;
        }

        function applyAiValuesToErConsult(fields, values) {
            const byIdx = new Map();
            (Array.isArray(values) ? values : []).forEach(v => {
                if (v && typeof v === 'object' && v.index !== undefined) {
                    const idx = Number(v.index);
                    if (Number.isFinite(idx)) byIdx.set(idx, v);
                }
            });

            const toBool = (v) => {
                if (typeof v === 'boolean') return v;
                if (typeof v === 'number') return v !== 0;
                const s = (v ?? '').toString().trim().toLowerCase();
                if (s === '1' || s === 'true' || s === 'yes' || s === 'y' || s === 'checked') return true;
                if (s === '0' || s === 'false' || s === 'no' || s === 'n' || s === '') return false;
                return true;
            };

            const norm = (s) => (s ?? '').toString().trim().toLowerCase();

            fields.forEach((f, idx) => {
                const item = byIdx.get(idx);
                if (!item) return;
                if (f.kind === 'select') {
                    const el = document.getElementById(f.name);
                    if (!el) return;
                    const wanted = (item.value ?? '').toString();
                    const ok = Array.from(el.options || []).some(o => o.value === wanted);
                    if (ok) el.value = wanted;
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                    return;
                }
                if (f.kind === 'checkbox') {
                    const el = document.getElementById(f.name);
                    if (!el) return;
                    const raw = (item.checked !== undefined) ? item.checked : ((item.value !== undefined) ? item.value : item.current_checked);
                    el.checked = toBool(raw);
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                    return;
                }
                if (f.kind === 'radio') {
                    const radios = Array.from(document.querySelectorAll(`input[name="${f.name}"]`));
                    if (!radios.length) return;
                    const wanted = norm(item.value);
                    const selected = radios.find(r => norm(r.value) === wanted) || null;
                    if (selected) {
                        selected.checked = true;
                        selected.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    return;
                }
                if (f.kind === 'input') {
                    const el = document.getElementById(f.name);
                    if (!el) return;
                    el.value = (item.value ?? '').toString();
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }

        function setErEncounterMeta(encounter) {
            const meta = document.getElementById('erConsultEncounterMeta');
            if (!meta) return;
            if (encounter && encounter.encounter_no) {
                meta.textContent = String(encounter.encounter_no) + ' • ' + String(encounter.status || '') + ' • Started: ' + String(encounter.started_at || '');
            } else {
                meta.textContent = '-';
            }
        }

        function renderErConsultHistoryModal(encounter, notes) {
            const metaEl = document.getElementById('erConsultHistoryModalMeta');
            const listEl = document.getElementById('erConsultHistoryModalList');
            if (metaEl) {
                if (encounter && encounter.encounter_no) {
                    metaEl.textContent = String(encounter.encounter_no) + ' • ' + String(encounter.status || '') + (encounter.started_at ? (' • Started: ' + String(encounter.started_at)) : '');
                } else {
                    metaEl.textContent = '';
                }
            }
            if (!listEl) return;

            const rows = Array.isArray(notes) ? notes : [];
            if (rows.length === 0) {
                listEl.innerHTML = 'No notes yet.';
                return;
            }

            setErConsultHistoryViewingMode(true);

            listEl.innerHTML = rows.map(r => {
                const id = String(r.id || '');
                const when = r.created_at ? new Date(r.created_at).toLocaleString() : '';
                const doctor = (r.author_name || '').toString() || (r.author_user_id ? ('User #' + String(r.author_user_id)) : '');
                const noteText = (r.note_text || '').toString();
                const parsed = parseConsultNoteText(noteText);
                const noteWhen = when;
                const noteDoctor = doctor;
                const body = parsed ? renderConsultNoteFormReadOnly({ ...parsed, noteWhen, noteDoctor }) : ('<pre class="whitespace-pre-wrap">' + escapeHtml(noteText) + '</pre>');

                return `
                    <div class="border border-gray-200 rounded-lg mb-4 overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between gap-3">
                            <div class="text-sm text-gray-800 font-semibold">${escapeHtml(noteWhen || ('Note #' + id))}</div>
                            <div class="flex items-center gap-2">
                                <button type="button" data-submit-note-id="${escapeHtml(id)}" class="px-3 py-1.5 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Submit to Pharmacy</button>
                                <button type="button" data-edit-note-id="${escapeHtml(id)}" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">Edit</button>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="text-xs text-gray-500 mb-2">${escapeHtml(noteDoctor || '')}</div>
                            ${body}
                        </div>
                    </div>
                `;
            }).join('');

            Array.from(listEl.querySelectorAll('button[data-submit-note-id]')).forEach(btn => {
                btn.addEventListener('click', async () => {
                    const key = (btn.getAttribute('data-submit-note-id') || '').toString();
                    if (!key || !/^\d+$/.test(key)) {
                        showErConsultAlert('err', 'Select a note');
                        return;
                    }

                    btn.disabled = true;
                    const oldText = btn.textContent;
                    btn.textContent = 'Submitting...';
                    try {
                        const res = await fetch('api/pharmacy/submit_consult_note.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ source: 'ER', note_id: Number(key) }),
                        });
                        const json = await res.json().catch(() => null);
                        if (!res.ok || !json || !json.ok) {
                            throw new Error((json && json.error) ? json.error : 'Failed to submit');
                        }
                        toggleErConsultSubmitSuccessModal(true, 'Submitted successfully.');
                    } catch (e) {
                        showErConsultAlert('err', e && e.message ? e.message : 'Failed to submit');
                    } finally {
                        btn.textContent = oldText;
                        btn.disabled = false;
                    }
                });
            });

            Array.from(listEl.querySelectorAll('button[data-edit-note-id]')).forEach(btn => {
                btn.addEventListener('click', () => {
                    const key = (btn.getAttribute('data-edit-note-id') || '').toString();
                    if (!key || !/^\d+$/.test(key)) {
                        showErConsultAlert('err', 'Select a note');
                        return;
                    }

                    const rows = Array.isArray(erConsultNotesCache) ? erConsultNotesCache : [];
                    const note = rows.find(r => Number(r.id) === Number(key)) || null;
                    if (!note) {
                        showErConsultAlert('err', 'Note not found');
                        return;
                    }

                    const pidEl = document.getElementById('erConsultPatientId');
                    if (pidEl) pidEl.value = String(note.patient_id || '');

                    const input = document.getElementById('erConsultPatientSearch');
                    if (input) input.value = (note.patient_code || '').toString() || String(note.patient_id || '');

                    const selected = document.getElementById('erConsultPatientSelected');
                    if (selected) {
                        const nm = (note.full_name || '').toString();
                        const code = (note.patient_code || '').toString();
                        selected.textContent = nm ? (nm + (code ? (' (' + code + ')') : '')) : '';
                    }

                    setErConsultEditingMode(Number(key));
                    const noteText = (note.note_text || '').toString();
                    const parsed = parseConsultNoteText(noteText);
                    if (parsed) {
                        applyParsedConsultNoteToErForm(parsed);
                    } else {
                        showErConsultAlert('err', 'This note format cannot be edited');
                        return;
                    }

                    const historyModal = document.getElementById('erConsultHistoryModal');
                    if (historyModal && !historyModal.classList.contains('hidden')) {
                        toggleModal('erConsultHistoryModal');
                    }

                    const saveBtn = document.getElementById('erConsultSaveBtn');
                    if (saveBtn) {
                        try { saveBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); } catch (e) {}
                    }
                    showErConsultAlert('ok', 'Loaded note for editing');
                });
            });
        }

        function parseConsultNoteText(noteText) {
            const text = (noteText ?? '').toString();
            if (!text.trim()) return null;

            const lines = text.split(/\r?\n/);
            const t = (s) => (s ?? '').toString().trim();

            const out = {
                patientName: '',
                visitDate: '',
                dobAgeGender: '',
                hpiStart: '',
                hpiDuration: '',
                hpiSeverity: '',
                hpiFactors: '',
                hpiAssociated: '',
                pmhList: '',
                pmhOther: '',
                surgicalHistory: '',
                currentMedications: '',
                allergiesNone: false,
                allergiesOther: '',
                familyHistory: '',
                socialSmoking: '',
                socialAlcohol: '',
                socialOccupation: '',
                additionalNotes: '',
                patientSignature: '',
                patientSignatureDate: '',
                soapChiefComplaint: '',
                soapBp: '',
                soapPulse: '',
                soapTemp: '',
                soapExam: '',
                soapPrimaryDx: '',
                soapDifferentialDx: '',
                soapInvestigations: '',
                soapMedications: '',
                soapAdvice: '',
                soapFollowUp: '',
                soapDoctorSignature: '',
            };

            const takeUntil = (startIdx, stopFn) => {
                const buf = [];
                let i = startIdx;
                for (; i < lines.length; i++) {
                    const s = t(lines[i]);
                    if (stopFn(s)) break;
                    buf.push(lines[i]);
                }
                return { text: buf.join('\n').trim(), next: i };
            };

            for (let i = 0; i < lines.length; i++) {
                const s = t(lines[i]);
                let m;

                if ((m = s.match(/^Patient Name:\s*(.*)$/i))) { out.patientName = t(m[1]); continue; }
                if ((m = s.match(/^Date:\s*(.*)$/i))) { out.visitDate = t(m[1]); continue; }
                if ((m = s.match(/^Age\/Gender:\s*(.*)$/i))) { out.dobAgeGender = t(m[1]); continue; }

                if ((m = s.match(/^When did the problem start\?\s*(.*)$/i))) { out.hpiStart = t(m[1]); continue; }
                if ((m = s.match(/^Duration\/Frequency:\s*(.*)$/i))) { out.hpiDuration = t(m[1]); continue; }
                if ((m = s.match(/^Severity \(mild\/moderate\/severe\):\s*(.*)$/i))) { out.hpiSeverity = t(m[1]); continue; }
                if ((m = s.match(/^Aggravating\/Relieving factors:\s*(.*)$/i))) { out.hpiFactors = t(m[1]); continue; }
                if ((m = s.match(/^Associated Symptoms:\s*(.*)$/i))) { out.hpiAssociated = t(m[1]); continue; }

                if (s === 'Past Medical History') {
                    for (let j = i + 1; j < lines.length; j++) {
                        const nx = t(lines[j]);
                        if (!nx) continue;
                        if (/^Other:\s*/i.test(nx)) break;
                        if (nx === 'Surgical History (if any)' || nx === 'Current Medications' || nx === 'Allergies') break;
                        out.pmhList = nx;
                        break;
                    }
                    continue;
                }
                if ((m = s.match(/^Other:\s*(.*)$/i))) { out.pmhOther = t(m[1]); continue; }

                if (s === 'Surgical History (if any)') {
                    const block = takeUntil(i + 1, (x) => x === 'Current Medications');
                    out.surgicalHistory = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Current Medications') {
                    const block = takeUntil(i + 1, (x) => x === 'Allergies');
                    out.currentMedications = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Allergies') {
                    let next = '';
                    for (let j = i + 1; j < lines.length; j++) {
                        const nx = t(lines[j]);
                        if (!nx) continue;
                        next = nx;
                        break;
                    }
                    if (/^none$/i.test(next)) out.allergiesNone = true;
                    const mm = next.match(/^Drugs\/Food\/Other:\s*(.*)$/i);
                    if (mm) out.allergiesOther = t(mm[1]);
                    continue;
                }
                if (s === 'Family History (relevant conditions)') {
                    const block = takeUntil(i + 1, (x) => x === 'Social History');
                    out.familyHistory = block.text;
                    i = block.next - 1;
                    continue;
                }

                if ((m = s.match(/^Smoking:\s*(.*)$/i))) { out.socialSmoking = t(m[1]); continue; }
                if ((m = s.match(/^Alcohol:\s*(.*)$/i))) { out.socialAlcohol = t(m[1]); continue; }
                if ((m = s.match(/^Occupation:\s*(.*)$/i))) { out.socialOccupation = t(m[1]); continue; }

                if (s === 'Additional Notes') {
                    const block = takeUntil(i + 1, (x) => /^Patient Signature:/i.test(x));
                    out.additionalNotes = block.text;
                    i = block.next - 1;
                    continue;
                }
                if ((m = s.match(/^Patient Signature:\s*(.*?)\s*Date:\s*(.*)$/i))) {
                    out.patientSignature = t(m[1]);
                    out.patientSignatureDate = t(m[2]);
                    continue;
                }

                if ((m = s.match(/^Chief Complaint:\s*(.*)$/i))) { out.soapChiefComplaint = t(m[1]); continue; }
                if ((m = s.match(/^Vital Signs:\s*BP:\s*(.*?)\s*Pulse:\s*(.*?)\s*Temp:\s*(.*)$/i))) {
                    out.soapBp = t(m[1]);
                    out.soapPulse = t(m[2]);
                    out.soapTemp = t(m[3]);
                    continue;
                }

                if (s === 'Physical Examination Findings:') {
                    const block = takeUntil(i + 1, (x) => x === 'A – Assessment');
                    out.soapExam = block.text;
                    i = block.next - 1;
                    continue;
                }

                if ((m = s.match(/^Primary Diagnosis:\s*(.*)$/i))) { out.soapPrimaryDx = t(m[1]); continue; }
                if ((m = s.match(/^Differential Diagnosis \(if any\):\s*(.*)$/i))) { out.soapDifferentialDx = t(m[1]); continue; }

                if (s === 'Investigations Ordered:') {
                    const block = takeUntil(i + 1, (x) => x === 'Medications Prescribed:');
                    out.soapInvestigations = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Medications Prescribed:') {
                    const block = takeUntil(i + 1, (x) => x === 'Treatment/Advice:');
                    out.soapMedications = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Treatment/Advice:') {
                    const block = takeUntil(i + 1, (x) => /^Follow-up:/i.test(x));
                    out.soapAdvice = block.text;
                    i = block.next - 1;
                    continue;
                }

                if ((m = s.match(/^Follow-up:\s*(.*)$/i))) { out.soapFollowUp = t(m[1]); continue; }
                if ((m = s.match(/^Doctor’s Name & Signature:\s*(.*)$/i))) { out.soapDoctorSignature = t(m[1]); continue; }
            }

            const ok = !!out.patientName || !!out.soapChiefComplaint || !!out.visitDate;
            return ok ? out : null;
        }

        function renderConsultNoteFormReadOnly(d) {
            const esc = (s) => escapeHtml((s ?? '').toString());

            return `
                <div class="space-y-4 w-full">
                    <div>
                        <div class="text-sm font-semibold text-gray-800">Doctor Consultation Form (Read-only)</div>
                        <div class="mt-1 text-xs text-gray-500">${esc(d.noteWhen || '')}${((d.noteWhen || '') && (d.noteDoctor || '')) ? ' • ' : ''}${esc(d.noteDoctor || '')}</div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Patient Information</div>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] text-gray-600">Full Name</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.patientName)}" />
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600">Date of Birth / Age / Gender</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.dobAgeGender)}" />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Appointment Details</div>
                        <div class="mt-2">
                            <label class="block text-[11px] text-gray-600">Date of Visit</label>
                            <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.visitDate)}" />
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">🩺 Doctor Consultation Note (SOAP Format)</div>

                        <div class="mt-3">
                            <div class="text-xs font-semibold text-gray-700">S – Subjective</div>
                            <div class="mt-2">
                                <label class="block text-[11px] text-gray-600">Chief Complaint</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapChiefComplaint)}" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="text-xs font-semibold text-gray-700">O – Objective</div>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[11px] text-gray-600">BP</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapBp)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Pulse</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapPulse)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Temp</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapTemp)}" />
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-[11px] text-gray-600">Physical Examination Findings</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapExam)}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="text-xs font-semibold text-gray-700">A – Assessment</div>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] text-gray-600">Primary Diagnosis</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapPrimaryDx)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Differential Diagnosis (if any)</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapDifferentialDx)}" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="text-xs font-semibold text-gray-700">P – Plan</div>
                            <div class="mt-2">
                                <label class="block text-[11px] text-gray-600">Investigations Ordered</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapInvestigations)}</textarea>
                            </div>
                            <div class="mt-3">
                                <label class="block text-[11px] text-gray-600">Medications Prescribed</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapMedications)}</textarea>
                            </div>
                            <div class="mt-3">
                                <label class="block text-[11px] text-gray-600">Treatment/Advice</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapAdvice)}</textarea>
                            </div>
                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] text-gray-600">Follow-up</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapFollowUp)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Doctor’s Name & Signature</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapDoctorSignature)}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        async function loadErConsultNotesByPatient(patientId) {
            const res = await fetch('api/er_notes/list.php?patient_id=' + encodeURIComponent(String(patientId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                throw new Error((json && json.error) ? json.error : 'Failed to load notes');
            }
            erConsultEncounterCache = json.encounter || null;
            erConsultNotesCache = Array.isArray(json.notes) ? json.notes : [];
            setErEncounterMeta(erConsultEncounterCache);

            const modalOpen = !document.getElementById('erConsultHistoryModal')?.classList.contains('hidden');
            if (modalOpen) {
                renderErConsultHistoryModal(erConsultEncounterCache, erConsultNotesCache);
            }
        }

        async function erConsultHistoryPatientSearch(q) {
            const resultsEl = document.getElementById('erConsultHistoryPatientResults');
            if (!resultsEl) return;

            const text = (q || '').toString().trim();
            if (!text) {
                resultsEl.classList.add('hidden');
                resultsEl.innerHTML = '';
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">Searching...</div>';

            const res = await fetch('api/patients/list.php?q=' + encodeURIComponent(text), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>';
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients.slice(0, 10) : [];
            if (rows.length === 0) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>';
                return;
            }

            resultsEl.innerHTML = rows.map(p => {
                const id = String(p.id || '');
                const code = (p.patient_code || '').toString();
                const name = (p.full_name || '').toString();
                const label = (name ? name : 'Patient') + (code ? (' (' + code + ')') : '');
                return `
                    <div class="px-4 py-2 bg-white hover:bg-gray-50 border-b border-gray-100 flex items-center justify-between gap-3">
                        <div class="text-sm text-gray-700">${escapeHtml(label)}</div>
                        <button type="button" data-view-history-patient-id="${escapeHtml(id)}" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">View History</button>
                    </div>
                `;
            }).join('');

            Array.from(resultsEl.querySelectorAll('button[data-view-history-patient-id]')).forEach(btn => {
                btn.addEventListener('click', async () => {
                    const pid = (btn.getAttribute('data-view-history-patient-id') || '').toString();
                    if (!pid || !/^\d+$/.test(pid)) return;

                    erConsultHistoryPatientId = pid;
                    const listEl = document.getElementById('erConsultHistoryModalList');
                    if (listEl) listEl.innerHTML = 'Loading...';

                    try {
                        await loadErConsultNotesByPatient(Number(pid));
                        renderErConsultHistoryModal(erConsultEncounterCache, erConsultNotesCache);
                    } catch (e) {
                        if (listEl) listEl.innerHTML = 'No notes yet.';
                    }

                    resultsEl.classList.add('hidden');
                });
            });
        }

        async function erConsultPatientSearch(q) {
            const resultsEl = document.getElementById('erConsultPatientResults');
            if (!resultsEl) return;
            if (!q) {
                resultsEl.classList.add('hidden');
                resultsEl.innerHTML = '';
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">Searching...</div>';

            const res = await fetch('api/patients/list.php?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>';
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients.slice(0, 10) : [];
            if (rows.length === 0) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>';
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = rows.map(p => {
                const name = escapeHtml(p.full_name || '');
                const code = escapeHtml(p.patient_code || ('P-' + String(p.id)));
                const dob = escapeHtml(p.dob || '');
                const sex = escapeHtml(p.sex || '');
                return `
                    <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50" data-id="${Number(p.id)}" data-name="${name}" data-code="${code}" data-dob="${dob}" data-sex="${sex}">
                        <div class="text-sm font-medium text-gray-900">${name}</div>
                        <div class="text-xs text-gray-500">${code}</div>
                    </button>
                `;
            }).join('');

            resultsEl.querySelectorAll('button[data-id]').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name') || '';
                    const code = btn.getAttribute('data-code') || '';
                    const dob = btn.getAttribute('data-dob') || '';
                    const sex = btn.getAttribute('data-sex') || '';
                    const pidEl = document.getElementById('erConsultPatientId');
                    const input = document.getElementById('erConsultPatientSearch');
                    const selected = document.getElementById('erConsultPatientSelected');
                    if (pidEl) pidEl.value = id;
                    if (input) input.value = code;
                    if (selected) selected.textContent = 'Selected: ' + name + ' (' + code + ')';
                    setErConsultPatientInfo({ full_name: name, dob: dob, sex: sex });
                    resultsEl.classList.add('hidden');
                    resultsEl.innerHTML = '';

                    try {
                        await loadErConsultNotesByPatient(Number(id));
                    } catch (e) {
                        showErConsultAlert('err', e && e.message ? e.message : 'Failed to load notes');
                    }
                });
            });
        }

        function statusChip(status) {
            const s = (status ?? '').toString();
            if (s === 'pending_approval') return { cls: 'bg-yellow-100 text-yellow-800', label: 'Pending Approval' };
            if (s === 'approved') return { cls: 'bg-blue-100 text-blue-800', label: 'Approved' };
            if (s === 'rejected') return { cls: 'bg-red-100 text-red-800', label: 'Rejected' };
            if (s === 'collected') return { cls: 'bg-purple-100 text-purple-800', label: 'Collected' };
            if (s === 'in_progress') return { cls: 'bg-indigo-100 text-indigo-800', label: 'In Progress' };
            if (s === 'completed') return { cls: 'bg-green-100 text-green-800', label: 'Completed' };
            if (s === 'cancelled') return { cls: 'bg-gray-100 text-gray-800', label: 'Cancelled' };
            return { cls: 'bg-gray-100 text-gray-800', label: s };
        }

        async function patientSearch(q) {
            const resultsEl = document.getElementById('patientResults');
            if (!resultsEl) return;
            if (!q) {
                resultsEl.classList.add('hidden');
                resultsEl.innerHTML = '';
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = `
                <div class="px-4 py-2 text-sm text-gray-500 bg-white">Searching...</div>
            `;

            const res = await fetch('api/patients/list.php?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = `
                    <div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>
                `;
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients.slice(0, 10) : [];
            if (rows.length === 0) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = `
                    <div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>
                `;
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = rows.map(p => {
                const name = escapeHtml(p.full_name || '');
                const code = escapeHtml(p.patient_code || ('P-' + String(p.id)));
                return `
                    <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50" data-id="${Number(p.id)}" data-name="${name}" data-code="${code}">
                        <div class="text-sm font-medium text-gray-900">${name}</div>
                        <div class="text-xs text-gray-500">${code}</div>
                    </button>
                `;
            }).join('');

            resultsEl.querySelectorAll('button[data-id]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name') || '';
                    const code = btn.getAttribute('data-code') || '';
                    document.getElementById('patientId').value = id;
                    const input = document.getElementById('patientSearch');
                    if (input) input.value = code;
                    const selectedText = 'Selected: ' + name + ' (' + code + ')';
                    document.getElementById('patientSelected').textContent = selectedText;
                    erPatientState[erActiveRole] = {
                        patient_id: (id ?? '').toString(),
                        patient_code: (code ?? '').toString(),
                        selected_text: selectedText,
                    };
                    resultsEl.classList.add('hidden');
                    resultsEl.innerHTML = '';
                });
            });
        }

        async function loadErRequests() {
            const tbody = document.getElementById('erRequestsTbody');
            if (!tbody) return;

            const res = await fetch('api/lab/list_requests.php?mode=er', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                return;
            }

            const rows = Array.isArray(json.requests) ? json.requests : [];
            tbody.innerHTML = rows.map(r => {
                const chip = statusChip(r.status);
                const reqNo = escapeHtml(r.request_no || ('#' + String(r.id)));
                const name = escapeHtml(r.full_name || '');
                const code = escapeHtml(r.patient_code || '');
                const tests = escapeHtml(r.tests || '');
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${reqNo}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(new Date(r.created_at).toLocaleString())}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${name}</div>
                            <div class="text-sm text-gray-500">ID: ${code}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800">${tests}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(r.chief_complaint || '')}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${chip.label}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="openErDetails(${Number(r.id)})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function openErDetails(id) {
            const content = document.getElementById('erDetailsContent');
            if (!content) return;

            const res = await fetch('api/lab/get_request.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load details.</div>';
                toggleModal('erDetailsModal');
                return;
            }

            const r = json.request || {};
            const items = Array.isArray(json.items) ? json.items : [];
            const vitals = (json.vitals && typeof json.vitals === 'object') ? json.vitals : null;

            const docName = (r.doctor_full_name || r.doctor_username || '').toString().trim();
            const docUser = (r.doctor_username || '').toString().trim();
            const docDisplay = docName
                ? (docUser && docUser !== docName ? (docName + ' (' + docUser + ')') : docName)
                : '-';

            const triage = (r.triage_level ?? '').toString();
            const bp = vitals && (vitals.bp ?? '') !== undefined ? (vitals.bp ?? '').toString() : '';
            const hr = vitals && (vitals.hr ?? '') !== undefined ? (vitals.hr ?? '').toString() : '';
            const rr = vitals && (vitals.rr ?? '') !== undefined ? (vitals.rr ?? '').toString() : '';
            const temp = vitals && (vitals.temp ?? '') !== undefined ? (vitals.temp ?? '').toString() : '';
            const spo2 = vitals && (vitals.spo2 ?? '') !== undefined ? (vitals.spo2 ?? '').toString() : '';

            content.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-800">Request</div>
                            <div class="text-sm text-gray-700 mt-2">${escapeHtml(r.request_no || '')}</div>
                            <div class="text-xs text-gray-500">Status: ${escapeHtml(r.status || '')}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-800">Patient</div>
                            <div class="text-sm text-gray-700 mt-2">${escapeHtml(r.full_name || '')}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(r.patient_code || '')}</div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Lab Request Form</div>
                        <div class="p-4 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-xs font-semibold text-gray-600">Triage Level</div>
                                    <input type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml(triage)}" disabled>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-600">Priority</div>
                                    <input type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml((r.priority || '').toString())}" disabled>
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-semibold text-gray-600">Chief Complaint</div>
                                <input type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml((r.chief_complaint || '').toString())}" disabled>
                            </div>

                            <div>
                                <div class="text-xs font-semibold text-gray-600">Vitals</div>
                                <div class="mt-1 grid grid-cols-2 gap-3">
                                    <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml(bp)}" disabled>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml(hr)}" disabled>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml(rr)}" disabled>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml(temp)}" disabled>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml(spo2)}" disabled>
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-semibold text-gray-600">Doctor</div>
                                <input type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml(docDisplay)}" disabled>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-xs font-semibold text-gray-600">Requested by</div>
                                    <input type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml((r.requested_by || '').toString())}" disabled>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-600">Source Unit</div>
                                    <input type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" value="${escapeHtml((r.source_unit || '').toString())}" disabled>
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-semibold text-gray-600">Notes</div>
                                <textarea rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" disabled>${escapeHtml((r.notes || '').toString())}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Tests</div>
                        <div class="p-4">
                            <ul class="space-y-2">
                                ${items.map(it => `
                                    <li class="flex items-center justify-between">
                                        <div class="text-sm text-gray-800">${escapeHtml(it.test_name || '')}</div>
                                        <div class="text-xs text-gray-500">${escapeHtml(it.specimen || '')}</div>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                    </div>
                </div>
            `;

            toggleModal('erDetailsModal');
        }

        let searchTimer = null;
        document.getElementById('patientSearch').addEventListener('input', (e) => {
            const q = e.target.value.trim();
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => patientSearch(q), 250);
        });

        const erPatientState = {
            nurse: { patient_id: '', patient_code: '', selected_text: '' },
            np_pa: { patient_id: '', patient_code: '', selected_text: '' },
        };

        const erDoctorState = {
            nurse: { doctor_id: '' },
            np_pa: { doctor_id: '' },
        };

        let erActiveRole = 'nurse';

        function erRoleFromView(view) {
            return (view === 'np-pa') ? 'np_pa' : 'nurse';
        }

        function saveErPatientState(role) {
            const key = (role === 'np_pa') ? 'np_pa' : 'nurse';
            const idEl = document.getElementById('patientId');
            const inputEl = document.getElementById('patientSearch');
            const selectedEl = document.getElementById('patientSelected');
            erPatientState[key] = {
                patient_id: (idEl?.value ?? '').toString(),
                patient_code: (inputEl?.value ?? '').toString(),
                selected_text: (selectedEl?.textContent ?? '').toString(),
            };
        }

        function saveErDoctorState(role) {
            const key = (role === 'np_pa') ? 'np_pa' : 'nurse';
            const el = document.getElementById('erDoctor');
            erDoctorState[key] = {
                doctor_id: (el?.value ?? '').toString(),
            };
        }

        function loadErPatientState(role) {
            const key = (role === 'np_pa') ? 'np_pa' : 'nurse';
            const st = erPatientState[key] || { patient_id: '', patient_code: '', selected_text: '' };
            const idEl = document.getElementById('patientId');
            const inputEl = document.getElementById('patientSearch');
            const selectedEl = document.getElementById('patientSelected');
            if (idEl) idEl.value = st.patient_id || '';
            if (inputEl) inputEl.value = st.patient_code || '';
            if (selectedEl) selectedEl.textContent = st.selected_text || '';
        }

        function loadErDoctorState(role) {
            const key = (role === 'np_pa') ? 'np_pa' : 'nurse';
            const st = erDoctorState[key] || { doctor_id: '' };
            const el = document.getElementById('erDoctor');
            if (el) {
                el.value = st.doctor_id || '';
            }
            setTimeout(refreshErDoctorAvailability, 0);
        }

        function clearErPatientState(role) {
            const key = (role === 'np_pa') ? 'np_pa' : 'nurse';
            erPatientState[key] = { patient_id: '', patient_code: '', selected_text: '' };
        }

        function clearErDoctorState(role) {
            const key = (role === 'np_pa') ? 'np_pa' : 'nurse';
            erDoctorState[key] = { doctor_id: '' };
        }

        function setErView(view) {
            if (view === 'new') {
                view = 'requests';
            }
            const newSec = document.getElementById('erNewRequestSection');
            const wardSec = document.getElementById('erWardSection');
            const clearanceSec = document.getElementById('erClearanceSection');
            const reqSec = document.getElementById('erRequestsSection');
            const resSec = document.getElementById('erLabResultsSection');
            const fbSec = document.getElementById('erDoctorFeedbackSection');
            const xraySec = document.getElementById('erXrayResultsSection');
            const consultSec = document.getElementById('erConsultationNotesSection');
            const assessSec = document.getElementById('erNursingAssessmentSection');
            if (!newSec || !reqSec || !consultSec || !wardSec || !clearanceSec || !resSec || !fbSec || !xraySec || !assessSec) return;

            const titleEl = document.getElementById('erRequestTitle');
            const subtitleEl = document.getElementById('erRequestSubtitle');
            const btnEl = document.getElementById('erSubmitBtn');
            const byLabel = document.getElementById('requestedByLabel');
            const doctorSection = document.getElementById('erDoctorSection');
            const approvedSection = document.getElementById('erApprovedBySection');

            const isWard = view === 'ward';
            const isClearance = view === 'clearance';
            const isQueue = view === 'requests';
            const isResults = view === 'lab-results';
            const isFeedback = view === 'doctor-feedback';
            const isXrayResults = view === 'xray-results';
            const isConsult = view === 'consultation-notes';
            const isAssess = view === 'nursing-assessment';
            const isDirectNurse = view === 'lab-request';

            saveErPatientState(erActiveRole);
            saveErDoctorState(erActiveRole);
            newSec.classList.toggle('hidden', isWard || isClearance || isQueue || isResults || isFeedback || isXrayResults || isConsult || isAssess);
            wardSec.classList.toggle('hidden', !isWard);
            clearanceSec.classList.toggle('hidden', !isClearance);
            reqSec.classList.toggle('hidden', !isQueue);
            resSec.classList.toggle('hidden', !isResults);
            fbSec.classList.toggle('hidden', !isFeedback);
            xraySec.classList.toggle('hidden', !isXrayResults);
            consultSec.classList.toggle('hidden', !isConsult);
            assessSec.classList.toggle('hidden', !isAssess);

            if (!isWard && view === 'np-pa') {
                if (titleEl) titleEl.textContent = 'New Lab Request (NP/PA)';
                if (subtitleEl) subtitleEl.textContent = 'NP/PA can submit lab tests directly to Laboratory without doctor approval.';
                if (btnEl) btnEl.textContent = 'Submit to Laboratory';
                if (byLabel) byLabel.textContent = 'Submitted by';
                if (doctorSection) doctorSection.classList.add('hidden');
                if (approvedSection) approvedSection.classList.add('hidden');
            } else if (!isWard && isDirectNurse) {
                if (titleEl) titleEl.textContent = 'Lab Request';
                if (subtitleEl) subtitleEl.textContent = 'ER Nurse can submit lab tests directly to Laboratory.';
                if (btnEl) btnEl.textContent = 'Submit to Laboratory';
                if (byLabel) byLabel.textContent = 'Submitted by';
                if (doctorSection) doctorSection.classList.add('hidden');
                if (approvedSection) approvedSection.classList.remove('hidden');
            } else if (!isWard) {
                if (titleEl) titleEl.textContent = 'ER Nurse Lab Request';
                if (subtitleEl) subtitleEl.textContent = 'Requests require doctor approval before Laboratory can process.';
                if (btnEl) btnEl.textContent = 'Submit for Doctor Approval';
                if (byLabel) byLabel.textContent = 'Requested by (Nurse)';
                if (doctorSection) doctorSection.classList.remove('hidden');
                if (approvedSection) approvedSection.classList.add('hidden');
            }

            if (!isWard && !isQueue && !isConsult) {
                erActiveRole = erRoleFromView(view);
                loadErPatientState(erActiveRole);
                loadErDoctorState(erActiveRole);
            }

            if (isWard) {
                try { history.replaceState(null, '', '#ward'); } catch (e) {}
                setTimeout(() => {
                    if (typeof renderWard === 'function') renderWard();
                }, 0);
            } else if (isClearance) {
                try { history.replaceState(null, '', '#clearance'); } catch (e) {}
                setTimeout(() => {
                    if (typeof renderClearance === 'function') renderClearance();
                }, 0);
            } else if (isQueue) {
                try { history.replaceState(null, '', '#requests'); } catch (e) {}
                loadErRequests();
            } else if (isResults) {
                try { history.replaceState(null, '', '#lab-results'); } catch (e) {}
                loadErLabResults();
            } else if (isFeedback) {
                try { history.replaceState(null, '', '#doctor-feedback'); } catch (e) {}
                loadErDoctorFeedback();
            } else if (isXrayResults) {
                try { history.replaceState(null, '', '#xray-results'); } catch (e) {}
                if (window.xrayResultsRelease && typeof window.xrayResultsRelease.bindModalOnce === 'function') {
                    window.xrayResultsRelease.bindModalOnce();
                }
                if (window.xrayResultsRelease && typeof window.xrayResultsRelease.render === 'function') {
                    window.xrayResultsRelease.render();
                }
            } else if (isConsult) {
                try { history.replaceState(null, '', '#consultation-notes'); } catch (e) {}
            } else if (isAssess) {
                try { history.replaceState(null, '', '#nursing-assessment'); } catch (e) {}
            } else if (isDirectNurse) {
                try { history.replaceState(null, '', '#lab-request'); } catch (e) {}
            } else if (view === 'np-pa') {
                try { history.replaceState(null, '', '#np-pa'); } catch (e) {}
            } else {
                try { history.replaceState(null, '', '#requests'); } catch (e) {}
            }
        }

        document.getElementById('refreshErRequests').addEventListener('click', loadErRequests);

        let erDoctorFeedbackRows = [];

        function erFormatDateTime(s) {
            const v = (s ?? '').toString();
            if (!v) return '';
            const d = new Date(v.replace(' ', 'T'));
            if (Number.isNaN(d.getTime())) return v;
            return d.toLocaleString();
        }

        function erParseJsonArray(v) {
            if (!v) return [];
            if (Array.isArray(v)) return v;
            try {
                const x = JSON.parse(String(v));
                return Array.isArray(x) ? x : [];
            } catch (e) {
                return [];
            }
        }

        function toggleErDoctorFeedbackModal(show) {
            const modal = document.getElementById('erDoctorFeedbackModal');
            if (!modal) return;
            modal.classList.toggle('hidden', !show);
            modal.classList.toggle('flex', !!show);
        }

        async function loadErDoctorFeedback() {
            const tbody = document.getElementById('erDoctorFeedbackTbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td></tr>';

            const res = await fetch('api/er_assessment/feedback_list.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                erDoctorFeedbackRows = [];
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No feedback.</td></tr>';
                return;
            }

            erDoctorFeedbackRows = Array.isArray(json.feedback) ? json.feedback : [];
            renderErDoctorFeedback();
        }

        function renderErDoctorFeedback() {
            const tbody = document.getElementById('erDoctorFeedbackTbody');
            if (!tbody) return;
            const rows = Array.isArray(erDoctorFeedbackRows) ? erDoctorFeedbackRows : [];
            if (!rows.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No feedback.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(r => {
                const id = Number(r.id);
                const fullName = escapeHtml(r.full_name || '');
                const code = escapeHtml(r.patient_code || ('P-' + String(r.patient_id || '')));
                const doctor = escapeHtml(r.doctor_name || '-');
                const tests = erParseJsonArray(r.lab_tests_json).join(', ');
                const status = escapeHtml((r.status || '-').toString());
                const when = erFormatDateTime(r.feedback_at);
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${fullName}</div>
                            <div class="text-sm text-gray-500">ID: ${code}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">${doctor}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${escapeHtml(tests || '-')}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${status}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${escapeHtml(when || '-')}</td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="openErDoctorFeedbackView(${id})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function openErDoctorFeedbackView(id) {
            const content = document.getElementById('erDoctorFeedbackModalContent');
            if (content) content.innerHTML = '<div class="p-6 text-sm text-gray-500">Loading...</div>';
            toggleErDoctorFeedbackModal(true);

            try {
                const res = await fetch('api/er_assessment/feedback_get.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok || !json.feedback) {
                    if (content) content.innerHTML = '<div class="p-6 text-sm text-gray-500">No feedback.</div>';
                    return;
                }

                const f = json.feedback;
                const fullName = escapeHtml(f.full_name || '');
                const code = escapeHtml(f.patient_code || ('P-' + String(f.patient_id || '')));
                const doctor = escapeHtml(f.doctor_name || '-');
                const tests = erParseJsonArray(f.lab_tests_json);
                const note = escapeHtml((f.lab_note || '').toString().trim() || '-');
                const when = erFormatDateTime(f.feedback_at);

                if (content) {
                    content.innerHTML = `
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <div class="text-xs font-bold text-gray-500">Patient</div>
                                    <div class="mt-1 text-sm font-semibold text-gray-900">${fullName}</div>
                                    <div class="text-xs text-gray-500">${code}</div>
                                </div>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <div class="text-xs font-bold text-gray-500">Doctor</div>
                                    <div class="mt-1 text-sm font-semibold text-gray-900">${doctor}</div>
                                    <div class="text-xs text-gray-500">${escapeHtml(when || '-')}</div>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="text-xs font-bold text-gray-500">Lab Tests</div>
                                <div class="mt-2 text-sm text-gray-800">${escapeHtml(tests.length ? tests.join(', ') : '-')}</div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="text-xs font-bold text-gray-500">Feedback Note</div>
                                <div class="mt-2 text-sm text-gray-800 whitespace-pre-wrap">${note}</div>
                            </div>
                        </div>
                    `;
                }
            } catch (e) {
                if (content) content.innerHTML = '<div class="p-6 text-sm text-gray-500">No feedback.</div>';
            }
        }

        document.getElementById('refreshErDoctorFeedback')?.addEventListener('click', loadErDoctorFeedback);

        window.toggleErDoctorFeedbackModal = toggleErDoctorFeedbackModal;
        window.openErDoctorFeedbackView = openErDoctorFeedbackView;

        const refreshErLabResultsBtn = document.getElementById('refreshErLabResults');
        if (refreshErLabResultsBtn) refreshErLabResultsBtn.addEventListener('click', loadErLabResults);

        const erLabResultsSearchInput = document.getElementById('erLabResultsSearch');
        if (erLabResultsSearchInput) erLabResultsSearchInput.addEventListener('input', applyErLabResultsFilter);

        const refreshErXrayResultsBtn = document.getElementById('refreshErXrayResults');
        if (refreshErXrayResultsBtn) refreshErXrayResultsBtn.addEventListener('click', () => {
            try {
                if (typeof loadXrayResultsRelease === 'function') loadXrayResultsRelease();
                if (window.xrayResultsRelease && typeof window.xrayResultsRelease.render === 'function') {
                    window.xrayResultsRelease.render();
                }
            } catch (e) {}
        });

        document.getElementById('erLabForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const patientId = (document.getElementById('patientId').value || '').toString().trim();
            const triageLevel = (document.getElementById('triageLevel').value || '').toString().trim();
            const chiefComplaint = (document.getElementById('chiefComplaint').value || '').toString().trim();
            const priority = (document.getElementById('priority').value || 'routine').toString();

            const doctorIdRaw = (document.getElementById('erDoctor')?.value || '').toString().trim();
            const doctorId = doctorIdRaw && /^\d+$/.test(doctorIdRaw) ? Number(doctorIdRaw) : null;
            const tests = Array.from(document.querySelectorAll('.testChk')).filter(x => x.checked).map(x => x.value);
            const requestedBy = (document.getElementById('requestedBy').value || '').toString().trim();
            const approvedBy = (document.getElementById('approvedBy')?.value || '').toString().trim();
            const notes = (document.getElementById('notes').value || '').toString().trim();

            const vitals = {
                bp: (document.getElementById('vitalBp').value || '').toString().trim(),
                hr: (document.getElementById('vitalHr').value || '').toString().trim(),
                rr: (document.getElementById('vitalRr').value || '').toString().trim(),
                temp: (document.getElementById('vitalTemp').value || '').toString().trim(),
                spo2: (document.getElementById('vitalSpo2').value || '').toString().trim(),
            };

            if (!patientId) {
                showAlert('err', 'Select a patient');
                return;
            }
            if (!triageLevel) {
                showAlert('err', 'Select triage level');
                return;
            }
            if (!chiefComplaint) {
                showAlert('err', 'Enter chief complaint');
                return;
            }

            const requesterRole = (function () {
                const h = (window.location.hash || '').toLowerCase();
                if (h === '#np-pa') return 'np_pa';
                if (h === '#lab-request') return 'nurse_direct';
                return 'nurse';
            })();

            if (requesterRole === 'nurse' && !doctorId) {
                showAlert('err', 'Select a doctor');
                return;
            }
            if (tests.length === 0) {
                showAlert('err', 'Select at least 1 test');
                return;
            }

            const body = {
                patient_id: Number(patientId),
                triage_level: Number(triageLevel),
                chief_complaint: chiefComplaint,
                priority,
                vitals,
                requested_by: requestedBy,
                approved_by: approvedBy,
                requester_role: requesterRole,
                doctor_id: doctorId,
                notes,
                tests,
            };

            const res = await fetch('api/lab/create_request.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(body)
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                showAlert('err', (json && json.error) ? json.error : 'Failed to submit request');
                return;
            }

            const createdStatus = (json.request?.status || '').toString();
            const reqNo = (json.request?.request_no || 'Request created');
            const h = (window.location.hash || '').toLowerCase();
            if (h === '#np-pa' || h === '#lab-request') {
                showAlert('ok', 'Request sent to Laboratory: ' + reqNo);
            } else if (createdStatus === 'approved') {
                showAlert('ok', 'Request sent: ' + reqNo + ' (Auto-approved: Standing Order)');
            } else {
                showAlert('ok', 'Request submitted for doctor approval: ' + reqNo);
            }
            e.target.reset();
            document.getElementById('patientId').value = '';
            document.getElementById('patientSelected').textContent = '';
            const input = document.getElementById('patientSearch');
            if (input) input.value = '';
            clearErPatientState(erActiveRole);
            clearErDoctorState(erActiveRole);
            const docSel = document.getElementById('erDoctor');
            if (docSel) docSel.value = '';
            const docSelected = document.getElementById('erDoctorSelected');
            if (docSelected) docSelected.textContent = '';
            const docAv = document.getElementById('erDoctorAvailability');
            if (docAv) docAv.innerHTML = '';
        });

        function applyErViewFromHash() {
            const h = (window.location.hash || '').toLowerCase();
            if (h === '#ward') {
                setErView('ward');
                return;
            }
            if (h === '#clearance') {
                setErView('clearance');
                return;
            }
            if (h === '#nursing-assessment') {
                setErView('nursing-assessment');
                return;
            }
            if (h === '#requests') {
                setErView('requests');
                return;
            }

            if (h === '#lab-results') {
                setErView('lab-results');
                return;
            }

            if (h === '#xray-results') {
                setErView('xray-results');
                return;
            }

            if (h === '#consultation-notes') {
                setErView('consultation-notes');
                return;
            }

            if (h === '#doctor-feedback') {
                setErView('doctor-feedback');
                return;
            }

            if (h === '#lab-request') {
                if (ER_CAN_NURSE) {
                    setErView('lab-request');
                } else {
                    setErView('requests');
                }
                return;
            }

            if (h === '#np-pa') {
                if (ER_CAN_NPPA) {
                    setErView('np-pa');
                } else if (ER_CAN_NURSE) {
                    setErView('requests');
                } else {
                    setErView('requests');
                }
                return;
            }

            if (ER_CAN_NURSE) {
                setErView('requests');
            } else if (ER_CAN_NPPA) {
                setErView('np-pa');
            } else {
                setErView('requests');
            }
        }

        window.addEventListener('hashchange', applyErViewFromHash);
        applyErViewFromHash();

        const clearanceSample = (function () {
            const now = Date.now();
            return {
                items: [
                    {
                        id: 9001,
                        patient: { id: 101, name: 'Maria Santos', age: 72, gender: 'F' },
                        type: 'discharge',
                        priority: 'high',
                        status: 'Pending',
                        requested_at: now - (1000 * 60 * 60 * 3),
                        cleared_at: null,
                        defaults: {
                            vitals: true,
                            labs: true,
                            meds: false,
                            plan: false,
                            followup: false,
                            notes: 'Pending: medication reconciliation and family teaching before discharge.'
                        }
                    },
                    {
                        id: 9002,
                        patient: { id: 106, name: 'Michael Brown', age: 51, gender: 'M' },
                        type: 'transfer',
                        priority: 'normal',
                        status: 'Pending',
                        requested_at: now - (1000 * 60 * 60 * 5),
                        cleared_at: null,
                        defaults: {
                            vitals: true,
                            labs: true,
                            meds: true,
                            plan: false,
                            followup: true,
                            notes: 'Awaiting receiving unit confirmation; send latest ECG/labs with patient.'
                        }
                    },
                    {
                        id: 9003,
                        patient: { id: 103, name: 'Emily White', age: 34, gender: 'F' },
                        type: 'medical',
                        priority: 'normal',
                        status: 'Pending',
                        requested_at: now - (1000 * 60 * 60 * 2),
                        cleared_at: null,
                        defaults: {
                            vitals: true,
                            labs: false,
                            meds: false,
                            plan: false,
                            followup: false,
                            notes: 'For medical clearance: review imaging results and finalize plan.'
                        }
                    },
                    {
                        id: 9004,
                        patient: { id: 102, name: 'David Chen', age: 16, gender: 'M' },
                        type: 'medical',
                        priority: 'high',
                        status: 'Cleared',
                        requested_at: now - (1000 * 60 * 60 * 10),
                        cleared_at: now - (1000 * 60 * 60 * 1),
                        defaults: {
                            vitals: true,
                            labs: true,
                            meds: true,
                            plan: true,
                            followup: true,
                            notes: 'Medically cleared. Safety plan reviewed with guardian.'
                        }
                    },
                    {
                        id: 9005,
                        patient: { id: 107, name: 'Aisha Rahman', age: 28, gender: 'F' },
                        type: 'discharge',
                        priority: 'normal',
                        status: 'Pending',
                        requested_at: now - (1000 * 60 * 60 * 1.5),
                        cleared_at: null,
                        defaults: {
                            vitals: true,
                            labs: true,
                            meds: true,
                            plan: false,
                            followup: false,
                            notes: 'Discharge pending: instructions and follow-up appointment.'
                        }
                    },
                    {
                        id: 9006,
                        patient: { id: 108, name: 'Robert Garcia', age: 63, gender: 'M' },
                        type: 'medical',
                        priority: 'normal',
                        status: 'Pending',
                        requested_at: now - (1000 * 60 * 60 * 6),
                        cleared_at: null,
                        defaults: {
                            vitals: false,
                            labs: true,
                            meds: false,
                            plan: false,
                            followup: false,
                            notes: 'Needs repeat BP and reassessment prior to medical clearance.'
                        }
                    },
                ]
            };
        })();

        let clearanceSelectedId = null;
        let clearanceToastTimer = null;

        function clearanceBadge(text, tone) {
            const base = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium';
            if (tone === 'red') return `<span class="${base} bg-red-50 text-red-700">${text}</span>`;
            if (tone === 'yellow') return `<span class="${base} bg-yellow-50 text-yellow-700">${text}</span>`;
            if (tone === 'green') return `<span class="${base} bg-green-50 text-green-700">${text}</span>`;
            if (tone === 'blue') return `<span class="${base} bg-blue-50 text-blue-700">${text}</span>`;
            return `<span class="${base} bg-gray-100 text-gray-700">${text}</span>`;
        }

        function showClearanceToast(kind, text) {
            const wrap = document.getElementById('clearanceToast');
            const t = document.getElementById('clearanceToastText');
            const ic = document.getElementById('clearanceToastIcon');
            if (!wrap || !t || !ic) return;
            t.textContent = text || '';
            if (kind === 'err') {
                ic.className = 'fas fa-circle-xmark';
            } else {
                ic.className = 'fas fa-circle-check';
            }
            wrap.classList.remove('hidden');
            clearTimeout(clearanceToastTimer);
            clearanceToastTimer = setTimeout(() => wrap.classList.add('hidden'), 2400);
        }

        function clearanceTypeLabel(t) {
            if (t === 'medical') return 'Medical';
            if (t === 'discharge') return 'Discharge';
            if (t === 'transfer') return 'Transfer';
            return 'Other';
        }

        function clearanceGetFilteredItems() {
            const filter = (document.getElementById('clearanceFilter')?.value || 'all').toString();
            const q = (document.getElementById('clearanceSearch')?.value || '').toString().trim().toLowerCase();
            return clearanceSample.items.filter(it => {
                if (filter !== 'all' && it.type !== filter) return false;
                if (!q) return true;
                const hay = `${it.patient.name} ${it.patient.age} ${it.patient.gender} ${clearanceTypeLabel(it.type)}`.toLowerCase();
                return hay.includes(q);
            });
        }

        function clearanceRenderStats(items) {
            const pending = items.filter(x => (x.status || '').toLowerCase() === 'pending').length;
            const cleared = items.filter(x => (x.status || '').toLowerCase() === 'cleared').length;
            const high = items.filter(x => (x.priority || '').toLowerCase() === 'high' && (x.status || '').toLowerCase() === 'pending').length;
            const clearedItems = items.filter(x => (x.status || '').toLowerCase() === 'cleared' && x.cleared_at && x.requested_at);
            const avg = clearedItems.length ? Math.round(clearedItems.reduce((a, x) => a + ((x.cleared_at - x.requested_at) / (1000 * 60 * 60)), 0) / clearedItems.length) : 0;
            const s1 = document.getElementById('clearanceStatPending');
            const s2 = document.getElementById('clearanceStatCleared');
            const s3 = document.getElementById('clearanceStatHigh');
            const s4 = document.getElementById('clearanceStatAvg');
            if (s1) s1.textContent = String(pending);
            if (s2) s2.textContent = String(cleared);
            if (s3) s3.textContent = String(high);
            if (s4) s4.textContent = String(avg);
        }

        function clearanceApplySelected() {
            const row = clearanceSample.items.find(x => x.id === clearanceSelectedId) || null;
            const meta = document.getElementById('clearanceSelectedMeta');
            const markBtn = document.getElementById('clearanceMarkClearedBtn');
            const printBtn = document.getElementById('clearancePrintBtn');
            const has = !!row;
            if (markBtn) markBtn.disabled = !has || ((row?.status || '').toLowerCase() === 'cleared');
            if (printBtn) printBtn.disabled = !has;
            if (meta) meta.textContent = row ? `Selected: ${row.patient.name} (${row.patient.age}/${row.patient.gender}) — ${clearanceTypeLabel(row.type)}` : 'Select an item from the queue.';

            const k = row ? `clearance_${row.id}` : '';
            const raw = k ? (localStorage.getItem(k) || '') : '';
            let st = null;
            try { st = raw ? JSON.parse(raw) : null; } catch (e) { st = null; }

            const d = row && row.defaults && typeof row.defaults === 'object' ? row.defaults : null;
            if (!st && d) st = d;
            const setChk = (id, v) => { const el = document.getElementById(id); if (el) el.checked = !!v; };
            setChk('clearanceChkVitals', st ? st.vitals : false);
            setChk('clearanceChkLabs', st ? st.labs : false);
            setChk('clearanceChkMeds', st ? st.meds : false);
            setChk('clearanceChkPlan', st ? st.plan : false);
            setChk('clearanceChkFollowup', st ? st.followup : false);
            const notesEl = document.getElementById('clearanceNotes');
            if (notesEl) notesEl.value = st && typeof st.notes === 'string' ? st.notes : '';
        }

        function clearanceRenderTable(items) {
            const tbody = document.getElementById('clearanceTbody');
            if (!tbody) return;
            if (items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-6 text-sm text-gray-500">No clearance items.</td></tr>`;
                return;
            }
            tbody.innerHTML = items.map(it => {
                const typeTone = it.type === 'discharge' ? 'blue' : (it.type === 'transfer' ? 'yellow' : 'green');
                const prTone = (it.priority || '').toLowerCase() === 'high' ? 'red' : 'gray';
                const stTone = (it.status || '').toLowerCase() === 'cleared' ? 'green' : 'yellow';
                const isSel = it.id === clearanceSelectedId;
                return `
                    <tr class="${isSel ? 'bg-blue-50/40' : ''}">
                        <td class="px-6 py-4 text-sm text-gray-800">${it.patient.name} <span class="text-xs text-gray-500">(${it.patient.age}/${it.patient.gender})</span></td>
                        <td class="px-6 py-4 text-sm">${clearanceBadge(clearanceTypeLabel(it.type), typeTone)}</td>
                        <td class="px-6 py-4 text-sm">${clearanceBadge((it.priority || 'Normal').toString(), prTone)}</td>
                        <td class="px-6 py-4 text-sm">${clearanceBadge(it.status || 'Pending', stTone)}</td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" class="clearanceSelectBtn px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50" data-id="${it.id}">Select</button>
                        </td>
                    </tr>
                `;
            }).join('');

            Array.from(document.querySelectorAll('.clearanceSelectBtn')).forEach(btn => {
                btn.addEventListener('click', () => {
                    const idRaw = (btn.getAttribute('data-id') || '').toString();
                    if (!idRaw || !/^\d+$/.test(idRaw)) return;
                    clearanceSelectedId = Number(idRaw);
                    renderClearance();
                });
            });
        }

        function renderClearance() {
            const items = clearanceGetFilteredItems();
            clearanceRenderStats(items);
            clearanceRenderTable(items);
            clearanceApplySelected();
        }

        const clearanceFilterEl = document.getElementById('clearanceFilter');
        if (clearanceFilterEl) {
            clearanceFilterEl.addEventListener('change', () => {
                clearanceSelectedId = null;
                renderClearance();
            });
        }
        const clearanceSearchEl = document.getElementById('clearanceSearch');
        if (clearanceSearchEl) {
            clearanceSearchEl.addEventListener('input', () => renderClearance());
        }
        const clearanceRefreshBtn = document.getElementById('clearanceRefreshBtn');
        if (clearanceRefreshBtn) {
            clearanceRefreshBtn.addEventListener('click', () => renderClearance());
        }

        const clearanceMarkClearedBtn = document.getElementById('clearanceMarkClearedBtn');
        if (clearanceMarkClearedBtn) {
            clearanceMarkClearedBtn.addEventListener('click', () => {
                const row = clearanceSample.items.find(x => x.id === clearanceSelectedId) || null;
                if (!row) return;
                if ((row.status || '').toLowerCase() === 'cleared') return;

                const payload = {
                    vitals: !!document.getElementById('clearanceChkVitals')?.checked,
                    labs: !!document.getElementById('clearanceChkLabs')?.checked,
                    meds: !!document.getElementById('clearanceChkMeds')?.checked,
                    plan: !!document.getElementById('clearanceChkPlan')?.checked,
                    followup: !!document.getElementById('clearanceChkFollowup')?.checked,
                    notes: (document.getElementById('clearanceNotes')?.value || '').toString(),
                };

                try {
                    localStorage.setItem(`clearance_${row.id}`, JSON.stringify(payload));
                } catch (e) {}

                row.status = 'Cleared';
                row.cleared_at = Date.now();
                showClearanceToast('ok', 'Marked cleared');
                renderClearance();
            });
        }

        function clearanceOpenPrintWindow(title, bodyHtml) {
            const w = window.open('', '_blank');
            if (!w) return;
            w.document.open();
            w.document.write(`<!DOCTYPE html><html><head><meta charset="utf-8"><title>${title}</title><style>
                body{font-family: Arial, sans-serif; padding: 24px; color: #111827;}
                h1{font-size: 18px; margin: 0 0 12px;}
                .meta{font-size: 12px; color:#4b5563; margin-bottom: 16px;}
                .box{border:1px solid #e5e7eb; border-radius: 10px; padding: 12px; margin-bottom: 12px;}
                .row{display:flex; gap:12px; flex-wrap:wrap;}
                .col{flex:1; min-width: 220px;}
                .chk{margin: 6px 0;}
                @media print{button{display:none;}}
            </style></head><body>
                <button onclick="window.print()" style="margin-bottom:12px; padding:8px 12px;">Print</button>
                ${bodyHtml}
            </body></html>`);
            w.document.close();
        }

        const clearancePrintBtn = document.getElementById('clearancePrintBtn');
        if (clearancePrintBtn) {
            clearancePrintBtn.addEventListener('click', () => {
                const row = clearanceSample.items.find(x => x.id === clearanceSelectedId) || null;
                if (!row) return;
                const raw = (localStorage.getItem(`clearance_${row.id}`) || '').toString();
                let st = null;
                try { st = raw ? JSON.parse(raw) : null; } catch (e) { st = null; }
                const mk = (ok, label) => `<div class="chk">[${ok ? 'x' : ' '}] ${label}</div>`;
                const html = `
                    <h1>Clearance</h1>
                    <div class="meta">Generated: ${new Date().toLocaleString()}</div>
                    <div class="box">
                        <div><b>Patient:</b> ${row.patient.name} (${row.patient.age}/${row.patient.gender})</div>
                        <div><b>Clearance Type:</b> ${clearanceTypeLabel(row.type)}</div>
                        <div><b>Status:</b> ${row.status || 'Pending'}</div>
                    </div>
                    <div class="box">
                        ${mk(!!st?.vitals, 'Vitals stable')}
                        ${mk(!!st?.labs, 'Labs/Imaging reviewed')}
                        ${mk(!!st?.meds, 'Medications reconciled')}
                        ${mk(!!st?.plan, 'Plan explained / instructions given')}
                        ${mk(!!st?.followup, 'Follow-up arranged')}
                    </div>
                    <div class="box">
                        <div><b>Notes:</b></div>
                        <div>${((st && typeof st.notes === 'string' && st.notes) ? st.notes : '-').replace(/\n/g,'<br>')}</div>
                    </div>
                    <div class="row">
                        <div class="col box"><b>Cleared by:</b><div style="margin-top:30px; border-top:1px solid #e5e7eb; padding-top:6px;">Signature</div></div>
                        <div class="col box"><b>Date/Time:</b><div style="margin-top:30px; border-top:1px solid #e5e7eb; padding-top:6px;">${new Date().toLocaleString()}</div></div>
                    </div>
                `;
                clearanceOpenPrintWindow('Clearance', html);
            });
        }

        const wardSample = (function () {
            const now = Date.now();
            return {
                beds: [
                    { bed: 'A-01', room: 'Ward A', patient: { id: 101, name: 'Maria Santos', age: 72, gender: 'F', category: 'senior' }, isolation: true, fall_risk: true, status: 'Observation', admitted_at: now - (1000 * 60 * 60 * 18) },
                    { bed: 'A-02', room: 'Ward A', patient: { id: 102, name: 'David Chen', age: 16, gender: 'M', category: 'pdea' }, isolation: false, fall_risk: false, status: 'Boarding', admitted_at: now - (1000 * 60 * 60 * 6) },
                    { bed: 'A-03', room: 'Ward A', patient: { id: 103, name: 'Emily White', age: 34, gender: 'F', category: 'all' }, isolation: false, fall_risk: false, status: 'For Admission', admitted_at: now - (1000 * 60 * 60 * 10) },
                    { bed: 'A-04', room: 'Ward A', patient: null, isolation: false, fall_risk: false, status: 'Available', admitted_at: null },
                    { bed: 'B-01', room: 'Ward B', patient: { id: 104, name: 'Juan Dela Cruz', age: 68, gender: 'M', category: 'senior' }, isolation: false, fall_risk: true, status: 'Admitted', admitted_at: now - (1000 * 60 * 60 * 26) },
                    { bed: 'B-02', room: 'Ward B', patient: { id: 105, name: 'Patient C51CE', age: 15, gender: 'F', category: 'pdea' }, isolation: true, fall_risk: false, status: 'Observation', admitted_at: now - (1000 * 60 * 60 * 4) },
                    { bed: 'B-03', room: 'Ward B', patient: { id: 106, name: 'Michael Brown', age: 51, gender: 'M', category: 'all' }, isolation: false, fall_risk: false, status: 'Awaiting Transfer', admitted_at: now - (1000 * 60 * 60 * 14) },
                    { bed: 'B-04', room: 'Ward B', patient: null, isolation: false, fall_risk: false, status: 'Cleaning', admitted_at: null },
                ],
                turnover7d: [3, 5, 4, 6, 2, 7, 5],
                fallRisk7d: [1, 2, 1, 3, 2, 2, 3],
            };
        })();

        let wardCategoryChart = null;
        let wardTurnoverChart = null;
        let wardIsolationChart = null;
        let wardFallRiskChart = null;
        let wardLosChart = null;
        let wardSelectedPatientId = null;
        let wardTransferFromBed = null;

        function wardGetFilteredBeds() {
            const filter = (document.getElementById('wardFilter')?.value || 'all').toString();
            const q = (document.getElementById('wardSearch')?.value || '').toString().trim().toLowerCase();

            return wardSample.beds.filter(b => {
                if (!b.patient) return false;
                if (filter === 'senior' && b.patient.category !== 'senior') return false;
                if (filter === 'pdea' && b.patient.category !== 'pdea') return false;
                if (!q) return true;
                const hay = `${b.bed} ${b.room} ${b.patient.name}`.toLowerCase();
                return hay.includes(q);
            });
        }

        function wardGetAllBeds() {
            const q = (document.getElementById('wardSearch')?.value || '').toString().trim().toLowerCase();
            if (!q) return wardSample.beds.slice();
            return wardSample.beds.filter(b => {
                const p = b.patient ? `${b.patient.name}` : '';
                const hay = `${b.bed} ${b.room} ${p}`.toLowerCase();
                return hay.includes(q);
            });
        }

        function wardBadge(text, tone) {
            const base = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium';
            if (tone === 'red') return `<span class="${base} bg-red-50 text-red-700">${text}</span>`;
            if (tone === 'yellow') return `<span class="${base} bg-yellow-50 text-yellow-700">${text}</span>`;
            if (tone === 'green') return `<span class="${base} bg-green-50 text-green-700">${text}</span>`;
            if (tone === 'blue') return `<span class="${base} bg-blue-50 text-blue-700">${text}</span>`;
            if (tone === 'purple') return `<span class="${base} bg-purple-50 text-purple-700">${text}</span>`;
            return `<span class="${base} bg-gray-100 text-gray-700">${text}</span>`;
        }

        function wardCategoryLabel(cat) {
            if (cat === 'senior') return 'Senior';
            if (cat === 'pdea') return 'PDEA';
            return 'General';
        }

        function wardRenderStats(beds) {
            const occ = beds.length;
            const iso = beds.filter(b => !!b.isolation).length;
            const fall = beds.filter(b => !!b.fall_risk).length;
            const avgHrs = occ === 0 ? 0 : Math.round((beds.reduce((a, b) => a + (Date.now() - (b.admitted_at || Date.now())), 0) / occ) / (1000 * 60 * 60));

            const sOcc = document.getElementById('wardStatOccupied');
            const sIso = document.getElementById('wardStatIsolation');
            const sFall = document.getElementById('wardStatFallRisk');
            const sAvg = document.getElementById('wardStatAvgHours');
            if (sOcc) sOcc.textContent = String(occ);
            if (sIso) sIso.textContent = String(iso);
            if (sFall) sFall.textContent = String(fall);
            if (sAvg) sAvg.textContent = String(avgHrs);
        }

        function wardRenderCharts(beds) {
            const ctx1 = document.getElementById('wardChartCategory');
            const ctx2 = document.getElementById('wardChartTurnover');
            const ctx3 = document.getElementById('wardChartIsolation');
            const ctx4 = document.getElementById('wardChartFallRisk');
            const ctx5 = document.getElementById('wardChartLos');
            if (!ctx1 || !ctx2 || !ctx3 || !ctx4 || !ctx5 || typeof Chart === 'undefined') return;

            const counts = { general: 0, senior: 0, pdea: 0 };
            beds.forEach(b => {
                if (!b.patient) return;
                if (b.patient.category === 'senior') counts.senior += 1;
                else if (b.patient.category === 'pdea') counts.pdea += 1;
                else counts.general += 1;
            });

            const labels = ['General', 'Senior', 'PDEA'];
            const data = [counts.general, counts.senior, counts.pdea];

            if (wardCategoryChart) {
                wardCategoryChart.data.labels = labels;
                wardCategoryChart.data.datasets[0].data = data;
                wardCategoryChart.update();
            } else {
                wardCategoryChart = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Beds',
                            data,
                            backgroundColor: ['rgba(37,99,235,0.25)', 'rgba(168,85,247,0.25)', 'rgba(34,197,94,0.25)'],
                            borderColor: ['rgba(37,99,235,0.9)', 'rgba(168,85,247,0.9)', 'rgba(34,197,94,0.9)'],
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                    }
                });
            }

            const tLabels = ['Day 1','Day 2','Day 3','Day 4','Day 5','Day 6','Day 7'];
            const tData = wardSample.turnover7d.slice();
            if (wardTurnoverChart) {
                wardTurnoverChart.data.labels = tLabels;
                wardTurnoverChart.data.datasets[0].data = tData;
                wardTurnoverChart.update();
            } else {
                wardTurnoverChart = new Chart(ctx2, {
                    type: 'line',
                    data: {
                        labels: tLabels,
                        datasets: [{
                            label: 'Patients',
                            data: tData,
                            borderColor: 'rgba(168,85,247,0.9)',
                            backgroundColor: 'rgba(168,85,247,0.2)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                    }
                });
            }

            const isoCount = beds.filter(b => !!b.isolation).length;
            const nonIsoCount = Math.max(0, beds.length - isoCount);
            if (wardIsolationChart) {
                wardIsolationChart.data.datasets[0].data = [isoCount, nonIsoCount];
                wardIsolationChart.update();
            } else {
                wardIsolationChart = new Chart(ctx3, {
                    type: 'doughnut',
                    data: {
                        labels: ['Isolation', 'Non-isolation'],
                        datasets: [{
                            data: [isoCount, nonIsoCount],
                            backgroundColor: ['rgba(245,158,11,0.35)', 'rgba(148,163,184,0.25)'],
                            borderColor: ['rgba(245,158,11,0.9)', 'rgba(148,163,184,0.6)'],
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } },
                        cutout: '68%'
                    }
                });
            }

            const frLabels = ['Day 1','Day 2','Day 3','Day 4','Day 5','Day 6','Day 7'];
            const frData = wardSample.fallRisk7d.slice();
            if (wardFallRiskChart) {
                wardFallRiskChart.data.labels = frLabels;
                wardFallRiskChart.data.datasets[0].data = frData;
                wardFallRiskChart.update();
            } else {
                wardFallRiskChart = new Chart(ctx4, {
                    type: 'line',
                    data: {
                        labels: frLabels,
                        datasets: [{
                            label: 'High fall-risk',
                            data: frData,
                            borderColor: 'rgba(239,68,68,0.9)',
                            backgroundColor: 'rgba(239,68,68,0.18)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                    }
                });
            }

            const los = { general: [], senior: [], pdea: [] };
            beds.forEach(b => {
                if (!b.patient || !b.admitted_at) return;
                const hrs = (Date.now() - b.admitted_at) / (1000 * 60 * 60);
                if (b.patient.category === 'senior') los.senior.push(hrs);
                else if (b.patient.category === 'pdea') los.pdea.push(hrs);
                else los.general.push(hrs);
            });
            const losAvg = [
                los.general.length ? Math.round(los.general.reduce((a, x) => a + x, 0) / los.general.length) : 0,
                los.senior.length ? Math.round(los.senior.reduce((a, x) => a + x, 0) / los.senior.length) : 0,
                los.pdea.length ? Math.round(los.pdea.reduce((a, x) => a + x, 0) / los.pdea.length) : 0,
            ];
            if (wardLosChart) {
                wardLosChart.data.labels = ['General', 'Senior', 'PDEA'];
                wardLosChart.data.datasets[0].data = losAvg;
                wardLosChart.update();
            } else {
                wardLosChart = new Chart(ctx5, {
                    type: 'bar',
                    data: {
                        labels: ['General', 'Senior', 'PDEA'],
                        datasets: [{
                            label: 'Avg LOS (hrs)',
                            data: losAvg,
                            backgroundColor: ['rgba(37,99,235,0.25)', 'rgba(168,85,247,0.25)', 'rgba(34,197,94,0.25)'],
                            borderColor: ['rgba(37,99,235,0.9)', 'rgba(168,85,247,0.9)', 'rgba(34,197,94,0.9)'],
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                    }
                });
            }
        }

        function wardRenderWorklist(beds) {
            const tbody = document.getElementById('wardWorklistTbody');
            if (!tbody) return;
            if (beds.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-6 text-sm text-gray-500">No patients found.</td></tr>`;
                return;
            }

            tbody.innerHTML = beds.map(b => {
                const cat = wardCategoryLabel(b.patient.category);
                const catTone = (b.patient.category === 'senior') ? 'purple' : (b.patient.category === 'pdea' ? 'green' : 'blue');
                const flags = [];
                if (b.isolation) flags.push(wardBadge('Isolation', 'yellow'));
                if (b.fall_risk) flags.push(wardBadge('Fall-risk', 'red'));
                if (flags.length === 0) flags.push(wardBadge('None', 'gray'));
                const statusTone = (b.status || '').toLowerCase().includes('transfer') ? 'yellow' : ((b.status || '').toLowerCase().includes('admit') ? 'green' : 'blue');

                return `
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">${b.bed}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">${b.patient.name} <span class="text-xs text-gray-500">(${b.patient.age}/${b.patient.gender})</span></td>
                        <td class="px-6 py-4 text-sm">${wardBadge(cat, catTone)}</td>
                        <td class="px-6 py-4 text-sm">${flags.join(' ')}</td>
                        <td class="px-6 py-4 text-sm">${wardBadge(b.status || '—', statusTone)}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" class="wardSelectBtn px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50" data-pid="${b.patient.id}">Select</button>
                                <button type="button" class="wardMoveBtn px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" data-bed="${b.bed}">Move</button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            Array.from(document.querySelectorAll('.wardSelectBtn')).forEach(btn => {
                btn.addEventListener('click', () => {
                    const pidRaw = (btn.getAttribute('data-pid') || '').toString();
                    if (!pidRaw || !/^\d+$/.test(pidRaw)) return;
                    wardSelectedPatientId = Number(pidRaw);
                    wardApplySelectedPatient();
                });
            });

            Array.from(document.querySelectorAll('.wardMoveBtn')).forEach(btn => {
                btn.addEventListener('click', () => {
                    const bed = (btn.getAttribute('data-bed') || '').toString();
                    if (!bed) return;
                    wardOpenTransferModal(bed);
                });
            });
        }

        function wardRenderBedsTable(beds) {
            const tbody = document.getElementById('wardBedsTbody');
            if (!tbody) return;
            tbody.innerHTML = beds.map(b => {
                const iso = b.isolation ? wardBadge('Yes', 'yellow') : wardBadge('No', 'gray');
                const fall = b.fall_risk ? wardBadge('High', 'red') : wardBadge('Low', 'gray');
                const assigned = b.patient ? b.patient.name : wardBadge('Empty', 'gray');
                return `
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">${b.bed}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${b.room}</td>
                        <td class="px-4 py-3 text-sm text-gray-800">${assigned}</td>
                        <td class="px-4 py-3 text-sm">${iso}</td>
                        <td class="px-4 py-3 text-sm">${fall}</td>
                    </tr>
                `;
            }).join('');
        }

        let wardToastTimer = null;
        function showWardToast(kind, text) {
            const wrap = document.getElementById('wardToast');
            const t = document.getElementById('wardToastText');
            const ic = document.getElementById('wardToastIcon');
            if (!wrap || !t || !ic) return;
            t.textContent = text || '';
            if (kind === 'err') {
                ic.className = 'fas fa-circle-xmark';
            } else {
                ic.className = 'fas fa-circle-check';
            }
            wrap.classList.remove('hidden');
            clearTimeout(wardToastTimer);
            wardToastTimer = setTimeout(() => wrap.classList.add('hidden'), 2400);
        }

        function wardOpenTransferModal(fromBed) {
            const row = wardSample.beds.find(b => b.bed === fromBed) || null;
            if (!row || !row.patient) return;
            wardTransferFromBed = fromBed;

            const meta = document.getElementById('wardTransferModalMeta');
            if (meta) meta.textContent = `${row.patient.name} (Bed ${row.bed}, ${row.room})`;

            const sel = document.getElementById('wardTransferTargetBed');
            if (sel) {
                const empties = wardSample.beds.filter(b => b.bed !== fromBed && !b.patient);
                sel.innerHTML = '<option value="">Select bed</option>' + empties.map(b => `<option value="${b.bed}">${b.bed} — ${b.room} (${b.status || 'Available'})</option>`).join('');
            }
            const reason = document.getElementById('wardTransferReason');
            if (reason) reason.value = '';
            const confirm = document.getElementById('wardTransferConfirmBtn');
            if (confirm) confirm.disabled = true;

            toggleModal('wardTransferModal');
        }

        const wardTransferTargetBed = document.getElementById('wardTransferTargetBed');
        if (wardTransferTargetBed) {
            wardTransferTargetBed.addEventListener('change', () => {
                const v = (wardTransferTargetBed.value || '').toString();
                const confirm = document.getElementById('wardTransferConfirmBtn');
                if (confirm) confirm.disabled = !v;
            });
        }

        const wardTransferConfirmBtn = document.getElementById('wardTransferConfirmBtn');
        if (wardTransferConfirmBtn) {
            wardTransferConfirmBtn.addEventListener('click', () => {
                const from = (wardTransferFromBed || '').toString();
                const to = (document.getElementById('wardTransferTargetBed')?.value || '').toString();
                if (!from || !to) return;
                const fromRow = wardSample.beds.find(b => b.bed === from) || null;
                const toRow = wardSample.beds.find(b => b.bed === to) || null;
                if (!fromRow || !fromRow.patient || !toRow || toRow.patient) {
                    showWardToast('err', 'Unable to move bed');
                    return;
                }

                toRow.patient = fromRow.patient;
                toRow.isolation = fromRow.isolation;
                toRow.fall_risk = fromRow.fall_risk;
                toRow.status = fromRow.status;
                toRow.admitted_at = fromRow.admitted_at;

                fromRow.patient = null;
                fromRow.isolation = false;
                fromRow.fall_risk = false;
                fromRow.status = 'Available';
                fromRow.admitted_at = null;

                wardTransferFromBed = null;
                toggleModal('wardTransferModal');
                showWardToast('ok', `Moved to ${to}`);
                renderWard();
            });
        }

        function wardApplySelectedPatient() {
            const beds = wardSample.beds;
            const row = beds.find(b => b.patient && b.patient.id === wardSelectedPatientId) || null;

            const meta = document.getElementById('wardSelectedPatientMeta');
            const saveDailyBtn = document.getElementById('wardSaveDailyBtn');
            const saveDisBtn = document.getElementById('wardSaveDischargeBtn');
            const printTransferBtn = document.getElementById('wardPrintTransferBtn');
            const printDischargeBtn = document.getElementById('wardPrintDischargeBtn');
            const pdeaPanel = document.getElementById('wardPdeaPanel');

            const has = !!row;
            if (saveDailyBtn) saveDailyBtn.disabled = !has;
            if (saveDisBtn) saveDisBtn.disabled = !has;
            if (printTransferBtn) printTransferBtn.disabled = !has;
            if (printDischargeBtn) printDischargeBtn.disabled = !has;
            if (!meta) return;

            if (!row) {
                meta.textContent = 'Select a patient from Worklist.';
                if (pdeaPanel) pdeaPanel.classList.add('hidden');
                return;
            }

            meta.textContent = `Selected: ${row.patient.name} (Bed ${row.bed})`;
            if (pdeaPanel) pdeaPanel.classList.toggle('hidden', row.patient.category !== 'pdea');
        }

        function renderWard() {
            const beds = wardGetFilteredBeds();
            wardRenderStats(beds);
            wardRenderCharts(beds);
            wardRenderWorklist(beds);
            wardRenderBedsTable(wardGetAllBeds());
            wardApplySelectedPatient();
        }

        const wardFilterEl = document.getElementById('wardFilter');
        if (wardFilterEl) {
            wardFilterEl.addEventListener('change', () => {
                wardSelectedPatientId = null;
                renderWard();
            });
        }
        const wardSearchEl = document.getElementById('wardSearch');
        if (wardSearchEl) {
            wardSearchEl.addEventListener('input', () => renderWard());
        }
        const wardRefreshBtn = document.getElementById('wardRefreshBtn');
        if (wardRefreshBtn) {
            wardRefreshBtn.addEventListener('click', () => renderWard());
        }

        const wardSaveDailyBtn = document.getElementById('wardSaveDailyBtn');
        if (wardSaveDailyBtn) {
            wardSaveDailyBtn.addEventListener('click', () => {
                const has = typeof wardSelectedPatientId === 'number' && wardSelectedPatientId > 0;
                if (!has) return;
                const txt = (document.getElementById('wardDailyNote')?.value || '').toString().trim();
                if (!txt) return;
                try {
                    const k = `ward_daily_${wardSelectedPatientId}`;
                    localStorage.setItem(k, txt);
                    showWardToast('ok', 'Daily note saved');
                } catch (e) {}
            });
        }

        const wardSaveDischargeBtn = document.getElementById('wardSaveDischargeBtn');
        if (wardSaveDischargeBtn) {
            wardSaveDischargeBtn.addEventListener('click', () => {
                const has = typeof wardSelectedPatientId === 'number' && wardSelectedPatientId > 0;
                if (!has) return;
                const payload = {
                    vitals: !!document.getElementById('wardChkVitals')?.checked,
                    labs: !!document.getElementById('wardChkLabs')?.checked,
                    meds: !!document.getElementById('wardChkMeds')?.checked,
                    education: !!document.getElementById('wardChkEducation')?.checked,
                    followup: !!document.getElementById('wardChkFollowup')?.checked,
                    ret: !!document.getElementById('wardChkReturn')?.checked,
                    facility: (document.getElementById('wardTransferFacility')?.value || '').toString(),
                    notes: (document.getElementById('wardTransferNotes')?.value || '').toString(),
                };
                try {
                    const k = `ward_discharge_${wardSelectedPatientId}`;
                    localStorage.setItem(k, JSON.stringify(payload));
                    showWardToast('ok', 'Prep saved');
                } catch (e) {}
            });
        }

        function wardGetSelectedPatientRow() {
            if (!(typeof wardSelectedPatientId === 'number' && wardSelectedPatientId > 0)) return null;
            return wardSample.beds.find(b => b.patient && b.patient.id === wardSelectedPatientId) || null;
        }

        function wardOpenPrintWindow(title, bodyHtml) {
            const w = window.open('', '_blank');
            if (!w) return;
            w.document.open();
            w.document.write(`<!DOCTYPE html><html><head><meta charset="utf-8"><title>${title}</title><style>
                body{font-family: Arial, sans-serif; padding: 24px; color: #111827;}
                h1{font-size: 18px; margin: 0 0 12px;}
                .meta{font-size: 12px; color:#4b5563; margin-bottom: 16px;}
                .box{border:1px solid #e5e7eb; border-radius: 10px; padding: 12px; margin-bottom: 12px;}
                .row{display:flex; gap:12px; flex-wrap:wrap;}
                .col{flex:1; min-width: 220px;}
                .chk{margin: 6px 0;}
                @media print{button{display:none;}}
            </style></head><body>
                <button onclick="window.print()" style="margin-bottom:12px; padding:8px 12px;">Print</button>
                ${bodyHtml}
            </body></html>`);
            w.document.close();
        }

        const wardPrintTransferBtn = document.getElementById('wardPrintTransferBtn');
        if (wardPrintTransferBtn) {
            wardPrintTransferBtn.addEventListener('click', () => {
                const row = wardGetSelectedPatientRow();
                if (!row) return;
                const facility = (document.getElementById('wardTransferFacility')?.value || '').toString();
                const notes = (document.getElementById('wardTransferNotes')?.value || '').toString();
                const html = `
                    <h1>Transfer Form</h1>
                    <div class="meta">Generated: ${new Date().toLocaleString()}</div>
                    <div class="box">
                        <div><b>Patient:</b> ${row.patient.name} (${row.patient.age}/${row.patient.gender})</div>
                        <div><b>From Bed:</b> ${row.bed} — ${row.room}</div>
                        <div><b>Category:</b> ${wardCategoryLabel(row.patient.category)}</div>
                        <div><b>Flags:</b> Isolation=${row.isolation ? 'Yes' : 'No'}, Fall-risk=${row.fall_risk ? 'High' : 'Low'}</div>
                        <div><b>Status:</b> ${row.status || '-'}</div>
                    </div>
                    <div class="box">
                        <div><b>Receiving Facility:</b> ${facility || '-'}</div>
                        <div style="margin-top:8px;"><b>Transfer Notes:</b></div>
                        <div>${(notes || '-').replace(/\n/g,'<br>')}</div>
                    </div>
                    <div class="row">
                        <div class="col box"><b>Prepared by:</b><div style="margin-top:30px; border-top:1px solid #e5e7eb; padding-top:6px;">Signature</div></div>
                        <div class="col box"><b>Received by:</b><div style="margin-top:30px; border-top:1px solid #e5e7eb; padding-top:6px;">Signature</div></div>
                    </div>
                `;
                wardOpenPrintWindow('Transfer Form', html);
            });
        }

        const wardPrintDischargeBtn = document.getElementById('wardPrintDischargeBtn');
        if (wardPrintDischargeBtn) {
            wardPrintDischargeBtn.addEventListener('click', () => {
                const row = wardGetSelectedPatientRow();
                if (!row) return;
                const chks = [
                    { id: 'wardChkVitals', label: 'Vitals stable' },
                    { id: 'wardChkLabs', label: 'Labs/Imaging reviewed' },
                    { id: 'wardChkMeds', label: 'Medications reconciled' },
                    { id: 'wardChkEducation', label: 'Discharge instructions given' },
                    { id: 'wardChkFollowup', label: 'Follow-up arranged' },
                    { id: 'wardChkReturn', label: 'Return precautions documented' },
                ].map(x => {
                    const ok = !!document.getElementById(x.id)?.checked;
                    return `<div class="chk">[${ok ? 'x' : ' '}] ${x.label}</div>`;
                }).join('');
                const html = `
                    <h1>Discharge Checklist</h1>
                    <div class="meta">Generated: ${new Date().toLocaleString()}</div>
                    <div class="box">
                        <div><b>Patient:</b> ${row.patient.name} (${row.patient.age}/${row.patient.gender})</div>
                        <div><b>Bed:</b> ${row.bed} — ${row.room}</div>
                    </div>
                    <div class="box">
                        ${chks}
                    </div>
                    <div class="row">
                        <div class="col box"><b>Nurse:</b><div style="margin-top:30px; border-top:1px solid #e5e7eb; padding-top:6px;">Signature</div></div>
                        <div class="col box"><b>Physician:</b><div style="margin-top:30px; border-top:1px solid #e5e7eb; padding-top:6px;">Signature</div></div>
                    </div>
                `;
                wardOpenPrintWindow('Discharge Checklist', html);
            });
        }

        let erConsultSearchTimer = null;
        const erConsultSearchEl = document.getElementById('erConsultPatientSearch');
        if (erConsultSearchEl) {
            erConsultSearchEl.addEventListener('input', (e) => {
                const q = (e.target.value || '').toString().trim();
                clearTimeout(erConsultSearchTimer);
                erConsultSearchTimer = setTimeout(() => erConsultPatientSearch(q), 250);
            });
        }

        const erConsultHistoryBtn = document.getElementById('erConsultHistoryBtn');
        if (erConsultHistoryBtn) {
            erConsultHistoryBtn.addEventListener('click', async () => {
                const pid = (document.getElementById('erConsultPatientId')?.value || '').toString().trim();
                toggleModal('erConsultHistoryModal');

                const metaEl = document.getElementById('erConsultHistoryModalMeta');
                if (metaEl) metaEl.textContent = '';

                const listEl = document.getElementById('erConsultHistoryModalList');
                if (listEl) listEl.textContent = pid ? 'Loading...' : 'Search a patient to load notes.';

                if (!pid) {
                    erConsultHistoryPatientId = '';
                    setErConsultHistoryViewingMode(false);
                    return;
                }

                erConsultHistoryPatientId = pid;
                try {
                    await loadErConsultNotesByPatient(Number(pid));
                    renderErConsultHistoryModal(erConsultEncounterCache, erConsultNotesCache);
                } catch (e) {
                    showErConsultAlert('err', e && e.message ? e.message : 'Failed to load notes');
                    setErConsultHistoryViewingMode(true);
                }
            });
        }

        const erConsultHistoryModalBackBtn = document.getElementById('erConsultHistoryModalBackBtn');
        if (erConsultHistoryModalBackBtn) {
            erConsultHistoryModalBackBtn.addEventListener('click', () => {
                erConsultHistoryPatientId = '';
                const metaEl = document.getElementById('erConsultHistoryModalMeta');
                if (metaEl) metaEl.textContent = '';
                const listEl = document.getElementById('erConsultHistoryModalList');
                if (listEl) listEl.textContent = 'Search a patient to load notes.';
                setErConsultHistoryViewingMode(false);
            });
        }

        const erConsultHistoryModalRefreshBtn = document.getElementById('erConsultHistoryModalRefreshBtn');
        if (erConsultHistoryModalRefreshBtn) {
            erConsultHistoryModalRefreshBtn.addEventListener('click', async () => {
                const pidMain = (document.getElementById('erConsultPatientId')?.value || '').toString().trim();
                const pid = pidMain || (erConsultHistoryPatientId || '').toString();
                if (!pid) {
                    setErConsultHistoryViewingMode(false);
                    return;
                }
                try {
                    await loadErConsultNotesByPatient(Number(pid));
                    renderErConsultHistoryModal(erConsultEncounterCache, erConsultNotesCache);
                } catch (e) {
                    showErConsultAlert('err', e && e.message ? e.message : 'Failed to load notes');
                }
            });
        }

        const erConsultHistoryPatientSearchEl = document.getElementById('erConsultHistoryPatientSearch');
        if (erConsultHistoryPatientSearchEl) {
            let t = null;
            erConsultHistoryPatientSearchEl.addEventListener('input', () => {
                const q = (erConsultHistoryPatientSearchEl.value || '').toString();
                if (t) clearTimeout(t);
                t = setTimeout(() => { erConsultHistoryPatientSearch(q); }, 250);
            });
        }

        const erConsultAutoFillBtn = document.getElementById('erConsultAutoFillBtn');
        if (erConsultAutoFillBtn) {
            erConsultAutoFillBtn.addEventListener('click', async () => {
                const pid = (document.getElementById('erConsultPatientId')?.value || '').toString().trim();
                if (!pid) {
                    showErConsultAlert('err', 'Select a patient');
                    return;
                }

                erConsultAutoFillBtn.disabled = true;
                try {
                    const fields = collectErConsultAiFields();
                    const seed = String(pid);
                    const res = await fetch('api/philhealth/ai_fill.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ page: 'consultation_notes_er', seed, fields }),
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        throw new Error((json && json.error) ? json.error : 'AI fill failed');
                    }
                    applyAiValuesToErConsult(fields, json.values || []);
                    showErConsultAlert('ok', 'Auto fill complete');
                } catch (e) {
                    showErConsultAlert('err', e && e.message ? e.message : 'AI fill failed');
                } finally {
                    erConsultAutoFillBtn.disabled = false;
                }
            });
        }

        const erConsultSaveBtn = document.getElementById('erConsultSaveBtn');
        if (erConsultSaveBtn) {
            erConsultSaveBtn.addEventListener('click', async () => {
                const pid = (document.getElementById('erConsultPatientId')?.value || '').toString().trim();
                if (!pid) {
                    showErConsultAlert('err', 'Select a patient');
                    return;
                }

                if (!erConsultHasAnyInput()) {
                    showErConsultAlert('err', 'Enter note details');
                    return;
                }

                const noteText = buildErConsultNoteText().trim();
                if (!noteText) {
                    showErConsultAlert('err', 'Enter note details');
                    return;
                }

                const isEditing = Number.isFinite(Number(erConsultEditingNoteId)) && Number(erConsultEditingNoteId) > 0;
                const url = isEditing ? 'api/er_notes/update.php' : 'api/er_notes/create.php';
                const payload = isEditing
                    ? { note_id: Number(erConsultEditingNoteId), patient_id: Number(pid), note_text: noteText }
                    : { patient_id: Number(pid), note_text: noteText };

                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    showErConsultAlert('err', (json && json.error) ? json.error : 'Failed to save note');
                    return;
                }

                const resetIds = [
                    'erSoapChiefComplaint','erSoapBp','erSoapPulse','erSoapTemp','erSoapExam','erSoapPrimaryDx','erSoapDifferentialDx',
                    'erSoapInvestigations','erSoapMedications','erSoapAdvice','erSoapFollowUp','erSoapDoctorSignature'
                ];
                resetIds.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });

                showErConsultAlert('ok', isEditing ? 'Note updated' : 'Note saved');
                toggleErConsultSavedModal(true, isEditing ? 'The consultation note has been updated successfully.' : 'The consultation note has been saved successfully.');
                setErConsultEditingMode(null);
                try {
                    await loadErConsultNotesByPatient(Number(pid));
                    const modalOpen = !document.getElementById('erConsultHistoryModal')?.classList.contains('hidden');
                    if (modalOpen) {
                        renderErConsultHistoryModal(erConsultEncounterCache, erConsultNotesCache);
                    }
                } catch (e) {
                    showErConsultAlert('err', e && e.message ? e.message : 'Failed to load notes');
                }
            });
        }

        let erTestSearchTimer = null;
        const erTestSearchEl = document.getElementById('erTestSearch');
        if (erTestSearchEl) {
            erTestSearchEl.addEventListener('input', (e) => {
                const q = (e.target.value || '').toString();
                clearTimeout(erTestSearchTimer);
                erTestSearchTimer = setTimeout(() => filterErTestList(q), 150);
            });
        }

        loadErTestsFromFees();
        loadErDoctors();
        const erDoctorEl = document.getElementById('erDoctor');
        if (erDoctorEl) {
            erDoctorEl.addEventListener('change', refreshErDoctorAvailability);
        }
    </script>
</body>

</html>
