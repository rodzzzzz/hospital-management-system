<script>
/**
 * Hospital WebSocket Client
 * 
 * Provides window.HospitalWS with:
 *   .subscribe(room)
 *   .unsubscribe(room)
 *   .on(eventType, callback)    — callback receives { type, event, data, timestamp }
 *   .off(eventType, callback)
 *   .isConnected()
 *
 * Automatically reconnects with exponential backoff.
 * Falls back to polling callbacks if WS stays disconnected.
 */
(function () {
    'use strict';

    var wsUrl = window.WS_URL || 'ws://localhost:8080';
    var ws = null;
    var rooms = new Set();
    var listeners = {};           // eventType -> Set<callback>
    var reconnectDelay = 1000;
    var maxReconnectDelay = 30000;
    var reconnectTimer = null;
    var connected = false;
    var intentionalClose = false;

    // Fallback polling support
    var fallbackIntervals = {};   // room -> intervalId
    var fallbackCallbacks = {};   // room -> function
    var FALLBACK_POLL_MS = 8000;

    function connect() {
        if (ws && (ws.readyState === WebSocket.CONNECTING || ws.readyState === WebSocket.OPEN)) {
            return;
        }

        try {
            ws = new WebSocket(wsUrl);
        } catch (e) {
            console.warn('[HospitalWS] Failed to create WebSocket:', e);
            scheduleReconnect();
            return;
        }

        ws.onopen = function () {
            console.log('[HospitalWS] Connected');
            connected = true;
            reconnectDelay = 1000;
            stopAllFallbackPolling();

            // Resubscribe to all rooms
            rooms.forEach(function (room) {
                ws.send(JSON.stringify({ type: 'subscribe', room: room }));
            });
        };

        ws.onmessage = function (evt) {
            try {
                var msg = JSON.parse(evt.data);
                if (msg.type === 'pong' || msg.type === 'connected' || msg.type === 'subscribed' || msg.type === 'unsubscribed') {
                    return;
                }
                dispatch(msg);
            } catch (e) {
                // ignore parse errors
            }
        };

        ws.onclose = function () {
            connected = false;
            ws = null;
            if (!intentionalClose) {
                console.log('[HospitalWS] Disconnected, reconnecting in', reconnectDelay, 'ms');
                startFallbackPolling();
                scheduleReconnect();
            }
        };

        ws.onerror = function () {
            // onclose will fire after this
        };
    }

    function scheduleReconnect() {
        if (reconnectTimer) return;
        reconnectTimer = setTimeout(function () {
            reconnectTimer = null;
            reconnectDelay = Math.min(reconnectDelay * 2, maxReconnectDelay);
            connect();
        }, reconnectDelay);
    }

    function dispatch(msg) {
        var type = msg.type || '';
        var event = msg.event || '';

        // Dispatch to type listeners
        if (type && listeners[type]) {
            listeners[type].forEach(function (cb) {
                try { cb(msg); } catch (e) { console.error('[HospitalWS] Listener error:', e); }
            });
        }

        // Dispatch to "type:event" listeners (e.g. "queue_update:call-next")
        var compound = type + ':' + event;
        if (event && listeners[compound]) {
            listeners[compound].forEach(function (cb) {
                try { cb(msg); } catch (e) { console.error('[HospitalWS] Listener error:', e); }
            });
        }

        // Dispatch to wildcard listeners
        if (listeners['*']) {
            listeners['*'].forEach(function (cb) {
                try { cb(msg); } catch (e) { console.error('[HospitalWS] Listener error:', e); }
            });
        }
    }

    // Fallback polling: when WS is down, periodically fire a synthetic event
    // so pages can re-fetch data the old-fashioned way
    function startFallbackPolling() {
        rooms.forEach(function (room) {
            if (fallbackIntervals[room]) return;
            fallbackIntervals[room] = setInterval(function () {
                dispatch({
                    type: 'fallback_poll',
                    event: 'poll',
                    data: { room: room },
                    timestamp: new Date().toISOString()
                });
            }, FALLBACK_POLL_MS);
        });
    }

    function stopAllFallbackPolling() {
        Object.keys(fallbackIntervals).forEach(function (room) {
            clearInterval(fallbackIntervals[room]);
        });
        fallbackIntervals = {};
    }

    // Public API
    window.HospitalWS = {
        subscribe: function (room) {
            rooms.add(room);
            if (connected && ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ type: 'subscribe', room: room }));
            }
        },

        unsubscribe: function (room) {
            rooms.delete(room);
            if (connected && ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ type: 'unsubscribe', room: room }));
            }
            if (fallbackIntervals[room]) {
                clearInterval(fallbackIntervals[room]);
                delete fallbackIntervals[room];
            }
        },

        on: function (eventType, callback) {
            if (!listeners[eventType]) {
                listeners[eventType] = new Set();
            }
            listeners[eventType].add(callback);
        },

        off: function (eventType, callback) {
            if (listeners[eventType]) {
                listeners[eventType].delete(callback);
            }
        },

        isConnected: function () {
            return connected;
        },

        disconnect: function () {
            intentionalClose = true;
            stopAllFallbackPolling();
            if (reconnectTimer) {
                clearTimeout(reconnectTimer);
                reconnectTimer = null;
            }
            if (ws) {
                ws.close();
                ws = null;
            }
            connected = false;
        }
    };

    // Auto-connect on load
    connect();
})();
</script>
