<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>

<html lang="en">

<head>

    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PhilHealth e-Claims Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {

            font-family: 'Inter', sans-serif;

            background-color: #f3f4f6;

        }

        .chart-container {

            position: relative;

            width: 100%;

            height: 300px;

        }

        .bg-green-gradient {

            background-image: linear-gradient(135deg, #ffffff, #7bc9a1); /* Soft blue-green to light green gradient */

        }

        .bg-blue-gradient {

            background-image: linear-gradient(155deg, #ffffff,#cfe1f0, #0000cd); /* Soft blue-green to light green gradient */

        }

        .no-scrollbar::-webkit-scrollbar {

        display: none;

        }



        .no-scrollbar {

        -ms-overflow-style: none;  /* IE and Edge */

        scrollbar-width: none;     /* Firefox */

        }

    </style>

</head>

<body class="bg-gray-100">

    <div class="flex h-screen">

        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <!-- Sidebar -->

        <?php if (false): ?>

         <aside id="sidebar" class="fixed inset-y-0 left-0 bg-green-gradient shadow-xl w-64 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40">

            <div class="flex flex-col h-full">

                <!-- Logo -->

                 <div class="flex items-center justify-center  p-5 h-20 border-b border-gray-200">

                    <img src="resources/logo.png" alt="Logo" class="h-16">

                    <span class="ml-3 text-l font-bold text-gray-800">Hospital</span>

                </div>



                <!-- Navigation -->

                <nav class="flex-1 p-4 overflow-y-auto no-scrollbar">

                    <ul class="space-y-1">

                        <li>

                            <a href="index.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-home w-6 text-center"></i>

                                <span>Dashboard</span>

                            </a>

                        </li>

                        <li>

                            <a href="patients.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-user-injured w-6 text-center"></i>

                                <span>Patients</span>

                            </a>

                        </li>

                        <li>

                            <a href="out-patient-department.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-hospital-user w-6 text-center"></i>

                                <span>OPD</span>

                            </a>

                        </li>

                        <li>

                            <a href="dialysis.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-sync-alt w-6 text-center"></i>

                                <span>Dialysis</span>

                            </a>

                        </li>

                        <li>

                            <a href="cashier.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-cash-register w-6 text-center"></i>

                                <span>Cashier</span>

                            </a>

                        </li>

                        <li>

                            <a href="pharmacy.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-pills w-6 text-center"></i>

                                <span>Pharmacy</span>

                            </a>

                        </li>

                        <li>

                            <a href="laboratory.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-flask w-6 text-center"></i>

                                <span>Laboratory</span>

                            </a>

                        </li>

                        <li>

                            <a href="inventory.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-boxes w-6 text-center"></i>

                                <span>Inventory</span>

                            </a>

                        </li>

                        <li>

                            <a href="kitchen.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-utensils w-6 text-center"></i>

                                <span>Kitchen</span>

                            </a>

                        </li>

                        <li>

                            <a href="payroll.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-money-check-alt w-6 text-center"></i>

                                <span>Payroll</span>

                            </a>

                        </li>

                        <li>

                            <a href="chat.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-comments w-6 text-center"></i>

                                <span>Chat Messages</span>

                            </a>

                        </li>

                        <li>

                            <a href="employees.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">

                                <i class="fas fa-user-friends w-6 text-center"></i>

                                <span>Employees</span>

                            </a>

                        </li>

                        <li>

                            <a href="philhealth-claims.php" class="flex items-center gap-x-4 px-4 py-2 bg-teal-600 text-white rounded-lg shadow-md">

                                <i class="fas fa-file-medical-alt w-6 text-center"></i>

                                <span>PhilHealth Claims</span>

                            </a>

                        </li>

                    </ul>

                </nav>

                <!-- User Profile -->

                <div class="mt-auto p-4 border-t border-gray-200">

                    <div class="flex items-center gap-x-4">

                        <img src="resources/doctor.jpg" alt="User" class="w-10 h-10 rounded-full object-cover">

                        <div>

                            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars((string)($authUser['full_name'] ?? ($authUser['username'] ?? 'User')), ENT_QUOTES); ?></p>

                            <p class="text-sm text-gray-500">User</p>

                        </div>

                        <button class="ml-auto text-gray-500 hover:text-red-600" title="Logout">

                            <i class="fas fa-sign-out-alt"></i>

                        </button>

                    </div>

                </div>

            </div>

        </aside>

        <?php endif; ?>



        <!-- Main Content -->

        <main class="ml-16 lg:ml-80 flex-1 overflow-auto relative">





            <!-- Header -->

             <div class="bg-emerald-700 shadow-sm p-4 px-6 sticky top-0 z-30 flex items-center justify-between text-white">

                <div class="flex items-center space-x-4">

                    <h1 class="text-xl md:text-2xl font-semibold">PhilHealth Claims</h1>

                </div>

                <div class="flex items-center space-x-2 md:space-x-4">

                    <button id="newClaimBtn" class="px-4 py-2 bg-teal-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700">

                        <i class="fas fa-plus"></i>

                        <span class="hidden sm:inline">New Claim</span>

                    </button>

                    <button id="cancelClaimRegistrationBtn" class="hidden px-4 py-2 bg-red-600 text-white rounded-lg flex items-center space-x-2 hover:bg-red-700">

                        <i class="fas fa-ban"></i>

                        <span class="hidden sm:inline">Cancel Claim Registration</span>

                    </button>

                    <div class="relative hidden md:block">

                        <button class="p-2 rounded-full hover:bg-emerald-600 relative">

                            <i class="fas fa-bell text-white"></i>

                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>

                        </button>

                    </div>

                    <?php $profileDropdownTheme = 'dark'; include __DIR__ . '/includes/profile-dropdown.php'; ?>

                </div>

            </div>

                

            <?php if (false): ?>

                                <!-- Section 4: Financials -->

                                <div class="form-section hidden" data-section="4">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 4 of 5: Financials</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Total Claim Amount</label>

                                            <input type="number" name="claimAmount" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Enter amount">

                                        </div>

                                         <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Professional Fee</label>

                                            <input type="number" name="profFee" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="PF Amount" step="0.01">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Room & Board</label>

                                            <input type="number" name="roomCharges" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Amount" step="0.01">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Drugs & Medicines</label>

                                            <input type="number" name="drugCharges" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Amount" step="0.01">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Laboratory Fees</label>

                                            <input type="number" name="labCharges" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Amount" step="0.01">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">PhilHealth Benefit</label>

                                            <input type="number" name="philhealthBenefit" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Total Benefit" step="0.01">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Patient Share</label>

                                            <input type="number" name="patientShare" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Out-of-pocket">

                                        </div>

                                    </div>

                                </div>

                            

                                <!-- Section 5: Initial Summary -->

                                <div class="form-section hidden" data-section="5">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 5 of 5: Summary of Basic Information</h5>

                                    <div id="step1-review-content" class="bg-gray-50 p-6 rounded-lg space-y-4 max-h-[50vh] overflow-y-auto">

                                        <!-- Summary for Step 1 will be populated here -->

                                    </div>

                                </div>

                            

                                <!-- Section Navigation -->

                                <div class="mt-6 flex justify-end space-x-3">

                                    <button type="button" id="fillTestBtn" onclick="aiFillNewClaim()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">

                                        Fill Test Info

                                    </button>

                                    <button type="button" id="prevSectionBtn" onclick="prevSection(1)" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all hidden">

                                        Previous Section

                                    </button>

                                    <button type="button" id="nextSectionBtn" onclick="nextSection(1, 5)" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all">

                                        Next Section

                                    </button>

                                </div>

                            </div>



                            <!-- Step 2: Confinement Details -->

                            <div class="form-step hidden" data-step="2">

                                <h4 class="text-lg font-semibold text-gray-700 mb-2">Form2: Patient Confinement Details</h4>

                                <!-- Section Progress Bar -->

                                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-6">

                                    <div id="step2-progress-bar" class="bg-blue-600 h-1.5 rounded-full transition-all duration-500" style="width: 0%"></div>

                                </div>



                                <div class="form-section" data-section="1">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 1 of 5: Confinement Information</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Admission Date</label>

                                            <input type="date" name="admissionDate" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Admission Time</label>

                                            <input type="time" name="admissionTime" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Room Type</label>

                                            <select name="roomType" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900">

                                                <option value="">Select room type</option>

                                                <option value="ward">Ward</option>

                                                <option value="semi-private">Semi-Private</option>

                                                <option value="private">Private</option>

                                                <option value="suite">Suite</option>

                                            </select>

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Discharge Date</label>

                                            <input type="date" name="dischargeDate" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Discharge Time</label>

                                            <input type="time" name="dischargeTime" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900">

                                        </div>

                                        <div class="lg:col-span-1">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Hospital Accreditation No.</label>

                                            <input type="text" name="hospitalAccreditation" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="HCI Accreditation Number">

                                        </div>

                                        <div class="lg:col-span-2">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Attending Physician</label>

                                            <input type="text" name="physician" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Doctor's full name">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Physician Accreditation No.</label>

                                            <input type="text" name="physicianAccreditation" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Professional Accreditation Number">

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="2">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 2 of 5: Admitting Diagnosis & History</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                        <div class="md:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Chief Complaint</label>

                                            <textarea name="chiefComplaint" rows="2" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Reason for seeking consultation"></textarea>

                                        </div>

                                        <div class="md:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">History of Present Illness</label>

                                            <textarea name="historyPresentIllness" rows="4" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Chronological account of the patient's chief complaint"></textarea>

                                        </div>

                                        <div class="md:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Pertinent Past Medical History</label>

                                            <input type="text" name="pastMedicalHistory" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., Hypertension, Diabetes Mellitus">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">OB/GYN History (if female)</label>

                                            <input type="text" name="obgynHistory" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="G-P-(TPAL)">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Pertinent Signs & Symptoms</label>

                                            <input type="text" name="signsSymptoms" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., Fever, Cough, Headache">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Referring Physician</label>

                                            <input type="text" name="referringPhysician" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Name of doctor, if any">

                                        </div>

                                        <div class="md:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Allergies (if any)</label>

                                            <input type="text" name="allergies" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., Penicillin, Seafood, or NKA for No Known Allergies">

                                        </div>

                                        <div class="md:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Admitting Impression/Diagnosis</label>

                                            <input type="text" name="admittingDiagnosis" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Initial diagnosis upon admission">

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="3">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 3 of 5: Physical Examination</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Blood Pressure (BP)</label>

                                            <input type="text" name="pe_bp" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., 120/80 mmHg">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Heart Rate (HR)</label>

                                            <input type="text" name="pe_hr" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., 80 bpm">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Respiratory Rate (RR)</label>

                                            <input type="text" name="pe_rr" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., 18 cpm">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Temperature</label>

                                            <input type="text" name="pe_temp" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., 37.0 Â°C">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Weight (kg)</label>

                                            <input type="number" name="pe_weight" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., 65">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Height (cm)</label>

                                            <input type="number" name="pe_height" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., 170">

                                        </div>

                                        <div class="md:col-span-2 lg:col-span-4">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">General Survey</label>

                                            <input type="text" name="pe_general" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., Awake, alert, not in distress">

                                        </div>

                                        <div class="md:col-span-2 lg:col-span-4">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">HEENT (Head, Eyes, Ears, Nose, Throat)</label>

                                            <input type="text" name="pe_heent" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., Anicteric sclerae, pink conjunctivae">

                                        </div>

                                        <div class="md:col-span-2 lg:col-span-4">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Chest/Lungs</label>

                                            <input type="text" name="pe_lungs" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., Clear breath sounds, no rales">

                                        </div>

                                        <div class="md:col-span-2 lg:col-span-4">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Cardiovascular (CVS)</label>

                                            <input type="text" name="pe_cvs" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., Adynamic precordium, distinct heart sounds">

                                        </div>

                                        <div class="md:col-span-2 lg:col-span-4">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Abdomen</label>

                                            <input type="text" name="pe_abdomen" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., Soft, non-tender, normoactive bowel sounds">

                                        </div>

                                        <div class="md:col-span-2 lg:col-span-4">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Extremities</label>

                                            <input type="text" name="pe_extremities" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., No edema, full pulses">

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="4">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 4 of 5: Course in the Ward</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                        <div class="md:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Brief Course in the Ward</label>

                                            <textarea name="courseInWard" rows="6" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Summarize the patient's hospital stay, including treatments, responses, and significant events."></textarea>

                                        </div>

                                        <div class="md:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Medications Administered</label>

                                            <textarea name="medicationsAdministered" rows="4" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="List key medications, dosages, and frequencies."></textarea>

                                        </div>

                                        <div class="md:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Procedures Done (if any)</label>

                                            <input type="text" name="proceduresDone" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., IV line insertion, blood transfusion">

                                        </div>

                                        <div class="md:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Discharge Instructions</label>

                                            <textarea name="dischargeInstructions" rows="4" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., Home medications, diet, follow-up schedule"></textarea>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="5">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 5 of 5: Summary of Confinement Details</h5>

                                    <div id="step2-review-content" class="bg-gray-50 p-6 rounded-lg space-y-4 max-h-[50vh] overflow-y-auto">

                                        <!-- Summary for Step 2 will be populated here -->

                                    </div>

                                </div>



                                <!-- Section Navigation -->

                                <div class="mt-6 flex justify-end space-x-3">

                                    <button type="button" id="prevSectionBtn" onclick="prevSection(2)" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all hidden">

                                        Previous Section

                                    </button>

                                    <button type="button" id="nextSectionBtn" onclick="nextSection(2, 5)" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all">

                                        Next Section

                                    </button>

                                </div>

                            </div>



                            <!-- Step 3: Diagnosis & Procedures -->

                            <div class="form-step hidden" data-step="3">

                                <h4 class="text-lg font-semibold text-gray-700 mb-2">Form3: Diagnosis and Procedures</h4>

                                <!-- Section Progress Bar -->

                                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-6">

                                    <div id="step3-progress-bar" class="bg-blue-600 h-1.5 rounded-full transition-all duration-500" style="width: 0%"></div>

                                </div>



                                <div class="form-section" data-section="1">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 1 of 5: Diagnosis Information</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Primary Diagnosis (ICD-10)</label>

                                            <input type="text" name="primaryDiagnosis" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., A09.0">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Procedure (RVS Code)</label>

                                            <input type="text" name="procedure" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="e.g., 99213">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Secondary Diagnosis (ICD-10)</label>

                                            <input type="text" name="secondaryDiagnosis" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Optional">

                                        </div>

                                        <div class="md:col-span-2 lg:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Brief Clinical History</label>

                                            <textarea name="clinicalHistory" rows="3" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Provide a brief summary of the patient's clinical history..."></textarea>

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Discharge Disposition</label>

                                            <select name="disposition" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900">

                                                <option value="">Select disposition</option>

                                                <option value="improved">Improved</option>

                                                <option value="transferred">Transferred</option>

                                                <option value="hama">Home Against Medical Advice (HAMA)</option>

                                                <option value="absconded">Absconded</option>

                                                <option value="expired">Expired</option>

                                            </select>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="2">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 2 of 5: Surgical/Procedure Details (if applicable)</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Date of Procedure</label>

                                            <input type="date" name="proc_date" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900">

                                        </div>

                                        <div class="md:col-span-2">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Name of Surgeon</label>

                                            <input type="text" name="proc_surgeon" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Surgeon's full name">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Surgeon's Accreditation No.</label>

                                            <input type="text" name="proc_surgeon_acc" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900">

                                        </div>

                                        <div class="md:col-span-2">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Name of Anesthesiologist</label>

                                            <input type="text" name="proc_anesthesiologist" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Anesthesiologist's full name">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Anesthesiologist's Acc. No.</label>

                                            <input type="text" name="proc_anes_acc" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Type of Anesthesia</label>

                                            <select name="proc_anesthesia_type" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900">

                                                <option value="">Select type</option>

                                                <option value="general">General</option>

                                                <option value="spinal">Spinal</option>

                                                <option value="epidural">Epidural</option>

                                                <option value="local">Local</option>

                                            </select>

                                        </div>

                                        <div class="md:col-span-3">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Operative/Surgical Findings</label>

                                            <textarea name="proc_findings" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-gray-900" placeholder="Describe findings during the operation"></textarea>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="3">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 3 of 5: Maternity Care Details (if applicable)</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Gravida</label><input type="number" name="mat_gravida" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="G"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Para</label><input type="number" name="mat_para" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="P"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Full Term</label><input type="number" name="mat_t" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="T"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Preterm</label><input type="number" name="mat_p" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="P"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Abortion</label><input type="number" name="mat_a" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="A"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Living</label><input type="number" name="mat_l" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="L"></div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Last Menstrual Period (LMP)</label>

                                            <input type="date" name="mat_lmp" class="w-full px-4 py-3 rounded-lg border border-gray-200">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Est. Date of Confinement (EDC)</label>

                                            <input type="date" name="mat_edc" class="w-full px-4 py-3 rounded-lg border border-gray-200">

                                        </div>

                                        <div class="md:col-span-2">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Type of Delivery</label>

                                            <select name="mat_delivery_type" class="w-full px-4 py-3 rounded-lg border border-gray-200">

                                                <option value="">Select type</option>

                                                <option value="nsd">Normal Spontaneous Delivery (NSD)</option>

                                                <option value="cs">Cesarean Section (CS)</option>

                                                <option value="forceps">Forceps-assisted</option>

                                                <option value="vacuum">Vacuum-assisted</option>

                                            </select>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="4">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 4 of 5: Newborn Care Details (if applicable)</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">APGAR Score (1 min)</label><input type="number" name="nb_apgar1" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="Score"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">APGAR Score (5 min)</label><input type="number" name="nb_apgar5" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="Score"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Birth Weight (grams)</label><input type="number" name="nb_weight" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="e.g., 3000"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Birth Length (cm)</label><input type="number" name="nb_length" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="e.g., 50"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Head Circumference (cm)</label><input type="number" name="nb_head" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="e.g., 35"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Chest Circumference (cm)</label><input type="number" name="nb_chest" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="e.g., 33"></div>

                                        <div><label class="block text-sm font-medium text-gray-600 mb-2">Abdominal Circumference (cm)</label><input type="number" name="nb_abdomen" class="w-full px-4 py-3 rounded-lg border border-gray-200" placeholder="e.g., 31"></div>

                                        <div class="md:col-span-2">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Newborn Screening Test Done?</label>

                                            <select name="nb_screening" class="w-full px-4 py-3 rounded-lg border border-gray-200">

                                                <option value="">Select</option>

                                                <option value="yes">Yes</option>

                                                <option value="no">No</option>

                                                <option value="declined">Declined</option>

                                            </select>

                                        </div>

                                        <div class="md:col-span-2">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">BCG Vaccination Given?</label>

                                            <select name="nb_bcg" class="w-full px-4 py-3 rounded-lg border border-gray-200"><option value="">Select</option><option value="yes">Yes</option><option value="no">No</option></select>

                                        </div>

                                        <div class="md:col-span-2">

                                            <label class="block text-sm font-medium text-gray-600 mb-2">Hepatitis B Vaccination Given?</label>

                                            <select name="nb_hepb" class="w-full px-4 py-3 rounded-lg border border-gray-200"><option value="">Select</option><option value="yes">Yes</option><option value="no">No</option></select>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="5">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 5 of 5: Summary of Diagnosis</h5>

                                    <div id="step3-review-content" class="bg-gray-50 p-6 rounded-lg space-y-4 max-h-[50vh] overflow-y-auto">

                                        <!-- Summary for Step 3 will be populated here -->

                                    </div>

                                </div>



                                <!-- Section Navigation -->

                                <div class="mt-6 flex justify-end space-x-3">

                                    <button type="button" id="prevSectionBtn" onclick="prevSection(3)" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all hidden">

                                        Previous Section

                                    </button>

                                    <button type="button" id="nextSectionBtn" onclick="nextSection(3, 5)" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all">

                                        Next Section

                                    </button>

                                </div>

                            </div>



                            <!-- Step 4: Document Uploads -->

                            <div class="form-step hidden" data-step="4">

                                <h4 class="text-lg font-semibold text-gray-700 mb-2">Form4: Required Documents</h4>

                                <!-- Section Progress Bar -->

                                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-6">

                                    <div id="step4-progress-bar" class="bg-blue-600 h-1.5 rounded-full transition-all duration-500" style="width: 0%"></div>

                                </div>



                                <div class="form-section" data-section="1">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 1 of 5: Core Documents</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="claimForm" name="claimForm" accept=".pdf,.jpg,.png" 

                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"

                                                    onchange="handleFileSelect(this, 'claimFormLabel')">

                                                <div class="text-center" id="claimFormLabel">

                                                    <i class="fas fa-file-upload text-gray-400 text-xl mb-2"></i>

                                                    <p class="text-sm text-gray-500">Claim Form (CF4)</p>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="medicalDocs" name="medicalDocs" accept=".pdf,.jpg,.png" multiple 

                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"

                                                    onchange="handleFileSelect(this, 'medicalDocsLabel')">

                                                <div class="text-center" id="medicalDocsLabel">

                                                    <i class="fas fa-file-medical text-gray-400 text-xl mb-2"></i>

                                                    <p class="text-sm text-gray-500">Medical Documents</p>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="soa" name="soa" accept=".pdf,.jpg,.png"

                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"

                                                    onchange="handleFileSelect(this, 'soaLabel')">

                                                <div class="text-center" id="soaLabel">

                                                    <i class="fas fa-file-invoice text-gray-400 text-xl mb-2"></i>

                                                    <p class="text-sm text-gray-500">Statement of Account (SOA)</p>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="mdr" name="mdr" accept=".pdf,.jpg,.png"

                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"

                                                    onchange="handleFileSelect(this, 'mdrLabel')">

                                                <div class="text-center" id="mdrLabel">

                                                    <i class="fas fa-id-card text-gray-400 text-xl mb-2"></i>

                                                    <p class="text-sm text-gray-500">Member Data Record (MDR)</p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="2">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 2 of 5: Consent & Authorization Forms</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="consentAdmission" name="consentAdmission" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'consentAdmissionLabel')">

                                                <div class="text-center" id="consentAdmissionLabel"><i class="fas fa-file-signature text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">Consent for Admission</p></div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="consentProcedure" name="consentProcedure" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'consentProcedureLabel')">

                                                <div class="text-center" id="consentProcedureLabel"><i class="fas fa-file-signature text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">Consent for Procedure</p></div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="dataPrivacy" name="dataPrivacy" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'dataPrivacyLabel')">

                                                <div class="text-center" id="dataPrivacyLabel"><i class="fas fa-user-shield text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">Data Privacy Consent</p></div>

                                            </div>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="3">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 3 of 5: Laboratory & Imaging Results</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="lab_cbc" name="lab_cbc" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'lab_cbc_label')">

                                                <div class="text-center" id="lab_cbc_label"><i class="fas fa-vial text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">CBC Result</p></div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="lab_urinalysis" name="lab_urinalysis" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'lab_urinalysis_label')">

                                                <div class="text-center" id="lab_urinalysis_label"><i class="fas fa-vial text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">Urinalysis Result</p></div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="lab_xray" name="lab_xray" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'lab_xray_label')">

                                                <div class="text-center" id="lab_xray_label"><i class="fas fa-x-ray text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">X-Ray Result</p></div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="lab_ctscan" name="lab_ctscan" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'lab_ctscan_label')">

                                                <div class="text-center" id="lab_ctscan_label"><i class="fas fa-x-ray text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">CT-Scan Result</p></div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="lab_mri" name="lab_mri" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'lab_mri_label')">

                                                <div class="text-center" id="lab_mri_label"><i class="fas fa-x-ray text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">MRI Result</p></div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="lab_others" name="lab_others" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'lab_others_label')">

                                                <div class="text-center" id="lab_others_label"><i class="fas fa-vials text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">Other Lab Results</p></div>

                                            </div>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="4">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 4 of 5: Other Supporting Documents</h5>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="doc_promissory" name="doc_promissory" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'doc_promissory_label')">

                                                <div class="text-center" id="doc_promissory_label"><i class="fas fa-file-contract text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">Promissory Note</p></div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="doc_police_report" name="doc_police_report" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'doc_police_report_label')">

                                                <div class="text-center" id="doc_police_report_label"><i class="fas fa-file-alt text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">Police Report</p></div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="doc_indigency" name="doc_indigency" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'doc_indigency_label')">

                                                <div class="text-center" id="doc_indigency_label"><i class="fas fa-file-alt text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">Cert. of Indigency</p></div>

                                            </div>

                                        </div>

                                        <div class="space-y-2">

                                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">

                                                <input type="file" id="doc_others" name="doc_others" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this, 'doc_others_label')">

                                                <div class="text-center" id="doc_others_label"><i class="fas fa-folder-plus text-gray-400 text-xl mb-2"></i><p class="text-sm text-gray-500">Other Documents</p></div>

                                            </div>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-section hidden" data-section="5">

                                    <h5 class="text-md font-medium text-gray-600 mb-4">Section 5 of 5: Summary of Uploaded Documents</h5>

                                    <div id="step4-review-content" class="bg-gray-50 p-6 rounded-lg space-y-4 max-h-[50vh] overflow-y-auto">

                                        <!-- Summary for Step 4 will be populated here -->

                                    </div>

                                </div>



                                <!-- Section Navigation -->

                                <div class="mt-6 flex justify-end space-x-3">

                                    <button type="button" id="prevSectionBtn" onclick="prevSection(4)" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all hidden">

                                        Previous Section

                                    </button>

                                    <button type="button" id="nextSectionBtn" onclick="nextSection(4, 5)" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all">

                                        Next Section

                                    </button>

                                </div>

                            </div>



                            <!-- Step 5: Review -->

                            <div class="form-step hidden" data-step="5">

                                <h4 class="text-lg font-semibold text-gray-700 mb-4">Form5: Review and Submit</h4>

                                <div id="review-content" class="space-y-4 bg-gray-50 p-6 rounded-lg max-h-[60vh] overflow-y-auto">

                                    <!-- Summary content will be populated here by JS -->

                                </div>

                                 <!-- Upload Progress (shown on submit) -->

                                 <div id="uploadProgress" class="hidden mt-4">

                                    <div class="flex justify-between text-sm text-gray-600 mb-1">

                                        <span id="uploadStatusText">Uploading files...</span>

                                        <span id="uploadPercentage">0%</span>

                                    </div>

                                    <div class="w-full bg-gray-200 rounded-full h-2">

                                        <div id="uploadProgressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>

                                    </div>

                                </div>

                            </div>



                            <!-- Navigation Buttons -->

                            <div class="border-t border-gray-200 pt-6 mt-6 flex justify-between items-center">

                                <button type="button" id="prevBtn" onclick="prevStep()"

                                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">

                                    Previous

                                </button>

                                <div class="flex-grow text-right">

                                    <button type="button" id="nextBtn" onclick="nextStep()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">Next</button>

                                    <button type="submit" id="submitBtn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all hidden">

                                        Submit Claim

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

            <?php endif; ?>



            <!-- Dashboard Content -->

            <div class="p-4 md:p-6">

                <!-- Stats Overview -->

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

                    <!-- Total Claims -->

                    <div class="bg-gradient-to-br from-slate-50 to-blue-400 rounded-2xl p-6 text-gray-800 flex flex-col justify-between">

                        <div>

                            <div class="flex justify-between items-start">

                                <div>

                                    <p>Total Claims</p>

                                    <h3 id="statTotalClaims" class="text-3xl font-bold mt-1">-</h3>

                                    <p class="text-gray-700 text-sm mt-1">Total in database</p>

                                </div>

                                <div class="bg-blue-400/30 p-3 rounded-lg">

                                    <i class="fas fa-file-invoice-dollar text-2xl"></i>

                                </div>

                            </div>

                            <div class="mt-4 flex items-center "><span id="statTotalClaimsSub">-</span></div>

                        </div>

                        <div class="mt-4 border-t border-gray-500/20 pt-2 text-right">

                            <a href="#" onclick="event.preventDefault(); showRecords('total', 'Total Claims');" class="text-sm font-medium text-gray-600 hover:text-gray-800">

                                View Records <i class="fas fa-arrow-right ml-1 text-xs"></i>

                            </a>

                        </div>

                    </div>



                    <!-- Pending Claims -->

                    <div class="bg-gradient-to-br from-slate-50 to-amber-400 rounded-2xl p-6 text-gray-800 flex flex-col justify-between">

                        <div>

                            <div class="flex justify-between items-start">

                                <div>

                                    <p>Pending Claims</p>

                                    <h3 id="statPendingClaims" class="text-3xl font-bold mt-1">-</h3>

                                    <p class="text-gray-700 text-sm mt-1">Need attention</p>

                                </div>

                                <div class="bg-amber-400/30 p-3 rounded-lg">

                                    <i class="fas fa-clock text-2xl"></i>

                                </div>

                            </div>

                            <div class="mt-4 flex items-center "><span id="statPendingClaimsSub">-</span></div>

                        </div>

                        <div class="mt-4 border-t border-gray-500/20 pt-2 text-right">

                            <a href="#" onclick="event.preventDefault(); showRecords('pending', 'Pending Claims');" class="text-sm font-medium text-gray-600 hover:text-gray-800">

                                View Records <i class="fas fa-arrow-right ml-1 text-xs"></i>

                            </a>

                        </div>

                    </div>



                    <!-- Approved Claims -->

                    <div class="bg-gradient-to-br from-slate-50 to-green-400 rounded-2xl p-6 text-gray-800 flex flex-col justify-between">

                        <div>

                            <div class="flex justify-between items-start">

                                <div>

                                    <p >Approved Claims</p>

                                    <h3 id="statApprovedClaims" class="text-3xl font-bold mt-1">-</h3>

                                    <p class="text-gray-700 text-sm mt-1">This month</p>

                                </div>

                                <div class="bg-green-400/30 p-3 rounded-lg">

                                    <i class="fas fa-check-circle text-2xl"></i>

                                </div>

                            </div>

                            <div class="mt-4 flex items-center "><span id="statApprovedClaimsSub">-</span></div>

                        </div>

                        <div class="mt-4 border-t border-gray-500/20 pt-2 text-right">

                            <a href="#" onclick="event.preventDefault(); showRecords('approved', 'Approved Claims');" class="text-sm font-medium text-gray-600 hover:text-gray-800">

                                View Records <i class="fas fa-arrow-right ml-1 text-xs"></i>

                            </a>

                        </div>

                    </div>



                    <!-- Rejected Claims -->

                    <div class="bg-gradient-to-br from-slate-50 to-red-400 rounded-2xl p-6 text-gray-800 flex flex-col justify-between">

                        <div>

                            <div class="flex justify-between items-start">

                                <div>

                                    <p >Rejected Claims</p>

                                    <h3 id="statRejectedClaims" class="text-3xl font-bold mt-1">-</h3>

                                    <p class="text-gray-700 text-sm mt-1">Need revision</p>

                                </div>

                                <div class="bg-red-400 p-3 rounded-lg">

                                    <i class="fas fa-times-circle text-2xl"></i>

                                </div>

                            </div>

                            <div class="mt-4 flex items-center "><span id="statRejectedClaimsSub">-</span></div>

                        </div>

                        <div class="mt-4 border-t border-gray-500/20 pt-2 text-right">

                            <a href="#" onclick="event.preventDefault(); showRecords('rejected', 'Rejected Claims');" class="text-sm font-medium text-gray-600 hover:text-gray-800">

                                View Records <i class="fas fa-arrow-right ml-1 text-xs"></i>

                            </a>

                        </div>

                    </div>



                    <!-- Eligible Claims -->

                    <div class="bg-gradient-to-br from-slate-50 to-teal-400 rounded-2xl p-6 text-gray-800 flex flex-col justify-between">

                        <div>

                            <div class="flex justify-between items-start">

                                <div>

                                    <p>Eligible Claims</p>

                                    <h3 id="statEligibleClaims" class="text-3xl font-bold mt-1">-</h3>

                                    <p class="text-gray-700 text-sm mt-1">Ready for submission</p>

                                </div>

                                <div class="bg-teal-400/30 p-3 rounded-lg">

                                    <i class="fas fa-user-check text-2xl"></i>

                                </div>

                            </div>

                            <div class="mt-4 flex items-center "><span id="statEligibleClaimsSub">-</span></div>

                        </div>

                        <div class="mt-4 border-t border-gray-500/20 pt-2 text-right">

                            <a href="#" onclick="event.preventDefault(); showRecords('eligible', 'Eligible Claims');" class="text-sm font-medium text-gray-600 hover:text-gray-800">

                                View Records <i class="fas fa-arrow-right ml-1 text-xs"></i>

                            </a>

                        </div>

                    </div>



                    <!-- Ineligible Claims -->

                    <div class="bg-gradient-to-br from-slate-50 to-slate-400 rounded-2xl p-6 text-gray-800 flex flex-col justify-between">

                        <div>

                            <div class="flex justify-between items-start">

                                <div>

                                    <p>Ineligible Claims</p>

                                    <h3 id="statIneligibleClaims" class="text-3xl font-bold mt-1">-</h3>

                                    <p class="text-gray-700 text-sm mt-1">Missing documents</p>

                                </div>

                                <div class="bg-slate-400/30 p-3 rounded-lg">

                                    <i class="fas fa-user-times text-2xl"></i>

                                </div>

                            </div>

                            <div class="mt-4 flex items-center "><span id="statIneligibleClaimsSub">-</span></div>

                        </div>

                        <div class="mt-4 border-t border-gray-500/20 pt-2 text-right">

                            <a href="#" onclick="event.preventDefault(); showRecords('ineligible', 'Ineligible Claims');" class="text-sm font-medium text-gray-600 hover:text-gray-800">

                                View Records <i class="fas fa-arrow-right ml-1 text-xs"></i>

                            </a>

                        </div>

                    </div>

                </div>



                <!-- Charts Grid -->

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                    <!-- Claims Monthly Trend -->

                    <div class="bg-white rounded-2xl shadow-lg p-6">

                        <h3 class="text-lg font-semibold mb-4">Monthly Claims Trend</h3>

                        <div class="chart-container">

                            <canvas id="monthlyTrendChart"></canvas>

                        </div>

                    </div>



                    <!-- Claims by Type -->

                    <div class="bg-white rounded-2xl shadow-lg p-6">

                        <h3 class="text-lg font-semibold mb-4">Claims by Type</h3>

                        <div class="chart-container">

                            <canvas id="claimTypeChart"></canvas>

                        </div>

                    </div>



                    <!-- Processing Time -->

                    <div class="bg-white rounded-2xl shadow-lg p-6">

                        <h3 class="text-lg font-semibold mb-4">Average Processing Time</h3>

                        <div class="chart-container">

                            <canvas id="processingTimeChart"></canvas>

                        </div>

                    </div>



                    <!-- Claims Status Distribution -->

                    <div class="bg-white rounded-2xl shadow-lg p-6">

                        <h3 class="text-lg font-semibold mb-4">Claims Status Distribution</h3>

                        <div class="chart-container">

                            <canvas id="statusDistributionChart"></canvas>

                        </div>

                    </div>

                </div>

                <!-- Recent Claims Table -->
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="p-6 border-b border-gray-100">

                        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">

                            <h3 class="text-lg font-semibold">Recent Claims</h3>

                            <div class="flex items-center space-x-2">

                                <input type="text" placeholder="Search claims..." class="w-full md:w-auto px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                                <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg">

                                    <i class="fas fa-filter"></i>

                                </button>

                            </div>

                        </div>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Claim ID</th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Filed</th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>

                                </tr>

                            </thead>

                            <tbody id="philhealthClaimsTbody" class="bg-white divide-y divide-gray-200"></tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>



    <!-- View Records Modal -->

    <div id="viewRecordsModal" class="fixed top-0 bottom-0 left-0 right-0 bg-black bg-opacity-60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl relative z-50 flex flex-col max-h-[90vh]">

            <div class="p-6 border-b border-gray-200 flex-shrink-0">

                <div class="flex justify-between items-center">

                    <h3 id="recordsModalTitle" class="text-xl font-semibold text-gray-900">Claim Records</h3>

                    <button onclick="toggleRecordsModal(false)" class="text-gray-400 hover:text-gray-600">

                        <i class="fas fa-times text-xl"></i>

                    </button>

                </div>

            </div>

            <div id="recordsModalBody" class="p-6 overflow-y-auto">

                <!-- Table will be dynamically inserted here -->

            </div>

            <div class="p-4 bg-gray-50 border-t flex justify-end flex-shrink-0">

                <button onclick="toggleRecordsModal(false)" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-100">

                    Close

                </button>

            </div>

        </div>

    </div>



    <script>

        function aiFillNewClaim() { }

        async function proceedToCF1() {
            window.location.href = 'philhealth-cf1.php?mode=edit';
        }



        function toggleRecordsModal(show) {

            const modal = document.getElementById('viewRecordsModal');

            if (show) {

                modal.classList.remove('hidden');

                modal.classList.add('flex');

            } else {

                modal.classList.add('hidden');

                modal.classList.remove('flex');

            }

        }



        function handleFileSelect(input, labelId) {

            const label = document.getElementById(labelId);

            if (input.files.length > 0) {

                const fileCount = input.files.length;

                const fileNames = Array.from(input.files).map(file => file.name).join(', ');

                label.innerHTML = `

                    <i class="fas fa-check-circle text-green-500 text-xl mb-2"></i>

                    <p class="text-sm text-gray-700">${fileCount} file(s) selected</p>

                    <p class="text-xs text-gray-500 truncate">${fileNames}</p>

                `;

            }

        }



        function simulateUpload(callback) {

            const progress = document.getElementById('uploadProgress');

            const statusText = document.getElementById('uploadStatusText');

            const progressBar = document.getElementById('uploadProgressBar');

            const percentage = document.getElementById('uploadPercentage');

            let width = 0;



            statusText.textContent = 'Submitting claim...';

            progressBar.style.width = '0%';

            percentage.textContent = '0%';

            progress.classList.remove('hidden');



            const interval = setInterval(() => {

                if (width >= 100) {

                    clearInterval(interval);

                    callback();

                } else {

                    width += Math.random() * 15;

                    if (width > 100) width = 100;

                    progressBar.style.width = width + '%';

                    percentage.textContent = Math.round(width) + '%';

                }

            }, 500);

        }



        async function openExistingClaimForms(patientId) {

            const pid = Number(patientId);

            if (!Number.isFinite(pid) || pid <= 0) return;



            try {

                const res = await fetch(API_BASE_URL + '/philhealth/member_claim.php?patient_id=' + encodeURIComponent(String(pid)), {

                    headers: { 'Accept': 'application/json' },

                });

                const json = await res.json().catch(() => null);

                if (!res.ok || !json || !json.ok) {

                    throw new Error((json && json.error) ? json.error : 'Failed to load member claim');

                }



                const safeWrite = (key, value) => {

                    try {

                        sessionStorage.setItem(key, JSON.stringify(value ?? null));

                    } catch (_) { }

                };



                safeWrite('philhealthPatientId', String(pid));



                const forms = (json.forms && typeof json.forms === 'object') ? json.forms : {};

                if (forms.cf1) safeWrite('philhealthCf1Draft', forms.cf1);

                if (forms.cf2) safeWrite('philhealthCf2Draft', forms.cf2);

                if (forms.cf3) safeWrite('philhealthCf3Draft', forms.cf3);

                if (forms.cf4) safeWrite('philhealthCf4Draft', forms.cf4);



                sessionStorage.setItem('philhealthNewClaimActive', '1');

                if (forms.cf1) sessionStorage.setItem('philhealthStepCf1Complete', '1');

                if (forms.cf2) sessionStorage.setItem('philhealthStepCf2Complete', '1');

                if (forms.cf3) sessionStorage.setItem('philhealthStepCf3Complete', '1');

                if (forms.cf4) sessionStorage.setItem('philhealthStepCf4Complete', '1');



                window.location.href = 'philhealth-cf1.php?patient_id=' + encodeURIComponent(String(pid)) + '&mode=edit';

            } catch (e) {

                alert(e && e.message ? e.message : 'Failed to open forms');

            }

        }



        function ensureAiFillOverlay() {

            let style = document.getElementById('aiFillOverlayStyle');

            if (!style) {

                style = document.createElement('style');

                style.id = 'aiFillOverlayStyle';

                style.textContent = '@keyframes aiFillSpin{to{transform:rotate(360deg)}}.ai-fill-spin{animation:aiFillSpin 1s linear infinite}';

                document.head.appendChild(style);

            }

            let overlay = document.getElementById('aiFillOverlay');

            if (!overlay) {

                overlay = document.createElement('div');

                overlay.id = 'aiFillOverlay';

                overlay.className = 'hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center';

                overlay.innerHTML = `

                    <div class="bg-white rounded-xl shadow-xl px-6 py-5 w-[92%] max-w-sm">

                        <div class="flex items-center gap-4">

                            <div class="w-10 h-10 rounded-full border-4 border-gray-200 border-t-blue-600 ai-fill-spin"></div>

                            <div>

                                <div class="text-sm font-semibold text-gray-900" id="aiFillOverlayTitle">Filling with AI…</div>

                                <div class="text-xs text-gray-500" id="aiFillOverlaySubtitle">Please wait</div>

                            </div>

                        </div>

                    </div>

                `;

                document.body.appendChild(overlay);

            }

            return overlay;

        }



        function showAiFillOverlay(title, subtitle) {

            const overlay = ensureAiFillOverlay();

            const t = overlay.querySelector('#aiFillOverlayTitle');

            const s = overlay.querySelector('#aiFillOverlaySubtitle');

            if (t) t.textContent = title || 'Filling with AI…';

            if (s) s.textContent = subtitle || 'Please wait';

            overlay.classList.remove('hidden');

        }



        function hideAiFillOverlay() {

            const overlay = document.getElementById('aiFillOverlay');

            if (overlay) overlay.classList.add('hidden');

        }



        async function aiFillNewClaim() {

            const form = document.getElementById('newClaimForm');

            if (!form) return;



            const btn = document.getElementById('fillTestBtn');

            if (btn) btn.disabled = true;

            showAiFillOverlay('Filling with AI…', 'Preparing your claim information');



            try {

                let seed = '';

                try {

                    seed = (sessionStorage.getItem('philhealthAiSeed') || '').toString();

                } catch (e) {

                    seed = '';

                }

                if (seed.trim() === '') {

                    seed = Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 10);

                    try {

                        sessionStorage.setItem('philhealthAiSeed', seed);

                    } catch (e) {

                    }

                }



                const targets = [];

                const fields = [];



                const radiosByName = new Map();

                Array.from(form.querySelectorAll('input[type="radio"]')).forEach(r => {

                    if (!r.name) return;

                    if (!radiosByName.has(r.name)) radiosByName.set(r.name, []);

                    radiosByName.get(r.name).push(r);

                });



                // normal inputs (excluding radios)

                Array.from(form.querySelectorAll('input, textarea, select')).forEach(el => {

                    const tag = (el.tagName || '').toLowerCase();

                    const type = (el.getAttribute('type') || '').toLowerCase();

                    if (tag === 'input' && type === 'radio') return;

                    if (type === 'file') return;

                    if (el.disabled) return;



                    if (tag === 'select') {

                        const options = Array.from(el.options).map(o => ({ value: o.value, label: o.textContent || '' })).filter(o => o.value !== '');

                        targets.push({ kind: 'select', el, options });

                        fields.push({ kind: 'select', name: el.name || '', required: !!el.required, options });

                        return;

                    }



                    if (tag === 'input' && type === 'checkbox') {

                        targets.push({ kind: 'checkbox', el });

                        fields.push({ kind: 'checkbox', name: el.name || '', required: !!el.required });

                        return;

                    }



                    targets.push({ kind: 'input', el, type: type || 'text' });

                    fields.push({ kind: 'input', name: el.name || '', type: type || 'text', required: !!el.required, placeholder: el.getAttribute('placeholder') || '' });

                });



                // radio groups (one entry per group)

                Array.from(radiosByName.entries()).forEach(([name, radios]) => {

                    const options = radios.map(r => ({ value: r.value || '', label: (r.parentElement && r.parentElement.textContent) ? r.parentElement.textContent.trim() : '' })).filter(o => o.value !== '');

                    if (!options.length) return;

                    targets.push({ kind: 'radio', name, radios, options });

                    fields.push({ kind: 'radio', name, required: radios.some(r => r.required), options });

                });



                const res = await fetch(API_BASE_URL + '/philhealth/ai_fill.php', {

                    method: 'POST',

                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },

                    body: JSON.stringify({ page: 'basic_modal', seed, fields }),

                });

                const json = await res.json().catch(() => null);

                if (!res.ok || !json || !json.ok) {

                    throw new Error((json && json.error) ? json.error : 'AI fill failed');

                }



                const values = Array.isArray(json.values) ? json.values : [];

                values.forEach(item => {

                    if (!item || typeof item !== 'object') return;

                    const idx = Number(item.index);

                    if (!Number.isFinite(idx)) return;

                    const target = targets[idx];

                    if (!target) return;

                    const v = item.value;



                    if (target.kind === 'select') {

                        const has = target.options.some(o => o.value === v);

                        if (has) target.el.value = v;

                        else if (target.options[0]) target.el.value = target.options[0].value;

                        target.el.dispatchEvent(new Event('input', { bubbles: true }));

                        target.el.dispatchEvent(new Event('change', { bubbles: true }));

                        return;

                    }



                    if (target.kind === 'checkbox') {

                        target.el.checked = !!(item.checked ?? v);

                        target.el.dispatchEvent(new Event('change', { bubbles: true }));

                        return;

                    }



                    if (target.kind === 'radio') {

                        const selected = target.radios.find(r => r.value === v) || target.radios[0];

                        if (selected) {

                            selected.checked = true;

                            selected.dispatchEvent(new Event('change', { bubbles: true }));

                        }

                        return;

                    }



                    if (target.kind === 'input') {

                        target.el.value = (v ?? '').toString();

                        target.el.dispatchEvent(new Event('input', { bubbles: true }));

                        target.el.dispatchEvent(new Event('change', { bubbles: true }));

                    }

                });

            } catch (e) {

                alert(e && e.message ? e.message : 'AI fill failed');

            } finally {

                if (btn) btn.disabled = false;

                hideAiFillOverlay();

            }

        }



        async function proceedToCF1() {

            if (!validateSection(1, 1)) return;



            try {

                const form = document.getElementById('newClaimForm');

                const fd = new FormData(form);

                const keys = [

                    'patientName', 'philhealthId', 'patientDOB', 'patientGender', 'patientContact', 'civilStatus', 'patientEmail',

                    'streetAddress', 'barangay', 'city', 'province', 'zipCode', 'employerName', 'employerAddress'

                ];

                const payload = {};

                keys.forEach(k => {

                    payload[k] = (fd.get(k) ?? '').toString();

                });



                const editCtx = window.philhealthEditContext;

                const editPatientId = editCtx && editCtx.patient_id ? Number(editCtx.patient_id) : null;

                if (Number.isFinite(editPatientId) && editPatientId > 0) {

                    payload.patientId = editPatientId;

                }



                payload.philhealthId = (payload.philhealthId || '').toString().replace(/\D/g, '');

                if (!payload.philhealthId) {

                    alert('Please enter a valid PhilHealth ID.');

                    return;

                }



                const isEditExisting = Number.isFinite(editPatientId) && editPatientId > 0;

                if (!isEditExisting) {

                    const pinRes = await fetch(API_BASE_URL + '/philhealth/pin_exists.php?pin=' + encodeURIComponent(payload.philhealthId), {

                        headers: { 'Accept': 'application/json' },

                    });

                    const pinJson = await pinRes.json().catch(() => null);

                    if (!pinRes.ok || !pinJson || !pinJson.ok) {

                        throw new Error((pinJson && pinJson.error) ? pinJson.error : 'Failed to validate PhilHealth ID');

                    }

                    if (pinJson.exists === true) {

                        alert('Duplicate PhilHealth ID. Member already exists and cannot create another claim.');

                        return;

                    }

                }



                const startRes = await fetch(API_BASE_URL + '/philhealth/claim_session.php', {

                    method: 'POST',

                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },

                    body: JSON.stringify({ action: 'start' }),

                });

                const startJson = await startRes.json().catch(() => null);

                if (!startRes.ok || !startJson || !startJson.ok) {

                    throw new Error((startJson && startJson.error) ? startJson.error : 'Failed to start claim session');

                }



                try {
                    sessionStorage.setItem('philhealthNewClaimActive', '1');
                    sessionStorage.removeItem('philhealthPatientId');
                } catch (e0) {
                }

                if (isEditExisting) {
                    let claimJson = null;
                    if (editCtx && editCtx.loaded === true && editCtx.cache) {
                        claimJson = editCtx.cache;
                    } else {
                        const claimRes = await fetch(API_BASE_URL + '/philhealth/member_claim.php?patient_id=' + encodeURIComponent(String(editPatientId)), {
                            headers: { 'Accept': 'application/json' },
                        });
                        claimJson = await claimRes.json().catch(() => null);
                        if (!claimRes.ok || !claimJson || !claimJson.ok) {
                            throw new Error((claimJson && claimJson.error) ? claimJson.error : 'Failed to load member claim');
                        }
                    }

                    try {
                        sessionStorage.setItem('philhealthPatientId', String(editPatientId));
                    } catch (e0) {
                    }

                    const forms = (claimJson && claimJson.forms) ? claimJson.forms : null;

                    const setOrRemove = (key, obj) => {
                        try {
                            if (obj && typeof obj === 'object') sessionStorage.setItem(key, JSON.stringify(obj));
                            else sessionStorage.removeItem(key);
                        } catch (e) {
                        }
                    };

                    setOrRemove('philhealthCf1Draft', forms ? forms.cf1 : null);
                    setOrRemove('philhealthCf2Draft', forms ? forms.cf2 : null);
                    setOrRemove('philhealthCf3Draft', forms ? forms.cf3 : null);
                    setOrRemove('philhealthCf4Draft', forms ? forms.cf4 : null);

                    const hasForm = (v) => v && typeof v === 'object';
                    try {
                        if (hasForm(forms && forms.cf1)) sessionStorage.setItem('philhealthStepCf1Complete', '1');
                        else sessionStorage.removeItem('philhealthStepCf1Complete');

                        if (hasForm(forms && forms.cf2)) sessionStorage.setItem('philhealthStepCf2Complete', '1');
                        else sessionStorage.removeItem('philhealthStepCf2Complete');

                        if (hasForm(forms && forms.cf3)) sessionStorage.setItem('philhealthStepCf3Complete', '1');
                        else sessionStorage.removeItem('philhealthStepCf3Complete');

                        if (hasForm(forms && forms.cf4)) sessionStorage.setItem('philhealthStepCf4Complete', '1');
                        else sessionStorage.removeItem('philhealthStepCf4Complete');
                    } catch (e) {
                    }
                } else {
                    try {
                        sessionStorage.removeItem('philhealthCf1Draft');
                        sessionStorage.removeItem('philhealthCf2Draft');
                        sessionStorage.removeItem('philhealthCf3Draft');
                        sessionStorage.removeItem('philhealthCf4Draft');
                        sessionStorage.removeItem('philhealthStepCf1Complete');
                        sessionStorage.removeItem('philhealthStepCf2Complete');
                        sessionStorage.removeItem('philhealthStepCf3Complete');
                        sessionStorage.removeItem('philhealthStepCf4Complete');
                    } catch (e0) {
                    }
                }

            } catch (e) {

                alert(e && e.message ? e.message : 'Failed to start claim registration');

                return;

            }



            let savedPid = null;

            try {

                const pid = (sessionStorage.getItem('philhealthPatientId') || '').toString().trim();
                if (pid) savedPid = pid;

            } catch (e2) {

                savedPid = null;

            }

            if (savedPid !== null && savedPid !== undefined && String(savedPid).trim() !== '') {

                window.location.href = 'philhealth-cf1.php?patient_id=' + encodeURIComponent(String(savedPid)) + '&mode=edit';

            } else {

                window.location.href = 'philhealth-cf1.php?mode=edit';

            }

        }



        // --- Section Logic ---

        function showSection(stepNumber, sectionNumber) {

            const stepContainer = document.querySelector(`.form-step[data-step="${stepNumber}"]`);

            if (!stepContainer) return;

        

            // Hide all sections in the current step

            stepContainer.querySelectorAll('.form-section').forEach(section => {

                section.classList.add('hidden');

            });

            if (stepNumber === 1 && sectionNumber === 5) {

                populateStep1Summary();

            }

            if (stepNumber === 2 && sectionNumber === 5) {

                populateStep2Summary();

            }

            if (stepNumber === 3 && sectionNumber === 5) {

                populateStep3Summary();

            }

            if (stepNumber === 4 && sectionNumber === 5) {

                populateStep4Summary();

            }



            // Show the target section

            const sectionToShow = stepContainer.querySelector(`.form-section[data-section="${sectionNumber}"]`);

            if (sectionToShow) {

                sectionToShow.classList.remove('hidden');

            }

        

            const totalSections = totalSectionsPerStep[stepNumber] || 1;

            updateSectionProgressBar(stepNumber, sectionNumber, totalSections);

            

            // Manage button visibility

            const prevSectionBtn = stepContainer.querySelector('#prevSectionBtn');

            const nextSectionBtn = stepContainer.querySelector('#nextSectionBtn');



            if (stepNumber === 1) {

                if (prevSectionBtn) prevSectionBtn.classList.add('hidden');

                if (nextSectionBtn) {

                    nextSectionBtn.classList.remove('hidden');

                    nextSectionBtn.style.display = 'inline-flex';

                    nextSectionBtn.textContent = 'Proceed to CF1';

                    nextSectionBtn.setAttribute('onclick', 'proceedToCF1()');

                }

                document.getElementById('nextBtn').style.display = 'none';

                document.getElementById('prevBtn').style.display = 'none';

                document.getElementById('submitBtn').classList.add('hidden');

                return;

            }

            

            if (totalSections > 1) {

                // This step has sections, so manage section buttons

                if (prevSectionBtn) prevSectionBtn.classList.toggle('hidden', sectionNumber === 1);

                if (nextSectionBtn) nextSectionBtn.setAttribute('onclick', `nextSection(${stepNumber}, ${totalSections})`);

                if (nextSectionBtn) {

                    nextSectionBtn.classList.remove('hidden');

                    nextSectionBtn.textContent = sectionNumber === totalSections ? 'Finish Step' : 'Next Section';

                }

                

                // Hide main navigation until last section is finished

                const isLastSection = sectionNumber === totalSections;

                document.getElementById('nextBtn').style.display = 'none';

                document.getElementById('prevBtn').style.display = 'none';

                document.getElementById('submitBtn').classList.toggle('hidden', !(isLastSection && stepNumber === totalSteps));

                if (prevSectionBtn) prevSectionBtn.style.display = !isLastSection ? 'inline-flex' : 'none';

                if (nextSectionBtn) nextSectionBtn.style.display = !isLastSection ? 'inline-flex' : 'none';



            } else {

                // This step has no sections, hide section buttons and show main buttons

                if (prevSectionBtn) prevSectionBtn.classList.add('hidden');

                if (nextSectionBtn) nextSectionBtn.classList.add('hidden');



                document.getElementById('nextBtn').classList.add('hidden');

                document.getElementById('prevBtn').classList.add('hidden');

                document.getElementById('submitBtn').classList.toggle('hidden', stepNumber !== totalSteps);

            }

        }



        function updateSectionProgressBar(stepNumber, currentSection, totalSections) {

            const progressBar = document.getElementById(`step${stepNumber}-progress-bar`);

            if (progressBar && totalSections > 1) {

                const percentage = ((currentSection - 1) / (totalSections - 1)) * 100;

                progressBar.style.width = `${percentage}%`;

            }

        }



        function nextSection(stepNumber, totalSections) {

            if (stepNumber === 1) {

                proceedToCF1();

                return;

            }

            if (validateSection(stepNumber, currentSections[stepNumber])) {

                if (currentSections[stepNumber] < totalSections) {

                    currentSections[stepNumber]++;

                    showSection(stepNumber, currentSections[stepNumber]);

                } else {

                    // Reached the end of sections for this step, show main nav

                    showSection(stepNumber, currentSections[stepNumber]);

                }

            }

        }



        function prevSection(stepNumber) {

            if (currentSections[stepNumber] > 1) {

                currentSections[stepNumber]--;

                showSection(stepNumber, currentSections[stepNumber]);

            }

        }



        function validateSection(stepNumber, sectionNumber) {

            const sectionDiv = document.querySelector(`.form-step[data-step="${stepNumber}"] .form-section[data-section="${sectionNumber}"]`);

            if (!sectionDiv) return true; // No validation if section doesn't exist

        

            const inputs = sectionDiv.querySelectorAll('input[required], select[required]');

            let isValid = true;

            inputs.forEach(input => {

                if (!input.value.trim()) {

                    isValid = false;

                    input.classList.add('border-red-500');

                    input.classList.remove('border-gray-200');

                } else {

                    input.classList.remove('border-red-500');

                    input.classList.add('border-gray-200');

                }

            });

            return isValid;

        }



        // --- Step Logic ---

        function showStep(stepNumber) {

            document.querySelectorAll('.form-step').forEach(step => {

                step.classList.add('hidden');

            });

            const stepToShow = document.querySelector(`.form-step[data-step="${stepNumber}"]`);

            stepToShow.classList.remove('hidden');

            

            updateStepper(stepNumber);



            if (stepNumber === 5) {

                populateReviewContent();

            }



            // When showing a new step, always start at its first section.

            showSection(stepNumber, currentSections[stepNumber]);

        }



        // --- Stepper Navigation & Modal Control ---

        function updateStepper(stepNumber) {

            document.querySelectorAll('#newClaimModal .step').forEach((stepEl, index) => {

                const circle = stepEl.querySelector('.step-circle');

                const connector = stepEl.nextElementSibling;



                if (index < stepNumber - 1) { // Completed steps

                    stepEl.classList.remove('text-gray-400');

                    stepEl.classList.add('text-blue-600');

                    circle.classList.remove('bg-gray-200');

                    circle.classList.add('bg-blue-600');

                    circle.innerHTML = '<i class="fas fa-check text-white text-sm"></i>';

                    if (connector) connector.classList.add('border-blue-600');

                } else if (index === stepNumber - 1) { // Current step

                    stepEl.classList.remove('text-gray-400');

                    stepEl.classList.add('text-blue-600');

                    circle.classList.remove('bg-gray-200');

                    circle.classList.add('bg-blue-600');

                    circle.textContent = stepNumber;

                    if (connector) connector.classList.remove('border-blue-600');

                } else { // Upcoming steps

                    stepEl.classList.remove('text-blue-600');

                    stepEl.classList.add('text-gray-400');

                    circle.classList.remove('bg-blue-600');

                    circle.classList.add('bg-gray-200');

                    circle.textContent = index + 1;

                    if (connector) connector.classList.remove('border-blue-600');

                }

            });



            // Button visibility

            document.getElementById('prevBtn').classList.add('hidden');

            document.getElementById('nextBtn').classList.add('hidden');

            document.getElementById('submitBtn').classList.add('hidden');

        }



        function nextStep() {

            const totalSections = totalSectionsPerStep[currentStep] || 1;

            // Only allow proceeding if on the last section of the current step

            if (currentSections[currentStep] === totalSections) {

                if (validateStep(currentStep) && currentStep < totalSteps) {

                    currentStep++;

                    // Reset section to 1 for the new step

                    currentSections[currentStep] = 1; 

                    showStep(currentStep);

                }

            } else {

                alert('Please complete all sections in the current step first by clicking "Next Section".');

            }

        }



        function prevStep() {

            if (currentStep > 1) {

                currentStep--;

                // Reset section to 1 for the previous step

                currentSections[currentStep] = 1;

                showStep(currentStep);

            }

        }



        function validateStep(stepNumber) {

            const currentStepDiv = document.querySelector(`.form-step[data-step="${stepNumber}"]`);

            const inputs = currentStepDiv.querySelectorAll('input[required], select[required]');

            let isValid = true;

            inputs.forEach(input => {

                if (!input.value.trim()) {

                    isValid = false;

                    input.classList.add('border-red-500');

                    input.classList.remove('border-gray-200');

                } else {

                    input.classList.remove('border-red-500');

                    input.classList.add('border-gray-200');

                }

            });

            return isValid;

        }



        function goToStepAndSection(step, section) {

            currentStep = step;

            currentSections[step] = section;

            showStep(step);

        }



        function populateReviewContent() {

            const form = document.getElementById('newClaimForm');

            const reviewContent = document.getElementById('review-content');

            const formData = new FormData(form);

            let html = '';



            const reviewSections = {

                'Basic Information': [

                    'patientName', 'philhealthId', 'patientDOB', 'patientGender', 'patientContact', 'civilStatus', 'patientEmail', 'streetAddress', 'barangay', 'city', 'province', 'zipCode', 'employerName', 'employerAddress'

                ],

                'Membership Details': [

                    'memberType', 'membershipStatus', 'memberCategory', 'relationship', 'principalPIN', 'principalDOB', 'principalName', 'employerPEN'

                ],

                'Claim Type': [

                    'claimType', 'claimSubtype', 'firstCaseRate', 'secondCaseRate', 'thirdCaseRate', 'isSecondCaseRate', 'consent'

                ],

                'Financials': [

                    'claimAmount', 'profFee', 'roomCharges', 'drugCharges', 'labCharges', 'philhealthBenefit', 'patientShare'

                ],

                'Confinement Details': [

                    'admissionDate', 'admissionTime', 'roomType', 'dischargeDate', 'dischargeTime', 'hospitalAccreditation', 'physician', 'physicianAccreditation',

                    'chiefComplaint', 'historyPresentIllness', 'pastMedicalHistory', 'obgynHistory', 'signsSymptoms', 'referringPhysician', 'allergies', 'admittingDiagnosis',

                    'pe_bp', 'pe_hr', 'pe_rr', 'pe_temp', 'pe_weight', 'pe_height', 'pe_general', 'pe_heent', 'pe_lungs', 'pe_cvs', 'pe_abdomen', 'pe_extremities',

                    'courseInWard', 'medicationsAdministered', 'proceduresDone', 'dischargeInstructions'

                ],

                'Diagnosis and Procedures': [

                    'primaryDiagnosis', 'procedure', 'secondaryDiagnosis', 'clinicalHistory', 'disposition',

                    'proc_date', 'proc_surgeon', 'proc_surgeon_acc', 'proc_anesthesiologist', 'proc_anes_acc', 'proc_anesthesia_type', 'proc_findings',

                    'mat_gravida', 'mat_para', 'mat_t', 'mat_p', 'mat_a', 'mat_l', 'mat_lmp', 'mat_edc', 'mat_delivery_type',

                    'nb_apgar1', 'nb_apgar5', 'nb_weight', 'nb_length', 'nb_head', 'nb_chest', 'nb_abdomen', 'nb_screening', 'nb_bcg', 'nb_hepb'

                ],

                'Uploaded Documents': [

                    'claimForm', 'medicalDocs', 'soa', 'mdr',

                    'consentAdmission', 'consentProcedure', 'dataPrivacy',

                    'lab_cbc', 'lab_urinalysis', 'lab_xray', 'lab_ctscan', 'lab_mri', 'lab_others',

                    'doc_promissory', 'doc_police_report', 'doc_indigency', 'doc_others'

                ]

            };



            const reviewSectionMapping = {

                'Basic Information': { step: 1, section: 1 },

                'Membership Details': { step: 1, section: 2 },

                'Claim Type': { step: 1, section: 3 },

                'Financials': { step: 1, section: 4 }, // This is a single section in step 1

                'Confinement Details': { step: 2, section: 1 },

                'Diagnosis and Procedures': { step: 3, section: 1 },

                'Uploaded Documents': { step: 4, section: 1 }

            };



            for (const [title, fieldNames] of Object.entries(reviewSections)) {

                let sectionHtml = '';

                fieldNames.forEach(name => {

                    const input = form.elements[name];

                    if (!input) return;



                    let value = formData.get(name);

                    if (input.type === 'file') {

                        if (input.multiple) {

                            value = input.files.length > 0 ? `${input.files.length} file(s) selected` : 'No files selected';

                        } else {

                            value = input.files.length > 0 ? input.files[0].name : 'No file selected';

                        }

                    } else if (input.type === 'checkbox') {

                        value = input.checked ? 'Yes' : 'No';

                    }



                    if (value && value.trim() !== '') {

                        const labelEl = input.closest('div').querySelector('label');

                        const labelText = labelEl ? labelEl.textContent.replace('*', '').trim() : name;

                        let finalLabel = labelText;

                        // For file uploads, the label is inside a <p> tag

                        if (input.type === 'file') {

                            const pLabel = input.closest('.space-y-2').querySelector('p');

                            if (pLabel) finalLabel = pLabel.textContent.trim();

                        }

                        sectionHtml += `<div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">${finalLabel}</dt><dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">${value}</dd></div>`;

                    }

                });



                if (sectionHtml) {

                    const mapping = reviewSectionMapping[title];

                    html += `<div class="mb-6">

                                <div class="flex justify-between items-center border-b pb-2 mb-3">

                                    <h5 class="text-md font-semibold text-gray-800">${title}</h5>

                                    ${mapping ? `<button type="button" onclick="goToStepAndSection(${mapping.step}, ${mapping.section})" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">

                                        <i class="fas fa-edit"></i>Edit

                                    </button>` : ''}

                                </div>

                                <dl>${sectionHtml}</dl>

                             </div>`;

                }

            }



            reviewContent.innerHTML = html || '<p class="text-gray-700">No information has been entered yet. Please go back and fill out the form.</p>';

        }



        function populateStep1Summary() {

            const form = document.getElementById('newClaimForm');

            const reviewContent = document.getElementById('step1-review-content');

            const formData = new FormData(form);

            let html = '';



            const step1Sections = {

                'Patient Identification': [

                    'patientName', 'philhealthId', 'patientDOB', 'patientGender', 'patientContact', 'civilStatus', 'patientEmail', 'streetAddress', 'barangay', 'city', 'province', 'zipCode', 'employerName', 'employerAddress'

                ],

                'Membership Details': [

                    'memberType', 'membershipStatus', 'memberCategory', 'relationship', 'principalPIN', 'principalDOB', 'principalName', 'employerPEN'

                ],

                'Claim Type': [

                    'claimType', 'claimSubtype', 'firstCaseRate', 'secondCaseRate', 'thirdCaseRate', 'isSecondCaseRate', 'consent'

                ],

                'Financials': [

                    'claimAmount', 'profFee', 'roomCharges', 'drugCharges', 'labCharges', 'philhealthBenefit', 'patientShare'

                ]

            };



            const sectionMapping = {

                'Patient Identification': 1,

                'Membership Details': 2,

                'Claim Type': 3,

                'Financials': 4

            };



            for (const [title, fieldNames] of Object.entries(step1Sections)) {

                let sectionHtml = '';

                fieldNames.forEach(name => {

                    const input = form.elements[name];

                    if (!input) return;



                    let value = formData.get(name);

                    if (input.type === 'checkbox') value = input.checked ? 'Yes' : 'No';



                    if (value && value.trim() !== '') {

                        const labelEl = input.closest('div').querySelector('label');

                        const labelText = labelEl ? labelEl.textContent.replace('*', '').trim() : name;

                        sectionHtml += `<div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">${labelText}</dt><dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">${value}</dd></div>`;

                    }

                });



                if (sectionHtml) {

                    const sectionNum = sectionMapping[title];

                    html += `<div class="mb-6">

                                <div class="flex justify-between items-center border-b pb-2 mb-3">

                                    <h5 class="text-md font-semibold text-gray-800">${title}</h5>

                                    <button type="button" onclick="goToStepAndSection(1, ${sectionNum})" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">

                                        <i class="fas fa-edit"></i>Edit

                                    </button>

                                </div>

                                <dl>${sectionHtml}</dl>

                             </div>`;

                }

            }

            reviewContent.innerHTML = html || '<p class="text-gray-700">No information has been entered yet. Please go back and fill out the form.</p>';

        }



        function populateStep2Summary() {

            const form = document.getElementById('newClaimForm');

            const reviewContent = document.getElementById('step2-review-content');

            const formData = new FormData(form);

            let html = '';



            const fields = [

                'admissionDate', 'admissionTime', 'roomType', 'dischargeDate', 'dischargeTime', 'hospitalAccreditation', 'physician', 'physicianAccreditation',

                'chiefComplaint', 'historyPresentIllness', 'pastMedicalHistory', 'obgynHistory', 'signsSymptoms', 'referringPhysician', 'allergies', 'admittingDiagnosis',

                'pe_bp', 'pe_hr', 'pe_rr', 'pe_temp', 'pe_weight', 'pe_height', 'pe_general', 'pe_heent', 'pe_lungs', 'pe_cvs', 'pe_abdomen', 'pe_extremities',

                'courseInWard', 'medicationsAdministered', 'proceduresDone', 'dischargeInstructions'

            ];

            let sectionHtml = '';



            fields.forEach(name => {

                const input = form.elements[name];

                if (!input) return;

                const value = formData.get(name);

                if (value && value.trim() !== '') {

                    const labelEl = input.closest('div').querySelector('label');

                    const labelText = labelEl ? labelEl.textContent.replace('*', '').trim() : name;

                    sectionHtml += `<div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">${labelText}</dt><dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">${value}</dd></div>`;

                }

            });



            if (sectionHtml) {

                html = `<div class="mb-6">

                            <div class="flex justify-between items-center border-b pb-2 mb-3">

                                <h5 class="text-md font-semibold text-gray-800">Confinement Details</h5>

                                <button type="button" onclick="goToStepAndSection(2, 1)" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">

                                    <i class="fas fa-edit"></i>Edit

                                </button>

                            </div>

                            <dl>${sectionHtml}</dl>

                         </div>`;

            }

            reviewContent.innerHTML = html || '<p class="text-gray-700">No information has been entered yet.</p>';

        }



        function populateStep3Summary() {

            const form = document.getElementById('newClaimForm');

            const reviewContent = document.getElementById('step3-review-content');

            const formData = new FormData(form);

            let html = '';



            const fields = [

                'primaryDiagnosis', 'procedure', 'secondaryDiagnosis', 'clinicalHistory', 'disposition',

                'proc_date', 'proc_surgeon', 'proc_surgeon_acc', 'proc_anesthesiologist', 'proc_anes_acc', 'proc_anesthesia_type', 'proc_findings',

                'mat_gravida', 'mat_para', 'mat_t', 'mat_p', 'mat_a', 'mat_l', 'mat_lmp', 'mat_edc', 'mat_delivery_type',

                'nb_apgar1', 'nb_apgar5', 'nb_weight', 'nb_length', 'nb_head', 'nb_chest', 'nb_abdomen', 'nb_screening', 'nb_bcg', 'nb_hepb'

            ];

            let sectionHtml = '';



            fields.forEach(name => {

                const input = form.elements[name];

                if (!input) return;

                const value = formData.get(name);

                if (value && value.trim() !== '') {

                    const labelEl = input.closest('div').querySelector('label');

                    const labelText = labelEl ? labelEl.textContent.replace('*', '').trim() : name;

                    sectionHtml += `<div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">${labelText}</dt><dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">${value}</dd></div>`;

                }

            });



            if (sectionHtml) {

                html = `<div class="mb-6">

                            <div class="flex justify-between items-center border-b pb-2 mb-3">

                                <h5 class="text-md font-semibold text-gray-800">Diagnosis and Procedures</h5>

                                <button type="button" onclick="goToStepAndSection(3, 1)" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">

                                    <i class="fas fa-edit"></i>Edit

                                </button>

                            </div>

                            <dl>${sectionHtml}</dl>

                         </div>`;

            }

            reviewContent.innerHTML = html || '<p class="text-gray-700">No information has been entered yet.</p>';

        }



        function populateStep4Summary() {

            const form = document.getElementById('newClaimForm');

            const reviewContent = document.getElementById('step4-review-content');

            let html = '';



            const fields = [

                'claimForm', 'medicalDocs', 'soa', 'mdr',

                'consentAdmission', 'consentProcedure', 'dataPrivacy',

                'lab_cbc', 'lab_urinalysis', 'lab_xray', 'lab_ctscan', 'lab_mri', 'lab_others',

                'doc_promissory', 'doc_police_report', 'doc_indigency', 'doc_others'

            ];

            let sectionHtml = '';



            fields.forEach(name => {

                const input = form.elements[name];

                if (!input) return;

                const value = input.files.length > 0 ? Array.from(input.files).map(f => f.name).join(', ') : 'No file selected';

                

                const labelEl = input.closest('.space-y-2').querySelector('p');

                const labelText = labelEl ? labelEl.textContent.trim() : name;

                sectionHtml += `<div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">${labelText}</dt><dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">${value}</dd></div>`;

            });



            if (sectionHtml) {

                html = `<div class="mb-6">

                            <div class="flex justify-between items-center border-b pb-2 mb-3">

                                <h5 class="text-md font-semibold text-gray-800">Uploaded Documents</h5>

                                <button type="button" onclick="goToStepAndSection(4, 1)" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">

                                    <i class="fas fa-edit"></i>Edit

                                </button>

                            </div>

                            <dl>${sectionHtml}</dl>

                         </div>`;

            }

            reviewContent.innerHTML = html || '<p class="text-gray-700">No documents have been selected.</p>';

        }





        function showRecords(category, title) {

            const dash = window.philhealthDashboardData || {};

            const all = Array.isArray(dash.claims) ? dash.claims : [];

            const statusOf = (c) => (c && c.status ? c.status : '').toString().trim().toLowerCase();

            const records = all.filter(c => {

                const s = statusOf(c);

                if (category === 'total') return true;

                if (category === 'pending') return s === 'pending';

                if (category === 'approved') return s === 'approved';

                if (category === 'rejected') return s === 'rejected';

                if (category === 'eligible') return s === 'eligible';

                if (category === 'ineligible') return s === 'ineligible';

                return false;

            });

            const modalTitle = document.getElementById('recordsModalTitle');

            const modalBody = document.getElementById('recordsModalBody');



            modalTitle.textContent = title;



            let tableHTML = `

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Claim ID</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>

                                ${category === 'rejected' || category === 'ineligible' ? '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>' : ''}

                            </tr>

                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

            `;



            records.forEach(claim => {

                const claimId = (claim.claim_ref || claim.claim_id || '').toString();

                const patient = (claim.full_name || claim.patient || '').toString();

                const amount = (claim.amount || '-').toString();

                const status = (claim.status || '').toString();

                const date = (claim.created_at || claim.date || '').toString();

                const reason = (claim.reason || '-').toString();

                tableHTML += `

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${claimId}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${patient}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${amount}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${status}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${date}</td>

                        ${category === 'rejected' || category === 'ineligible' ? `<td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">${reason}</td>` : ''}

                    </tr>

                `;

            });



            tableHTML += `

                        </tbody>

                    </table>

                </div>

            `;



            modalBody.innerHTML = tableHTML;

            toggleRecordsModal(true);

        }



        // Initialize form submission

        document.addEventListener('DOMContentLoaded', function() {

            // Sidebar toggle

            const menuBtn = document.getElementById('menu-btn');

            const sidebar = document.getElementById('sidebar');

            const mainContent = document.querySelector('main');



            if (menuBtn && sidebar && mainContent) {

                menuBtn.addEventListener('click', (e) => {

                    sidebar.classList.toggle('-translate-x-full');

                    e.stopPropagation();

                });



                mainContent.addEventListener('click', () => {

                    sidebar.classList.add('-translate-x-full');

                });

            }

            const newClaimBtn = document.getElementById('newClaimBtn');

            const cancelClaimRegistrationBtn = document.getElementById('cancelClaimRegistrationBtn');



            let claimActive = null;



            const fetchClaimActive = async () => {

                try {

                    const res = await fetch(API_BASE_URL + '/philhealth/claim_session.php', { headers: { 'Accept': 'application/json' } });

                    const json = await res.json().catch(() => null);

                    if (!res.ok || !json || !json.ok) return false;

                    return !!json.active;

                } catch (e) {

                    return false;

                }
            };



            const clearPhilhealthDraft = () => {

                try {

                    sessionStorage.removeItem('philhealthPatientId');

                    sessionStorage.removeItem('philhealthCf1Draft');

                    sessionStorage.removeItem('philhealthCf2Draft');

                    sessionStorage.removeItem('philhealthCf3Draft');

                    sessionStorage.removeItem('philhealthCf4Draft');

                    sessionStorage.removeItem('philhealthAiSeed');

                    sessionStorage.removeItem('philhealthNewClaimActive');

                    sessionStorage.removeItem('philhealthStepCf1Complete');

                    sessionStorage.removeItem('philhealthStepCf2Complete');

                    sessionStorage.removeItem('philhealthStepCf3Complete');

                    sessionStorage.removeItem('philhealthStepCf4Complete');

                } catch (e) {

                }

            };



            const startNewClaimDirect = async () => {

                if (!newClaimBtn) return;

                newClaimBtn.disabled = true;

                try {

                    clearPhilhealthDraft();

                    await fetch(API_BASE_URL + '/philhealth/claim_session.php', {

                        method: 'POST',

                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },

                        body: JSON.stringify({ action: 'start' }),

                    });

                    try {

                        sessionStorage.setItem('philhealthNewClaimActive', '1');

                        sessionStorage.removeItem('philhealthPatientId');

                    } catch (e) {

                    }

                    window.location.href = 'philhealth-cf1.php?mode=edit';

                } finally {

                    newClaimBtn.disabled = false;

                }

            };



            const refreshClaimControls = () => {

                if (claimActive === null) return;

                if (!newClaimBtn) return;

                if (!cancelClaimRegistrationBtn) return;

                cancelClaimRegistrationBtn.classList.add('hidden');

                const label = newClaimBtn.querySelector('span');

                if (label) label.textContent = 'New Claim';

                newClaimBtn.onclick = function (e) {

                    if (e && e.preventDefault) e.preventDefault();

                    startNewClaimDirect();

                };

            };



            fetchClaimActive().then(async active => {

                claimActive = !!active;

                if (claimActive) {

                    await fetch(API_BASE_URL + '/philhealth/claim_session.php', {

                        method: 'POST',

                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },

                        body: JSON.stringify({ action: 'cancel' }),

                    }).catch(() => { });

                    claimActive = false;

                }

                clearPhilhealthDraft();

                refreshClaimControls();

            });



            if (cancelClaimRegistrationBtn) {

                cancelClaimRegistrationBtn.addEventListener('click', async function () {

                    if (!confirm('Cancel claim registration? This will discard the current claim registration.')) return;



                    let patientId = null;

                    try {

                        patientId = (sessionStorage.getItem('philhealthPatientId') || '').toString().trim() || null;

                    } catch (e0) {

                    }



                    await fetch(API_BASE_URL + '/philhealth/claim_session.php', {

                        method: 'POST',

                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },

                        body: JSON.stringify({ action: 'cancel' }),

                    }).catch(() => { });

                    clearPhilhealthDraft();

                    claimActive = false;

                    alert('Claim registration cancelled.');

                    refreshClaimControls();

                });

            }



            async function loadPhilhealthDashboard() {

                const membersTbody = document.getElementById('philhealthMembersTbody');

                const claimsTbody = document.getElementById('philhealthClaimsTbody');

                if (!claimsTbody) return;



                try {

                    const res = await fetch(API_BASE_URL + '/philhealth/dashboard.php', { headers: { 'Accept': 'application/json' } });

                    const json = await res.json().catch(() => null);

                    if (!res.ok || !json || !json.ok) {

                        throw new Error((json && json.error) ? json.error : 'Failed to load dashboard');

                    }



                    window.philhealthDashboardData = json;



                    const escapeHtml = (s) => {

                        return (s ?? '').toString()

                            .replace(/&/g, '&amp;')

                            .replace(/</g, '&lt;')

                            .replace(/>/g, '&gt;')

                            .replace(/"/g, '&quot;')

                            .replace(/'/g, '&#039;');

                    };



                    const formatDate = (s) => {

                        const d = new Date(s);

                        if (!Number.isFinite(d.getTime())) return (s ?? '').toString();

                        return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: '2-digit' });

                    };



                    if (membersTbody) {

                        const members = Array.isArray(json.members) ? json.members : [];

                        membersTbody.innerHTML = members.map(m => {

                            const name = escapeHtml(m.full_name || '');

                            const dob = m.dob ? ('DOB: ' + escapeHtml(m.dob)) : '';

                            const pin = escapeHtml(m.philhealth_pin || '');

                            const updated = escapeHtml(formatDate(m.updated_at || ''));

                            const patientId = (m.patient_id ?? '').toString();

                            return `

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="text-sm font-medium text-gray-900">${name}</div>

                                        <div class="text-sm text-gray-500">${dob}</div>

                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${pin}</td>

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Registered</span>

                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${updated}</td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                        <div class="flex items-center gap-3">

                                            <button type="button" class="text-gray-700 hover:text-gray-900" onclick="openExistingClaimForms('${patientId}')">View/Edit CF1-CF4</button>

                                        </div>

                                    </td>

                                </tr>

                            `;

                        }).join('');

                    }



                    const claims = Array.isArray(json.claims) ? json.claims : [];

                    claimsTbody.innerHTML = claims.map(c => {

                        const patientId = (c.patient_id ?? '').toString();

                        const claimRef = escapeHtml(c.claim_ref || c.claim_id || '');

                        const name = escapeHtml(c.full_name || '');

                        const pin = escapeHtml(c.philhealth_pin || '');

                        const status = escapeHtml(c.status || '');

                        const filed = escapeHtml(formatDate(c.created_at || ''));

                        return `

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="text-sm font-medium text-gray-900">${claimRef}</div>

                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="text-sm text-gray-900">${name}</div>

                                    <div class="text-sm text-gray-500">PIN: ${pin}</div>

                                </td>

                                <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">-</div></td>

                                <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-700">${status}</div></td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${filed}</td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">-</td>

                            </tr>

                        `;

                    }).join('');



                    const byStatus = (arr) => {

                        const out = { total: 0, pending: 0, approved: 0, rejected: 0, eligible: 0, ineligible: 0 };

                        out.total = arr.length;

                        arr.forEach(c => {

                            const s = (c && c.status ? c.status : '').toString().trim().toLowerCase();

                            if (s === 'pending') out.pending++;

                            else if (s === 'approved') out.approved++;

                            else if (s === 'rejected') out.rejected++;

                            else if (s === 'eligible') out.eligible++;

                            else if (s === 'ineligible') out.ineligible++;

                        });

                        return out;

                    };



                    const stats = byStatus(claims);

                    const setText = (id, v) => {

                        const el = document.getElementById(id);

                        if (el) el.textContent = (v ?? '-').toString();

                    };

                    setText('statTotalClaims', stats.total);

                    setText('statPendingClaims', stats.pending);

                    setText('statApprovedClaims', stats.approved);

                    setText('statRejectedClaims', stats.rejected);

                    setText('statEligibleClaims', stats.eligible);

                    setText('statIneligibleClaims', stats.ineligible);

                    setText('statTotalClaimsSub', '');

                    setText('statPendingClaimsSub', '');

                    setText('statApprovedClaimsSub', '');

                    setText('statRejectedClaimsSub', '');

                    setText('statEligibleClaimsSub', '');

                    setText('statIneligibleClaimsSub', '');



                    if (typeof window.updatePhilhealthDashboardCharts === 'function') {

                        window.updatePhilhealthDashboardCharts(claims);

                    }

                } catch (e) {

                    membersTbody.innerHTML = '';

                    claimsTbody.innerHTML = '';

                }

            }



            loadPhilhealthDashboard();

                

        // Initialize the first step

        showStep(currentStep);

        });





        // Chart Options

        const chartOptions = {

            responsive: true,

            maintainAspectRatio: false,

            animation: {

                duration: 2000,

                easing: 'easeOutQuart'

            }

        };



        const safeChart = (id, cfg) => {

            const el = document.getElementById(id);

            if (!el) return null;

            return new Chart(el, cfg);

        };



        const monthlyTrendChart = safeChart('monthlyTrendChart', {

            type: 'line',

            data: { labels: [], datasets: [{ label: 'Total Claims', data: [], borderColor: 'rgb(59, 130, 246)', backgroundColor: 'rgba(59, 130, 246, 0.1)', fill: true, tension: 0.4 }] },

            options: { ...chartOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, title: { display: true, text: 'Number of Claims' } } } }

        });



        const claimTypeChart = safeChart('claimTypeChart', {

            type: 'doughnut',

            data: { labels: [], datasets: [{ data: [], backgroundColor: [] }] },

            options: { ...chartOptions, plugins: { legend: { position: 'bottom' } } }

        });



        const processingTimeChart = safeChart('processingTimeChart', {

            type: 'bar',

            data: { labels: [], datasets: [{ label: 'Number of Claims', data: [], backgroundColor: 'rgba(59, 130, 246, 0.8)' }] },

            options: { ...chartOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, title: { display: true, text: 'Number of Claims' } } } }

        });



        const statusDistributionChart = safeChart('statusDistributionChart', {

            type: 'polarArea',

            data: {

                labels: ['Approved', 'Pending', 'Rejected', 'Eligible', 'Ineligible'],

                datasets: [{

                    data: [0, 0, 0, 0, 0],

                    backgroundColor: [

                        'rgba(34, 197, 94, 0.7)',

                        'rgba(234, 179, 8, 0.7)',

                        'rgba(239, 68, 68, 0.7)',

                        'rgba(59, 130, 246, 0.7)',

                        'rgba(99, 102, 241, 0.7)'

                    ]

                }]

            },

            options: { ...chartOptions, plugins: { legend: { position: 'bottom' } } }

        });



        window.updatePhilhealthDashboardCharts = function (claims) {

            const list = Array.isArray(claims) ? claims : [];

            const toDate = (c) => {

                const raw = (c && (c.created_at || c.updated_at)) ? (c.created_at || c.updated_at) : null;

                const d = raw ? new Date(raw) : new Date('');

                return Number.isFinite(d.getTime()) ? d : null;

            };



            if (monthlyTrendChart) {

                const now = new Date();

                const months = [];

                for (let i = 8; i >= 0; i--) {

                    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);

                    months.push(d);

                }

                const labels = months.map(d => d.toLocaleDateString(undefined, { month: 'short' }));

                const counts = months.map(d => {

                    const y = d.getFullYear();

                    const m = d.getMonth();

                    return list.filter(c => {

                        const dt = toDate(c);

                        return dt && dt.getFullYear() === y && dt.getMonth() === m;

                    }).length;

                });

                monthlyTrendChart.data.labels = labels;

                monthlyTrendChart.data.datasets[0].data = counts;

                monthlyTrendChart.update();

            }



            if (statusDistributionChart) {

                let approved = 0, pending = 0, rejected = 0, eligible = 0, ineligible = 0;

                list.forEach(c => {

                    const s = (c && c.status ? c.status : '').toString().trim().toLowerCase();

                    if (s === 'approved') approved++;

                    else if (s === 'rejected') rejected++;

                    else if (s === 'pending') pending++;

                    else if (s === 'eligible') eligible++;

                    else if (s === 'ineligible') ineligible++;

                });

                statusDistributionChart.data.datasets[0].data = [approved, pending, rejected, eligible, ineligible];

                statusDistributionChart.update();

            }



            if (claimTypeChart) {

                claimTypeChart.data.labels = [];

                claimTypeChart.data.datasets[0].data = [];

                claimTypeChart.update();

            }



            if (processingTimeChart) {

                processingTimeChart.data.labels = [];

                processingTimeChart.data.datasets[0].data = [];

                processingTimeChart.update();

            }

        };

    </script>

</body>

</html>



