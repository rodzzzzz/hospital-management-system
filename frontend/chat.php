<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Messages - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include __DIR__ . '/includes/websocket-client.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        <!-- Main Content -->
        <main class="ml-16 lg:ml-80 flex h-screen bg-gray-50">
            <section class="flex-1 flex flex-col bg-white">
                <div class="h-16 px-6 border-b bg-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div id="chatHeaderAvatar" class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-700">--</div>
                        <div>
                            <div id="chatHeaderTitle" class="text-sm font-semibold text-gray-900">Select a department</div>
                            <div id="chatSendAsRow" class="hidden"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-folder"></i>
                        </button>
                        <button class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 bg-gray-50 overflow-y-auto">
                    <div id="chatEmptyState" class="h-full flex items-center justify-center">
                        <div class="text-center">
                            <div id="chatEmptyTitle" class="text-xs font-semibold text-gray-700">--</div>
                            <div class="text-xs text-gray-500">Send the first message.</div>
                        </div>
                    </div>
                    <div id="chatMessages" class="hidden p-6 space-y-4"></div>
                </div>

                <div class="bg-white border-t p-4">
                    <div class="flex items-center gap-3">
                        <button id="chatAttachBtn" class="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <div class="flex-1">
                            <input id="chatMessageInput" type="text" placeholder="Type a message" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button id="chatSendBtn" class="w-10 h-10 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        // File Upload Preview
        document.getElementById('chatAttachBtn').addEventListener('click', function() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*,.pdf,.doc,.docx,.xls,.xlsx';
            input.click();

            input.onchange = function() {
                if (input.files && input.files[0]) {
                    showNotification('File ready to upload: ' + input.files[0].name);
                }
            };
        });

        // Notification Function
        function showNotification(message, type = 'success') {
            return;
        }

        // Message Send Function
        (function () {
            const apiBase = API_BASE_URL + '/chat';

            const deptListEl = document.querySelector('#chat-inner-nav .px-2');
            const headerTitleEl = document.getElementById('chatHeaderTitle');
            const headerAvatarEl = document.getElementById('chatHeaderAvatar');
            const sendAsRowEl = document.getElementById('chatSendAsRow');
            const emptyStateEl = document.getElementById('chatEmptyState');
            const emptyTitleEl = document.getElementById('chatEmptyTitle');
            const messagesEl = document.getElementById('chatMessages');
            const inputEl = document.getElementById('chatMessageInput');
            const sendBtnEl = document.getElementById('chatSendBtn');

            const searchInput = document.querySelector('#innerNavSearch');

            const fallbackItems = [
                { key: 'announcements', type: 'announcements', label: 'Announcements', module: null, unread: 0 },
                { key: 'ER', type: 'department', label: 'ER', module: 'ER', unread: 0 },
                { key: 'OPD', type: 'department', label: 'OPD', module: 'OPD', unread: 0 },
                { key: 'LAB', type: 'department', label: 'LAB', module: 'LAB', unread: 0 },
                { key: 'PHARMACY', type: 'department', label: 'PHARMACY', module: 'PHARMACY', unread: 0 },
                { key: 'CASHIER', type: 'department', label: 'CASHIER', module: 'CASHIER', unread: 0 },
            ];

            const state = {
                me: null,
                items: [],
                active: null,
                lastId: 0,
                polling: null,
                refresh: null,
                filter: '',
            };

            function escapeHtml(s) {
                return String(s)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function initials(label) {
                const x = String(label || '').trim();
                if (!x) return '--';
                const parts = x.split(/\s+/).filter(Boolean);
                if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
                return (parts[0][0] + parts[1][0]).toUpperCase();
            }

            function setComposerEnabled(enabled) {
                inputEl.disabled = !enabled;
                sendBtnEl.disabled = !enabled;
                if (!enabled) {
                    sendBtnEl.classList.add('opacity-50');
                    sendBtnEl.classList.add('cursor-not-allowed');
                } else {
                    sendBtnEl.classList.remove('opacity-50');
                    sendBtnEl.classList.remove('cursor-not-allowed');
                }
            }

            async function apiGet(path) {
                const res = await fetch(`${path}`, {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });
                const data = await res.json().catch(() => null);
                if (!res.ok || !data || data.ok !== true) {
                    const err = (data && data.error) ? data.error : `Request failed (${res.status})`;
                    throw new Error(err);
                }
                return data;
            }

            async function apiPost(path, body) {
                const res = await fetch(`${path}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(body || {}),
                });
                const data = await res.json().catch(() => null);
                if (!res.ok || !data || data.ok !== true) {
                    const err = (data && data.error) ? data.error : `Request failed (${res.status})`;
                    throw new Error(err);
                }
                return data;
            }

            function renderSendAsSelect() {
                sendAsSelectEl.innerHTML = '';
                const mods = (state.me && state.me.send_as_modules) ? state.me.send_as_modules : [];

                if (sendAsRowEl) {
                    sendAsRowEl.style.display = (mods.length >= 2) ? '' : 'none';
                }

                for (const m of mods) {
                    const opt = document.createElement('option');
                    opt.value = m;
                    opt.textContent = m;
                    sendAsSelectEl.appendChild(opt);
                }
                if (mods.length === 0) {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'N/A';
                    sendAsSelectEl.appendChild(opt);
                }
            }

            function defaultSenderModule() {
                const mods = (state.me && state.me.send_as_modules) ? state.me.send_as_modules : [];
                if (mods.length === 1) {
                    return mods[0];
                }
                return (sendAsSelectEl && sendAsSelectEl.value) ? sendAsSelectEl.value : '';
            }

            function activeKey() {
                if (!state.active) return '';
                if (state.active.type === 'announcements') return 'announcements';
                return state.active.module || '';
            }

            function renderDeptList() {
                const container = document.querySelector('#chat-inner-nav .px-2');
                if (!container) return;
                
                container.innerHTML = '';
                const f = state.filter.trim().toUpperCase();

                for (const item of state.items) {
                    const label = item.label || '';
                    if (f && String(label).toUpperCase().indexOf(f) === -1) {
                        continue;
                    }

                    const isActive = activeKey() === item.key;
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'w-full mt-2 flex items-center gap-3 px-3 py-3 rounded-lg text-left ' +
                        (isActive ? 'bg-blue-50 border border-blue-200' : 'hover:bg-gray-50');
                    btn.dataset.key = item.key;
                    btn.dataset.type = item.type;
                    btn.dataset.module = item.module || '';

                    const left = document.createElement('div');
                    left.className = 'relative';

                    const avatar = document.createElement('div');
                    avatar.className = 'w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-700';
                    avatar.textContent = initials(label);
                    left.appendChild(avatar);

                    const dot = document.createElement('div');
                    dot.className = 'absolute bottom-0 right-0 w-2 h-2 bg-green-500 border-2 border-white rounded-full';
                    left.appendChild(dot);

                    const mid = document.createElement('div');
                    mid.className = 'flex-1 min-w-0';

                    const title = document.createElement('div');
                    title.className = 'text-sm font-medium text-gray-900';
                    title.textContent = label;

                    const subtitle = document.createElement('div');
                    subtitle.className = 'text-xs text-gray-500';
                    subtitle.textContent = 'Start conversation';

                    mid.appendChild(title);
                    mid.appendChild(subtitle);

                    const right = document.createElement('div');
                    right.className = 'flex items-center gap-2';
                    if ((item.unread || 0) > 0) {
                        const badge = document.createElement('span');
                        badge.className = 'min-w-5 h-5 px-2 text-xs flex items-center justify-center bg-blue-600 text-white rounded-full';
                        badge.textContent = String(item.unread);
                        right.appendChild(badge);
                    }

                    btn.appendChild(left);
                    btn.appendChild(mid);
                    btn.appendChild(right);

                    btn.addEventListener('click', function () {
                        openConversation(item);
                    });

                    container.appendChild(btn);
                }
            }

            function bindStaticDeptListClicks() {
                const buttons = document.querySelectorAll('#chat-inner-nav button[data-type]');
                buttons.forEach((btn) => {
                    btn.addEventListener('click', function () {
                        const type = btn.dataset.type || '';
                        const key = btn.dataset.key || '';
                        const mod = btn.dataset.module || '';
                        const item = {
                            key: key,
                            type: type,
                            label: type === 'announcements' ? 'Announcements' : mod,
                            module: mod || null,
                            unread: 0,
                        };
                        openConversation(item);
                    });
                });
            }

            function showEmpty(label) {
                emptyTitleEl.textContent = label || '--';
                emptyStateEl.classList.remove('hidden');
                messagesEl.classList.add('hidden');
                messagesEl.innerHTML = '';
            }

            function showMessages() {
                emptyStateEl.classList.add('hidden');
                messagesEl.classList.remove('hidden');
            }

            function renderMessage(msg) {
                const wrap = document.createElement('div');
                wrap.className = msg.is_me ? 'flex justify-end' : 'flex justify-start';

                const bubbleWrap = document.createElement('div');
                bubbleWrap.className = msg.is_me ? 'max-w-[70%] text-right' : 'max-w-[70%] text-left';

                const meta = document.createElement('div');
                meta.className = 'text-xs text-gray-500 mb-1';
                const role = String(msg.sender_role || '').trim();
                const who = msg.is_me ? 'You' : String(msg.sender_name || '').trim();
                const ctx = role !== '' ? role : String(msg.sender_module || '').trim();
                meta.textContent = ctx !== '' ? (`${who} (${ctx})`) : who;

                const bubble = document.createElement('div');
                bubble.className = msg.is_me
                    ? 'inline-block bg-blue-600 text-white rounded-2xl rounded-tr-md px-4 py-2'
                    : 'inline-block bg-white text-gray-800 rounded-2xl rounded-tl-md px-4 py-2 border border-gray-200';
                bubble.innerHTML = escapeHtml(msg.body);

                bubbleWrap.appendChild(meta);
                bubbleWrap.appendChild(bubble);

                wrap.appendChild(bubbleWrap);
                return wrap;
            }

            function personaPool() {
                return {
                    ER: [
                        { name: 'Mitsury', role: 'ER Nurse' },
                        { name: 'Jasper', role: 'ER Supervisor' },
                    ],
                    OPD: [
                        { name: 'Mary Rose', role: 'OPD Supervisor' },
                        { name: 'Kane', role: 'OPD Medtech' },
                    ],
                    LAB: [
                        { name: 'Aira', role: 'Lab Supervisor' },
                        { name: 'Noah', role: 'Lab Medtech' },
                    ],
                    PHARMACY: [
                        { name: 'Camille', role: 'Pharmacist' },
                        { name: 'Jude', role: 'Pharmacy Assistant' },
                    ],
                    CASHIER: [
                        { name: 'Bianca', role: 'Cashier Supervisor' },
                        { name: 'Liam', role: 'Cashier Clerk' },
                    ],
                    HR: [
                        { name: 'Sofia', role: 'HR Officer' },
                        { name: 'Ethan', role: 'HR Assistant' },
                    ],
                };
            }

            function myRoleForModule(module) {
                const roles = (state.me && Array.isArray(state.me.roles)) ? state.me.roles : [];
                const needle = String(module || '').trim().toUpperCase();
                for (const r of roles) {
                    if (!r) continue;
                    const m = String(r.module || '').trim().toUpperCase();
                    if (m === needle) {
                        const rr = String(r.role || '').trim();
                        if (rr) return rr;
                    }
                }
                return '';
            }

            function sampleMessagesFor(targetModule, label) {
                const pools = personaPool();
                const mine = myDefaultModule();
                const meName = (state.me && state.me.full_name) ? String(state.me.full_name) : 'You';
                const myRole = mine ? myRoleForModule(mine) : '';

                const target = String(targetModule || label || '--').trim().toUpperCase();
                const targetPool = pools[target] || [
                    { name: `${target || 'Department'} User 1`, role: 'Staff' },
                    { name: `${target || 'Department'} User 2`, role: 'Staff' },
                ];

                const a = targetPool[0] || { name: `${target} User 1`, role: 'Staff' };
                const b = targetPool[1] || a;

                const introDept = String(label || target || '--').trim();
                return [
                    { id: -1, sender_module: target || introDept, sender_name: a.name, sender_role: a.role, body: `Hi, this is ${introDept}. Do you have an update?`, created_at: '', is_me: false },
                    { id: -2, sender_module: mine || '', sender_name: meName, sender_role: myRole, body: 'Acknowledged. We are checking now.', created_at: '', is_me: true },
                    { id: -3, sender_module: target || introDept, sender_name: b.name, sender_role: b.role, body: 'Thanks. Please notify once ready.', created_at: '', is_me: false },
                ];
            }

            function renderSamples(label, targetModule) {
                showMessages();
                messagesEl.innerHTML = '';
                for (const m of sampleMessagesFor(targetModule || '', label || '--')) {
                    messagesEl.appendChild(renderMessage(m));
                }
                messagesEl.scrollTop = messagesEl.scrollHeight;
            }

            function myDefaultModule() {
                const mods = (state.me && Array.isArray(state.me.modules)) ? state.me.modules : [];
                for (const m of mods) {
                    const mm = String(m || '').trim().toUpperCase();
                    if (mm && mm !== 'ADMIN') return mm;
                }
                for (const m of mods) {
                    const mm = String(m || '').trim().toUpperCase();
                    if (mm) return mm;
                }
                return '';
            }

            async function loadMessages(threadId) {
                showEmpty(headerTitleEl.textContent || '--');
                state.lastId = 0;
                try {
                    const data = await apiGet(`${apiBase}/messages/list.php?thread_id=${encodeURIComponent(threadId)}&after_id=0`);
                    const msgs = data.messages || [];
                    if (msgs.length === 0) {
                        showMessages();
                        messagesEl.innerHTML = '';
                        for (const m of sampleMessagesFor(headerTitleEl.textContent || '--')) {
                            messagesEl.appendChild(renderMessage(m));
                        }
                        messagesEl.scrollTop = messagesEl.scrollHeight;
                        return;
                    }
                    showMessages();
                    messagesEl.innerHTML = '';
                    for (const m of msgs) {
                        messagesEl.appendChild(renderMessage(m));
                    }
                    state.lastId = data.last_id || state.lastId;
                    messagesEl.scrollTop = messagesEl.scrollHeight;
                    if (state.lastId > 0) {
                        await apiPost(`${apiBase}/threads/mark_read.php`, { thread_id: threadId, last_message_id: state.lastId });
                    }
                } catch (e) {
                    showMessages();
                    messagesEl.innerHTML = '';
                    for (const m of sampleMessagesFor(headerTitleEl.textContent || '--')) {
                        messagesEl.appendChild(renderMessage(m));
                    }
                    messagesEl.scrollTop = messagesEl.scrollHeight;
                }
            }

            async function poll() {
                if (!state.active || !state.active.threadId) {
                    return;
                }
                const threadId = state.active.threadId;
                const after = state.lastId || 0;
                const data = await apiGet(`${apiBase}/messages/list.php?thread_id=${encodeURIComponent(threadId)}&after_id=${encodeURIComponent(after)}`);
                const msgs = data.messages || [];
                if (msgs.length > 0) {
                    if (messagesEl.classList.contains('hidden')) {
                        showMessages();
                        messagesEl.innerHTML = '';
                    }
                    for (const m of msgs) {
                        messagesEl.appendChild(renderMessage(m));
                    }
                    state.lastId = data.last_id || state.lastId;
                    messagesEl.scrollTop = messagesEl.scrollHeight;
                    if (state.lastId > 0) {
                        await apiPost(`${apiBase}/threads/mark_read.php`, { thread_id: threadId, last_message_id: state.lastId });
                    }
                }
            }

            async function refreshModules() {
                const data = await apiGet(`${apiBase}/modules/list.php`);
                state.me = data.me;
                state.items = data.items || [];
                renderDeptList();
            }

            async function openConversation(item) {
                try {
                    state.active = null;
                    state.lastId = 0;
                    headerTitleEl.textContent = item.label || '--';
                    headerAvatarEl.textContent = initials(item.label || '--');
                    renderSamples(item.label || '--', item.module || '');

                    let threadData;
                    if (item.type === 'announcements') {
                        threadData = await apiGet(`${apiBase}/threads/get.php?type=announcements`);
                        state.active = { type: 'announcements', module: null, threadId: threadData.thread.id };
                        const canPost = !!(state.me && state.me.can_post_announcements);
                        setComposerEnabled(canPost);
                    } else {
                        const mine = myDefaultModule();
                        const target = String(item.module || '').trim().toUpperCase();
                        if (mine !== '' && target !== '' && mine === target) {
                            setComposerEnabled(false);
                            return;
                        }
                        threadData = await apiGet(`${apiBase}/threads/get.php?target_module=${encodeURIComponent(item.module)}`);
                        state.active = { type: 'department', module: item.module, threadId: threadData.thread.id };
                        setComposerEnabled(true);
                    }

                    await loadMessages(state.active.threadId);
                    await refreshModules();
                } catch (e) {
                    setComposerEnabled(false);
                    return;
                }
            }

            async function sendMessage() {
                try {
                    if (!state.active || !state.active.threadId) {
                        return;
                    }
                    const body = (inputEl.value || '').trim();
                    if (!body) return;

                    inputEl.value = '';

                    const data = await apiPost(`${apiBase}/messages/send.php`, {
                        thread_id: state.active.threadId,
                        body: body,
                    });

                    if (data && data.message) {
                        if (messagesEl.classList.contains('hidden')) {
                            showMessages();
                            messagesEl.innerHTML = '';
                        }
                        messagesEl.appendChild(renderMessage(data.message));
                        state.lastId = Math.max(state.lastId || 0, data.message.id || 0);
                        messagesEl.scrollTop = messagesEl.scrollHeight;
                        if (state.lastId > 0) {
                            await apiPost(`${apiBase}/threads/mark_read.php`, { thread_id: state.active.threadId, last_message_id: state.lastId });
                        }
                    }

                    await refreshModules();
                } catch (e) {
                    return;
                }
            }

            sendBtnEl.addEventListener('click', function () {
                sendMessage();
            });

            inputEl.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    state.filter = searchInput.value || '';
                    renderDeptList();
                });
            }

            (async function init() {
                try {
                    bindStaticDeptListClicks();
                    await refreshModules();
                    showEmpty('--');
                    setComposerEnabled(false);

                    // Subscribe to WebSocket for real-time chat updates
                    HospitalWS.subscribe('chat-global');
                    HospitalWS.on('chat_message', function(msg) {
                        // Append message directly if it belongs to the active thread
                        if (msg.data && state.active && state.active.threadId) {
                            var m = msg.data;
                            if (m.thread_id === state.active.threadId && m.sender_user_id !== (state.me ? state.me.id : 0)) {
                                if (messagesEl.classList.contains('hidden')) {
                                    showMessages();
                                    messagesEl.innerHTML = '';
                                }
                                messagesEl.appendChild(renderMessage(m));
                                state.lastId = Math.max(state.lastId || 0, m.id || 0);
                                messagesEl.scrollTop = messagesEl.scrollHeight;
                            }
                            // Refresh module list to update unread counts
                            refreshModules().catch(function() {});
                        }
                    });
                    HospitalWS.on('fallback_poll', async function() {
                        try { await poll(); } catch(e) {}
                        try { await refreshModules(); } catch(e) {}
                    });
                } catch (e) {
                    console.error(e);
                    if (!state.me) {
                        state.items = fallbackItems;
                        state.me = {
                            id: 0,
                            full_name: '',
                            modules: [],
                            send_as_modules: [],
                            can_post_announcements: false,
                        };
                    }
                    renderSendAsSelect();
                    renderDeptList();
                    showEmpty('--');
                    setComposerEnabled(false);
                    showNotification(e.message || 'Failed to load chat', 'info');
                }
            })();
            
            // Make openConversation globally available for sidebar access
            window.openConversation = openConversation;
        })();
    </script>
</body>
</html>

