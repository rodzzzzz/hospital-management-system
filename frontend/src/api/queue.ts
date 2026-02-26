import client from "./client";

export async function listQueueStations() {
  const { data } = await client.get("/queue/list_stations.php");
  return data;
}

export async function callNext(stationId: number) {
  const { data } = await client.post("/queue/call_next.php", {
    station_id: stationId,
  });
  return data;
}

export async function transferPatient(payload: {
  queue_id: number;
  target_station_id: number;
  priority?: number;
}) {
  const { data } = await client.post("/queue/transfer.php", payload);
  return data;
}

export async function getQueueSettings() {
  const { data } = await client.get("/queue/settings.php");
  return data;
}
