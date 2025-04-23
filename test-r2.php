<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Storage;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Print R2 configuration
echo "R2 Configuration:\n";
echo "AWS_ACCESS_KEY_ID: " . $_ENV['AWS_ACCESS_KEY_ID'] . "\n";
echo "AWS_BUCKET: " . $_ENV['AWS_BUCKET'] . "\n";
echo "AWS_URL: " . $_ENV['AWS_URL'] . "\n";
echo "AWS_ENDPOINT: " . $_ENV['AWS_ENDPOINT'] . "\n\n";

// Test URL construction
$bucket = $_ENV['AWS_BUCKET'];
$endpoint = $_ENV['AWS_URL'];

if (empty($endpoint)) {
    // Fallback to constructing URL from endpoint if URL is not set
    $accountId = explode('.', $_ENV['AWS_ENDPOINT'])[1];
    $endpoint = "https://{$bucket}.{$accountId}.r2.cloudflarestorage.com";
}

$testPath = "school_logos/test.jpg";
$directUrl = "{$endpoint}/{$testPath}";

echo "Direct URL construction test:\n";
echo "Test path: {$testPath}\n";
echo "Constructed URL: {$directUrl}\n";
