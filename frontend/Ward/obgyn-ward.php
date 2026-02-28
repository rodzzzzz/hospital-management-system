<?php require_once __DIR__ . '/../auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/../config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OB-GYN Ward - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .bed-card { transition: all 0.3s ease; }
        .bed-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .status-occupied   { border-left: 4px solid #ef4444; }
        .status-available  { border-left: 4px solid #10b981; }
        .status-cleaning    { border-left: 4px solid #eab308; }
        .status-observation { border-left: 4px solid #f59e0b; background: #fffbeb; }
        .status-labor      { border-left: 4px solid #ec4899; animation: pulse-pink 2s infinite; }
        .status-postpartum { border-left: 4px solid #a855f7; }
        @keyframes pulse-pink { 0%,100%{opacity:1} 50%{opacity:.7} }
    </style>
</head>
<body class="bg-gray-50">
<div class="min-h-screen">
    <?php include __DIR__ . '/includes/ward-sidebar.php'; ?>
    <main class="ml-64 p-8">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">OB-GYN Ward</h1>
                <p class="text-sm text-gray-600 mt-1">Obstetrics & Gynecology patient care and delivery tracking</p>
            </div>
            <div class="flex gap-3">
                <button type="button" id="btnAdmitObgyn" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Admit Patient
                </button>
                <button type="button" id="btnRefreshObgyn" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-rotate mr-2"></i>Refresh
                </button>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Beds</p>
                        <p class="text-2xl font-bold text-gray-900" id="statTotal">20</p>
                    </div>
                    <i class="fas fa-bed text-blue-500 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">In Labor</p>
                        <p class="text-2xl font-bold text-pink-600" id="statLabor">0</p>
                    </div>
                    <i class="fas fa-baby text-pink-500 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Postpartum</p>
                        <p class="text-2xl font-bold text-purple-600" id="statPostpartum">0</p>
                    </div>
                    <i class="fas fa-heart text-purple-500 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Available</p>
                        <p class="text-2xl font-bold text-green-600" id="statAvailable">20</p>
                    </div>
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Deliveries Today</p>
                        <p class="text-2xl font-bold text-indigo-600" id="statDeliveries">0</p>
                    </div>
                    <i class="fas fa-star-of-life text-indigo-500 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Ward Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex gap-4">
                    <button class="obgyn-tab px-4 py-3 font-medium text-pink-600 border-b-2 border-pink-600 transition-colors" data-section="maternity">
                        <i class="fas fa-baby mr-2"></i>Maternity
                    </button>
                    <button class="obgyn-tab px-4 py-3 font-medium text-gray-500 hover:text-gray-700 transition-colors" data-section="gynecology">
                        <i class="fas fa-venus mr-2"></i>Gynecology
                    </button>
                    <button class="obgyn-tab px-4 py-3 font-medium text-gray-500 hover:text-gray-700 transition-colors" data-section="nursery">
                        <i class="fas fa-baby-carriage mr-2"></i>Nursery
                    </button>
                </nav>
            </div>
        </div>

        <!-- Maternity Section -->
        <div id="section-maternity" class="obgyn-section">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Maternity Ward - Bed Overview</h2>
                    <div class="flex gap-2 text-xs">
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-pink-500 inline-block"></span>In Labor</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-purple-500 inline-block"></span>Postpartum</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>Available</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span>Cleaning</span>
                    </div>
                </div>
                <div id="maternityBeds" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"></div>
            </div>
        </div>

        <!-- Gynecology Section -->
        <div id="section-gynecology" class="obgyn-section hidden">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm text-gray-500">Post-Op</p><p class="text-2xl font-bold text-red-600" id="gynStatPostOp">0</p></div>
                        <i class="fas fa-scalpel text-red-400 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm text-gray-500">Medical GYN</p><p class="text-2xl font-bold text-orange-600" id="gynStatMedical">0</p></div>
                        <i class="fas fa-pills text-orange-400 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm text-gray-500">Available</p><p class="text-2xl font-bold text-green-600" id="gynStatAvailable">0</p></div>
                        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm text-gray-500">For Discharge</p><p class="text-2xl font-bold text-blue-600" id="gynStatDischarge">0</p></div>
                        <i class="fas fa-door-open text-blue-400 text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Gynecology Ward - Bed Overview</h2>
                    <div class="flex gap-2 text-xs">
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>Post-Op</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-orange-400 inline-block"></span>Medical</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>Available</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span>Cleaning</span>
                    </div>
                </div>
                <div id="gynecologyBeds" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"></div>
            </div>
        </div>

        <!-- Nursery Section -->
        <div id="section-nursery" class="obgyn-section hidden">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm text-gray-500">Newborns</p><p class="text-2xl font-bold text-indigo-600" id="nurStatTotal">0</p></div>
                        <i class="fas fa-baby text-indigo-400 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm text-gray-500">Under Observation</p><p class="text-2xl font-bold text-yellow-600" id="nurStatObs">0</p></div>
                        <i class="fas fa-eye text-yellow-400 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm text-gray-500">Stable</p><p class="text-2xl font-bold text-green-600" id="nurStatStable">0</p></div>
                        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm text-gray-500">For Discharge</p><p class="text-2xl font-bold text-blue-600" id="nurStatDischarge">0</p></div>
                        <i class="fas fa-door-open text-blue-400 text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Nursery / Newborn Care</h2>
                    <div class="flex gap-2 text-xs">
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-indigo-500 inline-block"></span>Occupied</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span>Observation</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>Available</span>
                    </div>
                </div>
                <div id="nurseryBeds" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"></div>
            </div>
        </div>

    </main>
</div>

<!-- Patient Details Modal -->
<div id="patientDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white flex items-center justify-between p-6 border-b z-10">
                <h3 class="text-lg font-semibold text-gray-900" id="detailsModalTitle">Patient Details</h3>
                <button type="button" id="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="patientDetailsContent" class="p-6"></div>
        </div>
    </div>
</div>

<!-- Vitals Modal -->
<div id="vitalsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Update Vital Signs</h3>
                <button type="button" id="closeVitalsModal" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="vitalsForm" class="p-6">
                <input type="hidden" name="bed_id" value="">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Temperature (°C)</label>
                        <input type="number" step="0.1" name="temperature" placeholder="36.5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Heart Rate (bpm)</label>
                        <input type="number" name="heart_rate" placeholder="80" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Pressure</label>
                        <input type="text" name="blood_pressure" placeholder="120/80" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Respiratory Rate</label>
                        <input type="number" name="respiratory_rate" placeholder="20" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">O2 Saturation (%)</label>
                        <input type="number" name="oxygen_saturation" placeholder="98" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                        <input type="number" step="0.1" name="weight" placeholder="60" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div id="fetalHrField">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fetal Heart Rate (bpm)</label>
                        <input type="number" name="fetal_heart_rate" placeholder="140" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div id="contractionField">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contraction Frequency</label>
                        <input type="text" name="contractions" placeholder="e.g. q5min" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" placeholder="Additional observations..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelVitals" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700"><i class="fas fa-save mr-2"></i>Save Vitals</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Progress Note Modal -->
<div id="progressNoteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Add Progress Note</h3>
                <button type="button" id="closeProgressModal" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="progressNoteForm" class="p-6">
                <input type="hidden" name="bed_id" value="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Note Type</label>
                    <select name="note_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <option value="daily_rounds">Daily Rounds</option>
                        <option value="labor_progress">Labor Progress</option>
                        <option value="delivery_note">Delivery Note</option>
                        <option value="postpartum_check">Postpartum Check</option>
                        <option value="newborn_check">Newborn Check</option>
                        <option value="medication">Medication</option>
                        <option value="condition_change">Condition Change</option>
                        <option value="general">General Note</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Note *</label>
                    <textarea name="note_text" rows="5" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" placeholder="Enter detailed progress note..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recorded By</label>
                    <input type="text" name="recorded_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" placeholder="Nurse / Physician name">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelProgress" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700"><i class="fas fa-save mr-2"></i>Save Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Wound Care Modal (GYN) -->
<div id="woundCareModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Wound Care Record</h3>
                <button type="button" id="closeWoundModal" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="woundCareForm" class="p-6">
                <input type="hidden" name="bed_id" value="">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date &amp; Time</label>
                        <input type="datetime-local" name="wound_datetime" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Wound Site</label>
                        <input type="text" name="wound_site" placeholder="e.g. Lower abdomen" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Wound Condition</label>
                        <select name="wound_condition" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="clean">Clean &amp; Dry</option>
                            <option value="healing">Healing Well</option>
                            <option value="mild_discharge">Mild Discharge</option>
                            <option value="infected">Signs of Infection</option>
                            <option value="dehisced">Dehisced</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pain Score (0-10)</label>
                        <input type="number" min="0" max="10" name="pain_score" placeholder="e.g. 3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dressing Type</label>
                        <input type="text" name="dressing_type" placeholder="e.g. Dry sterile dressing" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Done By</label>
                        <input type="text" name="done_by" placeholder="Nurse / Physician name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                    <textarea name="wound_remarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Any observations, drainage, odor, etc."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelWound" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"><i class="fas fa-save mr-2"></i>Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Feeding Record Modal (Nursery) -->
<div id="feedingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Feeding Record</h3>
                <button type="button" id="closeFeedingModal" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="feedingForm" class="p-6">
                <input type="hidden" name="bed_id" value="">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Feeding Time</label>
                        <input type="datetime-local" name="feeding_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Feeding Type</label>
                        <select name="feeding_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="breastfeeding">Breastfeeding</option>
                            <option value="formula">Formula</option>
                            <option value="mixed">Mixed</option>
                            <option value="IV_fluids">IV Fluids Only</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (mL)</label>
                        <input type="number" name="feeding_amount" placeholder="e.g. 30" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (min)</label>
                        <input type="number" name="feeding_duration" placeholder="e.g. 15" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tolerance</label>
                        <select name="feeding_tolerance" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="good">Good – No regurgitation</option>
                            <option value="mild_regurg">Mild Regurgitation</option>
                            <option value="vomiting">Vomiting</option>
                            <option value="refused">Refused</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recorded By</label>
                        <input type="text" name="recorded_by" placeholder="Nurse name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="feeding_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Any additional observations..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelFeeding" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"><i class="fas fa-save mr-2"></i>Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delivery Tracking Modal -->
<div id="deliveryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Record Delivery</h3>
                <button type="button" id="closeDeliveryModal" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="deliveryForm" class="p-6">
                <input type="hidden" name="bed_id" value="">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Time *</label>
                        <input type="datetime-local" name="delivery_time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Type *</label>
                        <select name="delivery_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                            <option value="">Select type</option>
                            <option value="NSD">Normal Spontaneous Delivery (NSD)</option>
                            <option value="CS">Caesarean Section (CS)</option>
                            <option value="assisted">Assisted Delivery</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Newborn Sex</label>
                        <select name="newborn_sex" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Birth Weight (kg)</label>
                        <input type="number" step="0.01" name="birth_weight" placeholder="3.2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">APGAR Score (1 min)</label>
                        <input type="number" min="0" max="10" name="apgar_1min" placeholder="9" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">APGAR Score (5 min)</label>
                        <input type="number" min="0" max="10" name="apgar_5min" placeholder="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Attending Physician</label>
                    <input type="text" name="attending_physician" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" placeholder="Dr. ...">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Notes</label>
                    <textarea name="delivery_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" placeholder="Complications, remarks..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelDelivery" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700"><i class="fas fa-baby mr-2"></i>Record Delivery</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ─── Sample data (replace with API calls) ────────────────────────────────────
const bedsData = {
    maternity: [
        {
            id: 1, bedNumber: 'MAT-01', section: 'maternity',
            status: 'labor',
            patient: {
                name: 'Rosario Dela Cruz', age: 28, admissionDate: '2026-02-28',
                diagnosis: 'G2P1 Active Labor', condition: 'In Labor',
                gest_age: '39 weeks', bloodType: 'A+', allergies: 'None',
                guardian: 'Ricardo Dela Cruz (Husband)', contact: '0917-111-2222',
            },
            vitals: { temperature: 37.0, heartRate: 95, bloodPressure: '120/80',
                      respiratoryRate: 20, oxygenSaturation: 99,
                      fetalHeartRate: 145, contractions: 'q5min',
                      lastUpdated: '2026-02-28 17:30' },
            treatment: 'IV access, continuous fetal monitoring',
            notes: [],
        },
        {
            id: 2, bedNumber: 'MAT-02', section: 'maternity',
            status: 'postpartum',
            patient: {
                name: 'Maria Santos', age: 24, admissionDate: '2026-02-27',
                diagnosis: 'G1P1 Post-NSD', condition: 'Postpartum',
                gest_age: '40 weeks', bloodType: 'O+', allergies: 'None',
                guardian: 'Jose Santos (Husband)', contact: '0917-333-4444',
            },
            vitals: { temperature: 36.8, heartRate: 78, bloodPressure: '110/70',
                      respiratoryRate: 18, oxygenSaturation: 99,
                      fetalHeartRate: null, contractions: null,
                      lastUpdated: '2026-02-28 16:00' },
            treatment: 'Breastfeeding support, uterine monitoring',
            notes: [],
        },
    ],
    gynecology: [
        {
            id: 10, bedNumber: 'GYN-01', section: 'gynecology',
            status: 'occupied',
            patient: {
                name: 'Ana Reyes', age: 42, admissionDate: '2026-02-27',
                diagnosis: 'Post-op Myomectomy', condition: 'Stable',
                bloodType: 'B+', allergies: 'Aspirin',
                guardian: 'Carlos Reyes (Husband)', contact: '0918-555-6666',
                gyn_type: 'post_op', pain_score: 4, for_discharge: false,
            },
            vitals: { temperature: 37.2, heartRate: 82, bloodPressure: '130/85',
                      respiratoryRate: 18, oxygenSaturation: 98,
                      fetalHeartRate: null, contractions: null,
                      lastUpdated: '2026-02-28 15:00' },
            treatment: 'IV antibiotics, pain management, wound care',
            notes: [],
        },
        {
            id: 11, bedNumber: 'GYN-02', section: 'gynecology',
            status: 'occupied',
            patient: {
                name: 'Lilian Torres', age: 35, admissionDate: '2026-02-28',
                diagnosis: 'Ovarian Cyst — Medical Management', condition: 'Improving',
                bloodType: 'A+', allergies: 'None',
                guardian: 'Ramon Torres (Husband)', contact: '0919-777-8888',
                gyn_type: 'medical', pain_score: 2, for_discharge: true,
            },
            vitals: { temperature: 36.9, heartRate: 76, bloodPressure: '118/75',
                      respiratoryRate: 17, oxygenSaturation: 99,
                      fetalHeartRate: null, contractions: null,
                      lastUpdated: '2026-02-28 14:00' },
            treatment: 'Oral contraceptives, NSAIDs, ultrasound monitoring',
            notes: [],
        },
    ],
    nursery: [
        {
            id: 20, bedNumber: 'NUR-01', section: 'nursery',
            status: 'occupied',
            patient: {
                name: 'Baby Santos (F)', age: 0, admissionDate: '2026-02-27',
                diagnosis: 'Term newborn, NSD', condition: 'Stable',
                bloodType: 'Unknown', allergies: 'None',
                guardian: 'Maria Santos (Mother)', contact: '0917-333-4444',
                apgar: '9/10', birth_weight: 3.2, for_discharge: false,
            },
            vitals: { temperature: 36.7, heartRate: 148, bloodPressure: 'N/A',
                      respiratoryRate: 44, oxygenSaturation: 99,
                      fetalHeartRate: null, contractions: null,
                      lastUpdated: '2026-02-28 16:30' },
            treatment: 'Routine newborn care, breastfeeding initiation',
            notes: [],
        },
        {
            id: 21, bedNumber: 'NUR-02', section: 'nursery',
            status: 'observation',
            patient: {
                name: 'Baby Dela Cruz (M)', age: 0, admissionDate: '2026-02-28',
                diagnosis: 'Neonatal jaundice — phototherapy', condition: 'Observation',
                bloodType: 'Unknown', allergies: 'None',
                guardian: 'Rosario Dela Cruz (Mother)', contact: '0917-111-2222',
                apgar: '8/9', birth_weight: 3.5, for_discharge: false,
            },
            vitals: { temperature: 36.9, heartRate: 155, bloodPressure: 'N/A',
                      respiratoryRate: 46, oxygenSaturation: 98,
                      fetalHeartRate: null, contractions: null,
                      lastUpdated: '2026-02-28 18:00' },
            treatment: 'Phototherapy, blood bilirubin monitoring q6h',
            notes: [],
        },
    ],
};

// Pad with empty beds
[['maternity', 'MAT', 10], ['gynecology', 'GYN', 8], ['nursery', 'NUR', 6]].forEach(([sec, prefix, count]) => {
    const start = bedsData[sec].length + 1;
    for (let i = start; i <= count; i++) {
        bedsData[sec].push({ id: sec.charCodeAt(0) * 100 + i, bedNumber: `${prefix}-${String(i).padStart(2,'0')}`, section: sec, status: 'available', patient: null });
    }
});

let currentSection = 'maternity';

// ─── Init ─────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    renderSection('maternity');
    updateStats();
    setupListeners();
});

function setupListeners() {
    // Tabs
    document.querySelectorAll('.obgyn-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            const sec = this.dataset.section;
            document.querySelectorAll('.obgyn-tab').forEach(t => {
                t.classList.remove('text-pink-600', 'border-b-2', 'border-pink-600');
                t.classList.add('text-gray-500');
            });
            this.classList.add('text-pink-600', 'border-b-2', 'border-pink-600');
            this.classList.remove('text-gray-500');
            document.querySelectorAll('.obgyn-section').forEach(s => s.classList.add('hidden'));
            document.getElementById('section-' + sec).classList.remove('hidden');
            currentSection = sec;
        });
    });

    // Refresh
    document.getElementById('btnRefreshObgyn').addEventListener('click', function () {
        renderSection(currentSection);
        updateStats();
    });

    // Admit
    document.getElementById('btnAdmitObgyn').addEventListener('click', function () {
        alert('Connect to Admissions module to admit a new OB-GYN patient.');
    });

    // Close modals
    const allModals = ['patientDetailsModal','vitalsModal','progressNoteModal','deliveryModal','woundCareModal','feedingModal'];
    ['closeDetailsModal','closeVitalsModal','cancelVitals','closeProgressModal','cancelProgress',
     'closeDeliveryModal','cancelDelivery','closeWoundModal','cancelWound','closeFeedingModal','cancelFeeding'
    ].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('click', function () {
            allModals.forEach(m => document.getElementById(m).classList.add('hidden'));
        });
    });

    // Forms
    document.getElementById('vitalsForm').addEventListener('submit', handleVitalsSubmit);
    document.getElementById('progressNoteForm').addEventListener('submit', handleProgressNoteSubmit);
    document.getElementById('deliveryForm').addEventListener('submit', handleDeliverySubmit);
    document.getElementById('woundCareForm').addEventListener('submit', handleWoundCareSubmit);
    document.getElementById('feedingForm').addEventListener('submit', handleFeedingSubmit);
}

// ─── Rendering ────────────────────────────────────────────────────────────────
function renderSection(sec) {
    const containerMap = { maternity: 'maternityBeds', gynecology: 'gynecologyBeds', nursery: 'nurseryBeds' };
    const container = document.getElementById(containerMap[sec]);
    if (!container) return;
    container.innerHTML = (bedsData[sec] || []).map(bed => createBedCard(bed)).join('');
    if (sec === 'gynecology') updateGynStats();
    if (sec === 'nursery')    updateNurseryStats();
}

function createBedCard(bed) {
    const statusBorder = {
        labor:       'status-labor',
        postpartum:  'status-postpartum',
        occupied:    'status-occupied',
        available:   'status-available',
        cleaning:    'status-cleaning',
        observation: 'status-observation',
    };
    const borderClass = bed.patient && bed.status === 'labor' ? 'status-labor' :
                        bed.patient && bed.status === 'postpartum' ? 'status-postpartum' :
                        statusBorder[bed.status] || 'status-available';

    if (bed.status === 'available') {
        return `
            <div class="bed-card bg-white rounded-lg shadow p-4 ${borderClass}">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-lg font-bold text-gray-900">${bed.bedNumber}</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                </div>
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-bed text-3xl mb-2"></i>
                    <p class="text-sm">Bed Available</p>
                </div>
            </div>`;
    }

    if (bed.status === 'cleaning') {
        return `
            <div class="bed-card bg-white rounded-lg shadow p-4 ${borderClass}">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-lg font-bold text-gray-900">${bed.bedNumber}</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Cleaning</span>
                </div>
                <div class="text-center py-8 text-yellow-400">
                    <i class="fas fa-broom text-3xl mb-2"></i>
                    <p class="text-sm">Being Cleaned</p>
                </div>
            </div>`;
    }

    const p = bed.patient;
    const conditionColor = {
        'In Labor':   'bg-pink-100 text-pink-800',
        'Postpartum': 'bg-purple-100 text-purple-800',
        'Stable':     'bg-green-100 text-green-800',
        'Improving':  'bg-blue-100 text-blue-800',
        'Critical':   'bg-red-100 text-red-800',
    };
    const condClass = conditionColor[p.condition] || 'bg-gray-100 text-gray-800';

    // Section-specific quick-action buttons
    let extraActions = '';
    if (bed.section === 'maternity' && bed.status === 'labor') {
        extraActions = `<button onclick="event.stopPropagation(); openDeliveryModal(${bed.id})" class="flex-1 px-2 py-2 text-xs bg-pink-50 text-pink-700 rounded hover:bg-pink-100"><i class="fas fa-baby mr-1"></i>Deliver</button>`;
    } else if (bed.section === 'gynecology' && bed.status === 'occupied') {
        extraActions = `<button onclick="event.stopPropagation(); openWoundCareModal(${bed.id})" class="flex-1 px-2 py-2 text-xs bg-red-50 text-red-700 rounded hover:bg-red-100"><i class="fas fa-band-aid mr-1"></i>Wound</button>`;
    } else if (bed.section === 'nursery') {
        extraActions = `<button onclick="event.stopPropagation(); openFeedingModal(${bed.id})" class="flex-1 px-2 py-2 text-xs bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100"><i class="fas fa-baby-carriage mr-1"></i>Feed</button>`;
    }

    const extraVital = bed.vitals.fetalHeartRate ? `
        <div><span class="text-gray-500">FHR:</span><span class="font-semibold ml-1">${bed.vitals.fetalHeartRate} bpm</span></div>
        <div><span class="text-gray-500">Ctx:</span><span class="font-semibold ml-1">${bed.vitals.contractions || '—'}</span></div>` : '';

    // GYN extra: pain score + post-op day
    const gynExtra = (bed.section === 'gynecology' && bed.patient.pain_score != null) ? `
        <div class="col-span-2"><span class="text-gray-500">Pain Score:</span><span class="font-semibold ml-1 ${bed.patient.pain_score >= 7 ? 'text-red-600' : ''}">${ bed.patient.pain_score}/10</span></div>` : '';

    // Nursery extra: APGAR + birth weight
    const nurExtra = (bed.section === 'nursery' && bed.patient.apgar) ? `
        <div><span class="text-gray-500">APGAR:</span><span class="font-semibold ml-1">${bed.patient.apgar}</span></div>
        <div><span class="text-gray-500">Wt:</span><span class="font-semibold ml-1">${bed.patient.birth_weight || '—'} kg</span></div>` : '';

    return `
        <div class="bed-card bg-white rounded-lg shadow p-4 ${borderClass} cursor-pointer" onclick="showPatientDetails(${bed.id})">
            <div class="flex items-center justify-between mb-3">
                <span class="text-lg font-bold text-gray-900">${bed.bedNumber}</span>
                <span class="px-2 py-1 text-xs font-semibold rounded-full ${condClass}">${p.condition}</span>
            </div>
            <div class="mb-3">
                <h4 class="font-semibold text-gray-900">${p.name}</h4>
                <p class="text-sm text-gray-600">${p.age} y/o${p.gest_age ? ' &bull; ' + p.gest_age : ''}</p>
                <p class="text-sm text-gray-600 mt-1"><i class="fas fa-stethoscope mr-1"></i>${p.diagnosis}</p>
            </div>
            <div class="border-t pt-3 mb-3">
                <p class="text-xs text-gray-500 mb-2">Vitals (${(bed.vitals.lastUpdated||'').split(' ')[1] || '—'})</p>
                <div class="grid grid-cols-2 gap-1 text-xs">
                    <div><span class="text-gray-500">Temp:</span><span class="font-semibold ml-1">${bed.vitals.temperature}°C</span></div>
                    <div><span class="text-gray-500">HR:</span><span class="font-semibold ml-1">${bed.vitals.heartRate} bpm</span></div>
                    <div><span class="text-gray-500">BP:</span><span class="font-semibold ml-1">${bed.vitals.bloodPressure}</span></div>
                    <div><span class="text-gray-500">SpO2:</span><span class="font-semibold ml-1">${bed.vitals.oxygenSaturation}%</span></div>
                    ${extraVital}${gynExtra}${nurExtra}
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="event.stopPropagation(); openVitalsModal(${bed.id})" class="flex-1 px-2 py-2 text-xs bg-blue-50 text-blue-600 rounded hover:bg-blue-100">
                    <i class="fas fa-heartbeat mr-1"></i>Vitals
                </button>
                <button onclick="event.stopPropagation(); openProgressModal(${bed.id})" class="flex-1 px-2 py-2 text-xs bg-green-50 text-green-600 rounded hover:bg-green-100">
                    <i class="fas fa-notes-medical mr-1"></i>Note
                </button>
                ${extraActions}
            </div>
        </div>`;
}

// ─── Statistics ───────────────────────────────────────────────────────────────
function updateStats() {
    let total = 0, labor = 0, postpartum = 0, available = 0;
    Object.values(bedsData).forEach(beds => {
        total += beds.length;
        beds.forEach(b => {
            if (b.status === 'labor') labor++;
            else if (b.status === 'postpartum') postpartum++;
            else if (b.status === 'available') available++;
        });
    });
    document.getElementById('statTotal').textContent      = total;
    document.getElementById('statLabor').textContent      = labor;
    document.getElementById('statPostpartum').textContent = postpartum;
    document.getElementById('statAvailable').textContent  = available;
}

function updateGynStats() {
    let postOp = 0, medical = 0, available = 0, discharge = 0;
    bedsData.gynecology.forEach(b => {
        if (b.status === 'available') available++;
        else if (b.patient) {
            if (b.patient.gyn_type === 'medical') medical++;
            else postOp++;
            if (b.patient.for_discharge) discharge++;
        }
    });
    document.getElementById('gynStatPostOp').textContent   = postOp;
    document.getElementById('gynStatMedical').textContent  = medical;
    document.getElementById('gynStatAvailable').textContent = available;
    document.getElementById('gynStatDischarge').textContent = discharge;
}

function updateNurseryStats() {
    let total = 0, obs = 0, stable = 0, discharge = 0;
    bedsData.nursery.forEach(b => {
        if (b.status !== 'available') {
            total++;
            if (b.patient && b.patient.condition === 'Observation') obs++;
            else if (b.patient && b.patient.condition === 'Stable') stable++;
            if (b.patient && b.patient.for_discharge) discharge++;
        }
    });
    document.getElementById('nurStatTotal').textContent    = total;
    document.getElementById('nurStatObs').textContent      = obs;
    document.getElementById('nurStatStable').textContent   = stable;
    document.getElementById('nurStatDischarge').textContent = discharge;
}

// ─── Patient Details ──────────────────────────────────────────────────────────
function showPatientDetails(bedId) {
    const bed = findBedById(bedId);
    if (!bed || !bed.patient) return;
    const p = bed.patient;
    const v = bed.vitals;

    const maternityExtra = (bed.section === 'maternity' && v.fetalHeartRate) ? `
        <div class="bg-pink-50 p-4 rounded-lg text-center">
            <i class="fas fa-baby text-pink-600 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600">Fetal HR</p>
            <p class="text-xl font-bold text-gray-900">${v.fetalHeartRate} bpm</p>
        </div>
        <div class="bg-pink-50 p-4 rounded-lg text-center">
            <i class="fas fa-stopwatch text-pink-600 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600">Contractions</p>
            <p class="text-xl font-bold text-gray-900">${v.contractions || '—'}</p>
        </div>` : '';

    const deliverBtn = bed.status === 'labor' ? `
        <button onclick="openDeliveryModal(${bed.id}); document.getElementById('patientDetailsModal').classList.add('hidden');"
            class="flex-1 px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
            <i class="fas fa-baby mr-2"></i>Record Delivery
        </button>` : '';

    document.getElementById('patientDetailsContent').innerHTML = `
        <div class="space-y-6">
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center"><i class="fas fa-user-circle mr-2 text-pink-600"></i>Patient Information</h4>
                <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg text-sm">
                    <div><span class="text-gray-500">Name:</span> <span class="font-semibold">${p.name}</span></div>
                    <div><span class="text-gray-500">Age:</span> <span class="font-semibold">${p.age} years</span></div>
                    <div><span class="text-gray-500">Bed:</span> <span class="font-semibold">${bed.bedNumber}</span></div>
                    <div><span class="text-gray-500">Blood Type:</span> <span class="font-semibold">${p.bloodType}</span></div>
                    <div><span class="text-gray-500">Admitted:</span> <span class="font-semibold">${p.admissionDate}</span></div>
                    ${p.gest_age ? `<div><span class="text-gray-500">Gest. Age:</span> <span class="font-semibold">${p.gest_age}</span></div>` : ''}
                    <div class="col-span-2"><span class="text-gray-500">Diagnosis:</span> <span class="font-semibold">${p.diagnosis}</span></div>
                    <div class="col-span-2"><span class="text-gray-500">Allergies:</span> <span class="font-semibold ${p.allergies !== 'None' ? 'text-red-600' : ''}">${p.allergies}</span></div>
                    <div class="col-span-2"><span class="text-gray-500">Guardian:</span> <span class="font-semibold">${p.guardian}</span></div>
                    <div class="col-span-2"><span class="text-gray-500">Contact:</span> <span class="font-semibold">${p.contact}</span></div>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center"><i class="fas fa-heartbeat mr-2 text-pink-600"></i>Current Vital Signs</h4>
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-blue-50 p-4 rounded-lg text-center"><i class="fas fa-thermometer-half text-blue-600 text-2xl mb-1"></i><p class="text-sm text-gray-600">Temp</p><p class="text-xl font-bold">${v.temperature}°C</p></div>
                    <div class="bg-red-50 p-4 rounded-lg text-center"><i class="fas fa-heartbeat text-red-600 text-2xl mb-1"></i><p class="text-sm text-gray-600">Heart Rate</p><p class="text-xl font-bold">${v.heartRate} bpm</p></div>
                    <div class="bg-purple-50 p-4 rounded-lg text-center"><i class="fas fa-tint text-purple-600 text-2xl mb-1"></i><p class="text-sm text-gray-600">BP</p><p class="text-xl font-bold">${v.bloodPressure}</p></div>
                    <div class="bg-green-50 p-4 rounded-lg text-center"><i class="fas fa-lungs text-green-600 text-2xl mb-1"></i><p class="text-sm text-gray-600">Resp Rate</p><p class="text-xl font-bold">${v.respiratoryRate}/min</p></div>
                    <div class="bg-cyan-50 p-4 rounded-lg text-center"><i class="fas fa-wind text-cyan-600 text-2xl mb-1"></i><p class="text-sm text-gray-600">SpO2</p><p class="text-xl font-bold">${v.oxygenSaturation}%</p></div>
                    ${maternityExtra}
                </div>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center"><i class="fas fa-prescription mr-2 text-green-600"></i>Treatment Plan</h4>
                <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700">${bed.treatment}</div>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center"><i class="fas fa-clipboard-list mr-2 text-yellow-600"></i>Progress Notes</h4>
                <div class="bg-gray-50 p-4 rounded-lg text-sm">
                    ${bed.notes && bed.notes.length ? bed.notes.map(n => `
                        <div class="mb-3 pb-3 border-b last:border-b-0">
                            <div class="flex justify-between mb-1">
                                <span class="font-semibold text-gray-900">${n.type}</span>
                                <span class="text-xs text-gray-500">${n.timestamp}</span>
                            </div>
                            <p class="text-gray-700">${n.text}</p>
                            <p class="text-xs text-gray-500 mt-1">By: ${n.recordedBy}</p>
                        </div>`).join('') :
                        '<p class="text-gray-500 text-center py-4">No progress notes yet.</p>'
                    }
                </div>
            </div>
            <div class="flex gap-3 pt-4 border-t">
                <button onclick="openVitalsModal(${bed.id}); document.getElementById('patientDetailsModal').classList.add('hidden');"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-heartbeat mr-2"></i>Update Vitals
                </button>
                <button onclick="openProgressModal(${bed.id}); document.getElementById('patientDetailsModal').classList.add('hidden');"
                    class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-notes-medical mr-2"></i>Add Note
                </button>
                ${deliverBtn}
            </div>
        </div>`;
    document.getElementById('patientDetailsModal').classList.remove('hidden');
}

// ─── Modals ───────────────────────────────────────────────────────────────────
function openVitalsModal(bedId) {
    const bed = findBedById(bedId);
    if (!bed) return;
    const form = document.getElementById('vitalsForm');
    form.querySelector('[name="bed_id"]').value = bedId;
    if (bed.vitals) {
        form.querySelector('[name="temperature"]').value    = bed.vitals.temperature || '';
        form.querySelector('[name="heart_rate"]').value     = bed.vitals.heartRate || '';
        form.querySelector('[name="blood_pressure"]').value = bed.vitals.bloodPressure || '';
        form.querySelector('[name="respiratory_rate"]').value = bed.vitals.respiratoryRate || '';
        form.querySelector('[name="oxygen_saturation"]').value = bed.vitals.oxygenSaturation || '';
        form.querySelector('[name="fetal_heart_rate"]').value = bed.vitals.fetalHeartRate || '';
        form.querySelector('[name="contractions"]').value   = bed.vitals.contractions || '';
    }
    // Show/hide fetal fields based on section
    const showFetal = bed.section === 'maternity' && bed.status === 'labor';
    document.getElementById('fetalHrField').classList.toggle('hidden', !showFetal);
    document.getElementById('contractionField').classList.toggle('hidden', !showFetal);

    document.getElementById('vitalsModal').classList.remove('hidden');
}

function openProgressModal(bedId) {
    document.querySelector('#progressNoteForm [name="bed_id"]').value = bedId;
    document.getElementById('progressNoteModal').classList.remove('hidden');
}

function openWoundCareModal(bedId) {
    const form = document.getElementById('woundCareForm');
    form.querySelector('[name="bed_id"]').value = bedId;
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    form.querySelector('[name="wound_datetime"]').value = now.toISOString().slice(0,16);
    document.getElementById('woundCareModal').classList.remove('hidden');
}

function openFeedingModal(bedId) {
    const form = document.getElementById('feedingForm');
    form.querySelector('[name="bed_id"]').value = bedId;
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    form.querySelector('[name="feeding_time"]').value = now.toISOString().slice(0,16);
    document.getElementById('feedingModal').classList.remove('hidden');
}

function openDeliveryModal(bedId) {
    document.querySelector('#deliveryForm [name="bed_id"]').value = bedId;
    // Default delivery time to now
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.querySelector('#deliveryForm [name="delivery_time"]').value = now.toISOString().slice(0,16);
    document.getElementById('deliveryModal').classList.remove('hidden');
}

// ─── Form handlers ────────────────────────────────────────────────────────────
function handleVitalsSubmit(e) {
    e.preventDefault();
    const fd = new FormData(e.target);
    const bedId = parseInt(fd.get('bed_id'));
    const bed = findBedById(bedId);
    if (bed && bed.vitals) {
        bed.vitals.temperature       = parseFloat(fd.get('temperature')) || bed.vitals.temperature;
        bed.vitals.heartRate         = parseInt(fd.get('heart_rate'))    || bed.vitals.heartRate;
        bed.vitals.bloodPressure     = fd.get('blood_pressure')          || bed.vitals.bloodPressure;
        bed.vitals.respiratoryRate   = parseInt(fd.get('respiratory_rate')) || bed.vitals.respiratoryRate;
        bed.vitals.oxygenSaturation  = parseInt(fd.get('oxygen_saturation')) || bed.vitals.oxygenSaturation;
        bed.vitals.fetalHeartRate    = fd.get('fetal_heart_rate') ? parseInt(fd.get('fetal_heart_rate')) : bed.vitals.fetalHeartRate;
        bed.vitals.contractions      = fd.get('contractions') || bed.vitals.contractions;
        bed.vitals.lastUpdated       = new Date().toLocaleString('sv-SE').replace('T', ' ').slice(0,16);
    }
    alert('Vital signs updated successfully!');
    document.getElementById('vitalsModal').classList.add('hidden');
    e.target.reset();
    renderSection(bed ? bed.section : currentSection);
}

function handleProgressNoteSubmit(e) {
    e.preventDefault();
    const fd = new FormData(e.target);
    const bedId = parseInt(fd.get('bed_id'));
    const bed = findBedById(bedId);
    if (bed) {
        bed.notes.push({
            type: fd.get('note_type'),
            text: fd.get('note_text'),
            recordedBy: fd.get('recorded_by') || 'Staff',
            timestamp: new Date().toLocaleString('en-PH'),
        });
    }
    alert('Progress note saved successfully!');
    document.getElementById('progressNoteModal').classList.add('hidden');
    e.target.reset();
}

function handleDeliverySubmit(e) {
    e.preventDefault();
    const fd = new FormData(e.target);
    const bedId = parseInt(fd.get('bed_id'));
    const bed = findBedById(bedId);
    if (bed) {
        // Move mother to postpartum
        bed.status = 'postpartum';
        if (bed.patient) bed.patient.condition = 'Postpartum';
        bed.notes.push({
            type: 'Delivery Note',
            text: `${fd.get('delivery_type')} delivered at ${fd.get('delivery_time')}. ` +
                  `Newborn: ${fd.get('newborn_sex') || 'Unknown sex'}, ${fd.get('birth_weight') || '?'} kg. ` +
                  `APGAR: ${fd.get('apgar_1min') || '?'}/${fd.get('apgar_5min') || '?'}. ` +
                  `${fd.get('delivery_notes') || ''}`,
            recordedBy: fd.get('attending_physician') || 'Physician',
            timestamp: new Date().toLocaleString('en-PH'),
        });
        // Update delivery count stat
        const cur = parseInt(document.getElementById('statDeliveries').textContent) || 0;
        document.getElementById('statDeliveries').textContent = cur + 1;
    }
    alert('Delivery recorded successfully! Mother moved to Postpartum.');
    document.getElementById('deliveryModal').classList.add('hidden');
    e.target.reset();
    renderSection('maternity');
    updateStats();
}

function handleWoundCareSubmit(e) {
    e.preventDefault();
    const fd = new FormData(e.target);
    const bed = findBedById(parseInt(fd.get('bed_id')));
    if (bed) {
        const condLabel = { clean: 'Clean & Dry', healing: 'Healing Well', mild_discharge: 'Mild Discharge', infected: 'Signs of Infection', dehisced: 'Dehisced' };
        bed.notes.push({
            type: 'Wound Care',
            text: `Site: ${fd.get('wound_site') || 'N/A'}. Condition: ${condLabel[fd.get('wound_condition')] || fd.get('wound_condition')}. ` +
                  `Pain: ${fd.get('pain_score') || '—'}/10. Dressing: ${fd.get('dressing_type') || 'N/A'}. ` +
                  `${fd.get('wound_remarks') || ''}`,
            recordedBy: fd.get('done_by') || 'Staff',
            timestamp: new Date().toLocaleString('en-PH'),
        });
        if (bed.patient) bed.patient.pain_score = parseInt(fd.get('pain_score')) || bed.patient.pain_score;
    }
    alert('Wound care record saved successfully!');
    document.getElementById('woundCareModal').classList.add('hidden');
    e.target.reset();
    if (bed) renderSection(bed.section);
}

function handleFeedingSubmit(e) {
    e.preventDefault();
    const fd = new FormData(e.target);
    const bed = findBedById(parseInt(fd.get('bed_id')));
    if (bed) {
        const tolLabel = { good: 'Good – No regurgitation', mild_regurg: 'Mild Regurgitation', vomiting: 'Vomiting', refused: 'Refused' };
        bed.notes.push({
            type: 'Feeding Record',
            text: `Type: ${fd.get('feeding_type')}. Amount: ${fd.get('feeding_amount') || '—'} mL. ` +
                  `Duration: ${fd.get('feeding_duration') || '—'} min. Tolerance: ${tolLabel[fd.get('feeding_tolerance')] || fd.get('feeding_tolerance')}. ` +
                  `${fd.get('feeding_notes') || ''}`,
            recordedBy: fd.get('recorded_by') || 'Staff',
            timestamp: new Date().toLocaleString('en-PH'),
        });
    }
    alert('Feeding record saved successfully!');
    document.getElementById('feedingModal').classList.add('hidden');
    e.target.reset();
}

// ─── Helpers ──────────────────────────────────────────────────────────────────
function findBedById(id) {
    for (const sec of Object.values(bedsData)) {
        const b = sec.find(x => x.id === id);
        if (b) return b;
    }
    return null;
}
</script>
</body>
</html>
