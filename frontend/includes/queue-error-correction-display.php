<!-- Queue Error Correction: Display Page Notification Overlay -->
<div id="qecDisplayOverlay" class="fixed inset-0 z-[100] hidden" style="pointer-events:none;">
    <style>
        @keyframes qecDisplayBorderPulse {
            0%, 100% { box-shadow: inset 0 0 80px 40px rgba(220, 38, 38, 0.6); }
            50% { box-shadow: inset 0 0 100px 50px rgba(220, 38, 38, 0.15); }
        }
        @keyframes qecDisplaySlideIn {
            from { transform: translateY(-40px) scale(0.95); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }
        @keyframes qecDisplayPulseIcon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        #qecDisplayOverlay.active {
            animation: qecDisplayBorderPulse 2s ease-in-out infinite;
            pointer-events: none;
        }
        .qec-display-card {
            animation: qecDisplaySlideIn 0.6s ease-out;
            pointer-events: auto;
        }
        .qec-display-icon-pulse {
            animation: qecDisplayPulseIcon 1s ease-in-out infinite;
        }
    </style>
    <div class="absolute inset-0 flex items-center justify-center p-8">
        <div id="qecDisplayContent" class="w-full max-w-5xl"></div>
    </div>
</div>

<script>
(function() {
    if (typeof window.qecDisplayStationId === 'undefined') {
        console.error('qecDisplayStationId must be set before this script');
        return;
    }

    const STATION_ID = window.qecDisplayStationId;
    let qecDisplayAnnouncedIds = new Set();
    let qecDisplayDismissTimers = {};

    function qecDisplayCheckCorrections() {
        // Only show on display after staff confirms the correction
        fetch(API_BASE_URL + '/queue/confirmed-corrections/' + STATION_ID)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.corrections && data.corrections.length > 0) {
                    data.corrections.forEach(c => {
                        if (!qecDisplayAnnouncedIds.has('confirmed_' + c.id)) {
                            qecDisplayAnnouncedIds.add('confirmed_' + c.id);
                            qecDisplayShowNotification(c);
                            qecDisplayAnnounce(c);
                        }
                    });
                }
            })
            .catch(() => {});
    }

    function qecDisplayShowNotification(correction) {
        const overlay = document.getElementById('qecDisplayOverlay');
        const content = document.getElementById('qecDisplayContent');

        overlay.classList.remove('hidden');
        overlay.classList.add('active');

        const cardId = 'qec-display-card-confirmed-' + correction.id;

        // Don't duplicate
        if (document.getElementById(cardId)) return;

        const card = document.createElement('div');
        card.id = cardId;
        card.className = 'qec-display-card bg-white rounded-2xl shadow-2xl p-8 border-4 border-red-500 mb-6';
        card.innerHTML = `
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-4 qec-display-icon-pulse">
                    <i class="fas fa-exclamation-triangle text-red-600 text-5xl"></i>
                </div>
                <h2 class="text-4xl font-black text-red-700 mb-3">PATIENT REDIRECTED</h2>
                <p class="text-xl text-gray-600 mb-6">This patient was sent here by mistake and has been redirected</p>
                <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-4 text-left">
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 bg-red-600 text-white rounded-xl flex items-center justify-center">
                            <span class="text-3xl font-bold">${correction.queue_number || '?'}</span>
                        </div>
                        <div class="text-left">
                            <div class="text-3xl font-black text-gray-900">${correction.full_name || 'Patient'}</div>
                            <div class="text-lg text-gray-600">${correction.patient_code || ''}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-4 text-left">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <span class="font-bold text-green-700 text-2xl">Please proceed to:</span>
                        <span class="inline-block px-8 py-3 bg-green-600 text-white rounded-xl font-black text-3xl">${correction.correct_station_name || '?'}</span>
                    </div>
                </div>
            </div>
        `;
        content.appendChild(card);

        // Auto-dismiss after 20 seconds
        qecDisplayDismissTimers[cardId] = setTimeout(() => {
            const el = document.getElementById(cardId);
            if (el) el.remove();
            qecDisplayCheckIfEmpty();
        }, 20000);
    }

    function qecDisplayCheckIfEmpty() {
        const content = document.getElementById('qecDisplayContent');
        if (content && content.children.length === 0) {
            const overlay = document.getElementById('qecDisplayOverlay');
            overlay.classList.add('hidden');
            overlay.classList.remove('active');
        }
    }

    function qecDisplayAnnounce(correction) {
        if (!('speechSynthesis' in window)) return;

        const patientName = correction.full_name || 'Patient';
        const stationName = correction.correct_station_name || 'the correct station';
        const text = `Attention. ${patientName}, please proceed to ${stationName}. ${patientName}, please proceed to ${stationName}.`;

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.rate = 0.85;
        utterance.pitch = 1.0;
        utterance.volume = 1.0;
        utterance.lang = 'en-US';

        // Cancel any ongoing speech
        window.speechSynthesis.cancel();

        // Small delay to ensure cancel completes
        setTimeout(() => {
            window.speechSynthesis.speak(utterance);
        }, 200);
    }

    // Poll every 5 seconds
    qecDisplayCheckCorrections();
    setInterval(qecDisplayCheckCorrections, 5000);
})();
</script>
