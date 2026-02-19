/**
 * Service worker: fetches profile from local server, stores token/URL, relays to content script.
 * Set DEFAULT_SERVER_URL for your environment (or replace via build step / env when packaging).
 */
const DEFAULT_SERVER_URL = 'http://localhost:8080';

const ALLOWED_SERVER_PATTERNS = [
  /^http:\/\/localhost(:\d+)?\/?$/,
  /^http:\/\/127\.0\.0\.1(:\d+)?\/?$/,
];

function isAllowedServerUrl(url) {
  try {
    const u = new URL(url);
    const origin = u.origin + '/';
    return ALLOWED_SERVER_PATTERNS.some((re) => re.test(origin));
  } catch {
    return false;
  }
}

function normalizeServerUrl(url) {
  try {
    const u = new URL(url);
    u.pathname = u.pathname.replace(/\/+$/, '') || '/';
    return u.origin + u.pathname;
  } catch {
    return null;
  }
}

chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
  if (message.type === 'GET_PROFILE') {
    handleGetProfile()
      .then((result) => sendResponse(result))
      .catch((err) => sendResponse({ error: err?.message || 'Failed to get profile' }));
    return true;
  }
  if (message.type === 'SAVE_CONFIG') {
    const payload = message.payload != null ? message.payload : { serverUrl: message.serverUrl, token: message.token, username: message.username };
    handleSaveConfig(payload)
      .then(() => sendResponse({ ok: true }))
      .catch((err) => sendResponse({ error: err?.message || 'Failed to save config' }));
    return true;
  }
  if (message.type === 'GET_CONFIG') {
    handleGetConfig()
      .then((config) => sendResponse({ config }))
      .catch((err) => sendResponse({ error: err?.message || 'Failed to get config' }));
    return true;
  }
  if (message.type === 'FILL_FORM') {
    const tabId = message.tabId ?? sender.tab?.id;
    handleFillForm(tabId, message.profile)
      .then((result) => sendResponse(result))
      .catch((err) => sendResponse({ error: err?.message || 'Fill failed' }));
    return true;
  }
});

async function handleGetConfig() {
  const out = await chrome.storage.local.get(['serverUrl', 'token', 'username']);
  return {
    serverUrl: out.serverUrl || DEFAULT_SERVER_URL,
    token: out.token || '',
    username: out.username || '',
  };
}

async function handleSaveConfig(payload) {
  const { serverUrl, token, username } = payload || {};
  if (serverUrl !== undefined) {
    const normalized = normalizeServerUrl(serverUrl);
    if (normalized && !isAllowedServerUrl(normalized)) {
      throw new Error('Server URL not allowed. Use http://localhost or http://127.0.0.1');
    }
    await chrome.storage.local.set({
      serverUrl: normalized || serverUrl || '',
      token: token !== undefined ? token : (await chrome.storage.local.get('token')).token,
      username: username !== undefined ? username : (await chrome.storage.local.get('username')).username,
    });
  } else if (token !== undefined || username !== undefined) {
    const current = await chrome.storage.local.get(['token', 'username']);
    await chrome.storage.local.set({
      token: token !== undefined ? token : current.token,
      username: username !== undefined ? username : current.username,
    });
  }
}

async function handleGetProfile() {
  const out = await chrome.storage.local.get(['serverUrl', 'token', 'username']);
  const serverUrl = out.serverUrl || DEFAULT_SERVER_URL;
  const token = out.token;
  if (!token) {
    return { error: 'Not logged in. Log in in the popup.' };
  }
  if (!isAllowedServerUrl(serverUrl)) {
    return { error: 'Server URL not allowed' };
  }
  const base = serverUrl.replace(/\/$/, '');
  const url = `${base}/api/autofill/profile.php`;
  const res = await fetch(url, {
    method: 'GET',
    headers: { Authorization: `Bearer ${token}` },
  });
  if (res.status === 401) {
    await chrome.storage.local.set({ token: '', username: '' });
    return { error: 'Session expired. Please log in again.', sessionExpired: true };
  }
  if (!res.ok) {
    const body = await res.text();
    let errMsg = `Server returned ${res.status}`;
    try {
      const j = JSON.parse(body);
      if (j.error) errMsg = j.error;
    } catch (_) {}
    return { error: errMsg };
  }
  const profile = await res.json();
  return { profile };
}

async function handleFillForm(tabId, profile) {
  if (!tabId || !profile) {
    return { error: 'Missing tab or profile' };
  }
  try {
    await chrome.tabs.sendMessage(tabId, { type: 'FILL_FORM', profile });
    return { ok: true };
  } catch (err) {
    return { error: 'Could not reach page. Try refreshing and click Fill again.' };
  }
}
