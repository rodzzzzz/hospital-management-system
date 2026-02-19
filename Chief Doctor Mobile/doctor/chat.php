<?php
require_once __DIR__ . '/_auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .mobile-surface {
            border-radius: 28px;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        @media (max-width: 520px) {
            html {
                font-size: 15px;
            }

            body {
                padding: 0 !important;
                background: #ffffff !important;
                display: block !important;
            }

            .mobile-frame {
                width: 100vw !important;
                max-width: 100vw !important;
                height: 100dvh !important;
                max-height: 100dvh !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }

            .mobile-frame .px-5 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .mobile-frame .pt-5 {
                padding-top: 1rem !important;
            }

            .mobile-frame .pb-4 {
                padding-bottom: 0.75rem !important;
            }

            .mobile-frame .py-5 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-200 flex items-center justify-center p-4">
    <div class="mobile-frame w-full max-w-[420px] h-[820px] max-h-[calc(100vh-2rem)] bg-white shadow-2xl overflow-hidden mobile-surface relative flex flex-col">
        <div id="mobileDrawerOverlay" class="hidden absolute inset-0 bg-black/40 z-40"></div>
        <div id="mobileDrawer" class="absolute top-0 left-0 h-full w-[280px] bg-white z-50 -translate-x-full transition-transform duration-200 overflow-hidden no-scrollbar flex flex-col">
            <div class="px-5 py-5 bg-emerald-600 text-white">
                <div class="flex items-center justify-between">
                    <div class="font-extrabold">Chief Doctor Menu</div>
                    <button id="drawerClose" type="button" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Close menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-3 text-xs opacity-90">CHIEF DOCTOR</div>
            </div>
            <div class="flex-1 overflow-y-auto no-scrollbar">
                <div class="p-3">
                    <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-house text-emerald-600"></i>
                        <span class="font-semibold">Dashboard</span>
                    </a>
                    <a href="vehicle.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-truck-medical text-emerald-600"></i>
                        <span class="font-semibold">Vehicle</span>
                    </a>
                    <a href="camera.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-video text-emerald-600"></i>
                        <span class="font-semibold">Camera</span>
                    </a>
                    <a href="philhealth.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-file-invoice-dollar text-emerald-600"></i>
                        <span class="font-semibold">PhilHealth</span>
                    </a>
                    <a href="daily-reports.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-chart-line text-emerald-600"></i>
                        <span class="font-semibold">Daily Reports</span>
                    </a>
                    <a href="chat.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
                        <i class="fas fa-comments text-emerald-600"></i>
                        <span class="font-semibold">Chat Messages</span>
                    </a>
                    <a href="patients.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-user-injured text-emerald-600"></i>
                        <span class="font-semibold">Patients</span>
                    </a>
                    <a href="appointments.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-calendar-check text-emerald-600"></i>
                        <span class="font-semibold">Appointments</span>
                    </a>
                    <a href="lab-requests.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-vials text-emerald-600"></i>
                        <span class="font-semibold">Lab Requests</span>
                    </a>
                    <a href="lab-results.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-file-medical text-emerald-600"></i>
                        <span class="font-semibold">Lab Results</span>
                    </a>
                    <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-user-doctor text-emerald-600"></i>
                        <span class="font-semibold">Profile</span>
                    </a>
                </div>
            </div>

            <div class="p-3 border-t border-slate-200">
                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                    <i class="fas fa-right-from-bracket text-emerald-600"></i>
                    <span class="font-semibold">Logout</span>
                </a>
            </div>
        </div>

        <div class="bg-emerald-600 text-white px-5 pt-5 pb-4">
            <div class="flex items-center justify-between">
                <button id="drawerOpen" type="button" class="w-10 h-10 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="text-center">
                    <div class="text-xs font-semibold opacity-90">CHAT MESSAGES</div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="text-right leading-tight">
                        <div class="text-[10px] opacity-90">CHIEF DOCTOR</div>
                        <div class="text-[10px] font-bold opacity-95"><?php echo htmlspecialchars($doctorName !== '' ? $doctorName : 'Chief Doctor', ENT_QUOTES); ?></div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center overflow-hidden">
                        <i class="fas fa-user text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div class="px-5 pt-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-lg font-extrabold text-slate-900">Conversations</div>
                        <div class="mt-0.5 text-xs font-semibold text-slate-500">Clinical departments</div>
                    </div>
                </div>

                <div class="mt-4 flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    <button type="button" data-filter="all" class="filterBtn shrink-0 h-9 px-4 rounded-full bg-slate-900 text-white text-xs font-extrabold">All</button>
                    <button type="button" data-filter="unread" class="filterBtn shrink-0 h-9 px-4 rounded-full bg-white border border-slate-200 text-slate-700 text-xs font-extrabold">Unread</button>
                    <button type="button" data-filter="groups" class="filterBtn shrink-0 h-9 px-4 rounded-full bg-white border border-slate-200 text-slate-700 text-xs font-extrabold">Groups</button>
                    <button type="button" data-filter="archived" class="filterBtn shrink-0 h-9 px-4 rounded-full bg-white border border-slate-200 text-slate-700 text-xs font-extrabold">Archived</button>
                </div>

                <div class="mt-4">
                    <div class="relative">
                        <i class="fas fa-magnifying-glass text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                        <input id="searchInput" type="text" class="w-full h-11 pl-11 pr-4 rounded-2xl border border-slate-200 bg-white text-sm font-semibold text-slate-800 placeholder:text-slate-400" placeholder="Search..." />
                    </div>
                </div>
            </div>

            <div id="convList" class="mt-4 px-5 pb-24"></div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 bg-white border-t border-slate-200">
            <div class="px-6 py-3 flex items-center justify-between">
                <button type="button" class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center" aria-label="Home">
                    <i class="fas fa-house"></i>
                </button>
                <button type="button" class="w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center" aria-label="Chat">
                    <i class="fas fa-comments"></i>
                </button>
                <button type="button" class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center" aria-label="Calls">
                    <i class="fas fa-phone"></i>
                </button>
                <button type="button" class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center" aria-label="Contacts">
                    <i class="fas fa-user-group"></i>
                </button>
                <button type="button" class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center" aria-label="Settings">
                    <i class="fas fa-gear"></i>
                </button>
            </div>
        </div>

        <div id="chatPanel" class="hidden absolute inset-0 bg-[#f7f5ff] z-[60] flex flex-col">
            <div class="bg-white border-b border-slate-200 px-5 pt-5 pb-4">
                <div class="flex items-center justify-between">
                    <button id="chatBack" type="button" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 flex items-center justify-center" aria-label="Back">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center text-xs font-extrabold" id="chatAvatar">DP</div>
                        <div class="leading-tight">
                            <div id="chatTitle" class="text-sm font-extrabold text-slate-900">Department</div>
                            <div id="chatSub" class="text-[10px] font-bold text-emerald-600">Online</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button id="chatCall" type="button" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 flex items-center justify-center" aria-label="Call">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button id="chatMore" type="button" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 flex items-center justify-center" aria-label="More">
                            <i class="fas fa-ellipsis"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="chatMessages" class="flex-1 overflow-y-auto no-scrollbar px-5 py-4 space-y-3"></div>

            <div class="bg-white border-t border-slate-200 px-5 py-4">
                <div class="flex items-end gap-2">
                    <button id="chatPlus" type="button" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 flex items-center justify-center" aria-label="Add">
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="flex-1">
                        <textarea id="chatInput" rows="1" class="w-full min-h-[44px] max-h-[120px] resize-none rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 placeholder:text-slate-400 no-scrollbar" placeholder="Type a message..."></textarea>
                    </div>
                    <button id="chatSend" type="button" class="w-11 h-11 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white flex items-center justify-center" aria-label="Send">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            const openBtn = document.getElementById('drawerOpen');
            const closeBtn = document.getElementById('drawerClose');

            function openDrawer() {
                if (!drawer || !overlay) return;
                drawer.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }

            function closeDrawer() {
                if (!drawer || !overlay) return;
                drawer.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            openBtn?.addEventListener('click', openDrawer);
            closeBtn?.addEventListener('click', closeDrawer);
            overlay?.addEventListener('click', closeDrawer);

            const convList = document.getElementById('convList');
            const searchInput = document.getElementById('searchInput');
            const chatPanel = document.getElementById('chatPanel');
            const chatBack = document.getElementById('chatBack');
            const chatTitle = document.getElementById('chatTitle');
            const chatSub = document.getElementById('chatSub');
            const chatAvatar = document.getElementById('chatAvatar');
            const chatMessages = document.getElementById('chatMessages');
            const chatInput = document.getElementById('chatInput');
            const chatSend = document.getElementById('chatSend');
            const filterBtns = Array.from(document.querySelectorAll('.filterBtn'));

            const departments = [
                { id: 'mss', name: 'MSS/Cashier', icon: 'fa-file-invoice-dollar', status: 'online' },
                { id: 'patients', name: 'Patients', icon: 'fa-user-injured', status: 'online' },
                { id: 'lab', name: 'Lab Test', icon: 'fa-vials', status: 'online' },
                { id: 'xray', name: 'Xray', icon: 'fa-x-ray', status: 'online' },
                { id: 'or', name: 'Operating Room', icon: 'fa-procedures', status: 'online' },
                { id: 'dr', name: 'Delivery Room', icon: 'fa-baby', status: 'online' },
                { id: 'icu', name: 'ICU', icon: 'fa-bed-pulse', status: 'online' },
                { id: 'pharmacy', name: 'Pharmacy', icon: 'fa-pills', status: 'offline' },
            ];

            const sampleThreads = {
                mss: [
                    { from: 'dept', text: 'Good morning Doc. Billing update is ready.', time: '08:12' },
                    { from: 'me', text: 'Send the summary for today.', time: '08:13' },
                    { from: 'dept', text: 'Noted. Today revenue + unpaid totals will be sent.', time: '08:14' },
                ],
                patients: [
                    { from: 'dept', text: 'Hi doctor, I’ve been feeling really tired lately, even after sleeping 7–8 hours. Is that normal?', time: '07:52' },
                    { from: 'me', text: 'Hi, thanks for reaching out. Feeling persistent fatigue can happen for many reasons. Common ones include stress, poor sleep quality, dehydration, low physical activity, or nutritional deficiencies like iron or vitamin D.', time: '07:54' },
                    { from: 'dept', text: 'I’ve also been getting headaches in the afternoon. Could that be related?', time: '07:56' },
                    { from: 'me', text: 'Yes, it could be. Afternoon headaches are often linked to dehydration, eye strain, skipped meals, or caffeine crashes. Stress and tension can also play a role.', time: '07:57' },
                    { from: 'dept', text: 'Should I be worried that it’s something serious?', time: '07:59' },
                    { from: 'me', text: 'Most of the time, symptoms like these are not serious, especially if they’re recent. However, if the fatigue and headaches last more than a few weeks, worsen, or come with other symptoms like dizziness, shortness of breath, or unexplained weight changes, it’s important to get evaluated.', time: '08:01' },
                    { from: 'dept', text: 'What can I do right now to feel better?', time: '08:03' },
                    { from: 'me', text: 'I’d recommend starting with some basics: Drink plenty of water throughout the day, eat regular balanced meals, limit excessive caffeine, take short breaks from screens, and try light exercise like walking. Also, aim for consistent sleep times, not just enough hours.', time: '08:05' },
                    { from: 'dept', text: 'When should I come in for a checkup?', time: '08:07' },
                    { from: 'me', text: 'If symptoms persist beyond 2–3 weeks despite these changes, or if new symptoms appear, schedule an appointment. We may consider blood tests or further evaluation at that point.', time: '08:09' },
                    { from: 'dept', text: 'Okay, thank you doctor. That helps a lot.', time: '08:10' },
                    { from: 'me', text: 'You’re welcome. Keep an eye on how you’re feeling, and don’t hesitate to reach out if things change.', time: '08:11' },
                ],
                lab: [
                    { from: 'dept', text: 'Lab results: 4 pending CBC, 2 urinalysis.', time: '06:41' },
                    { from: 'me', text: 'Prioritize STAT orders.', time: '06:43' },
                    { from: 'dept', text: 'Yes Doc, STAT will be processed first.', time: '06:44' },
                ],
                xray: [
                    { from: 'dept', text: 'Xray room 2 available. Room 1 under cleaning.', time: '09:01' },
                    { from: 'me', text: 'Update me when room 1 is ready.', time: '09:03' },
                    { from: 'dept', text: 'Okay Doc.', time: '09:04' },
                ],
                or: [
                    { from: 'dept', text: 'OR schedule updated: 2 elective cases today.', time: '10:10' },
                    { from: 'me', text: 'Confirm anesthesia coverage.', time: '10:11' },
                    { from: 'dept', text: 'Confirmed. 1 anesthesiologist on standby.', time: '10:12' },
                ],
                dr: [
                    { from: 'dept', text: 'Delivery room: 1 active labor, monitoring vitals.', time: '11:22' },
                    { from: 'me', text: 'Notify for any fetal distress.', time: '11:23' },
                    { from: 'dept', text: 'Understood Doc.', time: '11:24' },
                ],
                icu: [
                    { from: 'dept', text: 'ICU: 1 vent patient stable; 1 admission request.', time: '05:18' },
                    { from: 'me', text: 'Proceed with admission assessment.', time: '05:19' },
                    { from: 'dept', text: 'Noted.', time: '05:20' },
                ],
                pharmacy: [
                    { from: 'dept', text: 'Pharmacy is currently offline (inventory sync).', time: '04:30' },
                    { from: 'me', text: 'Please update once system is restored.', time: '04:32' },
                ],
            };

            function escapeHtml(s) {
                return (s ?? '').toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function statusChip(status) {
                if (status === 'online') return { cls: 'bg-emerald-50 text-emerald-700 border border-emerald-200', label: 'Online' };
                if (status === 'offline') return { cls: 'bg-rose-50 text-rose-700 border border-rose-200', label: 'Offline' };
                return { cls: 'bg-amber-50 text-amber-700 border border-amber-200', label: 'Maintenance' };
            }

            function initials(name) {
                const parts = (name || '').split(' ').filter(Boolean);
                const a = parts[0]?.[0] || 'D';
                const b = parts[1]?.[0] || '';
                return (a + b).toUpperCase();
            }

            let activeFilter = 'all';

            let activeDeptId = null;

            function getLastMessage(deptId) {
                const t = sampleThreads[deptId] || [];
                if (!t.length) return { text: 'No messages yet.', time: '' };
                const last = t[t.length - 1];
                return { text: last.text, time: last.time };
            }

            function getUnreadCount(deptId) {
                const t = sampleThreads[deptId] || [];
                let c = 0;
                for (let i = t.length - 1; i >= 0; i--) {
                    if (t[i].from === 'me') break;
                    c += 1;
                }
                return c;
            }

            function renderFilters() {
                filterBtns.forEach((btn) => {
                    const f = btn.getAttribute('data-filter');
                    const active = f === activeFilter;
                    btn.className = 'filterBtn shrink-0 h-9 px-4 rounded-full text-xs font-extrabold ' + (active ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-700');
                });
            }

            function renderList() {
                if (!convList) return;
                const q = (searchInput?.value || '').trim().toLowerCase();
                let filtered = departments.filter((d) => d.name.toLowerCase().includes(q));

                if (activeFilter === 'unread') {
                    filtered = filtered.filter((d) => getUnreadCount(d.id) > 0);
                }
                if (activeFilter === 'groups') {
                    filtered = [];
                }
                if (activeFilter === 'archived') {
                    filtered = [];
                }

                convList.innerHTML = filtered.map((d) => {
                    const last = getLastMessage(d.id);
                    const unread = getUnreadCount(d.id);
                    return `
<button type="button" data-dept="${escapeHtml(d.id)}" class="w-full text-left bg-white border border-slate-200 rounded-[26px] px-4 py-3 hover:bg-slate-50">
    <div class="flex items-center gap-3">
        <div class="relative">
            <div class="w-12 h-12 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center text-xs font-extrabold">${escapeHtml(initials(d.name))}</div>
            ${unread > 0 ? `<div class=\"absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-violet-600 text-white text-[10px] font-extrabold flex items-center justify-center\">${unread}</div>` : ''}
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-3">
                <div class="text-sm font-extrabold text-slate-900 truncate">${escapeHtml(d.name)}</div>
                <div class="text-[10px] font-bold text-slate-400 shrink-0">${escapeHtml(last.time)}</div>
            </div>
            <div class="mt-0.5 text-xs font-semibold text-slate-500 truncate">${escapeHtml(last.text)}</div>
        </div>
    </div>
</button>`;
                }).join('');

                convList.querySelectorAll('button[data-dept]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const id = btn.getAttribute('data-dept');
                        if (!id) return;
                        openChat(id);
                    });
                });
            }

            function bubble(msg) {
                const mine = msg.from === 'me';
                const wrap = mine ? 'justify-end' : 'justify-start';
                const bubbleCls = mine
                    ? 'bg-violet-600 text-white rounded-3xl rounded-tr-md'
                    : 'bg-white border border-slate-200 text-slate-800 rounded-3xl rounded-tl-md';

                return `
<div class="flex ${wrap}">
    <div class="max-w-[78%]">
        <div class="px-4 py-3 ${bubbleCls}">
            <div class="text-sm font-semibold leading-relaxed">${escapeHtml(msg.text)}</div>
        </div>
        <div class="mt-1 text-[10px] font-bold text-slate-400 ${mine ? 'text-right' : ''}">${escapeHtml(msg.time || '')}</div>
    </div>
</div>`;
            }

            function renderChat(id) {
                const dept = departments.find((d) => d.id === id);
                if (!dept) return;
                const st = statusChip(dept.status);

                if (chatTitle) chatTitle.textContent = dept.name;
                if (chatSub) {
                    chatSub.textContent = st.label;
                    chatSub.className = 'text-[10px] font-bold ' + (dept.status === 'online' ? 'text-emerald-600' : dept.status === 'offline' ? 'text-rose-600' : 'text-amber-600');
                }
                if (chatAvatar) chatAvatar.textContent = initials(dept.name);

                const thread = sampleThreads[id] || [];
                if (chatMessages) {
                    chatMessages.innerHTML = thread.map(bubble).join('');
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }

            function openChat(id) {
                activeDeptId = id;
                renderChat(id);
                chatPanel?.classList.remove('hidden');
            }

            function closeChat() {
                chatPanel?.classList.add('hidden');
                activeDeptId = null;
            }

            function autoGrowTextarea(el) {
                if (!el) return;
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 120) + 'px';
            }

            searchInput?.addEventListener('input', renderList);
            chatBack?.addEventListener('click', closeChat);

            filterBtns.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const f = btn.getAttribute('data-filter');
                    if (!f) return;
                    activeFilter = f;
                    renderFilters();
                    renderList();
                });
            });

            chatInput?.addEventListener('input', () => autoGrowTextarea(chatInput));

            chatSend?.addEventListener('click', () => {
                if (!activeDeptId) return;
                const val = (chatInput?.value || '').trim();
                if (!val) return;
                const t = new Date();
                const hh = String(t.getHours()).padStart(2, '0');
                const mm = String(t.getMinutes()).padStart(2, '0');
                const time = `${hh}:${mm}`;

                const thread = sampleThreads[activeDeptId] || (sampleThreads[activeDeptId] = []);
                thread.push({ from: 'me', text: val, time });
                if (chatInput) {
                    chatInput.value = '';
                    autoGrowTextarea(chatInput);
                }
                renderChat(activeDeptId);
                renderList();
            });

            renderFilters();
            renderList();
            autoGrowTextarea(chatInput);
        })();
    </script>
</body>

</html>
