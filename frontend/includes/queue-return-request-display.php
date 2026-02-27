<!-- Queue Return Request: Display Page Notification Overlay -->
<div id="qrrDisplayOverlay" class="fixed inset-0 z-[100] hidden" style="pointer-events:none;">
    <style>
        @keyframes qrrDisplayBorderPulse {
            0%, 100% { box-shadow: inset 0 0 80px 40px rgba(234, 88, 12, 0.6); }
            50% { box-shadow: inset 0 0 100px 50px rgba(234, 88, 12, 0.15); }
        }
        @keyframes qrrDisplaySlideIn {
            from { transform: translateY(-40px) scale(0.95); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }
        @keyframes qrrDisplayPulseIcon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        #qrrDisplayOverlay.active {
            animation: qrrDisplayBorderPulse 2s ease-in-out infinite;
            pointer-events: none;
        }
        .qrr-display-card {
            animation: qrrDisplaySlideIn 0.6s ease-out;
            pointer-events: auto;
        }
        .qrr-display-icon-pulse {
            animation: qrrDisplayPulseIcon 1s ease-in-out infinite;
        }
    </style>
    <div class="absolute inset-0 flex items-center justify-center p-8">
        <div id="qrrDisplayContent" class="w-full max-w-5xl"></div>
    </div>
</div>

<script>
(function() {
    if (typeof window.qrrDisplayStationId === 'undefined') {
        console.error('qrrDisplayStationId must be set before this script');
        return;
    }

    const STATION_ID = window.qrrDisplayStationId;
    let qrrDisplayAnnouncedIds = new Set();
    let qrrDisplayDismissTimers = {};

    function qrrDisplayCheckConfirmed() {
        fetch(API_BASE_URL + '/queue/confirmed-return-requests/' + STATION_ID)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.requests && data.requests.length > 0) {
                    data.requests.forEach(r => {
                        if (!qrrDisplayAnnouncedIds.has('confirmed_' + r.id)) {
                            qrrDisplayAnnouncedIds.add('confirmed_' + r.id);
                            qrrDisplayShowNotification(r);
                            qrrDisplayAnnounce(r);
                        }
                    });
                }
            })
            .catch(() => {});
    }

    function qrrDisplayShowNotification(request) {
        const overlay = document.getElementById('qrrDisplayOverlay');
        const content = document.getElementById('qrrDisplayContent');

        overlay.classList.remove('hidden');
        overlay.classList.add('active');

        const cardId = 'qrr-display-card-confirmed-' + request.id;

        // Don't duplicate
        if (document.getElementById(cardId)) return;

        const card = document.createElement('div');
        card.id = cardId;
        card.className = 'qrr-display-card bg-white rounded-2xl shadow-2xl p-8 border-4 border-orange-500 mb-6';
        card.innerHTML = `
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-orange-100 rounded-full mb-4 qrr-display-icon-pulse">
                    <i class="fas fa-undo-alt text-orange-600 text-5xl"></i>
                </div>
                <h2 class="text-4xl font-black text-orange-700 mb-3">PATIENT REDIRECTED</h2>
                <p class="text-xl text-gray-600 mb-6">This patient was sent here by mistake and has been redirected</p>
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 mb-4 text-left">
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 bg-orange-600 text-white rounded-xl flex items-center justify-center">
                            <span class="text-3xl font-bold">${request.queue_number || '?'}</span>
                        </div>
                        <div class="text-left">
                            <div class="text-3xl font-black text-gray-900">${request.full_name || 'Patient'}</div>
                            <div class="text-lg text-gray-600">${request.patient_code || ''}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-4 text-left">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <span class="font-bold text-green-700 text-2xl">Please proceed to:</span>
                        <span class="inline-block px-8 py-3 bg-green-600 text-white rounded-xl font-black text-3xl">${request.suggested_station_name || '?'}</span>
                    </div>
                </div>
            </div>
        `;
        content.appendChild(card);

        // Auto-dismiss after 20 seconds
        qrrDisplayDismissTimers[cardId] = setTimeout(() => {
            const el = document.getElementById(cardId);
            if (el) el.remove();
            qrrDisplayCheckIfEmpty();
        }, 20000);
    }

    function qrrDisplayCheckIfEmpty() {
        const content = document.getElementById('qrrDisplayContent');
        if (content && content.children.length === 0) {
            const overlay = document.getElementById('qrrDisplayOverlay');
            overlay.classList.add('hidden');
            overlay.classList.remove('active');
        }
    }

    function qrrDisplayAnnounce(request) {
        if (!('speechSynthesis' in window)) return;

        const patientName = request.full_name || 'Patient';
        const stationName = request.suggested_station_name || 'the correct station';
        const text = `Attention. ${patientName}, please proceed to ${stationName}. ${patientName}, please proceed to ${stationName}.`;

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.rate = 0.85;
        utterance.pitch = 1.0;
        utterance.volume = 1.0;
        utterance.lang = 'en-US';

        window.speechSynthesis.cancel();

        setTimeout(() => {
            window.speechSynthesis.speak(utterance);
        }, 200);
    }

    // Check on load
    qrrDisplayCheckConfirmed();

    // Subscribe to WebSocket
    if (typeof HospitalWS !== 'undefined') {
        HospitalWS.subscribe('queue-' + STATION_ID);
        HospitalWS.on('return_request_response', function() { qrrDisplayCheckConfirmed(); });
        HospitalWS.on('queue_update', function() { qrrDisplayCheckConfirmed(); });
        HospitalWS.on('fallback_poll', function() { qrrDisplayCheckConfirmed(); });
    } else {
        setInterval(qrrDisplayCheckConfirmed, 5000);
    }
})();
</script>
