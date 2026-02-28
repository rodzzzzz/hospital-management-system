<?php
require_once __DIR__ . '/../auth.php';

$authUser = auth_current_user();
$current = basename($_SERVER['PHP_SELF'] ?? '');

$isDoctorContext = ($current === 'doctor.php');

$hrPages = [
    'hr.php',
    'hr-accounts.php',
    'hr-directory.php',
    'hr-scheduling.php',
    'hr-payroll.php',
    'hr-departments.php',
    'hr-reports.php',
];

if (!$authUser) {
    if (in_array($current, $hrPages, true)) {
        header('Location: hr-login.php');
        exit;
    }
    header('Location: login.php');
    exit;
}

$authRoles = $authUser['roles'] ?? [];
if (!is_array($authRoles) || count($authRoles) === 0) {
    header('Location: not-assigned.php');
    exit;
}

$isAdmin = auth_user_has_module($authUser, 'ADMIN');

if (in_array($current, $hrPages, true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'HR')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['er.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'ER')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['clinical-area.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'ER') && !auth_user_has_module($authUser, 'OPD') && !auth_user_has_module($authUser, 'DOCTOR') && !auth_user_has_module($authUser, 'ICU') && !auth_user_has_module($authUser, 'XRAY')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['out-patient-department.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'OPD')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['laboratory.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'LAB')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['icu.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'ICU')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['xray.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'XRAY')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['pharmacy.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'PHARMACY')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['cashier.php', 'cashier2.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'CASHIER')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['doctor.php', 'doctor-approvals.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'DOCTOR')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['operating-room.php', 'delivery-room.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'DOCTOR')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['admissions.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'ADMISSIONS')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['Ward/ward-management.php', 'Ward/index.php', 'Ward/pediatrics-ward.php', 'Ward/obgyn-ward.php', 'Ward/medical-ward.php', 'Ward/ward-census.php', 'Ward/nurses-notes.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'WARD')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['bed-management.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'ADMISSIONS') && !auth_user_has_module($authUser, 'WARD')) {
        header('Location: dashboard.php');
        exit;
    }
}

if (in_array($current, ['discharge.php'], true)) {
    if (!$isAdmin && !auth_user_has_module($authUser, 'ADMISSIONS') && !auth_user_has_module($authUser, 'DOCTOR') && !auth_user_has_module($authUser, 'WARD')) {
        header('Location: dashboard.php');
        exit;
    }
}

$navItems = [
    [
        'href' => 'dashboard.php',
        'label' => 'Dashboard',
        'icon' => 'fas fa-home',
    ],
    [
        'href' => 'patients.php',
        'label' => 'Patients',
        'icon' => 'fas fa-user-injured',
    ],
    [
        'href' => 'medical-records.php',
        'label' => 'Medical Records',
        'icon' => 'fas fa-notes-medical',
    ],
    [
        'href' => 'clinical-area.php',
        'label' => 'Clinical Area',
        'icon' => 'fas fa-hospital',
        'active_pages' => ['clinical-area.php', 'out-patient-department.php', 'er.php', 'operating-room.php', 'delivery-room.php', 'icu.php', 'xray.php'],
    ],
    [
        'href' => 'admissions.php',
        'label' => 'Admissions',
        'icon' => 'fas fa-bed-pulse',
        'active_pages' => ['admissions.php', 'bed-management.php', 'discharge.php'],
    ],
    [
        'href' => 'Ward/index.php',
        'label' => 'Ward Management',
        'icon' => 'fas fa-hospital',
        'active_pages' => ['Ward/ward-management.php', 'Ward/index.php', 'Ward/pediatrics-ward.php', 'Ward/obgyn-ward.php', 'Ward/medical-ward.php', 'Ward/ward-census.php', 'Ward/nurses-notes.php'],
    ],
    [
        'href' => 'dialysis.php',
        'label' => 'Dialysis',
        'icon' => 'fas fa-sync-alt',
        'active_pages' => ['dialysis.php'],
    ],
    [
        'href' => 'laboratory.php',
        'label' => 'Laboratory',
        'icon' => 'fas fa-flask',
        'active_pages' => ['laboratory.php'],
    ],
    [
        'href' => 'cashier.php',
        'label' => 'Cashier',
        'icon' => 'fas fa-cash-register',
    ],
    [
        'href' => 'pharmacy.php',
        'label' => 'Pharmacy',
        'icon' => 'fas fa-pills',
    ],
    [
        'href' => 'price-master.php',
        'label' => 'Price Master',
        'icon' => 'fas fa-tags',
    ],
    [
        'href' => 'inventory.php',
        'label' => 'Inventory',
        'icon' => 'fas fa-boxes',
    ],
    [
        'href' => 'kitchen.php',
        'label' => 'Kitchen',
        'icon' => 'fas fa-utensils',
    ],
    [
        'href' => 'payroll.php',
        'label' => 'Payroll',
        'icon' => 'fas fa-money-check-alt',
    ],
    [
        'href' => 'chat.php',
        'label' => 'Chat Messages',
        'icon' => 'fas fa-comments',
    ],
    [
        'href' => 'hr.php',
        'label' => 'HR',
        'icon' => 'fas fa-user-friends',
    ],
    [
        'href' => 'philhealth-claims.php',
        'label' => 'PhilHealth Claims',
        'icon' => 'fas fa-file-medical-alt',
    ],
];

$navAlways = ['dashboard.php', 'patients.php', 'chat.php'];
$navRequirements = [
    'clinical-area.php' => ['ER', 'OPD', 'DOCTOR', 'ICU', 'XRAY'],
    'admissions.php' => ['ADMISSIONS'],
    'Ward/ward-management.php' => ['WARD', 'ADMISSIONS'],
    'Ward/index.php' => ['WARD', 'ADMISSIONS'],
    'Ward/pediatrics-ward.php' => ['WARD', 'ADMISSIONS'],
    'Ward/obgyn-ward.php' => ['WARD', 'ADMISSIONS'],
    'Ward/medical-ward.php' => ['WARD', 'ADMISSIONS'],
    'Ward/ward-census.php' => ['WARD', 'ADMISSIONS'],
    'Ward/nurses-notes.php' => ['WARD', 'ADMISSIONS'],
    'bed-management.php' => ['ADMISSIONS', 'WARD'],
    'discharge.php' => ['ADMISSIONS', 'DOCTOR', 'WARD'],
    'dialysis.php' => ['LAB'],
    'laboratory.php' => ['LAB'],
    'cashier.php' => ['CASHIER'],
    'pharmacy.php' => ['PHARMACY'],
    'hr.php' => ['HR'],
];

$outerNavItems = array_values(array_filter($navItems, static function ($item) use ($authUser, $isAdmin, $navAlways, $navRequirements) {
    if (!is_array($item)) {
        return false;
    }
    $href = (string)($item['href'] ?? '');
    $path = (string)(parse_url($href, PHP_URL_PATH) ?: $href);
    $base = basename($path);
    if ($base === '') {
        return false;
    }

    if (in_array($base, $navAlways, true)) {
        return true;
    }
    if ($isAdmin) {
        return true;
    }

    $req = $navRequirements[$base] ?? null;
    if (!is_array($req) || count($req) === 0) {
        return false;
    }
    foreach ($req as $m) {
        if (is_string($m) && auth_user_has_module($authUser, $m)) {
            return true;
        }
    }
    return false;
}));

$orPages = [
    'operating-room.php',
];

$orInnerItems = [
    [
        'href' => 'operating-room.php#dashboard',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'operating-room.php#schedule',
        'label' => 'Surgery Schedule',
        'icon' => 'fas fa-calendar-check',
    ],
    [
        'href' => 'operating-room.php#cases',
        'label' => 'Active Cases',
        'icon' => 'fas fa-procedures',
    ],
    [
        'href' => 'operating-room.php#theatres',
        'label' => 'Theatre Status',
        'icon' => 'fas fa-door-open',
    ],
];

$drPages = [
    'delivery-room.php',
];

$drInnerItems = [
    [
        'href' => 'delivery-room.php#dashboard',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'delivery-room.php#labor-queue',
        'label' => 'Labor Queue',
        'icon' => 'fas fa-users',
    ],
    [
        'href' => 'delivery-room.php#deliveries',
        'label' => 'Delivery Records',
        'icon' => 'fas fa-file-medical',
    ],
    [
        'href' => 'delivery-room.php#newborn',
        'label' => 'Newborn Care',
        'icon' => 'fas fa-baby',
    ],
];

$icuPages = [
    'icu.php',
];

$icuInnerItems = [
    [
        'href' => 'icu.php#overview',
        'label' => 'Overview',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'icu.php#patients',
        'label' => 'Patients List',
        'icon' => 'fas fa-user-injured',
    ],
    [
        'href' => 'icu.php#labs',
        'label' => 'Labs / Results',
        'icon' => 'fas fa-flask',
    ],
    [
        'href' => 'icu.php#transfers',
        'label' => 'Transfers / Discharge Planning',
        'icon' => 'fas fa-right-left',
    ],
    [
        'href' => 'icu.php#billing',
        'label' => 'Admit Billing',
        'icon' => 'fas fa-file-invoice-dollar',
    ],
    [
        'href' => 'icu.php#admission-status',
        'label' => 'Admission Status',
        'icon' => 'fas fa-clipboard-check',
    ],
];

$xrayPages = [
    'xray.php',
];

$payrollPages = [
    'payroll.php',
];

$payrollInnerItems = [
    [
        'href' => 'payroll.php#dashboard',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'payroll.php#employees',
        'label' => 'Employees',
        'icon' => 'fas fa-users',
    ],
    [
        'href' => 'payroll.php#payroll-run',
        'label' => 'Payroll Run',
        'icon' => 'fas fa-file-invoice-dollar',
    ],
    [
        'href' => 'payroll.php#approvals',
        'label' => 'Approvals',
        'icon' => 'fas fa-hourglass-half',
    ],
    [
        'href' => 'payroll.php#taxes',
        'label' => 'Taxes & Contributions',
        'icon' => 'fas fa-receipt',
    ],
    [
        'href' => 'payroll.php#reports',
        'label' => 'Reports',
        'icon' => 'fas fa-file-alt',
    ],
    [
        'href' => 'payroll.php#settings',
        'label' => 'Settings',
        'icon' => 'fas fa-gear',
    ],
];

$xrayInnerItems = [
    [
        'href' => 'xray.php#overview',
        'label' => 'Overview',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'xray.php#scheduling',
        'label' => 'Scheduling',
        'icon' => 'fas fa-calendar-check',
    ],
    [
        'href' => 'xray.php#worklist',
        'label' => 'Worklist',
        'icon' => 'fas fa-list-check',
    ],
];

$medicalRecordsInnerItems = [
    [
        'href' => 'medical-records.php#dashboard',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'medical-records.php#patients',
        'label' => 'Patients',
        'icon' => 'fas fa-user-injured',
    ],
    [
        'href' => 'medical-records.php#encounters',
        'label' => 'Encounters',
        'icon' => 'fas fa-calendar-check',
    ],
    [
        'href' => 'medical-records.php#er-forms',
        'label' => 'ER Forms',
        'icon' => 'fas fa-truck-medical',
    ],
    [
        'href' => 'medical-records.php#lab-results',
        'label' => 'Lab Results',
        'icon' => 'fas fa-flask',
    ],
    [
        'href' => 'medical-records.php#resita',
        'label' => 'Receipt',
        'icon' => 'fas fa-receipt',
    ],
    [
        'href' => 'medical-records.php#billing',
        'label' => 'Cashier/Billing',
        'icon' => 'fas fa-cash-register',
    ],
];

$cashierInnerItems = [
    [
        'href' => 'cashier.php#dashboard',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'cashier.php#pending-charges',
        'label' => 'Pending Charges',
        'icon' => 'fas fa-hourglass-half',
    ],
    [
        'href' => 'cashier.php#payments',
        'label' => 'Payments',
        'icon' => 'fas fa-file-invoice-dollar',
    ],
    [
        'label' => 'Treasurer',
        'icon' => 'fas fa-landmark',
        'children' => [
            [
                'href' => 'cashier-treasurer-revenue.php',
                'label' => 'Revenue Data',
                'icon' => 'fas fa-coins',
            ],
            [
                'href' => 'cashier-treasurer-expense.php',
                'label' => 'Expense Data',
                'icon' => 'fas fa-receipt',
            ],
            [
                'href' => 'cashier-treasurer-accounting.php',
                'label' => 'Accounting Data',
                'icon' => 'fas fa-book',
            ],
            [
                'href' => 'cashier-treasurer-budget.php',
                'label' => 'Budget & Planning Data',
                'icon' => 'fas fa-chart-pie',
            ],
            [
                'href' => 'cashier-treasurer-cashbank.php',
                'label' => 'Cash & Bank Data',
                'icon' => 'fas fa-building-columns',
            ],
            [
                'href' => 'cashier-treasurer-compliance.php',
                'label' => 'Compliance & Audit Data',
                'icon' => 'fas fa-clipboard-check',
            ],
        ],
    ],
];

$pharmacyPages = [
    'pharmacy.php',
];

$cashierPages = [
    'cashier.php',
    'cashier-treasurer-revenue.php',
    'cashier-treasurer-expense.php',
    'cashier-treasurer-accounting.php',
    'cashier-treasurer-budget.php',
    'cashier-treasurer-cashbank.php',
    'cashier-treasurer-compliance.php',
];

$pharmacyInnerItems = [
    [
        'href' => 'pharmacy.php#dashboard',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'pharmacy.php#patient-resit',
        'label' => 'Patient Receipt',
        'icon' => 'fas fa-receipt',
    ],
    [
        'href' => 'pharmacy.php#patient-resit-info',
        'label' => 'Patient Receipt Info',
        'icon' => 'fas fa-clipboard-list',
    ],
    [
        'href' => 'pharmacy.php#consultation-notes',
        'label' => 'Consultation Notes',
        'icon' => 'fas fa-notes-medical',
    ],
    [
        'href' => 'pharmacy.php#medicines',
        'label' => 'Medicines',
        'icon' => 'fas fa-capsules',
    ],
];

if ($isDoctorContext) {
    $navItems = [
        [
            'href' => 'doctor.php#lab-requests',
            'label' => 'Lab Requests',
            'icon' => 'fas fa-flask',
        ],
        [
            'href' => 'doctor.php#patients',
            'label' => 'Patients',
            'icon' => 'fas fa-user-injured',
        ],
    ];
}

$philhealthPages = [
    'philhealth-claims.php',
    'philhealth-members.php',
    'philhealth-cf1.php',
    'philhealth-cf2.php',
    'philhealth-cf3.php',
    'philhealth-cf4.php',
];

$patientsPages = [
    'patients.php',
];

$medicalRecordsPages = [
    'medical-records.php',
];

 $chatPages = [
     'chat.php',
 ];

$clinicalAreaPages = [
    'clinical-area.php',
    'out-patient-department.php',
    'er.php',
    'operating-room.php',
    'delivery-room.php',
    'icu.php',
    'xray.php',
];

$dialysisPages = [
    'dialysis.php',
];

$hrInnerItems = [
    [
        'href' => 'hr.php',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'hr-directory.php',
        'label' => 'Staff Directory',
        'icon' => 'fas fa-address-book',
    ],
    [
        'href' => 'hr-accounts.php',
        'label' => 'User Accounts / Access',
        'icon' => 'fas fa-user-shield',
    ],
    [
        'href' => 'hr-scheduling.php',
        'label' => 'Scheduling / Duty Roster',
        'icon' => 'fas fa-calendar-alt',
    ],
    [
        'href' => 'hr-payroll.php',
        'label' => 'Payroll',
        'icon' => 'fas fa-file-invoice-dollar',
    ],
    [
        'href' => 'hr-departments.php',
        'label' => 'Departments & Positions',
        'icon' => 'fas fa-sitemap',
    ],
    [
        'href' => 'hr-reports.php',
        'label' => 'Reports',
        'icon' => 'fas fa-chart-bar',
    ],
];

$patientsInnerItems = [
    [
        'href' => 'patients.php#dashboard',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'patients.php#progress',
        'label' => "Patient's Progress",
        'icon' => 'fas fa-list-check',
    ],
    [
        'href' => 'patients.php#queue',
        'label' => 'Queue',
        'icon' => 'fas fa-user-clock',
    ],
];

$hasErModule = auth_user_has_module($authUser, 'ER');
if ($hasErModule) {
    $patientsInnerItems = array_values(array_filter($patientsInnerItems, static function ($item) {
        $href = (string)($item['href'] ?? '');
        $frag = (string)(parse_url($href, PHP_URL_FRAGMENT) ?: '');
        return $frag !== 'queue';
    }));
}

$philhealthInnerItems = [
    [
        'href' => 'philhealth-claims.php',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'philhealth-members.php',
        'label' => 'Philhealth Member',
        'icon' => 'fas fa-users',
    ],
    [
        'href' => 'philhealth-claims.php?tab=drafts',
        'label' => 'Drafts',
        'icon' => 'fas fa-pen-to-square',
    ],
    [
        'href' => 'philhealth-cf1.php?mode=edit',
        'label' => 'CF1',
        'icon' => 'fas fa-file',
    ],
    [
        'href' => 'philhealth-cf2.php?mode=edit',
        'label' => 'CF2',
        'icon' => 'fas fa-file',
    ],
    [
        'href' => 'philhealth-cf3.php?mode=edit',
        'label' => 'CF3',
        'icon' => 'fas fa-file',
    ],
    [
        'href' => 'philhealth-cf4.php?mode=edit',
        'label' => 'CF4',
        'icon' => 'fas fa-file',
    ],
];

$admissionsPages = [
    'admissions.php',
    'bed-management.php',
    'discharge.php',
];

$admissionsInnerItems = [
    [
        'href' => 'admissions.php#dashboard',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'bed-management.php#housekeeping',
        'label' => 'Housekeeping',
        'icon' => 'fas fa-broom',
    ],
    [
        'type' => 'separator',
        'label' => 'Discharge',
    ],
    [
        'href' => 'discharge.php#dashboard',
        'label' => 'Discharge Dashboard',
        'icon' => 'fas fa-door-open',
    ],
    [
        'href' => 'discharge.php#planning',
        'label' => 'Discharge Planning',
        'icon' => 'fas fa-clipboard-list',
    ],
];

$wardPages = [
    'Ward/ward-management.php',
    'Ward/index.php',
    'Ward/pediatrics-ward.php',
    'Ward/obgyn-ward.php',
    'Ward/medical-ward.php',
    'Ward/ward-census.php',
    'Ward/nurses-notes.php',
];

$wardInnerItems = [
    [
        'href' => 'Ward/index.php',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'Ward/pediatrics-ward.php',
        'label' => 'Pediatrics Ward',
        'icon' => 'fas fa-child',
    ],
    [
        'href' => 'Ward/obgyn-ward.php',
        'label' => 'OB-GYN Ward',
        'icon' => 'fas fa-venus',
    ],
    [
        'href' => 'ward-management.php#surgical',
        'label' => 'Surgical Ward',
        'icon' => 'fas fa-scalpel',
    ],
    [
        'href' => 'ward-management.php#medical',
        'label' => 'Medicine Ward',
        'icon' => 'fas fa-heart-pulse',
    ],
    [
        'href' => 'Ward/ward-census.php',
        'label' => 'Ward Census',
        'icon' => 'fas fa-list-check',
    ],
    [
        'href' => 'Ward/nurses-notes.php',
        'label' => "Nurse's Notes",
        'icon' => 'fas fa-notes-medical',
    ],
    [
        'href' => 'ward-management.php#vitals-monitor',
        'label' => 'Vitals Monitor',
        'icon' => 'fas fa-heart-pulse',
    ],
    [
        'href' => 'ward-management.php#fluid-balance',
        'label' => 'Fluid Balance',
        'icon' => 'fas fa-droplet',
    ],
    [
        'href' => 'ward-management.php#mar',
        'label' => 'Med. Admin (MAR)',
        'icon' => 'fas fa-pills',
    ],
    [
        'href' => 'ward-management.php#shift-report',
        'label' => 'Shift Report',
        'icon' => 'fas fa-file-medical',
    ],
];


$erPages = [
    'er.php',
];

$erInnerItems = [
    [
        'href' => 'er.php#ward',
        'label' => 'Ward',
        'icon' => 'fas fa-bed',
    ],
    [
        'href' => 'er.php#nursing-assessment',
        'label' => 'Nursing Assessment',
        'icon' => 'fas fa-notes-medical',
    ],
    [
        'href' => 'er.php#doctor-feedback',
        'label' => 'Doctor Feedback',
        'icon' => 'fas fa-comment-medical',
    ],
    [
        'href' => 'er.php#np-pa',
        'label' => 'NP/PA Lab Req',
        'icon' => 'fas fa-user-nurse',
    ],
    [
        'href' => 'er.php#lab-request',
        'label' => 'Lab Request',
        'icon' => 'fas fa-file-medical',
    ],
    [
        'href' => 'er.php#requests',
        'label' => 'ER Lab Req',
        'icon' => 'fas fa-list-check',
    ],
    [
        'href' => 'er.php#lab-results',
        'label' => 'Lab Test Result',
        'icon' => 'fas fa-file-medical',
    ],
    [
        'href' => 'er.php#consultation-notes',
        'label' => 'Consultation Notes',
        'icon' => 'fas fa-notes-medical',
    ],
    [
        'href' => 'er.php#clearance',
        'label' => 'Clearance',
        'icon' => 'fas fa-clipboard-check',
    ],
    [
        'href' => 'er.php#xray-results',
        'label' => 'Xray Result',
        'icon' => 'fas fa-x-ray',
    ],
];

$hasErNurse = auth_user_has_role($authUser, 'ER', 'ER Nurse');
$hasErNpPa = auth_user_has_role($authUser, 'ER', 'NP/PA');
if ($hasErNurse || $hasErNpPa) {
    $erInnerItems = array_values(array_filter($erInnerItems, static function ($item) use ($hasErNurse, $hasErNpPa) {
        $href = (string)($item['href'] ?? '');
        $frag = (string)(parse_url($href, PHP_URL_FRAGMENT) ?: '');
        if ($frag === 'lab-request') {
            return $hasErNurse;
        }
        if ($frag === 'np-pa') {
            return $hasErNpPa;
        }
        return true;
    }));
}

$labPages = [
    'laboratory.php',
];

$priceMasterPages = [
    'price-master.php',
];

$labInnerItems = [
    [
        'href' => 'laboratory.php#dashboard',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'laboratory.php#pending',
        'label' => 'Pending Lab Tests',
        'icon' => 'fas fa-hourglass-half',
    ],
    [
        'href' => 'laboratory.php#patient-lab-test',
        'label' => 'Patient Lab Test',
        'icon' => 'fas fa-vial',
    ],
    [
        'href' => 'laboratory.php#lab-test-result',
        'label' => 'Lab Test Result',
        'icon' => 'fas fa-file-medical',
    ],
];

$priceMasterInnerItems = [
    [
        'href' => 'price-master.php#laboratory-fees',
        'label' => 'Laboratory Fees',
        'icon' => 'fas fa-flask',
    ],
    [
        'href' => 'price-master.php#opd-fees',
        'label' => 'OPD Fees',
        'icon' => 'fas fa-stethoscope',
    ],
    [
        'href' => 'price-master.php#radiology-fees',
        'label' => 'Radiology Fees',
        'icon' => 'fas fa-x-ray',
    ],
    [
        'href' => 'price-master.php#procedure-fees',
        'label' => 'Procedure Fees',
        'icon' => 'fas fa-syringe',
    ],
    [
        'href' => 'price-master.php#room-fees',
        'label' => 'Room Fees',
        'icon' => 'fas fa-bed',
    ],
    [
        'href' => 'price-master.php#discounts-packages',
        'label' => 'Discounts / Packages',
        'icon' => 'fas fa-percent',
    ],
];

$doctorInnerItems = [
    [
        'href' => 'doctor.php#lab-requests',
        'label' => 'Lab Requests',
        'icon' => 'fas fa-flask',
    ],
    [
        'href' => 'doctor.php#patients',
        'label' => 'Patients',
        'icon' => 'fas fa-user-injured',
    ],
];

$opdPages = [
    'out-patient-department.php',
];

$opdInnerItems = [
    [
        'href' => 'out-patient-department.php#overview',
        'label' => 'Overview',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'out-patient-department.php#nursing-assessment',
        'label' => 'Nursing Assessment',
        'icon' => 'fas fa-stethoscope',
    ],
    [
        'href' => 'out-patient-department.php#appointments',
        'label' => 'Appointments',
        'icon' => 'fas fa-calendar-check',
    ],
    [
        'href' => 'out-patient-department.php#lab-requests',
        'label' => 'Lab Requests',
        'icon' => 'fas fa-list-check',
    ],
    [
        'href' => 'out-patient-department.php#lab-results',
        'label' => 'Lab Test Result',
        'icon' => 'fas fa-file-medical',
    ],
    [
        'href' => 'out-patient-department.php#xray',
        'label' => 'X-ray',
        'icon' => 'fas fa-x-ray',
    ],
    [
        'href' => 'out-patient-department.php#consultation-notes',
        'label' => 'Consultation Notes',
        'icon' => 'fas fa-notes-medical',
    ],
];

?>

<style>
    :root { font-size: 12px; }

    :root {
        --outer-rail-width: 6rem;
        --inner-sidebar-width: 16rem;
    }

    .outer-nav-item {
        position: relative;
        outline: none;
    }

    .outer-nav-label {
        display: none;
    }

    .outer-nav-item.is-hovered .outer-nav-iconwrap {
        background: #2563eb;
        color: #ffffff;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
    }

    .outer-nav-flyout {
        position: fixed;
        top: 0;
        left: 0;
        height: 2.5rem;
        display: flex;
        align-items: center;
        padding: 0;
        max-width: 0;
        overflow: hidden;
        opacity: 0;
        pointer-events: none;
        white-space: nowrap;
        z-index: 9999;
        background: #2563eb;
        color: #ffffff;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
        transform: translateX(-6px);
        transition: opacity 160ms ease, transform 160ms ease, max-width 220ms ease, padding 220ms ease;
    }

    .outer-nav-flyout.is-visible {
        opacity: 1;
        transform: translateX(0);
        max-width: 220px;
        padding: 0 16px;
    }

    @media (prefers-reduced-motion: reduce) {
        .outer-nav-flyout {
            transition: none;
        }
    }

    main {
        margin-left: var(--outer-rail-width) !important;
    }

    @media (min-width: 1024px) {
        main {
            margin-left: calc(var(--outer-rail-width) + var(--inner-sidebar-width)) !important;
        }
    }
</style>

<div class="fixed inset-y-0 left-0 z-40 flex">
    <aside class="group relative z-50 bg-gray-100 border-r border-gray-200 shadow-xl w-24 overflow-visible">
        <div class="flex flex-col h-full w-24">
            <div class="flex items-center justify-center h-20 border-b border-gray-200">
                <img src="resources/logo.png" alt="Logo" class="h-10 w-10">
            </div>

            <nav class="flex-1 overflow-visible py-2 px-0">
                <div class="h-full overflow-y-auto" style="overflow-x: visible;">
                    <ul class="space-y-1">
                        <?php foreach ($outerNavItems as $item): ?>
                            <?php
                            $href = (string)($item['href'] ?? '');
                            $path = (string)(parse_url($href, PHP_URL_PATH) ?: $href);
                            $base = basename($path);
                            $fragment = (string)(parse_url($href, PHP_URL_FRAGMENT) ?: '');
                            $activePages = $item['active_pages'] ?? null;
                            $isActive = (!$isDoctorContext) && (
                                (is_array($activePages) ? in_array($current, $activePages, true) : ($current === $base))
                            );
                            if (!$isDoctorContext && $base === 'hr.php' && in_array($current, $hrPages, true)) {
                                $isActive = true;
                            }
                            $linkClass = 'outer-nav-item flex items-center justify-center w-full h-10 rounded-lg transition-colors duration-200';
                            ?>
                            <li>
                                <a href="<?php echo htmlspecialchars($href, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($fragment, ENT_QUOTES); ?>" class="<?php echo $linkClass . ($isDoctorContext ? ' doctor-outer-link' : ''); ?>" aria-label="<?php echo htmlspecialchars($item['label'], ENT_QUOTES); ?>">
                                    <span class="outer-nav-iconwrap <?php echo $isActive ? 'flex items-center justify-center w-14 h-10 rounded-lg bg-blue-600 text-white shadow-md' : 'flex items-center justify-center w-14 h-10 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-700'; ?>">
                                        <i class="<?php echo htmlspecialchars($item['icon'], ENT_QUOTES); ?> w-6 text-center text-[18px]"></i>
                                    </span>
                                    <span class="outer-nav-label"><?php echo htmlspecialchars($item['label'], ENT_QUOTES); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>

            <div id="outer-nav-flyout" class="outer-nav-flyout" aria-hidden="true"></div>

            <div class="mt-auto py-4 px-0 border-t border-gray-200">
                <div class="flex items-center justify-center">
                    <img src="resources/doctor.jpg" alt="User" class="w-10 h-10 rounded-full object-cover">
                </div>
            </div>
        </div>

        <div class="absolute inset-y-0 left-full w-56 bg-gray-100 border-r border-gray-200 opacity-0 translate-x-4 pointer-events-none transition duration-200 group-hover:opacity-100 group-hover:translate-x-0 group-hover:pointer-events-auto lg:hidden">
            <div class="flex items-center h-20 px-6">
                <span class="text-xl font-bold text-gray-800 whitespace-nowrap">Hospital</span>
            </div>

            <nav class="flex-1 overflow-y-auto p-4">
                <ul class="space-y-2">
                    <?php foreach ($outerNavItems as $item): ?>
                        <?php
                        $href = (string)($item['href'] ?? '');
                        $path = (string)(parse_url($href, PHP_URL_PATH) ?: $href);
                        $base = basename($path);
                        $activePages = $item['active_pages'] ?? null;
                        $isActive = (!$isDoctorContext) && (
                            (is_array($activePages) ? in_array($current, $activePages, true) : ($current === $base))
                        );
                        if (!$isDoctorContext && $base === 'hr.php' && in_array($current, $hrPages, true)) {
                            $isActive = true;
                        }
                        $flyoutLinkClass = $isActive
                            ? 'flex items-center gap-x-4 px-4 py-3 bg-blue-600 text-white rounded-xl shadow-md'
                            : 'flex items-center gap-x-4 px-4 py-3 text-gray-700 hover:bg-gray-200 rounded-xl transition-colors duration-200';
                        ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($href, ENT_QUOTES); ?>" class="<?php echo $flyoutLinkClass; ?>">
                                <i class="<?php echo htmlspecialchars($item['icon'], ENT_QUOTES); ?> w-6 text-center"></i>
                                <span class="font-medium"><?php echo htmlspecialchars($item['label'], ENT_QUOTES); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <div class="mt-auto p-4 border-t border-gray-200">
                <div class="flex items-center gap-x-3">
                    <img src="resources/doctor.jpg" alt="User" class="w-10 h-10 rounded-full object-cover">
                    <div class="whitespace-nowrap">
                        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars((string)($authUser['full_name'] ?? ($authUser['username'] ?? 'User')), ENT_QUOTES); ?></p>
                        <p class="text-sm text-gray-500"><?php
                            $roleLabel = '';
                            $roles = $authUser['roles'] ?? [];
                            if (is_array($roles) && count($roles) > 0) {
                                $r0 = $roles[0];
                                $m = strtoupper(trim((string)($r0['module'] ?? '')));
                                $r = trim((string)($r0['role'] ?? ''));
                                $roleLabel = ($r !== '') ? ($m . ': ' . $r) : $m;
                            }
                            echo htmlspecialchars($roleLabel !== '' ? $roleLabel : 'User', ENT_QUOTES);
                        ?></p>
                    </div>
                    <button id="btnLogout" type="button" class="ml-auto text-gray-500 hover:text-red-600" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <aside class="hidden lg:flex relative z-40 flex-col bg-gray-100 border-r border-gray-200 w-64">
        <div class="flex items-center h-20 px-6">
            <span class="text-lg font-semibold text-gray-800">Navigation</span>
        </div>
        <div class="px-4 pb-2">
            <div class="relative">
                <input id="innerNavSearch" type="text" placeholder="Search menu..." class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="off" />
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto px-4 pb-4">
            <?php if (in_array($current, $philhealthPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">PhilHealth Claims</div>
                    <nav id="philhealth-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($philhealthInnerItems as $inner): ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $innerPath = parse_url($innerHref, PHP_URL_PATH);
                                $innerBase = basename((string)($innerPath ?: ''));
                                $innerQuery = (string)(parse_url($innerHref, PHP_URL_QUERY) ?: '');
                                $innerQueryArr = [];
                                if ($innerQuery !== '') {
                                    parse_str($innerQuery, $innerQueryArr);
                                }
                                $innerTab = isset($innerQueryArr['tab']) ? (string)$innerQueryArr['tab'] : '';
                                $currentTab = isset($_GET['tab']) ? (string)$_GET['tab'] : '';
                                $innerActive = ($current === $innerBase) && ($innerTab === '' ? ($currentTab === '' || $innerHref === $innerBase) : ($currentTab === $innerTab));
                                $innerClass = $innerActive
                                    ? 'flex items-center gap-x-3 px-3 py-2 bg-blue-600 text-white rounded-lg shadow-md'
                                    : 'flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                        <i class="<?php echo htmlspecialchars($inner['icon'], ENT_QUOTES); ?> w-5 text-center"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars($inner['label'], ENT_QUOTES); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $patientsPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Patients</div>
                    <nav id="patients-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($patientsInnerItems as $inner): ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                $innerClass = 'patients-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                        <i class="<?php echo htmlspecialchars($inner['icon'], ENT_QUOTES); ?> w-5 text-center"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars($inner['label'], ENT_QUOTES); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $medicalRecordsPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Medical Records</div>
                    <nav id="medical-records-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($medicalRecordsInnerItems as $inner): ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                $innerClass = 'medical-records-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                        <i class="<?php echo htmlspecialchars($inner['icon'], ENT_QUOTES); ?> w-5 text-center"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars($inner['label'], ENT_QUOTES); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $clinicalAreaPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Clinical Area</div>
                    <nav id="clinical-area-inner-nav">
                        <ul class="space-y-1">
                            <?php if ($isAdmin || auth_user_has_module($authUser, 'OPD')): ?>
                                <li>
                                    <button type="button" class="clinical-area-toggle w-full flex items-center justify-between gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200" data-target="opd" aria-expanded="false">
                                        <span class="flex items-center gap-x-3">
                                            <i class="fas fa-hospital-user w-5 text-center"></i>
                                            <span class="font-medium">OPD</span>
                                        </span>
                                        <i class="fas fa-chevron-down text-xs opacity-70"></i>
                                    </button>
                                    <div id="clinical-opd-panel" class="clinical-area-panel overflow-hidden" style="max-height: 0;">
                                        <nav id="opd-inner-nav" class="mt-1 ml-4">
                                            <ul class="space-y-1">
                                                <?php foreach ($opdInnerItems as $inner): ?>
                                                    <?php
                                                    $innerHref = (string)($inner['href'] ?? '');
                                                    $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                                    $innerClass = 'opd-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                                    ?>
                                                    <li>
                                                        <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                                            <i class="<?php echo htmlspecialchars($inner['icon'] ?? '', ENT_QUOTES); ?> w-5 text-center"></i>
                                                            <span class="font-medium"><?php echo htmlspecialchars($inner['label'] ?? '', ENT_QUOTES); ?></span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </li>
                            <?php endif; ?>

                            <?php if ($isAdmin || auth_user_has_module($authUser, 'ICU')): ?>
                                <li>
                                    <button type="button" class="clinical-area-toggle w-full flex items-center justify-between gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200" data-target="icu" aria-expanded="false">
                                        <span class="flex items-center gap-x-3">
                                            <i class="fas fa-procedures w-5 text-center"></i>
                                            <span class="font-medium">ICU</span>
                                        </span>
                                        <i class="fas fa-chevron-down text-xs opacity-70"></i>
                                    </button>
                                    <div id="clinical-icu-panel" class="clinical-area-panel overflow-hidden" style="max-height: 0;">
                                        <nav id="icu-inner-nav" class="mt-1 ml-4">
                                            <ul class="space-y-1">
                                                <?php foreach ($icuInnerItems as $inner): ?>
                                                    <?php
                                                    $innerHref = (string)($inner['href'] ?? '');
                                                    $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                                    $innerClass = 'icu-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                                    ?>
                                                    <li>
                                                        <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                                            <i class="<?php echo htmlspecialchars($inner['icon'] ?? '', ENT_QUOTES); ?> w-5 text-center"></i>
                                                            <span class="font-medium"><?php echo htmlspecialchars($inner['label'] ?? '', ENT_QUOTES); ?></span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </li>
                            <?php endif; ?>

                            <?php if ($isAdmin || auth_user_has_module($authUser, 'XRAY')): ?>
                                <li>
                                    <button type="button" class="clinical-area-toggle w-full flex items-center justify-between gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200" data-target="xray" aria-expanded="false">
                                        <span class="flex items-center gap-x-3">
                                            <i class="fas fa-x-ray w-5 text-center"></i>
                                            <span class="font-medium">Xray</span>
                                        </span>
                                        <i class="fas fa-chevron-down text-xs opacity-70"></i>
                                    </button>
                                    <div id="clinical-xray-panel" class="clinical-area-panel overflow-hidden" style="max-height: 0;">
                                        <nav id="xray-inner-nav" class="mt-1 ml-4">
                                            <ul class="space-y-1">
                                                <?php foreach ($xrayInnerItems as $inner): ?>
                                                    <?php
                                                    $innerHref = (string)($inner['href'] ?? '');
                                                    $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                                    $innerClass = 'xray-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                                    ?>
                                                    <li>
                                                        <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                                            <i class="<?php echo htmlspecialchars($inner['icon'] ?? '', ENT_QUOTES); ?> w-5 text-center"></i>
                                                            <span class="font-medium"><?php echo htmlspecialchars($inner['label'] ?? '', ENT_QUOTES); ?></span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </li>
                            <?php endif; ?>

                            <?php if ($isAdmin || auth_user_has_module($authUser, 'ER')): ?>
                                <li>
                                    <button type="button" class="clinical-area-toggle w-full flex items-center justify-between gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200" data-target="er" aria-expanded="false">
                                        <span class="flex items-center gap-x-3">
                                            <i class="fas fa-truck-medical w-5 text-center"></i>
                                            <span class="font-medium">ER</span>
                                        </span>
                                        <i class="fas fa-chevron-down text-xs opacity-70"></i>
                                    </button>
                                    <div id="clinical-er-panel" class="clinical-area-panel overflow-hidden" style="max-height: 0;">
                                        <nav id="er-inner-nav" class="mt-1 ml-4">
                                            <ul class="space-y-1">
                                                <?php foreach ($erInnerItems as $inner): ?>
                                                    <?php
                                                    $innerHref = (string)($inner['href'] ?? '');
                                                    $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                                    $innerClass = 'er-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                                    ?>
                                                    <li>
                                                        <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                                            <i class="<?php echo htmlspecialchars($inner['icon'] ?? '', ENT_QUOTES); ?> w-5 text-center"></i>
                                                            <span class="font-medium"><?php echo htmlspecialchars($inner['label'] ?? '', ENT_QUOTES); ?></span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </li>
                            <?php endif; ?>

                            <?php if ($isAdmin || auth_user_has_module($authUser, 'DOCTOR')): ?>
                                <li>
                                    <button type="button" class="clinical-area-toggle w-full flex items-center justify-between gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200" data-target="or" aria-expanded="false">
                                        <span class="flex items-center gap-x-3">
                                            <i class="fas fa-syringe w-5 text-center"></i>
                                            <span class="font-medium">Operating Room</span>
                                        </span>
                                        <i class="fas fa-chevron-down text-xs opacity-70"></i>
                                    </button>
                                    <div id="clinical-or-panel" class="clinical-area-panel overflow-hidden" style="max-height: 0;">
                                        <nav id="or-inner-nav" class="mt-1 ml-4">
                                            <ul class="space-y-1">
                                                <?php foreach ($orInnerItems as $inner): ?>
                                                    <?php
                                                    $innerHref = (string)($inner['href'] ?? '');
                                                    $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                                    $innerClass = 'or-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                                    ?>
                                                    <li>
                                                        <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                                            <i class="<?php echo htmlspecialchars($inner['icon'] ?? '', ENT_QUOTES); ?> w-5 text-center"></i>
                                                            <span class="font-medium"><?php echo htmlspecialchars($inner['label'] ?? '', ENT_QUOTES); ?></span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </li>
                            <?php endif; ?>

                            <?php if ($isAdmin || auth_user_has_module($authUser, 'DOCTOR')): ?>
                                <li>
                                    <button type="button" class="clinical-area-toggle w-full flex items-center justify-between gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200" data-target="dr" aria-expanded="false">
                                        <span class="flex items-center gap-x-3">
                                            <i class="fas fa-baby w-5 text-center"></i>
                                            <span class="font-medium">Delivery Room</span>
                                        </span>
                                        <i class="fas fa-chevron-down text-xs opacity-70"></i>
                                    </button>
                                    <div id="clinical-dr-panel" class="clinical-area-panel overflow-hidden" style="max-height: 0;">
                                        <nav id="dr-inner-nav" class="mt-1 ml-4">
                                            <ul class="space-y-1">
                                                <?php foreach ($drInnerItems as $inner): ?>
                                                    <?php
                                                    $innerHref = (string)($inner['href'] ?? '');
                                                    $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                                    $innerClass = 'dr-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                                    ?>
                                                    <li>
                                                        <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                                            <i class="<?php echo htmlspecialchars($inner['icon'] ?? '', ENT_QUOTES); ?> w-5 text-center"></i>
                                                            <span class="font-medium"><?php echo htmlspecialchars($inner['label'] ?? '', ENT_QUOTES); ?></span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $admissionsPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Admissions</div>
                    <nav id="admissions-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($admissionsInnerItems as $inner): ?>
                                <?php if (isset($inner['type']) && $inner['type'] === 'separator'): ?>
                                <li class="pt-3 pb-1">
                                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-3"><?php echo htmlspecialchars($inner['label'], ENT_QUOTES); ?></div>
                                </li>
                                <?php else: ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                $innerClass = 'admissions-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                        <i class="<?php echo htmlspecialchars($inner['icon'], ENT_QUOTES); ?> w-5 text-center"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars($inner['label'], ENT_QUOTES); ?></span>
                                    </a>
                                </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $wardPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Ward Management</div>
                    <nav id="ward-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($wardInnerItems as $inner): ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $isActive = ($innerHref === $current || basename($innerHref) === $current);
                                $innerClass = 'ward-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' . ($isActive ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-200');
                                ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                        <i class="<?php echo htmlspecialchars($inner['icon'], ENT_QUOTES); ?> w-5 text-center"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars($inner['label'], ENT_QUOTES); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $dialysisPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Dialysis</div>
                    <nav id="dialysis-inner-nav">
                        <ul class="space-y-1">
                            <li>
                                <a href="dialysis.php" class="flex items-center gap-x-3 px-3 py-2 <?php echo ($current === 'dialysis.php') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-200'; ?> rounded-lg transition-colors duration-200">
                                    <i class="fas fa-chart-line w-5 text-center"></i>
                                    <span class="font-medium">Overview</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $labPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Laboratory</div>
                    <nav id="lab-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($labInnerItems as $inner): ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                $innerClass = 'lab-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                        <i class="<?php echo htmlspecialchars($inner['icon'], ENT_QUOTES); ?> w-5 text-center"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars($inner['label'], ENT_QUOTES); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $priceMasterPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Price Master</div>
                    <nav id="price-master-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($priceMasterInnerItems as $inner): ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                $innerClass = 'price-master-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                        <i class="<?php echo htmlspecialchars($inner['icon'], ENT_QUOTES); ?> w-5 text-center"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars($inner['label'], ENT_QUOTES); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $pharmacyPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Pharmacy</div>
                    <nav id="pharmacy-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($pharmacyInnerItems as $inner): ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                $innerClass = 'pharmacy-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                        <i class="<?php echo htmlspecialchars($inner['icon'], ENT_QUOTES); ?> w-5 text-center"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars($inner['label'], ENT_QUOTES); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $cashierPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Cashier</div>
                    <nav id="cashier-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($cashierInnerItems as $inner): ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                $innerClass = 'cashier-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                $hasChildren = is_array($inner['children'] ?? null);
                                ?>
                                <?php if (!$hasChildren): ?>
                                    <li>
                                        <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                            <i class="<?php echo htmlspecialchars($inner['icon'], ENT_QUOTES); ?> w-5 text-center"></i>
                                            <span class="font-medium"><?php echo htmlspecialchars($inner['label'], ENT_QUOTES); ?></span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <details class="group">
                                            <summary class="flex items-center justify-between gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200 cursor-pointer select-none">
                                                <span class="flex items-center gap-x-3">
                                                    <i class="<?php echo htmlspecialchars((string)($inner['icon'] ?? ''), ENT_QUOTES); ?> w-5 text-center"></i>
                                                    <span class="font-medium"><?php echo htmlspecialchars((string)($inner['label'] ?? ''), ENT_QUOTES); ?></span>
                                                </span>
                                                <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-200 group-open:rotate-180"></i>
                                            </summary>
                                            <ul class="mt-1 ml-5 pl-3 border-l border-gray-200 space-y-1">
                                                <?php foreach ((array)$inner['children'] as $child): ?>
                                                    <?php
                                                    $childHref = (string)($child['href'] ?? '');
                                                    $childFragment = (string)(parse_url($childHref, PHP_URL_FRAGMENT) ?: '');
                                                    $childClass = 'cashier-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                                    ?>
                                                    <li>
                                                        <a href="<?php echo htmlspecialchars($childHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($childFragment, ENT_QUOTES); ?>" class="<?php echo $childClass; ?>">
                                                            <i class="<?php echo htmlspecialchars((string)($child['icon'] ?? ''), ENT_QUOTES); ?> w-5 text-center"></i>
                                                            <span class="font-medium"><?php echo htmlspecialchars((string)($child['label'] ?? ''), ENT_QUOTES); ?></span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </details>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $chatPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Chat Messages</div>
                    <nav id="chat-inner-nav">
                        <div class="px-2 pb-2">
                            <button type="button" class="w-full flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-50 text-left" data-key="announcements" data-type="announcements">
                                <div class="relative">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-700">AN</div>
                                    <div class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">Announcements</div>
                                    <div class="text-xs text-gray-500">Start conversation</div>
                                </div>
                            </button>

                            <button type="button" class="w-full mt-2 flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-50 text-left" data-key="ER" data-type="department" data-module="ER">
                                <div class="relative">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-700">ER</div>
                                    <div class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">ER</div>
                                    <div class="text-xs text-gray-500">Start conversation</div>
                                </div>
                            </button>

                            <button type="button" class="w-full mt-2 flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-50 text-left" data-key="OPD" data-type="department" data-module="OPD">
                                <div class="relative">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-700">OP</div>
                                    <div class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">OPD</div>
                                    <div class="text-xs text-gray-500">Start conversation</div>
                                </div>
                            </button>

                            <button type="button" class="w-full mt-2 flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-50 text-left" data-key="LAB" data-type="department" data-module="LAB">
                                <div class="relative">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-700">LA</div>
                                    <div class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">LAB</div>
                                    <div class="text-xs text-gray-500">Start conversation</div>
                                </div>
                            </button>

                            <button type="button" class="w-full mt-2 flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-50 text-left" data-key="PHARMACY" data-type="department" data-module="PHARMACY">
                                <div class="relative">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-700">PH</div>
                                    <div class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">PHARMACY</div>
                                    <div class="text-xs text-gray-500">Start conversation</div>
                                </div>
                            </button>

                            <button type="button" class="w-full mt-2 flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-50 text-left" data-key="CASHIER" data-type="department" data-module="CASHIER">
                                <div class="relative">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-700">CA</div>
                                    <div class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">CASHIER</div>
                                    <div class="text-xs text-gray-500">Start conversation</div>
                                </div>
                            </button>
                        </div>
                    </nav>
                </div>
            <?php elseif (in_array($current, $payrollPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">Payroll</div>
                    <nav id="payroll-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($payrollInnerItems as $inner): ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $innerFragment = (string)(parse_url($innerHref, PHP_URL_FRAGMENT) ?: '');
                                $innerClass = 'payroll-inner-link flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" data-hash="<?php echo htmlspecialchars($innerFragment, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                        <i class="<?php echo htmlspecialchars((string)($inner['icon'] ?? ''), ENT_QUOTES); ?> w-5 text-center"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars((string)($inner['label'] ?? ''), ENT_QUOTES); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php elseif (in_array($current, $hrPages, true)): ?>
                <div class="pt-4">
                    <div class="text-xs font-semibold tracking-wider text-gray-400 uppercase px-2 pb-2">HR</div>
                    <nav id="hr-inner-nav">
                        <ul class="space-y-1">
                            <?php foreach ($hrInnerItems as $inner): ?>
                                <?php
                                $innerHref = (string)($inner['href'] ?? '');
                                $innerPath = parse_url($innerHref, PHP_URL_PATH);
                                $innerBase = basename((string)($innerPath ?: ''));
                                $innerActive = ($current === $innerBase);
                                $innerClass = $innerActive
                                    ? 'flex items-center gap-x-3 px-3 py-2 bg-blue-600 text-white rounded-lg shadow-md'
                                    : 'flex items-center gap-x-3 px-3 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                                ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($innerHref, ENT_QUOTES); ?>" class="<?php echo $innerClass; ?>">
                                        <i class="<?php echo htmlspecialchars((string)($inner['icon'] ?? ''), ENT_QUOTES); ?> w-5 text-center"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars((string)($inner['label'] ?? ''), ENT_QUOTES); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php else: ?>
                <div class="pt-4"></div>
            <?php endif; ?>
        </div>
    </aside>
</div>

<script>
    (function () {
        var flyout = document.getElementById('outer-nav-flyout');
        if (!flyout) return;

        var activeEl = null;

        function hide() {
            if (activeEl) activeEl.classList.remove('is-hovered');
            activeEl = null;
            flyout.classList.remove('is-visible');
        }

        function showFor(el) {
            if (!el) return;
            var label = el.getAttribute('aria-label') || '';
            if (!label) return;

            if (activeEl && activeEl !== el) activeEl.classList.remove('is-hovered');
            activeEl = el;
            activeEl.classList.add('is-hovered');

            flyout.textContent = label;

            var iconWrap = el.querySelector('.outer-nav-iconwrap');
            var rect = (iconWrap || el).getBoundingClientRect();

            flyout.style.top = (rect.top + rect.height / 2) + 'px';
            flyout.style.left = (rect.right - 1) + 'px';
            flyout.style.transform = 'translateY(-50%) translateX(-6px)';

            flyout.classList.add('is-visible');

            window.requestAnimationFrame(function () {
                if (!activeEl) return;
                flyout.style.transform = 'translateY(-50%) translateX(0)';
            });
        }

        function closestItem(target) {
            return target && target.closest ? target.closest('.outer-nav-item') : null;
        }

        document.addEventListener('mouseenter', function (e) {
            var el = closestItem(e.target);
            if (!el) return;
            showFor(el);
        }, true);

        document.addEventListener('mouseleave', function (e) {
            var el = closestItem(e.target);
            if (!el) return;
            hide();
        }, true);

        document.addEventListener('focusin', function (e) {
            var el = closestItem(e.target);
            if (!el) return;
            showFor(el);
        });

        document.addEventListener('focusout', function (e) {
            var el = closestItem(e.target);
            if (!el) return;
            hide();
        });

        window.addEventListener('scroll', hide, true);
        window.addEventListener('resize', hide);
    })();

    (function () {
        var input = document.getElementById('innerNavSearch');
        if (!input) return;
        var aside = input.closest ? input.closest('aside') : null;
        if (!aside) return;

        function applyFilter() {
            var q = (input.value || '').toString().trim().toLowerCase();
            var lis = aside.querySelectorAll('nav ul > li');
            lis.forEach(function (li) {
                if (!q) {
                    li.classList.remove('hidden');
                    return;
                }
                var text = (li.textContent || '').toString().replace(/\s+/g, ' ').trim().toLowerCase();
                if (text.indexOf(q) !== -1) li.classList.remove('hidden');
                else li.classList.add('hidden');
            });
        }

        input.addEventListener('input', applyFilter);
        input.addEventListener('keydown', function (e) {
            if (e && e.key === 'Escape') {
                input.value = '';
                applyFilter();
            }
        });
    })();

    (function () {
        var wrapper = document.getElementById('dialysis-inner-nav');
        if (!wrapper) return;

        function safeGet(key) {
            try { return sessionStorage.getItem(key); } catch (e) { return null; }
        }

        function safeSet(key, value) {
            try { sessionStorage.setItem(key, value); } catch (e) { }
        }

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        function panelEl(key) {
            return null;
        }

        function toggleEl(key) {
            return wrapper.querySelector('button.dialysis-toggle[data-target="' + key + '"]');
        }

        function isOpen(panel) {
            if (!panel) return false;
            var mh = panel.style.maxHeight || '';
            return mh !== '' && mh !== '0px' && mh !== '0';
        }

        function setOpen(key, open) {
            var panel = panelEl(key);
            var btn = toggleEl(key);
            if (!panel || !btn) return;

            panel.style.transition = 'max-height 300ms ease';
            if (open) {
                panel.style.maxHeight = panel.scrollHeight + 'px';
                btn.setAttribute('aria-expanded', 'true');
            } else {
                panel.style.maxHeight = '0px';
                btn.setAttribute('aria-expanded', 'false');
            }
        }

        function readOpenKeys() {
            var raw = safeGet('dialysisOpenPanels');
            if (!raw) return [];
            try {
                var arr = JSON.parse(raw);
                if (!Array.isArray(arr)) return [];
                return arr.map(function (x) { return (x || '').toString(); }).filter(Boolean);
            } catch (e) {
                return [];
            }
        }

        function writeOpenKeys(keys) {
            var uniq = [];
            keys.forEach(function (k) {
                k = (k || '').toString();
                if (!k) return;
                if (uniq.indexOf(k) !== -1) return;
                uniq.push(k);
            });
            safeSet('dialysisOpenPanels', JSON.stringify(uniq));
        }

        function persistKey(key, open) {
            var keys = readOpenKeys();
            var idx = keys.indexOf(key);
            if (open && idx === -1) keys.push(key);
            if (!open && idx !== -1) keys.splice(idx, 1);
            writeOpenKeys(keys);
        }

        wrapper.querySelectorAll('button.dialysis-toggle[data-target]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var key = (btn.getAttribute('data-target') || '').toString();
                if (!key) return;
                var panel = panelEl(key);
                var open = isOpen(panel);
                setOpen(key, !open);
                persistKey(key, !open);
            });
        });

        (function restoreOpenState() {
            var keys = readOpenKeys();
            keys.forEach(function (k) {
                setOpen(k, true);
            });

            var page = getPage();
            if (page === 'laboratory.php') setOpen('laboratory', true);
        })();

        window.addEventListener('resize', function () {
            ['laboratory'].forEach(function (k) {
                var panel = panelEl(k);
                if (!panel) return;
                if (isOpen(panel)) panel.style.maxHeight = panel.scrollHeight + 'px';
            });
        });
    })();

    (function () {
        var nav = document.getElementById('or-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'operating-room.php') {
            var links = nav.querySelectorAll('a.or-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'or-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';

            var links = nav.querySelectorAll('a.or-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'or-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
    })();

    (function () {
        var nav = document.getElementById('payroll-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'payroll.php') {
            var links = nav.querySelectorAll('a.payroll-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'payroll-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';

            var links = nav.querySelectorAll('a.payroll-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'payroll-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
        nav.addEventListener('click', function () {
            window.setTimeout(setActiveFromHash, 0);
        });
    })();

    (function () {
        var nav = document.getElementById('icu-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'icu.php') {
            var links = nav.querySelectorAll('a.icu-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'icu-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'overview';

            var links = nav.querySelectorAll('a.icu-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'icu-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
        nav.addEventListener('click', function () {
            window.setTimeout(setActiveFromHash, 0);
        });
    })();

    (function () {
        var nav = document.getElementById('xray-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'xray.php') {
            var links = nav.querySelectorAll('a.xray-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'xray-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'overview';

            var links = nav.querySelectorAll('a.xray-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'xray-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
        nav.addEventListener('click', function () {
            window.setTimeout(setActiveFromHash, 0);
        });
    })();

    (function () {
        var nav = document.getElementById('dr-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'delivery-room.php') {
            var links = nav.querySelectorAll('a.dr-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'dr-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';

            var links = nav.querySelectorAll('a.dr-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'dr-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
    })();

    (function () {
        var wrapper = document.getElementById('clinical-area-inner-nav');
        if (!wrapper) return;

        function safeGet(key) {
            try { return sessionStorage.getItem(key); } catch (e) { return null; }
        }

        function safeSet(key, value) {
            try { sessionStorage.setItem(key, value); } catch (e) { }
        }

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        function panelEl(key) {
            if (key === 'er') return document.getElementById('clinical-er-panel');
            if (key === 'or') return document.getElementById('clinical-or-panel');
            if (key === 'dr') return document.getElementById('clinical-dr-panel');
            if (key === 'icu') return document.getElementById('clinical-icu-panel');
            if (key === 'xray') return document.getElementById('clinical-xray-panel');
            return document.getElementById('clinical-opd-panel');
        }

        function toggleEl(key) {
            return wrapper.querySelector('button.clinical-area-toggle[data-target="' + key + '"]');
        }

        function isOpen(panel) {
            if (!panel) return false;
            var mh = panel.style.maxHeight || '';
            return mh !== '' && mh !== '0px' && mh !== '0';
        }

        function setOpen(key, open) {
            var panel = panelEl(key);
            var btn = toggleEl(key);
            if (!panel || !btn) return;

            panel.style.transition = 'max-height 300ms ease';
            if (open) {
                panel.style.maxHeight = panel.scrollHeight + 'px';
                btn.setAttribute('aria-expanded', 'true');
            } else {
                panel.style.maxHeight = '0px';
                btn.setAttribute('aria-expanded', 'false');
            }
        }

        function toggle(key) {
            var panel = panelEl(key);
            var open = isOpen(panel);
            setOpen(key, !open);
        }

        function readOpenKeys() {
            var raw = safeGet('clinicalAreaOpenPanels');
            if (!raw) return [];
            try {
                var arr = JSON.parse(raw);
                if (!Array.isArray(arr)) return [];
                return arr.map(function (x) { return (x || '').toString(); }).filter(Boolean);
            } catch (e) {
                return [];
            }
        }

        function writeOpenKeys(keys) {
            var uniq = [];
            keys.forEach(function (k) {
                k = (k || '').toString();
                if (!k) return;
                if (uniq.indexOf(k) !== -1) return;
                uniq.push(k);
            });
            safeSet('clinicalAreaOpenPanels', JSON.stringify(uniq));
        }

        function persistKey(key, open) {
            var keys = readOpenKeys();
            var idx = keys.indexOf(key);
            if (open && idx === -1) keys.push(key);
            if (!open && idx !== -1) keys.splice(idx, 1);
            writeOpenKeys(keys);
        }

        wrapper.querySelectorAll('button.clinical-area-toggle[data-target]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var key = (btn.getAttribute('data-target') || '').toString();
                if (!key) return;
                toggle(key);
                var panel = panelEl(key);
                persistKey(key, isOpen(panel));
            });
        });

        (function restoreOpenState() {
            var keys = readOpenKeys();
            keys.forEach(function (k) {
                setOpen(k, true);
            });

            var page = getPage();
            if (page === 'out-patient-department.php') setOpen('opd', true);
            else if (page === 'er.php') setOpen('er', true);
            else if (page === 'operating-room.php') setOpen('or', true);
            else if (page === 'delivery-room.php') setOpen('dr', true);
            else if (page === 'icu.php') setOpen('icu', true);
            else if (page === 'xray.php') setOpen('xray', true);
        })();

        window.addEventListener('resize', function () {
            ['opd', 'er', 'or', 'dr', 'icu', 'xray'].forEach(function (k) {
                var panel = panelEl(k);
                if (!panel) return;
                if (isOpen(panel)) panel.style.maxHeight = panel.scrollHeight + 'px';
            });
        });
    })();

    (function () {
        var nav = document.getElementById('philhealth-inner-nav');
        if (!nav) return;

        function safeGet(key) {
            try { return sessionStorage.getItem(key); } catch (e) { return null; }
        }

        function clearPhilhealthDraft() {
            try {
                sessionStorage.removeItem('philhealthPatientId');
                sessionStorage.removeItem('philhealthCf1Draft');
                sessionStorage.removeItem('philhealthCf2Draft');
                sessionStorage.removeItem('philhealthCf3Draft');
                sessionStorage.removeItem('philhealthCf4Draft');
                sessionStorage.removeItem('philhealthNewClaimActive');
                sessionStorage.removeItem('philhealthStepCf1Complete');
                sessionStorage.removeItem('philhealthStepCf2Complete');
                sessionStorage.removeItem('philhealthStepCf3Complete');
                sessionStorage.removeItem('philhealthStepCf4Complete');
            } catch (e) {
            }
        }

        async function fetchClaimActive() {
            try {
                var res = await fetch(API_BASE_URL + '/philhealth/claim_session.php', { headers: { 'Accept': 'application/json' } });
                var json = await res.json().catch(function () { return null; });
                if (!res.ok || !json || !json.ok) return false;
                return !!json.active;
            } catch (e) {
                return false;
            }
        }

        function has(key) {
            return safeGet(key) === '1';
        }

        var claimActive = null;
        fetchClaimActive().then(function (active) {
            claimActive = !!active;
        });

        function isRegistrationLocked() {
            var path = '';
            try { path = (window.location && window.location.pathname ? window.location.pathname : '').toString().toLowerCase(); } catch (e0) { path = ''; }
            var onCfPage = (path.indexOf('philhealth-cf1.php') !== -1 || path.indexOf('philhealth-cf2.php') !== -1 || path.indexOf('philhealth-cf3.php') !== -1 || path.indexOf('philhealth-cf4.php') !== -1);
            if (!onCfPage) return false;
            if (safeGet('philhealthNewClaimActive') !== '1') return false;
            if (claimActive === false) return false;
            return true;
        }

        function isAllowedDuringRegistration(href) {
            if (!href) return false;
            if (href.indexOf('#') === 0) return true;
            if (href.indexOf('javascript:') === 0) return true;
            return (
                href.indexOf('philhealth-cf1.php') !== -1 ||
                href.indexOf('philhealth-cf2.php') !== -1 ||
                href.indexOf('philhealth-cf3.php') !== -1 ||
                href.indexOf('philhealth-cf4.php') !== -1
            );
        }

        function guardMessage() {
            alert('Claim registration is not complete. Please finish the registration or cancel it before navigating to another page.');
        }

        document.addEventListener('click', function (e) {
            if (!isRegistrationLocked()) return;
            var a = e.target && e.target.closest ? e.target.closest('a') : null;
            if (!a) return;
            var href = (a.getAttribute('href') || '').toString();
            if (!href) return;

            if (isAllowedDuringRegistration(href)) {
                try { sessionStorage.setItem('philhealthAllowUnloadOnce', '1'); } catch (e0) { }
                return;
            }

            e.preventDefault();
            guardMessage();
        }, true);

        window.addEventListener('beforeunload', function (e) {
            if (!isRegistrationLocked()) return;
            try {
                if (sessionStorage.getItem('philhealthAllowUnloadOnce') === '1') {
                    sessionStorage.removeItem('philhealthAllowUnloadOnce');
                    return;
                }
            } catch (e0) {
            }
            e.preventDefault();
            e.returnValue = '';
        });

        function canGo(href) {
            if (href.indexOf('philhealth-cf1.php') !== -1) return true;
            if (href.indexOf('philhealth-cf2.php') !== -1) return has('philhealthStepCf1Complete');
            if (href.indexOf('philhealth-cf3.php') !== -1) return has('philhealthStepCf1Complete') && has('philhealthStepCf2Complete');
            if (href.indexOf('philhealth-cf4.php') !== -1) return has('philhealthStepCf1Complete') && has('philhealthStepCf2Complete') && has('philhealthStepCf3Complete');
            return true;
        }

        nav.addEventListener('click', function (e) {
            var a = e.target && e.target.closest ? e.target.closest('a') : null;
            if (!a) return;
            var href = a.getAttribute('href') || '';
            if (href.indexOf('philhealth-cf') !== 0) return;

            function readBasicPatientId() {
                try {
                    var pid = (sessionStorage.getItem('philhealthPatientId') || '').toString().trim();
                    if (!pid) return null;
                    return pid;
                } catch (e2) {
                    return null;
                }
            }

            function buildHrefWithContext(originalHref) {
                try {
                    var url = new URL(originalHref, window.location.href);
                    if (!url.searchParams.get('mode')) url.searchParams.set('mode', 'edit');
                    if (!url.searchParams.get('patient_id')) {
                        var pid = readBasicPatientId();
                        if (pid) url.searchParams.set('patient_id', pid);
                    }
                    return url.pathname.replace(/^.*\//, '') + (url.search || '');
                } catch (e3) {
                    return originalHref;
                }
            }

            var proceed = function () {
                if (claimActive !== true) {
                    alert('Please start or resume a claim from the PhilHealth Dashboard before opening CF forms.');
                    window.location.href = 'philhealth-claims.php';
                    return;
                }
                if (canGo(href)) return;
                alert('Please complete the previous form before proceeding.');
            };

            if (claimActive === null) {
                e.preventDefault();
                fetchClaimActive().then(function (active) {
                    claimActive = !!active;
                    proceed();
                    if (claimActive && canGo(href)) window.location.href = buildHrefWithContext(href);
                    if (!claimActive) window.location.href = 'philhealth-claims.php';
                });
                return;
            }

            if (!claimActive) {
                e.preventDefault();
                alert('Please start or resume a claim from the PhilHealth Dashboard before opening CF forms.');
                window.location.href = 'philhealth-claims.php';
                return;
            }

            var nextHref = buildHrefWithContext(href);
            if (nextHref !== href) {
                a.setAttribute('href', nextHref);
                href = nextHref;
            }

            if (canGo(href)) return;
            e.preventDefault();
            alert('Please complete the previous form before proceeding.');
        });
    })();

    (function () {
        var nav = document.getElementById('opd-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'out-patient-department.php') {
            var links = nav.querySelectorAll('a.opd-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'opd-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'overview';

            var links = nav.querySelectorAll('a.opd-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'opd-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
        nav.addEventListener('click', function () {
            window.setTimeout(setActiveFromHash, 0);
        });
    })();

    (function () {
        var nav = document.getElementById('er-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'er.php') {
            var links = nav.querySelectorAll('a.er-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'er-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'new';

            var links = nav.querySelectorAll('a.er-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'er-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
    })();

    (function () {
        var nav = document.getElementById('price-master-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'price-master.php') {
            var links = nav.querySelectorAll('a.price-master-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'price-master-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'laboratory-fees';

            var links = nav.querySelectorAll('a.price-master-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'price-master-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
        nav.addEventListener('click', function () {
            window.setTimeout(setActiveFromHash, 0);
        });
    })();

    (function () {
        var nav = document.getElementById('medical-records-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'medical-records.php') {
            var links = nav.querySelectorAll('a.medical-records-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'medical-records-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';

            var links = nav.querySelectorAll('a.medical-records-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'medical-records-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
    })();

    (function () {
        var nav = document.getElementById('cashier-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        function setInactive() {
            var links = nav.querySelectorAll('a.cashier-inner-link');
            links.forEach(function (a) {
                a.className = 'cashier-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';
            if (h === 'invoices') h = 'payments';

            var links = nav.querySelectorAll('a.cashier-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'cashier-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });

            try {
                var active = nav.querySelector('a.cashier-inner-link[data-hash="' + String(h).replace(/"/g, '') + '"]');
                if (active) {
                    var details = active.closest('details');
                    if (details) details.open = true;
                }
            } catch (e1) {
            }
        }

        function setActiveFromPage() {
            var page = getPage();
            var links = nav.querySelectorAll('a.cashier-inner-link');
            var active = null;
            links.forEach(function (a) {
                var href = (a.getAttribute('href') || '').toString();
                var path = (href.split('?')[0] || '').split('#')[0];
                var base = (path || '').replace(/^.*\//, '');
                var isActive = base && base === page;
                if (isActive) active = a;
                a.className = 'cashier-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });

            try {
                if (active) {
                    var details = active.closest('details');
                    if (details) details.open = true;
                }
            } catch (e1) {
            }
        }

        var page = getPage();
        var isCashier = page === 'cashier.php';
        var isTreasurer = page.indexOf('cashier-treasurer-') === 0;
        if (!isCashier && !isTreasurer) {
            setInactive();
            return;
        }

        if (isCashier) {
            setActiveFromHash();
            window.addEventListener('hashchange', setActiveFromHash);
        } else {
            setActiveFromPage();
        }
    })();

    (function () {
        var nav = document.getElementById('pharmacy-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'pharmacy.php') {
            var links = nav.querySelectorAll('a.pharmacy-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'pharmacy-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';

            var links = nav.querySelectorAll('a.pharmacy-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'pharmacy-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
    })();

    (function () {
        var nav = document.getElementById('patients-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'patients.php') {
            var links = nav.querySelectorAll('a.patients-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'patients-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';

            var links = nav.querySelectorAll('a.patients-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'patients-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
    })();

    (function () {
        var nav = document.getElementById('lab-inner-nav');
        if (!nav) return;

        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }

        if (getPage() !== 'laboratory.php') {
            var links = nav.querySelectorAll('a.lab-inner-link[data-hash]');
            links.forEach(function (a) {
                a.className = 'lab-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-200';
            });
            return;
        }

        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';

            var links = nav.querySelectorAll('a.lab-inner-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                a.className = 'lab-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-gray-200');
            });
        }

        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
    })();

    (function () {
        function getHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            return h || 'lab-requests';
        }

        function setOuterActive() {
            var h = getHash();
            var links = document.querySelectorAll('a.doctor-outer-link[data-hash]');
            links.forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var isActive = target === h;
                var icon = a.querySelector('.outer-nav-iconwrap');
                if (!icon) return;
                icon.className = 'outer-nav-iconwrap flex items-center justify-center w-14 h-10 rounded-lg ' + (isActive
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700');
            });
        }

        setOuterActive();
        window.addEventListener('hashchange', function () {
            setOuterActive();
        });
    })();

    (function () {
        var btn = document.getElementById('btnLogout');
        if (!btn) return;

        btn.addEventListener('click', function () {
            var isHrPage = <?php echo in_array($current, $hrPages, true) ? 'true' : 'false'; ?>;
            var target = isHrPage ? 'hr-login.php' : 'login.php';

            fetch('auth-logout.php', {
                method: 'POST',
                headers: { 'Accept': 'application/json' }
            }).catch(function () { }).finally(function () {
                window.location.href = target;
            });
        });
    })();

    (function () {
        // Handle chat button clicks in inner sidebar
        var chatButtons = document.querySelectorAll('#chat-inner-nav button[data-type]');
        chatButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                // Remove active state from all buttons
                chatButtons.forEach(function (b) {
                    b.classList.remove('bg-blue-50', 'border', 'border-blue-200');
                    b.classList.add('hover:bg-gray-50');
                });
                
                // Add active state to clicked button
                btn.classList.add('bg-blue-50', 'border', 'border-blue-200');
                btn.classList.remove('hover:bg-gray-50');
                
                // Trigger the chat functionality if the main chat page is loaded
                if (typeof window.openConversation === 'function') {
                    var type = btn.dataset.type || '';
                    var key = btn.dataset.key || '';
                    var mod = btn.dataset.module || '';
                    var item = {
                        key: key,
                        type: type,
                        label: type === 'announcements' ? 'Announcements' : mod,
                        module: mod || null,
                        unread: 0,
                    };
                    window.openConversation(item);
                }
            });
        });
    })();

    (function () {
        var nav = document.getElementById('admissions-inner-nav');
        if (!nav) return;
        function getPage() {
            var p = '';
            try { p = (window.location && window.location.pathname ? window.location.pathname : '').toString(); } catch (e0) { p = ''; }
            p = (p.split('?')[0] || '').split('#')[0];
            return (p || '').replace(/^.*\//, '');
        }
        function setActiveFromHash() {
            var h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';
            nav.querySelectorAll('a.admissions-inner-link[data-hash]').forEach(function (a) {
                var target = (a.getAttribute('data-hash') || '').toString();
                var href = a.getAttribute('href') || '';
                var isCurrentPage = href.includes(getPage() + '#');
                var isActive = isCurrentPage && target === h;
                a.className = 'admissions-inner-link flex items-center gap-x-3 px-3 py-2 rounded-lg transition-colors duration-200 ' + (isActive ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-200');
            });
        }
        setActiveFromHash();
        window.addEventListener('hashchange', setActiveFromHash);
        nav.addEventListener('click', function () { window.setTimeout(setActiveFromHash, 0); });
    })();

    // Ward navigation - file-based, no hash navigation needed

</script>
