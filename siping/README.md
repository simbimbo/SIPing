# SIPing (legacy) – ingestion + Python port staging

This folder contains the **legacy SIPing** codebase pulled from Steven’s archived `~/Desktop/rblock` dump.

## What’s what

- `legacy/agent/`
  - Polling/agent component (Perl + a Python variant) that connects to the server and runs probes.
- `legacy/server/`
  - Server-side listeners + CGI endpoints (Perl/CGI) + graphing + alert logic.
- `legacy/config-ui/`
  - A PHPMaker-generated web UI for managing config/DB tables (includes bundled libs like CKEditor, dompdf, etc.).
- `legacy/scripts/`
  - Deployment-era copies/backups of server/alert scripts, plus misc helper bits (some duplicates).

## Sensitive data handling

The original dump included **hardcoded DB credentials and internal IPs**.

- Password literals were removed from tracked files.
- DB password values now come from env vars (e.g., `SIPING_DB_PASS`) or are blank in PHP config.
- The raw snapshot is intentionally excluded from git: `siping/raw/` (see `.gitignore`).

If you need to run any of this legacy code (not recommended), set env vars like:

```bash
export SIPING_DB_HOST=127.0.0.1
export SIPING_DB_PORT=3306
export SIPING_DB_USER=siping_master
export SIPING_DB_PASS='...'
export SIPING_DB_NAME=siping
```

## Next intended step

Create a clean Python target layout under `siping/` (e.g. `siping/` or `siping/src/`) and treat `legacy/` as reference-only while we port.
