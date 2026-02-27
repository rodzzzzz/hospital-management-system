<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Queue Display - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include __DIR__ . '/includes/websocket-client.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
            background: #f8fafc;
        }
        .display-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
        }
        .header-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem 2rem;
        }
        .queue-section {
            flex: 1;
            display: flex;
            padding: 2rem;
            gap: 2rem;
            margin: 0 auto;
            width: 100%;
            min-height: 0;
        }
        .left-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .right-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }
        .currently-serving {
            background: white;
            border-radius: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            padding: 2.5rem 2rem;
            min-height: 320px;
        }
        .next-patients {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            overflow: hidden;
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .queue-list {
            flex: 1;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
            min-height: 0;
        }
        .queue-list::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 9rem;
            background: linear-gradient(to bottom, transparent, white 80%);
            pointer-events: none;
            z-index: 10;
        }
        .patient-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            flex: 1;
            overflow-y: hidden;
            min-height: 0;
        }
        .queue-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 0.5rem;
        }
        .queue-number {
            font-size: 3rem;
            font-weight: 700;
            min-width: 50px;
            text-align: center;
        }
        .patient-info {
            flex: 1;
            margin-left: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .patient-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.125rem;
        }
        .serving-number {
            font-size: 6.5rem;
            font-weight: 800;
            line-height: 1;
            position: relative;
        }
        .serving-name {
            font-size: 2rem;
            font-weight: 600;
            color: #1e293b;
            margin-top: 1rem;
        }
        .station-title {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }
        .station-subtitle {
            font-size: 1.125rem;
            color: #64748b;
        }
        .time-display {
            font-size: 2.8rem;
            font-weight: 600;
            color: #1e293b;
        }
        .date-display {
            font-size: 1rem;
            color: #64748b;
        }
        .no-patients {
            text-align: center;
            color: #94a3b8;
            font-size: 1rem;
            font-weight: 500;
            padding: 2rem;
        }
        .footer-info {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.08);
        }
        .logo-section {
            display: flex;
            align-items: center;
        }
        .logo-section img {
            margin-right: 1rem;
        }
        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .hospital-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="display-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <img src="resources/logo.png" alt="Hospital Logo" class="logo">
                <div>
                    <h1 class="station-title">Doctor's Office</h1>
                    <p class="station-subtitle">Patient Queue Management System</p>
                </div>
                </div>
                <div>
                    <div class="time-display" id="currentTime">00:00:00</div>
                    <div class="date-display" id="currentDate">Loading...</div>
                </div>
            </div>
        </div>

        <!-- Queue Section -->
        <div class="queue-section">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Currently Serving -->
                <div class="currently-serving bg-green-100 text-green-600 border border-green-300">
                    <div class="mb-4">
                        <h2 class="text-xl font-semibold text-gray-700 mb-2">Now Serving</h2>
                        <div id="currentlyServing">
                            <div class="serving-number">---</div>
                            <div class="serving-name">No patient being served</div>
                        </div>
                    </div>
                </div>
                
                <!-- Next 3 Patients -->
                <div class="next-patients bg-blue-50 border border-blue-300">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Next in Queue</h2>
                    <div id="nextPatientsList" class="patient-list flex-1">
                        <div class="no-patients">No patients in queue</div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="right-column">
                <!-- Remaining 6 Patients -->
                <div class="queue-list bg-gray-100 border border-gray-300">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Waiting List</h2>
                    <div id="queueList" class="patient-list flex-1">
                        <div class="no-patients">No patients in queue</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-info">
            <div class="logo-section">
                <div class="hospital-name">DRBMJRAH MEMORIAL HOSPITAL</div>
            </div>
            <div class="text-gray-600">
                <i class="fas fa-phone-alt mr-2"></i>+63917 513 9979 
                <span class="mx-4">|</span>
                <i class="fas fa-map-marker-alt mr-2"></i>Brgy. Mabul, Malabang
Lanao Del Sur, BARMM 9300
            </div>
        </div>
    </div>

    <script>
        let displayData = null;
        let refreshInterval;

        // Initialize display
        document.addEventListener('DOMContentLoaded', function() {
            updateDateTime();
            setInterval(updateDateTime, 1000);
            loadQueueData();
            // Subscribe to WebSocket for real-time queue updates
            HospitalWS.subscribe('queue-2');
            HospitalWS.subscribe('global');
            HospitalWS.on('queue_update', function() { loadQueueData(); });
            HospitalWS.on('fallback_poll', function() { loadQueueData(); });
        });

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const timeOptions = { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: false 
            };
            const dateOptions = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', timeOptions);
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', dateOptions);
        }

        // Load queue data
        async function loadQueueData() {
            try {
                const response = await fetch(API_BASE_URL + '/queue/display/2'); // Doctor station ID is 2
                displayData = await response.json();
                updateDisplay();
            } catch (error) {
                console.error('Error loading queue data:', error);
            }
        }

        // Update display
        function updateDisplay() {
            if (!displayData) return;

            // Update currently serving
            const currentlyServingDiv = document.getElementById('currentlyServing');
            if (displayData.currently_serving) {
                currentlyServingDiv.innerHTML = `
                    <div class="relative w-fit mx-auto">
                        <div class="absolute inset-0 bg-green-400 rounded-full w-[80px] h-[80px] left-[calc(50%-40px)] top-[calc(50%-40px)] animate-ping"></div>
                        <div class="serving-number relative w-fit p-0">${displayData.currently_serving.queue_number}</div>
                    </div>
                    <div class="serving-name line-clamp-1">${displayData.currently_serving.full_name}</div>
                `;
            } else {
                currentlyServingDiv.innerHTML = `
                    <div class="serving-number">---</div>
                    <div class="serving-name">No patient being served</div>
                `;
            }

            // Update queue lists
            const nextPatientsListDiv = document.getElementById('nextPatientsList');
            const queueListDiv = document.getElementById('queueList');
            
            if (displayData.next_patients && displayData.next_patients.length > 0) {
                // Split patients: first 3 go to next patients, rest to waiting list
                const nextThree = displayData.next_patients.slice(0, 2);
                const remainingPatients = displayData.next_patients.slice(2);
                
                // Update next 3 patients
                nextPatientsListDiv.innerHTML = nextThree.map((patient, index) => `
                    <div class="queue-item bg-blue-100 border border-blue-300">
                        <div class="queue-number">${patient.queue_number}</div>
                        <div class="patient-info">
                            <div class="patient-name line-clamp-1">${patient.full_name}</div>
                        </div>
                    </div>
                `).join('');
                
                // Update remaining patients
                if (remainingPatients.length > 0) {
                    queueListDiv.innerHTML = remainingPatients.map((patient, index) => `
                        <div class="queue-item bg-gray-50 border border-gray-300">
                            <div class="queue-number">${patient.queue_number}</div>
                            <div class="patient-info">
                                <div class="patient-name line-clamp-1">${patient.full_name}</div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    queueListDiv.innerHTML = '<div class="no-patients">No additional patients waiting</div>';
                }
            } else {
                nextPatientsListDiv.innerHTML = '<div class="no-patients">No patients in queue</div>';
                queueListDiv.innerHTML = '<div class="no-patients">No patients in queue</div>';
            }
        }


        // Sound notification (optional)
        function playNotificationSound() {
            // Play a subtle notification sound when queue changes
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZURE');
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Could not play sound'));
        }

        // Announce patient name via Text-to-Speech
        function announcePatient(name) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                
                const utterance = new SpeechSynthesisUtterance('Patient ' + name + ', please proceed to the Doctor\'s Office');
                utterance.lang = 'en-US';
                utterance.rate = 0.9;
                utterance.pitch = 1.2;
                utterance.volume = 1;
                
                // Get available voices
                let voices = window.speechSynthesis.getVoices();
                
                // If voices not loaded yet, wait for them
                if (voices.length === 0) {
                    window.speechSynthesis.onvoiceschanged = () => {
                        voices = window.speechSynthesis.getVoices();
                        selectFemaleVoice(utterance, voices);
                        window.speechSynthesis.speak(utterance);
                    };
                } else {
                    selectFemaleVoice(utterance, voices);
                    window.speechSynthesis.speak(utterance);
                }
            }
        }
        
        function selectFemaleVoice(utterance, voices) {
            // Priority order for female voices
            const femaleVoiceNames = [
                'Microsoft Zira',
                'Google US English Female',
                'Samantha',
                'Victoria',
                'Karen',
                'Moira',
                'Tessa',
                'female',
                'woman'
            ];
            
            // Try to find a female voice by name
            for (const voiceName of femaleVoiceNames) {
                const voice = voices.find(v => 
                    v.lang.startsWith('en') && 
                    v.name.toLowerCase().includes(voiceName.toLowerCase())
                );
                if (voice) {
                    utterance.voice = voice;
                    console.log('Using voice:', voice.name);
                    return;
                }
            }
            
            // Fallback: use any English voice (increase pitch for more feminine sound)
            const englishVoice = voices.find(v => v.lang.startsWith('en'));
            if (englishVoice) {
                utterance.voice = englishVoice;
                utterance.pitch = 1.5;
                console.log('Using fallback voice with higher pitch:', englishVoice.name);
            }
        }

        // Check for changes and play sound
        let previousQueueCount = 0;
        let previousServingName = null;
        function checkForChanges() {
            if (displayData) {
                // Announce new serving patient via TTS
                const currentServingName = displayData.currently_serving ? displayData.currently_serving.full_name : null;
                if (currentServingName && currentServingName !== previousServingName) {
                    announcePatient(currentServingName);
                }
                previousServingName = currentServingName;

                // Play notification sound for new queue additions
                if (displayData.queue_count !== previousQueueCount) {
                    if (displayData.queue_count > previousQueueCount) {
                        playNotificationSound();
                    }
                    previousQueueCount = displayData.queue_count;
                }
            }
        }

        // Update the display function to check for changes
        const originalUpdateDisplay = updateDisplay;
        updateDisplay = function() {
            originalUpdateDisplay();
            checkForChanges();
        };

        // Keyboard shortcuts for testing
        document.addEventListener('keydown', function(e) {
            if (e.key === 'r' || e.key === 'R') {
                loadQueueData();
            }
        });

        // Fullscreen support
        document.addEventListener('dblclick', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        });
    </script>
    <script>window.qecDisplayStationId = 2;</script>
    <?php include __DIR__ . '/includes/queue-error-correction-display.php'; ?>
    <script>window.qrrDisplayStationId = 2;</script>
    <?php include __DIR__ . '/includes/queue-return-request-display.php'; ?>
</body>
</html>
