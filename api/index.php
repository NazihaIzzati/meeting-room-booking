<?php
// Handle static files before bootstrapping Laravel
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Check if it's a static file request
$staticFile = __DIR__ . '/../public' . $requestPath;
if (file_exists($staticFile) && is_file($staticFile)) {
    $mimeTypes = [
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'json' => 'application/json',
    ];
    
    $ext = strtolower(pathinfo($staticFile, PATHINFO_EXTENSION));
    $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';
    
    header('Content-Type: ' . $mimeType);
    header('Cache-Control: public, max-age=31536000, immutable');
    readfile($staticFile);
    exit;
}

// Otherwise, bootstrap Laravel
require __DIR__ . '/../public/index.php';
