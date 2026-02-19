/**
 * Popup: login and Fill form. Server URL comes from extension config (default in background).
 * Shows logged-in state until session expires (2 days); 401 clears session and shows login again.
 */

const usernameEl = document.getElementById('username');
const passwordEl = document.getElementById('password');
const btnLogin = document.getElementById('btnLogin');
const btnLogout = document.getElementById('btnLogout');
const btnFill = document.getElementById('btnFill');
const statusEl = document.getElementById('status');
const viewLoggedIn = document.getElementById('viewLoggedIn');
const viewLogin = document.getElementById('viewLogin');
const loggedInUsernameEl = document.getElementById('loggedInUsername');

let effectiveServerUrl = '';

function showStatus(message, type = 'info') {
  if (statusEl) {
    statusEl.textContent = message;
    statusEl.className = `status ${type}`;
    statusEl.classList.remove('hidden');
  }
}

function hideStatus() {
  if (statusEl) statusEl.classList.add('hidden');
}

function setLoggedInState(loggedIn, username = '') {
  if (!viewLogin || !viewLoggedIn) return;
  if (loggedIn) {
    viewLogin.classList.add('hidden');
    viewLoggedIn.classList.remove('hidden');
    if (loggedInUsernameEl) loggedInUsernameEl.textContent = username || 'User';
  } else {
    viewLoggedIn.classList.add('hidden');
    viewLogin.classList.remove('hidden');
    if (passwordEl) passwordEl.value = '';
  }
}

function getCurrentTabId() {
  return chrome.tabs.query({ active: true, currentWindow: true }).then((tabs) => {
    if (tabs[0]) return tabs[0].id;
    return null;
  });
}

function sendToBackground(type, payload = {}) {
  return new Promise((resolve) => {
    chrome.runtime.sendMessage({ type, ...payload }, (response) => {
      if (chrome.runtime.lastError) {
        resolve({ error: chrome.runtime.lastError.message });
      } else {
        resolve(response || {});
      }
    });
  });
}

function isAllowedServerUrl(url) {
  try {
    const u = new URL(url);
    const origin = u.origin.toLowerCase();
    return origin.startsWith('http://localhost') || origin.startsWith('http://127.0.0.1');
  } catch {
    return false;
  }
}

async function loadConfig() {
  const { config } = await sendToBackground('GET_CONFIG');
  if (config) {
    effectiveServerUrl = config.serverUrl || '';
    if (usernameEl) usernameEl.value = config.username || '';
    if (config.token) {
      setLoggedInState(true, config.username);
    } else {
      setLoggedInState(false);
    }
  } else {
    setLoggedInState(false);
  }
}

async function saveConfigAndLogin() {
  const username = (usernameEl && usernameEl.value || '').trim();
  const password = (passwordEl && passwordEl.value) || '';
  const serverUrl = effectiveServerUrl.trim();

  if (!serverUrl || !isAllowedServerUrl(serverUrl)) {
    showStatus('Server URL is not set or not allowed.', 'error');
    return;
  }
  if (!username || !password) {
    showStatus('Enter username and password', 'error');
    return;
  }

  btnLogin.disabled = true;
  hideStatus();

  const base = serverUrl.replace(/\/$/, '');
  const res = await fetch(`${base}/api/auth/login.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ username, password }),
  });

  const data = await res.json().catch(() => ({}));

  if (!res.ok) {
    showStatus(data.error || `Login failed (${res.status})`, 'error');
    btnLogin.disabled = false;
    return;
  }

  if (!data.token) {
    showStatus('Server did not return a token; ensure API supports token login.', 'error');
    btnLogin.disabled = false;
    return;
  }

  await sendToBackground('SAVE_CONFIG', {
    serverUrl,
    token: data.token,
    username: data.user?.username ?? username,
  });
  effectiveServerUrl = serverUrl;
  setLoggedInState(true, data.user?.username ?? username);
  showStatus('Logged in. Session lasts 2 days.', 'success');
  btnLogin.disabled = false;
  if (passwordEl) passwordEl.value = '';
}

async function logout() {
  const { config } = await sendToBackground('GET_CONFIG');
  if (config?.token && config?.serverUrl) {
    const base = (config.serverUrl || '').replace(/\/$/, '');
    if (base) {
      try {
        await fetch(`${base}/api/auth/logout.php`, {
          method: 'POST',
          headers: { Authorization: `Bearer ${config.token}` },
        });
      } catch (_) {}
    }
  }
  await sendToBackground('SAVE_CONFIG', { token: '', username: '' });
  setLoggedInState(false);
  showStatus('Logged out', 'info');
}

async function fillForm() {
  hideStatus();
  btnFill.disabled = true;

  const result = await sendToBackground('GET_PROFILE');
  const { profile, error: getError, sessionExpired } = result;

  if (sessionExpired) {
    setLoggedInState(false);
    showStatus('Session expired. Please log in again.', 'error');
    btnFill.disabled = false;
    return;
  }

  if (getError || !profile) {
    showStatus(getError || 'Could not load profile', 'error');
    btnFill.disabled = false;
    return;
  }

  const tabId = await getCurrentTabId();
  if (!tabId) {
    showStatus('No active tab', 'error');
    btnFill.disabled = false;
    return;
  }

  const fillResult = await sendToBackground('FILL_FORM', { tabId, profile });
  if (fillResult.error) {
    showStatus(fillResult.error, 'error');
  } else {
    showStatus('Form filled successfully.', 'success');
  }
  btnFill.disabled = false;
}

function init() {
  if (!viewLogin || !viewLoggedIn) return;
  btnLogin.addEventListener('click', saveConfigAndLogin);
  btnLogout.addEventListener('click', logout);
  btnFill.addEventListener('click', fillForm);
  loadConfig();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
