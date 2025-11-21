# Fix ICU Library Issue

PHP 8.2.10 was compiled against ICU 73, but your system has ICU 77.1 installed.

## Solution: Create a symlink

Run this command in your terminal (it will ask for your password):

```bash
sudo ln -sf /usr/local/opt/icu4c/lib/libicuio.77.1.dylib /usr/local/opt/icu4c/lib/libicuio.73.dylib
```

Then verify PHP works:
```bash
php -v
php artisan migrate:status
```

## Alternative: Reinstall PHP (takes longer)

```bash
brew uninstall php
brew install php@8.2
brew link php@8.2
```

## Alternative: Upgrade PHP to 8.3 or 8.4 (supports ICU 77)

```bash
brew install php
brew link php
```
