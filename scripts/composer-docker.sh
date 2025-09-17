#!/usr/bin/env bash
set -euo pipefail

# Run Composer inside a Docker container with full PHP extensions (from laravelsail/php84-composer).
# This avoids host PHP extension issues like missing ext-xml or ext-dom.
#
# Usage examples:
#   scripts/composer-docker.sh install
#   scripts/composer-docker.sh update
#   scripts/composer-docker.sh require laravel/sail --dev
#   scripts/composer-docker.sh dump-autoload -o
#
# The current working directory is mounted as /app inside the container.

IMAGE="laravelsail/php84-composer:latest"
PROJECT_DIR="$(pwd)"

if ! command -v docker >/dev/null 2>&1; then
  echo "Error: Docker is required but not found. Please install Docker and try again." >&2
  exit 1
fi

exec docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "${PROJECT_DIR}:/app" -w /app \
  ${IMAGE} composer "$@"
