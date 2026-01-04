#!/bin/sh
set -e

if [ "$CF_PURGE_ON_START" = "true" ]; then
  echo "🔄 Purging Cloudflare cache..."
  curl -s -X POST \
    "https://api.cloudflare.com/client/v4/zones/${CF_ZONE_ID}/purge_cache" \
    -H "Authorization: Bearer ${CF_API_TOKEN}" \
    -H "Content-Type: application/json" \
    --data '{"purge_everything":true}'
  echo "✅ Purge done"
fi

echo "🚀 Starting Cloudflare Tunnel..."
exec cloudflared tunnel --no-autoupdate run
