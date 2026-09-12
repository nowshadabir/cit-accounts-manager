<?php

/**
 * Script to generate high-quality PWA icons for CIT Accounts.
 */

$iconsDir = __DIR__ . '/../public/icons';
if (!is_dir($iconsDir)) {
    mkdir($iconsDir, 0755, true);
}

function createPwaIcon($size, $isMaskable = false, $outputPath = '') {
    $img = imagecreatetruecolor($size, $size);
    imagealphablending($img, false);
    imagesavealpha($img, true);

    // Transparent initial background
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagefill($img, 0, 0, $transparent);
    imagealphablending($img, true);

    // Primary Colors
    // Royal / Deep Blue gradient colors
    $blueTop = [37, 99, 235];    // #2563eb
    $blueBottom = [29, 78, 216]; // #1d4ed8
    $white = imagecolorallocate($img, 255, 255, 255);
    $lightBlue = imagecolorallocatealpha($img, 239, 246, 255, 20); // #eff6ff with alpha
    $darkAccent = imagecolorallocate($img, 15, 23, 42); // #0f172a

    // Background Shape
    if ($isMaskable) {
        // Maskable fills entire canvas with gradient
        for ($y = 0; $y < $size; $y++) {
            $ratio = $y / $size;
            $r = (int)($blueTop[0] + ($blueBottom[0] - $blueTop[0]) * $ratio);
            $g = (int)($blueTop[1] + ($blueBottom[1] - $blueTop[1]) * $ratio);
            $b = (int)($blueTop[2] + ($blueBottom[2] - $blueTop[2]) * $ratio);
            $lineColor = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $size, $y, $lineColor);
        }
        $safeSize = $size * 0.75;
        $offset = ($size - $safeSize) / 2;
    } else {
        // Standard Icon with rounded corners
        $radius = (int)($size * 0.22);
        // Draw rounded rectangle with gradient
        for ($y = 0; $y < $size; $y++) {
            $ratio = $y / $size;
            $r = (int)($blueTop[0] + ($blueBottom[0] - $blueTop[0]) * $ratio);
            $g = (int)($blueTop[1] + ($blueBottom[1] - $blueTop[1]) * $ratio);
            $b = (int)($blueTop[2] + ($blueBottom[2] - $blueTop[2]) * $ratio);
            $lineColor = imagecolorallocate($img, $r, $g, $b);
            
            // Apply rounded corner clipping
            for ($x = 0; $x < $size; $x++) {
                $inCorner = false;
                if ($x < $radius && $y < $radius) {
                    $dx = $radius - $x;
                    $dy = $radius - $y;
                    if ($dx*$dx + $dy*$dy > $radius*$radius) $inCorner = true;
                } elseif ($x >= $size - $radius && $y < $radius) {
                    $dx = $x - ($size - $radius - 1);
                    $dy = $radius - $y;
                    if ($dx*$dx + $dy*$dy > $radius*$radius) $inCorner = true;
                } elseif ($x < $radius && $y >= $size - $radius) {
                    $dx = $radius - $x;
                    $dy = $y - ($size - $radius - 1);
                    if ($dx*$dx + $dy*$dy > $radius*$radius) $inCorner = true;
                } elseif ($x >= $size - $radius && $y >= $size - $radius) {
                    $dx = $x - ($size - $radius - 1);
                    $dy = $y - ($size - $radius - 1);
                    if ($dx*$dx + $dy*$dy > $radius*$radius) $inCorner = true;
                }
                
                if (!$inCorner) {
                    imagesetpixel($img, $x, $y, $lineColor);
                }
            }
        }
        $safeSize = $size * 0.85;
        $offset = ($size - $safeSize) / 2;
    }

    // Draw Modern Financial Ledger / CIT Icon Symbol in Center
    $cx = $size / 2;
    $cy = $size / 2;
    
    // Draw outer subtle card/ledger outline
    $cardW = (int)($safeSize * 0.68);
    $cardH = (int)($safeSize * 0.68);
    $cardX1 = (int)($cx - $cardW / 2);
    $cardY1 = (int)($cy - $cardH / 2);
    $cardX2 = (int)($cx + $cardW / 2);
    $cardY2 = (int)($cy + $cardH / 2);
    $cardRadius = (int)($size * 0.05);

    // Inner glowing card background
    $innerCardColor = imagecolorallocatealpha($img, 255, 255, 255, 18);
    imagefilledrectangle($img, $cardX1, $cardY1, $cardX2, $cardY2, $innerCardColor);

    // Outer border of ledger
    $thick = max(2, (int)($size * 0.024));
    imagesetthickness($img, $thick);
    imagerectangle($img, $cardX1, $cardY1, $cardX2, $cardY2, $white);

    // 3 Horizontal Ledger Lines (Finance & Accounts representation)
    $line1Y = (int)($cardY1 + $cardH * 0.30);
    $line2Y = (int)($cardY1 + $cardH * 0.50);
    $line3Y = (int)($cardY1 + $cardH * 0.70);
    $lineLeft = (int)($cardX1 + $cardW * 0.22);
    $lineRight = (int)($cardX2 - $cardW * 0.22);

    imageline($img, $lineLeft, $line1Y, $lineRight, $line1Y, $white);
    imageline($img, $lineLeft, $line2Y, $lineRight, $line2Y, $white);
    imageline($img, $lineLeft, $line3Y, (int)($lineLeft + ($lineRight - $lineLeft) * 0.65), $line3Y, $white);

    // Little checkmark / upward growth badge on the top right
    $badgeRadius = (int)($size * 0.07);
    $badgeCx = (int)($cardX2 - $badgeRadius * 0.3);
    $badgeCy = (int)($cardY1 + $badgeRadius * 0.3);
    $emeraldGreen = imagecolorallocate($img, 16, 185, 129); // #10b981
    imagefilledellipse($img, $badgeCx, $badgeCy, $badgeRadius * 2, $badgeRadius * 2, $emeraldGreen);
    imageellipse($img, $badgeCx, $badgeCy, $badgeRadius * 2, $badgeRadius * 2, $white);

    // Upward arrow in the badge
    $arrThick = max(1, (int)($size * 0.015));
    imagesetthickness($img, $arrThick);
    imageline($img, (int)($badgeCx - $badgeRadius * 0.4), (int)($badgeCy + $badgeRadius * 0.2), $badgeCx, (int)($badgeCy - $badgeRadius * 0.3), $white);
    imageline($img, $badgeCx, (int)($badgeCy - $badgeRadius * 0.3), (int)($badgeCx + $badgeRadius * 0.4), (int)($badgeCy + $badgeRadius * 0.2), $white);

    imagepng($img, $outputPath, 9);
    imagedestroy($img);
    echo "Generated: {$outputPath}\n";
}

// Generate all standard icon variants
createPwaIcon(192, false, $iconsDir . '/icon-192x192.png');
createPwaIcon(512, false, $iconsDir . '/icon-512x512.png');
createPwaIcon(192, true, $iconsDir . '/icon-maskable-192x192.png');
createPwaIcon(512, true, $iconsDir . '/icon-maskable-512x512.png');
createPwaIcon(180, false, $iconsDir . '/apple-touch-icon.png');
createPwaIcon(72, false, $iconsDir . '/badge-72x72.png');
createPwaIcon(64, false, $iconsDir . '/favicon-64x64.png');
createPwaIcon(32, false, $iconsDir . '/favicon-32x32.png');
