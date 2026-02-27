<?php
require_once __DIR__ . '/auth.php';
auth_session_start();

$requireClaimSession = true;
if ($requireClaimSession) {
    $claimActive = ($_SESSION['philhealth_claim_active'] ?? false) === true;
    $hasPatientId = isset($_GET['patient_id']) && trim((string)$_GET['patient_id']) !== '';
    if (!$claimActive && !$hasPatientId) {
        header('Location: philhealth-claims.php');
        exit;
    }
}

$isEditMode = isset($_GET['mode']) && $_GET['mode'] === 'edit';
$selfPath = basename($_SERVER['PHP_SELF']);
$editQuery = $_GET;
$editQuery['mode'] = 'edit';
$editUrl = $selfPath . '?' . http_build_query($editQuery);
$viewQuery = $_GET;
unset($viewQuery['mode']);
$viewUrl = $selfPath . (count($viewQuery) ? ('?' . http_build_query($viewQuery)) : '');

$isEmbed = isset($_GET['embed']) && $_GET['embed'] === '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhilHealth CF1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .cf-box {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }
        .cf-label {
            font-size: 13px;
            color: #374151;
        }
        .cf-input {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            width: 100%;
            padding: 8px 10px;
            background: #ffffff;
            font-size: 14px;
            line-height: 1.25rem;
            color: #111827;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }
        .cf-input::placeholder {
            color: #9ca3af;
        }
        .cf-input:hover {
            border-color: #9ca3af;
        }
        .cf-input:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        }
        .cf-section {
            border: 1px solid #e5e7eb;
        }
        .cf-section-title {
            background: #f3f4f6;
            color: #111827;
            font-weight: 700;
            font-size: 14px;
            padding: 10px 14px;
            letter-spacing: 0.02em;
            border-bottom: 1px solid #e5e7eb;
        }
        .cf-pin {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: 24px;
            gap: 4px;
        }
        .cf-pin input {
            width: 24px;
            height: 26px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #ffffff;
            text-align: center;
            font-size: 12px;
            outline: none;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }
        .cf-pin input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        }
        .cf-date {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: 24px;
            gap: 4px;
            align-items: center;
        }
        .cf-date input {
            width: 24px;
            height: 26px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #ffffff;
            text-align: center;
            font-size: 12px;
            outline: none;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }
        .cf-date input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        }
        .cf-small {
            font-size: 12px;
        }
        .cf-check {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
        }
        .cf-check input {
            width: 14px;
            height: 14px;
            accent-color: #2563eb;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php if (!$isEmbed): ?>
            <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        <?php endif; ?>
        <main class="flex-1 overflow-auto relative <?php echo $isEmbed ? 'p-2' : 'p-6'; ?>">
            <div class="mx-auto w-full <?php echo $isEmbed ? 'max-w-none' : 'max-w-[1400px]'; ?>">
                <div class="bg-white cf-section rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-4">
                        <?php if (!$isEmbed): ?>
                            <div class="flex items-center justify-between pb-2">
                                <div class="flex items-center gap-2">
                                    <button id="fillInfoBtn" type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Fill Info</button>
                                    <button id="cancelClaimBtn" type="button" class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-all">Cancel Claim Registration</button>
                                    <a id="nextCfBtn" href="philhealth-cf2.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">Next (CF2)</a>
                                </div>
                                <div id="modeToggleWrap">
                                    <?php if ($isEditMode): ?>
                                        <a href="<?php echo htmlspecialchars($viewUrl); ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">View</a>
                                    <?php else: ?>
                                        <a href="<?php echo htmlspecialchars($editUrl); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">Edit</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="grid grid-cols-12 gap-3 items-start">
                            <div class="col-span-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center cf-box">
                                        <span class="text-xs font-semibold">Logo</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold">PhilHealth</div>
                                        <div class="text-xs text-gray-600">Your Partner in Health</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-6 text-center">
                                <div class="text-xs">Republic of the Philippines</div>
                                <div class="text-sm font-bold">PHILIPPINE HEALTH INSURANCE CORPORATION</div>
                            </div>

                            <div class="col-span-3">
                                <div class="flex justify-end">
                                    <div class="text-right">
                                        <div class="text-xs">This form may be reproduced and is NOT FOR SALE</div>
                                        <div class="mt-2 inline-block cf-box px-3 py-2">
                                            <div class="text-lg font-black leading-none">CF-1</div>
                                            <div class="text-xs font-semibold">(Claim Form 1)</div>
                                            <div class="text-[10px] text-gray-700">Revised September 2018</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-center justify-end gap-2">
                                    <div class="text-xs font-semibold">Series #</div>
                                    <div class="cf-pin" aria-label="Series number">
                                        <?php for ($i = 0; $i < 8; $i++): ?>
                                            <input type="text" maxlength="1" inputmode="numeric" />
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 cf-box p-3">
                            <div class="text-xs font-bold">IMPORTANT REMINDERS</div>
                            <div class="cf-small mt-1">
                                PLEASE WRITE IN CAPITAL LETTERS AND CHECK THE APPROPRIATE BOXES.
                            </div>
                            <div class="cf-small mt-1">
                                All information required in this form are necessary. Claim forms with incomplete information shall not be processed.
                            </div>
                        </div>
                    </div>

                    <form id="cf1Form" class="px-4 pb-6 space-y-5">
                        <section class="cf-section">
                            <div class="cf-section-title">PART I - MEMBER INFORMATION</div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-12 gap-4 items-start">
                                    <div class="col-span-12 md:col-span-7">
                                        <div class="cf-label font-semibold">1. PhilHealth Identification Number (PIN) of Member:</div>
                                        <div class="mt-2" aria-label="Member PIN">
                                            <input type="text" name="member_pin" class="cf-input" placeholder="XX-XXXXXXXXX-X" inputmode="numeric" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="cf-label font-semibold">2. Name of Member:</div>
                                    <div class="mt-2 grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-3">
                                            <input class="cf-input" name="member_last_name" type="text" />
                                            <div class="cf-small text-center mt-1">Last Name</div>
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <input class="cf-input" name="member_first_name" type="text" />
                                            <div class="cf-small text-center mt-1">First Name</div>
                                        </div>
                                        <div class="col-span-12 md:col-span-2">
                                            <input class="cf-input" name="member_name_ext" type="text" />
                                            <div class="cf-small text-center mt-1">Name Extension (JR/SR/III)</div>
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <input class="cf-input" name="member_middle_name" type="text" />
                                            <div class="cf-small text-center mt-1">Middle Name</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-8">
                                        <div class="cf-label font-semibold">4. Mailing Address:</div>
                                        <div class="mt-2 grid grid-cols-12 gap-3">
                                            <div class="col-span-12 md:col-span-4">
                                                <input class="cf-input" name="member_address_unit" type="text" />
                                                <div class="cf-small text-center mt-1">Unit/Room No./Floor</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <input class="cf-input" name="member_address_building" type="text" />
                                                <div class="cf-small text-center mt-1">Building Name</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <input class="cf-input" name="member_address_house_no" type="text" />
                                                <div class="cf-small text-center mt-1">Lot/Blk/House/Bldg No.</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <input class="cf-input" name="member_address_street" type="text" />
                                                <div class="cf-small text-center mt-1">Street</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <input class="cf-input" name="member_address_subdivision" type="text" />
                                                <div class="cf-small text-center mt-1">Subdivision/Village</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <input class="cf-input" name="member_address_barangay" type="text" />
                                                <div class="cf-small text-center mt-1">Barangay</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <input class="cf-input" name="member_address_city" type="text" />
                                                <div class="cf-small text-center mt-1">City/Municipality</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-3">
                                                <input class="cf-input" name="member_address_province" type="text" />
                                                <div class="cf-small text-center mt-1">Province</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-3">
                                                <input class="cf-input" name="member_address_country" type="text" />
                                                <div class="cf-small text-center mt-1">Country</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-2">
                                                <input class="cf-input" name="member_address_zip" type="text" />
                                                <div class="cf-small text-center mt-1">Zip Code</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12 md:col-span-4 space-y-4">
                                        <div>
                                            <div class="cf-label font-semibold">5. Sex:</div>
                                            <div class="mt-2 flex gap-6">
                                                <label class="cf-check"><input type="radio" name="member_sex" value="M"> Male</label>
                                                <label class="cf-check"><input type="radio" name="member_sex" value="F"> Female</label>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="cf-label font-semibold">6. Contact Information:</div>
                                            <div class="mt-2 space-y-3">
                                                <div>
                                                    <input class="cf-input" name="member_landline" type="text" />
                                                    <div class="cf-small text-center mt-1">Landline No. (Area Code + Tel. No.)</div>
                                                </div>
                                                <div>
                                                    <input class="cf-input" name="member_mobile" type="text" />
                                                    <div class="cf-small text-center mt-1">Mobile No.</div>
                                                </div>
                                                <div>
                                                    <input class="cf-input" name="member_email" type="email" />
                                                    <div class="cf-small text-center mt-1">Email Address</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="cf-label font-semibold">7. Patient is the member?</div>
                                        <div class="mt-2 flex flex-wrap gap-6">
                                            <label class="cf-check"><input type="radio" name="patient_is_member" value="yes"> Yes, Proceed to Part III</label>
                                            <label class="cf-check"><input type="radio" name="patient_is_member" value="no"> No, Proceed to Part II</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="cf-section">
                            <div class="cf-section-title">PART II - PATIENT INFORMATION <span class="font-normal">(To be filled-out only if the patient is a dependent)</span></div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-12 gap-4 items-start">
                                    <div class="col-span-12">
                                        <div class="cf-label font-semibold">3. Date of Birth:</div>
                                        <div class="mt-2 flex flex-wrap items-start gap-6">
                                            <div class="shrink-0">
                                                <div class="cf-date" aria-label="Patient birth month">
                                                    <?php for ($i = 0; $i < 2; $i++): ?>
                                                        <input type="text" name="patient_birth_month[]" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="cf-small mt-1 text-center">month</div>
                                            </div>
                                            <div class="shrink-0">
                                                <div class="cf-date" aria-label="Patient birth day">
                                                    <?php for ($i = 0; $i < 2; $i++): ?>
                                                        <input type="text" name="patient_birth_day[]" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="cf-small mt-1 text-center">day</div>
                                            </div>
                                            <div class="shrink-0">
                                                <div class="cf-date" aria-label="Patient birth year">
                                                    <?php for ($i = 0; $i < 4; $i++): ?>
                                                        <input type="text" name="patient_birth_year[]" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="cf-small mt-1 text-center">year</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="cf-label font-semibold">2. Name of Patient:</div>
                                    <div class="mt-2 grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-3">
                                            <input class="cf-input" name="patient_last_name" type="text" />
                                            <div class="cf-small text-center mt-1">Last Name</div>
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <input class="cf-input" name="patient_first_name" type="text" />
                                            <div class="cf-small text-center mt-1">First Name</div>
                                        </div>
                                        <div class="col-span-12 md:col-span-2">
                                            <input class="cf-input" name="patient_name_ext" type="text" />
                                            <div class="cf-small text-center mt-1">Name Extension (JR/SR/III)</div>
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <input class="cf-input" name="patient_middle_name" type="text" />
                                            <div class="cf-small text-center mt-1">Middle Name</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-4 md:col-start-9">
                                        <div class="cf-label font-semibold">5. Sex:</div>
                                        <div class="mt-2 flex gap-6">
                                            <label class="cf-check"><input type="radio" name="patient_sex" value="M"> Male</label>
                                            <label class="cf-check"><input type="radio" name="patient_sex" value="F"> Female</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-4 items-start">
                                    <div class="col-span-12 md:col-span-4">
                                        <div class="cf-label font-semibold">6. Contact Number:</div>
                                        <input class="cf-input mt-2" name="patient_mobile" type="text" />
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <div class="cf-label font-semibold">7. Email Address:</div>
                                        <input class="cf-input mt-2" name="patient_email" type="email" />
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <div class="cf-label font-semibold">8. Civil Status:</div>
                                        <select class="cf-input mt-2" name="patient_civil_status">
                                            <option value=""></option>
                                            <option value="single">Single</option>
                                            <option value="married">Married</option>
                                            <option value="widowed">Widowed</option>
                                            <option value="separated">Separated</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-4 items-start">
                                    <div class="col-span-12">
                                        <div class="cf-label font-semibold">9. Address:</div>
                                    </div>
                                    <div class="col-span-12 md:col-span-12">
                                        <input class="cf-input" name="patient_street_address" type="text" />
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <input class="cf-input" name="patient_barangay" type="text" />
                                        <div class="cf-small text-center mt-1">Barangay</div>
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <input class="cf-input" name="patient_city" type="text" />
                                        <div class="cf-small text-center mt-1">City/Municipality</div>
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <input class="cf-input" name="patient_province" type="text" />
                                        <div class="cf-small text-center mt-1">Province</div>
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <input class="cf-input" name="patient_zip_code" type="text" />
                                        <div class="cf-small text-center mt-1">Zip Code</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-4 items-start">
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="cf-label font-semibold">10. Employer Name (if applicable):</div>
                                        <input class="cf-input mt-2" name="patient_employer_name" type="text" />
                                    </div>
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="cf-label font-semibold">11. Employer Address (if applicable):</div>
                                        <input class="cf-input mt-2" name="patient_employer_address" type="text" />
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="cf-section">
                            <div class="cf-section-title">PART III - MEMBER CERTIFICATION</div>
                            <div class="p-4 space-y-3">
                                <div class="cf-small italic">
                                    Under the penalty of law, I attest that the information provided in this Form are true and accurate to the best of my knowledge.
                                </div>

                                <div class="grid grid-cols-12 gap-4 items-end">
                                    <div class="col-span-12 md:col-span-6">
                                        <input class="cf-input" name="member_signature_name" type="text" />
                                        <div class="cf-small text-center mt-1">Signature Over Printed Name of Member</div>
                                    </div>
                                    <div class="col-span-12 md:col-span-6">
                                        <input class="cf-input" name="member_rep_signature_name" type="text" />
                                        <div class="cf-small text-center mt-1">Signature Over Printed Name of Member's Representative</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-4 items-start">
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="cf-label font-semibold">Date Signed</div>
                                        <div class="mt-2 flex flex-wrap items-start gap-6">
                                            <div>
                                                <div class="cf-date" aria-label="Member date signed month">
                                                    <?php for ($i = 0; $i < 2; $i++): ?>
                                                        <input type="text" name="member_date_signed_month[]" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="cf-small mt-1 text-center">month</div>
                                            </div>
                                            <div>
                                                <div class="cf-date" aria-label="Member date signed day">
                                                    <?php for ($i = 0; $i < 2; $i++): ?>
                                                        <input type="text" name="member_date_signed_day[]" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="cf-small mt-1 text-center">day</div>
                                            </div>
                                            <div>
                                                <div class="cf-date" aria-label="Member date signed year">
                                                    <?php for ($i = 0; $i < 4; $i++): ?>
                                                        <input type="text" name="member_date_signed_year[]" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="cf-small mt-1 text-center">year</div>
                                            </div>
                                        </div>

                                        <div class="mt-3 grid grid-cols-12 gap-3">
                                            <div class="col-span-12 md:col-span-6">
                                                <div class="cf-small">If member/representative unable to write, put right thumbmark.</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-6">
                                                <div class="cf-small">Member/Representative should be assisted by an HCI representative.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12 md:col-span-6">
                                        <div class="cf-label font-semibold">Date Signed</div>
                                        <div class="mt-2 flex flex-wrap items-start gap-6">
                                            <div>
                                                <div class="cf-date" aria-label="Representative date signed month">
                                                    <?php for ($i = 0; $i < 2; $i++): ?>
                                                        <input type="text" name="rep_date_signed_month[]" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="cf-small mt-1 text-center">month</div>
                                            </div>
                                            <div>
                                                <div class="cf-date" aria-label="Representative date signed day">
                                                    <?php for ($i = 0; $i < 2; $i++): ?>
                                                        <input type="text" name="rep_date_signed_day[]" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="cf-small mt-1 text-center">day</div>
                                            </div>
                                            <div>
                                                <div class="cf-date" aria-label="Representative date signed year">
                                                    <?php for ($i = 0; $i < 4; $i++): ?>
                                                        <input type="text" name="rep_date_signed_year[]" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="cf-small mt-1 text-center">year</div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="cf-section">
                            <div class="cf-section-title">PART IV - EMPLOYER'S CERTIFICATION <span class="font-normal">(for employed members only)</span></div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-12 gap-4 items-start">
                                    <div class="col-span-12">
                                        <div class="cf-label font-semibold">2. Contact No.:</div>
                                        <input class="cf-input mt-2" name="employer_contact_no" type="text" />
                                    </div>
                                </div>

                                <div>
                                    <div class="cf-label font-semibold">3. Business Name:</div>
                                    <input class="cf-input mt-2" name="employer_business_name" type="text" />
                                    <div class="cf-small text-center mt-1">Business Name of Employer</div>
                                </div>

                                <div>
                                    <div class="cf-label font-semibold">4. CERTIFICATION OF EMPLOYER:</div>
                                    <div class="cf-small italic mt-2">
                                        "This is to certify that the required 3/6 monthly premium contributions plus at least 6 months contributions preceding the 3 months qualifying contributions within 12 month period prior to the first day of confinement (sufficient regularity) have been regularly remitted to PhilHealth. Moreover, the information supplied by the member or his/her representative on Part I are consistent with our available records."
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-4 items-end">
                                    <div class="col-span-12 md:col-span-6">
                                        <input class="cf-input" name="employer_signature_name" type="text" />
                                        <div class="cf-small text-center mt-1">Signature Over Printed Name of Employer/Authorized Representative</div>
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <input class="cf-input" name="employer_official_capacity" type="text" />
                                        <div class="cf-small text-center mt-1">Official Capacity/Designation</div>
                                    </div>
                                    <div class="col-span-12 md:col-span-2">
                                        <div class="cf-label font-semibold">Date Signed</div>
                                        <div class="mt-2 grid grid-cols-3 gap-2">
                                            <div class="cf-date" aria-label="Employer date signed month">
                                                <?php for ($i = 0; $i < 2; $i++): ?>
                                                    <input type="text" name="employer_date_signed_month[]" maxlength="1" inputmode="numeric" />
                                                <?php endfor; ?>
                                            </div>
                                            <div class="cf-date" aria-label="Employer date signed day">
                                                <?php for ($i = 0; $i < 2; $i++): ?>
                                                    <input type="text" name="employer_date_signed_day[]" maxlength="1" inputmode="numeric" />
                                                <?php endfor; ?>
                                            </div>
                                            <div class="cf-date" aria-label="Employer date signed year">
                                                <?php for ($i = 0; $i < 4; $i++): ?>
                                                    <input type="text" name="employer_date_signed_year[]" maxlength="1" inputmode="numeric" />
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </form>
                </div>
            </div>
        </main>
    </div>
    <script>
        const isEmbed = <?php echo $isEmbed ? 'true' : 'false'; ?>;
        (function () {
            function safeGet(key) {
                try { return sessionStorage.getItem(key); } catch (e) { return null; }
            }
            if (safeGet('philhealthNewClaimActive') === '1') {
                const p = new URLSearchParams(window.location.search);
                if (p.get('mode') !== 'edit') {
                    p.set('mode', 'edit');
                    window.location.href = window.location.pathname + '?' + p.toString();
                    return;
                }
                const wrap = document.getElementById('modeToggleWrap');
                if (wrap) wrap.style.display = 'none';
            }
        })();

        (function () {
            const form = document.getElementById('cf1Form');
            if (!form) return;

            const isEmbed = <?php echo $isEmbed ? 'true' : 'false'; ?>;
            if (isEmbed) {
                const topActions = document.getElementById('cf1TopActions');
                if (topActions) topActions.style.display = 'none';
            }

            const cssEscape = (s) => {
                if (window.CSS && typeof window.CSS.escape === 'function') return window.CSS.escape(s);
                return (s || '').toString().replace(/[^a-zA-Z0-9_-]/g, function (ch) { return '\\' + ch; });
            };

            const trigger = (el) => {
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            };

            const applyDraft = (draft) => {
                if (!draft || typeof draft !== 'object') return;
                Object.keys(draft).forEach((name) => {
                    if (name === 'member_pin[]') {
                        const raw = draft[name];
                        if (Array.isArray(raw)) {
                            const digits = raw.map(x => (x ?? '').toString()).join('').replace(/\D/g, '');
                            if (digits) {
                                draft['member_pin'] = digits;
                            }
                        }
                    }
                    const candidates = [name];
                    if (name.endsWith('[]')) {
                        candidates.push(name.slice(0, -2));
                    } else {
                        candidates.push(name + '[]');
                    }

                    let els = [];
                    let resolvedName = null;
                    for (const cand of candidates) {
                        const sel = `[name="${cssEscape(cand)}"]`;
                        const found = Array.from(form.querySelectorAll(sel));
                        if (found.length) {
                            els = found;
                            resolvedName = cand;
                            break;
                        }
                    }
                    if (!els.length) return;

                    let v = draft[name];
                    if (resolvedName === 'member_pin' && Array.isArray(v)) {
                        v = v.map(x => (x ?? '').toString()).join('');
                    }
                    if (resolvedName === 'member_pin' && typeof v === 'string') {
                        const digits = v.replace(/\D/g, '');
                        if (digits.length >= 12) {
                            v = digits.slice(0, 2) + '-' + digits.slice(2, 11) + '-' + digits.slice(11, 12);
                        } else {
                            v = digits;
                        }
                    }

                    if (els.length > 1) {
                        const arr = Array.isArray(v) ? v : [v];
                        els.forEach((el, i) => {
                            const type = (el.getAttribute('type') || '').toLowerCase();
                            const nextVal = (arr[i] ?? '').toString();
                            if (type === 'radio') {
                                el.checked = el.value === nextVal;
                                trigger(el);
                                return;
                            }
                            if (type === 'checkbox') {
                                el.checked = nextVal === '1' || nextVal === 'true' || nextVal === 'on';
                                trigger(el);
                                return;
                            }
                            el.value = nextVal;
                            trigger(el);
                        });
                        return;
                    }

                    const el = els[0];
                    const type = (el.getAttribute('type') || '').toLowerCase();
                    if (type === 'radio') {
                        const radiosSel = `[name="${cssEscape(resolvedName || name)}"]`;
                        const radios = Array.from(form.querySelectorAll(radiosSel));
                        radios.forEach(r => {
                            r.checked = r.value === (v ?? '').toString();
                            trigger(r);
                        });
                        return;
                    }
                    if (type === 'checkbox') {
                        el.checked = v === true || v === 1 || v === '1' || v === 'true' || v === 'on';
                        trigger(el);
                        return;
                    }
                    el.value = (v ?? '').toString();
                    trigger(el);
                });
            };

            const readJson = (key) => {
                try {
                    const raw = sessionStorage.getItem(key);
                    if (!raw) return null;
                    const v = JSON.parse(raw);
                    return (v && typeof v === 'object') ? v : null;
                } catch (e) {
                    return null;
                }
            };

            const existing = readJson('philhealthCf1Draft');
            if (existing) applyDraft(existing);

            const params = new URLSearchParams(window.location.search);
            const patientId = params.get('patient_id');
            const pin = params.get('pin');
            if (!patientId && !pin) {
                try {
                    const pid = (sessionStorage.getItem('philhealthPatientId') || '').toString().trim();
                    if (!existing && pid) {
                        (async () => {
                            const qs = 'patient_id=' + encodeURIComponent(pid);
                            const res = await fetch(API_BASE_URL + '/philhealth/member_claim.php?' + qs, { headers: { 'Accept': 'application/json' } });
                            const json = await res.json().catch(() => null);
                            if (!res.ok || !json || !json.ok || !json.forms || !json.forms.cf1 || typeof json.forms.cf1 !== 'object') return;
                            const cf1 = json.forms.cf1;
                            try { sessionStorage.setItem('philhealthCf1Draft', JSON.stringify(cf1)); } catch (e) { }
                            applyDraft(cf1);
                        })().catch(() => { });
                    }
                } catch (e0) {
                }
                return;
            }

            (async () => {
                const qs = patientId ? ('patient_id=' + encodeURIComponent(patientId)) : ('pin=' + encodeURIComponent(pin));
                let cf1 = null;

                try {
                    const res = await fetch(API_BASE_URL + '/philhealth/member_cf1.php?' + qs, { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (res.ok && json && json.ok && json.cf1 && typeof json.cf1 === 'object') {
                        cf1 = json.cf1;
                    }
                } catch (e) {
                }

                if (!cf1) {
                    try {
                        const res = await fetch(API_BASE_URL + '/philhealth/member_claim.php?' + qs, { headers: { 'Accept': 'application/json' } });
                        const json = await res.json().catch(() => null);
                        if (res.ok && json && json.ok && json.forms && json.forms.cf1 && typeof json.forms.cf1 === 'object') {
                            cf1 = json.forms.cf1;
                        }
                    } catch (e) {
                    }
                }

                if (cf1) {
                    try {
                        sessionStorage.setItem('philhealthCf1Draft', JSON.stringify(cf1));
                    } catch (e) {
                    }
                    applyDraft(cf1);
                }
            })().catch(() => { });
        })();

        (function () {
            const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
            if (isEditMode) return;

            document.querySelectorAll('form input, form textarea').forEach(el => {
                const type = (el.getAttribute('type') || '').toLowerCase();
                if (type === 'checkbox' || type === 'radio' || type === 'file') {
                    el.disabled = true;
                } else {
                    el.readOnly = true;
                }
                el.tabIndex = -1;
            });

            document.querySelectorAll('form select').forEach(el => {
                el.disabled = true;
                el.tabIndex = -1;
            });
        })();

        (function () {
            const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
            if (!isEditMode) return;

            const form = document.querySelector('form');
            if (!form) return;

            let t = null;
            const save = () => {
                const fd = new FormData(form);
                const obj = {};
                for (const [k, v] of fd.entries()) {
                    if (obj[k] !== undefined) {
                        if (Array.isArray(obj[k])) obj[k].push(v);
                        else obj[k] = [obj[k], v];
                    } else {
                        obj[k] = v;
                    }
                }
                try {
                    sessionStorage.setItem('philhealthCf1Draft', JSON.stringify(obj));
                } catch (e) {
                }
            };

            const readJson = (key) => {
                try {
                    const raw = sessionStorage.getItem(key);
                    if (!raw) return null;
                    const v = JSON.parse(raw);
                    return (v && typeof v === 'object') ? v : null;
                } catch (e) {
                    return null;
                }
            };

            if (isEmbed) {
                window.addEventListener('message', function (event) {
                    const data = event && event.data;
                    if (!data || data.type !== 'PHILHEALTH_MEMBER_FORMS_SAVE') return;
                    save();
                });
            }

            const scheduleSave = () => {
                if (t) clearTimeout(t);
                t = setTimeout(save, 150);
            };

            form.addEventListener('input', scheduleSave);
            form.addEventListener('change', scheduleSave);

            const nextLink = document.getElementById('nextCfBtn');
            if (nextLink) {
                const validateAll = () => {
                    let ok = true;
                    const els = Array.from(form.querySelectorAll('input, textarea, select'));
                    els.forEach(el => {
                        const tag = (el.tagName || '').toLowerCase();
                        const type = (el.getAttribute('type') || '').toLowerCase();
                        if (el.disabled) return;
                        if (type === 'file' || type === 'hidden' || type === 'button' || type === 'submit' || type === 'reset') return;
                        if (type === 'checkbox' || type === 'radio') return;
                        if (tag === 'select') {
                            const v = (el.value || '').toString().trim();
                            if (v === '') {
                                ok = false;
                                el.style.borderColor = '#ef4444';
                            } else {
                                el.style.borderColor = '';
                            }
                            return;
                        }
                        const v = (el.value || '').toString().trim();
                        if (v === '') {
                            ok = false;
                            el.style.borderColor = '#ef4444';
                        } else {
                            el.style.borderColor = '';
                        }
                    });
                    return ok;
                };

                nextLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    save();
                    if (!validateAll()) {
                        alert('Please complete all fields in CF1 before proceeding to CF2.');
                        return;
                    }
                    try {
                        sessionStorage.setItem('philhealthStepCf1Complete', '1');
                    } catch (e2) {
                    }
                    window.location.href = 'philhealth-cf2.php?mode=edit';
                });
            }

            save();
        })();

        (function () {
            const cancelBtn = document.getElementById('cancelClaimBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => {
                    if (!confirm('Cancel claim registration? This will discard the current claim registration.')) return;

                    fetch(API_BASE_URL + '/philhealth/claim_session.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ action: 'cancel' }),
                    }).catch(() => { });
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
                    window.location.href = 'philhealth-claims.php';
                });
            }

            const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
            const btn = document.getElementById('fillInfoBtn');
            if (!btn) return;

            const getAiSeed = () => {
                let seed = '';
                try {
                    seed = (sessionStorage.getItem('philhealthAiSeed') || '').toString();
                } catch (e) {
                    seed = '';
                }
                if (seed.trim() !== '') return seed.trim();
                seed = Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 10);
                try {
                    sessionStorage.setItem('philhealthAiSeed', seed);
                } catch (e) {
                }
                return seed;
            };

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

            const collect = () => {
                const form = document.querySelector('form');
                const targets = [];
                const fields = [];
                if (!form) return { targets, fields };

                // digit containers
                const digitContainers = Array.from(form.querySelectorAll('.cf-pin, .cf-date'));
                digitContainers.forEach(container => {
                    const inputs = Array.from(container.querySelectorAll('input'));
                    if (!inputs.length) return;
                    const aria = container.getAttribute('aria-label') || '';
                    targets.push({ kind: 'digits', container, inputs });
                    fields.push({ kind: 'digits', label: aria || 'digits', length: inputs.length });
                });

                const skip = (el) => !!el.closest('.cf-pin, .cf-date');

                const radiosByName = new Map();
                Array.from(form.querySelectorAll('input[type="radio"]')).forEach(r => {
                    if (!r.name) return;
                    if (skip(r)) return;
                    if (!radiosByName.has(r.name)) radiosByName.set(r.name, []);
                    radiosByName.get(r.name).push(r);
                });

                Array.from(form.querySelectorAll('input, textarea, select')).forEach(el => {
                    const tag = (el.tagName || '').toLowerCase();
                    const type = (el.getAttribute('type') || '').toLowerCase();
                    if (skip(el)) return;
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

                Array.from(radiosByName.entries()).forEach(([name, radios]) => {
                    const options = radios.map(r => ({ value: r.value || '', label: (r.parentElement && r.parentElement.textContent) ? r.parentElement.textContent.trim() : '' })).filter(o => o.value !== '');
                    if (!options.length) return;
                    targets.push({ kind: 'radio', name, radios, options });
                    fields.push({ kind: 'radio', name, required: radios.some(r => r.required), options });
                });

                return { targets, fields };
            };

            btn.addEventListener('click', async () => {
                if (!isEditMode) {
                    alert('Switch to Edit mode to fill information.');
                    return;
                }
                btn.disabled = true;
                showAiFillOverlay('Filling with AI…', 'Preparing CF1 information');
                try {
                    const seed = getAiSeed();
                    const { targets, fields } = collect();
                    const res = await fetch(API_BASE_URL + '/philhealth/ai_fill.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ page: 'cf1', seed, fields }),
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

                        if (target.kind === 'digits') {
                            const s = (item.value ?? '').toString().replace(/\D/g, '');
                            target.inputs.forEach((el, i) => {
                                el.value = s[i] || '';
                                el.dispatchEvent(new Event('input', { bubbles: true }));
                                el.dispatchEvent(new Event('change', { bubbles: true }));
                            });
                            return;
                        }

                        if (target.kind === 'select') {
                            const v = item.value;
                            const has = target.options.some(o => o.value === v);
                            if (has) target.el.value = v;
                            else if (target.options[0]) target.el.value = target.options[0].value;
                            target.el.dispatchEvent(new Event('input', { bubbles: true }));
                            target.el.dispatchEvent(new Event('change', { bubbles: true }));
                            return;
                        }

                        if (target.kind === 'checkbox') {
                            target.el.checked = !!(item.checked ?? item.value);
                            target.el.dispatchEvent(new Event('change', { bubbles: true }));
                            return;
                        }

                        if (target.kind === 'radio') {
                            const v = item.value;
                            const selected = target.radios.find(r => r.value === v) || target.radios[0];
                            if (selected) {
                                selected.checked = true;
                                selected.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                            return;
                        }

                        if (target.kind === 'input') {
                            target.el.value = (item.value ?? '').toString();
                            target.el.dispatchEvent(new Event('input', { bubbles: true }));
                            target.el.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                } catch (e) {
                    alert(e && e.message ? e.message : 'AI fill failed');
                } finally {
                    btn.disabled = false;
                    hideAiFillOverlay();
                }
            });
        })();
    </script>
</body>
</html>
