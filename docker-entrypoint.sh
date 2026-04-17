#!/bin/sh
set -e

# Use Railway's PORT or default to 8000
export PORT=${PORT:-8000}

# Update nginx.conf with the correct port
sed -i "s/listen 8000/listen ${PORT}/g" /etc/nginx/nginx.conf
sed -i "s/listen \[\:\:\]:8000/listen [::]:${PORT}/g" /etc/nginx/nginx.conf

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
