#!/bin/bash
set -e

# Install Composer if not available
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    COMPOSER_CMD="php composer.phar"
else
    COMPOSER_CMD="composer"
fi

# Install dependencies
echo "Installing dependencies..."
$COMPOSER_CMD install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo "Build completed successfully!"

