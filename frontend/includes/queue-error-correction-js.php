<script>
// =====================================================
// Queue Error Correction System (shared across stations)
// =====================================================
// Requires: qecStationId (int) to be set before this script loads
// Requires: Toastify library

(function() {
    if (typeof window.qecStationId === 'undefined') {
        console.error('qecStationId must be set before loading queue-error-correction-js.php');
        return;
    }

    const STATION_ID = window.qecStationId;
    let qecSelectedTransfer = null;
    let qecSelectedCorrectStation = null;
    let qecPendingCorrections = [];
    let qecAnnouncedIds = new Set();
    let qecAllTransfers = []; // Store all transfers for search

    // =============================================
    // PART A: Report Wrong Station (outgoing errors)
    // =============================================

    window.qecOpenReportModal = function() {
        document.getElementById('qecReportModal').classList.remove('hidden');
        document.getElementById('qecStep1').classList.remove('hidden');
        document.getElementById('qecStep2').classList.add('hidden');
        document.getElementById('qecBackBtn').classList.add('hidden');
        document.getElementById('qecSubmitBtn').disabled = true;
        qecSelectedTransfer = null;
        qecSelectedCorrectStation = null;
        document.getElementById('qecPatientSearch').value = '';
        qecLoadRecentTransfers();
    };

    window.qecCloseReportModal = function() {
        document.getElementById('qecReportModal').classList.add('hidden');
    };

    window.qecBackToStep1 = function() {
        document.getElementById('qecStep1').classList.remove('hidden');
        document.getElementById('qecStep2').classList.add('hidden');
        document.getElementById('qecBackBtn').classList.add('hidden');
        document.getElementById('qecSubmitBtn').disabled = true;
        qecSelectedCorrectStation = null;
    };

    function qecLoadRecentTransfers() {
        const list = document.getElementById('qecTransferList');
        list.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i><p class="mt-2">Loading recent transfers...</p></div>';

        fetch(API_BASE_URL + '/queue/recent-transfers/' + STATION_ID)
            .then(r => r.json())
            .then(data => {
                if (!data.success || !data.transfers || data.transfers.length === 0) {
                    list.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-check-circle text-4xl mb-2"></i><p class="text-lg">No recent transfers found today</p></div>';
                    qecAllTransfers = [];
                    return;
                }
                qecAllTransfers = data.transfers;
                qecRenderTransfers(qecAllTransfers);
            })
            .catch(() => {
                list.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-3xl mb-2"></i><p>Failed to load transfers</p></div>';
            });
    }

    function qecRenderTransfers(transfers) {
        const list = document.getElementById('qecTransferList');
        list.innerHTML = '';
        
        transfers.forEach(t => {
            const isDischarged = t.current_status === 'completed';
            const div = document.createElement('div');
            div.className = isDischarged
                ? 'p-6 border-2 border-gray-200 rounded-xl opacity-50 cursor-not-allowed'
                : 'p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all duration-200';
            if (!isDischarged) {
                div.onclick = () => qecSelectTransfer(t, div);
            }
            const time = new Date(t.transferred_at).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'});

            // Build journey trail
            let journeyHtml = '';
            if (t.journey && t.journey.length > 0) {
                const trail = t.journey.map(j => `<span class="font-semibold">${j.station_name}</span>`).join(' <i class="fas fa-arrow-right text-xs text-gray-400 mx-1"></i> ');
                journeyHtml = `
                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-route text-blue-600 text-sm"></i>
                            <span class="text-sm font-bold text-blue-800">Journey</span>
                        </div>
                        <div class="text-sm text-gray-700">${t.to_station_name} <i class="fas fa-arrow-right text-xs text-gray-400 mx-1"></i> ${trail}</div>
                    </div>
                `;
            }

            // Current station info
            let currentStationText;
            if (isDischarged) {
                currentStationText = '<span class="px-3 py-1 bg-gray-100 text-gray-700 text-base font-bold rounded-md">Discharged</span>';
            } else if (t.current_station_name && t.current_station_id != t.to_station_id) {
                currentStationText = `Currently at: <span class="text-blue-600 font-semibold">${t.current_station_name}</span>`;
            } else {
                currentStationText = `Sent to: <span class="text-red-600">${t.to_station_name || '?'}</span>`;
            }

            // Determine icon based on patient status
            let patientIcon = 'fa-user';
            
            if (isDischarged) {
                patientIcon = 'fa-user-check';
            } else if (t.current_station_name && t.current_station_id != t.to_station_id) {
                patientIcon = 'fa-arrow-right';
            }

            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                            <i class="fas ${patientIcon} text-2xl"></i>
                        </div>
                        <div>
                            <div class="text-xl font-bold text-gray-800">${t.full_name || 'Unknown'}</div>
                            <div class="text-base text-gray-500">${t.patient_code || ''} &bull; Queue #${t.queue_number || '?'}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-gray-700">${currentStationText}</div>
                        <div class="text-md text-gray-400">${time}</div>
                    </div>
                </div>
                ${journeyHtml}
            `;
            list.appendChild(div);
        });

        if (transfers.length === 0) {
            list.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-search text-4xl mb-2"></i><p class="text-lg">No patients match your search</p></div>';
        }
    }

    function qecSelectTransfer(transfer, element) {
        qecSelectedTransfer = transfer;
        document.querySelectorAll('#qecTransferList > div').forEach(d => d.classList.remove('ring-4', 'ring-red-500', 'bg-red-50'));
        element.classList.add('ring-4', 'ring-red-500', 'bg-red-50');

        // Move to step 2
        document.getElementById('qecStep1').classList.add('hidden');
        document.getElementById('qecStep2').classList.remove('hidden');
        document.getElementById('qecBackBtn').classList.remove('hidden');
        document.getElementById('qecReasonField').value = '';

        // Build journey display for patient info
        let journeySection = '';
        if (transfer.journey && transfer.journey.length > 0) {
            const trail = [transfer.to_station_name, ...transfer.journey.map(j => j.station_name)].join(' &rarr; ');
            journeySection = `
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-route text-blue-600 text-xl"></i>
                        <span class="text-lg font-bold text-blue-800">Patient Journey</span>
                    </div>
                    <div class="text-base text-gray-700 font-medium">${trail}</div>
                </div>
            `;
        }

        const currentLabel = (transfer.current_station_name && transfer.current_station_id != transfer.to_station_id)
            ? `Currently at: <strong>${transfer.current_station_name}</strong>`
            : `Was wrongly sent to: <strong>${transfer.to_station_name}</strong>`;

        document.getElementById('qecSelectedPatientInfo').innerHTML = `
            <div class="flex items-center gap-4 mb-3">
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                <div>
                    <div class="text-xl font-bold text-red-800">${transfer.full_name}</div>
                    <div class="text-base text-red-600">${currentLabel}</div>
                </div>
            </div>
            ${journeySection}
        `;

        // Use current station to exclude from correct station list; alert goes to current station
        const currentStationId = transfer.current_station_id || transfer.to_station_id;
        qecLoadCorrectStations(currentStationId);
    }

    function qecLoadCorrectStations(wrongStationId) {
        const list = document.getElementById('qecCorrectStationList');
        list.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i></div>';

        fetch(API_BASE_URL + '/queue/stations')
            .then(r => r.json())
            .then(data => {
                list.innerHTML = '';
                data.stations.forEach(station => {
                    if (station.id == wrongStationId || station.id == STATION_ID) return;

                    const div = document.createElement('div');
                    div.className = 'p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-200';
                    div.onclick = () => qecSelectCorrectStation(station, div);

                    let icon = 'fa-arrow-right', iconColor = 'bg-blue-600';
                    const sn = (station.station_display_name || '').toLowerCase();
                    if (sn.includes('doctor')) { icon = 'fa-user-md'; iconColor = 'bg-purple-600'; }
                    else if (sn.includes('pharmacy')) { icon = 'fa-pills'; iconColor = 'bg-pink-600'; }
                    else if (sn.includes('cashier')) { icon = 'fa-cash-register'; iconColor = 'bg-yellow-600'; }
                    else if (sn.includes('lab')) { icon = 'fa-flask'; iconColor = 'bg-cyan-600'; }
                    else if (sn.includes('x-ray') || sn.includes('xray')) { icon = 'fa-x-ray'; iconColor = 'bg-indigo-600'; }
                    else if (sn.includes('opd') || sn.includes('out-patient')) { icon = 'fa-hospital-user'; iconColor = 'bg-blue-600'; }

                    div.innerHTML = `
                        <div class="flex items-center">
                            <div class="w-16 h-16 ${iconColor} text-white rounded-xl flex items-center justify-center mr-4">
                                <i class="fas ${icon} text-2xl"></i>
                            </div>
                            <div class="text-xl font-bold text-gray-800">${station.station_display_name}</div>
                        </div>
                    `;
                    list.appendChild(div);
                });
            });
    }

    function qecSelectCorrectStation(station, element) {
        qecSelectedCorrectStation = station;
        document.querySelectorAll('#qecCorrectStationList > div').forEach(d => d.classList.remove('ring-4', 'ring-blue-500', 'bg-blue-50'));
        element.classList.add('ring-4', 'ring-blue-500', 'bg-blue-50');
        document.getElementById('qecSubmitBtn').disabled = false;
    }

    window.qecSubmitReport = function() {
        if (!qecSelectedTransfer || !qecSelectedCorrectStation) return;

        const btn = document.getElementById('qecSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Reporting...';

        const reasonText = (document.getElementById('qecReasonField').value || '').trim();
        const currentStationId = qecSelectedTransfer.current_station_id || qecSelectedTransfer.to_station_id;
        const alertStationName = qecSelectedTransfer.current_station_name || qecSelectedTransfer.to_station_name;

        fetch(API_BASE_URL + '/queue/report-error', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                queue_id: qecSelectedTransfer.current_queue_id || qecSelectedTransfer.id,
                patient_id: qecSelectedTransfer.patient_id,
                wrong_station_id: currentStationId,
                correct_station_id: qecSelectedCorrectStation.id,
                notes: reasonText || null
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Toastify({
                    text: 'Error reported! The ' + alertStationName + ' staff will be alerted.',
                    duration: 5000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#10B981',
                }).showToast();
                qecCloseReportModal();
            } else {
                throw new Error(data.message || 'Failed to report error');
            }
        })
        .catch(err => {
            Toastify({
                text: err.message || 'Failed to report error',
                duration: 4000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#EF4444',
            }).showToast();
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> Report Error';
        });
    };

    // =============================================
    // PART B: Incoming Correction Alert (red overlay)
    // =============================================

    function qecCheckPendingCorrections() {
        fetch(API_BASE_URL + '/queue/pending-corrections/' + STATION_ID)
            .then(r => {
                if (!r.ok) {
                    throw new Error('Failed to fetch');
                }
                return r.json();
            })
            .then(data => {
                if (data.success && data.corrections && data.corrections.length > 0) {
                    qecPendingCorrections = data.corrections;
                    qecShowAlertOverlay(data.corrections);
                } else {
                    qecPendingCorrections = [];
                    qecHideAlertOverlay();
                }
            })
            .catch(() => {
                qecPendingCorrections = [];
                qecHideAlertOverlay();
            });
    }

    function qecShowAlertOverlay(corrections) {
        const overlay = document.getElementById('qecAlertOverlay');
        const content = document.getElementById('qecAlertContent');

        if (overlay.classList.contains('hidden')) {
            // Play alert sound
            qecPlayAlertSound();
        }

        overlay.classList.remove('hidden');
        overlay.classList.add('active');

        content.innerHTML = corrections.map(c => {
            const reasonHtml = c.notes
                ? `<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4 text-left"><div class="text-sm font-bold text-yellow-700 mb-1"><i class="fas fa-comment-alt mr-1"></i> Reason from reporting staff:</div><div class="text-lg text-gray-800">${c.notes}</div></div>`
                : '';
            return `
            <div class="qec-alert-card bg-white rounded-2xl shadow-2xl p-8 border-4 border-red-500">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-4xl"></i>
                    </div>
                    <h2 class="text-3xl font-black text-red-700">WRONG STATION ALERT</h2>
                    <p class="text-lg text-gray-600 mt-2">A patient was sent here by mistake</p>
                </div>
                ${reasonHtml}
                <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-12">
                    <div class="flex items-center gap-4 w-full">
                        <div class="w-20 h-20 bg-red-600 text-white rounded-xl flex items-center justify-center">
                            <span class="text-3xl font-bold">${c.queue_number || '?'}</span>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900">${c.full_name || 'Unknown Patient'}</div>
                            <div class="text-lg text-gray-600">${c.patient_code || ''}</div>
                        </div>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex items-center justify-center gap-3 text-2xl">
                        <span class="text-red-700 font-bold">Should go to:</span>
                        <span class="px-4 py-2 bg-red-600 text-white rounded-lg font-black text-2xl">${c.correct_station_name || '?'}</span>
                    </div>
                    <div class="text-center mt-3 text-sm text-gray-500">
                        Reported by: ${c.reported_by_name || 'Staff'} at ${new Date(c.reported_at).toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'})}
                    </div>
                </div>
                <div class="text-center">
                    <button onclick="qecConfirmCorrection(${c.id})" class="px-10 py-5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-xl font-black transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                        <i class="fas fa-check-circle mr-3"></i>
                        Confirm &amp; Redirect Patient
                    </button>
                </div>
            </div>
        `}).join('');
    }

    function qecHideAlertOverlay() {
        const overlay = document.getElementById('qecAlertOverlay');
        overlay.classList.add('hidden');
        overlay.classList.remove('active');
    }

    window.qecConfirmCorrection = function(errorLogId) {
        const buttons = document.querySelectorAll('#qecAlertContent button');
        buttons.forEach(b => { b.disabled = true; b.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...'; });

        fetch(API_BASE_URL + '/queue/confirm-correction', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ error_log_id: errorLogId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Toastify({
                    text: 'Patient redirected to ' + (data.result?.correct_station_name || 'correct station'),
                    duration: 5000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#10B981',
                }).showToast();

                // Remove this correction from the list
                qecPendingCorrections = qecPendingCorrections.filter(c => c.id !== errorLogId);
                if (qecPendingCorrections.length === 0) {
                    qecHideAlertOverlay();
                } else {
                    qecShowAlertOverlay(qecPendingCorrections);
                }

                // Refresh the station queue if a reload function exists
                if (typeof window.qecRefreshQueue === 'function') {
                    window.qecRefreshQueue();
                }
            } else {
                throw new Error(data.message || 'Failed to confirm correction');
            }
        })
        .catch(err => {
            Toastify({
                text: err.message || 'Failed to confirm correction',
                duration: 4000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#EF4444',
            }).showToast();
            buttons.forEach(b => { b.disabled = false; b.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Confirm & Redirect Patient'; });
        });
    };

    function qecPlayAlertSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            // Play two-tone alert
            [440, 880].forEach((freq, i) => {
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
        const searchInput = document.getElementById('qecPatientSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                
                if (searchTerm === '') {
                    qecRenderTransfers(qecAllTransfers);
                    return;
                }
                
                const filteredTransfers = qecAllTransfers.filter(t => {
                    const nameMatch = (t.full_name || '').toLowerCase().includes(searchTerm);
                    const codeMatch = (t.patient_code || '').toLowerCase().includes(searchTerm);
                    const queueMatch = (t.queue_number || '').toString().includes(searchTerm);
                    return nameMatch || codeMatch || queueMatch;
                });
                
                qecRenderTransfers(filteredTransfers);
            });
        }
    });

    // Check for pending corrections on load
    qecCheckPendingCorrections();

    // Subscribe to WebSocket for real-time correction alerts
    if (typeof HospitalWS !== 'undefined') {
        HospitalWS.subscribe('queue-' + STATION_ID);
        HospitalWS.on('correction_alert', function() { qecCheckPendingCorrections(); });
        HospitalWS.on('queue_update', function() { qecCheckPendingCorrections(); });
        HospitalWS.on('fallback_poll', function() { qecCheckPendingCorrections(); });
    } else {
        setInterval(qecCheckPendingCorrections, 5000);
    }

})();
</script>
