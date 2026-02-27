<?php
declare(strict_types=1);

/**
 * Broadcast event to WebSocket server
 * 
 * @param string $type Event type (e.g., 'queue_update', 'chat_message')
 * @param string $event Specific event (e.g., 'call-next', 'message-sent')
 * @param array $rooms Array of room names to broadcast to (e.g., ['queue-2', 'global'])
 * @param array $payload Data to send with the event
 * @return bool Success status
 */
function broadcastWebSocketEvent(string $type, string $event, array $rooms, array $payload): bool
{
    $wsServerUrl = getenv('WS_BROADCAST_URL') ?: 'http://127.0.0.1:8081/broadcast';
    
    $data = [
        'type' => $type,
        'event' => $event,
        'rooms' => $rooms,
        'payload' => $payload
    ];
    
    $ch = curl_init($wsServerUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 1000);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        return true;
    }
    
    error_log("WebSocket broadcast failed: HTTP $httpCode, Event: $event");
    return false;
}

/**
 * Broadcast queue update to affected stations
 * 
 * @param string $event Queue event type
 * @param array $stationIds Array of affected station IDs
 * @param array $data Queue data
 */
function broadcastQueueUpdate(string $event, array $stationIds, array $data = []): void
{
    $rooms = ['global'];
    
    foreach ($stationIds as $stationId) {
        $rooms[] = "queue-{$stationId}";
    }
    
    broadcastWebSocketEvent('queue_update', $event, $rooms, $data);
}

/**
 * Broadcast chat message
 * 
 * @param int $departmentId Department ID
 * @param array $message Message data
 */
function broadcastChatMessage(int $departmentId, array $message): void
{
    $rooms = ["chat-{$departmentId}", 'chat-global'];
    broadcastWebSocketEvent('chat_message', 'new_message', $rooms, $message);
}

/**
 * Broadcast queue error correction alert
 * 
 * @param int $wrongStationId Station that received wrong patient
 * @param array $correction Correction data
 */
function broadcastCorrectionAlert(int $wrongStationId, array $correction): void
{
    $rooms = ["queue-{$wrongStationId}"];
    broadcastWebSocketEvent('correction_alert', 'new_correction', $rooms, $correction);
}

/**
 * Broadcast queue return request alert (Reverse QEC)
 * Notifies the origin station (Station A) that a receiving station filed a return request
 * 
 * @param int $originStationId Station that originally sent the patient
 * @param array $requestData Return request data
 */
function broadcastReturnRequestAlert(int $originStationId, array $requestData): void
{
    $rooms = ["queue-{$originStationId}"];
    broadcastWebSocketEvent('return_request_alert', 'new_return_request', $rooms, $requestData);
}

/**
 * Broadcast queue return request response (confirm/reject)
 * Notifies the requesting station (Station B) of the origin station's decision
 * 
 * @param int $requestingStationId Station that filed the return request
 * @param array $responseData Response data including type (confirmed/rejected)
 */
function broadcastReturnResponse(int $requestingStationId, array $responseData): void
{
    $rooms = ["queue-{$requestingStationId}"];
    broadcastWebSocketEvent('return_request_response', 'return_response', $rooms, $responseData);
}

/**
 * Broadcast acknowledgement that Station B has seen a rejection.
 * Notifies Station A (origin station).
 *
 * @param int $originStationId Station that rejected the return request
 * @param array $ackData Acknowledgement payload
 */
function broadcastReturnRejectionAcknowledged(int $originStationId, array $ackData): void
{
    $rooms = ["queue-{$originStationId}"];
    broadcastWebSocketEvent('return_request_ack', 'rejection_acknowledged', $rooms, $ackData);
}
