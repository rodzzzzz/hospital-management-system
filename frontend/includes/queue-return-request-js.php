<script>
// =====================================================
// Queue Return Request System — Reverse QEC
// (shared across stations)
// =====================================================
// Requires: qrrStationId (int) to be set before this script loads
// Requires: Toastify library

(function() {
    if (typeof window.qrrStationId === 'undefined') {
        console.error('qrrStationId must be set before loading queue-return-request-js.php');
        return;
    }

    const STATION_ID = window.qrrStationId;
    let qrrSelectedTransfer = null;
    let qrrSelectedCorrectStation = null;
    let qrrPendingRequests = [];
    let qrrAnnouncedRejectionIds = new Set();
    let qrrAllTransfers = [];
    let qrrRejectingRequestId = null;

    // =============================================
    // PART A: Report Return Request (Station B initiates)
    // =============================================

    window.qrrOpenReportModal = function() {
        document.getElementById('qrrReportModal').classList.remove('hidden');
        document.getElementById('qrrStep1').classList.remove('hidden');
        document.getElementById('qrrStep2').classList.add('hidden');
        document.getElementById('qrrBackBtn').classList.add('hidden');
        document.getElementById('qrrSubmitBtn').disabled = true;
        qrrSelectedTransfer = null;
        qrrSelectedCorrectStation = null;
        document.getElementById('qrrPatientSearch').value = '';
        qrrLoadIncomingTransfers();
    };

    window.qrrCloseReportModal = function() {
        document.getElementById('qrrReportModal').classList.add('hidden');
    };

    window.qrrBackToStep1 = function() {
        document.getElementById('qrrStep1').classList.remove('hidden');
        document.getElementById('qrrStep2').classList.add('hidden');
        document.getElementById('qrrBackBtn').classList.add('hidden');
        document.getElementById('qrrSubmitBtn').disabled = true;
        qrrSelectedCorrectStation = null;
    };

    function qrrLoadIncomingTransfers() {
        const list = document.getElementById('qrrTransferList');
        list.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-orange-500 text-3xl"></i><p class="mt-2">Loading incoming transfers...</p></div>';

        fetch(API_BASE_URL + '/queue/incoming-transfers/' + STATION_ID)
            .then(r => r.json())
            .then(data => {
                if (!data.success || !data.transfers || data.transfers.length === 0) {
                    list.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-check-circle text-4xl mb-2"></i><p class="text-lg">No incoming transfers found today</p></div>';
                    qrrAllTransfers = [];
                    return;
                }
                qrrAllTransfers = data.transfers;
                qrrRenderTransfers(qrrAllTransfers);
            })
            .catch(() => {
                list.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-3xl mb-2"></i><p>Failed to load transfers</p></div>';
            });
    }

    function qrrRenderTransfers(transfers) {
        const list = document.getElementById('qrrTransferList');
        list.innerHTML = '';

        transfers.forEach(t => {
            const isDischarged = t.current_status === 'completed';
            const hasPending = t.has_pending_return;
            const isNotHere = t.current_station_id && t.current_station_id != STATION_ID;
            const isDisabled = isDischarged || hasPending || isNotHere;

            const div = document.createElement('div');
            div.className = isDisabled
                ? 'p-6 border-2 border-gray-200 rounded-xl opacity-50 cursor-not-allowed'
                : 'p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-400 hover:bg-orange-50 transition-all duration-200';
            if (!isDisabled) {
                div.onclick = () => qrrSelectTransfer(t, div);
            }
            const time = new Date(t.transferred_at).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'});

            let statusBadge = '';
            if (isDischarged) {
                statusBadge = '<span class="px-3 py-1 bg-gray-100 text-gray-700 text-base font-bold rounded-md">Discharged</span>';
            } else if (hasPending) {
                statusBadge = '<span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-base font-bold rounded-md">Return Pending</span>';
            } else if (isNotHere) {
                statusBadge = '<span class="px-3 py-1 bg-blue-100 text-blue-700 text-base font-bold rounded-md">Moved to ' + (t.current_station_name || '?') + '</span>';
            } else {
                statusBadge = 'From: <span class="text-orange-600 font-semibold">' + (t.from_station_name || '?') + '</span>';
            }

            let patientIcon = 'fa-user';
            if (isDischarged) patientIcon = 'fa-user-check';
            else if (isNotHere) patientIcon = 'fa-arrow-right';
            else if (hasPending) patientIcon = 'fa-clock';

            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                            <i class="fas ${patientIcon} text-2xl"></i>
                        </div>
                        <div>
                            <div class="text-xl font-bold text-gray-800">${t.full_name || 'Unknown'}</div>
                            <div class="text-base text-gray-500">${t.patient_code || ''} &bull; Queue #${t.queue_number || '?'}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-gray-700">${statusBadge}</div>
                        <div class="text-md text-gray-400">${time}</div>
                    </div>
                </div>
            `;
            list.appendChild(div);
        });

        if (transfers.length === 0) {
            list.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-search text-4xl mb-2"></i><p class="text-lg">No patients match your search</p></div>';
        }
    }

    function qrrSelectTransfer(transfer, element) {
        qrrSelectedTransfer = transfer;
        document.querySelectorAll('#qrrTransferList > div').forEach(d => d.classList.remove('ring-4', 'ring-orange-500', 'bg-orange-50'));
        element.classList.add('ring-4', 'ring-orange-500', 'bg-orange-50');

        // Move to step 2
        document.getElementById('qrrStep1').classList.add('hidden');
        document.getElementById('qrrStep2').classList.remove('hidden');
        document.getElementById('qrrBackBtn').classList.remove('hidden');
        document.getElementById('qrrReasonField').value = '';

        document.getElementById('qrrSelectedPatientInfo').innerHTML = `
            <div class="flex items-center gap-4 mb-3">
                <i class="fas fa-undo-alt text-orange-500 text-3xl"></i>
                <div>
                    <div class="text-xl font-bold text-orange-800">${transfer.full_name}</div>
                    <div class="text-base text-orange-600">Received from: <strong>${transfer.from_station_name || '?'}</strong></div>
                </div>
            </div>
        `;

        // Load correct stations, excluding current station and origin
        qrrLoadCorrectStations(transfer.from_station_id);
    }

    function qrrLoadCorrectStations(originStationId) {
        const list = document.getElementById('qrrCorrectStationList');
        list.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-orange-500 text-2xl"></i></div>';

        fetch(API_BASE_URL + '/queue/stations')
            .then(r => r.json())
            .then(data => {
                list.innerHTML = '';
                data.stations.forEach(station => {
                    if (station.id == STATION_ID) return; // Exclude self

                    const div = document.createElement('div');
                    div.className = 'p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-400 hover:bg-orange-50 transition-all duration-200';
                    div.onclick = () => qrrSelectCorrectStation(station, div);

                    let icon = 'fa-arrow-right', iconColor = 'bg-orange-600';
                    const sn = (station.station_display_name || '').toLowerCase();
                    if (sn.includes('doctor')) { icon = 'fa-user-md'; iconColor = 'bg-purple-600'; }
                    else if (sn.includes('pharmacy')) { icon = 'fa-pills'; iconColor = 'bg-pink-600'; }
                    else if (sn.includes('cashier')) { icon = 'fa-cash-register'; iconColor = 'bg-yellow-600'; }
                    else if (sn.includes('lab')) { icon = 'fa-flask'; iconColor = 'bg-cyan-600'; }
                    else if (sn.includes('x-ray') || sn.includes('xray')) { icon = 'fa-x-ray'; iconColor = 'bg-indigo-600'; }
                    else if (sn.includes('opd') || sn.includes('out-patient')) { icon = 'fa-hospital-user'; iconColor = 'bg-blue-600'; }

                    // Highlight the origin station as a suggested option
                    const isOrigin = station.id == originStationId;
                    const originBadge = isOrigin ? '<span class="ml-3 px-2 py-1 bg-orange-100 text-orange-700 text-sm font-bold rounded">Origin</span>' : '';

                    div.innerHTML = `
                        <div class="flex items-center">
                            <div class="w-16 h-16 ${iconColor} text-white rounded-xl flex items-center justify-center mr-4">
                                <i class="fas ${icon} text-2xl"></i>
                            </div>
                            <div class="text-xl font-bold text-gray-800">${station.station_display_name}${originBadge}</div>
                        </div>
                    `;
                    list.appendChild(div);
                });
            });
    }

    function qrrSelectCorrectStation(station, element) {
        qrrSelectedCorrectStation = station;
        document.querySelectorAll('#qrrCorrectStationList > div').forEach(d => d.classList.remove('ring-4', 'ring-orange-500', 'bg-orange-50'));
        element.classList.add('ring-4', 'ring-orange-500', 'bg-orange-50');
        document.getElementById('qrrSubmitBtn').disabled = false;
    }

    window.qrrSubmitRequest = function() {
        if (!qrrSelectedTransfer || !qrrSelectedCorrectStation) return;

        const btn = document.getElementById('qrrSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';

        const reasonText = (document.getElementById('qrrReasonField').value || '').trim();
        const originStationId = qrrSelectedTransfer.from_station_id;

        fetch(API_BASE_URL + '/queue/return-request', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                queue_id: qrrSelectedTransfer.current_queue_id || qrrSelectedTransfer.id,
                patient_id: qrrSelectedTransfer.patient_id,
                requesting_station_id: STATION_ID,
                origin_station_id: originStationId,
                suggested_station_id: qrrSelectedCorrectStation.id,
                notes: reasonText || null
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Toastify({
                    text: 'Return request sent! The ' + (qrrSelectedTransfer.from_station_name || 'origin') + ' staff will be notified.',
                    duration: 5000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#10B981',
                }).showToast();
                qrrCloseReportModal();
            } else {
                throw new Error(data.message || 'Failed to send return request');
            }
        })
        .catch(err => {
            Toastify({
                text: err.message || 'Failed to send return request',
                duration: 4000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#EF4444',
            }).showToast();
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Send Return Request';
        });
    };

    // =============================================
    // PART B: Incoming Return Request Alert (Station A — origin station)
    // =============================================

    function qrrCheckPendingRequests() {
        fetch(API_BASE_URL + '/queue/pending-return-requests/' + STATION_ID)
            .then(r => {
                if (!r.ok) throw new Error('Failed to fetch');
                return r.json();
            })
            .then(data => {
                if (data.success && data.requests && data.requests.length > 0) {
                    qrrPendingRequests = data.requests;
                    qrrShowAlertOverlay(data.requests);
                } else {
                    qrrPendingRequests = [];
                    qrrHideAlertOverlay();
                }
            })
            .catch(() => {
                qrrPendingRequests = [];
                qrrHideAlertOverlay();
            });
    }

    function qrrShowAlertOverlay(requests) {
        const overlay = document.getElementById('qrrAlertOverlay');
        const content = document.getElementById('qrrAlertContent');

        if (overlay.classList.contains('hidden')) {
            qrrPlayAlertSound();
        }

        overlay.classList.remove('hidden');
        overlay.classList.add('active');

        content.innerHTML = requests.map(r => {
            const reasonHtml = r.request_notes
                ? `<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4 text-left"><div class="text-sm font-bold text-yellow-700 mb-1"><i class="fas fa-comment-alt mr-1"></i> Reason from receiving station:</div><div class="text-lg text-gray-800">${r.request_notes}</div></div>`
                : '';
            return `
            <div class="qrr-alert-card bg-white rounded-2xl shadow-2xl p-8 border-4 border-orange-500">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-100 rounded-full mb-4">
                        <i class="fas fa-undo-alt text-orange-600 text-4xl"></i>
                    </div>
                    <h2 class="text-3xl font-black text-orange-700">RETURN REQUEST</h2>
                    <p class="text-lg text-gray-600 mt-2">A station wants to return a patient you sent them</p>
                </div>
                ${reasonHtml}
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 mb-4">
                    <div class="flex items-center gap-4 w-full">
                        <div class="w-20 h-20 bg-orange-600 text-white rounded-xl flex items-center justify-center">
                            <span class="text-3xl font-bold">${r.queue_number || '?'}</span>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900">${r.full_name || 'Unknown Patient'}</div>
                            <div class="text-lg text-gray-600">${r.patient_code || ''}</div>
                        </div>
                    </div>
                </div>
                <div class="mb-12">
                    <div class="flex items-center gap-4 text-xl">
                        <div class="p-6 bg-gray-50 rounded-lg flex-1">
                            <span class="text-gray-500">Currently at:</span>
                            <span class="font-bold text-gray-800 ml-1">${r.requesting_station_name || '?'}</span>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 text-3xl"></i>
                        <div class="p-6 bg-blue-50 rounded-lg flex-1">
                            <span class="text-gray-500">Move to:</span>
                            <span class="font-bold text-blue-800 ml-1">${r.suggested_station_name || '?'}</span>
                        </div>
                    </div>
                    <div class="text-center mt-3 text-sm text-gray-500">
                        Reported by: ${r.requested_by_name || 'Staff'} at ${new Date(r.requested_at).toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'})}
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center gap-4 max-w-xl mx-auto">
                    <button onclick="qrrConfirmRequest(${r.id})" class="px-10 py-5 bg-green-600 text-white rounded-xl hover:bg-green-700 text-xl font-black transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] w-full">
                        <i class="fas fa-check-circle mr-3"></i>
                        Confirm &amp; Redirect
                    </button>
                    <button onclick="qrrOpenRejectModal(${r.id}, '${(r.full_name || '').replace(/'/g, "\\'")}', '${(r.requesting_station_name || '').replace(/'/g, "\\'")}')" class="px-10 py-5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-xl font-black transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] w-full">
                        <i class="fas fa-times-circle mr-3"></i>
                        Reject
                    </button>
                </div>
            </div>
        `}).join('');
    }

    function qrrHideAlertOverlay() {
        const overlay = document.getElementById('qrrAlertOverlay');
        overlay.classList.add('hidden');
        overlay.classList.remove('active');
    }

    window.qrrConfirmRequest = function(requestId) {
        const buttons = document.querySelectorAll('#qrrAlertContent button');
        buttons.forEach(b => { b.disabled = true; b.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...'; });

        fetch(API_BASE_URL + '/queue/confirm-return-request', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ request_id: requestId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Toastify({
                    text: 'Patient redirected to ' + (data.result?.suggested_station_name || 'correct station'),
                    duration: 5000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#10B981',
                }).showToast();

                qrrPendingRequests = qrrPendingRequests.filter(r => r.id !== requestId);
                // Close the Station A decision modal after action.
                qrrHideAlertOverlay();

                if (typeof window.qrrRefreshQueue === 'function') {
                    window.qrrRefreshQueue();
                }
            } else {
                throw new Error(data.message || 'Failed to confirm return request');
            }
        })
        .catch(err => {
            Toastify({
                text: err.message || 'Failed to confirm return request',
                duration: 4000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#EF4444',
            }).showToast();
            buttons.forEach(b => { b.disabled = false; });
            // Re-render to restore button text
            if (qrrPendingRequests.length > 0) qrrShowAlertOverlay(qrrPendingRequests);
        });
    };

    // =============================================
    // PART C: Reject Modal (Station A rejects with reason)
    // =============================================

    window.qrrOpenRejectModal = function(requestId, patientName, stationName) {
        qrrRejectingRequestId = requestId;
        document.getElementById('qrrRejectPatientInfo').innerHTML =
            '<strong>' + patientName + '</strong> — requested return from <strong>' + stationName + '</strong>';
        document.getElementById('qrrRejectReasonField').value = '';
        document.getElementById('qrrRejectSubmitBtn').disabled = true;
        document.getElementById('qrrRejectReasonModal').classList.remove('hidden');

        // Enable submit when reason is provided
        document.getElementById('qrrRejectReasonField').oninput = function() {
            document.getElementById('qrrRejectSubmitBtn').disabled = this.value.trim() === '';
        };
    };

    window.qrrCloseRejectModal = function() {
        document.getElementById('qrrRejectReasonModal').classList.add('hidden');
        qrrRejectingRequestId = null;
    };

    window.qrrSubmitReject = function() {
        if (!qrrRejectingRequestId) return;
        const requestId = qrrRejectingRequestId;
        const reason = (document.getElementById('qrrRejectReasonField').value || '').trim();
        if (!reason) return;

        const btn = document.getElementById('qrrRejectSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Rejecting...';

        fetch(API_BASE_URL + '/queue/reject-return-request', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ request_id: qrrRejectingRequestId, reason: reason })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Toastify({
                    text: 'Return request rejected. The requesting station has been notified.',
                    duration: 5000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#F59E0B',
                }).showToast();

                qrrCloseRejectModal();
                qrrPendingRequests = qrrPendingRequests.filter(r => r.id !== requestId);
                // Close the Station A decision modal after action.
                qrrHideAlertOverlay();
            } else {
                throw new Error(data.message || 'Failed to reject');
            }
        })
        .catch(err => {
            Toastify({
                text: err.message || 'Failed to reject return request',
                duration: 4000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#EF4444',
            }).showToast();
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-times-circle mr-2"></i> Confirm Rejection';
        });
    };

    // =============================================
    // PART D: Rejection Notification (Station B sees blocking overlay)
    // =============================================

    function qrrCheckRejections() {
        fetch(API_BASE_URL + '/queue/rejected-return-requests/' + STATION_ID)
            .then(r => {
                if (!r.ok) throw new Error('Failed to fetch');
                return r.json();
            })
            .then(data => {
                if (data.success && data.requests && data.requests.length > 0) {
                    data.requests.forEach(r => {
                        if (!qrrAnnouncedRejectionIds.has(r.id)) {
                            qrrAnnouncedRejectionIds.add(r.id);
                            qrrShowRejectionOverlay(r);
                        }
                    });
                }
            })
            .catch(() => {});
    }

    function qrrShowRejectionOverlay(request) {
        const overlay = document.getElementById('qrrRejectionOverlay');
        const content = document.getElementById('qrrRejectionContent');

        qrrPlayAlertSound();
        overlay.classList.remove('hidden');
        overlay.classList.add('active');

        const cardId = 'qrr-rejection-card-' + request.id;
        if (document.getElementById(cardId)) return;

        const card = document.createElement('div');
        card.id = cardId;
        card.className = 'bg-white rounded-2xl shadow-2xl p-8 border-4 border-red-500';
        card.innerHTML = `
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                    <i class="fas fa-times-circle text-red-600 text-4xl"></i>
                </div>
                <h2 class="text-3xl font-black text-red-700">RETURN REQUEST REJECTED</h2>
                <p class="text-lg text-gray-600 mt-2">Your return request was rejected by ${request.origin_station_name || 'the origin station'}</p>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-4">
                <div class="flex items-center gap-4 w-full">
                    <div class="w-20 h-20 bg-red-600 text-white rounded-xl flex items-center justify-center">
                        <span class="text-3xl font-bold">${request.queue_number || '?'}</span>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-gray-900">${request.full_name || 'Unknown Patient'}</div>
                        <div class="text-lg text-gray-600">${request.patient_code || ''}</div>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-5 mb-6">
                <div class="text-sm font-bold text-yellow-700 mb-1"><i class="fas fa-comment-alt mr-1"></i> Rejection Reason:</div>
                <div class="text-xl text-gray-800 font-semibold">${request.rejection_reason || 'No reason provided'}</div>
                <div class="text-sm text-gray-500 mt-2">Rejected by: ${request.responded_by_name || 'Staff'}</div>
            </div>
            <div class="text-center">
                <button onclick="qrrDismissRejection('${cardId}', ${request.id})" class="px-10 py-5 bg-gray-700 text-white rounded-xl hover:bg-gray-800 text-xl font-black transition-all duration-200 shadow-lg">
                    <i class="fas fa-check mr-3"></i>
                    Acknowledged
                </button>
            </div>
        `;
        content.appendChild(card);
    }

    function qrrNotifyRejectionAcknowledged(requestId) {
        if (!requestId) return;
        fetch(API_BASE_URL + '/queue/acknowledge-return-rejection', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ request_id: requestId, station_id: STATION_ID })
        }).catch(() => {});
    }

    window.qrrDismissRejection = function(cardId, requestId) {
        qrrNotifyRejectionAcknowledged(requestId);

        const el = document.getElementById(cardId);
        if (el) el.remove();

        const content = document.getElementById('qrrRejectionContent');
        if (content && content.children.length === 0) {
            const overlay = document.getElementById('qrrRejectionOverlay');
            overlay.classList.add('hidden');
            overlay.classList.remove('active');
        }
    };

    // =============================================
    // Sound & Search
    // =============================================

    function qrrPlayAlertSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            [523, 659].forEach((freq, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = freq;
                osc.type = 'sine';
                gain.gain.value = 0.3;
                osc.start(ctx.currentTime + i * 0.3);
                osc.stop(ctx.currentTime + i * 0.3 + 0.25);
            });
        } catch (e) {}
    }

    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('qrrPatientSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();

                if (searchTerm === '') {
                    qrrRenderTransfers(qrrAllTransfers);
                    return;
                }

                const filteredTransfers = qrrAllTransfers.filter(t => {
                    const nameMatch = (t.full_name || '').toLowerCase().includes(searchTerm);
                    const codeMatch = (t.patient_code || '').toLowerCase().includes(searchTerm);
                    const queueMatch = (t.queue_number || '').toString().includes(searchTerm);
                    return nameMatch || codeMatch || queueMatch;
                });

                qrrRenderTransfers(filteredTransfers);
            });
        }
    });

    // =============================================
    // Polling & WebSocket
    // =============================================

    // Check for pending return requests (Station A) and rejections (Station B) on load
    qrrCheckPendingRequests();
    qrrCheckRejections();

    if (typeof HospitalWS !== 'undefined') {
        HospitalWS.subscribe('queue-' + STATION_ID);
        HospitalWS.on('return_request_alert', function() { qrrCheckPendingRequests(); });
        HospitalWS.on('return_request_response', function() { qrrCheckRejections(); });
        HospitalWS.on('return_request_ack', function(event) {
            const ack = event ? (event.data || event.payload || null) : null;
            if (!ack) return;
            const stationName = ack.requesting_station_name || 'Requesting station';
            const patientName = ack.patient_name || 'the patient';
            Toastify({
                text: stationName + ' acknowledged your rejection for ' + patientName + '.',
                duration: 5000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#3B82F6',
            }).showToast();
        });
        HospitalWS.on('queue_update', function() { qrrCheckPendingRequests(); qrrCheckRejections(); });
        HospitalWS.on('fallback_poll', function() { qrrCheckPendingRequests(); qrrCheckRejections(); });
    } else {
        setInterval(function() { qrrCheckPendingRequests(); qrrCheckRejections(); }, 5000);
    }

})();
</script>
