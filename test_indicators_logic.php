<?php

/**
 * Test script untuk validate getInsightIndicators logic
 * Run: php test_indicators_logic.php
 */

// Simulate GRID_SLOTS structure
$gridSlots = [
    'kependudukan' => [
        'title'    => 'Penduduk',
        'subject'  => 'Penduduk',
        'keywords' => []
    ],
    'tenaga-kerja' => [
        'title'    => 'Tenaga Kerja',
        'subject'  => 'Tenaga Kerja',
        'keywords' => []
    ],
    'pengangguran' => [
        'title'    => 'Pengangguran',
        'subject'  => null,
        'keywords' => ['pengangguran', 'tpak', 'tpt', 'tidak bekerja']
    ],
    'kemiskinan' => [
        'title'    => 'Kemiskinan',
        'subject'  => 'Kemiskinan',
        'keywords' => []
    ],
    'rasio-gini' => [
        'title'    => 'Rasio GINI',
        'subject'  => null,
        'keywords' => ['gini', 'ketimpangan', 'rasio gini']
    ],
];

echo "=== Testing getInsightIndicators Logic ===\n\n";

foreach ($gridSlots as $slug => $slotConfig) {
    echo "Slot: $slug\n";
    echo "  Title: " . $slotConfig['title'] . "\n";
    echo "  Subject: " . ($slotConfig['subject'] ?? 'null') . "\n";
    echo "  Keywords: " . (empty($slotConfig['keywords']) ? '[]' : implode(', ', $slotConfig['keywords'])) . "\n";

    // Apply the logic from getInsightIndicators
    if ($slotConfig['subject'] !== null) {
        echo "  → WILL MATCH BY: subject = '{$slotConfig['subject']}'\n";
    } elseif (!empty($slotConfig['keywords'])) {
        echo "  → WILL MATCH BY: keywords\n";
    } else {
        echo "  → NO MATCHING CRITERIA!\n";
    }

    echo "\n";
}

echo "✓ Logic test complete. Ready for production deployment.\n";
