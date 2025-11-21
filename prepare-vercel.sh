#!/bin/bash
# Script to prepare Laravel project for Vercel deployment
# This commits the vendor directory which is required for Vercel

set -e

echo "🚀 Preparing Laravel project for Vercel deployment..."

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
fi

# Remove vendor from .gitignore
echo "📝 Updating .gitignore..."
if grep -q "^/vendor$" .gitignore; then
    # Create backup
    cp .gitignore .gitignore.backup
    # Remove vendor line
    sed -i.bak '/^\/vendor$/d' .gitignore
    echo "✅ Removed /vendor from .gitignore"
else
    echo "⚠️  /vendor not found in .gitignore (may already be committed)"
fi

# Add vendor to git
echo "📦 Adding vendor directory to git..."
git add vendor/ .gitignore

# Check if there are changes
if git diff --staged --quiet; then
    echo "ℹ️  No changes to commit (vendor may already be committed)"
else
    echo "💾 Committing vendor directory..."
    git commit -m "Add vendor directory for Vercel deployment" || echo "⚠️  Nothing to commit"
fi

echo ""
echo "✅ Preparation complete!"
echo ""
echo "Next steps:"
echo "1. Push to GitHub: git push"
echo "2. Deploy on Vercel dashboard"
echo ""
echo "Note: The vendor directory is now committed. This is required for Vercel."
echo "      Consider using Railway or Render for better Laravel support."

