<?php require_once __DIR__ . '/../auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/../config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse's Notes - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50">
<div class="min-h-screen">
    <?php include __DIR__ . '/includes/ward-sidebar.php'; ?>
    <main class="ml-64 p-8">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nurse's Notes</h1>
                <p class="text-sm text-gray-600 mt-1">Nursing notes and shift reports for admitted patients</p>
            </div>
            <button type="button" id="btnAddNote" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Note
            </button>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500">Total Notes</p><p class="text-2xl font-bold text-gray-900" id="nTotal">0</p></div>
                    <i class="fas fa-notes-medical text-blue-500 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500">AM Shift</p><p class="text-2xl font-bold text-yellow-600" id="nAM">0</p></div>
                    <i class="fas fa-sun text-yellow-400 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500">PM Shift</p><p class="text-2xl font-bold text-orange-600" id="nPM">0</p></div>
                    <i class="fas fa-cloud-sun text-orange-400 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500">Night Shift</p><p class="text-2xl font-bold text-indigo-600" id="nNight">0</p></div>
                    <i class="fas fa-moon text-indigo-400 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Filters + Notes List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="flex flex-wrap items-center gap-3 px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800 mr-auto">Notes Log</h2>
                <input type="text" id="searchNotes" placeholder="Search patient or author..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-52 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <select id="filterNoteWard" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Wards</option>
                    <option value="pediatrics">Pediatrics</option>
                    <option value="obgyn">OB-GYN</option>
                    <option value="medical">Medical</option>
                </select>
                <select id="filterNoteType" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Types</option>
                    <option value="assessment">Assessment</option>
                    <option value="medication">Medication</option>
                    <option value="vital_signs">Vital Signs</option>
                    <option value="labor_progress">Labor Progress</option>
                    <option value="wound_care">Wound Care</option>
                    <option value="feeding">Feeding</option>
                    <option value="general">General</option>
                </select>
                <select id="filterShift" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Shifts</option>
                    <option value="AM">AM (6am-2pm)</option>
                    <option value="PM">PM (2pm-10pm)</option>
                    <option value="Night">Night (10pm-6am)</option>
                </select>
            </div>

            <div id="notesListContainer" class="divide-y divide-gray-100">
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p class="text-sm">Loading notes...</p>
                </div>
            </div>

            <div id="notesEmpty" class="hidden text-center py-12 text-gray-400">
                <i class="fas fa-notes-medical text-4xl mb-3"></i>
                <p class="text-sm">No notes match your filters.</p>
            </div>
        </div>

    </main>
</div>

<!-- Add Nurse Note Modal -->
<div id="addNoteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Add Nurse's Note</h3>
                <button type="button" id="closeNoteModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="addNoteForm" class="p-6">
                <input type="hidden" name="admission_id" value="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ward</label>
                    <select name="ward" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pediatrics">Pediatrics</option>
                        <option value="obgyn">OB-GYN</option>
                        <option value="medical">Medical</option>
                        <option value="icu">ICU</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient Name *</label>
                    <input type="text" name="patient_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Patient full name">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bed / Room</label>
                    <input type="text" name="bed" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g. MAT-01">
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Note Type</label>
                        <select name="note_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="assessment">Assessment</option>
                            <option value="medication">Medication</option>
                            <option value="vital_signs">Vital Signs</option>
                            <option value="labor_progress">Labor Progress</option>
                            <option value="wound_care">Wound Care</option>
                            <option value="feeding">Feeding</option>
                            <option value="general">General Note</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Shift</label>
                        <select name="shift" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="AM">AM (6am-2pm)</option>
                            <option value="PM">PM (2pm-10pm)</option>
                            <option value="Night">Night (10pm-6am)</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note *</label>
                    <textarea name="note_text" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter nursing note..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nurse Name *</label>
                    <input type="text" name="author_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your full name">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelNote" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ─── Sample notes data ────────────────────────────────────────────────────
let notesData = [
    { id:1, patient:'Rosario Dela Cruz', bed:'MAT-01', ward:'obgyn',      type:'labor_progress', shift:'PM',    text:'Patient at 8cm dilation, contractions q3min, FHR 145 bpm, reassured.', author:'Nurse Maribel Santos', timestamp:'2026-02-28 17:30' },
    { id:2, patient:'Maria Santos',      bed:'MAT-02', ward:'obgyn',      type:'assessment',     shift:'PM',    text:'Post-NSD Day 1. Uterus firm and midline. Lochia rubra, moderate. BP 110/70, HR 78. Ambulating with assistance.', author:'Nurse Maribel Santos', timestamp:'2026-02-28 16:00' },
    { id:3, patient:'Ana Reyes',         bed:'GYN-01', ward:'obgyn',      type:'wound_care',     shift:'AM',    text:'Post-op Day 1 wound dressing changed. Wound clean and dry. No signs of infection. Pain score 4/10, given Tramadol as ordered.', author:'Nurse Jocelyn Cruz', timestamp:'2026-02-28 10:00' },
    { id:4, patient:'Baby Santos (F)',   bed:'NUR-01', ward:'obgyn',      type:'feeding',        shift:'AM',    text:'Breastfeeding initiated. Latched well, 15 min each breast. Good tolerance, no regurgitation. Mother coached on proper positioning.', author:'Nurse Jocelyn Cruz', timestamp:'2026-02-28 09:30' },
    { id:5, patient:'Juan Dela Cruz',    bed:'A-02',   ward:'pediatrics', type:'vital_signs',    shift:'PM',    text:'T: 39.5, HR: 120, RR: 28, SpO2: 94%. Platelet count 85k. IV fluids at 40 mL/hr. Tepid sponge bath done.', author:'Nurse Rose Bautista', timestamp:'2026-02-28 15:00' },
    { id:6, patient:'Pedro Lopez',       bed:'MED-01', ward:'medical',    type:'medication',     shift:'AM',    text:'Captopril 25mg given as ordered. BP pre-medication: 180/110. BP post-medication (1hr): 150/90. Patient instructed to avoid salt.', author:'Nurse Anna Garcia', timestamp:'2026-02-28 08:00' },
    { id:7, patient:'Lourdes Fernandez', bed:'MED-02', ward:'medical',    type:'assessment',     shift:'Night', text:'Blood glucose 18 mmol/L. Insulin drip adjusted per sliding scale. Urine output adequate. No acute distress.', author:'Nurse Carlo Reyes', timestamp:'2026-02-28 02:00' },
];

const typeLabels = { assessment:'Assessment', medication:'Medication', vital_signs:'Vital Signs', labor_progress:'Labor Progress', wound_care:'Wound Care', feeding:'Feeding', general:'General' };
const typeColors = {
    assessment:    'bg-blue-100 text-blue-800',
    medication:    'bg-purple-100 text-purple-800',
    vital_signs:   'bg-red-100 text-red-800',
    labor_progress:'bg-pink-100 text-pink-800',
    wound_care:    'bg-orange-100 text-orange-800',
    feeding:       'bg-indigo-100 text-indigo-800',
    general:       'bg-gray-100 text-gray-700',
};
const shiftColors = { AM:'bg-yellow-100 text-yellow-800', PM:'bg-orange-100 text-orange-800', Night:'bg-indigo-100 text-indigo-800' };
const wardLabels  = { pediatrics:'Pediatrics', obgyn:'OB-GYN', medical:'Medical', icu:'ICU' };

function getShiftFromTime(ts) {
    const h = parseInt((ts || '').split(' ')[1]?.split(':')[0] || '0');
    if (h >= 6  && h < 14) return 'AM';
    if (h >= 14 && h < 22) return 'PM';
    return 'Night';
}

function renderNotes(data) {
    const container = document.getElementById('notesListContainer');
    const empty     = document.getElementById('notesEmpty');
    if (!data.length) {
        container.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }
    empty.classList.add('hidden');
    container.innerHTML = data.map(n => {
        const shift = n.shift || getShiftFromTime(n.timestamp);
        return `
        <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-900 text-sm">${n.patient}</span>
                    <span class="text-xs text-gray-500">${n.bed} &bull; ${wardLabels[n.ward] || n.ward}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium ${typeColors[n.type] || 'bg-gray-100 text-gray-700'}">${typeLabels[n.type] || n.type}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium ${shiftColors[shift] || 'bg-gray-100 text-gray-700'}">${shift} Shift</span>
                    <span class="text-xs text-gray-400">${n.timestamp}</span>
                </div>
            </div>
            <p class="text-sm text-gray-700 mb-2 leading-relaxed">${n.text}</p>
            <p class="text-xs text-gray-400"><i class="fas fa-user-nurse mr-1"></i>${n.author}</p>
        </div>`;
    }).join('');
}

function updateSummary(data) {
    document.getElementById('nTotal').textContent = data.length;
    document.getElementById('nAM').textContent    = data.filter(n => (n.shift || getShiftFromTime(n.timestamp)) === 'AM').length;
    document.getElementById('nPM').textContent    = data.filter(n => (n.shift || getShiftFromTime(n.timestamp)) === 'PM').length;
    document.getElementById('nNight').textContent = data.filter(n => (n.shift || getShiftFromTime(n.timestamp)) === 'Night').length;
}

function applyFilters() {
    const search = document.getElementById('searchNotes').value.toLowerCase();
    const ward   = document.getElementById('filterNoteWard').value;
    const type   = document.getElementById('filterNoteType').value;
    const shift  = document.getElementById('filterShift').value;
    let filtered = notesData;
    if (ward  !== 'all') filtered = filtered.filter(n => n.ward  === ward);
    if (type  !== 'all') filtered = filtered.filter(n => n.type  === type);
    if (shift !== 'all') filtered = filtered.filter(n => (n.shift || getShiftFromTime(n.timestamp)) === shift);
    if (search) filtered = filtered.filter(n => n.patient.toLowerCase().includes(search) || n.author.toLowerCase().includes(search) || n.text.toLowerCase().includes(search));
    renderNotes(filtered);
}

document.addEventListener('DOMContentLoaded', function () {
    renderNotes(notesData);
    updateSummary(notesData);

    const addNoteModal = document.getElementById('addNoteModal');

    // Set default shift based on current time
    const h = new Date().getHours();
    const defaultShift = h >= 6 && h < 14 ? 'AM' : h >= 14 && h < 22 ? 'PM' : 'Night';
    addNoteModal.querySelector('[name="shift"]').value = defaultShift;

    document.getElementById('btnAddNote').addEventListener('click', function () {
        addNoteModal.classList.remove('hidden');
    });
    document.getElementById('closeNoteModal').addEventListener('click', () => addNoteModal.classList.add('hidden'));
    document.getElementById('cancelNote').addEventListener('click',     () => addNoteModal.classList.add('hidden'));
    window.addEventListener('click', e => { if (e.target === addNoteModal) addNoteModal.classList.add('hidden'); });

    ['searchNotes','filterNoteWard','filterNoteType','filterShift'].forEach(id => {
        document.getElementById(id).addEventListener('input', applyFilters);
        document.getElementById(id).addEventListener('change', applyFilters);
    });

    document.getElementById('addNoteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

        const payload = {
            admission_id: parseInt(form.querySelector('[name="admission_id"]')?.value || '0'),
            ward:         form.querySelector('[name="ward"]')?.value || '',
            note_type:    form.querySelector('[name="note_type"]')?.value || 'general',
            note_text:    form.querySelector('[name="note_text"]')?.value || '',
            author_name:  form.querySelector('[name="author_name"]')?.value || '',
        };

        fetch(API_BASE_URL + '/ward_management/notes_create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(res => {
            // Also add to local display immediately
            const newNote = {
                id: notesData.length + 1,
                patient: form.querySelector('[name="patient_name"]')?.value || 'Unknown',
                bed:     form.querySelector('[name="bed"]')?.value || '—',
                ward:    payload.ward,
                type:    payload.note_type,
                shift:   form.querySelector('[name="shift"]')?.value || 'AM',
                text:    payload.note_text,
                author:  payload.author_name || 'Staff',
                timestamp: new Date().toLocaleString('sv-SE').replace('T',' ').slice(0,16),
            };
            notesData.unshift(newNote);
            renderNotes(notesData);
            updateSummary(notesData);
            addNoteModal.classList.add('hidden');
            form.reset();
            if (!res.ok) console.warn('API warning:', res.error);
        })
        .catch(() => {
            // Still add locally even if API fails
            const newNote = {
                id: notesData.length + 1,
                patient: form.querySelector('[name="patient_name"]')?.value || 'Unknown',
                bed:     form.querySelector('[name="bed"]')?.value || '—',
                ward:    payload.ward,
                type:    payload.note_type,
                shift:   form.querySelector('[name="shift"]')?.value || 'AM',
                text:    payload.note_text,
                author:  payload.author_name || 'Staff',
                timestamp: new Date().toLocaleString('sv-SE').replace('T',' ').slice(0,16),
            };
            notesData.unshift(newNote);
            renderNotes(notesData);
            updateSummary(notesData);
            addNoteModal.classList.add('hidden');
            form.reset();
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Note';
        });
    });
});
</script>
</body>
</html>
