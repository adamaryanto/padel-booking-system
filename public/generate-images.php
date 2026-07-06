<?php
// Script to generate mock images for courts and landing page using GD library

$courtsDir = __DIR__ . '/../storage/app/public/courts';
$landingDir = __DIR__ . '/../storage/app/public/landing';

if (!file_exists($courtsDir)) {
    mkdir($courtsDir, 0755, true);
}
if (!file_exists($landingDir)) {
    mkdir($landingDir, 0755, true);
}

function generatePlaceholderImage($width, $height, $text, $backgroundColorHex, $outputPath) {
    // Create image
    $im = imagecreatetruecolor($width, $height);
    
    // Parse hex color
    hex2rgb($backgroundColorHex, $r, $g, $b);
    $bgColor = imagecolorallocate($im, $r, $g, $b);
    imagefill($im, 0, 0, $bgColor);
    
    // Grid lines for court effect
    $gridColor = imagecolorallocate($im, 255, 255, 255);
    imagesetthickness($im, 2);
    // Draw court border
    imagerectangle($im, 20, 20, $width - 20, $height - 20, $gridColor);
    // Draw net (center line)
    imageline($im, $width / 2, 20, $width / 2, $height - 20, $gridColor);
    
    // Draw text using built-in font
    $textColor = imagecolorallocate($im, 255, 255, 255);
    $font = 5; // Use built-in font size 5 (largest built-in font)
    
    // Calculate center position
    $textWidth = imagefontwidth($font) * strlen($text);
    $textHeight = imagefontheight($font);
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2;
    
    imagestring($im, $font, $x, $y, $text, $textColor);
    
    // Save image
    imagepng($im, $outputPath);
    imagedestroy($im);
    echo "Generated: " . basename($outputPath) . "\n";
}

function hex2rgb($hex, &$r, &$g, &$b) {
    $hex = str_replace("#", "", $hex);
    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
}

// Generate court images (800x600)
generatePlaceholderImage(800, 600, "OUTDOOR COURT", "1b4332", $courtsDir . '/court_outdoor.png');
generatePlaceholderImage(800, 600, "INDOOR COURT", "0f4c5c", $courtsDir . '/court_indoor.png');
generatePlaceholderImage(800, 600, "PANORAMIC COURT", "5f0f40", $courtsDir . '/court_panoramic.png');

// Generate landing images (1200x800)
generatePlaceholderImage(1200, 800, "PADELHUB HERO IMAGE", "111827", $landingDir . '/hero.png');
generatePlaceholderImage(1200, 800, "ABOUT PADELHUB", "1f2937", $landingDir . '/about.png');

echo "All mock images generated successfully!\n";
