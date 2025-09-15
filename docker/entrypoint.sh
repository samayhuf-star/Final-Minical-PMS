#!/usr/bin/env sh
set -e

# Ensure vendor autoload exists
if [ ! -f /app/vendor/autoload.php ]; then
  echo "Installing composer dependencies..."
  composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
fi

# Render .env if missing from environment variables
if [ ! -f /app/.env ]; then
  echo "Generating .env from environment variables..."
  cat > /app/.env <<EOF
COMPOSE_PROJECT_NAME=minical

DATABASE_HOST='${DATABASE_HOST:-db}'
DATABASE_USER='${DATABASE_USER:-root}'
DATABASE_PASS='${DATABASE_PASS:-MiniCalPwd}'
DATABASE_NAME='${DATABASE_NAME:-minical}'

ENVIRONMENT='${ENVIRONMENT:-production}'

PROJECT_URL='${PROJECT_URL:-http://localhost/public}'
API_URL='${API_URL:-http://localhost/api}'

AWS_ACCESS_KEY='${AWS_ACCESS_KEY:-}'
AWS_SECRET_KEY='${AWS_SECRET_KEY:-}'
AWS_S3_BUCKET='${AWS_S3_BUCKET:-}'

SMTP_USER='${SMTP_USER:-}'
SMTP_PASS='${SMTP_PASS:-}'

RECAPTCHA_SITE_KEY='${RECAPTCHA_SITE_KEY:-}'
RECAPTCHA_SECRET_KEY='${RECAPTCHA_SECRET_KEY:-}'
EOF
fi

exec "$@"

