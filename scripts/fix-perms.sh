#!/usr/bin/env bash
set -euo pipefail

# Usage: ./scripts/fix-perms.sh [UID] [GID]
# Defaults to current host user's UID/GID.
UID_TARGET=${1:-$(id -u)}
GID_TARGET=${2:-$(id -g)}

printf "Fixing ownership to %s:%s ...\n" "$UID_TARGET" "$GID_TARGET"

# Re-own project files (skips docker named volumes)
sudo chown -R "$UID_TARGET":"$GID_TARGET" .

# Ensure writable Laravel dirs
sudo find storage -type d -exec chmod 775 {} + 2>/dev/null || true
sudo find storage -type f -exec chmod 664 {} + 2>/dev/null || true
sudo find bootstrap/cache -type d -exec chmod 775 {} + 2>/dev/null || true
sudo find bootstrap/cache -type f -exec chmod 664 {} + 2>/dev/null || true

# Common occasional lock files
[ -f storage/logs/laravel.log ] && sudo chmod 664 storage/logs/laravel.log || true

printf "Done.\n"
