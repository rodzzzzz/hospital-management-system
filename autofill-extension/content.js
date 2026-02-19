/**
 * Content script: receives profile from background and fills form fields using heuristics.
 */

const PROFILE_FIELD_MATCHES = {
  name: [
    'name', 'fullname', 'full_name', 'full-name', 'username', 'displayname',
    'your name', 'customer name', 'contact name', 'firstname', 'first_name',
    'lastname', 'last_name', 'fname', 'lname',
  ],
  email: [
    'email', 'e-mail', 'emailaddress', 'email_address', 'mail', 'user_email',
    'your email', 'contact email',
  ],
  phone: [
    'phone', 'telephone', 'tel', 'mobile', 'cell', 'phonenumber', 'phone_number',
    'your phone', 'contact phone',
  ],
  address_line1: [
    'address', 'address1', 'address_line1', 'street', 'street1', 'addr',
    'address line 1', 'street address',
  ],
  address_line2: [
    'address2', 'address_line2', 'street2', 'apt', 'apartment', 'suite', 'unit',
    'address line 2',
  ],
  city: ['city', 'town', 'locality'],
  state: ['state', 'region', 'province', 'county'],
  postal_code: ['zip', 'zipcode', 'postal', 'postalcode', 'postcode', 'postal_code'],
  country: ['country', 'countrycode'],
};

function getFieldSignatures(input) {
  const tag = (input.tagName || '').toLowerCase();
  if (tag !== 'input' && tag !== 'textarea' && tag !== 'select') return [];
  const name = (input.getAttribute('name') || input.name || '').toLowerCase().replace(/[^a-z0-9_]/g, '');
  const id = (input.getAttribute('id') || input.id || '').toLowerCase().replace(/[^a-z0-9_]/g, '');
  const placeholder = (input.getAttribute('placeholder') || '').toLowerCase().replace(/[^a-z0-9_]/g, '');
  const ariaLabel = (input.getAttribute('aria-label') || '').toLowerCase().replace(/[^a-z0-9_]/g, '');
  const type = (input.getAttribute('type') || input.type || 'text').toLowerCase();
  const signatures = [name, id, placeholder, ariaLabel].filter(Boolean);
  if (type === 'email') signatures.push('email');
  if (type === 'tel') signatures.push('phone', 'tel');
  return [...new Set(signatures)];
}

function scoreFieldForProfileKey(signatures, profileKey) {
  const matches = PROFILE_FIELD_MATCHES[profileKey];
  if (!matches) return 0;
  for (const sig of signatures) {
    if (!sig) continue;
    for (const m of matches) {
      if (sig === m || sig.includes(m) || m.includes(sig)) return 2;
      if (sig.replace(/_/g, '') === m.replace(/_/g, '')) return 2;
    }
  }
  return 0;
}

function findBestProfileKey(signatures, profile) {
  let bestKey = null;
  let bestScore = 0;
  for (const key of Object.keys(PROFILE_FIELD_MATCHES)) {
    if (profile[key] == null || profile[key] === '') continue;
    const score = scoreFieldForProfileKey(signatures, key);
    if (score > bestScore) {
      bestScore = score;
      bestKey = key;
    }
  }
  return bestKey;
}

function setNativeValue(el, value) {
  const proto = Object.getPrototypeOf(el);
  const setter = Object.getOwnPropertyDescriptor(proto, 'value')?.set;
  if (setter) {
    setter.call(el, value);
  } else {
    el.value = value;
  }
}

function fillInput(input, value) {
  if (input.disabled || input.readOnly) return;
  setNativeValue(input, value);
  input.dispatchEvent(new Event('input', { bubbles: true }));
  input.dispatchEvent(new Event('change', { bubbles: true }));
}

function fillSelect(select, value) {
  if (select.disabled) return;
  const options = Array.from(select.options);
  const normalized = String(value).toLowerCase().trim();
  for (const opt of options) {
    if (opt.value.toLowerCase().trim() === normalized ||
        (opt.text || '').toLowerCase().trim() === normalized) {
      select.value = opt.value;
      select.dispatchEvent(new Event('change', { bubbles: true }));
      return;
    }
  }
  if (options.some((o) => o.value === value)) {
    select.value = value;
    select.dispatchEvent(new Event('change', { bubbles: true }));
  }
}

function fillFormWithProfile(profile) {
  const filled = new Set();
  const inputs = document.querySelectorAll('input:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="image"]), textarea, select');
  let count = 0;
  for (const input of inputs) {
    const signatures = getFieldSignatures(input);
    const key = findBestProfileKey(signatures, profile);
    if (!key || filled.has(key)) continue;
    const value = profile[key];
    if (value == null || value === '') continue;
    const tag = (input.tagName || '').toLowerCase();
    if (tag === 'select') {
      fillSelect(input, value);
    } else {
      fillInput(input, value);
    }
    filled.add(key);
    count += 1;
  }
  return count;
}

chrome.runtime.onMessage.addListener((message, _sender, sendResponse) => {
  if (message.type === 'FILL_FORM' && message.profile) {
    const count = fillFormWithProfile(message.profile);
    sendResponse({ ok: true, filled: count });
  }
});
