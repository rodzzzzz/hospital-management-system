# Hospital Management System — Frontend

React + TypeScript + Vite frontend for the Hospital Management System.

## Prerequisites

- Node.js 22.12+
- XAMPP running with Apache + MySQL (backend API at `http://localhost/hospital/api/`)
- Database `TTSI_auto` imported

## Development

```bash
cd frontend
npm install
npm run dev
```

Opens at `http://localhost:5173`. API calls are proxied to `http://localhost/hospital/api/` via Vite.

## Production Build

```bash
npm run build
```

Output in `frontend/dist/`. Serve with any static server or use Electron/Tauri.

## Desktop (Electron)

```bash
cd desktop/electron
npm install
npm start                # dev mode (requires frontend dev server running)
npm run dist:win         # build Windows exe
npm run dist:mac         # build macOS dmg
```

## Desktop (Tauri)

Requires Rust toolchain. See [Tauri prerequisites](https://tauri.app/start/prerequisites/).

```bash
cd desktop/tauri
cargo tauri dev          # dev mode
cargo tauri build        # production build
```

## Architecture

- `src/api/` — Axios client + endpoint wrappers per module
- `src/auth/` — AuthContext, AuthProvider, useAuth hook, ProtectedRoute
- `src/rbac/` — useRBAC hook, RequireModule, RequireRole guard components
- `src/layouts/` — AppLayout (sidebar+header), AuthLayout (login)
- `src/pages/` — One folder per module, lazy-loaded via React Router
- `src/types/` — TypeScript interfaces matching backend DB schema
