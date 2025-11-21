#!/bin/bash
# Fix ICU library issue by creating symlink
cd /usr/local/opt/icu4c/lib
if [ -f libicuio.77.1.dylib ] && [ ! -f libicuio.73.dylib ]; then
    echo "Creating symlink for ICU 73 compatibility..."
    sudo ln -sf libicuio.77.1.dylib libicuio.73.dylib
    echo "Symlink created. Please run: php artisan migrate"
else
    echo "Library already exists or ICU files not found"
fi
